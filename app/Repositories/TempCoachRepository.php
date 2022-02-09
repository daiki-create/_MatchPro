<?php

namespace App\Repositories;

use App\Models\TempCoach;
use Illuminate\Database\Eloquent\Model;

class TempCoachRepository implements TempCoachRepositoryInterface
{
    public function __construct(TempCoach $tempCoach)
    {
        $this->tempCoach = $tempCoach;   
    }

    public function save($post_data)
    {
        return $this->tempCoach
            ->updateOrCreate([
                'loginid' => $post_data->loginid,
                'temp_passwd' => $post_data->temp_passwd,
                'temp_code' => $post_data->temp_code,
                'coach_flag' => $post_data->coach_flag
        ]);
    }

    public function getTempCoachByLoginid($loginid)
    {
        $temp_coach = $this->tempCoach
            ->where('loginid', $loginid)
            ->first();

        if(!empty($temp_coach))
        {
            return $temp_coach;
        }
        return FALSE;
    }

    public function deleteTempCoach($temp_coach)
    {
        return $this->tempCoach
            ->where('loginid', $temp_coach['loginid'])
            ->delete();
    }
}