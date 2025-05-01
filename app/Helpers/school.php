<?php

use App\Models\School;

if (!function_exists('activeSchool')) {
    function activeSchool() {
        $schoolId = session('school_id');

        return $schoolId
            ? School::with('client')->find($schoolId)
            : null;
    }
}
