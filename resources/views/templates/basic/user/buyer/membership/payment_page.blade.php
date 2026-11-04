@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    @if(auth()->user()->is_freelancer ==1 || auth()->user()->is_freelancer == '1')
        <p class=" text-bc fs-14"><a class="text-3gray" href="{{ route('user.home') }}">
                <i class="fa-solid fa-house fs-14"></i></a>
            <i class="fa-solid fa-chevron-right fs-12"></i> Upgrade Membership Packages Payment
        </p>
    @elseif(auth()->user()->is_employer ==1 || auth()->user()->is_employer == '1')
        <p class=" text-bc fs-14"><a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
            <i class="fa-solid fa-house fs-14"></i></a>
            <i class="fa-solid fa-chevron-right fs-12"></i> Upgrade Membership Packages Payment
        </p>
    @endif

    <h6 class="text-center text-dgreen mt-3 mt-md-0">Join Wiserlance & Grow with Us</h6>
    <div class="row gx-2 mt-md-3">
        @foreach ($membership as $key => $m)
            <div class="col-md-6 col-lg-4 col-xl-6 {{ $loop->first ? '' : 'mt-4 mt-md-0' }}">
                <div class="border-secondary shadow rounded">
                    <ul class="list-group fs-14">
                        <li class="list-group-item text-white {{ $loop->first ? 'bg-secondary' : 'bg-green' }} text-center fw-bold p-2 fs-5">
                            <span class="fs-14 fw-normal">{{ $m->title }}</span>
                        </li>
                        <li class="list-group-item fs-5 fw-bold text-green text-center bg-light">${{ $m->price }} </li>
                        <li class="list-group-item">Service Platfom fees <span class="float-end">{{ $m->service_platform_fee }}%</span></li>
                        <li class="list-group-item">Job Platfom fees <span class="float-end">{{ $m->fee }}%</span></li>
                        <li class="list-group-item">Regular job posting<span class="float-end">{{ $m->normal_jobs > 100000 ? "Unlimited" : $m->normal_jobs }}</span></li>
                        <li class="list-group-item">Features job posting <span class="float-end">{{ $m->featured_jobs > 100000 ? "Unlimited" : $m->featured_jobs }}</span></li>
                        <li class="list-group-item">Invite your selected sellers to your posted jobs <span class="float-end">{{ $m->invites }}</span></li>
                        <li class="list-group-item">Mechanism for matching the seller's expertise to the job requirements <span class="float-end">{{ $m->expertise_matching_mechanism == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Requirements for approval and verification of payment methods <span class="float-end">{{ $m->verified == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Requirement to deposit money that matches the cost of the job<span class="float-end">{{ $m->money_deposit_for_job == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Chat, share files, and place audio and video calls<span class="float-end">{{ $m->communication_tools == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Timesheets, time tracker<span class="float-end">{{ $m->time_tracking_features == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Job Posting Assistance<span class="float-end">{{ $m->r_assistance == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Easy, safe payments<span class="float-end">{{ $m->safe_payment == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Featured Job tag<span class="float-end">{{ $m->jobtag == 0 ? 'No' : 'Yes' }}</span></li>
                        <li class="list-group-item">Customer Support
                            <span class="float-end">
                                @if ($m->support === 1)
                                    Basic
                                @elseif ($m->support === 2)
                                    Standard
                                @elseif ($m->support === 3)
                                    Premium
                                @else
                                    No
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach

        <div class="col-md-6 col-lg-4 col-xl-6">
            <div class="priceboxnew">
                <form method="POST" action="{{ url('/user/buyer/payment') }}">
                    @csrf
                    <div class="modal-body">
                        <input name="current_id" value="{{ $current_id }}" type="hidden" />
                        <input name="new_id" value="{{ $new_id }}" type="hidden" />
                        <div class="form-group">
                            <h5>@lang('How you want to pay')</h5>
                            <select class="form-control" name="payment">
                                <option value="wallet">{{ __($general->sitename) }} @lang('wallet')</option>
                                <!--<option value="checkout">@lang('Checkout')</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- 🩵 FIXED: removed "stretched-link" class that caused full-page form submit --}}
                        <button type="submit" class="btn btn-success borders">
                            @lang('Pay')
                            <i class="lni lni-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
