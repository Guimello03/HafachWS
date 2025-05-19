<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Gerenciar Escola') }}
        </h2>
    </x-slot>
    {{-- Mensagens --}}
    @if (session('success'))
        <div class="p-4 text-green-800 bg-green-100 border border-green-300 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 mt-4 text-red-800 bg-red-100 border border-red-300 rounded shadow">
            <ul class="pl-5 list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div x-data="{ tab: 'dados' }" class="px-6 pt-2">
        <h2 class="mb-4 text-xl font-bold text-gray-900">Gerenciando Escola</h2>

        {{-- Tabs --}}
        <div class="flex flex-wrap gap-2 mb-4 text-sm font-medium">
            <button @click="tab = 'dados'"
                :class="tab === 'dados'
                    ?
                    'text-blue-600 bg-gray-100 border border-gray-300' :
                    'text-gray-700 hover:text-blue-600 hover:bg-gray-50 border border-transparent'"
                class="px-4 py-2 transition rounded-lg">
                📄 Dados Básicos
            </button>
            <button @click="tab = 'acesso'"
                :class="tab === 'acesso'
                    ?
                    'text-blue-600 bg-gray-100 border border-gray-300' :
                    'text-gray-700 hover:text-blue-600 hover:bg-gray-50 border border-transparent'"
                class="px-4 py-2 transition rounded-lg">
                🔐 Controle de Acesso
            </button>
            <button @click="tab = 'qr'"
                :class="tab === 'qr'
                    ?
                    'text-blue-600 bg-gray-100 border border-gray-300' :
                    'text-gray-700 hover:text-blue-600 hover:bg-gray-50 border border-transparent'"
                class="px-4 py-2 transition rounded-lg">
                📱 QR Code
            </button>
        </div>

        {{-- Tab Content --}}
        <div class="p-6 bg-white border rounded-lg shadow-sm">
            {{-- DADOS BÁSICOS --}}
            <div x-show="tab === 'dados'" x-cloak>



                {{-- Container principal --}}
                <div class="bg-white rounded-lg shadow-sm ">
                    <h3 class="mb-4 text-lg font-semibold text-gray-700">Dados da Escola</h3>

                    {{-- Dados fixos da escola --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium">Nome</label>
                            <input type="text" value="{{ $school->name }}" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Razão Social</label>
                            <input type="text" value="{{ $school->corporate_name ?? '' }}" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">CNPJ</label>
                            <input type="text" value="{{ $school->cnpj }}" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>

                    {{-- Linha divisória com texto centralizado --}}
                    <div class="flex items-center my-8">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="mx-4 text-sm font-medium text-gray-500">Acesso Diretor</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    {{-- Bloco do diretor --}}
                    @if ($director)
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium">Email</label>
                                <input type="email" value="{{ $director->email }}" disabled
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <form method="POST" action="{{ route('director.update-password', $director) }}">
                                @csrf
                                @method('PUT')




                                <label class="block text-sm font-medium">Nova Senha</label>
                                <input type="password" name="password" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <button type="submit"
                                    class="px-4 py-2 mt-2 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                    Atualizar Senha
                                </button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('schools.director.store') }}"
                            class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @csrf


                            <input type="hidden" name="school_id" value="{{ $school->uuid }}">

                            <div>
                                <label class="block text-sm font-medium">Nome</label>
                                <input type="text" name="name" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Email</label>
                                <input type="email" name="email" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div class="md:col-span-2 md:flex md:items-end md:gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium">Senha</label>
                                    <input type="password" name="password" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-[300px] p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div class="mt-2 md:mt-0">
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-semibold text-left text-white bg-green-600 rounded hover:bg-green-700">
                                        Criar Diretor
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- ... tudo acima permanece idêntico ... -->

            {{-- CONTROLE DE ACESSO --}}
            <div x-show="tab === 'acesso'" x-cloak>

                {{-- Título e botão soltos sobre fundo da página --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Acessos</h3>

                    <a href="{{ route('groups.index') }}"
                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Gerenciar Equipamentos
                    </a>
                </div>

                {{-- Área de cards responsiva --}}
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($groups as $group)
                        <div
                            class="p-4 transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $group->name }}</h4>
                            <p class="mt-1 text-sm text-gray-500">Dispositivos: {{ $group->devices->count() }}</p>

                            {{-- Botões de ação --}}
                            <div class="flex gap-2 mt-4">
                                {{-- Livewire de Pessoas --}}
                                <livewire:manage-group-persons :group="$group" />

                                {{-- Slot extra se quiser equipamento aqui depois --}}
                                {{-- <a href="#" class="btn btn-secondary">Gerenciar Equipamentos</a> --}}
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>





          <div x-show="tab === 'qr'" x-cloak x-init="initPersonSelect()">
    <h3 class="mb-4 text-lg font-semibold text-gray-700">Gerar QR Codes</h3>

    <!-- Card Estilo Moderno -->
    <div class="max-w-sm p-6 text-gray-700 bg-white border border-gray-200 rounded-lg shadow-md">
        <div class="flex flex-col items-start space-y-4">
            <div class="text-3xl text-indigo-600">
                <!-- Ícone -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4h4v4H4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 6h4v4h-4v-4zm6-6h4v4h-4v-4zm0 6h4v4h-4v-4z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold">Gerar QR Codes</h3>
            <p class="text-sm text-gray-600">
                Gere um arquivo PDF com os QR Codes dos <strong>alunos</strong>, <strong>responsáveis</strong> ou <strong>funcionários</strong>.
            </p>
            <button onclick="document.getElementById('qrModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                Gerar
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div id="qrModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white w-full max-w-5xl rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]">

            <!-- Fechar -->
            <button onclick="document.getElementById('qrModal').classList.add('hidden')"
                class="absolute text-2xl text-gray-400 top-4 right-6 hover:text-gray-600">&times;</button>

            <h3 class="mb-6 text-2xl font-semibold text-gray-800">Configurar QR Codes</h3>

            <!-- Formulário -->
            <form id="qr-form" class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3" onsubmit="return false;">
                @csrf

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Tipo de Usuário</label>
                    <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Selecione</option>
                        @foreach ($types as $key => $info)
                            <option value="{{ $key }}">{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Nome</label>
                    <select name="person_uuid" id="person_uuid"
                        placeholder="Todos"
                        autocomplete="off"
                        class="w-full border-gray-300 rounded shadow-sm">
                        <option value="">Todos</option>
                    </select>
                </div>

                <div class="self-end">
                    <button type="submit" onclick="submitQRForm()"
                        class="w-full px-4 py-2 text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Gerar
                    </button>
                </div>
            </form>

            <!-- Sempre presente no DOM -->
            <div id="qr-preview" class="hidden p-6 mb-6 border border-gray-100 rounded-lg bg-gray-50">
                <!-- QR Codes vão ser carregados aqui via JS -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
  <script>
    // Inicializa TomSelect
    function initPersonSelect() {
        if (window.personSelectInstance) return;

        const typeInput = document.querySelector('[name="type"]');
        const selectElement = document.getElementById('person_uuid');

        window.personSelectInstance = new TomSelect(selectElement, {
            create: false,
            allowEmptyOption: true,
            placeholder: 'Todos',
            valueField: 'value',
            labelField: 'text',
            searchField: 'text',
            preload: false,
            load: function(query, callback) {
                const type = typeInput.value;
                if (!type || query.length < 1) return callback();
                fetch(`/reports/person-search?type=${type}&term=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => callback(data.map(p => ({ value: p.uuid, text: p.name }))))
                    .catch(() => callback());
            },
            plugins: ['clear_button'],
            onClear: function () {
                this.clearOptions();
                this.addOption({ value: '', text: 'Todos' });
                this.setValue('');
            }
        });

        typeInput.addEventListener('change', () => {
            if (window.personSelectInstance) {
                window.personSelectInstance.destroy();
                window.personSelectInstance = null;
            }
            setTimeout(() => initPersonSelect(), 50);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initPersonSelect();
    });

    // Espera o form aparecer e atualiza os campos ocultos
    function waitForFormAndSetInputs(type, person) {
        const preview = document.getElementById('qr-preview');
        const form = preview.querySelector('form[action*="qr-download-pdf"]');

        if (form) {
            const typeInput = form.querySelector('input[name="type"]');
            const uuidInput = form.querySelector('input[name="person_uuid"]');

            if (typeInput) typeInput.value = type;
            if (uuidInput) uuidInput.value = person;

            console.log("📥 Inputs atualizados para PDF:", { type, person });
        } else {
            // 🔁 tenta novamente em 100ms se ainda não carregou
            setTimeout(() => waitForFormAndSetInputs(type, person), 100);
        }
    }

    // Gera o preview dos QR Codes
    function submitQRForm() {
        const type = document.getElementById('type').value;
        const personSelect = document.getElementById('person_uuid');
        const selectInstance = personSelect?.tomselect;
        const person = selectInstance?.getValue() ?? '';

        const preview = document.getElementById('qr-preview');
        if (!preview) {
            console.warn('#qr-preview não encontrado');
            return;
        }

        preview.innerHTML = '<p class="text-gray-500">Carregando...</p>';
        preview.classList.remove('hidden');

        fetch(`/qr-preview?type=${type}&person_uuid=${person}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            preview.innerHTML = html;
            waitForFormAndSetInputs(type, person);
        })
        .catch(() => {
            preview.innerHTML = '<p class="text-red-500">Erro ao gerar QR Codes.</p>';
        });
    }
</script>


</div>

</x-admin-layout>
