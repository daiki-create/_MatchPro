<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCoach extends Model
{
    protected $fillable = [
        'loginid',
        'temp_passwd',
        'temp_code',
        'coach_flag'
    ];

    use HasFactory;
}
