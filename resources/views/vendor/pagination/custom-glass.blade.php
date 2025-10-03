@if ($paginator->hasPages())
    <nav class="flex items-center space-x-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-white/40 bg-white/20 backdrop-blur-md rounded-full">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
                class="px-3 py-1 bg-white/30 text-white border border-white/40 rounded-full 
                       backdrop-blur-md shadow hover:bg-white/50 transition">‹</a>
        @endif

        {{-- Links --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1 text-white/50">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 bg-blue-500/60 text-white rounded-full shadow backdrop-blur-md">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" 
                            class="px-3 py-1 bg-white/30 text-white border border-white/40 rounded-full 
                                   backdrop-blur-md shadow hover:bg-white/50 transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
                class="px-3 py-1 bg-white/30 text-white border border-white/40 rounded-full 
                       backdrop-blur-md shadow hover:bg-white/50 transition">›</a>
        @else
            <span class="px-3 py-1 text-white/40 bg-white/20 backdrop-blur-md rounded-full">›</span>
        @endif
    </nav>
@endif
