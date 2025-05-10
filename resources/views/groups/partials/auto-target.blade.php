

@if ($groups->count())
    <div class="p-6 bg-white border rounded-lg shadow-sm">
        <h3 class="mb-4 text-xl font-semibold text-gray-800">Automatizar Envio de Pessoas para Grupo</h3>

        <form method="POST" action="{{ route('groups.auto_target') }}" class="space-y-4">
            @csrf

            {{-- Grupo --}}
            <div>
                <label for="device_group_id" class="block text-sm font-medium text-gray-700">Grupo</label>
                <select name="device_group_id" id="device_group_id" x-model="selectedGroupId"
                    @change="updateSelectedTypes()"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($groups as $group)
                        <option value="{{ $group->uuid }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tipos --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Enviar automaticamente:</label>
                <div class="flex flex-wrap gap-4">
                    <template x-for="type in ['students', 'guardians', 'functionaries']" :key="type">
                        <label class="inline-flex items-center">
                            <input type="checkbox" :value="type" name="target_types[]" x-model="selectedTypes"
                                class="text-blue-600 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700"
                                x-text="{
                                    students: 'Alunos',
                                    guardians: 'Responsáveis',
                                    functionaries: 'Funcionários'
                                }[type]"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="flex items-center justify-center h-10 px-4 py-2 font-semibold text-white bg-blue-500 border rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                    Salvar Configuração
                </button>
            </div>
        </form>
    </div>
@endif
