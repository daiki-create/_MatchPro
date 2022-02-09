<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface PayrollAccountRepositoryInterface
{
    public function getAccountByCoachId($coach_id);

    public function saveYuutyoAccount($post_data);
    public function saveOtherAccount($post_data);
    public function saveMajorAccount($post_data);

    public function deleteAccount($account);
}