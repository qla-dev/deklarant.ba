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
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']); // Get all invoices
        Route::get('/{id}', [InvoiceController::class, 'show']); // Get invoice by invoice ID
        Route::get('/users/{userId}', [InvoiceController::class, 'getInvoicesByUser']); // Get invoices by user ID
        Route::get('/suppliers/{supplierId}', [InvoiceController::class, 'getInvoicesBySupplier']); // Get invoices by supplier ID

        Route::post('/users/{userId}/suppliers/{supplierId}', [InvoiceController::class, 'store']);
        Route::put('/{invoiceId}', [InvoiceController::class, 'update']);
        Route::delete('/{invoiceId}', [InvoiceController::class, 'destroy']);
    });
    
    // INVOICE ITEM ROUTES
    Route::get('invoice-items', [InvoiceItemController::class, 'index']);
    Route::put('invoice-items/{invoiceItemId}',[InvoiceItemController::class, 'update']);
    Route::apiResource('invoices.invoice-items', InvoiceItemController::class)->except(['index', 'show']);
    Route::get('invoices/{invoiceId}/invoice-items', [InvoiceItemController::class,'getInvoiceItemsSingleInvoice']);

    // SUPPLIER ROUTES
    Route::apiResource('suppliers', SupplierController::class);

    // USER PACKAGES ROUTES
    Route::apiResource('user-packages', UserPackageController::class);
    Route::post('user-packages/users/{userId}/packages/{packageId}', [UserPackageController::class, 'store']);
    Route::delete('user-packages/users/{userId}/packages/{packageId}', [UserPackageController::class, 'destroy']);
});

Route::get('/user', function (Request $request) {
    return response()->json($request->user());
});
