<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Usuários sem Foto') }}
        </h2>
    </x-slot>

    <div class="px-6 py-4">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Tipo de usuário -->
                <div>
                    <label for="user_type" class="block mb-1 text-sm font-medium text-gray-700">Tipo de Usuário</label>
                    <select id="user_type"
                        class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione</option>
                        <option value="Aluno">Aluno</option>
                        <option value="Funcionário">Funcionário</option>
                        <option value="Responsável">Responsável</option>
                    </select>
                </div>

                <!-- Botão Buscar -->
                <div>
                    <button onclick="buscarUsuariosSemFoto()"
                        class="w-full h-10 px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="px-6 pb-10">
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
            <table id="result-table" class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="text-gray-600 bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Nome</th>
                        <th class="px-4 py-3 text-left">Identificação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr id="placeholder-row">
                        <td colspan="2" class="px-4 py-4 text-center text-gray-500">Nenhum dado carregado...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            function buscarUsuariosSemFoto() {
                const tipo = document.getElementById('user_type').value;

                if (!tipo) {
                    alert('Selecione o tipo de usuário.');
                    return;
                }

                fetch(`/reports/users-without-photo?user_type=${tipo}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) throw new Error(`Erro: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    const tbody = document.querySelector('#result-table tbody');
                    tbody.innerHTML = '';

                    if (!data.length) {
                        tbody.innerHTML = `<tr><td colspan="2" class="px-4 py-4 text-center text-gray-500">Nenhum registro encontrado.</td></tr>`;
                        return;
                    }

                    data.forEach(user => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">${user.name}</td>
                                <td class="px-4 py-3">${user.identificacao ?? '---'}</td>
                            </tr>`;
                    });
                })
                .catch(error => {
                    console.error(error);
                    alert('Erro ao buscar dados.');
                });
            }
        </script>
    @endpush
</x-admin-layout>
