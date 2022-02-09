<?php

namespace App\Repositories;

use App\Models\PayrollAccount;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class PayrollAccountRepository implements PayrollAccountRepositoryInterface
{
    public function __construct(PayrollAccount $payrollAccount)
    {
        $this->payrollAccount = $payrollAccount;
    }

    public function getAccountByCoachId($coach_id)
    {
        $payrollAccount = $this->payrollAccount
        ->where('coach_id', $coach_id)
        ->first();

        if($payrollAccount)
        {
            return $payrollAccount;
        }
        return FALSE;
    }

    public function saveYuutyoAccount($post_data)
    {
        return $this->payrollAccount
        ->create([
            'coach_id' => $post_data['coach_id'],
            'bank' => $post_data['bank_select'],
            'branch' => $post_data['branch'],
            'accoun_ttype' => $post_data['account_type'],
            'symbol_number' => $post_data['symbol_number'],
            'name' => $post_data['name'],
        ]);
    }

    public function saveMajorAccount($post_data)
    {
        return $this->payrollAccount
        ->create([
            'coach_id' => $post_data['coach_id'],
            'bank' => $post_data['bank_select'],
            'branch' => $post_data['branch'],
            'branch_code' => $post_data['branch_code'],
            'account_type' => $post_data['account_type'],
            'symbol_number' => $post_data['symbol_number'],
            'name' => $post_data['name'],
        ]);
    }

    public function saveOtherAccount($post_data)
    {
        return $this->payrollAccount
        ->create([
            'coach_id' => $post_data['coach_id'],
            'bank' => $post_data['bank'],
            'bank_code' => $post_data['bank_code'],
            'branch' => $post_data['branch'],
            'branch_code' => $post_data['branch_code'],
            'account_type' => $post_data['account_type'],
            'symbol_number' => $post_data['symbol_number'],
            'name' => $post_data['name'],
        ]);
    }

    public function deleteAccount($account)
    {
        return $this->payrollAccount
            ->where('coach_id', $account['coach_id'])
            ->delete();
    }
}