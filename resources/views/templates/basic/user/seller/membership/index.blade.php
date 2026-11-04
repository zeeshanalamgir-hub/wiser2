@extends("templates.basic.layouts.wiserlance.dashboard-layout-job")
@section('content')
<div class="row mt-2">
    <div class="col-12">
        <h5 class="text-dgreen2">Freelancer Membership Packages</h5>
    </div>
</div>
<h6 class="text-center text-dgreen mt-3 mt-md-0">Join Wiserlance & Grow with Us</h6>
<p class="text-center text-white bg-dgreen2" style="padding: 5px;border-radius: 5px">We aim to grow together, therefore we've created a system that maximises Professionals' productivity and earnings!</p>

<!-- Toggle Switch -->
<div class="text-center mt-4">
    <button id="monthlyBtn" class="btn btn-success me-2 active">Monthly</button>
    <button id="yearlyBtn" class="btn btn-outline-success">Yearly</button>
</div>

<div class="row gx-2" id="membershipContainer">
    @foreach ($membership as $m)
        <div class="membership-card col-md-6 col-lg-4 col-xl-4
            {{ $loop->index > 0 ? 'pt-4 mt-md-0' : '' }}"
            data-plan="{{ $m->freelancer_payment_plan_id }}"
            style="display: {{ $m->freelancer_payment_plan_id == 1 ? 'block' : 'none' }};">
            <div class="border-secondary shadow rounded">
                <ul class="list-group fs-14">
                    {{-- Dynamic Background Class for Title --}}
                    <li class="list-group-item text-center fw-bold p-2 fs-5 text-white
                        {{ $m->title == 'Try it free' ? 'bg-secondary' : ($m->title == 'Basic' ? 'bg-dgreen2' : ($m->title == 'STANDARD' ? 'bg-green' : 'bg-dgreen')) }}">
                        <span class="fs-14 fw-normal">{{ $m->title }}</span>
                    </li>

                    {{-- Price --}}
                    <li class="list-group-item fs-5 fw-bold text-green text-center bg-light">
                        {{ $m->price == 0 ? 'Free' : '$' . $m->price . ($m->freelancer_payment_plan_id == 1 ? '/M' : '/Y') }}
                    </li>

                    {{-- Dynamic Details --}}
                    <li class="list-group-item">Service Cost <span class="float-end">{{ $m->service_cost }}%</span></li>
                    <li class="list-group-item">Job Cost <span class="float-end">{{ $m->fee }}%</span></li>
                    <li class="list-group-item">Monthly Number of Bids<span class="float-end">{{ $m->bids }}</span></li>
                    <li class="list-group-item">Featured Jobs Bids <span class="float-end">{{ $m->featuredjobquotes }}</span></li>
                    <li class="list-group-item">Annual Bids Rollover <span class="float-end">{{ $m->annual_bids_rollover }}</span></li>
                    <li class="list-group-item">Purchase Additional Bids <span class="float-end">{{ $m->purchase_additional_bids == 0 ? 'No' : 'Yes' }}</span></li>
                    <li class="list-group-item">Monthly Regular Services <span class="float-end">{{ $m->normal_services }}</span></li>
                    <li class="list-group-item">Monthly Featured Services<span class="float-end">{{ $m->featured_services }}</span></li>
                    <li class="list-group-item">Annual Services Rollover<span class="float-end">{{ $m->annual_services_rollover }}</span></li>
                    <li class="list-group-item">Purchase Additional Services<span class="float-end">{{ $m->purchase_service == 0 ? 'No' : 'Yes' }}</span></li>
                    <li class="list-group-item">Portfolio Slots<span class="float-end">{{ $m->portfolio_size }}</span></li>
                    <li class="list-group-item">Verified Account<span class="float-end">{{ $m->verifiedaccount == 0 ? 'No' : 'Yes' }}</span></li>
                    <li class="list-group-item">Priority Support<span class="float-end">{{ $m->support == 0 ? 'No' : 'Yes' }}</span></li>
                    <li class="list-group-item">Quick Withdrawals Service<span class="float-end">{{ $m->safe_payment == 0 ? 'No' : 'Yes' }}</span></li>

                    {{-- Button Logic --}}
                    @if($m->title=="Try it free")
                    @else
                        @if($m->default == 0)
                            @if ($currentmembership_id == $m->id)
                                <li class="list-group-item text-center py-3">
                                    <button class="btn btn-sm btn-secondary"> Selected</button>
                                </li>
                            @else
                                <li class="list-group-item text-center py-3">
                                    <a  href="{{url('/user/seller/payment?c_id='.$currentmembership_id.'&id='.$m->id)}}">
                                        <button class="btn btn-sm btn-success {{ $m->title == 'Basic' ? 'bg-dgreen2' : ($m->title == 'STANDARD' ? 'bg-green' : 'bg-dgreen') }}"> Buy Now</button>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="list-group-item text-center py-3">
                                <a  href="{{url('/user/seller/payment?c_id='.$currentmembership_id.'&id='.$m->id)}}">
                                    <button class="btn btn-sm btn-success {{ $m->title == 'Basic' ? 'bg-dgreen2' : ($m->title == 'STANDARD' ? 'bg-green' : 'bg-dgreen') }}"> Buy Now</button>
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    @endforeach
</div>

@endsection

@section('script')
<script>
    (function ($) {
        "use strict";

        // Switch logic for Monthly/Yearly
        $('#monthlyBtn').on('click', function () {
            $('.membership-card[data-plan="1"]').show(); // Show monthly cards
            $('.membership-card[data-plan="2"]').hide(); // Hide yearly cards
            $('#monthlyBtn').addClass('active btn-success').removeClass('btn-outline-success');
            $('#yearlyBtn').removeClass('active btn-success').addClass('btn-outline-success');
        });

        $('#yearlyBtn').on('click', function () {
            $('.membership-card[data-plan="1"]').hide(); // Hide monthly cards
            $('.membership-card[data-plan="2"]').show(); // Show yearly cards
            $('#yearlyBtn').addClass('active btn-success').removeClass('btn-outline-success');
            $('#monthlyBtn').removeClass('active btn-success').addClass('btn-outline-success');
        });

        // Show Monthly by default
        $(document).ready(function () {
            $('#monthlyBtn').trigger('click');
        });

    })(jQuery);
</script>
@endsection
