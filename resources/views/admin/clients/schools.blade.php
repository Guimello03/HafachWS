<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            Escolas da Rede: {{ $client->name }}
        </h2>
    </x-slot>

    {{-- Mensagens --}}
    @if (session('success'))
        <div class="p-4 mx-6 mt-4 text-green-800 bg-green-100 border border-green-300 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 mx-6 mt-4 text-red-800 bg-red-100 border border-red-300 rounded shadow">
            <ul class="pl-5 list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Título --}}
    <div class="px-1 pt-2">
        <h2 class="px-6 mb-4 text-xl font-bold text-gray-900 pd-6">Listagem de Escolas</h2>

        {{-- Filtro e botão --}}
        <div class="px-6 pd-6">
            <div class="p-4 bg-white border-b border-gray-200 rounded-lg shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <form method="GET" action="{{ route('clients.schools', $client->id) }}">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nome ou CNPJ"
                            class="h-10 px-4 py-2 border rounded-lg shadow-sm w-[400px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </form>

                    <a href="{{ route('clients.schools.index', ['client' => $client->id]) }}"
                        class="flex items-center justify-center h-10 px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700">
                        Nova Escola
                    </a>
                </div>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="px-6 py-6">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <table class="min-w-full bg-white divide-y divide-gray-200 rounded shadow">
                    <thead class="text-gray-600 bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Nome</th>
                            <th class="px-4 py-3 text-center">CNPJ</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($schools as $school)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-4">{{ $school->name }}</td>
                                <td class="px-4 py-4 text-center">{{ $school->cnpj }}</td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('schools.edit', $school->uuid) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 transition rounded-full hover:text-white hover:bg-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    Nenhuma escola cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
