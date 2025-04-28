<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceEventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Control id
Route::get('/device/event/push', [DeviceEventController::class, 'receive']);

Route::post('/device/event/result', [DeviceEventController::class, 'result']);
