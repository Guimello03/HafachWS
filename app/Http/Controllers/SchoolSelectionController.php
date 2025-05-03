<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SchoolSelectionController extends Controller
{
    public function index()
    {
        $schools = Auth::user()->schools; // escolas visíveis pelo usuário logado
        return view('select-school', compact('schools'));
    }

    public function store(Request $request)
    {
        $user = Auth::user()->load('schools'); // carrega as escolas do usuário logado
        
        
        if ($user->hasRole('super_admin')) {
            $request->validate([
                'school_id' => 'required|exists:schools,uuid',
            ]);
        } else {
            $request->validate([
                'school_id' => [
                    'required',
                    Rule::in($user->schools->pluck('uuid')->toArray()),
                ],
            ]);
        }

        
        $school = School::where('uuid', $request->school_id)->firstOrFail();
        

        // Salva na sessão e persiste no usuário
        session(['school_id' => (string) $school->uuid]);

        $user->update([
            'last_school_uuid' => (string) $school->uuid,
        ]);

        return response()->json(['success' => true]);
    }
}
