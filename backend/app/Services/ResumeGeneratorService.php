<?php

namespace App\Services;

use App\Models\TrackedJob;
use App\Models\ResumeTemplate;

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

        $file = $this->docx->generate(
            $atsResume
        );

        return [
            'job' => $job->title,
            'resume' => $atsResume,
            'file' => $file
        ];
    }
}