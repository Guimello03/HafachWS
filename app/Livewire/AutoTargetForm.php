<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\DeviceGroup;
use App\Models\DeviceGroupAutoTarget;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\Functionary;
use App\Services\DeviceGroupSyncService;


class AutoTargetForm extends Component
{
    public $groups;
    public $device_group_id;
    public $target_types = [];

    public function mount($groups)
    {
        $this->groups = $groups;
        $this->device_group_id = $groups->first()->uuid ?? null;
        $this->loadTargets();
    }

    public function updatedDeviceGroupId()
    {
        $this->loadTargets();
    }

    public function loadTargets()
    {
        $types = [
            Student::class => 'students',
            Guardian::class => 'guardians',
            Functionary::class => 'functionaries',
        ];

        $targets = DeviceGroupAutoTarget::where('device_group_id', $this->device_group_id)->pluck('target_type');
        logger()->info('[AutoTargetForm] Target types encontrados no banco:', $targets->toArray());

        $this->target_types = $targets
            ->map(fn($t) => $types[$t] ?? null)
            ->filter()
            ->values()
            ->toArray();
            logger()->info('[AutoTargetForm] Checkboxes que serão marcados:', $this->target_types);

    }

    public function save()
    {
        $this->validate([
            'device_group_id' => 'required|exists:device_groups,uuid',
            'target_types' => 'nullable|array',
            'target_types.*' => 'in:students,guardians,functionaries',
        ]);
    
        DeviceGroupAutoTarget::where('device_group_id', $this->device_group_id)->delete();
    
        $types = [
            'students' => Student::class,
            'guardians' => Guardian::class,
            'functionaries' => Functionary::class,
        ];
    
        if (!empty($this->target_types)) {
            $data = collect($this->target_types)
                ->filter(fn($type) => isset($types[$type]))
                ->map(fn($type) => [
                    'uuid' => (string) Str::uuid(),
                    'device_group_id' => $this->device_group_id,
                    'target_type' => $types[$type],
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();
    
            DeviceGroupAutoTarget::insert($data);
            
        }
    
        // ✅ Estes dois são boas práticas:
        $this->resetErrorBag(); 
        $this->loadTargets(); // 🔁 Atualiza os checkboxes no front
    
        session()->flash('success', 'Configuração salva com sucesso.');
    }
    

    public function render()
    {
        return view('livewire.auto-target-form');
    }
}
