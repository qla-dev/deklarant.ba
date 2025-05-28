<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);


//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::middleware('auth:web')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');
    Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

});


//Route::view('/detalji-deklaracije', 'detalji-deklaracije')->name('invoices.view');
Route::get('/detalji-deklaracije/{id}', function ($id) {
    return view('detalji-deklaracije'); // just returns the Blade view
})->name('invoices.view');

// Custom logout route
Route::post('/custom-logout', [App\Http\Controllers\Api\AuthController::class, 'logoutUser'])->name('logout2');

