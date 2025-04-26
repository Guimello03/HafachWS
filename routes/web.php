<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuardianController;

//Rotas de Responsáveis

Route::delete('guardians/{guardian}/remove-photo',[GuardianController::class,'removePhoto'])->name('guardians.remove-photo');
Route::get('guardians/{guardian}/photo-modal', [GuardianController::class, 'photoModal'])->name('guardians.photo-modal');
Route::put('/guardians/{guardian}/photo', [GuardianController::class, 'updatePhoto'])->name('guardians.updatePhoto');
Route::resource('guardians', GuardianController::class);
//Rotas de Alunos


Route::put('/students/{student}/photo', [StudentController::class, 'updatePhoto'])->name('students.updatePhoto');
Route::delete('students/{student}/remove-photo',[StudentController::class,'removePhoto'])->name('students.remove-photo');
Route::get('students/{student}/photo-modal', [StudentController::class, 'photoModal'])->name('students.photo-modal');

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
