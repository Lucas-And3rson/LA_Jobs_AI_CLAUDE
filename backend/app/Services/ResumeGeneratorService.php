<?php

namespace App\Services;

use App\Models\TrackedJob;
use App\Models\ResumeTemplate;
use App\Models\GeneratedResume;

class ResumeGeneratorService
{
    public function __construct(
        private GroqService $groq,
        private DocxResumeService $docx
    ) {
    }

    public function generateATSResume(
        TrackedJob $job,
        ResumeTemplate $resume
    ): array {

        $masterResume = json_decode(
            $resume->content,
            true
        );

        $atsResume = $this->groq->generateATSResume(
            [
                'title' => $job->title,
                'company' => $job->company,
                'description' => $job->description,
            ],
            $masterResume
        );
        
        $atsResume['languages'] = $masterResume['languages'] ?? [];

        $file = $this->docx->generate(
            $atsResume
        );
        
        GeneratedResume::create([
            'tracked_job_id' => $job->id,

            'candidate_name' => $atsResume['name'] ?? null,

            'job_title' => $job->title,

            'company' => $job->company,

            'ats_score' => $atsResume['ats_score'] ?? 0,

            'file_path' => $file,

            'resume_json' => $atsResume,
        ]);

        return [
            'job' => $job->title,
            'resume' => $atsResume,
            'file' => $file
        ];
    }
}