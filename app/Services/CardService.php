<?php

namespace App\Services;

// use App\Repositories\CardRepositoryInterface as CardRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Mail\RegisterCardMail;
use App\Mail\ReRegisterCardMail;
use App\Mail\ChargeSuccessMail;
use App\Mail\ChargeFailedMail;
use Illuminate\Support\Facades\Mail;

class CardService
{
    // public function __construct(CardRepository $cardRepository)
    public function __construct()
    {
        $this->coachRepository = app()->make('App\Repositories\CoachRepositoryInterface');
        $this->reservationRepository = app()->make('App\Repositories\ReservationRepositoryInterface');

        $this->login = session()->get('login');
        $this->date = date('Y-m-d');

        // 開発環境は山崎にメール送信
        if(config('rentacoach.to.address'))
        {
            $this->to = config('rentacoach.to.address');
        }
        else
        {
            $this->to = $this->login['id'];
        }
    }

    public function regist($payjp_token)
    {
        DB::beginTransaction();

        $login = session()->get('login');
        \Payjp\Payjp::setApiKey(config('rentacoach.payjp.secret_key'));

        // 顧客作成
        $customer = \Payjp\Customer::create([
            "email" => $login['loginid'],
            'description' => "userId: {$login['id']}, userName: {$login['name']}",
        ]);
        // カード作成
        $card = $customer->cards->create(array(
            "card" => $payjp_token
        ));

        // DB反映
        if($customer)
        {
            if($this->coachRepository->updatePayjp($login['id'], $customer->id, $card->id))
            {
                // メール送信
                Mail::to($this->to)
                    ->send(new RegisterCardMail($this->login));

                DB::commit();
                return $customer;
            }
        }
        DB::rollback();
        return FALSE;
    }

    public function update($payjp_token)
    {
        DB::beginTransaction();

        $login = session()->get('login');
        \Payjp\Payjp::setApiKey(config('rentacoach.payjp.secret_key'));

        if($student = $this->coachRepository->getStudentById($login['id']))
        {
            if($student['payjp_customer_id'])
            {
                // 顧客取得
                $customer = \Payjp\Customer::retrieve($student['payjp_customer_id']);
                // カードいったん削除
                $card = $customer->cards->retrieve($student['payjp_card_id']);
                $card->delete();
                // 新しくカード作成
                $card = $customer->cards->create(array(
                    "card" => $payjp_token
                ));
                
                if($customer)
                {
                    if($this->coachRepository->updatePayjp($login['id'], $customer->id, $card->id))
                    {
                        // メール送信
                        Mail::to($this->to)
                        ->send(new RegisterCardMail($this->login));

                        DB::commit();
                        return $customer;
                    }
                }
            }
        }
        DB::rollback();
        return FALSE;
    }

    public function reRegister($payjp_token)
    {
        DB::beginTransaction();

        $login = session()->get('login');
        \Payjp\Payjp::setApiKey(config('rentacoach.payjp.secret_key'));

        if($student = $this->coachRepository->getStudentById($login['id']))
        {
            if($student['payjp_customer_id'])
            {
                // 顧客取得
                $customer = \Payjp\Customer::retrieve($student['payjp_customer_id']);
                // カードいったん削除
                $card = $customer->cards->retrieve($student['payjp_card_id']);
                $card->delete();
                // 新しくカード作成
                $card = $customer->cards->create(array(
                    "card" => $payjp_token
                ));

                if($customer)
                {
                    if($this->coachRepository->updatePayjp($login['id'], $customer->id, $card->id))
                    {
                        DB::commit();
                        if($reservations = $this->reservationRepository->getImmediateChargeTargetReservations($this->date, $student['id']))
                        {
                            foreach($reservations as $reservation)
                            {
                                if($coach =  $this->coachRepository->getCoachById($reservation['coach_id']))
                                {
                                    try
                                    {
                                        if($student = $this->coachRepository->getStudentById($reservation['student_id']))
                                        {
                                            $diff = floor(strtotime($reservation['end_time'])) - floor(strtotime($reservation['start_time']));
                                            $amount = floor($reservation['fee']) * ($diff/60/30);
    
                                            // payjpの最低金額を下回るのはNG
                                            if($amount > 0 && $amount < 50)
                                            {
                                                $amount = 50;
                                            }
                                            elseif($amount = 0)
                                            {
                                                echo('amount=0');
                                                continue;
                                            }
                                            elseif($amount > 9999999)
                                            {
                                                $amount = 9999999;
                                            }
    
                                            \Payjp\Charge::create([
                                                "customer" => $customer->id,
                                                "amount" => $amount,
                                                "currency" => 'jpy',
                                            ]);
    
                                            $reservation['amount'] = $amount;
                                        }
                                    } 
                                    catch(\Payjp\Error\Card $e){
                                        echo('Card declined.');
                                        // メール送信
                                        Mail::to($this->to)
                                        ->send(new ChargeFailedMail($reservation, $coach, $this->login));
                                        return FALSE;
                                    }
                                    catch(\Payjp\Error\InvalidRequest $e){
                                        echo('Invalid Request.');
                                        // メール送信
                                        Mail::to($this->to)
                                        ->send(new ChargeFailedMail($reservation, $coach, $this->login));
                                        return FALSE;
                                    }
    
                                    if($this->reservationRepository->updateChargedFlag($reservation['id']))
                                    {
                                        if($this->coachRepository->updateStudentActive($reservation))
                                        {
                                            // メール送信
                                            Mail::to($this->to)
                                            ->send(new ChargeSuccessMail($reservation, $coach, $this->login));
                                        }
                                    }
                                }
                            }
                            Mail::to($this->to)
                            ->send(new RegisterCardMail( $this->login));
                            return $customer;
                        }
                    }
                }
            }
        }
        DB::rollback();
        return FALSE;
    }

    public function charge()
    {
        // 課金対象の予約リストを取得
        if($reservations = $this->reservationRepository->getChargeTargetReservations($this->date))
        {
            \Payjp\Payjp::setApiKey(config('rentacoach.payjp.secret_key'));
            foreach($reservations as $reservation)
            {
                if($student = $this->coachRepository->getStudentById($reservation['student_id']))
                {
                    if($coach =  $this->coachRepository->getCoachById($reservation['coach_id']))
                    {
                        DB::beginTransaction();

                        // 開発環境は山崎にメール送信
                        if(config('rentacoach.to.address'))
                        {
                            $this->to = config('rentacoach.to.address');
                        }
                        else
                        {
                            $this->to = $student['loginid'];
                        }
    
                        $diff = floor(strtotime($reservation['end_time'])) - floor(strtotime($reservation['start_time']));
                        $amount = floor($reservation['fee']) * ($diff/60/30);
    
                        // payjpの最低金額を下回るのはNG
                        if($amount > 0 && $amount < 50)
                        {
                            $amount = 50;
                        }
                        elseif($amount = 0)
                        {
                            echo('amount=0');
                            continue;
                        }
                        elseif($amount > 9999999)
                        {
                            $amount = 9999999;
                        }
                        $reservation['amount'] = $amount;
                        
                        try{
                            \Payjp\Charge::create([
                                "customer" => $student['payjp_customer_id'],
                                "amount" => $amount,
                                "currency" => 'jpy',
                            ]);
                            if($this->reservationRepository->updateChargedFlag($reservation['id']))
                            {
                                echo('charged');
                                $this->coachRepository->updateStudentActive($reservation);
                                // メール送信
                                if($student)
                                {
                                    Mail::to($this->to)
                                    ->send(new ChargeSuccessMail($reservation, $coach, $student));
                                    DB::commit();
                                }
                            }
                        }
                        catch(\Payjp\Error\Card $e){
                            echo('Card declined.');
                            DB::rollback();
                            if($this->coachRepository->updateStudentInactive($reservation))
                            {
                                if($student)
                                {
                                    // メール送信
                                    Mail::to($this->to)
                                    ->send(new ChargeFailedMail($reservation, $coach, $student));
                                }
                            }
                        }
                        catch(\Payjp\Error\InvalidRequest $e){
                            echo('Invalid Request.');
                            DB::rollback();
                            if($this->coachRepository->updateStudentInactive($reservation))
                            {
                                if($student)
                                {
                                    // メール送信
                                    Mail::to($this->to)
                                    ->send(new ChargeFailedMail($reservation, $coach, $student));
                                }
                            }
                        }
                    }
                }
            }
            return TRUE;
        }
        return FALSE;
    }
}