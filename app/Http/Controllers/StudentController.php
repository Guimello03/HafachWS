<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Guardian;
use App\Jobs\SendPhotoToDevicesJob;
use App\Services\ImageProcessingService;





class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Alunos', 'url' => ''], // sem URL porque é a página atual
    ];
    $school = activeSchool();

    if (!$school) {
        return redirect()->route('dashboard')->with('error', 'Escola ativa não definida.');
    }

    $query = Student::where('school_id', $school->uuid);

    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('registration_number', 'like', "%{$search}%");
        });
    }

    $students = $query->paginate(10);

    return view('students.index', compact('students', 'breadcrumbs'));
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
            ['label' => 'Alunos', 'url' => route('students.index')],
            ['label' => 'Criar Aluno', 'url' => ''], // sem URL porque é a página atual
        ];
        return view('students.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageProcessingService $imageService)
    {
        $school = activeSchool();
        if (!$school) {
            return redirect()->route('dashboard')->with('error', 'Escola não encontrada.');
        }
        $request->merge(['school_id' => $school->uuid]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:students',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'guardian_id' => 'nullable|exists:guardians,uuid',
            'school_id' => 'required|exists:schools,uuid',
        ]);
        


        $student = Student::create($validated);
        if ($request->hasFile('photo')) {
            $image = $imageService->processUploadedImage($request->file('photo'), $student->uuid);
            $student->photo_path = $image['path'];
            $student->saveQuietly(); // evita disparar updated()
        }
        
        return redirect()->route('students.index')->with('success', 'Aluno criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $student->load('guardian');
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Alunos', 'url' => route('students.index')],
            ['label' => 'Editar Aluno', 'url' => ''], // sem URL porque é a página atual
        ];
        
        $guardians = Guardian::where('school_id', $student->school_id)->get();
        if ($student->guardian_id) {
            $guardians = $guardians->where('uuid', '!=', $student->guardian_id);
        }
        

        return view('students.edit', compact('student', 'guardians', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student,ImageProcessingService $imageService)
    {
        logger()->info('📷 Recebendo imagem:', [
            'tem_arquivo' => $request->hasFile('photo_path'),
            'é_válido' => $request->file('photo_path')?->isValid(),
            'tamanho' => $request->file('photo_path')?->getSize(),
            
        ]);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:students,registration_number,' . $student->uuid . ',uuid',
            'birth_date' => 'required|date',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'guardian_id' => 'nullable|exists:guardians,uuid',
        ]);
       
        $student->update($validated);

    // Processar nova foto se enviada
    if ($request->hasFile('photo_path') && $request->file('photo_path')->isValid()) {
        // Deletar foto anterior, se houver
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }

        // Processar imagem com redimensionamento
        $image = $imageService->processUploadedImage($request->file('photo_path'), $student->uuid);

        // Atualiza caminho no banco
        $student->photo_path = $image['path'];
        $student->save();
    
    

        // ✅ Agora sim: a imagem está salva, e o job pode ser disparado com segurança
        foreach ($student->deviceGroups as $group) {
            SendPhotoToDevicesJob::dispatch($group, $student);
        }
    }

    return redirect()->route('students.edit', $student->uuid)->with('success', 'Aluno atualizado com sucesso!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)){
         Storage::disk('public')->delete($student->photo_path);}

        $student->delete();
        return redirect()->route('students.index')->with('success', 'Aluno removido com sucesso!');
    }


    public function removePhoto(Student $student)
    {
    if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
        Storage::disk('public')->delete($student->photo_path);
        $student->update(['photo_path' => null]);

        // 🚨 ESSENCIAL para fetch()
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
    
    public function updatePhoto(Request $request, Student $student,ImageProcessingService $imageService)
  {
    $request->validate([
        'photo' =>  'nullable|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        // Deletar foto anterior, se houver
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }

        // Processar imagem com redimensionamento
        $image = $imageService->processUploadedImage($request->file('photo'), $student->uuid);

        // Atualiza caminho no banco
        $student->photo_path = $image['path'];
        $student->save();
    
    

        // ✅ Agora sim: a imagem está salva, e o job pode ser disparado com segurança
        foreach ($student->deviceGroups as $group) {
            SendPhotoToDevicesJob::dispatch($group, $student);
        }
    }
    return redirect()->route('students.index')->with('success', 'Foto atualizada com sucesso!');
   }

   public function photoModal($student)
{
    $student = Student::findOrFail($student);

    return response()->json([
        'photo_url' => $student->photo_path ? asset('storage/' . $student->photo_path) : null,
        'student_id' => $student->uuid,
    ]);
}
public function removeGuardian(Student $student)
{
    
    $student->update([
        'guardian_id' => null, // remove o vínculo
    ]);

    return redirect()->back()->with('success', 'Responsável removido com sucesso!');


}



}
