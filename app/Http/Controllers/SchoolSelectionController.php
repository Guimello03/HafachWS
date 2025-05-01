<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\School;
use App\Models\User;
use App\Models\Client;

class SchoolSelectionController extends Controller
{
    public function index()
    {
        $schools = Auth::user()->schools; // escolas do client_admin
        return view('select-school', compact('schools'));
    }

    public function store(Request $request)
{
    $user = Auth::user();

    if ($user->hasRole('super_admin')) {
        // Super admin pode escolher qualquer escola válida
        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);
    } else {
        // Outros só podem escolher entre as escolas que têm acesso
        $request->validate([
            'school_id' => [
                'required',
                Rule::in($user->schools->pluck('id')->toArray()),
            ],
        ]);
    }

    session(['school_id' => $request->school_id]);

    $user->update(['last_school_id' => $request->school_id]);

    return response()->json(['success' => true]);
}

public function clientAdmin(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'school_id' => [
            'required',
            Rule::in($user->schools->pluck('id')->toArray()),
        ],
    ]);

    session(['school_id' => $request->school_id]);
    $user->update(['last_school_id' => $request->school_id]);

    return response()->json(['success' => true]);
}
}