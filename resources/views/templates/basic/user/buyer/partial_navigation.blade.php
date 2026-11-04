<div class="row">
    <div class="col-12 ps-3">
        <nav class="navbar navbar-expand-sm bg-light fw-bold">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="col-12 d-flex justify-content-between">
                    <!--
                                <button class="btn btn-success  bg-dgreen btn-md" onclick="location.href='../hiring/workSpaceCotractOverview.php';"> <i class="fa-solid fa-file-lines"></i> Contract Overview </button>
                                <button class="btn btn-light bg-lgreen2 border btn-md" onclick="location.href='../hiring/workSpaceTimesheet.php';"> <i class="fa-solid fa-clock"></i> Timesheet </button>
                                <button class="btn btn-light bg-lgreen2 border btn-md" onclick="location.href='../hiring/workSpaceMessages.php';"> <i class="fa-solid fa-comments"></i> Messages </button>
                                <button class="btn btn-light bg-lgreen2 border btn-md" onclick="location.href='../hiring/workSpaceInvoice.php';"> <i class="fa-solid fa-file-invoice-dollar"></i> Invoice </button>
                                <button class="btn btn-light bg-lgreen2 border btn-md" onclick="location.href='../hiring/workSpaceRating.php';"> <i class="fa-solid fa-star"></i> Rating </button>
                                -->
                    <nav id="navbar" class="navbar navbar-expand-sm bg-light p-0">
                        <div class="container">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li class="nav-item ">
                                        <a class="nav-link px-3 {{ $activeList === 'overview' ? 'active text-green' : '' }}"
                                            aria-current="page"
                                            href="{{ route('user.workspace.overview', ['encryptedId' => $encJobId]) }}"
                                            data-url="{{ route('user.workspace.overview', ['encryptedId' => $encJobId]) }}">
                                            <i class="fa-solid fa-file-lines"></i> Contract Overview</a>
                                    </li>
                                    <li class="nav-item px-3 border-start">
                                        @php
                                            $url = '#';

                                            if ($job_type == 'hourly') {
                                                $url = route('user.workspace.timesheet', ['encryptedId' => $encJobId]);
                                            } elseif ($job_type == 'byproject') {
                                                $url = route('user.buyer.workspace.byproject', ['encUserBidingId' => $encJobId]);
                                            } elseif ($job_type == 'bymilestone') {
                                                $url = route('user.buyer.workspace.bymilestone', ['encUserBidingId' => $encJobId]);
                                            }
                                        @endphp

                                        <a class="nav-link {{ $activeList === 'milestone' ? 'active text-green' : '' }}"
                                        href="{{ $url }}"
                                        data-url="{{ $url }}">
                                            
                                            <i class="fa-solid fa-clock"></i>

                                            @if ($job_type == 'bymilestone' || $job_type == 'byproject')
                                                Milestone
                                            @else
                                                Timesheet
                                            @endif
                                        </a>
                                    </li>
                                    <li class="nav-item px-3 border-start">
                                        <a class="nav-link {{ $activeList === 'message' ? 'active text-green' : '' }}"
                                            aria-current="page"
                                            href="{{ route('messages.buyer', ['encryptedId' => $encJobId]) }}">
                                            <i class="fa-solid fa-comments"></i> Messages</a>
                                    </li>
                                    <li class="nav-item px-3 border-start">
                                        <a class="nav-link {{ $activeList === 'invoice' ? 'active text-green' : '' }}"
                                            aria-current="page"
                                            href="{{ route('user.workspace.invoice', ['encryptedId' => $encJobId]) }}"><i
                                                class="fa-solid fa-file-invoice-dollar"></i> Invoice</a>
                                    </li>
                                    <li class="nav-item ps-3 border-start">
                                        <a class="nav-link {{ $activeList === 'rating' ? 'active text-green' : '' }}"
                                            aria-current="page"
                                            href="{{ route('user.workspace.rating', ['encryptedId' => $encJobId]) }}"><i
                                                class="fa-solid fa-star"></i> Rating</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </nav>
    </div>
</div>
@section('script')
    <script>
        const routes = {
            acceptWorkBid: "{{ route('user.buyer.accept.work.bid') }}",
            rejectWorkBid: "{{ route('user.buyer.reject.work.bid') }}",
            disputeWorkIntervals: "{{ route('user.buyer.intervals.dispute') }}"
        };

        const csrfTokenAjax = "{{ csrf_token() }}";
    </script>
@endsection
