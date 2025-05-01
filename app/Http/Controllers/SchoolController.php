<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function index()
    {
        return School::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'client_id' => 'required|exists:clients,id',
        ]);

        $school = School::create($request->validate());

        return response()->json($school, 201);
    }

    public function show(School $school)
    {
        return response()->json($school);
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'client_id' => 'required|exists:clients,id',
        ]);

        $school->update($request->all());

        return response()->json($school);
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
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($schools);
    }
}

