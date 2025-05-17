<x-admin-layout>
    <x-breadcrumb :items="$breadcrumbs" />

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Relatório de Frequência de Funcionários') }}
        </h2>
    </x-slot>

    <div class="px-6 py-4">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-4">

                <!-- Período -->
                <div>
                    <label for="date-range" class="block mb-1 text-sm font-medium text-gray-700">Período</label>
                    <input type="text" id="date-range"
                           class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm js-date-range focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Selecione o período" readonly>
                </div>

                <!-- Direção -->
                <div>
                    <label for="direction" class="block mb-1 text-sm font-medium text-gray-700">Direção</label>
                    <select id="direction"
                            class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Entrada">Entrada</option>
                        <option value="Saída">Saída</option>
                    </select>
                </div>

                <!-- Funcionário -->
                <div>
                    <label for="functionary_id" class="block mb-1 text-sm font-medium text-gray-700">Funcionário</label>
                    <select id="functionary_id" name="functionary_id" placeholder="Digite para buscar..." autocomplete="off"
                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                    </select>
                </div>

                <!-- Botão -->
                <div>
                    <button onclick="buscarRelatorio()"
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
                    <th class="px-4 py-3 text-left">Data</th>
                    <th class="px-4 py-3 text-left">Hora</th>
                    <th class="px-4 py-3 text-left">Funcionário</th>
                    <th class="px-4 py-3 text-left">Direção</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <tr id="placeholder-row">
                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">Nenhum dado carregado...</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            let flatInstance = null;

            document.addEventListener("DOMContentLoaded", () => {
                flatInstance = flatpickr(".js-date-range", {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    locale: "pt"
                });

                new TomSelect("#functionary_id", {
                    valueField: 'uuid',
                    labelField: 'name',
                    searchField: 'name',
                    maxOptions: 20,
                    openOnFocus: false,
                    allowEmptyOption: true,
                    placeholder: 'Digite para buscar...',
                    load: function (query, callback) {
                        if (!query.length) return callback();
                        fetch(`/reports/person-search?term=${encodeURIComponent(query)}&type=functionary`)
                            .then(res => res.json())
                            .then(data => callback(data))
                            .catch(() => callback());
                    }
                });
            });

            function buscarRelatorio() {
                const direction = document.getElementById('direction').value;
                const functionaryId = document.getElementById('functionary_id')?.value;
                const schoolId = '{{ session('school_id') }}';

                if (!direction) {
                    alert('Selecione a direção.');
                    return;
                }

                let start_date = '';
                let end_date = '';

                if (!flatInstance || flatInstance.selectedDates.length === 0) {
                    alert('Selecione uma data no período.');
                    return;
                }

                start_date = flatInstance.formatDate(flatInstance.selectedDates[0], "Y-m-d");
                end_date = flatInstance.selectedDates.length >= 2
                    ? flatInstance.formatDate(flatInstance.selectedDates[1], "Y-m-d")
                    : start_date;

                const params = new URLSearchParams({
                    direction,
                    start_date,
                    end_date,
                    school_id: schoolId
                });

                if (functionaryId) {
                    params.append('functionary_id', functionaryId);
                }

                fetch(`/reports/functionary-attendance/data?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Erro: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    const tbody = document.querySelector('#result-table tbody');
                    tbody.innerHTML = '';

                    if (!data.length) {
                        tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Nenhum registro encontrado.</td></tr>`;
                    } else {
                        data.forEach(item => {
                            const dateObj = new Date(item.date);
                            const dataFormatada = dateObj.toLocaleDateString('pt-BR');
                            const horaFormatada = dateObj.toLocaleTimeString('pt-BR', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            const direcao = item.direction === 'in' ? 'Entrada' : 'Saída';

                            tbody.innerHTML += `
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">${dataFormatada}</td>
                                    <td class="px-4 py-3">${horaFormatada}</td>
                                    <td class="px-4 py-3">${item.person?.name ?? '---'}</td>
                                    <td class="px-4 py-3">${direcao}</td>
                                </tr>`;
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro ao buscar dados.');
                });
            }
        </script>
    @endpush
</x-admin-layout>
