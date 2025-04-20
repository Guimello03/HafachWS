<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Lista de Alunos') }}
        </h2>
    </x-slot>
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
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="flex justify-end mb-4">
                    <a href="{{ route('students.create') }}"
                        class="inline-block px-4 py-2 font-semibold text-black bg-green-600 rounded shadow hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300">
                        + Novo Aluno




                    </a>
                    </a>

                </div>
                <table class="min-w-full bg-white divide-y divide-gray-200 rounded shadow">

                    <thead class="text-gray-700 bg-gray-100">

                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Nome</th>
                            <th class="px-4 py-2 text-left">Matrícula</th>
                            <th class="px-4 py-2 text-left">Nascimento</th>
                            <th class="px-4 py-2 text-center">Ações</th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        @foreach ($students as $index => $student)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-4 py-2">{{ $student->id }}</td>
                                <td class="px-4 py-2">{{ $student->name }}</td>
                                <td class="px-4 py-2">{{ $student->registration_number }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('students.edit', $student->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 transition rounded-full hover:text-white hover:bg-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 transition rounded-full hover:text-white hover:bg-red-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
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
