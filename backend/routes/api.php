<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrackedJobController;
use App\Http\Controllers\Api\ResumeController;

Route::get('/tracked-jobs', [
    TrackedJobController::class,
    'index'
]);

Route::post('/tracked-jobs', [
    TrackedJobController::class,
    'store'
]);

Route::post(
    '/jobs/{jobId}/generate-resume',
    [ResumeController::class, 'generate']
);