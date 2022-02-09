<?php

namespace App\Http\Controllers;

use App\Services\TempCoachService;
use App\Services\MailService;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Http\Requests\TempCoachRequest;

use App\Mail\ToCreateCoachMail;
use Illuminate\Support\Facades\Mail;

class TempCoachController extends Controller
{

    public function __construct(TempCoachService $tempCoachService)
    {
        $this->tempCoachService = $tempCoachService;
    }

    public function create($coach_flag)
    {
        $assign_data = [
            'coach_flag' => $coach_flag
        ];
        return view('temp_coaches.create', $assign_data);
    }

    public function save(TempCoachRequest $post_data)
    {
        if($temp_coach = $this->tempCoachService->save($post_data))
        {
            // 開発環境は山崎にメール送信
            if(config('rentacoach.to.address'))
            {
                $to = config('rentacoach.to.address');
            }
            else
            {
                $to = $temp_coach['loginid'];
            }

            // メール送信
            Mail::to($to)
                ->send(new ToCreateCoachMail($temp_coach));

            return view('temp_coaches.thanks');
        }
        return redirect(route('temp_coaches.create'));
    }

    public function redirectToRegister($loginid, $temp_code, $coach_flag)
    {
        // temp_codeをもったユーザがいるかどうか
        if($temp_coach = $this->tempCoachService->getTempCoachByCode($loginid, $temp_code))
        {
            $assign_data = [
                'loginid' => $temp_coach['loginid'],
                'coach_flag' => $coach_flag
            ];
            return redirect(route('coaches.create', $assign_data));
        }
        // いなければ仮登録画面へ
        $assign_data = [
            'coach_flag' => $coach_flag
        ];
        return redirect(route('temp_coaches.create', $assign_data));
    }
}

