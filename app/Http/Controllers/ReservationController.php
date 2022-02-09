<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use App\Services\MailService;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ReviewRequest;

class ReservationController extends Controller
{
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
        $this->coachService = app()->make('App\Services\CoachService');

        $this->login = session()->get('login');
        $this->login = session()->get('login');
    }

    public function list()
    {
        $coach_flag = session()->get('coach_flag');
        if($reservations = $this->reservationService->getReservation())
        {
            $assign_data = [
                'reservations' => $reservations,
                'coach_flag' => $coach_flag
            ];
            return view('reservations.list', $assign_data);
        }
        return view('reservations.list');
    }

    public function create($id)
    {
        if(!$this->login['payjp_customer_id'])
        {
            session()->flash('flash_message', '練習を申し込むにはお支払い情報の登録が必要です。');
            return redirect(route('cards.create'));
        }
        if($this->login['identified_status'] == 'unidentified')
        {
            session()->flash('flash_message', '練習を申し込むには本人確認書類の登録が必要です。');
            return redirect(route('mypage.create_verification_document'));
        }
        if($this->login['identified_status'] == 'checking')
        {
            session()->flash('flash_message', '現在、本人確認書類を確認しています。');
            return redirect(route('mypage.index'));
        }
        if($this->login['status'] == 'inactive')
        {
            session()->flash('flash_message', 'ご利用を継続するには、新しいお支払い情報を登録してください。');
            return redirect(route('cards.create'));
        }

        $coach = $this->coachService->getCoachById($id);
        $assign_data = [
            'coach' => $coach,
        ];
        return view('reservations.create', $assign_data);
    }

    public function to_confirm(ReservationRequest $post_data)
    {
        $post = [
            'name' => $post_data['name'],
            'area' => $post_data['area'],
            'fee' => $post_data['fee'],
            'avalable_datetime' => $post_data['avalable_datetime'],

            'date' => $post_data['date'],
            'start_time' => $post_data['start_time'],
            'end_time' => $post_data['end_time'],
            'content' => $post_data['content'],
            'num' => $post_data['num'],
            'id' => $post_data['id'],
        ];
        session()->put('post', $post);
        return redirect(route('reservations.confirm'));
    }

    public function confirm()
    {
        $post = session()->get('post');
        $assign_data = [
            'post' => $post
        ];
        return view('reservations.confirm', $assign_data);
    }

    public function save(Request $request)
    {
        $post_data = session()->get('post');
        $login = session()->get('login');
        $post_data['student_id'] = $login['id'];
        // 保存 & メッセージ & メール
        if($this->reservationService->save($post_data))
        {
            // リダイレクト
            session()->forget('post');
            session()->flash('flash_message', '仮予約が完了しました。コーチが出勤可否を決定次第、お知らせいたします。');
            return redirect(route('reservations.list'));
        }
        $assign_data = [
            'id' => $post_data['id']
        ];
        session()->flash('flash_message', '仮予約の完了に失敗しました。お手数ですがもう一度最初からやり直してください。');
        return redirect(route('reservations.create', $assign_data));
    }

    public function accept($reservation_id)
    {
        // ステータスをacceptedに更新
        if($this->reservationService->accept($reservation_id))
        {
            session()->flash('flash_message', '予約を承認しました。練習当日までのお知らせなどある場合はチャットにて生徒さんにご連絡ください。');
            return redirect(route('reservations.list'));
        }
        session()->flash('flash_message', '予約の承認に失敗しました。お手数ですが、サポートセンターまでお問い合わせください。');
        return redirect(route('reservations.list'));
    }

    public function reject($reservation_id)
    {
        // ステータスをrejectedに更新
        if($this->reservationService->reject($reservation_id))
        {
            session()->flash('flash_message', '予約をお断りしました。');
            return redirect(route('reservations.list'));
        }
        session()->flash('flash_message', '予約のお断りに失敗しました。お手数ですが、サポートセンターまでお問い合わせください。');
        return redirect(route('reservations.list'));
    }

    public function coach_cancel($reservation_id, $date, $start_time)
    {
        // ステータスをcanceldに更新、ペナルティの更新
        if($this->reservationService->coach_cancel($reservation_id, $date, $start_time))
        {
            session()->flash('flash_message', '予約をキャンセルしました。');
            return redirect(route('reservations.list'));
        }
        session()->flash('flash_message', '予約のキャンセルに失敗しました。お手数ですが、サポートセンターまでお問い合わせください。');
        return redirect(route('reservations.list'));
    }

    public function cancel($reservation_id, $date, $start_time)
    {
        // ステータスをcanceldに更新、キャンセル料のセット
        if($this->reservationService->cancel($reservation_id, $date, $start_time))
        {
            session()->flash('flash_message', '予約をキャンセルしました。');
            return redirect(route('reservations.list'));
        }
        session()->flash('flash_message', '予約のキャンセルに失敗しました。お手数ですが、サポートセンターまでお問い合わせください。');
        return redirect(route('reservations.list'));
    }

    public function createReview($reservation_id)
    {
        $assign_data = [
            'reservation_id' => $reservation_id,
        ];
        return view('reservations.create_review', $assign_data);
    }

    public function saveReview(ReviewRequest $post_data)
    {
        if($this->reservationService->saveReview($post_data))
        {
            session()->flash('flash_message', 'レビューのご協力ありがとうございました。');
            return redirect(route('reservations.list'));
        }
        $assign_data = [
            'reservation_id' => $post_data['reservation_id']
        ];
        session()->flash('flash_message', 'レビューの送信に失敗しました。お手数ですが、サポートセンターまでお問い合わせください。');
        return redirect(route('reservations.create_review', $assign_data));
    }
}
