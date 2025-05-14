<div wire:poll.10000ms class="space-y-2">
    @forelse($devices as $device)
        <div class="flex items-center p-3 text-sm transition bg-white rounded-md shadow-sm hover:shadow-md">
            
            <!-- Ícone status -->
            <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                @if(optional($device->status)->is_online)
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                @else
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                @endif
            </div>

            <!-- Informações do dispositivo -->
            <div class="flex flex-col flex-1 ml-3">
                <span class="font-medium text-gray-700">{{ $device->name }}</span>
                <span class="text-xs text-gray-400">{{ $device->serial_number }}</span>
            </div>

            <!-- Status e tempo -->
            <div class="flex flex-col items-end text-xs">
                @if(optional($device->status)->is_online)
                    <span class="font-bold text-green-500">Online</span>
                @else
                    <span class="font-bold text-red-500">Offline</span>
                @endif
                <span class="text-gray-400">
                    {{ optional($device->status)->last_seen?->diffForHumans() ?? 'Nunca' }}
                </span>
            </div>
        </div>
    @empty
        <div class="py-4 text-center text-gray-400">
            Nenhum equipamento encontrado.
        </div>
    @endforelse
</div>
