<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\School;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;


class EnsureSchoolSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
{
    $user = Auth::user();

    // Super admin passa direto
    if ($user->hasRole('super_admin')) {
        return $next($request);
    }

    // client_admin: se não tiver session, usa last_school_id
    if ($user->hasRole('client_admin')) {
        if (!session()->has('school_id')) {
            $lastSchoolId = $user->last_school_id;

            if ($lastSchoolId) {
                session(['school_id' => $lastSchoolId]);
            } else {
                // fallback: tenta pegar a primeira escola vinculada
                $firstSchool = $user->schools()->first();

                if ($firstSchool) {
                    session(['school_id' => $firstSchool->id]);
                    $user->update(['last_school_id' => $firstSchool->id]);
                } else {
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'email' => 'Nenhuma escola vinculada ao seu usuário.',
                    ]);
                }
            }
        }

        return $next($request);
    }

    // Outros: exige school_id na sessão
    if (!session()->has('school_id')) {
        return redirect()->route('select.school');
    }

    return $next($request);
}
}