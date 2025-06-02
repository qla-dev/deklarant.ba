<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\InvoiceItemController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\UserPackageController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ImporterController;
use App\Http\Controllers\Api\TariffRateController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FileManagerController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\CanScan;
use App\Http\Middleware\IsMineProfile;

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

    // INVOICE ROUTES
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']); // Get all invoices
        Route::get('/{id}', [InvoiceController::class, 'show']); // Get invoice by invoice ID
        Route::get('/users/{userId}', [InvoiceController::class, 'getInvoicesByUser']); // Get invoices by user ID
        Route::get('/suppliers/{supplierId}', [InvoiceController::class, 'getInvoicesBySupplier']); // Get invoices by supplier ID

        Route::post('/users/{userId}/suppliers/{supplierId}', [InvoiceController::class, 'store']);
        Route::post('/users/{userId}/suppliers/{supplierId}/form', [InvoiceController::class, 'storeInvoicesWithItems']);
        Route::put('/{invoiceId}', [InvoiceController::class, 'update']);
        Route::delete('/{invoiceId}', [InvoiceController::class, 'destroy']);
    });

    Route::get('/invoice-info/{invoiceId}', [InvoiceController::class, 'getInvoiceInfoById']);


    // INVOICE ITEM ROUTES
    Route::get('invoice-items', [InvoiceItemController::class, 'index']);
    Route::put('invoice-items/{invoiceItemId}', [InvoiceItemController::class, 'update']);
    Route::apiResource('invoices.invoice-items', InvoiceItemController::class)->except(['index', 'show']);
    Route::get('invoices/{invoiceId}/invoice-items', [InvoiceItemController::class, 'getInvoiceItemsSingleInvoice']);


    // SUPPLIER ROUTES
    Route::apiResource('suppliers', SupplierController::class);
    Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->middleware(IsAdmin::class);

    // IMPORTER ROUTES
    Route::apiResource('importers', ImporterController::class);
    Route::put('importers/{importer}', [ImporterController::class, 'update'])->middleware(IsAdmin::class);


    // USER PACKAGES ROUTES
    Route::apiResource('user-packages', UserPackageController::class);
    Route::post('user-packages/users/{userId}/packages/{packageId}', [UserPackageController::class, 'store']);
    Route::delete('user-packages/users/{userId}/packages/{packageId}', [UserPackageController::class, 'destroy']);


    // STATISTICS ROUTES
    Route::get('/statistics', [StatsController::class, 'getStatistics']);
    Route::get('/statistics/users/{id}', [StatsController::class, 'getUserStatisticsById'])->where('id', '[0-9]+');
    Route::get('/statistics/users', [StatsController::class, 'getAllUserStatistics']);
    Route::get('/statistics/suppliers/{id}', [StatsController::class, 'getEntityStatisticsById'])->where('id', '[0-9]+');
    Route::get('/statistics/importers/{id}', [StatsController::class, 'getEntityStatisticsById'])->where('id', '[0-9]+');

    // SUPPLIERS' PROFIT
    Route::get('/suppliers/{id}/annual-profit', [StatsController::class, 'getEntityAnnualProfit']);
    Route::get('/suppliers/{id}/last-year-profit', [StatsController::class, 'getEntityLastYearProfit']);
    // IMPORTERS' PROFIT
    Route::get('/importers/{id}/annual-profit', [StatsController::class, 'getEntityAnnualProfit']);
    Route::get('/importers/{id}/last-year-profit', [StatsController::class, 'getEntityLastYearProfit']);


    // USERS ROUTES

    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUserById']);
    Route::put('/users/{id}', [UserController::class, 'updateUser'])->middleware(IsMineProfile::class);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);


    // FILE MANAGER ROUTES

    Route::post('/storage/uploads', [FileManagerController::class, 'uploadFile'])->middleware(CanScan::class);
    Route::post('/apps-file-manager/create-folder', [FileManagerController::class, 'createFolder']);
    Route::get('/folders/{path?}', [FileManagerController::class, 'showFolder'])->where('path', '.*');

    // INVOICE UPLOAD ROUTES
    Route::post('/storage/invoice-uploads', [FileManagerController::class, 'uploadInvoiceFile'])->middleware(CanScan::class);

    // SCAN ROUTES
    Route::post('/invoices/{invoiceId}/scan', [InvoiceController::class, 'scan']);
    Route::get('/invoices/{id}/scan', [InvoiceController::class, 'getScanStatus']);
    Route::get('/invoices/{id}/scan/result', [InvoiceController::class, 'getScanResult']);
    Route::get('/invoices/{id}/scan/parties', [InvoiceController::class, 'getScanParties']);
});

    Route::post('/logoutUser', [AuthController::class, 'logoutUser']);

// LOGIN AND REGISTER ROUTES
Route::prefix('auth')->middleware('web')->group(function () {
    Route::post('/register', [AuthController::class, 'registerUser']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/my-token', [AuthController::class, 'myToken']);
});

Route::get('/exchange-rates', [ExchangeRateController::class, 'getRates']);
