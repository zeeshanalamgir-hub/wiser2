@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Work Delivery Approval Confirmation')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.work.delivery.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="service">
                        <p>@lang('Are you sure to approved this work delivery')</p>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="disputeModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Are you sure to dispute this booking')</h2>
                    </div>
                    <form method="POST"
                        action="{{ route('user.work.dispute', ['encbuyerCounterOfferDataId' => encrypt(123)]) }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="service">
                        <p>@lang('Are you sure to cancel this Proposal')</p>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="dispute" placeholder="Why dispute ...." required></textarea>
                            <small>@lang('Minimum 50 Characters Required')</small>
                        </div>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Dispute Report')</h2>
                    </div>
                    <div class="dispute-detail">
                    </div>
                    <div class="form-group">
                    </div>
                    <div class="form-group text-center mb-0">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="row pb-lg-4 mt-4">
        <div class="col-lg-4 pb-4 pb-lg-0 "> <!-- this drop down for small screens -->

            <x-buyer-service-mob-nav :booking="$booking" />

            <!--Breadcrumbs-->
            <p class="mb-0 text-bc mt-1 fs-14">
                <a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
                    <i class="fa-solid fa-house fs-14"></i>
                </a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a class="text-3gray" href="{{ route('user.service-booked') }}">Service Booking</a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                Details
            </p>
            <!--Breadcrumbs End-->

        </div>
        <x-buyer-service-nav :booking="$booking" activeTab="details" />
    </div>
    @php
        $serviceDetails = $booking?->service?->serviceDetail;
        $seller = optional($serviceDetails->user);
    @endphp
    <!-- Card start-->

    @if($booking->status == 10)
        <div class="alert alert-danger" role="alert">
            Your order has been cancelled due to the reason of <strong>{{$booking->decline_reason ?? 'N/A'}}</strong>
        </div>
    @endif
  <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2;">
    <div class="w-100">
        <p class="float-end mb-0">
            <a class="links-c" href="{{ route('user', ['id' => $booking->seller_id]) }}">
                <i class="fa-regular fa-comment-dots fs-5"></i>
            </a>
            Chat with <span class="text-green">{{ $seller->username ?? 'Seller' }}</span>
        </p>
        <h5 class="text-dgreen2 mb-2">Service Details</h5>
    </div>

    <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-bold rounded mb-2">
        {{ $booking->service->title ?? '-' }}<br>
        <span class="fw-normal fs-14 text-secondary">
            {!! $booking->service->description ?? '-' !!}
        </span>
    </div>

    <div class="w-100 p-2 border-bottom fs-14">Service Plan
            <span class="float-end fw-bold">{{ $booking?->serviceDetails?->title ?? '-' }}</span>
        </div>
    <div class="w-100 p-2 border-bottom fs-14">Service Cost
        <span class="float-end">${{ number_format($booking->service_price, 2) }}</span>
    </div>
    <div class="w-100 p-2 border-bottom fs-14">Quantity
        <span class="float-end">{{ $booking->qty }}</span>
    </div>

    <div class="w-100 p-2 border-bottom fs-14">SubTotal
        <span class="float-end">${{ number_format($booking->service_price * $booking->qty, 2) }}</span>
    </div>

    @forelse($addOns as $addon)
        <div class="w-100 p-2 border-bottom fs-14">{{ $addon->title }}
            <span class="float-end">${{ number_format($addon->amount, 2) }}</span>
        </div>
    @empty
    @endforelse

    @if($booking->otherprice > 0)
    <div class="w-100 p-2 border-bottom fs-14">Total Extra Services Cost
        <span class="float-end">${{ number_format($booking->otherprice ?? 0, 2) }}</span>
    </div>
    @endif

    @if($booking?->discount > 0)
    <div class="w-100 p-2 border-bottom fs-14">Discount
        <span class="float-end">${{ number_format($booking?->discount,2) ?? 0 }}</span>
    </div>
    @endif
    
    <div class="w-100 p-2 border-bottom fs-14">Platform Fee ({{ $servicePlatformFeePercentage ?? 0 }}%)
        <span class="float-end">${{ number_format($booking->buyer_service_fee,2) ?? 0 }}</span>
    </div>
    
    <div class="w-100 p-2 border-bottom fs-14">Order Number
        <span class="float-end">{{ $booking->order_number ?? '--' }}</span>
    </div>
    <div class="w-100 p-2 border-bottom fs-14">Seller
        <span class="float-end">
            <a href="{{ route('profile', [slug($booking->seller->username), encrypt($booking->seller_id)]) }}">
                <span>@</span>{{ __($booking->seller->fullname) }}
            </a>
        </span>
    </div>
    <div class="w-100 p-2 border-bottom fs-14">Delivery Days
        <span class="float-end">{{ $booking?->service_days ?? 0 }} Days</span>
    </div>

    <div class="w-100 p-2 border-bottom fs-14">Revisions
        <span class="float-end">{{ $booking?->service_revisions ?? 0 }}</span>
    </div>

    <div class="w-100 p-2 border-bottom fs-14">Working Status
        <span class="float-end">
            @if(workingStatusText($booking->working_status) == 'waiting start')
                <button class="py-0 btn btn-sm btn-secondary border-0 fs-12">Waiting Start</button>
            @elseif(workingStatusText($booking->working_status) == 'in progress')
                <button class="py-0 btn btn-sm btn-success bg-dgreen2 fs-12 border-0 ms-2">In Progress</button>
            @elseif(workingStatusText($booking->working_status) == 'delivered')
                <button class="py-0 btn btn-sm btn-success bg-green fs-12 border-0 ms-2">Delivered</button>
            @elseif(workingStatusText($booking->working_status) == 'completed')
                <button class="py-0 btn btn-sm btn-success bg-green fs-12 border-0 ms-2">Completed</button>
            @elseif(workingStatusText($booking->working_status) == 'disputed')
                <button class="py-0 btn btn-sm btn-danger border-0 fs-12 ms-2">Disputed</button>
            @elseif(workingStatusText($booking->working_status) == 'cancel')
                <button class="py-0 btn btn-sm btn-danger border-0 fs-12 ms-2">Cancel</button>
            @else
                <button class="py-0 btn btn-sm btn-light border-0 fs-12">{{ ucwords(workingStatusText($booking->working_status)) }}</button>
            @endif
        </span>
    </div>
    
    <div class="w-100 p-2 border-bottom fs-14">Status
        <span class="float-end">
            @if(statusText($booking->status) == 'waiting acceptance')
                <button class="py-0 btn btn-sm btn-secondary border-0 fs-12">Waiting Acceptance</button>
            @elseif(statusText($booking->status) == 'accepted')
                <button class="py-0 btn btn-sm btn-success bg-green border-0 fs-12">Accepted</button>
            @elseif(statusText($booking->status) == 'cancel')
                <button class="py-0 btn btn-sm btn-danger border-0 fs-12">Cancel</button>
            @else
                <button class="py-0 btn btn-sm btn-light border-0 fs-12">{{ ucwords(statusText($booking->status)) }}</button>
            @endif
        </span>
    </div>

    @php
        $incomes = number_format($packagePrice * $booking->qty, 2) + number_format($addOnsPrice, 2) + number_format($extraServicesTotal, 2);
        $deductions = number_format($platform_fee_amount, 2) + number_format($paymentProcessingFee ?? 0, 2) + number_format($calculatedBuyerServiceFee ?? 0, 2);
        $totalServiceCost = (($booking->qty * $booking?->service_price) + $booking->otherprice) - $booking->discount;
    @endphp
    <div class="w-100 p-2 border-bottom fs-14">You'll Pay
        <span class="float-end">${{ number_format($booking->agreed_amount + $booking->buyer_service_fee, 2) }}</span>
    </div>
</div>
    <!-- Card end-->
@endsection
@section('script')
    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Dispute Report')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="dispute-detail">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        'use strict';
        $('.disputeShow').on('click', function() {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>
@endsection
