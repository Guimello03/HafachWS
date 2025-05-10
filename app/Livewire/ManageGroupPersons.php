<?php

namespace App\Livewire;

use App\Models\DeviceGroup;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\Functionary;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Services\DeviceGroupSyncService;


class ManageGroupPersons extends Component
{
    public DeviceGroup $group;
    public string $personType = 'students';
    public bool $showModal = false;

    public Collection $linked;
    public Collection $available;

    public array $selectedAvailable = [];
    public array $selectedLinked = [];

    public string $searchAvailable = '';
    public string $searchLinked = '';

    public function mount(DeviceGroup $group)
    {
        $this->group = $group;
        $this->linked = collect();
        $this->available = collect();
    }

    public function render()
    {
        return view('livewire.manage-group-persons');
    }

    public function openModal()
{
    $this->selectedAvailable = [];
    $this->selectedLinked = [];
    $this->searchAvailable = '';
    $this->searchLinked = '';
    $this->showModal = true;
    $this->loadPeople();
}

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function updatedPersonType()
    {
        $this->selectedAvailable = [];
        $this->selectedLinked = [];
        $this->searchAvailable = '';
        $this->searchLinked = '';
        $this->loadPeople();
    }

    public function updatedSearchAvailable()
    {
        $this->loadPeople();
    }

    public function updatedSearchLinked()
    {
        $this->loadPeople();
    }

    protected function loadPeople()
    {
        $school = activeSchool();
        $class = $this->resolveModel();

        $this->linked = $class::where('school_id', $school->uuid)
            ->whereHas('deviceGroups', fn($q) => $q->where('device_group_id', $this->group->uuid))
            ->when($this->searchLinked, fn($q) => $q->where('name', 'like', '%' . $this->searchLinked . '%'))
            ->get();

        $this->available = $class::where('school_id', $school->uuid)
            ->whereDoesntHave('deviceGroups', fn($q) => $q->where('device_group_id', $this->group->uuid))
            ->when($this->searchAvailable, fn($q) => $q->where('name', 'like', '%' . $this->searchAvailable . '%'))
            ->get();
    }

    protected function resolveModel()
    {
        return match ($this->personType) {
            'students' => Student::class,
            'guardians' => Guardian::class,
            'functionaries' => Functionary::class,
            default => Student::class,
        };
    }

    public function linkSelected()
    {
        $modelClass = $this->resolveModel();

        foreach ($this->selectedAvailable as $uuid) {
            DB::table('device_group_person')->updateOrInsert([
                'device_group_id' => $this->group->uuid,
                'person_id' => $uuid,
                'person_type' => $modelClass,
            ]);
        }

        $modelClass = $this->resolveModel();

$people = $modelClass::whereIn('uuid', $this->selectedAvailable)
    ->get()
    ->map(function ($person) use ($modelClass) {
        return [
            'uuid' => $person->uuid,
            'type' => $modelClass,
            'relation' => DeviceGroupSyncService::relationMethodFromType($modelClass),
        ];
    });

DeviceGroupSyncService::addPersonsToGroup($this->group, $people);

$this->selectedAvailable = [];
$this->loadPeople();

session()->flash('message', 'Pessoas vinculadas e sincronizadas com sucesso!');
    }

    public function unlinkSelected()
{
    $modelClass = $this->resolveModel();

    $people = collect($this->selectedLinked)->map(function ($uuid) use ($modelClass) {
        return [
            'uuid' => $uuid,
            'type' => $modelClass,
        ];
    });

    // 1. Remove da tabela pivot (visualmente desvincula no sistema)
    DB::table('device_group_person')
        ->where('device_group_id', $this->group->uuid)
        ->where('person_type', $modelClass)
        ->whereIn('person_id', $this->selectedLinked)
        ->delete();

    // 2. Remove do equipamento via comando
    \App\Services\DeviceGroupSyncService::removePersonsFromGroup($this->group, $people);

    // 3. Limpa o estado do componente
    $this->selectedLinked = [];
    $this->loadPeople();

    session()->flash('message', 'Pessoas desvinculadas e comando de remoção enviado.');
}
    public function toggleSelectAll($list)
    {
        if ($list === 'available') {
            $this->selectedAvailable = $this->allAvailableSelected ? [] : $this->available->pluck('uuid')->toArray();
        }
    
        if ($list === 'linked') {
            $this->selectedLinked = $this->allLinkedSelected ? [] : $this->linked->pluck('uuid')->toArray();
        }
    }
public function getAllAvailableSelectedProperty(): bool
{
    return $this->available->count() > 0 &&
           count($this->selectedAvailable) === $this->available->count();
}

public function getAllLinkedSelectedProperty(): bool
{
    return $this->linked->count() > 0 &&
           count($this->selectedLinked) === $this->linked->count();
}
}
