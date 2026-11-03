@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="pg disabled" aria-disabled="true"><i class="fa-solid fa-chevron-left"></i></span>
        @else
            <a class="pg" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"><i class="fa-solid fa-chevron-left"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg disabled">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pg" href="{{ $url }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pg" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"><i class="fa-solid fa-chevron-right"></i></a>
        @else
            <span class="pg disabled" aria-disabled="true"><i class="fa-solid fa-chevron-right"></i></span>
        @endif
    </nav>
@endif
