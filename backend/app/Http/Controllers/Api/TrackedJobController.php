<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrackedJob;
use App\Jobs\ProcessTrackedJob;

class TrackedJobController extends Controller
{
    public function index(Request $request)
    {
        $query = TrackedJob::query();

        if ($request->filled('stack')) {

            $query->whereJsonContains(
                'stack',
                $request->stack
            );
        }

        if ($request->filled('seniority')) {

            $query->where(
                'seniority',
                'ILIKE',
                '%' . $request->seniority . '%'
            );
        }

        if ($request->filled('remote')) {

            $query->where(
                'remote',
                true
            );
        }

        return response()->json(
            $query
                ->latest()
                ->get()
        );
    }

    public function store(Request $request)
    {
        $job = TrackedJob::updateOrCreate(
            [
                'url' => $request->url
            ],
            [
                'title' => $request->title,
                'company' => $request->company,
                'description' => $request->description,
            ]
        );

        ProcessTrackedJob::dispatch($job->id);

        return response()->json([
            'success' => true,
            'job' => $job
        ]);
    }
}