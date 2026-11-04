
<div class="col-12"> <!-- Payment section start-->
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Project Payments
            {{-- @unless ($isCompleted)
            <button class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                    data-bs-target="#modalRequestMilestone" title="Request Milestone">
                Request Milestone
            </button>
            @endunless --}}
        </div>

            <div class="card-body p-4">
                @foreach ($buyerCounterOffersData as $data)
                <div class="row pb-2 border-bottom">
                    <div class="col-lg-8">
                        <u class="text-secondary fw-bold">{{ $data['title'] }} </u><br>
                        <ul class="nav">
                            <li> Amount: <b>${{ $data['amount'] }}</b> <span class="ms-2"></li>
                            <li> Due Date: <b> {{ \Carbon\Carbon::parse($data['due_date'])->format('d-M-Y') }} </b></li>
                            {{-- <li> <span class="ms-sm-2"> Submission Date: <b> {{ \Carbon\Carbon::parse($data['due_date'])->format('d-M-Y') }} </b> </span></li> --}}
                            <li> <span class="ms-sm-2"> Revision: <b> {{ $data['revision'] }}</b><span></li>
                            <li><span
                                    class="bg-warning bg-opacity-50 px-2 py-1 fs-12 rounded-1 ms-0 ms-sm-2">{{ $data['milestoneStatusText'] }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4 pt-3 pt-lg-0">
                        <ul class="nav float-lg-end">
                            @if ($data['milestoneStatus'] == 2)
                                <li class="pt-3">
                                    <button class="btn btn-sm btn-outline-success viewRequestBtn"
                                        data-id="{{ $data['encbuyerCounterOfferDataId'] }}"
                                        data-url="{{ route('user.buyer.job.work.data', '__id__') }}"
                                        data-bs-toggle="modal" data-bs-target="#modalViewRequest" title="View Request">
                                        View Request
                                    </button>
                                </li>
                                <li class="ms-2 pt-3">
                                    <button id="fixedTypeDispute" class="btn btn-sm btn-outline-danger ps-2"
                                        data-url="{{ route('user.work.dispute', ['encbuyerCounterOfferDataId' => $data['encbuyerCounterOfferDataId']]) }}"
                                        data-bs-toggle="modal" data-bs-target="#modalDispute" title="Dispute"
                                        data-dispute-amount="{{ $data['amount'] }}">
                                        Dispute
                                    </button>
                                </li>
                            @endif
                            @if ($data['milestoneStatus'] == 6)
                                <a href="{{ route('user.buyer.dispute.view', ['type'=>'job','id' => $data['disputeId']]) }}"
                                    class="btn btn-sm btn-outline-danger ms-3 mt-3" title="View Dispute">View Dispute</a>
                            @endif
                            @if ($data['requestType'] == "seller" && $data['milestoneStatus'] == 8)
                            <li class="pt-3">
                                <a title="Accept Milestone" data-target="#reqmsacceptModal"
                                    data-work-id="{{ @$data['encbuyerCounterOfferDataId'] }}" href="javascript:void(0);"
                                    class="reqmsacceptBtn btn btn-sm btn-outline-success"><i class="fa fa-check"></i>
                                </a>
                            </li>
                            <li class="ms-2 pt-3">
                                <a title="Reject Milestone" data-target="#reqmsrejectModal"
                                    data-work-id="{{ @$data['encbuyerCounterOfferDataId'] }}"
                                    href="javascript:void(0);" class="reqmsrejectBtn btn btn-sm btn-outline-danger"><i
                                        class="fa fa-times"></i> </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>

    </div>
</div>


@include('workspace.dispute_modal')
@include('workspace.requestmilestone', ['encJobId' => $encJobId,'encBidId'=>$encBidId])

<div class="modal fade" id="modalRequestPayment" tabindex="-1" aria-labelledby="modalRequestPaymentLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="max-width:350px">
            <form method="POST"
                action="{{ route('user.seller.job.work.submit', $data['encbuyerCounterOfferDataId']) }}"
                enctype="multipart/form-data">
                <div class="modal-header bg-green text-white py-2 px-4">
                    <h1 class="modal-title fs-5" id="modalRequestPaymentLabel">Request Payment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Laravel Form Submission -->

                    @csrf <!-- Laravel CSRF token -->

                    <!-- Work File Upload -->
                    <div class="form-group mb-3">
                        <label for="formFileLg" class="form-label font-weight-bold">Upload Work File</label>
                        <input class="form-control fs-14" name="file" id="formFileLg" type="file" required>
                        <small class="form-text text-muted">Supported files: zip, max size: 25 MB</small>
                    </div>

                    <!-- Delivery Details Text Area -->
                    <div class="form-group mb-3">
                        <textarea rows="6" class="form-control" name="details" placeholder="Describe your delivery details..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer clear-both">
                    <button type="submit" class="btn btn-sm btn-success w-100">Submit Request</button>
                </div>
            </form> <!-- Closing the form here -->
        </div>
    </div>
</div>

<div class="modal fade" id="modalViewRequest" tabindex="-1" aria-labelledby="modalViewRequestLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light  py-2 px-4">
                <h1 class="modal-title fs-5 text-green" id="modalViewRequestLabel">Payment Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be loaded here -->
                <div id="modalRequestContent">
                    <!-- The AJAX response will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Reject Work Modal -->
<div class="modal fade" id="rejectWorkModal" tabindex="-1" aria-labelledby="rejectWorkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger py-2 px-4">
                <h1 class="modal-title fs-5 text-white" id="rejectWorkModalLabel">Payment Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Reason Field -->
                    <div class="form-group">
                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea id="reason" class="form-control" name="reason" rows="3" required></textarea>
                    </div>

                    <!-- Attachment Field (Optional) -->
                    <div class="form-group mt-3">
                        <label for="attachment" class="form-label">Upload Attachment</label>
                        <input type="file" id="attachment" class="form-control" name="attachment"
                            accept=".zip,.jpg,.png,.pdf">
                    </div>

                    <!-- Hidden Work Bid ID -->
                    <input type="hidden" id="reject_work_id" name="reject_work_id">
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="submitReject">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalPayNow" tabindex="-1" aria-labelledby="modalPayNowLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="modalPayNowLabel">Pay Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6">Are you sure to process this request?</p>
                <input type="hidden" id="work_id" name="work_id">
            </div>
            <div class="modal-footer clear-both">
                <button type="button" class="btn btn-sm btn-success" id="submitAccept">Yes</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reqmsacceptModal" tabindex="-1" aria-labelledby="reqmsacceptModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="reqmsacceptModalLabel">@lang('Accept Milestone')</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user.buyer.accept.extra.work.milestone.bid') }}">
                @csrf
                <div class="modal-body">
                    <p class="fs-6">Are you sure to process this request?</p>
                    <input type="hidden" id="new_milestone_accept_request_id"
                        name="new_milestone_accept_request_id">
                </div>
                <div class="modal-footer clear-both">
                    <button type="submit" class="btn btn-sm btn-success" id="submitMileStoneAccept">Yes</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="reqmsrejectModal" tabindex="-1" aria-labelledby="reqmsrejectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="reqmsrejectModalLabel">Decline Milestone</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user.buyer.reject.extra.work.milestone.bid') }}">
                @csrf
                <div class="modal-body">
                    <p class="fs-6">Are you sure to process this request?</p>
                    <input type="hidden" id="new_milestone_reject_request_id"
                        name="new_milestone_reject_request_id">
                </div>
                <div class="modal-footer clear-both">
                    <button type="submit" class="btn btn-sm btn-danger" id="submitMileStoneReject">Yes</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('script')
    <script>
        const routes = {
            acceptWorkBid: "{{ route('user.buyer.accept.work.bid') }}",
            rejectWorkBid: "{{ route('user.buyer.reject.work.bid') }}"
        };

        const csrfTokenAjax = "{{ csrf_token() }}";
    </script>
@endsection
