<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\FunctionaryController;
use App\Http\Controllers\SchoolSelectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// Rotas da aplicação

 Route::redirect('/', 'login')->name('home');
 Route::get('/schools-select', [SchoolController::class, 'getByClient'])->middleware('auth');
 Route::post('/schools-select', [SchoolSelectionController::class, 'store'])->middleware('auth');
 


// Dashboard (acessível para todos logados)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'ensure.school.selected'])->name('dashboard');
//------------------- Área de Seleção de Escola-----------------
Route::middleware(['auth','role:super_admin|client_admin'])->group(function () {
    Route::get('/select-school', [SchoolSelectionController::class, 'index'])->name('select.school');
    Route::post('/select-school', [SchoolSelectionController::class, 'clientAdmin'])->name('select.school.store');
});
// ----------------- Área Super Admin -----------------
Route::middleware(['auth','ensure.school.selected' ,  'role:super_admin'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::post('clients-and-school', [ClientController::class, 'storeClientAndSchool']);
    Route::get('admin/dashboard', [ClientController::class, 'adminDashboard'])->name('admin.dashboard');
});

// ----------------- Área Super Admin + Client Admin -----------------
Route::middleware(['auth','ensure.school.selected' ,  'role:super_admin|client_admin'])->group(function () {
    Route::resource('schools', SchoolController::class)->except(['create', 'edit']);
    
});

// ----------------- Área Super Admin + Client Admin + School Director -----------------
Route::middleware(['auth','ensure.school.selected' ,  'role:super_admin|client_admin|school_director'])->group(function () {
    // Students
    Route::resource('students', StudentController::class);
    Route::put('students/{student}/photo', [StudentController::class, 'updatePhoto'])->name('students.updatePhoto');
    Route::get('students/{student}/photo-modal', [StudentController::class, 'photoModal'])->name('students.photo-modal');
    Route::delete('students/{student}/remove-photo', [StudentController::class, 'removePhoto'])->name('students.remove-photo');
    Route::patch('students/{student}/remove-guardian', [StudentController::class, 'removeGuardian'])->name('students.remove-guardian');

    // Guardians
    Route::resource('guardians', GuardianController::class);
    Route::put('guardians/{guardian}/photo', [GuardianController::class, 'updatePhoto'])->name('guardians.updatePhoto');
    Route::get('guardians/{guardian}/photo-modal', [GuardianController::class, 'photoModal'])->name('guardians.photo-modal');
    Route::delete('guardians/{guardian}/remove-photo', [GuardianController::class, 'removePhoto'])->name('guardians.remove-photo');

    // Functionaries
    Route::resource('functionaries', FunctionaryController::class);
    Route::put('functionaries/{functionary}/photo', [FunctionaryController::class, 'updatePhoto'])->name('functionaries.updatePhoto');
    Route::get('functionaries/{functionary}/photo-modal', [FunctionaryController::class, 'photoModal'])->name('functionaries.photo-modal');
    Route::delete('functionaries/{functionary}/remove-photo', [FunctionaryController::class, 'removePhoto'])->name('functionaries.remove-photo');
});

// Auth routes

