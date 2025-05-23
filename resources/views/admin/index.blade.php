<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />
    
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Editar Clientes') }}
        </h2>
    </x-slot>
    <div class="px-1 pt-2">
        <h2 class="px-6 mb-4 text-xl font-bold text-gray-900 pd-6">Listagem de Clientes</h2>

        {{-- Filtro e botão --}}
        <div class="px-6 pd-6">
            <div class="p-4 bg-white border-b border-gray-200 rounded-lg shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <form method="GET" action="{{ route('clients.index') }}">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nome ou CNPJ"
                            class="h-10 px-4 py-2 border rounded-lg shadow-sm w-[400px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </form>

                    <a href="{{ route('clients.create') }}"
                        class="flex items-center justify-center h-10 px-4 py-2 font-semibold text-white bg-blue-500 border rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                        Cadastrar
                    </a>
                </div>
            </div>
        </div>
        {{-- Fim do filtro e botão --}}

        {{-- Tabela --}}
        <div class="px-6 py-6">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <table class="min-w-full bg-white divide-y divide-gray-200 rounded shadow">
                    <thead class="text-gray-600 bg-gray-100 ">
                        <tr>
                            <th class="px-4 py-3 text-left">Nome</th>
                            <th class="px-4 py-3 text-center">CNPJ</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- Linha por responsável --}}
                        @foreach ($clients as $index => $clients)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-4">{{ $clients->name }}</td>
                                <td class="px-4 py-4 text-center">{{ $clients->cnpj }}</td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('clients.edit', $clients->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 transition rounded-full hover:text-white hover:bg-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                                <a href="{{ route('clients.schools', $clients->id) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-yellow-400 transition rounded-full hover:text-white hover:bg-yellow-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                                      </svg>
                                                      

                                    </form>
                                </td>
                            </tr>
                        @endforeach
</x-admin-layout>
