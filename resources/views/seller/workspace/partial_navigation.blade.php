<div class="row">
    <div class="col-12 ps-3">
        <nav class="navbar navbar-expand-sm bg-light fw-bold">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a style="display:flex;align-items:center;gap:5px"
                                class="nav-link px-3 {{ $activeList === 'overview' ? 'active text-green' : '' }}"
                                href="{{ route('user.seller.workspace.overview', ['encryptedId' => $encJobId]) }}">
                                <i class="fa-solid fa-file-lines"></i>Contract Overview</a>
                        </li>

                        {{--  <li class="nav-item border-start">
                            <a href="#" class="nav-link px-3 {{ $activeList === 'milestone' ? 'active text-green' : '' }}"
                                data-url="
                    {{ $job_type == 'hourly'
                        ? route('user.seller.workspace.timesheet', ['encryptedId' => $encJobId])
                        : ($job_type == 'byproject'
                            ? route('user.seller.workspace.byproject', ['encUserBidingId' => $encJobId])
                            : route('user.seller.workspace.bymilestone', ['encUserBidingId' => $encJobId])) }}">
                            <i class="fa-solid fa-clock"></i>
                                @if ($job_type == 'bymilestone' || $job_type == 'byproject')
                                    Milestone
                                @else
                                    Timesheet
                                @endif
                            </a>
                        </li>  --}}
                        <li class="nav-item border-start">
                            <a href="
                                {{ $job_type == 'hourly'
                                ? route('user.seller.workspace.timesheet', ['encryptedId' => $encJobId])
                                : ($job_type == 'byproject'
                                    ? route('user.seller.workspace.byproject', ['encUserBidingId' => $encJobId])
                                    : route('user.seller.workspace.bymilestone', ['encUserBidingId' => $encJobId])) }}"
                                class="nav-link px-3 {{ ($activeList === 'milestone' || $activeList == 'project') ? 'active text-green' : '' }}">
                                <i class="fa-solid fa-clock"></i>
                                @if ($job_type == 'bymilestone' || $job_type == 'byproject')
                                    Milestone
                                @else
                                    Timesheet
                                @endif
                            </a>
                        </li>

                        <li class="nav-item border-start">
                            {{-- <a class="nav-link  {{ $activeList === 'message' ? 'active text-green' : '' }}"
                                data-url="" href="{{ route('chatify') }}" target="_blank">Messages</a> --}}
                            <a class="nav-link px-3 {{ $activeList === 'message' ? 'active text-green' : '' }}"
                                href="{{ route('messages.seller', ['encryptedId' => $encJobId]) }}"><i
                                    class="fa-solid fa-comments"></i> Messages</a>
                        </li>
                        <li class="nav-item border-start">
                            <a class="nav-link  px-3 {{ $activeList === 'invoice' ? 'active text-green' : '' }}"
                                href="{{ route('user.seller.workspace.invoice', ['encryptedId' => $encJobId]) }}">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                Invoice</a>
                        </li>
                        <li class="nav-item border-start">
                            <a class="nav-link  px-3 {{ $activeList === 'rating' ? 'active text-green' : '' }}"
                                href="{{ route('user.seller.workspace.rating', ['encryptedId' => $encJobId]) }}"><i
                                    class="fa-solid fa-star"></i>Rating</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
@section('script')
    <script>
        const routes = {
            deleteWorkIntervals: "{{ route('user.seller.deleteIntervals') }}",
            downloadUrl: "{{ route('download-auto-tracker') }}",
        };

        const csrfTokenAjax = "{{ csrf_token() }}";
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
