<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DeviceEventController;
use App\Http\Controllers\SchoolController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Aqui é onde você registra as rotas da sua API. Essas rotas são carregadas
| pelo RouteServiceProvider dentro de um grupo que atribui o middleware "api".
|
*/

// Rota de usuário autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas de eventos de dispositivos
Route::get('/device/event/push', [DeviceEventController::class, 'receive']);
Route::post('/device/event/result', [DeviceEventController::class, 'result']);
