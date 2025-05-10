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
                            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
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





            {{-- QR CODE --}}
            <div x-show="tab === 'qr'" x-cloak>
                <h3 class="mb-4 text-lg font-semibold text-gray-700">QR Code de Acesso</h3>
                <p class="mb-2 text-gray-600">Utilize este código para acessar diretamente a página da escola.</p>
                <div class="inline-block p-4 border rounded-lg bg-gray-50">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://escola.app/school/123"
                        alt="QR Code">
                </div>
            </div>
        </div>
    </div>



</x-admin-layout>
