<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceGroup;
use App\Models\Device;
use App\Models\DeviceGroupAutoTarget;
use App\Models\Functionary;
use App\Models\Student;
use App\Models\Guardian;


use Illuminate\Support\Facades\DB;
use App\Models\School;

class DeviceGroupController extends Controller
{
    public function index(Request $request)
{
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Escola', 'url' => route('school.dashboard')],
        ['label' => 'Grupos de Equipamentos', 'url' => ''],
    ];

    $school = activeSchool();
    if (!$school) {
        return redirect()->route('dashboard')->with('error', 'Escola ativa não definida.');
    }

    // Pega todos os grupos da escola
    $groups = DeviceGroup::with('devices')
    ->where('school_id', $school->uuid)
    ->get();

    // Pega todos os equipamentos já cadastrados da escola
    $schoolDevices = Device::where('school_id', $school->uuid)
        ->get()
        ->map(fn($device) => [
            'uuid' => $device->uuid,
            'label' => "{$device->model} - {$device->serial_number}"
        ])
        ->values();
        $autoTargetsByGroup = DeviceGroupAutoTarget::whereIn('device_group_id', $groups->pluck('uuid'))
    ->get()
    ->groupBy('device_group_id')
    ->map(function ($grouped) {
        return $grouped->pluck('target_type')->map(function ($type) {
            if ($type === Student::class) return 'students';
            if ($type === Guardian::class) return 'guardians';
            if ($type === Functionary::class) return 'functionaries';
            return null;
        })->filter()->values();
    });


    return view('groups.index', compact('groups', 'breadcrumbs', 'schoolDevices', 'autoTargetsByGroup'));

}
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Grupos de Dispositivos', 'url' => route('device_groups.index')],
            ['label' => 'Criar Grupo de Dispositivos', 'url' => ''], // sem URL porque é a página atual
        ];

        return view('groups.create', compact('breadcrumbs'));
    }
    public function edit($id)
    {
        $school = activeSchool();
        if (!$school) {
            return redirect()->route('dashboard')->with('error', 'Escola ativa não definida.');
        }
        $query = DeviceGroup::where('school_id', $school->uuid);
        $group = $query->findOrFail($id);
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Grupos de Dispositivos', 'url' => route('device_groups.index')],
            ['label' => 'Editar Grupo de Dispositivos', 'url' => ''], // sem URL porque é a página atual
        ];

        return view('device_groups.edit', compact('breadcrumbs', 'group', ''));
    }
    public function show($id)
    {
       //
    }
    

    
    public function store(Request $request)
{
    $school = activeSchool();

    if (!$school) {
        return redirect()->route('dashboard')->with('error', 'Escola ativa não definida.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'devices' => 'nullable|string', // ← JSON string, não array direta
    ]);

    try {
        DB::beginTransaction();

        // 1. Criar o grupo
        $group = DeviceGroup::create([
            'name' => $validated['name'],
            'school_id' => $school->uuid,
        ]);

        $deviceIds = [];

        // 2. Tratar os dispositivos recebidos (esperando string JSON do front)
        $devices = json_decode($request->devices, true);

        foreach ($devices ?? [] as $deviceData) {
            if (!isset($deviceData['type'])) {
                continue;
            }

            if ($deviceData['type'] === 'new') {
                if (empty($deviceData['device_id']) || empty($deviceData['model'])) {
                    continue;
                }

                $existing = Device::where('serial_number', $deviceData['device_id'])->first();

                if ($existing) {
                    $deviceIds[] = $existing->uuid;
                    continue;
                }

                $newDevice = Device::create([
                    'serial_number' => $deviceData['device_id'],
                    'model' => $deviceData['model'],
                    'school_id' => $school->uuid,
                ]);

                $deviceIds[] = $newDevice->uuid;
            }

            if ($deviceData['type'] === 'existing' && isset($deviceData['uuid'])) {
                $deviceIds[] = $deviceData['uuid'];
            }
        }

        // 3. Vincular ao grupo
        if (!empty($deviceIds)) {
            $group->devices()->attach($deviceIds);
            $this->sendMonitorMode($deviceIds, $group->uuid, $school->uuid);
        }

        DB::commit();

        return redirect()->route('device_groups.index')
            ->with('success', 'Grupo criado com sucesso.');
    } catch (\Throwable $e) {
        DB::rollBack();
        report($e);
        return redirect()->back()->with('error', 'Erro ao criar grupo de equipamentos.')->withInput();
    }
}

    
public function update(Request $request, DeviceGroup $deviceGroup)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'devices' => ['array'],
    ]);

    $deviceGroup->update([
        'name' => $validated['name'],
    ]);

    $deviceIds = [];

    foreach ($validated['devices'] ?? [] as $deviceJson) {
        $device = is_string($deviceJson)
            ? json_decode($deviceJson, true)
            : $deviceJson;

        if (!isset($device['type'])) {
            continue;
        }

        if ($device['type'] === 'existing') {
            if (!empty($device['uuid'])) {
                $deviceIds[] = $device['uuid'];
            }
            continue;
        }

        if ($device['type'] === 'new') {
            if (empty($device['device_id']) || empty($device['model'])) {
                continue;
            }

            $existing = Device::where('serial_number', $device['device_id'])->first();

            if ($existing) {
                $deviceIds[] = $existing->uuid;
                continue;
            }

            $new = Device::create([
                'serial_number' => $device['device_id'],
                'model' => $device['model'],
                'school_id' => $deviceGroup->school_id,
            ]);

            $deviceIds[] = $new->uuid;
        }
    }

    $deviceGroup->devices()->sync($deviceIds);
    $this->sendMonitorMode($deviceIds, $deviceGroup->uuid, $deviceGroup->school_id);

    return redirect()
        ->route('groups.index')
        ->with('success', 'Grupo atualizado com sucesso!');
}

public function destroy($id)
{
    $group = DeviceGroup::with('devices')->findOrFail($id);

    // Lista de IDs dos dispositivos vinculados a este grupo
    $deviceIds = $group->devices->pluck('uuid')->toArray();

    // Desvincula todos os dispositivos primeiro
    $group->devices()->detach();

    // Verifica quais dispositivos estavam **somente nesse grupo**
    foreach ($deviceIds as $deviceId) {
        $groupCount = DB::table('device_device_group')
            ->where('device_id', $deviceId)
            ->count();

        // Se não houver mais vínculos com outros grupos, deletamos o device
        if ($groupCount === 0) {
            Device::where('uuid', $deviceId)->delete();
        }
    }

    // Exclui o grupo em si
    $group->delete();

    return redirect()->route('groups.index')
        ->with('success', 'Grupo excluído com sucesso. Dispositivos órfãos foram removidos automaticamente.');
}
public function setAutoTargets(Request $request)
{
    $request->validate([
        'device_group_id' => 'required|exists:device_groups,uuid',
        'target_types' => 'nullable|array',
        'target_types.*' => 'in:students,guardians,functionaries',
    ]);

    $groupId = $request->device_group_id;

    // Apagar targets anteriores
    DeviceGroupAutoTarget::where('device_group_id', $groupId)->delete();

    // Criar novos (se houver)
    if ($request->has('target_types')) {
        $types = [
            'students' => \App\Models\Student::class,
            'guardians' => \App\Models\Guardian::class,
            'functionaries' => \App\Models\Functionary::class,
        ];

        $insertData = collect($request->target_types)
            ->filter(fn($type) => isset($types[$type])) // 💡 Evita chaves inválidas
            ->map(fn($type) => [
                'uuid' => (string) \Str::uuid(),
                'device_group_id' => $groupId,
                'target_type' => $types[$type],
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();
            \Log::debug('Insertando auto targets:', $insertData);

        DeviceGroupAutoTarget::insert($insertData);
    
    }

    return redirect()->back()->with('success', 'Configuração de envio automático salva com sucesso.');
}
protected function sendMonitorMode(array $deviceIds, string $groupId, string $schoolId)
{
    $payload = [
        'verb' => 'POST',
        'endpoint' => 'set_configuration',
        'body' => [
            'monitor' => [
                'request_timeout' => '5000',
                'hostname' => '192.168.1.14',
                'port' => '8000'
            ]
        ],
        'contentType' => 'application/json',
    ];

    foreach ($deviceIds as $deviceId) {
        \App\Models\DeviceGroupCommand::createAndDispatch([
            'payload' => $payload, // ✔️ agora enviado corretamente
            'device_group_id' => $groupId,
            'devices' => [$deviceId]
        ], $schoolId);
    }
}

}
