<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\FunctionaryController;
use App\Http\Controllers\SchoolSelectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceGroupController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\QrController;




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
Route::middleware(['auth','ensure.school.selected'])->get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

//------------------- Área de Seleção de Escola-----------------
Route::middleware(['auth','role:super_admin|client_admin'])->group(function () {
    Route::get('/select-school', [SchoolSelectionController::class, 'index'])->name('select.school');
   
});
// ----------------- Área Super Admin -----------------
Route::middleware(['auth','ensure.school.selected' ,  'role:super_admin'])->group(function () {
    Route::get('/school/settings/tolerance', [SchoolSettingController::class, 'edit'])->name('school.settings.tolerance');
    Route::post('/school/settings/tolerance', [SchoolSettingController::class, 'update']);
    Route::get('/clients/{client}/school.create', [SchoolController::class, 'create'])->name('clients.schools.index');
    Route::post('/schools.create', [SchoolController::class, 'store'])->name('schools.store');
    Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
    Route::put('/schools/{school}', [SchoolController::class, 'update'])->name('schools.update');



    Route::resource('clients', ClientController::class);
    Route::get('/clients/{client}/schools', [ClientController::class, 'schools'])->name('clients.schools');
    Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
    Route::post('clients-and-school', [ClientController::class, 'storeClientAndSchool']);  
    Route::get('admin/dashboard', [ClientController::class, 'adminDashboard'])->name('admin.dashboard');
    
});

// ----------------- Área Super Admin + Client Admin -----------------
Route::middleware(['auth','ensure.school.selected' ,  'role:super_admin|client_admin'])->group(function () {
    //Route::resource('schools', SchoolController::class)->except(['create', 'edit']);
    Route::post('/schools/director', [UserController::class, 'storeDirector'])
    ->name('schools.director.store');
    
});

// ----------------- Área Super Admin + Client Admin + School Director -----------------
Route::middleware(['auth','ensure.school.selected' ,  'role:super_admin|client_admin|school_director'])->group(function () {
    // Students
    Route::resource('students', StudentController::class);
    Route::put('students/{student}/photo', [StudentController::class, 'updatePhoto'])->name('students.updatePhoto');
    Route::get('students/{student}/photo-modal', [StudentController::class, 'photoModal'])->name('students.photo-modal');
    Route::delete('students/{student}/remove-photo', [StudentController::class, 'removePhoto'])->name('students.remove-photo');
    Route::patch('students/{student}/remove-guardian', [StudentController::class, 'removeGuardian'])->name('students.remove-guardian');
    //Route::get('students/search-students', [StudentController::class, 'searchStudents'])->name('students.search');

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

    // School
    Route::get('/schools', [SchoolController::class, 'dashboard'])->name('school.dashboard');
    Route::put('/school/{user}/update-password', [UserController::class, 'updatePassword'])
    ->name('director.update-password');

    // Device Groups
   
Route::delete('/device_group/{deviceGroup/destroy', [DeviceGroupController::class, 'destroy']);
    Route::put('/device_groups/{deviceGroup}', [DeviceGroupController::class, 'update']);
    Route::post('/groups_groups/auto-target', [DeviceGroupController::class, 'setAutoTargets'])
    ->name('groups.auto_target');
    Route::post('/device-groups', [\App\Http\Controllers\DeviceGroupController::class, 'store'])
    ->name('device_groups.store');
    Route::resource('groups', \App\Http\Controllers\DeviceGroupController::class);
   
    Route::get('/monitor', function () {
        return view('dashboard');
    });
    Route::get('/qr-preview', [QrController::class, 'preview'])->name('qr.preview');
    Route::post('/qr-download-pdf', [QrController::class, 'downloadPdf'])->name('qr.download.pdf');



});


Route::prefix('reports')->middleware(['web', 'auth', 'ensure.school.selected', 'ensure.report.school.scope'])->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');

    
    Route::get('/student-attendance', [ReportController::class, 'studentAttendanceView'])->name('student_attendance.view');
    Route::get('/student-attendance/data', [ReportController::class, 'studentAttendance'])->name('student_attendance');
    Route::get('/guardian-attendance', [ReportController::class, 'guardianAttendanceView'])->name('guardian_attendance.view');
    Route::get('/guardian-attendance/data', [ReportController::class, 'guardianAttendance'])->name('guardian_attendance');
    Route::get('/functionary-attendance', [ReportController::class, 'functionaryAttendanceView'])->name('functionary_attendance.view');
    Route::get('/functionary-attendance/data', [ReportController::class, 'functionaryAttendance'])->name('functionary_attendance');
    Route::get('/users-without-photo/view', [ReportController::class, 'usersWithoutPhotoView'])->name('users_without_photo.view');



    // Relatório de usuários sem foto
    Route::get('/users-without-photo', [ReportController::class, 'usersWithoutPhoto'])->name('users_without_photo');

    // 🔍 Busca de pessoas (autocomplete)
    Route::get('/person-search', function (\Illuminate\Http\Request $request) {
        $term = $request->get('term');
        $schoolId = session('school_id');
        $type = $request->get('type'); // 'student', 'guardian', 'functionary'

        $modelMap = [
            'student' => \App\Models\Student::class,
            'guardian' => \App\Models\Guardian::class,
            'functionary' => \App\Models\Functionary::class,
        ];

        if (!isset($modelMap[$type]) || !$term || !$schoolId) {
            return response()->json([]);
        }

        return $modelMap[$type]::where('school_id', $schoolId)
            ->where('name', 'like', "%{$term}%")
            ->select('uuid', 'name')
            ->limit(20)
            ->get();
    })->name('person_search');
});



// Auth routes

