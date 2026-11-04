@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
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
                Contract
            </p>
            <!--Breadcrumbs End-->

        </div>
        <x-buyer-service-nav :booking="$booking" activeTab="contract" />
    </div>
    @php
        $serviceDetails = $booking?->service?->serviceDetail;
        $seller = optional($serviceDetails->user);
    @endphp

    <!-- Order Information Card -->
    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100">
            <p class="float-end">
                <a class="links-c" href="{{ route('user', ['id' => $booking->seller_id]) }}">
                    <i class="fa-regular fa-comment-dots fs-5"></i>
                </a>
                Chat with <span class="text-green">{{ $seller->fullname ?? 'Seller' }}</span>
            </p>
            <h5 class="text-dgreen2">Service Contract</h5>
        </div>
        <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-semibold rounded">ORDER SUMMERY</div>
        
        <div class="w-100 mt-2 p-2 border-bottom fs-14">Service Name
            <span class="float-end">{{ $booking->service?->title ?? '--' }}</span>
        </div>
        <div class="w-100 mt-2 p-2 border-bottom fs-14">Order Number
            <span class="float-end">{{ $booking->order_number }}</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Seller
            <span class="float-end">{{ $booking?->seller?->username }}</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Delivery Days
            <span class="float-end">{{ $booking?->service_days ?? 0 }} Days</span>
        </div>
        @php
            $incomes = number_format($subTotal, 2) + number_format($extraServicesTotal, 2);
            $deductions = number_format($platformFee, 2);
        @endphp
        <div class="w-100 p-2 border-bottom fs-14">Total Amount
            <span class="float-end">${{ number_format($booking?->agreed_amount, 2) }}</span>
        </div>
        
    </div>

    {{-- Order Details --}}

    <div class="card lg-3 px-3 mt-4 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        
        <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-semibold rounded">ORDER DETAILS</div>
        
        <div class="w-100 mt-2 p-2 border-bottom fs-14">Delivery Time
            <span class="float-end">{{ $booking->service_days ?? '--' }} Days</span>
        </div>
        <div class="w-100 mt-2 p-2 border-bottom fs-14">No. of Revisions
            <span class="float-end">{{ $booking->service_revisions }}</span>
        </div>

        @foreach ($booking?->service?->serviceDetail?->getTierItems() as $tier)
            <div class="w-100 p-2 border-bottom fs-14">
                {{ $tier['label'] }}
                <span class="float-end">
                    {!! $tier['type'] === 'checkbox' && $tier['value'] === 'yes'
                        ? '<i class="fa-solid fa-check text-green"></i>'
                        : ($tier['type'] === 'checkbox'
                            ? '<i class="fa-solid fa-xmark text-danger"></i>'
                            : e($tier['value'])) !!}
                </span>
            </div>
        @endforeach
        
        
    </div>

    @php
        $user_extra_services_cost = 0;
    @endphp
    
    <!-- Additional Services Card -->
    @if(isset($addOns) && count($addOns)>0)
        <div class="card lg-3 mt-4 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
            <div class="w-100 bg-lgreen3 px-3 py-2 mt-2 text-dgreen fw-semibold rounded">ADDITIONAL SERVICES</div>
            <p class="my-2">Additional services requested by <span class="text-green">{{ $booking?->seller?->username }}</span></p>
            @forelse($addOns as $addon)
                <div class="w-100 p-2 border-bottom fs-14">
                    {{ $addon->title }}
                    <span class="float-end">
                        ${{ number_format($addon->amount / ($booking->currentrate ?: 1), 2) }}
                    </span>
                </div>
            @empty
                <div class="w-100 p-2 fs-14 text-muted text-center">No additional services</div>
            @endforelse

        </div>
    @endif

    <!-- Pricing Summary Card -->
    <div class="card lg-3 mt-4 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100 bg-lgreen3 px-3 py-2 mt-2 text-dgreen fw-semibold rounded">TERMS</div>
        <div class="w-100 p-2 border-bottom fs-14">Quantity
            <span class="float-end">{{ $booking->qty }}</span>
        </div>
        
        <div class="w-100 p-2 border-bottom fs-14">Service Subtotal
            <span class="float-end">${{ number_format($subTotal, 2) }}</span>
        </div>
        @if($addOnsPrice > 0)
        <div class="w-100 p-2 border-bottom fs-14">Additional Services
            <span class="float-end">${{ number_format($addOnsPrice,2) }}</span>
        </div>
        @endif

        @if($booking->discount > 0)
        <div class="w-100 p-2 border-bottom fs-14">Discount
            <span class="float-end">${{ number_format($booking->discount,2) }}</span>
        </div>
        @endif
        <div class="w-100 p-2 border-bottom fw-bold fs-14">Total Amount
            <span class="float-end fw-bold">${{ number_format(($subTotal + $addOnsPrice) - $booking->discount,2) }}</span>
        </div>
        <div class="w-100 p-2 fs-14">Platform Fee ({{ $buyer_service_platform_fee }}%)
            <span class="float-end">${{ number_format($platformFee,2) }}</span>
        </div>
        
        <div class="w-100 p-2 fs-14">You Will Pay
            <span class="float-end">${{ number_format($booking->agreed_amount + $platformFee,2) }}</span>
        </div>
    </div>

    {{-- <h6 class="mt-4">Cancellation and Disputes</h6>
    <ul class="fs-14">
        <li>Either party may cancel the order under appropriate grounds (Late delivery, non-delivered, etc)</li>
        <li>Wiserlance's <a href="#">Terms of Service</a> and Dispute Policy apply to this agreement</li>
        <li>Communication conducted outside of Wiserlance may constitute a violation of the platform's policies, which could
            result in the cancellation of an order or the suspension of an account.</li>
    </ul> --}}
@endsection

@section('script')
    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Dispute Report')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="dispute-detail"></div>
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
