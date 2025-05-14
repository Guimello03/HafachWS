<?php

namespace App\Helpers;

use App\Models\SchoolSetting;

class GetSchoolSetting
{
    /**
     * Recupera o valor de configuração por escola.
     *
     * @param string $schoolId
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($schoolId, $key, $default = null)
    {
        return optional(
            SchoolSetting::where('school_id', $schoolId)
                ->where('key', $key)
                ->first()
        )->value ?? $default;
    }
}
