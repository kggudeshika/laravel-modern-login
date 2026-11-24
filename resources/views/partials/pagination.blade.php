{{-- Custom pagination links. Used with: $posts->links('partials.pagination') --}}
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Pagination">
        <p class="muted">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>

        <ul>
            @if ($paginator->onFirstPage())
                <li><span class="page is-disabled" aria-disabled="true">&lsaquo;</span></li>
            @else
                <li><a class="page" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">&lsaquo;</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="page is-disabled">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="page is-current" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a class="page" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a class="page" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">&rsaquo;</a></li>
            @else
                <li><span class="page is-disabled" aria-disabled="true">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
