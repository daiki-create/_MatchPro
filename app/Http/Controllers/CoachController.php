<?php

namespace App\Http\Controllers;

use App\Services\CoachService;

use Illuminate\Http\Request;
use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\AuthRequest;

use Illuminate\Support\Facades\Mail;

class CoachController extends Controller
{
    public function __construct(CoachService $coachService)
    {
        $this->coachService = $coachService;
    }

    public function login($coach_flag)
    {
        $assign_data = [
            'coach_flag' => $coach_flag
        ];
        return view('coaches.login', $assign_data);
    }

    public function auth(AuthRequest $post_data)
    {
        // パスワード認証
        if($_coach = $this->coachService->auth($post_data))
        {
            // セッションをセット
            session()->flush();
            $_coach['passwd'] = '';
            session()->put('login', $_coach);
            session()->put('coach_flag', $post_data['coach_flag']);

            // マイページにリダイレクト
            return redirect(route('mypage.index'));
        }
        // またはloginに戻す
        $assign_data = [
            'coach_flag' => $post_data['coach_flag']
        ];
        return redirect(route('coaches.login', $assign_data));
    }

    public function create($loginid, $coach_flag)
    {
        $assign_data = [
            'loginid' => $loginid,
            'coach_flag' => $coach_flag
        ];
        return view('coaches.create', $assign_data);
    }

    public function save(SaveUserRequest $post_data)
    {
        // 保存
        if($_coach = $this->coachService->save($post_data))
        {
            // セッションをセット
            $_coach['passwd'] = '';
            session()->flush();
            session()->put('login',$_coach);
            session()->put('coach_flag', $post_data['coach_flag']);

            // マイページにリダイレクト
            return redirect(route('mypage.index'));
        }
        $assign_data = [
            'loginid' => $post_data['loginid'],
            'coach_flag' => $post_data['coach_flag']
        ];
        return redirect(route('coaches.create', $assign_data));
    }

    public function list()
    {
        // コーチリストを取得
        $coach_list = $this->coachService->getCoachList();
        // アサイン
        if($coach_list)
        {
            $assign_data = [
                'coach_list' => $coach_list
            ];
            return view('coaches.list', $assign_data);
        }
        return view('coaches.list');
    }

    public function detail($id)
    {
        // コーチリストを取得
        $_coach = $this->coachService->getCoachById($id);
        // アサイン
        if($_coach)
        {
            $assign_data = [
                'coach' => $_coach
            ];
            return view('coaches.detail', $assign_data);
        }
        return view('coaches.detail');
    }

    public function logout(Request $request)
    {
        $coach_flag = $request->coach_flag;
        session()->flush();
        return redirect(url('coaches/login/'.$coach_flag));
    }

    public function left(Request $request)
    {
        $coach_flag = $request->coach_flag;
        if($this->coachService->left() == 'left')
        {
            session()->flush();
            return redirect(url('coaches/login/'.$coach_flag));
        }
        elseif($this->coachService->left() == 'inactive')
        {
            return redirect(route('cards.create'));
        }
        return redirect(route('mypage.index'));
    }
}
