<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Responsável') }}
        </h2>
    </x-slot>

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

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                <!-- Formulário de edição -->
                <form action="{{ route('guardians.update', $guardian->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" value="{{ old('name', $guardian->name) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf', $guardian->cpf) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $guardian->birth_date) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $guardian->email) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $guardian->phone) }}"
                            class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nova Foto</label>
                        <input type="file" name="photo" accept="image/*"
                            class="block w-full mt-1 text-sm text-gray-900">
                    </div>

                    <!-- Botões lado a lado -->
                    <div class="flex mt-4 space-x-2">
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            Salvar
                        </button>
                </form>

                        <form action="{{ route('guardians.destroy', $guardian->id) }}" method="POST"
                            onsubmit="return confirm('Tem certeza que deseja excluir este Responsável?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                                Excluir
                            </button>
                        </form>
                    </div>

                <!-- Exibição e botão de remover foto -->
                @if ($guardian->photo_path)
                    <div class="mt-6 mb-4">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Foto Atual</label>
                        <img src="{{ asset('storage/' . $guardian->photo_path) }}" alt="Foto do Responsável"
                            class="object-cover w-32 h-32 border border-gray-300 rounded">

                        <form action="{{ route('guardians.remove-photo', $guardian->id) }}" method="POST" class="mt-2">
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
