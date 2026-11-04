@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    <p class="text-bc fs-14">
        <a class="text-3gray" href="{{ route('user.home') }}"><i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12"></i> Freelancer Membership Packages
    </p> <!--Breadcrumbs End-->
    <div class="row">
        <div class="col-12">
            <h5 class="text-dgreen2 ms-2">Freelancer Membership Packages Payment</h5>
        </div>
    </div>
    <div class="row gx-2 mt-md-3" id="membershipContainer">
        @foreach ($membership as $m)
            <div class="membership-card col-md-6 col-lg-4 col-xl-6
                {{ $loop->index > 0 ? 'mt-4 mt-md-0' : '' }}"
                data-plan="{{ $m->freelancer_payment_plan_id }}">
                <div class="border-secondary shadow rounded">
                    <ul class="list-group fs-14">
                        {{-- Dynamic Background Class for Title --}}
                        <li
                            class="list-group-item text-center fw-bold p-2 fs-5 text-white
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
                        <li class="list-group-item">Featured Jobs Bids <span
                                class="float-end">{{ $m->featuredjobquotes  }}</span></li>
                        <li class="list-group-item">Annual Bids Rollover <span
                                class="float-end">{{ $m->annual_bids_rollover }}</span></li>
                        <li class="list-group-item">Purchase Additional Bids <span
                                class="float-end">{{ $m->purchase_additional_bids == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Monthly Regular Services <span
                                class="float-end">{{ $m->normal_services }}</span></li>
                        <li class="list-group-item">Monthly Featured Services<span
                                class="float-end">{{ $m->featured_services }}</span></li>
                        <li class="list-group-item">Annual Services Rollover<span
                                class="float-end">{{ $m->annual_services_rollover }}</span></li>
                        <li class="list-group-item">Purchase Additional Services<span
                                class="float-end">{{ $m->purchase_service == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Portfolio Slots<span class="float-end">{{ $m->portfolio_size }}</span></li>
                        <li class="list-group-item">Verified Account<span
                                class="float-end">{{ $m->verifiedaccount == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Priority Support<span
                                class="float-end">{{ $m->support == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Quick Withdrawals Service<span
                                class="float-end">{{ $m->safe_payment == 0 ? 'No' : 'Yes' }}</span></li>
                    </ul>
                </div>
            </div>
        @endforeach

        <div class="col-md-6 col-lg-4 col-xl-6">
            <div class="priceboxnew">
                <form method="POST" action="{{ url('/user/seller/payment') }}">
                    @csrf
                    <div class="modal-body">
                        <input name="current_id" value="{{ $current_id }}" type="hidden" />
                        <input name="new_id" value="{{ $new_id }}" type="hidden" />
                        <div>
                            <h5>@lang('How you want to pay')</h5>
                            <select class="form-control" name="payment">
                                <option value="wallet">{{ __($general->sitename) }} @lang('wallet')</option>
                                <option value="checkout">@lang('Checkout')</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- ✅ FIXED: Removed "stretched-link" class to stop accidental form submit --}}
                        <button type="submit" class="btn btn-success mt-1 borders">
                            @lang('Pay') <i class="lni lni-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
