<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransController::class, 'callback']);

Route::prefix('v1/tracking')->group(function () {
    Route::post('/update', [\App\Http\Controllers\Api\CarTrackingController::class, 'update']);
    Route::get('/latest', [\App\Http\Controllers\Api\CarTrackingController::class, 'getLatestLocations']);
});
