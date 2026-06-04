<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TrackedJob;
use App\Models\GeneratedResume;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJobs =
            TrackedJob::count();

        $processedJobs =
            TrackedJob::where(
                'ai_processed',
                true
            )->count();

        $pendingJobs =
            TrackedJob::where(
                'ai_processed',
                false
            )->count();

        $bestMatch =
            TrackedJob::orderByDesc(
                'match_score'
            )->first();

        $averageMatch =
            round(
                TrackedJob::avg(
                    'match_score'
                ),
                2
            );

        $generatedResumes =
            GeneratedResume::count();

        $averageATS =
            round(
                GeneratedResume::avg(
                    'ats_score'
                ),
                2
            );

        return response()->json([
            'total_jobs' => $totalJobs,

            'processed_jobs' => $processedJobs,

            'pending_jobs' => $pendingJobs,

            'best_match' => $bestMatch
                ? [
                    'title' => $bestMatch->title,
                    'company' => $bestMatch->company,
                    'score' => $bestMatch->match_score
                ]
                : null,

            'average_match_score' => $averageMatch,

            'generated_resumes' => $generatedResumes,

            'average_ats_score' => $averageATS
        ]);
    }
}
