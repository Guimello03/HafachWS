<x-admin-layout>
   
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

        <!-- Alunos -->
        <div class="flex items-center p-4 transition bg-white rounded-lg shadow-sm hover:shadow-md">
            <div>
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 ' . $colorClass . '">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347M4.26 10.147A50.636 50.636 0 0 0 1.602 9.334 59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84 50.717 50.717 0 0 1-2.658.814M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    <span class="text-xl font-semibold">{{ $totalStudents }}</span>
                </div>
                <p class="text-sm text-gray-500">Alunos</p>
                <p class="text-xs text-gray-400">Com foto: {{ $studentsWithPhoto }} / Sem foto:
                    {{ $studentsWithoutPhoto }}</p>
            </div>
            <canvas id="studentsChart" class="w-12 h-12"></canvas>
        </div>

        <!-- Responsáveis -->
        <div class="flex items-center p-4 transition bg-white rounded-lg shadow-sm hover:shadow-md">
            <div>
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 ' . $colorClass . '">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span class="text-xl font-semibold">{{ $totalGuardians }}</span>
                </div>
                <p class="text-sm text-gray-500">Responsáveis</p>
                <p class="text-xs text-gray-400"> Com foto: {{ $guardiansWithPhoto }} / Sem foto:
                    {{ $guardiansWithoutPhoto }}</p>
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
