<div class="col-md-7 col-xl-8 mt-4 mt-md-0 order-2 order-md-1">
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Identifier </div>
        <div class="card-body">
            <div class="d-flex">
                @if(!is_null($booking->buyer->image))
                    <img src="{{ Storage::url($booking->buyer->image) }}"
                    alt="@lang('Profile Image')" class="rounded-circle me-2" width="45" height="45" style="object-fit:cover">
                @else
                    <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                        style="height:50px;width:54px;font-size:20px">
                        {{ strtoupper(substr($booking->buyer->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($$booking->buyer->lastname ?? 'A', 0, 1)) }}
                    </div>
                @endif
                <div class="w-100" style="padding-left: 10px">
                    <h6 class="mb-0 fw-bold">{{ $booking->buyer->fullname }} (Buyer) </h6>
                    <span class="fs-14">{{ $disputedetail->reason }}</span>
                </div>
            </div>

        </div>
    </div>
</div>
@include("templates.basic.user.buyer.dispute_results")
{{-- <div class="col-md-5 col-xl-4 order-1 order-md-2">
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Results </div>
        <div class="card-body p-4">
            <p class="mb-2">Dispute amount:<span class="fs-6 fw-bold"> ${{ $disputedetail->dispute_amount }}</span></p>
            @if(isset($settledDispute))
                <p class="mb-0">Seller:<span class="fs-6 fw-bold"> ${{ $settledDispute->seller_amount }}</span></p>
                <p class="mb-2">Buyer:<span class="fs-6 fw-bold"> ${{ $settledDispute->buyer_amount }}</span></p>
            @endif
            @if ($disputedetail->status == 0)
                <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('Not Settled')</span>
            @elseif($disputedetail->status == 1)
                <span class="bg-green text-white py-1 px-2 fs-14 rounded">@lang('Dispute Settled')</span>
            @elseif($disputedetail->status == 2)
                <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('dispute submitted to admin')</span>
            @endif
        </div>
    </div>
</div> --}}
