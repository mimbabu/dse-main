<?php

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DataController;
use App\Http\Controllers\Api\V1\SectorController;
use App\Http\Controllers\Api\v1\CompanyController;
use App\Http\Controllers\Api\V1\PriceEarningController;
use App\Http\Controllers\Api\V1\DayEndSummaryController;
use App\Http\Controllers\Api\V1\LatestSharePriceController;
use App\Http\Controllers\Api\V1\TestController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::apiResource('latest-share-price', LatestSharePriceController::class)->except(['store', 'update']);
    Route::apiResource('price-earning', PriceEarningController::class)->except(['store', 'update']);
    Route::apiResource('day-end-summary', DayEndSummaryController::class)->except(['store', 'update']);
    Route::apiResource('data', DataController::class)->only(['index', 'show']);
    Route::apiResource('sector', SectorController::class)->only(['index', 'show']);
    Route::get('sector/{sector}/companies', [SectorController::class, 'companies'])->name('sector.companies');


    Route::get('/webtest', [TestController::class, 'index'])->name('webtest.index');
    Route::get('/foo', [TestController::class, 'foo'])->name('webtest.foo');
    Route::get('/breakfoo', [TestController::class, 'circuitfoo'])->name('webtest.circuitfoo');
    Route::get('/companyfoo/{companyCode}', [TestController::class, 'get_company_details'])->name('webtest.companyfoo');

    Route::apiResource('company', CompanyController::class)->only(['index', 'show']);
    Route::get('company/{company}/day-end-summary', [CompanyController::class, 'dayEndSummary'])->name('company.dayEndSummary');
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getAuthenticatedUser']);
});