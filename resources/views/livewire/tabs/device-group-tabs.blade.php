<div>
    <script>
        window.schoolDevices = @json($schoolDevices);
    </script>
    <div class="px-6 py-4 space-y-6">
        {{-- ⏹️ Abas --}}
        <div class="flex gap-2 border-b">
            <button wire:click="selectTab('groups')" @class([
                'px-4 py-2 text-sm font-medium border-b-2 hover:text-blue-600',
                'border-blue-600 text-blue-600' => $tab === 'groups',
            ])>
                Grupos de Equipamento
            </button>

            <button wire:click="selectTab('auto')" @class([
                'px-4 py-2 text-sm font-medium border-b-2 hover:text-blue-600',
                'border-blue-600 text-blue-600' => $tab === 'auto',
            ])>
                Envio Automático
            </button>
        </div>

        {{-- 📦 Aba: Grupos de Equipamento --}}
        @if ($tab === 'groups')
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Grupos De Equipamentos</h3>

                    <button @click="$dispatch('open-create-modal')"
                        class="flex items-center justify-center h-10 px-4 py-2 font-semibold text-white bg-blue-500 border rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                        Cadastrar
                    </button>
                </div>

                @if ($groups->isEmpty())
                    <div class="p-6 text-center text-gray-500 bg-white border border-dashed rounded-lg">
                        Nenhum grupo cadastrado.
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($groups as $group)
                            <div
                                class="relative flex flex-col p-4 bg-white border rounded-lg shadow-sm hover:shadow-md">
                                <!-- Botões no canto superior direito -->
                                <div x-data class="absolute flex gap-2 top-2 right-2">
                                    <button @click="$dispatch('open-edit-modal', @js($group))"
                                        class="text-yellow-600 hover:text-yellow-800" title="Editar grupo">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.867 19.125h.008v.008h-.008v-.008Z" />
                                        </svg>
                                    </button>

                                    <form action="{{ route('groups.destroy', $group->uuid) }}" method="POST"
                                        onsubmit="return confirm('Deseja mesmo excluir este grupo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"
                                            title="Excluir grupo">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <!-- Conteúdo do card (alinhado ao topo) -->
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $group->name }}</h4>
                                    <p class="mt-1 text-sm text-gray-500">Dispositivos: {{ $group->devices->count() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @include('groups.partials.create-modal')
                @include('groups.partials.edit-modal')
            </div>
        @endif

        {{-- 📤 Aba: Envio Automático --}}
        @if ($tab === 'auto')
            <livewire:auto-target-form :groups="$groups" />
        @endif
    </div>
</div>
