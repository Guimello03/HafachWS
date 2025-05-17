<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DeviceEvent;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\Functionary;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function index()
    {
        Log::info('Entrou em reports.index', [
            'user_id' => optional(auth()->user())->id,
            'school_id' => session('school_id'),
            'school_uuid' => app()->bound('school_uuid') ? app('school_uuid') : null,
        ]);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Relatórios', 'url' => ''],
        ];

        return view('reports.index', compact('breadcrumbs'));
    }

    /**
     * View do relatório de frequência de alunos
     */
    public function studentAttendanceView()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Relatórios', 'url' => route('reports.index')],
            ['label' => 'Frequência de Alunos', 'url' => ''],
        ];
$type = 'student';

    return view('reports.student-attendance', compact('breadcrumbs', 'type'));
}

    /**
     * Endpoint AJAX de frequência de alunos
     */
    public function studentAttendance(Request $request)
{
   

    if (!app()->bound('school_uuid') && session('school_id')) {
        app()->instance('school_uuid', session('school_id'));
    }

    $schoolUuid = app()->bound('school_uuid') ? app('school_uuid') : null;

    if (!$schoolUuid) {
        Log::warning('school_uuid não definido em studentAttendance', [
            'user_id' => optional(auth()->user())->id
        ]);
        return response()->json(['error' => 'school_uuid não definido.'], 422);
    }

    // 🔁 Corrige o bug ao duplicar data antes da validação
    if ($request->filled('start_date') && !$request->filled('end_date')) {
        $request->merge(['end_date' => $request->start_date]);
    }

    // ✅ Validação depois do merge
    $validated = $request->validate([
        'direction' => 'required|in:Entrada,Saída',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'student_id' => 'nullable|uuid',
    ]);
    if ($validated['start_date'] === $validated['end_date']) {
    $validated['end_date'] = Carbon::parse($validated['end_date'])->endOfDay()->toDateTimeString();
}

    $direction = $validated['direction'] === 'Entrada' ? 'in' : 'out';

    $query = DeviceEvent::query()
        ->where('person_type', Student::class)
        ->where('direction', $direction)
        ->whereHas('person', function ($q) use ($schoolUuid) {
            $q->where('school_id', $schoolUuid);
        });

    if (!empty($validated['start_date'])) {
        $query->where('date', '>=', $validated['start_date']);
    }

    if (!empty($validated['end_date'])) {
        $query->where('date', '<=', $validated['end_date']);
    }

    if (!empty($validated['student_id'])) {
        $query->where('person_id', $validated['student_id']);
    }

    $results = $query->with('person')->orderBy('date', 'desc')->get();

    return response()->json($results);
}


    public function guardianAttendanceView()
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Relatórios', 'url' => route('reports.index')],
        ['label' => 'Frequência de Responsáveis', 'url' => ''],
    ];
        $type = 'guardian';
    return view('reports.guardian-attendance', compact('breadcrumbs', 'type'));
}
    
public function guardianAttendance(Request $request)
{
    if (!$request->expectsJson()) {
        abort(403, 'Acesso negado. Requisição deve ser AJAX.');
    }

    if (!app()->bound('school_uuid') && session('school_id')) {
        app()->instance('school_uuid', session('school_id'));
    }

    $schoolUuid = app()->bound('school_uuid') ? app('school_uuid') : null;

    if (!$schoolUuid) {
        return response()->json(['error' => 'school_uuid não definido.'], 422);
    }

    // 🔁 Corrige o bug ao duplicar data antes da validação
    if ($request->filled('start_date') && !$request->filled('end_date')) {
        $request->merge(['end_date' => $request->start_date]);
    }

    $validated = $request->validate([
        'direction' => 'required|in:Entrada,Saída',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'guardian_id' => 'nullable|uuid',
    ]);

    if ($validated['start_date'] === $validated['end_date']) {
        $validated['end_date'] = Carbon::parse($validated['end_date'])->endOfDay()->toDateTimeString();
    }

    $direction = $validated['direction'] === 'Entrada' ? 'in' : 'out';

    $query = DeviceEvent::query()
        ->where('person_type', Guardian::class)
        ->where('direction', $direction)
        ->whereHas('person', function ($q) use ($schoolUuid) {
            $q->where('school_id', $schoolUuid);
        });

    if (!empty($validated['start_date'])) {
        $query->where('date', '>=', $validated['start_date']);
    }

    if (!empty($validated['end_date'])) {
        $query->where('date', '<=', $validated['end_date']);
    }

    if (!empty($validated['guardian_id'])) {
        $query->where('person_id', $validated['guardian_id']);
    }

    $results = $query->with('person')->orderBy('date', 'desc')->get();

    return response()->json($results);
}
public function functionaryAttendanceView()
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Relatórios', 'url' => route('reports.index')],
        ['label' => 'Frequência de Funcionários', 'url' => ''],
    ];

    $type = 'functionary';

    return view('reports.functionary-attendance', compact('breadcrumbs', 'type'));
}

public function functionaryAttendance(Request $request)
{
    if (!$request->expectsJson()) {
        abort(403, 'Acesso negado. Requisição deve ser AJAX.');
    }

    if (!app()->bound('school_uuid') && session('school_id')) {
        app()->instance('school_uuid', session('school_id'));
    }

    $schoolUuid = app()->bound('school_uuid') ? app('school_uuid') : null;

    if (!$schoolUuid) {
        return response()->json(['error' => 'school_uuid não definido.'], 422);
    }

    // 🔁 Corrige o bug ao duplicar data antes da validação
    if ($request->filled('start_date') && !$request->filled('end_date')) {
        $request->merge(['end_date' => $request->start_date]);
    }

    $validated = $request->validate([
        'direction' => 'required|in:Entrada,Saída',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'functionary_id' => 'nullable|uuid',
    ]);

    if ($validated['start_date'] === $validated['end_date']) {
        $validated['end_date'] = Carbon::parse($validated['end_date'])->endOfDay()->toDateTimeString();
    }

    $direction = $validated['direction'] === 'Entrada' ? 'in' : 'out';

    $query = DeviceEvent::query()
        ->where('person_type', Functionary::class)
        ->where('direction', $direction)
        ->whereHas('person', function ($q) use ($schoolUuid) {
            $q->where('school_id', $schoolUuid);
        });

    if (!empty($validated['start_date'])) {
        $query->where('date', '>=', $validated['start_date']);
    }

    if (!empty($validated['end_date'])) {
        $query->where('date', '<=', $validated['end_date']);
    }

    if (!empty($validated['functionary_id'])) {
        $query->where('person_id', $validated['functionary_id']);
    }

    $results = $query->with('person')->orderBy('date', 'desc')->get();

    return response()->json($results);
}

    /**
     * Relatório de usuários sem foto
     */
    public function usersWithoutPhoto(Request $request)
    {
        $schoolUuid = app()->bound('school_uuid') ? app('school_uuid') : session('school_id');

        if (!$schoolUuid) {
            Log::warning('school_uuid não definido em usersWithoutPhoto', [
                'user_id' => optional(auth()->user())->id
            ]);
            return response()->json(['error' => 'school_uuid não definido.'], 422);
        }

        $request->validate([
            'user_type' => 'required|in:Aluno,Funcionário,Responsável',
        ], [
            'user_type.required' => 'O campo tipo de usuário é obrigatório.',
            'user_type.in' => 'Tipo de usuário inválido.',
        ]);

        switch ($request->user_type) {
            case 'Aluno':
                $results = Student::where('school_id', $schoolUuid)
                    ->where(fn($q) => $q->whereNull('photo_path')->orWhere('photo_path', ''))
                    ->select('uuid', 'name', 'registration_number as identificacao')
                    ->get();
                break;

            case 'Funcionário':
                $results = Functionary::where('school_id', $schoolUuid)
                    ->where(fn($q) => $q->whereNull('photo_path')->orWhere('photo_path', ''))
                    ->select('uuid', 'name', 'cpf as identificacao')
                    ->get();
                break;

            case 'Responsável':
                $results = Guardian::where('school_id', $schoolUuid)
                    ->where(fn($q) => $q->whereNull('photo_path')->orWhere('photo_path', ''))
                    ->select('uuid', 'name', 'cpf as identificacao')
                    ->get();
                break;

            default:
                $results = [];
        }

        return response()->json($results);
    }


    public function usersWithoutPhotoView()
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Relatórios', 'url' => route('reports.index')],
        ['label' => 'Usuários sem Foto', 'url' => ''],
    ];

    return view('reports.users-without-photo', compact('breadcrumbs'));
}
}
