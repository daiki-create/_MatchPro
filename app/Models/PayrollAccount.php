<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollAccount extends Model
{
    protected $fillable = [
        'coach_id',
        'bank',
        'bank_code',
        'branch',
        'branch_code',
        'account_type',
        'symbol_number',
        'name',
    ];

    use HasFactory;
}
