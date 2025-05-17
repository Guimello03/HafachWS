<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\School;

class EnsureReportSchoolScope
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $this->deny($request, 'Usuário não autenticado.', 401);
        }

        $schoolUuid = $request->get('school_id') ?? session('school_id');

        if (!$schoolUuid) {
            return $this->deny($request, 'Escola obrigatória nos relatórios.', 422);
        }

        if ($user->hasRole('super_admin')) {
            $school = School::where('uuid', $schoolUuid)->first();
            if (!$school) {
                return $this->deny($request, 'Escola não encontrada.', 404);
            }

        } elseif ($user->hasRole('client_admin')) {
            $school = School::where('uuid', $schoolUuid)
                ->where('client_id', $user->client_id)
                ->first();

            if (!$school) {
                return $this->deny($request, 'Escola não pertence ao seu cliente.', 403);
            }

        } elseif ($user->hasRole('school_director')) {
            // Se não tiver last_school_uuid mas tiver uma escola vinculada, seta
            if (!$user->last_school_uuid && $user->schools()->count() === 1) {
                $user->last_school_uuid = $user->schools()->first()->uuid;
                $user->save();
            }

            if ($user->last_school_uuid !== $schoolUuid) {
                return $this->deny($request, 'Você não pode acessar esta escola.', 403);
            }

        } else {
            return $this->deny($request, 'Perfil sem permissão para relatórios.', 403);
        }

        session(['school_id' => $schoolUuid]);
        app()->instance('school_uuid', $schoolUuid);

        return $next($request);
    }

    private function deny(Request $request, string $message, int $code)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $message], $code);
        }

        return redirect('/reports')->with('error', $message);
    }
}
