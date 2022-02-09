<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'coach_id',
        'student_id',
        'date',
        'start_time',
        'end_time',
        'fee',
        'num',
        'content',
        'charged_flag'
    ];

    use HasFactory;
}
