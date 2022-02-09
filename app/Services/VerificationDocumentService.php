<?php

namespace App\Services;

use App\Repositories\VerificationDocumentRepositoryInterface as VerificationDocumentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VerificationDocumentService
{
    public function __construct(
        VerificationDocumentRepository $verificationDocumentRepository
    )
    {
        $this->verificationDocumentRepository = $verificationDocumentRepository;
        $this->coachRepository = app()->make('App\Repositories\CoachRepositoryInterface');
    }

    public function getDocumentById()
    {
        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        if($coach_flag)
        {
            $document = $this->verificationDocumentRepository->getCoachDocumentById($login['id']);
        }
        else
        {
            $document = $this->verificationDocumentRepository->getStudentDocumentById($login['id']);
        }
        if($document)
        {
            return $document;
        }
        return FALSE;
    }

    public function save($post_data)
    {
        DB::beginTransaction();

        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        $post_data['session_id'] = $login['id'];

        // 拡張子を取得
        $front_ext = pathinfo($post_data->file('img_front')->getClientOriginalName(), PATHINFO_EXTENSION);

        // ランダム文字列を取得
        $front_rand = md5(uniqid(rand(), true));

        // 名前を定義
        $post_data['img_front_name'] = $front_rand.'.'.$front_ext;

        if(isset($post_data['img_back']))
        {
            $back_ext = pathinfo($post_data->file('img_back')->getClientOriginalName(), PATHINFO_EXTENSION);
            $back_rand = md5(uniqid(rand(), true));
            $post_data['img_back_name'] = $back_rand.'.'.$back_ext;
        }
        else{
            // パスポートの場合はウラ画像無し
            $post_data['img_back'] = 'no_image';
        }

        if($coach_flag)
        {
            if($coach_document = $this->verificationDocumentRepository->getCoachDocumentById($login['id']))
            {
                \Storage::disk('public')->delete('verification_doocuments/coach/front/'.$coach_document['img_front']);
                \Storage::disk('public')->delete('verification_doocuments/coach/back/'.$coach_document['img_back']);
                if(!$this->verificationDocumentRepository->deleteCoachDocument($coach_document))
                {
                    DB::rollBack();
                    return FALSE;
                }
            }
            if($this->verificationDocumentRepository->saveCoachDocument($post_data))
            {
                if($this->coachRepository->updateCoachChecking($login['id']))
                {
                    // 文字列のファイルがなければstore
                    $post_data->file('img_front')->storeAs('public/verification_doocuments/coach/front', $post_data['img_front_name']);
                    if($post_data['img_back'] != 'no_image')
                    {
                        $post_data->file('img_back')->storeAs('public/verification_doocuments/coach/back', $post_data['img_back_name']);
                    }

                    // 再ロードしてreturn
                    if($coach = $this->coachRepository->getCoachById($login['id']))
                    {
                        DB::commit();
                        return $coach;
                    }
                }
            }
        }
        else
        {
            if($student_document = $this->verificationDocumentRepository->getStudentDocumentById($login['id']))
            {
                \Storage::disk('public')->delete('verification_doocuments/student/front/'.$student_document['img_front']);
                \Storage::disk('public')->delete('verification_doocuments/student/back/'.$student_document['img_back']);
                if(!$this->verificationDocumentRepository->deleteStudentDocument($student_document))
                {
                    DB::rollBack();
                    return FALSE;
                }
            }
            if($this->verificationDocumentRepository->saveStudentDocument($post_data))
            {
                if($this->coachRepository->updateStudentChecking($login['id']))
                {
                    // 文字列のファイルがなければstore
                    $post_data->file('img_front')->storeAs('public/verification_doocuments/student/front', $post_data['img_front_name']);
                    if($post_data['img_back'] != 'no_image')
                    {
                        $post_data->file('img_back')->storeAs('public/verification_doocuments/student/back', $post_data['img_back_name']);
                    }
                    // 再ロードしてreturn
                    if($student = $this->coachRepository->getStudentById($login['id']))
                    {
                        DB::commit();
                        return $student;
                    }
                }
            }
        }
        DB::rollBack();
        return FALSE;
    }
}