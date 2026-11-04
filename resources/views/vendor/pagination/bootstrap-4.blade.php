@if ($paginator->hasPages())
    <div class="row pt-md-2 mt-3">
        <div class="col-7 col-md-3 fs-12">
            {{-- Showing entries text --}}
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
        </div>
        <div class="col-5 col-md-9 px-0">
            <div class="float-end me-3 pe-md-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <button class="btn btn-xs btn-outline-secondary" disabled>
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-xs btn-outline-secondary">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <button class="btn btn-xs btn-outline-secondary mx-1" disabled>{{ $element }}</button>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <button class="btn btn-xs btn-green mx-1">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}" class="btn btn-xs btn-outline-secondary mx-1">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-xs btn-outline-secondary">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @else
                    <button class="btn btn-xs btn-outline-secondary" disabled>
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
@endif
