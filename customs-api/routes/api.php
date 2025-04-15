<?php

use App\Http\Controllers\Api\UploadController;
use Illuminate\Support\Facades\Route;

Route::post('/upload', [UploadController::class, 'store']);
Route::get('/tasks/{task}', [UploadController::class, 'show']);
Route::get('/tasks/{task}/result', [UploadController::class, 'result']);
