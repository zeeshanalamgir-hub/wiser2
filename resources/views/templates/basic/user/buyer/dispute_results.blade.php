<div class="col-md-5 col-xl-4 order-1 order-md-2">
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Results
            @if ($disputedetail->status == 0)
                <a href="javascript:void(0);" data-toggle="modal" data-target="#adminModal"
                    class="adminBtn btn btn-sm bg-lsgreen border text-primary mx-2">@lang('SEND TO ADMIN')</a>
            @endif
        </div>


        <div class="card-body p-4">
            <p class="mb-2">Dispute amount:<span class="fs-6 fw-bold"> ${{ $type === 'job' ? $disputedetail->dispute_amount : $disputedetail->disputed_amount }}</span></p>
            @if(isset($settledDispute))
                <p class="mb-0">Seller:<span class="fs-6 fw-bold"> ${{ $settledDispute->seller_amount }}</span></p>
                <p class="mb-2">Buyer:<span class="fs-6 fw-bold"> ${{ $settledDispute->buyer_amount }}</span></p>
            @endif
            @if ($disputedetail->status == 0)
                <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('Not Settled')</span>

            @elseif($disputedetail->status == 1)
                <span class="bg-green text-white py-1 px-2 fs-14 rounded">@lang('Dispute Settled')</span>
                @if ($booking->status == 4)

                    <span class="text-danger ms-0 ms-sm-2 bg-danger text-white px-2 py-1 fs-12 rounded-1">Refund</span>

                @elseif ($booking->status == 3)

                    <span class="text-success ms-0 ms-sm-2 bg-green text-white px-2 py-1 fs-12 rounded-1">Paid</span>

                @endif
            @elseif($disputedetail->status == 2)
                <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('dispute submitted to admin')</span>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="modalRejectLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:350px">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h5 class="modal-title" id="modalRejectLabel">@lang('Send Dispute to Admin')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="background: inherit;border: inherit;font-size: 22px;border-bottom: 0px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="fs-6">@lang('Are you sure to send dispute to admin?')</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{route('user.buyer.dispute.offer.work.bid.sendtoadmin')}}">
                    @csrf
                    <input type="hidden" name="dispute_id" value="{{ $disputedetail->id }}">
                    <input type="hidden" name="confirm" value="approved">
                    <button type="submit" class="btn btn-sm btn-danger">@lang('Yes')</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">@lang('No')</button>
                </form>
            </div>
        </div>
    </div>
</div>
