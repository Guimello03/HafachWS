<?php

use App\Models\School;
use Illuminate\Support\Str;

if (!function_exists('activeSchool')) {
    function activeSchool() {
        if (!auth()->check()) {
            return null;
        }

        $uuid = session('school_id');

        // Se for super admin e não tiver escola ativa, retorna null
        if (auth()->user()->hasRole('super_admin') && !$uuid) {
            return null;
        }

        return $uuid && Str::isUuid($uuid)
            ? School::with('client')->firstWhere('uuid', $uuid)
            : null;
    }
}
