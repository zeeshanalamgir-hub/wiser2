
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

                        <u class="text-secondary fw-bold">{{ $milestone['title'] ?? 'Project Payment'}} </u><br>
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
                <div class="col-lg-4 pt-3 pt-lg-0" style="display: flex;align-items:center;justify-content:end">
                    <ul class="nav float-lg-end">
                        @if($milestone['milestoneStatus'] == 2 )
                            <li class="mx-3">
                                <button
                                    class="mt-3 btn btn-sm btn-outline-success viewMileStoneRequestBtn"
                                    data-id="{{ $milestone['jobWorkDeliveryBidId'] }}"
                                    data-url="{{ route('user.buyer.job.work.milestone.data', $milestone['jobWorkDeliveryBidId']) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalViewRequest"
                                    title="View Request">
                                    View Request
                                </button>
                            </li>
                            <li class="">
                                <button id="fixedTypeDispute" class="mt-3 btn btn-sm btn-outline-danger ps-2"  data-amount="{{ $milestone['amount'] }}" data-url="{{ route('user.work.dispute', ['encbuyerCounterOfferDataId' => $milestone['encbuyerCounterOfferDataId']]) }}"
                                 title="Dispute">
                                    Dispute
                                </button>
                            </li>
                        @endif
                        @if($milestone['milestoneStatus'] == 6 )
                            <li class="">
                                <button
                                    class="mt-3 btn btn-sm btn-outline-success viewMileStoneRequestBtn"
                                    data-id="{{ $milestone['jobWorkDeliveryBidId'] }}"
                                    data-url="{{ route('user.buyer.job.work.milestone.data', $milestone['jobWorkDeliveryBidId']) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalViewRequest"
                                    title="View Request">
                                    View Request
                                </button>
                            </li>
                            <li class="">
                                <a href="{{ route('user.buyer.dispute.view', ['type'=>'job','id' => $milestone['disputeId']]) }}" class="mt-3 btn btn-sm btn-outline-danger ms-3" title="View Dispute">View Dispute</a>
                            </li>
                        @endif
                        @if($milestone['requestType'] == "seller" && $milestone['milestoneStatus'] == 8)
                            <a title="Accept Milestone" data-target="#reqmsacceptModal" data-work-id="{{ @$milestone['encbuyerCounterOfferDataId'] }}" href="javascript:void(0);" class="reqmsacceptBtn btn btn-sm btn-outline-success"><i class="fa fa-check"></i> </a>
                            <a title="Reject Milestone" data-target="#reqmsrejectModal" data-work-id="{{ @$milestone['encbuyerCounterOfferDataId'] }}" href="javascript:void(0);" class="reqmsrejectBtn btn btn-sm btn-outline-danger ms-2"><i class="fa fa-times"></i> </a>
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


<div class="modal fade" id="modalPayNow" tabindex="-1" aria-labelledby="modalPayNowLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
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
                        <input type="file" id="attachment" class="form-control" name="attachment" accept=".zip,.jpg,.png,.pdf">
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

<div class="modal fade" id="reqmsacceptModal" tabindex="-1" aria-labelledby="reqmsacceptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header bg-green text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="reqmsacceptModalLabel">@lang('Accept Milestone')</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user.buyer.accept.extra.work.milestone.bid') }}">
                @csrf
            <div class="modal-body">
                <p class="fs-6">Are you sure to process this request?</p>
                <input type="hidden" id="new_milestone_accept_request_id" name="new_milestone_accept_request_id">
            </div>
            <div class="modal-footer clear-both">
                <button type="submit" class="btn btn-sm btn-success" id="acceptExtraMileStone">Yes</button>
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
            <form method="POST" action="{{ route('user.buyer.reject.extra.work.milestone.bid') }}">
            @csrf
            <div class="modal-body">
                <p class="fs-6">Are you sure to process this request?</p>
                <input type="hidden" id="new_milestone_reject_request_id" name="new_milestone_reject_request_id">
            </div>
            <div class="modal-footer clear-both">
                <button type="submit" class="btn btn-sm btn-danger" id="rejectExtraMileStone">Yes</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#fixedTypeDispute').on('click', function() {
        var amount = $(this).data('amount');
        $('#disputeAmount').text('$' + amount);
        $('#modalDispute').modal('show');
    });
</script>
