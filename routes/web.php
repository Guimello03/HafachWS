<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuardianController;

//Rotas de Responsáveis
Route::delete('guardians/{guardian}/remove-photo',[GuardianController::class,('removePhoto')])->name('guardians.remove-photo');
Route::resource('guardians', GuardianController::class);
//Rotas de Alunos
Route::delete('students/{student}/remove-photo',[StudentController::class,('removePhoto')])->name('students.remove-photo');
Route::resource('students', StudentController::class);

//Jettstream
Route::get('/', function () {
    return view('welcome');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
