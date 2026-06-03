<?php

namespace App\Jobs;

use App\Models\TrackedJob;
use App\Services\GroqService;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\UserProfile;

class ProcessTrackedJob implements ShouldQueue
{
    use Queueable;

    public int $jobId;

    public function __construct(int $jobId)
    {
        $this->jobId = $jobId;
    }

    public function handle(): void
    {
        $profile = UserProfile::first();

        logger('JOB STARTED', [
            'job_id' => $this->jobId
        ]);

        $job = TrackedJob::find($this->jobId);

        if (!$job) {

            logger('JOB NOT FOUND');

            return;
        }

        logger('JOB FOUND', [
            'title' => $job->title
        ]);

        $groq = new GroqService();

        $analysis = $groq->analyzeJob([
            'title' => $job->title,
            'company' => $job->company,
            'description' => $job->description,

            'candidate' => [

                'seniority' => $profile->seniority,

                'years_experience' => $profile->years_experience,

                'stack' => $profile->stack,

                'keywords' => $profile->keywords,

                'desired_roles' => $profile->desired_roles,

                'english' => $profile->english,

                'remote_only' => $profile->remote_only,

                'location' => $profile->location,

                'preferred_locations' => $profile->preferred_locations,

                'salary_expectation' => $profile->salary_expectation
            ]
        ]);

        logger('AI ANALYSIS', [
            'analysis' => $analysis
        ]);

        if (empty($analysis)) {

            logger('EMPTY ANALYSIS');

            return;
        }

        $job->update([
            'seniority' => $analysis['seniority'] ?? null,
            'stack' => $analysis['stack'] ?? [],
            'keywords' => $analysis['keywords'] ?? [],
            'english_required' => $analysis['english_required'] ?? false,
            'remote' => $analysis['remote'] ?? false,
            'match_score' => $analysis['match_score'] ?? 0,
            'strengths' => $analysis['strengths'] ?? [],
            'weaknesses' => $analysis['weaknesses'] ?? [],
            'match_reasons' => $analysis['match_reasons'] ?? [],
            'recommendation' => $analysis['recommendation'] ?? null,
            'ai_summary' => $analysis['ai_summary'] ?? null,
            'ai_processed' => true,
        ]);

        logger('JOB UPDATED');
    }
}