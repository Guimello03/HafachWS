<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-xl mx-auto">
            <div class="p-5 bg-white rounded-lg shadow-md">
                <form method="POST" action="{{ route('clients.update', $client) }}">

                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        {{-- Nome --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nome</label>
                            <input type="text" name="name" value="{{ old('name', $client->name) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $client->email) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        {{-- CNPJ --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">CNPJ</label>
                            <input type="text" name="cnpj"
                                x-data x-init="new Cleave($el, { delimiters: ['.', '.', '/', '-'], blocks: [2, 3, 3, 4, 2], numericOnly: true })"
                                value="{{ old('cnpj', $client->cnpj) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('cnpj') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        {{-- Separador --}}
                        <div class="flex items-center justify-center my-6">
                            <div class="w-full border-t border-gray-300"></div>
                            <span class="px-3 text-sm text-gray-500 whitespace-nowrap">Administrador da Conta</span>
                            <div class="w-full border-t border-gray-300"></div>
                        </div>

                        {{-- Email de Acesso (readonly) --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">E-mail de Acesso</label>
                            <input type="email" value="{{ $clientAdmin->email ?? 'N/A' }}" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-600 text-sm rounded-lg block w-full p-2.5">
                        </div>

                        {{-- Nova senha --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nova Senha</label>
                            <input type="password" name="admin_password"
                                placeholder="Altere sua senha"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('admin_password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        {{-- Botões --}}
                        <div class="flex justify-end gap-4 pt-4">
                            <a href="{{ route('admin.dashboard') }}"
                                class="px-6 py-2.5 rounded-lg text-sm bg-gray-300 hover:bg-gray-400 text-black transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-lg text-sm bg-blue-600 hover:bg-blue-700 text-white transition">
                                Salvar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
