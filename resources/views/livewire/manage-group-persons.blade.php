<div>
    <button wire:click="openModal"
        class="px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 rounded hover:bg-indigo-700">
        Gerenciar Pessoas
    </button>
    


    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-4xl p-6 bg-white rounded-lg shadow-lg" @click.outside="$wire.closeModal()">

                {{-- Título e Fechar --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Gerenciar Pessoas: {{ $group->name }}</h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-800">✖️</button>
                    
                </div>

                {{-- Filtro de Tipo --}}
                <div class="flex gap-2 mb-4">
                    <button wire:click="$set('personType', 'students')"
                        class="px-3 py-1.5 rounded text-sm font-medium
                        {{ $personType === 'students' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Alunos
                    </button>
                    <button wire:click="$set('personType', 'guardians')"
                        class="px-3 py-1.5 rounded text-sm font-medium
                        {{ $personType === 'guardians' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Responsáveis
                    </button>
                    <button wire:click="$set('personType', 'functionaries')"
                        class="px-3 py-1.5 rounded text-sm font-medium
                        {{ $personType === 'functionaries' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Funcionários
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2">
                    {{-- Disponíveis --}}
                    <div>
                        <h4 class="mb-2 text-sm font-semibold text-gray-600">Disponíveis</h4>
                        <input type="text" placeholder="Buscar disponível..."
                            wire:model.debounce.300ms="searchAvailable"
                            class="w-full px-3 py-1 mb-2 text-sm border rounded" />
                        <label class="flex items-center gap-2 mb-2 text-sm text-gray-600">
                            <input type="checkbox" wire:click="toggleSelectAll('available')"
                                @checked($this->allAvailableSelected)>
                            Selecionar todos disponíveis
                        </label>
                        <ul class="overflow-y-auto border divide-y rounded max-h-64">
                            <div class="mt-2 text-sm text-gray-500">

                            </div>
                            @forelse($available as $person)
                                <li class="flex items-center gap-2 px-3 py-2 text-sm text-gray-800">
                                    <input type="checkbox" wire:model="selectedAvailable" value="{{ $person->uuid }}">
                                    <span>{{ $person->name }}</span>
                                </li>
                            @empty
                                <li class="px-3 py-2 text-sm italic text-gray-400">Nenhum disponível</li>
                            @endforelse
                        </ul>
                        @if (count($selectedAvailable))
                            {{ count($selectedAvailable) }} selecionado(s)
                        @else
                            Nenhum selecionado
                        @endif
                    </div>

                    {{-- Vinculados --}}
                    <div>
                        <h4 class="mb-2 text-sm font-semibold text-gray-600">Vinculados</h4>
                        <input type="text" placeholder="Buscar vinculado..." wire:model.debounce.300ms="searchLinked"
                            class="w-full px-3 py-1 mb-2 text-sm border rounded" />
                        <label class="flex items-center gap-2 mb-2 text-sm text-gray-600">
                            <input type="checkbox" wire:click="toggleSelectAll('linked')" @checked($this->allLinkedSelected)>
                            Selecionar todos vinculados
                        </label>
                        <ul class="overflow-y-auto border divide-y rounded max-h-64">

                            @forelse($linked as $person)
                                <li class="flex items-center gap-2 px-3 py-2 text-sm text-gray-800">
                                    <input type="checkbox" wire:model="selectedLinked" value="{{ $person->uuid }}">
                                    <span>{{ $person->name }}</span>
                                </li>
                            @empty
                                <li class="px-3 py-2 text-sm italic text-gray-400">Nenhum vinculado</li>
                            @endforelse
                        </ul>
                        <div class="mt-2 text-sm text-gray-500">
                            @if (count($selectedLinked))
                                {{ count($selectedLinked) }} selecionado(s)
                            @else
                                Nenhum selecionado
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button wire:click="linkSelected"
                        class="px-4 py-2 text-sm font-semibold text-white transition bg-green-600 rounded hover:bg-green-700">
                        Vincular Selecionados
                    </button>

                    <button wire:click="unlinkSelected"
                        class="px-4 py-2 text-sm font-semibold text-white transition bg-red-600 rounded hover:bg-red-700">
                        Remover Selecionados
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
