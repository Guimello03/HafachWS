<div class="py-4">
    <div class="max-w-xl mx-auto">
        <div class="p-5 bg-white rounded-lg shadow-md">
            <div class="space-y-6">

                {{-- Barra de progresso com ícones --}}
                @php
                    $steps = [
                        1 => ['label' => 'Cliente', 'icon' => 'user'],
                        2 => ['label' => 'Escola', 'icon' => 'school'],
                        3 => ['label' => 'Confirmação', 'icon' => 'check'],
                    ];

                    $icons = [
                        'user' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>',
                        'school' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/></svg>',
                        'check' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
                    ];
                @endphp

                <div class="flex items-center justify-between mb-2">
                    @foreach ($steps as $index => $stepData)
                        <div class="flex-1 flex items-center {{ $index !== 1 ? 'ml-4' : '' }}">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border-2
                                        {{ $step >= $index ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-500 border-gray-300' }}">
                                {!! $icons[$stepData['icon']] !!}
                            </div>
                            <div
                                class="ml-3 text-sm font-medium {{ $step >= $index ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ $stepData['label'] }}
                            </div>
                            @if ($index < count($steps))
                                <div class="flex-1 h-0.5 mx-4 {{ $step > $index ? 'bg-blue-600' : 'bg-gray-300' }}">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Etapa 1: Cliente + Admin --}}
                @if ($step === 1)
                    <div class="space-y-4">

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nome</label>
                            <input type="text" wire:model.defer="clientData.name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @error('clientData.name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">E-mail</label>
                            <input type="email" wire:model.defer="clientData.email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @error('clientData.email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">CNPJ</label>
                            <input type="text" wire:model.defer="clientData.cnpj" x-data x-mask="99.999.999/9999-99"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @error('clientData.cnpj')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-center my-6">
                            <div class="w-full border-t border-gray-300"></div>
                            <span class="px-3 text-sm text-gray-500 whitespace-nowrap">Login do Administrador</span>
                            <div class="w-full border-t border-gray-300"></div>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">E-mail de acesso</label>
                            <input type="email" wire:model.defer="adminUserData.email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @error('adminUserData.email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Senha de acesso</label>
                            <input type="password" wire:model.defer="adminUserData.password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @error('adminUserData.password')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-6">
                            <button wire:click="nextStep"
                                class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-6 py-2.5">
                                Próximo
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Etapa 2: Escola --}}
                @if ($step === 2)
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold text-center text-gray-800">Dados da Escola</h2>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nome da Escola</label>
                            <input type="text" wire:model.defer="schoolData.name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @error('schoolData.name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">CNPJ da Escola</label>
                            <input type="text" wire:model.defer="schoolData.cnpj" x-data x-mask="99.999.999/9999-99"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @error('schoolData.cnpj')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="flex justify-between mt-6">
                            <button wire:click="prevStep"
                                class="text-white bg-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-6 py-2.5">
                                Voltar
                            </button>

                            <button wire:click="nextStep"
                                class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-6 py-2.5">
                                Próximo
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Etapa 3: Confirmação --}}
                @if ($step === 3)
                    <div class="space-y-6">
                        <h2 class="text-xl font-bold text-center text-gray-800">Confirmação dos Dados</h2>

                        <div class="p-6 space-y-6 bg-white border border-gray-200 rounded-lg shadow-md">
                            <div>
                                <h3 class="mb-2 text-lg font-semibold text-gray-700">Cliente</h3>
                                <p><strong>Nome:</strong> {{ $clientData['name'] }}</p>
                                <p><strong>Email:</strong> {{ $clientData['email'] }}</p>
                                <p><strong>CNPJ:</strong> {{ $clientData['cnpj'] }}</p>
                            </div>

                            <div>
                                <h3 class="mb-2 text-lg font-semibold text-gray-700">Administrador</h3>
                                <p><strong>Email:</strong> {{ $adminUserData['email'] }}</p>
                                <p class="text-sm text-gray-400">(A senha não será exibida por segurança)</p>
                            </div>

                            <div>
                                <h3 class="mb-2 text-lg font-semibold text-gray-700">Escola</h3>
                                <p><strong>Nome:</strong> {{ $schoolData['name'] }}</p>
                                <p><strong>CNPJ:</strong> {{ $schoolData['cnpj'] }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button wire:click="prevStep"
                                class="text-white bg-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-6 py-2.5">
                                Voltar
                            </button>

                            <button wire:click="submit"
                                class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-6 py-2.5">
                                Cadastrar
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
