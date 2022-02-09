<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateRequest;
use App\Http\Requests\UpdateIconRequest;
use App\Http\Requests\UpdateTrainingRequest;
use App\Http\Requests\PayrollAccountYuutyoRequest;
use App\Http\Requests\PayrollAccountMajorRequest;
use App\Http\Requests\PayrollAccountRequest;
use App\Http\Requests\VerificationDocumentRequest;
use Validator;

class MypageController extends Controller
{
    public function __construct()
    {
        $this->coachService = app()->make('App\Services\CoachService');
        $this->payrollAccountService = app()->make('App\Services\PayrollAccountService');
        $this->verificationDocumentService = app()->make('App\Services\VerificationDocumentService');

        $this->login = session()->get('login');
        $this->coach_flag = session()->get('coach_flag');
    }

    public function index(Request $request)
    {
        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        $assign_data = [
            'login' => $login,
            'coach_flag' => $coach_flag
        ];
        return view('mypage.index', $assign_data);
    }

    public function edit(Request $request)
    {
        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        $assign_data = [
            'login' => $login,
            'coach_flag' => $coach_flag
        ];
        return view('mypage.edit', $assign_data);
    }

    public function update(UpdateRequest $post_data)
    {
        // 更新 & 再ロード
        if($_coach = $this->coachService->update($post_data))
        {
            // セッションをセット
            $_coach['passwd'] = '';
            session()->forget('login');
            session()->put('login',$_coach);
            session()->flash('flash_message', '基本情報を更新しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', '基本情報の更新に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(route('mypage.edit'));
    }

    public function updateIcon(UpdateIconRequest $post_data)
    {
        // 更新 & 再ロード
        if($_coach = $this->coachService->updateIcon($post_data))
        {
            // セッションをセット
            $_coach['passwd'] = '';
            $_coach['icon_pass'] = '/storage/icon/'.$_coach['icon'];
            session()->forget('login');
            session()->put('login',$_coach);
            session()->flash('flash_message', 'プロフィール画像を更新しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', 'プロフィール画像の更新に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(route('mypage.edit'));
    }

    public function updateTraining(UpdateTrainingRequest $post_data)
    {
        // 更新 & 再ロード
        if($_coach = $this->coachService->updateTraining($post_data))
        {
            // セッションをセット
            $_coach['passwd'] = '';
            session()->forget('login');
            session()->put('login',$_coach);
            session()->flash('flash_message','練習内容を更新しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', '練習内容の更新に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(route('mypage.edit'));
    }

    public function createVerificationDocument()
    {
        if($document = $this->verificationDocumentService->getDocumentById())
        {
            $assign_data = [
                'document' => $document,
                'identified_status' => $this->login['identified_status'],
            ];
            return view('mypage.create_verification_document', $assign_data);
        }
        $assign_data = [
            'identified_status' => $this->login['identified_status'],
        ];
        return view('mypage.create_verification_document', $assign_data);
    }

    public function saveVerificationDocument(VerificationDocumentRequest $post_data)
    {
        if($post_data['document_type'] != 'passport')
        {
            $validator = Validator::make(
                $post_data->all(), 
                ['img_back' => 'required']
            );
        }
        // 本人確認書類登録 & ユーザ情報再ロード
        if($user = $this->verificationDocumentService->save($post_data))
        {
            session()->forget('login');
            $user['passwd'] = '';
            session()->put('login',$user);
            session()->flash('flash_message', '本人確認書類を登録しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', '本人確認書類の登録に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(route('mypage.create_verification_document'));
    }

    public function createPayrollAccount()
    {
        // すでに登録口座がある場合はフォームに値をセットして表示
        if($account = $this->payrollAccountService->getAccountByCoachId())
        {
            $assign_data = [
                'account' => $account
            ];
            return view('mypage.create_payroll_account', $assign_data);
        }
        // 無い場合は空の値をセット
        $account = [
            'bank' => '',
            'bank_code' => '',
            'branch' => '',
            'branch_code' => '',
            'account_type' => '',
            'symbol_number' => '',
            'name' =>''
        ];
        $assign_data = [
            'account' => $account
        ];
        return view('mypage.create_payroll_account', $assign_data);
    }

    public function saveYuutyoPayrollAccount(PayrollAccountYuutyoRequest $post_data)
    {
        $login = session()->get('login');
        $post_data['coach_id'] = $login['id'];
        if($this->payrollAccountService->save($post_data))
        {
            session()->flash('flash_message', '給与振込口座を登録しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', '給与振込口座の登録に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(url()->previous());
    }

    public function saveMajorPayrollAccount(PayrollAccountMajorRequest $post_data)
    {
        $login = session()->get('login');
        $post_data['coach_id'] = $login['id'];
        if($this->payrollAccountService->save($post_data))
        {
            session()->flash('flash_message', '給与振込口座を登録しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', '給与振込口座の登録に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(url()->previous());
    }

    public function savePayrollAccount(PayrollAccountRequest $post_data)
    {
        $login = session()->get('login');
        $post_data['coach_id'] = $login['id'];
        if($this->payrollAccountService->save($post_data))
        {
            session()->flash('flash_message', '給与振込口座を登録しました。');
            return redirect(route('mypage.index'));
        }
        session()->flash('flash_message', '給与振込口座の登録に失敗しました。お手数ですが、サポートセンターまでご連絡ください。');
        return redirect(url()->previous());
    }
}
