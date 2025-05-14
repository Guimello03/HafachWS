<div wire:poll.5000ms class="space-y-2">
    @forelse($events as $event)
        <div class="flex items-center p-2 transition bg-white rounded-md shadow-sm hover:shadow-md">

            <!-- Ícone por tipo -->
            <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                @if($event['type'] === 'Student')
                    <svg class="w-4 h-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16z" />
                    </svg>
                @elseif($event['type'] === 'Guardian')
                    <svg class="w-4 h-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-9V7H9v2H7v2h2v2h2v-2h2v-2h-2z" clip-rule="evenodd" />
                    </svg>
                @elseif($event['type'] === 'Functionary')
                    <svg class="w-4 h-4 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-2-6h4v2H8v-2z" />
                    </svg>
                @endif
            </div>

            <!-- Detalhes -->
            <div class="flex flex-col ml-3 text-sm">
                <span class="font-medium text-gray-700">{{ $event['name'] }}</span>
                <span class="text-xs text-gray-400">{{ $event['date'] }}</span>
            </div>

            <!-- Direção (in/out) badge discreto ao lado -->
            <div class="ml-auto">
                @if($event['direction'] === 'in')
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
