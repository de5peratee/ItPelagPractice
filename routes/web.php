<?php

use App\Http\Controllers\BloomFilterController;
use App\Http\Controllers\LeakyBucketController;
use App\Http\Controllers\LossyCountController;
use App\Http\Controllers\MainMenuController;
use App\Models\LossyCount;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/main');
});


Route::get('/main', [MainMenuController::class, 'index'])->name('main-menu.index');

Route::get('/lossycount', [LossyCountController::class, 'index'])->name('lossycount.index');
Route::post('/lossycount/process', [LossyCountController::class, 'process'])->name('lossycount.process');

Route::get('/bloom', [BloomFilterController::class, 'index'])->name('bloom.index');
Route::post('/bloom/add', [BloomFilterController::class, 'add'])->name('bloom.add');
Route::post('/bloom/check', [BloomFilterController::class, 'check'])->name('bloom.check');

Route::get('/leaky-bucket', [LeakyBucketController::class, 'index'])->name('leaky-bucket.index');
Route::get('/leaky-bucket/state', [LeakyBucketController::class, 'getState']);
Route::post('/leaky-bucket/allow', [LeakyBucketController::class, 'allowRequest']);