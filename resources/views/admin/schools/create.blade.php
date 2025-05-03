<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Cadastrar Escola') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto">
            <div class="p-6 bg-white rounded-lg shadow-md">

                {{-- Erros de validação --}}
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="pl-5 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('schools.store'),}}">
                    @csrf

                    {{-- Campo oculto do client --}}
                    <input type="hidden" name="client_id" value="{{ $client->id }}">

                    {{-- Nome da Escola --}}
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nome da
                            Escola</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                    </div>

                    {{-- CNPJ --}}
                    <div class="mb-6">
                        <label for="cnpj" class="block mb-2 text-sm font-medium text-gray-900">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" x-data x-init="new Cleave($el, { delimiters: ['.', '.', '/', '-'], blocks: [2, 3, 3, 4, 2], numericOnly: true })"
                            value="{{ old('cnpj') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                    </div>

                    {{-- Botões --}}
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('clients.schools', $client->id) }}"
                            class="px-6 py-2.5 text-sm bg-gray-300 hover:bg-gray-400 text-black rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                            Salvar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-admin-layout>
