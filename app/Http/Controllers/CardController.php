<?php

namespace App\Http\Controllers;

use App\Services\CardService;
use App\Services\MailService;
use Facade\FlareClient\View;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
        $this->coachService = app()->make('App\Services\CoachService');

        $this->login = session()->get('login');
    }

    public function create()
    {
        $assign_data = [
            'login' => $this->login
        ];
        return view('cards.create', $assign_data);
    }

    public function register(Request $request)
    {
        if($customer = $this->cardService->regist($request['payjp-token'] ) )
        {
            $this->login['payjp_customer_id'] = $customer->id;
            session()->forget('login');
            session()->put('login',$this->login);
            session()->flash('flash_message', 'お支払い情報を登録しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', 'お支払い情報の登録に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(route('cards.create'));
    }

    public function update(Request $request)
    {
        if($customer = $this->cardService->update($request['payjp-token'] ) )
        {
            $this->login['payjp_customer_id'] = $customer->id;
            session()->forget('login');
            session()->put('login',$this->login);
            session()->flash('flash_message', 'お支払い情報を更新しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', 'お支払い情報の更新に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(route('cards.create'));
    }

    public function reRegister(Request $request)
    {
        if($customer = $this->cardService->reRegister($request['payjp-token'] ) )
        {
            $this->login['payjp_customer_id'] = $customer->id;
            $this->login['status'] = 'active';
            session()->forget('login');
            session()->put('login',$this->login);
            session()->flash('flash_message', 'お支払い情報を再登録しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', 'お支払い情報の再登録に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(route('cards.create'));
    }

    public function delete()
    {

    }
}
