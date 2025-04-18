<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Alunos') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <a href="{{ route('students.create') }}"
   class="inline-block bg-green-600 hover:bg-green-700 text-black font-semibold py-2 px-4 rounded shadow focus:outline-none focus:ring focus:ring-green-300">
    + Novo Aluno
    @if (session('success'))
    <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded shadow">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded shadow mt-4">
        <ul class="list-disc pl-5">
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
                            <th class="text-left px-4 py-2">ID</th>
                            <th class="text-left px-4 py-2">Nome</th>
                            <th class="text-left px-4 py-2">Matrícula</th>
                            <th class="text-left px-4 py-2">Nascimento</th>
                            <th class="text-left px-4 py-2">Ações</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $student->id }}</td>
                            <td class="px-4 py-2">{{ $student->name }}</td>
                            <td class="px-4 py-2">{{ $student->registration_number }}</td>
                            <td class="px-4 py-2">{{ $student->birth_date }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('students.edit', $student->id) }}"
                                   class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-1 px-3 rounded">
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

