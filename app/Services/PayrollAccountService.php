<?php

namespace App\Services;

use App\Repositories\PayrollAccountRepositoryInterface as PayrollAccountRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PayrollAccountService
{
    public function __construct(
        PayrollAccountRepository $payrollAccountRepository
    )
    {
        $this->payrollAccountRepository = $payrollAccountRepository;
    }

    public function getAccountByCoachId()
    {
        $login = session()->get('login');
        $account = $this->payrollAccountRepository->getAccountByCoachId($login['id']);
        if($account)
        {
            return $account;
        }
        return FALSE;
    }

    public function save($post_data)
    {
        DB::beginTransaction();

        if($account = $this->payrollAccountRepository->getAccountByCoachId($post_data['coach_id']))
        {
            if(!$this->payrollAccountRepository->deleteAccount($account))
            {
                DB::rollBack();
                return FALSE;
            }
        }
        if($post_data['bank_select'] == 'yuutyo')
        {
            if($this->payrollAccountRepository->saveYuutyoAccount($post_data))
            {
                DB::commit();
                return TRUE;
            }
        }
        elseif($post_data['bank_select'] == 'other')
        {
            if($this->payrollAccountRepository->saveOtherAccount($post_data))
            {
                DB::commit();
                return TRUE;
            }
        }
        elseif($post_data['bank_select'] == 'none')
        {
            DB::rollBack();
            return FALSE;
        }
        else
        {
            if($this->payrollAccountRepository->saveMajorAccount($post_data))
            {
                DB::commit();
                return TRUE;
            }
        }
        DB::rollBack();
        return FALSE;
    }
}