<?php

use App\Models\School;

if (!function_exists('activeSchool')) {
    function activeSchool() {
        $schoolUuid = session('school_id');

        return $schoolUuid
            ? School::with('client')->where('uuid', $schoolUuid)->first()
            : null;
    }
}
