<?php

namespace App\Services;

use App\Repositories\ReservationRepositoryInterface as ReservationRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Mail\ReservationSaveCoachMail;
use App\Mail\ReservationSaveStudentMail;
use App\Mail\ReservationAcceptedCoachMail;
use App\Mail\ReservationAcceptedStudentMail;
use App\Mail\ReservationRejectedCoachMail;
use App\Mail\ReservationRejectedStudentMail;
use App\Mail\ReservationCoachCanceledCoachMail;
use App\Mail\ReservationCoachCanceledStudentMail;
use App\Mail\ReservationCanceledCoachMail;
use App\Mail\ReservationCanceledStudentMail;
use Illuminate\Support\Facades\Mail;

class ReservationService
{
    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->coachRepository = app()->make('App\Repositories\CoachRepositoryInterface');
        $this->messageRepository = app()->make('App\Repositories\MessageRepositoryInterface');
    }

    public function save($post_data)
    {
        $coach_flag = session()->get('coach_flag');

        DB::beginTransaction();

        if(!$coach_flag)
        {
            if($this->reservationRepository->save($post_data))
            {
                // メッセージ送信
                $reservation_data = [
                    'sender_id' => $post_data['student_id'],
                    'receiver_id' => $post_data['id'],
                    'coach_id' => $post_data['id'],
                    'student_id' => $post_data['student_id'],
                    'sender' => 'system',
                    'message' => 
                        'このメッセージはシステムからの自動送信です\\n'.
                        '【仮予約情報】\\n'.
                        'test test'
                ];
                if($this->messageRepository->saveReservationMessage($reservation_data))
                {
                    // 開発環境は山崎にメール送信
                    if(config('rentacoach.to.address'))
                    {
                        $to_coach = config('rentacoach.to.address');
                        $to_student = config('rentacoach.to.address');
                    }
                    else
                    {
                        $to_coach = $post_data['id'];
                        $to_student = $post_data['student_id'];
                    }

                    // メール送信
                    if($coach = $this->coachRepository->getCoachById($post_data['id']))
                    {
                        if($student = $this->coachRepository->getStudentById($post_data['student_id']))
                        {
                            Mail::to($to_coach)
                            ->send(new ReservationSaveCoachMail($post_data, $coach, $student));
                            Mail::to($to_student)
                            ->send(new ReservationSaveStudentMail($post_data, $coach, $student));
                        }
                    }

                    DB::commit();
                    return TRUE;
                }
            }
        }
        DB::rollBack();
        return FALSE;
    }

    public function getReservation()
    {
        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        if($coach_flag)
        {
            if($reservations = $this->reservationRepository->getReservationByCoachId($login['id']))
            {
                // 予約IDから生徒情報を取得
                foreach($reservations as $reservation)
                {
                    if($student = $this->coachRepository->getStudentById($reservation['student_id']))
                    {
                        $reservation['name'] = $student['name'];
                    }
                }
                return $reservations;
            }
        }
        else{
            if($reservations = $this->reservationRepository->getReservationByStudentId($login['id']))
            {
                // 予約IDからコーチ情報を取得
                foreach($reservations as $reservation)
                {
                    if($coach = $this->coachRepository->getCoachById($reservation['coach_id']))
                    {
                        $reservation['name'] = $coach['name'];
                    }
                }
                return $reservations;
            }
        }
        return FALSE;
    }

    public function accept($reservation_id)
    {
        DB::beginTransaction();

        if($reservation = $this->reservationRepository->getReservationById($reservation_id))
        {
            // ステータスをacceptedに更新
            if($this->reservationRepository->updateReservationAccepted($reservation_id))
            {
                // メッセージ送信
                $reservation_data = [
                    'sender_id' => $reservation['coach_id'],
                    'receiver_id' => $reservation['student_id'],
                    'coach_id' => $reservation['coach_id'],
                    'student_id' => $reservation['student_id'],
                    'sender' => 'system',
                    'message' => '承認通知'
                ];
                if($this->messageRepository->saveReservationMessage($reservation_data))
                {
                    // 開発環境は山崎にメール送信
                    if(config('rentacoach.to.address'))
                    {
                        $to_coach = config('rentacoach.to.address');
                        $to_student = config('rentacoach.to.address');
                    }
                    else
                    {
                        $to_coach = $reservation['coach_id'];
                        $to_student = $reservation['student_id'];
                    }

                    // メール送信
                    if($coach = $this->coachRepository->getCoachById($reservation['coach_id']))
                    {
                        if($student = $this->coachRepository->getStudentById($reservation['student_id']))
                        {
                            Mail::to($to_coach)
                                ->send(new ReservationAcceptedCoachMail($reservation, $coach, $student));
                            Mail::to($to_student)
                                ->send(new ReservationAcceptedStudentMail($reservation, $coach, $student));

                            DB::commit();
                            return TRUE;
                        }
                    }
                }
            }
        }
        DB::rollBack();
        return FALSE;
    }

    public function reject($reservation_id)
    {
        DB::beginTransaction();

        if($reservation = $this->reservationRepository->getReservationById($reservation_id))
        {
            // ステータスをrejectedに更新
            if($this->reservationRepository->updateReservationRejected($reservation_id))
            {
                // メッセージ送信
                $reservation_data = [
                    'sender_id' => $reservation['coach_id'],
                    'receiver_id' => $reservation['student_id'],
                    'coach_id' => $reservation['coach_id'],
                    'student_id' => $reservation['student_id'],
                    'sender' => 'system',
                    'message' => 'お断り通知'
                ];
                if($this->messageRepository->saveReservationMessage($reservation_data))
                {
                    // 開発環境は山崎にメール送信
                    if(config('rentacoach.to.address'))
                    {
                        $to_coach = config('rentacoach.to.address');
                        $to_student = config('rentacoach.to.address');
                    }
                    else
                    {
                        $to_coach = $reservation['coach_id'];
                        $to_student = $reservation['student_id'];
                    }

                    // メール送信
                    if($coach = $this->coachRepository->getCoachById($reservation['coach_id']))
                    {
                        if($student = $this->coachRepository->getStudentById($reservation['student_id']))
                        {
                            Mail::to($to_coach)
                                ->send(new ReservationRejectedCoachMail($reservation, $coach, $student));
                            Mail::to($to_student)
                                ->send(new ReservationRejectedStudentMail($reservation, $coach, $student));

                            DB::commit();
                            return TRUE;
                        }
                    }
                }
            }
        } 
        DB::rollBack();
        return FALSE;
    }

    public function coach_cancel($reservation_id, $date, $start_time)
    {
        DB::beginTransaction();

        if($reservation = $this->reservationRepository->getReservationById($reservation_id))
        {
            if($this->reservationRepository->updateReservationRejected($reservation_id))
            {
                if($coach = $this->coachRepository->getCoachById($reservation['coach_id']))
                {
                    $penalty = $coach['penalty'];

                     // 日時によってペナルティの変更
                    $hour = 60 * 60;
                    $datetime = strtotime(date('Y-m-d H:i:s'));
                    $reservation_datetime = strtotime($date.' '.$start_time);
                    $reservation_datetime = strtotime(date('Y-m-d H:i:s', strtotime('+25 hour')));

                    $time_diff = $reservation_datetime - $datetime;

                    $add_penalty = 0;
                    if($time_diff < 0)
                    {
                        $add_penalty = 3;
                    }
                    elseif($time_diff < 6 * $hour)
                    {
                        $add_penalty = 2;
                    }
                    elseif($time_diff < 24 * $hour)
                    {
                        $add_penalty = 1;
                    }
    
                    $penalty = $penalty + $add_penalty;
                    if($this->coachRepository->updateCoachPenalty($coach['id'], $penalty))
                    {
                        // メッセージ送信
                        $reservation_data = [
                            'sender_id' => $reservation['coach_id'],
                            'receiver_id' => $reservation['student_id'],
                            'coach_id' => $reservation['coach_id'],
                            'student_id' => $reservation['student_id'],
                            'sender' => 'system',
                            'message' => '承認からのキャンセル通知'
                        ];
                        if($this->messageRepository->saveReservationMessage($reservation_data))
                        {
                            // 開発環境は山崎にメール送信
                            if(config('rentacoach.to.address'))
                            {
                                $to_coach = config('rentacoach.to.address');
                                $to_student = config('rentacoach.to.address');
                            }
                            else
                            {
                                $to_coach = $reservation['coach_id'];
                                $to_student = $reservation['student_id'];
                            }

                            // メール送信
                            if($coach = $this->coachRepository->getCoachById($reservation['coach_id']))
                            {
                                if($student = $this->coachRepository->getStudentById($reservation['student_id']))
                                {
                                    Mail::to($to_coach)
                                    ->send(new ReservationCoachCanceledCoachMail($reservation, $coach, $student));
                                    Mail::to($to_student)
                                    ->send(new ReservationCoachCanceledStudentMail($reservation, $coach, $student));

                                    DB::commit();
                                    return TRUE;
                                }
                            }
                        }
                    }
                }
            }
        }
        DB::rollBack();
        return FALSE;
    }

    public function cancel($reservation_id, $date, $start_time)
    {
        DB::beginTransaction();

        if($reservation = $this->reservationRepository->getReservationById($reservation_id))
        {
            $fee = $reservation['fee'];
            $status = $reservation['status'];

            // 日時によって金額の変更
            $hour = 60 * 60;
            $datetime = strtotime(date('Y-m-d H:i:s'));
            $reservation_datetime = strtotime($date.' '.$start_time);
            $time_diff = $reservation_datetime - $datetime;

            if($status == 'temp')
            {
                $fee = 0;
            }
            else{
                if($time_diff < 0)
                {
                    $fee = $fee;
                }
                elseif($time_diff < 6 * $hour)
                {
                    $fee = $fee * 75 / 100;
                }
                elseif($time_diff < 24 * $hour)
                {
                    $fee = $fee * 50 / 100;
                }
                elseif($time_diff < 48 * $hour)
                {
                    $fee = $fee * 25 / 100;
                }
                else
                {
                    $fee = 0;
                }
            }
            
            if($this->reservationRepository->updateReservationCanceled($reservation_id, $fee))
            {
                // メッセージ送信
                $reservation_data = [
                    'sender_id' => $reservation['student_id'],
                    'receiver_id' => $reservation['coach_id'],
                    'coach_id' => $reservation['coach_id'],
                    'student_id' => $reservation['student_id'],
                    'sender' => 'system',
                    'message' => 'キャンセル通知'
                ];
                if($this->messageRepository->saveReservationMessage($reservation_data))
                {
                    // 開発環境は山崎にメール送信
                    if(config('rentacoach.to.address'))
                    {
                        $to_coach = config('rentacoach.to.address');
                        $to_student = config('rentacoach.to.address');
                    }
                    else
                    {
                        $to_coach = $reservation['coach_id'];
                        $to_student = $reservation['student_id'];
                    }

                    // メール送信
                    if($coach = $this->coachRepository->getCoachById($reservation['coach_id']))
                    {
                        if($student = $this->coachRepository->getStudentById($reservation['student_id']))
                        {
                            Mail::to($to_coach)
                                ->send(new ReservationCanceledCoachMail($reservation, $coach, $student));
                            Mail::to($to_student)
                                ->send(new ReservationCanceledStudentMail($reservation, $coach, $student));

                            DB::commit();
                            return TRUE;
                        }
                    }
                }
            }
        }
        
        DB::rollBack();
        return FALSE;
    }

    function saveReview($post_data)
    {
        if($this->reservationRepository->saveReview($post_data))
        {
            return TRUE;
        }
        return FALSE;
    }
}