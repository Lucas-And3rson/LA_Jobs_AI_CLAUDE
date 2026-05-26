<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Jobs\TestQueueJob;

Route::get('/queue-test', function () {

    TestQueueJob::dispatch();

    return 'QUEUE ENVIADA';
});