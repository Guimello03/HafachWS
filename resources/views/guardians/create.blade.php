<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Cadastrar Responsável') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto">
            <div class="p-6 bg-white rounded-lg shadow-md">

                {{-- Mensagens de Erro --}}
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="pl-5 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('guardians.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Foto com input embutido -->
                    <div class="flex flex-col items-center justify-center mb-6">
                        <label for="uploadPhoto" class="relative cursor-pointer group">
                            <div
                                class="w-32 h-32 overflow-hidden transition-all bg-gray-100 border-2 border-gray-300 rounded-full shadow-md group-hover:opacity-80">
                                <img id="photoPreview"
                                    src="https://via.placeholder.com/150"
                                    alt="Prévia da Foto"
                                    class="object-cover w-full h-full rounded-full"
                                    onerror="document.getElementById('semFotoText').classList.remove('hidden'); this.classList.add('opacity-0');" />
                                <div id="semFotoText"
                                    class="absolute inset-0 flex items-center justify-center text-sm text-gray-400">
                                    Sem Foto
                                </div>
                            </div>

                            <div
                                class="absolute inset-0 flex items-center justify-center transition-opacity bg-black rounded-full opacity-0 bg-opacity-30 group-hover:opacity-100">
                                <span class="text-xs text-white">Selecionar</span>
                            </div>

                            <input type="file" name="photo" id="uploadPhoto" accept="image/*" class="hidden"
                                onchange="previewPhoto(event)">
                        </label>
                    </div>

                    <!-- Nome -->
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nome</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>

                    <!-- CPF -->
                    <div class="mb-4">
                        <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900">CPF</label>
                        <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>

                    <!-- Nascimento -->
                    <div class="mb-4">
                        <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">Nascimento</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>

                    <!-- E-mail -->
                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>

                    <!-- Telefone -->
                    <div class="mb-6">
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Telefone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-center gap-4 mt-4">
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-6 py-2.5">
                            Salvar
                        </button>

                        <a href="{{ route('guardians.index') }}"
                            class="text-white bg-yellow-500 hover:bg-yellow-600 font-medium rounded-lg text-sm px-6 py-2.5">
                            Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para preview da foto -->
    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('semFotoText');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('opacity-0');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-admin-layout>
