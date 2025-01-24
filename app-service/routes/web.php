<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TariffController;


Route::post('/search', [TariffController::class, 'search'])->name('search');
Route::post('/upload', [TariffController::class, 'upload'])->name('upload');
Route::get('/', function () {
    return view('welcome');
});
