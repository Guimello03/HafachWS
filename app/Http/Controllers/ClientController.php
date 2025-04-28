<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Client::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients',
            'cnpj' => 'required|string|max:14|unique:clients',
        ]);

        $client = Client::create($request->all());

        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $clients)
    {
        return response()->json($clients);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $clients)
    
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $clients)
    
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:clients,email,' . $clients->id,
            'cnpj' => 'sometimes|required|string|max:14|unique:clients,cnpj,' . $clients->id,
        ]);

        $clients->update($request->all());

        return response()->json($clients);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $clients)
    
    {
        $clients->delete();

        return response()->json(['message' => 'Client deleted successfully']);    
    }
}
