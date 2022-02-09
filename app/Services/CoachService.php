<?php

namespace App\Services;

use App\Repositories\CoachRepositoryInterface as CoachRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CoachService
{
    public function __construct(CoachRepository $coachRepository)
    {
        $this->coachRepository = $coachRepository;
        $this->tempCoachRepository = app()->make('App\Repositories\TempCoachRepositoryInterface');

        $this->login = session()->get('login');
        $this->coach_flag = session()->get('coach_flag');
    }

    public function auth($post_data)
    {
        // ログインIDのユーザを取得
        if($post_data['coach_flag'])
        {// コーチの場合
            $_user = $this->coachRepository->getCoachByLoginid($post_data['loginid']);
        }
        else
        {// 生徒の場合
            $_user = $this->coachRepository->getStudentByLoginid($post_data['loginid']);
        }
        if($_user)
        {
            // パスワード認証
            if(password_verify($post_data['passwd'], $_user['passwd']))
            {
                return $_user;
            }
        }

        return FALSE;
    }

    public function save($post_data)
    {
        DB::beginTransaction();

        $post_data->icon = 'default_icon';
        $post_data->status = 'active';
        $post_data->identified_status = 'unidentified';

        // 既存でいないかチェック
        if($post_data['coach_flag'])
        {// コーチの場合
            $post_data->penalty = 0;
            $_user = $this->coachRepository->getCoachByLoginid($post_data['loginid']);
        }
        else
        {// 生徒の場合
            $_user = $this->coachRepository->getStudentByLoginid($post_data['loginid']);
        }
        if(!$_user)
        {
            // 同じloginidのtempを取得
            if($temp_coach = $this->tempCoachRepository->getTempCoachByLoginid($post_data['loginid']))
            {
                // tempとパスワードが一致していれば
                if(password_verify($post_data['passwd'], $temp_coach['temp_passwd']))
                {
                    if($post_data['coach_flag'])
                    {// コーチの場合
                        $_user = $this->coachRepository->save($post_data);
                    }
                    else
                    {// 生徒の場合
                        $_user = $this->coachRepository->saveStudent($post_data);
                    }
                    if($_user)
                    {
                        // tempを削除
                        if($this->tempCoachRepository->deleteTempCoach($post_data))
                        {
                            // 再ロード
                            if($post_data['coach_flag'])
                            {// コーチの場合
                                $_user = $this->coachRepository->getCoachByLoginid($post_data['loginid']);
                            }
                            else
                            {// 生徒の場合
                                $_user = $this->coachRepository->getStudentByLoginid($post_data['loginid']);
                            }
                            if($_user)
                            {
                                DB::commit();
                                return $_user;
                            }
                        }
                    }
                }
            }
        }

        DB::rollBack();
        return FALSE;
    }

    public function getCoachByLoginid($loginid)
    {
        return $this->coachRepository->getCoachByLoginid($loginid);
    }

    public function getCoachById($id)
    {
        return $this->coachRepository->getCoachById($id);
    }

    public function getCoachList()
    {
        return $this->coachRepository->getCoachList();
    }

    public function update($post_data)
    {
        DB::beginTransaction();

        // 確認パスワードが一致していれば
        if($post_data['passwd'] == $post_data['re_passwd'])
        {
            if($post_data['coach_flag'])
            {
                if($this->coachRepository->updateCoach($post_data))
                {
                    // 再ロードしてreturn
                    if($coach = $this->coachRepository->getCoachByLoginid($post_data['loginid']))
                    {
                        DB::commit();
                        return $coach;
                    }
                }
            }
            else
            {
                if($this->coachRepository->updateStudent($post_data))
                {
                    // 再ロードしてreturn
                    if($student = $this->coachRepository->getStudentByLoginid($post_data['loginid']))
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

    public function updateIcon($post_data)
    {
        DB::beginTransaction();

        $login = session()->get('login');
        $post_data['loginid'] = $login['loginid'];

        // 拡張子を取得
        $ext = pathinfo($post_data->file('icon')->getClientOriginalName(), PATHINFO_EXTENSION);

        // ランダム文字列を取得
        $rand = md5(uniqid(rand(), true));

        // 名前を定義
        $post_data['icon_name'] = $rand.'.'.$ext;

        if($post_data['coach_flag'])
        {
            if($coach = $this->coachRepository->getCoachByLoginid($post_data['loginid']))
            {
                \Storage::disk('public')->delete('icon/'.$coach['icon']);
            }
            if($this->coachRepository->updateCoachIcon($post_data))
            {
                // 再ロードしてreturn
                if($re_coach = $this->coachRepository->getCoachByLoginid($post_data['loginid']))
                {
                    // 文字列のファイルがなければstore
                    $post_data->file('icon')->storeAs('public/icon', $post_data['icon_name']);
                    DB::commit();
                    return $re_coach;
                }
            }
        }
        else
        {
            if($student = $this->coachRepository->getStudentByLoginid($post_data['loginid']))
            {
                \Storage::disk('public')->delete('icon/'.$coach['icon']);
            }
            if($this->coachRepository->updateStudentIcon($post_data))
            {
                // 再ロードしてreturn
                if($re_student = $this->coachRepository->getStudentByLoginid($post_data['loginid']))
                {
                    $post_data->file('icon')->storeAs('public/icon', $post_data['icon_name']);
                    DB::commit();
                    return $re_student;
                }
            }
        }
        DB::rollBack();
        return FALSE;
    }

    public function updateTraining($post_data)
    {
        DB::beginTransaction();

        $post_data['loginid'] = $this->login['loginid'];

        if($this->coachRepository->updateTraining($post_data))
        {
            // 再ロードしてreturn
            if($coach = $this->coachRepository->getCoachByLoginid($post_data['loginid']))
            {
                DB::commit();
                return $coach;
            }
        }
        DB::rollBack();
        return FALSE;
    }

    public function left()
    {
        if($this->coach_flag)
        {
            if($coach = $this->coachRepository->getCoachById($this->login['id']))
            {
                if($coach['status'] != 'inactive')
                {
                    if($this->coachRepository->updateCoachLeft($this->login))
                    {
                        return 'left';
                    }
                }
                else{
                    return 'inactive';
                }
            }
        }
        else{
            if($student = $this->coachRepository->getStudentById($this->login['id']))
            {
                if($student['status'] != 'inactive')
                {
                    if($this->coachRepository->updateStudentLeft($this->login))
                    {
                        return 'left';
                    }
                }
                else{
                    return 'inactive';
                }
            }
        }
        return FALSE;
    }
}