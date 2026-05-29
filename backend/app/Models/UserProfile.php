<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [

        'name',
        'email',
        'seniority',
        'stack',
        'keywords',
        'english',
        'remote_only',
        'location',
        'salary_expectation'
    ];

    protected $casts = [

        'stack' => 'array',
        'keywords' => 'array',
        'english' => 'boolean',
        'remote_only' => 'boolean',
    ];
}