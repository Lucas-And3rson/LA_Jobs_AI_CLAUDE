<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedResume extends Model
{
    protected $fillable = [

        'tracked_job_id',
        'ats_score',
        'file_path',
        'resume_json',
        'candidate_name',
        'job_title',
        'company'
    ];

    protected $casts = [
        'resume_json' => 'array',
    ];

    public function job()
    {
        return $this->belongsTo(
            TrackedJob::class,
            'tracked_job_id'
        );
    }
}