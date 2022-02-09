<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'loginid',
        'passwd',
        'name',
        'birth',
        'icon',
        'status',
        'identified_status',
    ];

    use HasFactory;
}
