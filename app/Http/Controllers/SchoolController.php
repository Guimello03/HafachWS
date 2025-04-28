<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        return School::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'client_id' => 'required|exists:clients,id',
        ]);

        $school = School::create($request->all());

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

        return response()->json(['message' => 'School deleted successfully']);
    }
}
