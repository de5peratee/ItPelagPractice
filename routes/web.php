<?php

use App\Http\Controllers\LeakyBucketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/leaky-bucket', [LeakyBucketController::class, 'index'])->name('leaky-bucket.index');
Route::get('/leaky-bucket/state', [LeakyBucketController::class, 'getState']);
Route::post('/leaky-bucket/allow', [LeakyBucketController::class, 'allowRequest']);