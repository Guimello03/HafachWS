<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Guardian;
use App\Models\DeviceGroup;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $breadcrumbs = [
        ['label' => 'Dashboard'],
    ];

    $school = activeSchool();
    
    if (!$school) {
        abort(403, 'Nenhuma escola ativa');
    }

    $schoolUuid = $school->uuid;

    return view('dashboard', [
        'breadcrumbs' => $breadcrumbs,
        'totalStudents' => Student::where('school_id', $schoolUuid)->count(),
        'studentsWithPhoto' => Student::where('school_id', $schoolUuid)->whereNotNull('photo_path')->count(),
        'studentsWithoutPhoto' => Student::where('school_id', $schoolUuid)->whereNull('photo_path')->count(),

        'totalGuardians' => Guardian::where('school_id', $schoolUuid)->count(),
        'guardiansWithPhoto' => Guardian::where('school_id', $schoolUuid)->whereNotNull('photo_path')->count(),
        'guardiansWithoutPhoto' => Guardian::where('school_id', $schoolUuid)->whereNull('photo_path')->count(),

        'groups' => DeviceGroup::withCount(['commands' => function ($q) {
                $q->where('status', 'pending');
            }])
            ->where('school_id', $schoolUuid)
            ->having('commands_count', '>', 0)
            ->get(),
    ]);
}
}