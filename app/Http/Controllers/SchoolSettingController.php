<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolSetting;

class SchoolSettingController extends Controller
{
    public function edit()
    {
        $school = activeSchool();
        if (!$school) {
            return redirect()->route('dashboard')->with('error', 'Escola ativa não definida.');
        }

        $tolerance = SchoolSetting::where('school_id', $school->uuid)
            ->where('key', 'event_tolerance_minutes')
            ->first();

        return view('school.settings.tolerance', [
            'tolerance' => $tolerance?->value ?? 0
        ]);
    }

    public function update(Request $request)
{
    $request->validate([
        'tolerance_minutes' => 'required|integer|min:0|max:1440',
    ]);

    $school = activeSchool();

    $setting = \App\Models\SchoolSetting::updateOrCreate([
        'school_id' => $school->uuid,
        'key' => 'event_tolerance_minutes'
    ], [
        'value' => $request->tolerance_minutes
    ]);

    \Log::info('Tolerância configurada', [
        'school_id' => $school->uuid,
        'tolerance_minutes' => $setting->value
    ]);

    return redirect()->route('schools.edit', $school->uuid)
        ->with('tab', 'config')
        ->with('success', 'Configuração atualizada com sucesso.');
}

}
