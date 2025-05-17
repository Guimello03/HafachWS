<div wire:poll.5000ms class="space-y-2">
    @forelse($events as $event)
        <div class="flex items-center p-2 transition bg-white rounded-md shadow-sm hover:shadow-md">

            <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                @if ($event['type'] === 'Student')
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347M4.26 10.147A50.636 50.636 0 0 0 1.602 9.334 59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84 50.717 50.717 0 0 1-2.658.814M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                @elseif($event['type'] === 'Guardian')
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                @elseif($event['type'] === 'Functionary')
                    <svg class="w-4 h-4 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-2-6h4v2H8v-2z" />
                    </svg>
                @endif
            </div>
            <!-- Detalhes -->
            <div class="flex flex-col ml-3 text-sm">
                <div class="flex items-center space-x-2">
                    <span class="font-medium text-gray-700">{{ $event['name'] }}</span>

                    @if($event['type'] === 'Student')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-600">Aluno</span>
                    @elseif($event['type'] === 'Guardian')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600">Responsável</span>
                    @elseif($event['type'] === 'Functionary')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-purple-50 text-purple-600">Funcionário</span>
                    @endif
                </div>
                <span class="text-xs text-gray-400">{{ $event['date'] }}</span>
            </div>

            <!-- Direção -->
            <div class="ml-auto">
                @if ($event['direction'] === 'in')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">
                        Entrada
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-red-100 text-red-700">
                        Saída
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div class="py-4 text-center text-gray-400">
            Nenhum acesso recente.
        </div>
    @endforelse
</div>
