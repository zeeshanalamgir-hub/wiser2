<div class="col-md-7 col-xl-8 mt-2 mt-md-0 order-2 order-md-1">
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Identifier </div>
        <div class="card-body p-3">
            <div class="d-flex">
                @if($booking->buyer->image)
                    <img src="{{ Storage::url($booking->buyer->image) }}"
                    alt="@lang('Profile Image')" class="rounded-circle me-2" width="45" height="45" style="object-fit:cover">
                @else
                    <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                        style="height:45px;width:48px;font-size:20px">
                        {{ strtoupper(substr($booking->buyer->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($booking->buyer->lastname ?? 'A', 0, 1)) }}
                    </div>
                @endif
                <div class="w-100" style="padding-left:10px">
                    <h6 class="mb-0 fw-bold">{{ $booking->buyer->username }} (Buyer) </h6>
                    <span class="fs-14">{{ $disputedetail->reason }}</span>
                </div>
            </div>

        </div>
    </div>
</div>
@include("templates.basic.user.seller.dispute_results")
