<?php

namespace App\Services;

use App\Repositories\TempCoachRepositoryInterface as TempCoachRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TempCoachService
{
    public function __construct(TempCoachRepository $tempCoachRepository)
    {
        $this->tempCoachRepository = $tempCoachRepository;
    }

    public function save($post_data)
    {
        DB::beginTransaction();

        // 既存loginidがあれば削除
        $temp_coach = $this->tempCoachRepository->getTempCoachByLoginid($post_data['loginid']);
        if($temp_coach)
        {
            if(!$this->deleteTempCoach($temp_coach))
            {
                DB::rollBack();
                return FALSE;
            }
        }
        $temp_passwd = substr(md5(uniqid(rand(), true)) , 0, 8);
        $temp_code = md5(uniqid(rand(), true));
        $post_data->temp_passwd = password_hash($temp_passwd, PASSWORD_DEFAULT);
        $post_data->temp_code = password_hash($temp_code, PASSWORD_DEFAULT);

        $this->tempCoachRepository->save($post_data);

        DB::commit();

        // 再ロード
        if($temp_coach = $this->tempCoachRepository->getTempCoachByLoginid($post_data['loginid']))
        {
            $temp_coach['temp_passwd'] = $temp_passwd;
            $temp_coach['temp_code'] = $temp_code;
            return $temp_coach;
        }
    }

    public function deleteTempCoach($temp_coach)
    {
        return $this->tempCoachRepository->deleteTempCoach($temp_coach);
    }

    public function getTempCoachByLoginid($loginid)
    {
        return $this->tempCoachRepository->getTempCoachByLoginid($loginid);
    }

    public function getTempCoachByCode($loginid, $temp_code)
    {
        // loginidのユーザがいるかどうか
        if($temp_coach = $this->tempCoachRepository->getTempCoachByLoginid($loginid))
        {
            // temp_codeのハッシュ値が一致するかどうか
            if(password_verify($temp_code, $temp_coach['temp_code']))
            {
                return $temp_coach;
            }
        }
        return FALSE;
    }
}