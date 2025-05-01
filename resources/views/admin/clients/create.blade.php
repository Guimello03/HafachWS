
<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Cadastrar Cliente') }}
        </h2>
    </x-slot>

    <div class="px-1 pt-2">
        <h2 class="px-6 mb-2 text-xl font-bold text-gray-900 pd-6">Cadastro de Clientes</h2>
    </div>

    {{-- Livewire Component --}}
    @livewire('client-wizard')
</x-admin-layout>