<div class="row">
    <div class="col-12  ps-3 mt-3">
        <nav class="navbar navbar-expand-sm bg-light fw-bold">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link custom-ajax-link {{ $activeList === 'overview' ? 'active text-green' : '' }}"
                                data-url="{{ route('user.workspace.overview', ['encryptedId' => $encJobId]) }}"
                                href="#">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-ajax-link {{ $activeList === 'milestone' ? 'active text-green' : '' }}"
                                data-url="
                            {{ $job_type == 'hourly'
                                ? route('user.workspace.timesheet', ['encryptedId' => $encJobId])
                                : ($job_type == 'byproject'
                                    ? route('user.buyer.workspace.byproject', ['encUserBidingId' => $encJobId])
                                    : ($job_type == 'bymilestone'
                                        ? route('user.buyer.workspace.bymilestone', ['encUserBidingId' => $encJobId])
                                        : route('user.seller.workspace.default', ['encryptedId' => $encJobId]))) }}"
                                href="#">
                                @if ($job_type == 'bymilestone' || $job_type == 'byproject')
                                Milestone
                                @else
                                Timesheet
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-ajax-link {{ $activeList === 'message' ? 'active text-green' : '' }}"
                                data-url="{{ route('messages.buyer',['encryptedId' => $encJobId]) }}"
                                href="#">Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  custom-ajax-link {{ $activeList === 'invoice' ? 'active text-green' : '' }}"
                                data-url="{{ route('user.workspace.invoice', ['encryptedId' => $encJobId]) }}"
                                href="#">Invoice</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  custom-ajax-link {{ $activeList === 'rating' ? 'active text-green' : '' }}"
                                data-url="{{ route('user.workspace.rating', ['encryptedId' => $encJobId]) }}"
                                href="#">Rating</a>
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
    acceptWorkBid: "{{ route('user.buyer.accept.work.bid') }}",
    rejectWorkBid: "{{ route('user.buyer.reject.work.bid') }}",
    disputeWorkIntervals: "{{ route('user.buyer.intervals.dispute') }}"
};

const csrfTokenAjax = "{{ csrf_token() }}";
</script>
@endsection
