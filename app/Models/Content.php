<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['content']; //保存したいカラム名が1つの場合
    use HasFactory;
}
