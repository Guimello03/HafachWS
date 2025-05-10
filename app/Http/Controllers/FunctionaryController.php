<?php

namespace App\Http\Controllers;
use App\Models\Functionary;
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
        $school = activeSchool();
        if (!$school) {
            return redirect()->route('dashboard');
        }
        $query = Functionary::where('school_id', $school->uuid);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
            });
        }
        $functionaries = $query->orderBy('name')->paginate(10);
        
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
        $school = activeSchool();
        if (!$school) {
            return redirect()->route('dashboard');
        }
        $request->merge(['school_id' => $school->uuid]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:functionaries',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:functionaries',
            'birth_date' => 'required|date',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'school_id' => 'required|uuid|exists:schools,uuid',
        ]);
       $functionary =  Functionary::create($validated);
        if ($request->hasFile('photo_path')) {
            $filename = $functionary->uuid . '.jpg';
            $path = $request->file('photo_path')->storeAs('users/photos', $filename, 'public');
            $functionary->update(['photo_path' => $path]);
        }

        

        return redirect()->route('functionaries.index')->with('success', 'Funcionário criado com sucesso!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Functionary $Functionary){

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Functionary $functionary)
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
    public function update(Request $request, Functionary $functionary)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:functionaries,cpf,' . $functionary->uuid . ',uuid',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:functionaries,email,' . $functionary->uuid . ',uuid',
            'birth_date' => 'required|date',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $functionary->update($validated);


        if ($request->hasFile('photo_path')) {
            // Delete the old photo if it exists
            if ($functionary->photo_path) {
                Storage::disk('public')->delete($functionary->photo_path);
            }
            $filename = $functionary->uuid . '.jpg';
            $path = $request->file('photo_path')->storeAs('users/photos', $filename, 'public');
            $functionary->update([
                'photo_path' => $path
            ]);
        }


        return redirect()->route('functionaries.index')->with('success', 'Funcionário atualizado com sucesso!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Functionary $Functionary)
    {
        if ($Functionary->photo_path && Storage::disk('public')->exists($Functionary->photo_path)) {
            Storage::disk('public')->delete($Functionary->photo_path);
        }
        $Functionary->delete();
        return redirect()->route('functionaries.index')->with('success', 'Funcionário excluído com sucesso!');
    }
    public function removePhoto(Functionary $Functionary)
    {
        if ($Functionary->photo_path && Storage::disk('public')->exists($Functionary->photo_path)) {
            Storage::disk('public')->delete($Functionary->photo_path);
            $Functionary->update(['photo_path' => null]);
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
    public function updatePhoto(Request $request, Functionary $functionary)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo_path')) {
            // Delete the old photo if it exists
            if ($functionary->photo_path) {
                Storage::disk('public')->delete($functionary->photo_path);
            }
            $filename = $functionary->uuid . '.jpg';
            $path = $request->file('photo_path')->storeAs('users/photos', $filename, 'public');
            $functionary->update([
                'photo_path' => $path
            ]);
        }

        return redirect()->route('functionaries.index')->with('success', 'Foto atualizada com sucesso!');
    }
}

