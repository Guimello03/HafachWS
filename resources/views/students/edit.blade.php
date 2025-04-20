<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Aluno') }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded shadow">
            <ul class="pl-5 list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex flex-col items-start gap-8 md:flex-row">
                    <!-- COLUNA 1 – Formulário -->
                    <div class="w-full md:w-3/4">
                        <form action="{{ route('students.update', $student->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <input type="text" name="name" value="{{ old('name', $student->name) }}"
                                    class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                            </div>
                            <input type="file" name="photo" id="uploadPhoto" accept="image/*" class="hidden"
                                onchange="previewPhoto(event)">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Matrícula</label>
                                <input type="text" name="registration_number"
                                    value="{{ old('registration_number', $student->registration_number) }}"
                                    class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                                <input type="date" name="birth_date"
                                    value="{{ old('birth_date', $student->birth_date) }}"
                                    class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                            </div>

                            <div class="flex flex-wrap gap-2 mt-4">


                                <button type="submit"
                                    class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                    Salvar
                                </button>
                                <a href="{{ route('students.index') }}"
                                    class="px-4 py-2 text-white bg-yellow-600 rounded hover:bg-yellow-700">
                                    Voltar
                                </a>
                        </form>

                        <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                            onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>

                <!-- COLUNA 2 – Foto -->
                <div class="flex flex-col items-center justify-center w-full md:w-1/4">
                    @if ($student->photo_path)
                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto do aluno"
                            class="object-cover w-40 h-40 mb-2 border border-gray-300">

                        <form action="{{ route('students.remove-photo', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                Remover Foto
                            </button>
                        </form>
                    @else
                        <!-- Placeholder: Sem foto -->
                        <div id="noPhotoPlaceholder"
                            class="flex items-center justify-center w-32 h-32 mb-2 font-semibold text-center text-gray-500 bg-gray-100 border border-gray-300 rounded">
                            Sem Foto
                        </div>
                        <img id="photoPreview" src="#" alt="Prévia da Foto"
                            class="hidden object-cover w-32 h-32 mb-2 border border-gray-300 rounded">
                        <button type="button" onclick="document.getElementById('uploadPhoto').click()"
                            class="px-3 py-1 mt-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                            Adicionar Foto
                        </button>
                    @endif


                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function previewPhoto(event) {
            const input = event.target;
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    const placeholder = document.getElementById('noPhotoPlaceholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
