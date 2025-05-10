<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Grupos de Equipamentos') }}
        </h2>
    </x-slot>
    <script>
        window.alpineKey = Date.now();
    
        document.addEventListener('refresh-global-vars', () => {
            window.schoolDevices = @json($schoolDevices);
            window.alpineKey = Date.now(); // muda o valor = força Alpine recriar
        });
    </script>

    <livewire:tabs.device-group-tabs :groups="$groups" :schoolDevices="$schoolDevices" :autoTargetsByGroup="$autoTargetsByGroup" />
</x-admin-layout>
