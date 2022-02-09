<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = [
        'loginid',
        'passwd',
        'name',
        'birth',

        'icon',
        
        'status',
        'identified_status',
        'penalty',

        'area',
        'fee',
        'avalable_datetime',
        'profile',
    ];

    use HasFactory;
}
