<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackedJob extends Model
{
    protected $table = 'tracked_jobs';

    protected $fillable = [
        'title',
        'company',
        'description',
        'url',

        'seniority',
        'stack',
        'keywords',
        'english_required',
        'remote',
        'match_score',
        'ai_summary',
        'ai_processed',

        'strengths',
        'weaknesses',
        'match_reasons',
        'recommendation'
    ];

    protected $casts = [

        'stack' => 'array',
        'keywords' => 'array',

        'strengths' => 'array',
        'weaknesses' => 'array',
        'match_reasons' => 'array',

        'english_required' => 'boolean',
        'remote' => 'boolean',
        'ai_processed' => 'boolean',
    ];
}