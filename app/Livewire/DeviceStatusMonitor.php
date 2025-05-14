<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Device;

class DeviceStatusMonitor extends Component
{
    public $devices = [];

    public function loadDevices()
    {
        $school = activeSchool();

        if (!$school) {
            $this->devices = collect(); // Retorna vazio
            return;
        }

        // Carregar dispositivos apenas da escola ativa
        $this->devices = Device::with('status')
            ->where('school_id', $school->uuid)
            ->get();
    }

    public function mount()
    {
        $this->loadDevices();
    }

    public function render()
    {
        $this->loadDevices();
        return view('livewire.device-status-monitor');
    }
}
