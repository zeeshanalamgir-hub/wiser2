<div class="col-12"> <!-- Payment section start-->
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Project Payments
            @unless ($isCompleted)
            <button class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal" data-bs-target="#modalRequestMilestone" title="Request Milestone"> Request Milestone </button>
            @endunless
        </div>

        <div class="card-body">
            {{-- @dd($buyerCounterOffersData); --}}
            @foreach ($buyerCounterOffersData as $data)

            <div class="row pb-2 border-bottom">
                <div class="col-lg-8">
                    <u class="text-secondary fw-bold">{{ $data['title'] }} </u><br>
                    <ul class="nav">
                        <li> Amount: <b>${{ $data['amount'] }}</b> <span class="ms-2"></li>
                        <li> Due Date: <b> {{ \Carbon\Carbon::parse($data['due_date'])->format('d-M-Y')  }} </b></span> </li>

                        <li> <span class="ms-sm-2"> Revision: <b> {{ $data['revision'] }}</b></span> </li>
                        @if ($data['milestone_status'] == 6)
                            <li><span class="bg-danger text-white px-2 py-1 fs-12 rounded-1 ms-0 ms-sm-2">{{ $data['milestoneStatusText'] }}</span></li>
                        @else
                            <li><span class="bg-warning bg-opacity-50 px-2 py-1 fs-12 rounded-1 ms-0 ms-sm-2">{{ $data['milestoneStatusText'] }}</span></li>
                        @endif

                    </ul>
                </div>
                <div class="col-lg-4 pt-3 pt-lg-0">
                    <ul class="nav float-lg-end">
                        <li class="pt-3">
                            @if($data['milestoneStatus'] == 0)
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRequestPayment" title="Request Payment">
                                    Request Payment
                                </button>
                            @elseif ($data['milestoneStatus'] == 1  )
                                <button class="btn btn-sm btn-outline-success viewRequestBtn" data-id="{{ $data['buyerCounterOfferDataId'] }}" data-bs-toggle="modal"
                                data-url="{{ route('user.seller.job.work.data', '__id__') }}" data-bs-target="#modalViewRequest" title="View Request">
                                    View Request
                                </button>
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRequestPayment" title="Request Payment">
                                    Request Payment
                                </button>
                            @elseif ($data['milestoneStatus'] == 2  )
                                <button class="btn btn-sm btn-outline-success viewRequestBtn" data-id="{{ $data['buyerCounterOfferDataId'] }}" data-bs-toggle="modal"
                                data-bs-target="#modalViewRequest" title="View Request" data-url="{{ route('user.seller.job.work.data', '__id__') }}">
                                    View Request
                                </button>
                            @endif
                            @if($data['milestoneStatus'] == 6 )
                                <a href="{{ route('user.seller.dispute.view', ['type'=>'job', 'id' => $data['disputeId']]) }}" class="btn btn-sm btn-outline-danger ms-3" title="View Dispute">View Dispute</a>
                            @endif
                            @if($data['requestType'] == "buyer"  && $data['milestoneStatus'] == 8 )
                                <a title="Accept Milestone" data-target="#reqmsacceptModal" data-work-id="{{ @$data['encId'] }}" href="javascript:void(0);" class="reqmsacceptBtn btn btn-sm btn-outline-succes"><i class="fa fa-check"></i> </a>
                                <a title="Reject Milestone" data-target="#reqmsrejectModal" data-work-id="{{ @$data['encId'] }}" href="javascript:void(0);" class="reqmsrejectBtn btn btn-sm btn-outline-danger"><i class="fa fa-times"></i> </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@include('seller.workspace.requestmilestone', ['encJobId' => $encJobId])

<div class="modal fade" id="modalRequestPayment" tabindex="-1" aria-labelledby="modalRequestPaymentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('user.seller.job.work.submit', $data['buyerCounterOfferDataId']) }}" enctype="multipart/form-data">
            <div class="modal-header bg-light  py-2 px-4">
                <h1 class="modal-title fs-5 text-green" id="modalRequestPaymentLabel">Request Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Laravel Form Submission -->

                    @csrf <!-- Laravel CSRF token -->

                    <!-- Work File Upload -->
                    <div class="form-group mb-3">
                        <label for="formFileLg" class="form-label font-weight-bold">Upload Work File</label>
                        <input class="form-control fs-14" name="file" accept=".zip" id="formFileLg" type="file" required>
                        <small class="form-text text-muted">Supported files: zip, max size: 25 MB</small>
                    </div>

                    <!-- Delivery Details Text Area -->
                    <div class="form-group mb-3">
                        <textarea rows="6" class="form-control" name="details" placeholder="Describe your delivery details..." required></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-success bg-green border-0">Submit Request</button>
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
<div class="modal fade" id="reqmsacceptModal" tabindex="-1" aria-labelledby="reqmsacceptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header bg-green text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="reqmsacceptModalLabel">@lang('Accept Milestone')</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user.job.accept.extra.work.milestone.bid') }}">
                @csrf
            <div class="modal-body">
                <p class="fs-6">Are you sure to process this request?2</p>
                <input type="hidden" id="milestone_accept_work_id" name="milestone_accept_work_id">
            </div>
            <div class="modal-footer clear-both">
                <button type="submit" class="btn btn-sm btn-success">Yes</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="reqmsrejectModal" tabindex="-1" aria-labelledby="reqmsrejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="reqmsrejectModalLabel">Decline Milestone</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user.job.reject.extra.work.milestone.bid') }}">
            @csrf
            <div class="modal-body">
                <p class="fs-6">Are you sure to process this request?</p>
                <input type="hidden" id="milestone_reject_work_id" name="milestone_reject_work_id">
            </div>
            <div class="modal-footer clear-both">
                <button type="submit" class="btn btn-sm btn-danger" >Yes</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
            </form>
        </div>
    </div>
</div>

