@props(['clients', 'client', 'school'])

<div x-data="schoolSwitcher()" x-cloak>
    {{-- Exibição atual --}}
    <div class="flex items-center justify-end gap-4 text-sm text-gray-700">
        <!-- Bloco com nome + escola -->
        <div class="flex flex-col leading-tight text-right">
            <span class="text-xs text-gray-500 truncate">
                {{ $client->name ?? 'Cliente não definido' }}
            </span>
            <span class="font-semibold text-gray-800 truncate">
                {{ $school->name ?? 'Escola não selecionada' }}
            </span>
        </div>

        {{-- Botão que abre o modal e inicia os selects --}}
        <button @click="open = true; initSwitcher()" title="Trocar escola"
            class="text-gray-500 transition hover:text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
        </button>
    </div>

    {{-- Modal --}}
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg" @click.away="open = false">
            <h2 class="mb-2 text-xl font-semibold">Selecionar Escola</h2>


            {{-- Cliente (somente super_admin) --}}
            @if (Auth::user()->hasRole('super_admin'))
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select id="client-select" x-ref="clientSelect" class="w-full text-sm focus:outline-none">
                        <option value="">Selecione um cliente...</option>
                        @foreach ($clients as $clientOption)
                            <option value="{{ $clientOption->id }}">{{ $clientOption->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Escola --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Escola</label>
                <select id="school-select" x-ref="schoolSelect" class="w-full text-sm focus:outline-none">
                    <option value="">Selecione uma escola...</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button @click="open = false"
                    class="px-5 py-2.5 rounded-md text-black bg-gray-300 hover:bg-white-700 shadow-sm hover:shadow-md transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Cancelar
                </button>
                <button @click="confirmarTroca()"
                    class="px-5 py-2.5 rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow-md transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function schoolSwitcher() {
            return {
                open: false,
                selectedClient: '',
                selectedSchool: '',
                schools: [],
                clientSelectInitialized: false,
                schoolSelectInitialized: false,

                initSwitcher() {
                    if (!this.clientSelectInitialized && this.$refs.clientSelect) {
                        new TomSelect(this.$refs.clientSelect, {
                            placeholder: 'Selecione um cliente',
                            onChange: (value) => {
                                this.selectedClient = String(value);

                                // 🔥 LIMPA ESCOLAS ao trocar cliente
                                const schoolSelect = this.$refs.schoolSelect?.tomselect;
                                if (schoolSelect) {
                                    schoolSelect.clear(); // limpa seleção visível
                                    schoolSelect.clearOptions(); // remove opções anteriores
                                }
                                this.fetchSchools(value);
                            },
                            render: {
                                item: (data, escape) =>
                                    `<div class="text-sm text-gray-800">${escape(data.text)}</div>`,
                                option: (data, escape) =>
                                    `<div class="px-3 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100">${escape(data.text)}</div>`
                            }
                        });
                        this.clientSelectInitialized = true;
                    }

                    if (!this.schoolSelectInitialized) {
                        new TomSelect(this.$refs.schoolSelect, {
                            placeholder: 'Selecione uma escola',
                            onChange: (value) => {
                                this.selectedSchool = String(value);
                            },
                            render: {
                                item: (data, escape) =>
                                    `<div class="text-sm text-gray-800">${escape(data.text)}</div>`,
                                option: (data, escape) =>
                                    `<div class="px-3 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100">${escape(data.text)}</div>`
                            }
                        });
                        this.schoolSelectInitialized = true;
                    }

                    @unless (Auth::user()->hasRole('super_admin'))
                        this.selectedClient = '{{ $client->id ?? '' }}';
                        this.fetchSchools(this.selectedClient);
                    @endunless
                },

                fetchSchools(clientId) {
                    fetch(`/schools-select?client_id=${clientId}`)
                        .then(response => response.json())
                        .then(data => {
                            this.schools = data;
                            const schoolSelect = this.$refs.schoolSelect.tomselect;
                            schoolSelect.clearOptions();
                            schoolSelect.addOptions(data.map(school => ({
                                value: school.id,
                                text: school.name
                            })));
                            schoolSelect.refreshOptions();
                        });
                },

                confirmarTroca() {
                    if (!this.selectedSchool) {
                        alert('Selecione uma escola.');
                        return;
                    }

                    fetch('/schools-select', {
                            method: 'POST',
                            credentials: 'same-origin', // 👈 ESSENCIAL!
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                school_id: this.selectedSchool,
                                client_id: this.selectedClient
                            })
                        })
                        .then(response => {
                            if (response.ok) {
                                location.reload();
                            } else {
                                alert('Erro ao selecionar a escola.');
                            }
                        });
                }
            }
        }
    </script>
@endpush
