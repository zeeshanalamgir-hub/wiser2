@if (isset($breadcrumbs))
    <div class="row">
        <div class="col-lg-12 pb-lg-0">
            <p class="mb-0 text-bc fs-14">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($loop->last)
                     <span id="breadcrumb-title">
                        {!! $breadcrumb['title'] !!} <!-- Last breadcrumb without link -->
                     </span>
                    @else
                        <a class="text-3gray" href="{{ $breadcrumb['url'] }}">
                            {!! $breadcrumb['title'] !!} <!-- Breadcrumb with link -->
                        </a>
                        <i class="fa-solid fa-chevron-right fs-12"></i>
                    @endif
                @endforeach
            </p>
        </div>
    </div>
@endif
