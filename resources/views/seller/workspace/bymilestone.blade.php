<div class="col-12"> <!-- Payment section start-->
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Payments
            @unless ($isCompleted)
            <button class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal" data-bs-target="#modalRequestMilestone" title="Request Milestone"> Request Milestone </button>
            @endunless
        </div>
        <div class="card-body p-4">
            @foreach($data['milestones'] as $milestone)
            <div class="row pb-2 border-bottom">
                <div class="col-lg-8">

                        <u class="text-secondary fw-bold">{{ $milestone['title'] ?? "Project Payment" }} </u><br>
                        @php
                            $date = new DateTime( $milestone['due_date']);
                            $dueDateFormatted = $date->format('d M, Y');
                        @endphp
                        <ul class="nav">
                            <li> Amount: <b>${{ $milestone['amount'] }}</b> <span class="ms-2"></li>
                            <li> Due Date: <b> {{ $dueDateFormatted }} </b></span> </li>
                            @if($milestone['revision'] > 0)
                                <li> <span class="ms-sm-2"> Revision: <b> {{ $milestone['revision'] }}</b></span> </li>
                            @endif

                            <li><span class="bg-warning bg-opacity-50 px-2 py-1 fs-12 rounded-1 ms-0 ms-sm-2">{{ $milestone['milestoneStatusText'] }}</span></li>
                        </ul>

                </div>
                <div class="col-lg-4 pt-3 pt-lg-0">
                    <ul class="nav float-lg-end">

                        @if($milestone['milestoneStatus'] == 0)
                            <li class="mx-2">
                                <button class="mt-3 btn btn-sm btn-outline-success requestPaymentBtn" data-bs-toggle="modal" data-bs-target="#modalRequestPayment" data-id="{{ $milestone['id'] }}" title="Request Payment">
                                    Request Payment
                                </button>
                            </li>
                        @elseif ($milestone['milestoneStatus'] == 2)
                            <li class="">
                                <button class="mt-3 btn btn-sm btn-outline-success viewMileStoneRequestBtn"  data-url="{{ route('user.seller.job.work.milestone.data', $milestone['jobWorkDeliveryBidId'] ) }}"  data-id="{{ $milestone['jobWorkDeliveryBidId'] }}"  data-bs-toggle="modal" data-bs-target="#modalViewRequest" title="View Request">
                                    View Request
                                </button>
                            </li>
                        @elseif ($milestone['milestoneStatus'] == 1)
                            <li class="">
                                <button class="mt-3 btn btn-sm btn-outline-success viewMileStoneRequestBtn"  data-url="{{ route('user.seller.job.work.milestone.data', $milestone['jobWorkDeliveryBidId']) }}" data-id="{{ $milestone['jobWorkDeliveryBidId'] }}"  data-bs-toggle="modal" data-bs-target="#modalViewRequest" title="View Request">
                                    View Request
                                </button>
                            </li>
                            <li class="mx-2">
                                <button class="mt-3 btn btn-sm btn-outline-success requestPaymentBtn" data-bs-toggle="modal" data-bs-target="#modalRequestPayment" data-id="{{ $milestone['id'] }}" title="Request Payment">
                                    Request Payment
                                </button>
                            </li>
                        @endif

                        @if($milestone['milestoneStatus'] == 6 )
                            <li class="">
                                <button class="mt-3 btn btn-sm btn-outline-success viewMileStoneRequestBtn"  data-id="{{ $milestone['jobWorkDeliveryBidId'] }}" data-id="{{ $milestone['id'] }}" data-bs-toggle="modal" data-bs-target="#modalViewRequest" title="View Request">
                                    View Request
                                </button>
                            </li>
                            <li class="">
                                <a href="{{ url('user/seller/dispute/job/' . $milestone['disputeId']) }}" class="mt-3 btn btn-sm btn-outline-danger ms-3" title="View Dispute">View Dispute</a>
                            </li>
                        @endif
                        @if($milestone['requestType'] == "buyer"  && $milestone['milestoneStatus'] == 8 )
                            <a title="Accept Milestone" data-target="#reqmsacceptModal" data-work-id="{{ @$milestone['encId'] }}" href="javascript:void(0);" class="reqmsacceptBtn btn btn-sm btn-outline-success mt-3"><i class="fa fa-check"></i> </a>
                            <a title="Reject Milestone" data-target="#reqmsrejectModal" data-work-id="{{ @$milestone['encId'] }}" href="javascript:void(0);" class="reqmsrejectBtn btn btn-sm btn-outline-danger mt-3 ms-2"><i class="fa fa-times"></i> </a>
                        @endif
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
            <form method="POST" action="{{ route('user.seller.job.work.milestone.submit') }}" enctype="multipart/form-data">
                @csrf
            <input type="hidden" name="work_id" value="">

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
                        <input class="form-control fs-14" name="file" id="formFileLg" type="file" required
                               accept=".zip"> <!-- Ensure only .zip files can be uploaded -->
                        <small class="form-text text-muted">Supported file: ZIP, max size: 25 MB</small>
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
                <p class="fs-6">Are you sure to process this request?</p>
                <input type="hidden" id="milestone_accept_work_id" name="milestone_accept_work_id">
            </div>
            <div class="modal-footer clear-both">
                <button type="submit" class="btn btn-sm btn-success" >Yes</button>
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
                <button type="submit" class="btn btn-sm btn-danger">Yes</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
            </form>
        </div>
    </div>
</div>

