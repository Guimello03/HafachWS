<x-admin-layout>
    @foreach ($breadcrumbs as $index => $item)
        <li class="inline-flex items-center">
            {!! \App\Helpers\BreadcrumbHelper::getIcon($item['label']) !!}
            @if (!empty($item['url']))
                <a href="{{ $item['url'] }}"
                    class="ml-1 text-sm font-medium hover:text-gray-900">{{ $item['label'] }}</a>
            @else
                <span class="ml-1 text-sm font-medium text-gray-400">{{ $item['label'] }}</span>
            @endif

            {{-- Adiciona o separador só se NÃO for o último item --}}
            @if (!$loop->last)
                <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            @endif
        </li>
    @endforeach

    <x-slot name="header">

        <h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
            {{ __('Cadastrar Aluno') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto">
            <div class="p-6 bg-white rounded-lg shadow-md">

                {{-- Erros de validação --}}
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="pl-5 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Foto com input embutido -->
                    <div class="flex flex-col items-center justify-center mb-6">
                        <label for="uploadPhoto" class="relative cursor-pointer group">
                            <div
                                class="w-32 h-32 overflow-hidden transition-all bg-gray-100 border-2 border-gray-300 rounded-full shadow-md group-hover:opacity-80">
                                <img id="photoPreview" src="https://via.placeholder.com/150" alt="Prévia da Foto"
                                    class="object-cover w-full h-full rounded-full"
                                    onerror="document.getElementById('semFotoText').classList.remove('hidden'); this.classList.add('opacity-0');" />
                                <div id="semFotoText"
                                    class="absolute inset-0 flex items-center justify-center text-sm text-gray-400">
                                    Sem Foto
                                </div>
                            </div>

                            <!-- Overlay de hover -->
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

                    <!-- Matrícula -->
                    <div class="mb-4">
                        <label for="registration_number"
                            class="block mb-2 text-sm font-medium text-gray-900">Matrícula</label>
                        <input type="text" name="registration_number" id="registration_number"
                            value="{{ old('registration_number') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>

                    <!-- Data de Nascimento -->
                    <div class="mb-6">
                        <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">Data de
                            Nascimento</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-center gap-4 mt-4">
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-6 py-2.5">
                            Salvar
                        </button>

                        <a href="{{ route('students.index') }}"
                            class="text-white bg-yellow-500 hover:bg-yellow-600 font-medium rounded-lg text-sm px-6 py-2.5">
                            Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('semFotoText');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('opacity-0');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-admin-layout>
