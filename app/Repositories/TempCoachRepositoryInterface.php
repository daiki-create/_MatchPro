<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface TempCoachRepositoryInterface
{
    public function save($post_data);

    public function getTempCoachByLoginid($loginid);

    public function deleteTempCoach($temp_coach);
}