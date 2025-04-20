<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Cadastrar Responsável') }}
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

                <form action="{{ route('guardians.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" id="name"  class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">CPF</label>
                        <input type="text" name="cpf" id="cpf" oninput="formatCPF(this)" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                        <input type="date" name="birth_date" id="birth_date" oninput="formatDate(this)" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="tel" name="phone" id="phone" oninput="formatPhone(this)" class="block w-full mt-1 border-gray-300 rounded shadow-sm">
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
    <script>
        function formatCPF(input) {
            input.value = input.value
                .replace(/\D/g, '')   // Remove tudo que não for dígito
                .substring(0, 11) // limita a 11 dígitos
                .replace(/(\d{3})(\d)/, '$1.$2')    // Coloca ponto depois dos 3 primeiros dígitos
                .replace(/(\d{3})(\d)/, '$1.$2')    // Segundo ponto
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Traço
        }

        function formatPhone(input) {
            input.value = input.value
                .replace(/\D/g, '')
                .replace(/^(\d{2})(\d)/g, '($1) $2')
                .replace(/(\d{5})(\d{4})$/, '$1-$2');
        }

        function formatDate(input) {
            input.value = input.value
                //.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3') // Formato YYYY-MM-DD
                .replace(/(\d{2})-(\d{2})-(\d{4})/, '$1-$2-$3'); // Formato YYYY-MM-DD
        }
        </script>
</x-app-layout>


