<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationDocument extends Model
{
    protected $fillable = [
        'coach_id',
        'student_id',
        'document_type',
        'img_front',
        'img_back',
    ];

    use HasFactory;
}
