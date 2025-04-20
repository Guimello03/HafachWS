<?php

namespace App\Http\Controllers;

use App\Models\guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guardians = guardian::all();
        return view('guardians.index', compact('guardians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guardians.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:guardians',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:guardians',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        guardian::create($validated);
        return redirect()->route('guardians.index')->with('success', 'Responsável Cadastrado com Sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(guardian $guardian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(guardian $guardian)
    {
        return view('guardians.edit', compact('guardian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, guardian $guardian)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:guardians,cpf,' . $guardian->id,
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:guardians,email,' . $guardian->id,
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        $guardian->update($validated);
        return redirect()->route('guardians.index')->with('success', 'Responsável editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(guardian $guardian)
    {
        if ($guardian->photo_path && Storage::disk('public')->exists($guardian->photo_path)){
        Storage::disk('public')->delete($guardian->photo_path);}
        $guardian->delete();
        return redirect()->route('guardians.index')->with('success', 'Responsável removido com sucesso!');

    }
    public function removePhoto(guardian $guardian)
    {
        if ($guardian->photo_path && Storage::disk('public')->exists($guardian->photo_path)) {
            Storage::disk('public')->delete($guardian->photo_path);
            $guardian->update(['photo_path'=> null]);
            return redirect()->route('guardians.edit', $guardian->id)->with('success', 'Foto removida com sucesso!');
        }
    }

}


