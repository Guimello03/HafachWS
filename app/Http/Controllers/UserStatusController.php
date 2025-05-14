<?php

namespace App\Http\Controllers;

use App\Services\UserStatusService;

class UserStatusController extends Controller
{
    /**
     * Retorna o último status de todos os usuários no dia atual.
     */
    public function index()
    {
        $latestEvents = UserStatusService::getAllLastStatusToday();
        return response()->json($latestEvents);
    }
}
