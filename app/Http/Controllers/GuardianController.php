<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Responsáveis', 'url' => ''], // sem URL porque é a página atual
        ];
        $search = request('search');
        $guardians = Guardian::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);
             
            

        return view('guardians.index', compact('guardians', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Responsáveis', 'url' => route('guardians.index')],
            ['label' => 'Criar Responsável', 'url' => ''], // sem URL porque é a página atual
        ];
        return view('guardians.create', compact('breadcrumbs'));
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

        Guardian::create($validated);
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
    public function edit(Guardian $guardian)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Responsáveis', 'url' => route('guardians.index')],
            ['label' => 'Editar Responsável', 'url' => ''], // sem URL porque é a página atual
        ];
        return view('guardians.edit', compact('guardian', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guardian $guardian)
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
    public function destroy(Guardian $guardian)
    {
        if ($guardian->photo_path && Storage::disk('public')->exists($guardian->photo_path)){
        Storage::disk('public')->delete($guardian->photo_path);}
        $guardian->delete();
        return redirect()->route('guardians.index')->with('success', 'Responsável removido com sucesso!');

    }
    public function removePhoto(Guardian $guardian)
    {
        if ($guardian->photo_path && Storage::disk('public')->exists($guardian->photo_path)) {
            Storage::disk('public')->delete($guardian->photo_path);
            $guardian->update(['photo_path' => null]);
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Foto removida com sucesso!']);
            }
    
            return redirect()->back()->with('success', 'Foto removida com sucesso!');
        } else {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Nenhuma foto encontrada para remover.'], 404);
            }
    
            return redirect()->back()->with('error', 'Nenhuma foto encontrada para remover.');
        }
        
    }
    public function updatePhoto(Request $request, Guardian $guardian)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($guardian->photo_path) {
                Storage::disk('public')->delete($guardian->photo_path);
            }
            $path = $request->file('photo')->store('photos', 'public');

            $validated['photo_path'] = $path;
        }

        $guardian->update($validated);

        return redirect()->route('guardians.index')->with('success', 'Foto atualizada com sucesso!');
    }
}


