<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\v1\TestController;
use App\Http\Controllers\PriceEarningController;
use App\Http\Controllers\DayEndSummaryController;
use App\Http\Controllers\LatestSharePriceController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('role:admin')->group(function () {
    Route::resource('admin', AdminController::class)->only('index');
});


Route::get('latest-share-prices', [LatestSharePriceController::class, 'index'])->name('LatestSharePrice.index');
Route::get('store-latest-share-prices', [LatestSharePriceController::class, 'store'])->name('LatestSharePrice.store');

Route::get('price-earning', [PriceEarningController::class, 'index'])->name('PriceEarning.index');
Route::get('store-price-earning', [PriceEarningController::class, 'store'])->name('PriceEarning.store');

Route::get('day-end-summary', [DayEndSummaryController::class, 'index'])->name('DayEndSummary.index');
Route::get('store-day-end-summary', [DayEndSummaryController::class, 'store'])->name('DayEndSummary.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';



Route::get('list', [DseController::class, 'list']);
Route::get('idx', [DseController::class, 'idx']);
Route::get('mki', [DseController::class, 'mki']);



// Test Routes
Route::get('test/company/{companyCode}', [TestController::class, 'company']);