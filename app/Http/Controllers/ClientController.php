<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\CollectionorderBy;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Clientes', 'url' => ''], // sem URL porque é a página atual
        ];
        $search = $request->input('search');
        $clients = Client::query()

    ->where(function ($query) use ($search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('cnpj', 'like', "%{$search}%");
    })
    ->orderBy('name')
    ->paginate(10);


        return view('admin.index', compact('clients', 'breadcrumbs'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Clientes', 'url' => route('admin.dashboard')],
            ['label' => 'Criar Cliente', 'url' => ''], // sem URL porque é a página atual
        ];
        return view('admin.clients.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:client',
            'cnpj' => 'required|string|max:14|unique:client',
        ]);

        $client = Client::create($request->all());

        return response()->json($client, 201);
    }
   

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    
    {
        $clientAdmin = $client->users()->role('client_admin')->first();

    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Clientes', 'url' => route('admin.dashboard')],
        ['label' => 'Editar Cliente', 'url' => ''], // sem URL porque é a página atual
    ];
        $client->load('schools'); // Eager loading
    return view('admin.clients.edit', compact('client', 'breadcrumbs', 'clientAdmin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
{
    // Validação dos dados
    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|max:255|unique:clients,email,' . $client->id,
        'cnpj'  => 'sometimes|required|string|max:18|unique:clients,cnpj,' . $client->id,
        'admin_password' => 'nullable|string|min:6',
    ]);

    // Atualiza os campos do cliente
    $client->update([
        'name' => $validated['name'] ?? $client->name,
        'email' => $validated['email'] ?? $client->email,
        'cnpj' => $validated['cnpj'] ?? $client->cnpj,
    ]);

    // Atualiza a senha do client_admin, se informada
    if ($request->filled('admin_password')) {
        $clientAdmin = $client->users()->role('client_admin')->first();
        if ($clientAdmin) {
            $clientAdmin->update([
                'password' => bcrypt($request->admin_password),
            ]);
        }
    }

    return redirect()->route('admin.dashboard', $client->id)
        ->with('success', 'Cliente atualizado com sucesso!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    
    {
        $client->delete();

        return response()->json(['message' => 'Client deleted successfully']);    
    }

    public function adminDashboard()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>route('dashboard')],
            ['label' => 'Admin', 'url' => ''], // sem URL porque é a página atual
        ];
        $clients = Client::all();
        $schools = School::all();

        return view('admin.index', compact('clients', 'schools', 'breadcrumbs'));
    }
    public function schools(Client $client)
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Clientes', 'url' => route('admin.dashboard')],
        ['label' => 'Escolas do Cliente', 'url' => ''], // sem URL porque é a página atual
    ];
    $schools = $client->schools; // Puxa as escolas desse client
    return view('admin.clients.schools', compact('client', 'schools', 'breadcrumbs'));
}
}
