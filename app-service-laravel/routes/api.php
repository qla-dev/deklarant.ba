<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\InvoiceItemController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\UserPackageController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\TariffRateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Routes (Accessible Without Authentication)
Route::apiResource('packages', PackageController::class);
Route::apiResource('tariff-rates', TariffRateController::class);

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('invoice-items', InvoiceItemController::class);
    Route::apiResource('user-packages', UserPackageController::class);
    Route::apiResource('suppliers', SupplierController::class);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
});

