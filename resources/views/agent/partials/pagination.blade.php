@if($tickets->hasPages())
    <div class="pagination">
    
        @if ($tickets->onFirstPage())
            <span class="page-link disabled">← Previous</span>
        @else
            <a href="{{ $tickets->previousPageUrl() }}" class="page-link">← Previous</a>
        @endif

     
        @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
            @if ($page == $tickets->currentPage())
                <span class="page-link active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
            @endif
        @endforeach

        @if ($tickets->hasMorePages())
            <a href="{{ $tickets->nextPageUrl() }}" class="page-link">Next →</a>
        @else
            <span class="page-link disabled">Next →</span>
        @endif
    </div>

    <div class="pagination-info">
        Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} tickets
    </div>
@endif
