<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Lista de Responsáveis') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="mb-4">
                    <a href="{{ route('guardians.create') }}"
   class="inline-block px-4 py-2 font-semibold text-black bg-green-600 rounded shadow hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300">
    + Novo Responsável
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

</a>

</a>

                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Nome</th>
                            <th class="px-4 py-2 text-left">cpf</th>
                            <th class="px-4 py-2 text-left">Nascimento</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Telefone</th>
                            <th class="px-4 py-2 text-left">Ações</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guardians as $guardian)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $guardian->id }}</td>
                            <td class="px-4 py-2">{{ $guardian->name }}</td>
                            <td class="px-4 py-2">{{ $guardian->cpf}}</td>
                            <td class="px-4 py-2">{{ $guardian->birth_date }}</td>
                            <td class="px-4 py-2">{{ $guardian->email }}</td>
                            <td class="px-4 py-2">{{ $guardian->phone }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('guardians.edit', $guardian->id) }}"
                                   class="inline-block px-3 py-1 font-semibold text-black bg-yellow-400 rounded hover:bg-yellow-500">
                                    Editar
                                </a>
                        </tr>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

