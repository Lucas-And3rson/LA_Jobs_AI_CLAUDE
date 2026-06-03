<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [

        'name',
        'email',

        'seniority',
        'years_experience',

        'stack',
        'keywords',

        'desired_roles',

        'english',

        'remote_only',

        'location',
        'preferred_locations',

        'salary_expectation'
    ];

    protected $casts = [

        'stack' => 'array',
        'keywords' => 'array',

        'desired_roles' => 'array',
        'preferred_locations' => 'array',

        'english' => 'boolean',
        'remote_only' => 'boolean',
    ];
}