<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Guardian;




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
        $search = $request->input('search');
        $students = Student::query()
    ->when($search, function ($query, $search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('registration_number', 'like', "%{$search}%");
    })
    ->orderBy('name')
    ->paginate(10);
     
    




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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:students',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'guardian_id' => 'nullable|exists:guardians,uuid',
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }


        Student::create($validated);
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
        
        $guardians = \App\Models\Guardian::all();
        

        return view('students.edit', compact('student', 'guardians', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:students,registration_number,'. $student->id,
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'guardian_id' => 'nullable|exists:guardians,uuid',
        ]);
        


        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $path = $request->file('photo')->store('photos', 'public');

            $validated['photo_path'] = $path;
        }
        
             $student->update($validated);

             return redirect()->route('students.edit', $student->uuid);
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
    
    public function updatePhoto(Request $request, Student $student)
  {
    $request->validate([
        'photo' => 'required|image|max:2048',
    ]);

    if ($student->photo_path) {
        Storage::disk('public')->delete($student->photo_path);
    }

    $path = $request->file('photo')->store('photos', 'public');
    $student->update(['photo_path' => $path]);

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
