@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-6">
        <div class="inline-flex overflow-hidden border border-gray-300 rounded-lg shadow-sm">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-500 bg-white cursor-default">Anterior</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-2 text-sm text-gray-700 bg-white hover:bg-gray-100">
                    Anterior
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-gray-700 bg-white">{{ $element }}</span>
                @endif

                {{-- Array of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 text-sm font-bold text-white bg-blue-600">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" 
                               class="px-3 py-2 text-sm text-gray-700 bg-white hover:bg-gray-100">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-2 text-sm text-gray-700 bg-white hover:bg-gray-100">
                    Próximo
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-500 bg-white cursor-default">Próximo</span>
            @endif

        </div>
    </nav>
@endif
