<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Aluno') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                <!-- Validação de erros -->
                @if ($errors->any())
                    <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded shadow">
                        <ul class="pl-5 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulário de edição -->
                <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" value="{{ old('name', $student->name) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Matrícula</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number', $student->registration_number) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nova Foto</label>
                        <input type="file" name="photo" accept="image/*"
                            class="block w-full mt-1 text-sm text-gray-900">
                    </div>

                    <div>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            Salvar
                        </button>
                    </div>
                </form>

                <!-- Exibição e botão de remover foto -->
                @if ($student->photo_path)
                    <div class="mt-6 mb-4">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Foto Atual</label>
                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto do aluno"
                            class="object-cover w-32 h-32 border border-gray-300 rounded">

                        <form action="{{ route('students.remove-photo', $student->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                Remover Foto
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
