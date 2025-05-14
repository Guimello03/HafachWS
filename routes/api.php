<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DeviceCommandController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\DeviceEventController;
use App\Http\Controllers\UserStatusController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Aqui é onde você registra as rotas da sua API. Essas rotas são carregadas
| pelo RouteServiceProvider dentro de um grupo que atribui o middleware "api".
|
*/

// Rota de usuário autenticado

Route::post('/', function () {
    return response()->json(['message' => 'Rota inválida'], 404);
});
// Rotas de eventos de dispositivos
Route::get('/device/push', [DeviceCommandController::class, 'getPendingCommand']);
Route::post('/device/result', [DeviceCommandController::class, 'storeCommandResult']);
Route::post('/notifications/dao', [DeviceEventController::class, 'receiveDao']);


// Busca ultimo acesso do aluno 

Route::get('/users/last-status', [UserStatusController::class, 'index']);
