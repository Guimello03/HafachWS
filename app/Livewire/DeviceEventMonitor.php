<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DeviceEvent;

class DeviceEventMonitor extends Component
{
    public $events = [];

    public function loadEvents()
    {
        $school = activeSchool();
        
        if (!$school) {
            $this->events = collect();
            return;
        }

        $events = DeviceEvent::with(['person', 'device'])
            ->whereHas('device', function ($q) use ($school) {
                $q->where('school_id', $school->uuid);
            })
            ->latest('date')
            ->limit(10)
            ->get()
            ->map(function ($event) {
                return [
                    'date' => $event->date->format('d/m/Y H:i:s'),
                    'name' => $event->person->name ?? 'Desconhecido',
                    'type' => class_basename($event->person_type),
                    'direction' => $event->direction,
                ];
            });

        $this->events = $events;
    }

    public function mount()
    {
        $this->loadEvents();
    }

    public function render()
    {
        $this->loadEvents();
        return view('livewire.device-event-monitor');
    }
}
