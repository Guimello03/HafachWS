<form wire:submit.prevent="save" class="p-6 bg-white border rounded-lg shadow-sm space-y-6">
    <h3 class="text-xl font-semibold text-gray-800">Automatizar Envio de Pessoas para Grupo</h3>

    @if (session('success'))
        <div class="px-4 py-2 text-green-800 bg-green-100 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <label class="block text-sm font-medium text-gray-700">Grupo</label>
        <select wire:model="device_group_id"
          wire:change="loadTargets"
                class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-300">
            @foreach($groups as $group)
                <option value="{{ $group->uuid }}">{{ $group->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Enviar automaticamente:</label>
        <div class="flex flex-wrap gap-4">
            @foreach(['students' => 'Alunos', 'guardians' => 'Responsáveis', 'functionaries' => 'Funcionários'] as $key => $label)
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="target_types" value="{{ $key }}"
                           class="text-blue-600 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit"
                class="h-10 px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600">
            Salvar Configuração
        </button>
    </div>
</form>
