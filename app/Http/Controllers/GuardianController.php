<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageProcessingService;
use App\Jobs\SendPhotoToDevicesJob;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Responsáveis', 'url' => ''], // Página atual
    ];

    $school = activeSchool();

    if (!$school) {
        return redirect()->route('schools.select');
    }

    $query = Guardian::where('school_id', $school->uuid);

    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('cpf', 'like', "%{$search}%");
        });
    }

    $guardians = $query->orderBy('name')->paginate(10);

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
    public function store(Request $request,  ImageProcessingService $imageService)
    {

        $school = activeSchool();
        if (!$school) {
            return redirect()->route('/');
        }
        $request->merge(['school_id' => $school->uuid]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:guardians',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:guardians',
            'birth_date' => 'required|date',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'school_id' => 'required|exists:schools,uuid',
        ]);
       $guardian = Guardian::create($validated);
       if ($request->hasFile('photo')) {
        $image = $imageService->processUploadedImage($request->file('photo'), $guardian->uuid);
        $guardian->photo_path = $image['path'];
        $guardian->saveQuietly(); // evita disparar updated()
    }


       
        return redirect()->route('guardians.index')->with('success', 'Responsável Cadastrado com Sucesso!');
    

}

    /**s
     * Display the specified resource.
     */
    public function show(Guardian $guardian)
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
    public function update(Request $request, Guardian $guardian, ImageProcessingService $imageService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:255|unique:guardians,cpf,' . $guardian->uuid . ',uuid',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:guardians,email,' . $guardian->uuid . ',uuid',
            'birth_date' => 'required|date',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            
        ]);
        $guardian->update($validated);
        if ($request->hasFile('photo_path') && $request->file('photo_path')->isValid()) {
            // Deletar foto anterior, se houver
            if ($guardian->photo_path) {
                Storage::disk('public')->delete($guardian->photo_path);
            }
    
            // Processar imagem com redimensionamento
            $image = $imageService->processUploadedImage($request->file('photo_path'), $guardian->uuid);
    
            // Atualiza caminho no banco
            $guardian->photo_path = $image['path'];
            $guardian->save();
        
        
    
            // ✅ Agora sim: a imagem está salva, e o job pode ser disparado com segurança
            foreach ($guardian->deviceGroups as $group) {
                SendPhotoToDevicesJob::dispatch($group, $guardian);
            }
            }
        

       
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
    public function updatePhoto(Request $request, Guardian $guardian, ImageProcessingService $imageService)
{
    $validated = $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        // Deletar foto anterior, se houver
        if ($guardian->photo_path) {
            Storage::disk('public')->delete($guardian->photo_path);
        }

        // Processar imagem com redimensionamento
        $image = $imageService->processUploadedImage($request->file('photo'), $guardian->uuid);

        // guardian caminho no banco
        $guardian->photo_path = $image['path'];
        $guardian->save();
    
    

        // ✅ Agora sim: a imagem está salva, e o job pode ser disparado com segurança
        foreach ($guardian->deviceGroups as $group) {
            SendPhotoToDevicesJob::dispatch($group, $guardian);
        }
    }



        return redirect()->route('guardians.index')->with('success', 'Foto atualizada com sucesso!');
    }
}

