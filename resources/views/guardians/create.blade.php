<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Cadastrar Responsável') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                <form action="{{ route('guardians.store') }}" method="POST" enctype="multipart/form-data">>
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">CPF</label>
                        <input type="text" name="cpf" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                        <input type="date" name="birth_date" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="tel" name="phone" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    <div class="flex items-center gap-6 mb-4">
                        <!-- Imagem em branco (placeholder) -->
                        <div>
                            <div class="flex items-center justify-center w-32 h-32 text-gray-500 bg-gray-200 rounded">
                                Sem foto
                            </div>
                        </div>
                        <!-- Campo de upload -->
                        <div class="flex flex-col gap-2">
                            <input type="file" name="photo" class="block w-full text-sm text-gray-900" accept="image/*">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="px-4 py-2 text-black bg-blue-600 rounded hover:bg-blue-700">
                            Salvar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
