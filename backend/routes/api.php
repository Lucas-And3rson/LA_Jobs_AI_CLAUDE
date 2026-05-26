<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrackedJobController;

Route::get('/tracked-jobs', [
    TrackedJobController::class,
    'index'
]);

Route::post('/tracked-jobs', [
    TrackedJobController::class,
    'store'
]);