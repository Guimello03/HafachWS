<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Editar Aluno') }}
        </h2>
    </x-slot>

    <div class="py-6">
       
        <div class="flex justify-center gap-6 ">
            <div class="p-6 bg-white rounded-lg shadow-md lg:w-5/12">
                <!-- Foto de perfil -->
                <div class="flex flex-col items-center justify-center mb-6">
                    <label for="uploadPhoto" class="relative cursor-pointer group">
                        <div
                            class="w-32 h-32 overflow-hidden transition-all border-2 border-gray-300 rounded-full shadow-md group-hover:opacity-70">
                            @if ($student->photo_path)
                                <img id="photoPreview" src="{{ asset('storage/' . $student->photo_path) }}"
                                    class="object-cover w-full h-full">
                            @else
                                <img id="photoPreview" src="https://via.placeholder.com/150"
                                    class="hidden object-cover w-full h-full">
                                <div id="noPhotoPlaceholder"
                                    class="flex items-center justify-center w-32 h-32 text-sm text-gray-400 bg-gray-100 border-2 border-gray-300 rounded-full shadow-sm">
                                    Sem Foto
                                </div>
                            @endif
                        </div>
                        <div
                            class="absolute inset-0 flex items-center justify-center transition-opacity bg-gray-200 bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100">
                            <span class="text-sm text-gray-700">Alterar Foto</span>
                        </div>
                    </label>

                    @if ($student->photo_path)
                        <form action="{{ route('students.remove-photo', $student) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-4 py-1.5">
                                Remover Foto
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Formulário principal -->
                <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="file" name="photo_path" id="uploadPhoto" accept="image/*" class="hidden"
                        onchange="previewPhoto(event)">

                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nome</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <div class="mb-4">
                        <label for="registration_number"
                            class="block mb-2 text-sm font-medium text-gray-900">Matrícula</label>
                        <input type="text" name="registration_number" id="registration_number"
                            value="{{ old('registration_number', $student->registration_number) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <div class="mb-6">
                        <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">Data de Nascimento</label>
                        <input type="date" name="birth_date" id="birth_date"
                            value="{{ old('birth_date', \Carbon\Carbon::parse($student->birth_date)->format('Y-m-d')) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    @if ($student->guardian_id == null)
                        <!-- Select para escolher responsável -->
                        <div class="mb-6">
                            <label for="guardian_id" class="block mb-2 text-sm font-medium text-gray-900">Responsável
                                Legal</label>
                            <select id="guardian_id" name="guardian_id" placeholder="Digite para buscar..."
                            class="w-full text-sm focus:outline-none">
                                @foreach ($guardians as $guardian)
                                    <option value="{{ $guardian->uuid }}"
                                        {{ old('guardian_id', $student->guardian_id) == $guardian->id ? 'selected' : '' }}>
                                        {{ $guardian->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                new TomSelect('#guardian_id', {
                                    placeholder: "Digite para buscar...",
                                    allowEmptyOption: true,
                                    sortField: {
                                        field: "text",
                                        direction: "asc"
                                    }
                                });
                            });
                        </script>
                    @endif

                    <div class="flex flex-wrap justify-end gap-4 mt-8">
                        <!-- Botão Salvar -->
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-6 py-2.5">
                            Salvar
                        </button>

                </form>
                <!-- Botão Excluir-->

                <form action="{{ route('students.destroy', $student) }}" method="POST"
                    onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5">
                        Excluir
                    </button>
                </form>
            </div>
        </div>


        @if ($student->guardian_id != null)
            <!-- Card do Responsável -->
            <div class="hidden w-1/4 p-6 pt-4  bg-white border  rounded-lg shadow-md lg:block h-[205px]">
                <span class="block mb-1 text-xs font-medium text-gray-400 uppercase">Responsável Legal</span>

                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">{{ $student->guardian->name }}</h2>

                        <div class="grid grid-cols-2 gap-2 mt-4 text-sm text-gray-600">
                            <div>
                                <span class="block font-semibold">Nascimento:</span>
                                <span
                                    class="text-gray-900">{{ \Carbon\Carbon::parse($student->guardian->birth_date)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="block font-semibold">Telefone:</span>
                                <span class="text-gray-900">{{ $student->guardian->phone ?? '-' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block font-semibold">Email:</span>
                                <span class="text-gray-900">{{ $student->guardian->email ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('students.remove-guardian', $student->uuid) }}" method="POST"
                        class="ml-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-red-600 transition hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                        </button>
                    </form>
                </div>
            </div>
        @endif



    </div>
    </div>
    </div>

    <!-- Script de preview de foto -->
    <script>
       function previewPhoto(event) {
    const input = event.target;
    const file = input.files[0];

    console.log('📷 Preview carregado:', file);

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('noPhotoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    } else {
        console.warn('⚠️ Nenhum arquivo selecionado no preview.');
    }
}
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('uploadPhoto');
            const file = fileInput?.files[0];
            console.log('📤 Submetendo imagem:', file);
        });
        </script>
</x-admin-layout>
