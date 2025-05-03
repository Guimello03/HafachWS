<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientWizard extends Component
{
    public $step = 1;

    public $clientData = [
        'name' => '',
        'email' => '',
        'cnpj' => '',
    ];

    public $adminUserData = [
        'email' => '',
        'password' => '',
    ];

    public $schoolData = [
        'name' => '',
        'cnpj' => '',
    ];

    public $rulesStep1 = [
        'clientData.name' => 'required|string|max:255',
        'clientData.email' => 'required|email|unique:clients,email',
        'clientData.cnpj' => 'required|string|unique:clients,cnpj',
        'adminUserData.email' => 'required|email|unique:users,email',
        'adminUserData.password' => 'required|string|min:6',
    ];

    public $rulesStep2 = [
        'schoolData.name' => 'required|string|max:255',
        'schoolData.cnpj' => 'required|string|unique:schools,cnpj',
    ];

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate($this->rulesStep1);
        }

        if ($this->step === 2) {
            $this->validate($this->rulesStep2);
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function render()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Cadastro de Cliente', 'url' => ''], // Atualizei a descrição
        ];
        return view('livewire.client-wizard')
            ->with('breadcrumbs', $breadcrumbs);
    }

    public function submit()
{
    $this->validate(array_merge($this->rulesStep1, $this->rulesStep2)); // Valida tudo

    DB::beginTransaction();

    try {
        // Criar o Client
        $client = Client::create($this->clientData);

        // Criar o adminUser (client_admin)
        $adminUser = User::create([
            'name' => $this->clientData['name'],
            'email' => $this->adminUserData['email'],
            'password' => bcrypt($this->adminUserData['password']),
            'client_id' => $client->id,
        ]);
        $adminUser->assignRole('client_admin');

        // Criar a primeira School vinculada ao Client
        $school = School::create([
            'name' => $this->schoolData['name'],
            'cnpj' => $this->schoolData['cnpj'],
            'client_id' => $client->id,
        ]);

        // Vincular o client_admin à escola criada
        $adminUser->schools()->attach((string) $school->uuid);

        // ✅ ESSENCIAL: Definir escola padrão pro login funcionar
        $adminUser->update([
            'last_school_uuid' => (string) $school->uuid,
        ]);

        DB::commit();

        session()->flash('success', 'Cliente, administrador e escola cadastrados e vinculados com sucesso!');
        return redirect()->route('admin.dashboard');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erro ao criar cliente: ' . $e->getMessage());
        $this->addError('submit', 'Erro técnico ao salvar os dados. Tente novamente.');
    }
}
}