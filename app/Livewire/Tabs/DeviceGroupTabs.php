<?php

namespace App\Livewire\Tabs;

use Livewire\Component;
use Illuminate\Support\Collection;

class DeviceGroupTabs extends Component
{
    public Collection $groups;
    public Collection $schoolDevices;
    public Collection $autoTargetsByGroup;

    public string $tab = 'groups';

    public function mount($groups, $schoolDevices, $autoTargetsByGroup)
    {
        $this->groups = collect($groups);
        $this->schoolDevices = collect($schoolDevices);
        $this->autoTargetsByGroup = collect($autoTargetsByGroup);
    }

    public function selectTab(string $tab)
{
    $this->tab = $tab;

    if ($tab === 'groups') {
        $this->dispatch('refresh-global-vars');
    }

}

    public function render()
    {
        return view('livewire.tabs.device-group-tabs');
    }
    
}
