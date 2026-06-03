<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\TrackedJob;
use App\Models\ResumeTemplate;

use App\Services\ResumeGeneratorService;

class ResumeController extends Controller
{
    public function generate(
        int $jobId,
        ResumeGeneratorService $service
    )
    {
        $job = TrackedJob::findOrFail($jobId);

        $resume = ResumeTemplate::firstOrFail();

        $result = $service->generateATSResume(
            $job,
            $resume
        );

        return response()->json($result);
    }
}