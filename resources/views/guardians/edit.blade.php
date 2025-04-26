<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Editar Responsável') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto">
            <div class="p-6 bg-white rounded-lg shadow-md">

                {{-- Foto de perfil --}}
                <div class="flex flex-col items-center justify-center mb-6">
                    <label for="uploadPhoto" class="relative cursor-pointer group">
                        <div
                            class="w-32 h-32 overflow-hidden transition-all border-2 border-gray-300 rounded-full shadow-md group-hover:opacity-70">
                            @if ($guardian->photo_path)
                                <img id="photoPreview" src="{{ asset('storage/' . $guardian->photo_path) }}"
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

                    {{-- Remover foto --}}
                    @if ($guardian->photo_path)
                        <form action="{{ route('guardians.remove-photo', $guardian) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-4 py-1.5 mt-2">
                                Remover Foto
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Form de edição --}}
                <form action="{{ route('guardians.update', $guardian->uuid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="file" name="photo" id="uploadPhoto" accept="image/*" class="hidden"
                        onchange="previewPhoto(event)">

                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nome</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $guardian->name) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="mb-4">
                        <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900">CPF</label>
                        <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $guardian->cpf) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="mb-4">
                        <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">Data de Nascimento</label>
                        <input type="date" name="birth_date" id="birth_date"
                            value="{{ old('birth_date', $guardian->birth_date) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $guardian->email) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="mb-6">
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Telefone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $guardian->phone) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    {{-- Botões --}}
                    <div class="flex justify-center gap-4 mt-4">
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-6 py-2.5">
                            Salvar
                        </button>

                        <a href="{{ route('guardians.index') }}"
                            class="text-white bg-yellow-500 hover:bg-yellow-600 font-medium rounded-lg text-sm px-6 py-2.5">
                            Voltar
                        </a>
                </form>

                <form action="{{ route('guardians.destroy', $guardian) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('Tem certeza que deseja excluir este responsável?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-6 py-2.5">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Script preview --}}
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
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-admin-layout>
