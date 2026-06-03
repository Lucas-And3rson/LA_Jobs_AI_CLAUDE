<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeTemplate extends Model
{
    protected $fillable = [
        'name',
        'content'
    ];
}