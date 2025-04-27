<?php

namespace App\Http\Controllers;
use App\Models\functionary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class FunctionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Funcionários', 'url' => ''], // sem URL porque é a página atual
        ];
        $search = $request->input('search');
        $functionaries = functionary::query()
    ->when($search, function ($query, $search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('cpf', 'like', "%{$search}%");
    })
    ->orderBy('name')
    ->paginate(10);
        
        return view('functionaries.index', compact('functionaries', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard',
             'url' => route('dashboard'),
            ],
            ['label' => 'Funcionários', 'url' => route('functionaries.index')],
            ['label' => 'Criar Funcionário', 'url' => ''], // sem URL porque é a página atual
        ];
        return view('functionaries.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:functionaries',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:functionaries',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        functionary::create($validated);

        return redirect()->route('functionaries.index')->with('success', 'Funcionário criado com sucesso!');
    }
    /**
     * Display the specified resource.
     */
    public function show(functionary $functionary){

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(functionary $functionary)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Funcionários', 'url' => route('functionaries.index')],
            ['label' => 'Editar Funcionário', 'url' => ''], // sem URL porque é a página atual
        ];
        return view('functionaries.edit', compact('functionary', 'breadcrumbs'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, functionary $functionary)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:functionaries,cpf,' . $functionary->id,
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:functionaries,email,' . $functionary->id,
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($functionary->photo_path) {
                Storage::disk('public')->delete($functionary->photo_path);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        $functionary->update($validated);

        return redirect()->route('functionaries.index')->with('success', 'Funcionário atualizado com sucesso!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(functionary $functionary)
    {
        if ($functionary->photo_path && Storage::disk('public')->exists($functionary->photo_path)) {
            Storage::disk('public')->delete($functionary->photo_path);
        }
        $functionary->delete();
        return redirect()->route('functionaries.index')->with('success', 'Funcionário excluído com sucesso!');
    }
    public function removePhoto(functionary $functionary)
    {
        if ($functionary->photo_path && Storage::disk('public')->exists($functionary->photo_path)) {
            Storage::disk('public')->delete($functionary->photo_path);
            $functionary->update(['photo_path' => null]);
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
    public function updatePhoto(Request $request, functionary $functionary)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($functionary->photo_path) {
                Storage::disk('public')->delete($functionary->photo_path);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        $functionary->update($validated);

        return redirect()->route('functionaries.edit', $functionary->uuid)->with('success', 'Foto atualizada com sucesso!');
    }
}

