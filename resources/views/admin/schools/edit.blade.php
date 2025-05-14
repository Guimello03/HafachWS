<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Editar Escola') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="p-6 bg-white rounded-lg shadow-md">

                {{-- Abas --}}
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                        <button id="tab-basicos" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-button hover:text-gray-700 whitespace-nowrap rounded-t-md"
                            onclick="openTab('basicos')">
                            Dados Básicos
                        </button>
                        <button id="tab-config" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent tab-button hover:text-gray-700 whitespace-nowrap rounded-t-md"
                            onclick="openTab('config')">
                            Configurações
                        </button>
                    </nav>
                </div>

                {{-- Conteúdo das abas --}}
                <div id="tab-content-basicos" class="mt-6 tab-content">
                    {{-- Form de dados básicos --}}
                    <form method="POST" action="{{ route('schools.update', $school) }}">
                        @csrf
                        @method('PUT')

                        {{-- Nome --}}
                        <div class="mb-4">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nome da Escola</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $school->name) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                required>
                        </div>

                        {{-- CNPJ --}}
                        <div class="mb-4">
                            <label for="cnpj" class="block mb-2 text-sm font-medium text-gray-900">CNPJ</label>
                            <input type="text" name="cnpj" id="cnpj"
                                value="{{ old('cnpj', $school->cnpj) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                required>
                        </div>

                        {{-- Razão Social --}}
                        <div class="mb-6">
                            <label for="corporate_name" class="block mb-2 text-sm font-medium text-gray-900">Razão Social</label>
                            <input type="text" name="corporate_name" id="corporate_name"
                                value="{{ old('corporate_name', $school->corporate_name) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('clients.schools', $school->client_id) }}"
                                class="px-6 py-2.5 text-sm bg-gray-300 hover:bg-gray-400 text-black rounded-lg transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>

                <div id="tab-content-config" class="hidden mt-6 tab-content">
                    {{-- Card Configurações --}}
                    <div class="p-4 mb-6 bg-white border rounded-lg shadow-sm">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">Tolerância de Entrada/Saída</h3>
                                <p class="text-sm text-gray-500">Tempo em minutos para alternância automática entre entrada e saída.</p>
                            </div>
                        </div>

                        <form action="{{ url('/school/settings/tolerance') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="tolerance_minutes" class="block text-sm font-medium text-gray-700">Tolerância (minutos)</label>
                                <input type="number" name="tolerance_minutes" id="tolerance_minutes"
                                    value="{{ $tolerance?->value ?? 0 }}"
                                    min="0" max="1440"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Ex: 5">
                                <small class="text-gray-500">Coloque 0 para considerar todas as passagens como entrada (modo apresentação).</small>
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 font-semibold text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Salvar Configuração
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openTab(tab) {
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.tab-button').forEach(el => {
                    el.classList.remove('border-blue-600', 'text-blue-700', 'bg-blue-100');
                    el.classList.add('text-gray-500');
                });

                document.getElementById('tab-content-' + tab).classList.remove('hidden');
                document.getElementById('tab-' + tab).classList.add('border-blue-600', 'text-blue-700', 'bg-blue-100');
                document.getElementById('tab-' + tab).classList.remove('text-gray-500');
            }

            // Inicia a aba Básicos aberta
            openTab('basicos');
        </script>
    @endpush
</x-admin-layout>
