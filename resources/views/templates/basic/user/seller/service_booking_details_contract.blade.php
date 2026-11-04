@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="row pb-lg-4 mt-4">
        <div class="col-lg-4 pb-4 pb-lg-0 "> <!-- this drop down for small screens -->

            <x-seller-service-mob-nav :booking="$booking" />

            <!--Breadcrumbs-->
            <p class="mb-0 text-bc mt-1 fs-14">
                <a class="text-3gray" href="{{ route('user.home') }}">
                    <i class="fa-solid fa-house fs-14"></i>
                </a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a class="text-3gray" href="{{ route('user.seller-booking-service') }}">Service Booking</a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                Contract
            </p>
            <!--Breadcrumbs End-->

        </div>
        <x-seller-service-nav :booking="$booking" activeTab="contract" />
    </div>

    @php
        $serviceDetails = $booking->service->serviceDetail;
        $seller = optional($serviceDetails->user);  
    @endphp
    <!-- card start -->
    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100">
            <p class="float-end">
                <a class="links-c" href="{{ route('user', ['id' => $booking->buyer_id]) }}">
                    <i class="fa-regular fa-comment-dots fs-5"></i>
                </a>
                Chat with <span class="text-green"><a class="links-c" href="{{ route('user', ['id' => $booking->buyer_id]) }}">{{ $booking->buyer->fullname ?? 'Seller' }}</a></span>
            </p>
            <h5 class="text-dgreen2">Service Contract</h5>
        </div>

        <!-- Order Information Section -->
        <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-semibold rounded">ORDER INFORMATION</div>
        <div class="w-100 mt-2 p-2 border-bottom fs-14">Order ID <span
                class="float-end">{{ $booking->order_number }}</span></div>
        <div class="w-100 p-2 border-bottom fs-14">Order Date <span
                class="float-end">{{ $booking->created_at->format('d M Y') }}</span></div>
        <div class="w-100 p-2 border-bottom fs-14">Buyer <span class="float-end">{{ $booking->buyer->fullname }}</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Order Status
            <span class="float-end">
                @if (statusText($booking->status) == 'waiting acceptance')
                    <button class="py-0 btn btn-sm btn-secondary border-0 fs-12">Waiting Acceptance</button>
                @elseif(statusText($booking->status) == 'accepted')
                    <button class="py-0 btn btn-sm btn-success bg-green border-0 ms-2 fs-12">Accepted</button>
                @elseif(statusText($booking->status) == 'declined')
                    <button class="py-0 btn btn-sm btn-danger border-0 ms-2 fs-12">Declined</button>
                @elseif(statusText($booking->status) == 'cancel')
                    <button class="py-0 btn btn-sm btn-danger border-0 ms-2 fs-12">Cancel</button>
                @else
                    <button
                        class="py-0 btn btn-sm btn-light border-0 fs-12">{{ ucwords(statusText($booking->status)) }}</button>
                @endif
            </span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Working Status
            <span class="float-end">
                @if (workingServiceStatusText($booking->working_status) == 'waiting start')
                    <button class="py-0 btn btn-sm btn-secondary border-0 fs-12">Waiting Start</button>
                @elseif (workingServiceStatusText($booking->working_status) == 'accepted')
                    <button class="py-0 btn btn-sm btn-success border-0 fs-12">Accepted</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'in progress')
                    <button class="py-0 btn btn-sm btn-success bg-dgreen2 fs-12 border-0 ms-2">In Progress</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'delivered')
                    <button class="py-0 btn btn-sm btn-success bg-green fs-12 border-0 ms-2">Delivered</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'disputed')
                    <button class="py-0 btn btn-sm btn-danger border-0 fs-12 ms-2">Disputed</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'cancel')
                    <button class="py-0 btn btn-sm btn-danger border-0 fs-12 ms-2">Cancel</button>
                @else
                    <button
                        class="py-0 btn btn-sm btn-light border-0 fs-12">{{ ucwords(workingServiceStatusText($booking->working_status)) }}</button>
                @endif
            </span>
        </div>
    </div>

    <!-- Service Description Card -->
    <div class="card lg-3 mt-4 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-semibold rounded">SERVICE DESCRIPTION</div>
        <div class="w-100 p-2 border-bottom fs-14">Service Title <span
                class="float-end">{{ $booking->service->title }}</span></div>
        <div class="w-100 p-2 border-bottom fs-14">Selected Plan <span
                class="float-end fw-bold">{{ $booking->serviceDetails->title ?? '-' }}</span></div>
        <div class="w-100 p-2 border-bottom fs-14">Delivery Time <span class="float-end">{{ $booking->service_days }} Days
                (date: {{ $booking->created_at->addDays($booking?->service_days ?? 1)->format('d M Y') ?? '-' }})</span></div>
        <div class="w-100 p-2 border-bottom fs-14">Revisions <span
                class="float-end">{{ $booking->service_revisions }}</span></div>

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

    <!-- Additional Services Card -->
    @php
        $user_extra_services_cost = 0;
    @endphp
    <div class="card lg-3 mt-4 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-semibold rounded">ADDITIONAL SERVICES (IF ANY)</div>
        @forelse($addOns as $addon)
            @php
                $user_extra_services_cost += $addon->amount;
            @endphp
            <div class="w-100 p-2 border-bottom fs-14">
                {{ $addon->title }}
                <span class="float-end">
                    ${{ number_format($addon->amount / ($booking->currentrate ?: 1), 2) }}
                </span>
            </div>
        @empty
            <div class="w-100 p-2 fs-14 text-center text-muted">No additional services</div>
        @endforelse
    </div>
   
    <!-- Pricing Summary Card -->
    <div class="card lg-3 mt-4 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-semibold rounded">PRICING SUMMARY</div>
        <div class="w-100 p-2 border-bottom fs-14">Service Cost <span class="float-end">${{ number_format($packagePrice,2) }}</span></div>
        <div class="w-100 p-2 border-bottom fs-14">Quantity <span class="float-end">{{ $booking->qty }}</span></div>
        <div class="w-100 p-2 border-bottom fs-14">Subtotal <span
                class="float-end">${{ number_format($packagePrice * $booking->qty, 2) }}</span></div>
        
        @if($addOnPrice > 0)
        <div class="w-100 p-2 border-bottom fs-14">Extra Services 
            <span class="float-end">${{ number_format($addOnPrice,2) }}</span>
        </div>
        @endif

        @if($booking->discount > 0)
        <div class="w-100 p-2 border-bottom fs-14">Discount <span
                class="float-end">${{ number_format($booking->discount,2) }}</span></div>
        @endif
        <div class="w-100 p-2 border-bottom fs-14">Platform Fee ({{ $seller_platform_fee ?? '4' }}%) <span
                class="float-end">${{ $plarform_fee_amount }}</span></div>
        {{-- <div class="w-100 p-2 border-bottom fs-14">Payment Processing Fee <span
                class="float-end">${{ $paymentProcessingFee }}</span></div> --}}
        <div class="w-100 p-2 fs-14">You'll receive <span class="float-end">${{ number_format($amount - $plarform_fee_amount, 2) }}</span></div>
    </div>

    <h6 class="mt-4">Cancellation and Disputes</h6>
    <ul class="fs-14">
        <li>Either party may cancel the order under appropriate grounds (Late delivery, non-delivered, etc)</li>
        <li>Wiserlance's <a href="{{ route('terms') }}">Terms of Service</a> and Dispute Policy apply to this agreement</li>
        <li>Communication conducted outside of Wiserlance may constitute a violation of the platform's policies, which could
            result in the cancellation of an order or the suspension of an account.</li>
    </ul>
    <!-- card end -->
@endsection


@section('script')
    <script>
        'use strict';
        $('.approvedBtn').on('click', function() {
            var modal = $('#approvedModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.cancelBtn').on('click', function() {
            var modal = $('#cancelModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.workBtn').on('click', function() {
            var modal = $('#workModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.disputeShow').on('click', function() {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>
@endsection
