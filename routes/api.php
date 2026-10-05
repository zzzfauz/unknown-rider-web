<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\TrackingController;

Route::post('/tracking/update', [TrackingController::class, 'updateLokasi']);
Route::get('/tracking/get', [TrackingController::class, 'getSemuaLokasi']);