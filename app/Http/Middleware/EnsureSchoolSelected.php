<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;
use Illuminate\Support\Facades\Log;

class EnsureSchoolSelected
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            Log::warning('Acesso negado: usuário não autenticado.');
            return redirect()->route('login')->withErrors(['Sessão expirada. Faça login novamente.']);
        }

        // 🟢 Super Admin: ignora tudo
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // 🟡 Client Admin: define school_id via last_school_uuid ou primeira escola
        if ($user->hasRole('client_admin')) {
            if (!session()->has('school_id')) {
                $schoolUuid = $user->last_school_uuid;

                if (!$schoolUuid) {
                    $firstSchool = $user->schools()->first();

                    if ($firstSchool) {
                        $schoolUuid = $firstSchool->uuid;
                        $user->update(['last_school_uuid' => $schoolUuid]);
                    }
                }

                if ($schoolUuid) {
                    session(['school_id' => $schoolUuid]);
                }
            }

            return $next($request);
        }

        // 🔵 School Director: pega last_school_uuid
        if ($user->hasRole('school_director')) {
            if (!session()->has('school_id') && $user->last_school_uuid) {
                session(['school_id' => $user->last_school_uuid]);
            }

            // Ainda sem school_id? Força seleção
            if (!session()->has('school_id')) {
                Log::warning('Diretor sem escola vinculada ou sessão perdida.', [
                    'user_id' => $user->id
                ]);
                return redirect()->route('select.school')->withErrors(['Nenhuma escola vinculada ao seu perfil.']);
            }

            return $next($request);
        }

        // 🚫 Outros papéis não autorizados
        Log::warning('Usuário com papel não autorizado tentou acessar uma rota escolar.', [
            'user_id' => $user->id,
            'roles' => $user->roles->pluck('name')
        ]);

        return redirect()->route('login')->withErrors(['Você não tem permissão para acessar essa área.']);
    }
}
