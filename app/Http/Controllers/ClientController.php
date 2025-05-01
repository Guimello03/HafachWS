<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients =  Client::all();
        return view('admin.index', compact('clients'));

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:client,email,' . $client,
            'cnpj' => 'sometimes|required|string|max:14|unique:client,cnpj,' . $client,
        ]);

        $client->update($request->all());

        return response()->json($client);
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
}
