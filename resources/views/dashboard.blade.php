<x-admin-layout>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <!-- Alunos -->
        <div class="flex items-center p-4 transition bg-white rounded-lg shadow-sm hover:shadow-md">
            <div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M5.121 17.804A9 9 0 1 1 12 21a9 9 0 0 1-6.879-3.196z"/>
                    </svg>
                    <span class="text-xl font-semibold">{{ $totalStudents }}</span>
                </div>
                <p class="text-sm text-gray-500">Alunos</p>
                <p class="text-xs text-gray-400">Fotos: {{ $studentsWithPhoto }} / Sem: {{ $studentsWithoutPhoto }}</p>
            </div>
            <canvas id="studentsChart" class="w-12 h-12"></canvas>
        </div>

        <!-- Responsáveis -->
        <div class="flex items-center p-4 transition bg-white rounded-lg shadow-sm hover:shadow-md">
            <div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12z"/>
                    </svg>
                    <span class="text-xl font-semibold">{{ $totalGuardians }}</span>
                </div>
                <p class="text-sm text-gray-500">Responsáveis</p>
                <p class="text-xs text-gray-400">Fotos: {{ $guardiansWithPhoto }} / Sem: {{ $guardiansWithoutPhoto }}</p>
            </div>
            <canvas id="guardiansChart" class="w-12 h-12"></canvas>
        </div>

        <!-- Comandos Pendentes por Grupo -->
        <div class="col-span-2 p-4 transition bg-white rounded-lg shadow-sm hover:shadow-md">
            <h4 class="mb-3 text-sm font-semibold text-gray-700">Comandos Pendentes por Grupo</h4>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left text-gray-500">Grupo</th>
                        <th class="text-right text-gray-500">Pendentes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups as $group)
                        <tr class="border-b last:border-0">
                            <td>{{ $group->name }}</td>
                            <td class="font-bold text-right text-red-500">{{ $group->commands_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-400">Nenhum comando pendente</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Segunda Linha -->
    <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
        <!-- Últimos Acessos -->
        <div class="p-4 transition bg-white rounded-lg shadow-sm hover:shadow-md">
            <h4 class="mb-3 text-sm font-semibold text-gray-700">Últimos Acessos</h4>
            <div class="pr-1 space-y-3 overflow-y-auto max-h-72">
                @livewire('device-event-monitor')
            </div>
        </div>

        <!-- Status dos Equipamentos -->
        <div class="p-4 transition bg-white rounded-lg shadow-sm hover:shadow-md">
            <h4 class="mb-3 text-sm font-semibold text-gray-700">Status dos Equipamentos</h4>
            @livewire('device-status-monitor')
        </div>
    </div>
</x-admin-layout>
