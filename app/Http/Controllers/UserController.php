<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $authUser = Auth::user(); // Quem está criando o novo usuário

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,client_admin,school_director',
            'schools' => 'required|array',
            'schools.*' => 'exists:schools,id',
        ]);

        // Protege: Client Admin só pode criar School Directors
        if ($authUser->hasRole('client_admin') && $request->role !== 'school_director') {
            return response()->json([
                'message' => 'Você não tem permissão para criar este tipo de usuário.'
            ], 403);
        }

        // Super Admin pode definir client_id; Client Admin herda o próprio client_id
        $clientId = $authUser->hasRole('super_admin')
            ? ($request->client_id ?? null)
            : $authUser->client_id;

        // Criar o novo usuário
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'client_id' => $clientId,
        ]);

        // Definir Role via Spatie
        $newUser->assignRole($request->role);

        // Vincular o usuário às escolas
        $newUser->schools()->attach($request->schools);

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $newUser->only(['id', 'name', 'email']),
        ], 201);
    }
    public function storeDirector(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'school_id' => 'required|exists:schools,uuid',
    ]);

    $school = School::where('uuid', $request->school_id)->firstOrFail();

    // Garante que a escola ainda não tem diretor
    if ($school->users()->role('school_director')->exists()) {
        return back()->withErrors(['Esta escola já possui um diretor associado.']);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'client_id' => $school->client_id,
    ]);

    $user->assignRole('school_director');
    $school->users()->attach($user->id);

    return back()->with('success', 'Diretor criado com sucesso!');
}
public function updatePassword(Request $request, User $user)
{

    Log::info('Diretor sendo atualizado', ['user_id' => $user->id]);
    $request->validate([
        'password' => 'required|string|min:8',
    ]);

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('school.dashboard')->with('success', 'Senha atualizada com sucesso!');
}
}