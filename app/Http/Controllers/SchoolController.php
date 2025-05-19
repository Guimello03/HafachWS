<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;
use App\Models\Guardian;
use App\Models\DeviceGroup;
use App\Models\Student;
use App\Models\Functionary;


class SchoolController extends Controller
{
   
public function dashboard(Request $request)
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Escola', 'url' => ''],
    ];

    $school = activeSchool();
    if (!$school) {
        return redirect()->route('dashboard')->with('error', 'Escola ativa não definida.');
    }

    $groups = DeviceGroup::where('school_id', $school->uuid)->get();
    $director = $school->users()->role('school_director')->first();

    $personType = $request->get('type'); // 'students', 'guardians', 'functionaries'
    $personId = $request->get('person_uuid');

    $people = collect(); // ← fallback seguro
    $types = [
    'student' => ['label' => 'Aluno', 'model' => Student::class],
    'guardian' => ['label' => 'Responsável', 'model' => Guardian::class],
    'functionary' => ['label' => 'Funcionário', 'model' => Functionary::class],
];


   if ($personType && isset($types[$personType])) {
    $model = $types[$personType]['model'];

        $query = $model::where('school_id', $school->uuid);

        if ($personId) {
            $query->where('uuid', $personId);
        }

        $people = $query->get();
    }

    return view('school.index', compact(
        'breadcrumbs', 'director', 'school', 'groups', 'types', 'personType', 'personId', 'people'
    ));
}
    



    public function create(Client $client)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Clientes', 'url' => route('admin.dashboard')],
            ['label' => 'Criar Escola', 'url' => ''], // sem URL porque é a página atual
        ];
        return view('admin.schools.create', compact('client', 'breadcrumbs', ));
    }


public function index(Client $client, Request $request)
{
    $search = $request->input('search');

    $schools = $client->schools()
        ->when($search, fn($query) => $query->where('name', 'like', "%$search%"))
        ->get();

    return view('clients.schools', compact('client', 'schools'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'cnpj' => 'nullable|string|max:18',
        'client_id' => 'required|exists:clients,id',
    ]);

    $validated['uuid'] = (string) \Illuminate\Support\Str::uuid();

    $school = School::create($validated);

    // 🔗 Vincula automaticamente o client_admin desse client à escola
    $admin = \App\Models\User::where('client_id', $school->client_id)
                ->role('client_admin')
                ->first();
                

                if ($admin && !$school->users()->where('user_id', $admin->id)->exists()) {
                    
        $school->users()->attach($admin->id);
    }

    return redirect()
        ->route('clients.schools', $request->client_id)
        ->with('success', 'Escola criada com sucesso!');
}


    public function show(School $school)
    {
        return response()->json($school);
    }
    public function edit(School $school)
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Clientes', 'url' => route('admin.dashboard')],
        ['label' => 'Editar Escola', 'url' => ''],
    ];

    $client = $school->client;

    // 💡 Buscar SEMPRE o valor atualizado
    $tolerance = \App\Models\SchoolSetting::where('school_id', $school->uuid)
        ->where('key', 'event_tolerance_minutes')
        ->first();

    return view('admin.schools.edit', compact('school', 'breadcrumbs', 'client', 'tolerance'));
}
public function update(Request $request, School $school)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'corporate_name' => 'nullable|string|max:255',
        'cnpj' => 'nullable|string|max:18',
    ]);

    $school->update($validated);

    return redirect()
        ->route('clients.schools', $school->client_id)
        ->with('success', 'Escola atualizada com sucesso!');
}

    public function destroy(School $school)
    {
        $school->delete();

        return response()->json(['message' => 'Escola excluída com sucesso!']);
    }

    public function getByClient(Request $request)
    {
        // Se for super_admin, pegar o client_id vindo da requisição
        if (Auth::user()->hasRole('super_admin')) {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
            ]);

            $clientId = $request->query('client_id');
        } else {
            // Se for client.admin ou outro, pegar o client_id do próprio usuário
            $clientId = Auth::user()->client_id;
        }

        $schools = School::where('client_id', $clientId)
            ->select('uuid', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($schools);
    }
}
