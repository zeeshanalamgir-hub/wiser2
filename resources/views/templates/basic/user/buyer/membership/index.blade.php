@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    {{-- <p class=" text-bc fs-14"><a class="text-3gray" href="../seler/dashboard.php">
        <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12"></i> Upgrade Membership Packages
    </p> <!--Breadcrumbs End--> --}}

    {{-- <div class="row">
        <div class="col-12">
            <h5 class="text-dgreen2 ms-2"> Upgrade Membership Packages</h5>
        </div>
    </div> --}}
    <h6 class="text-center text-dgreen mt-3">Join Wiserlance & Grow with Us</h6>
    <p class="text-center text-white bg-dgreen2" style="padding: 5px;border-radius: 5px">We prioritise our clients' happiness. We strive to deliver professional services while protecting buyer/seller interests.</p>
    <div class="row gx-2 mt-md-3">
        @foreach ($membership as $key => $m)
            <div class="col-md-6 col-lg-4 col-xl-6 {{ $loop->first ? '' : 'mt-4 mt-md-0' }}">
                <div class="border-secondary shadow rounded">
                    <ul class="list-group fs-14">
                        <li class="list-group-item text-white {{ $loop->first ? 'bg-secondary' : 'bg-green' }} text-center fw-bold p-2 fs-5"> <span class="fs-14 fw-normal">{{$m->title}}</span></li>
                        <li class="list-group-item fs-5 fw-bold text-green text-center bg-light">${{$m->price}} </li>
                        <li class="list-group-item">Service Platfom fees <span class="float-end"> {{$m->service_platform_fee}}%</span></li>
                        <li class="list-group-item">Job Platfom fees <span class="float-end"> {{$m->fee}}%</span></li>
                        <li class="list-group-item">Regular job posting<span class="float-end">{{$m->normal_jobs >= 100000 ? 'Unlimited' : $m->normal_jobs}}</span></li>
                        <li class="list-group-item">Features job posting <span class="float-end">{{$m->featured_jobs}}</span></li>
                        <li class="list-group-item">Invite your selected sellers to your posted jobs <span class="float-end">{{$m->invites}}</span></li>
                        <li class="list-group-item">Mechanism for matching the seller's expertise to the job requirements <span class="float-end">{{$m->expertise_matching_mechanism == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Requirements for approval and verification of payment methods <span class="float-end">{{$m->verified == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Requirement to deposit money that matches the cost of the job<span class="float-end">{{$m->money_deposit_for_job == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Chat, share files, and place audio and video calls<span class="float-end">{{$m->communication_tools == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Timesheets, time tracker<span class="float-end">{{$m->time_tracking_features == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Job Posting Assistance<span class="float-end">{{$m->r_assistance == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Easy, safe payments<span class="float-end">{{$m->safe_payment == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Featured Job tag<span class="float-end">{{$m->jobtag == 0 ? "No" : "Yes"}}</span></li>
                        <li class="list-group-item">Customer Support<span class="float-end">@if ($m->support === 1)
                            Basic
                            @elseif ($m->support === 2)
                            Standard
                            @elseif ($m->support === 3)
                            Premium
                            @else
                            No
                            @endif</span></li>
                        @if ($m->title == 'Try it free')
                        @else
                            @if ($m->default == 0)
                                @if ($currentmembership_id == $m->id)
                                    <li class="list-group-item text-center py-3"> <button class="btn btn-sm border-0 btn-success text-white {{ $loop->first ? 'bg-secondary' : 'bg-green' }}"> Selected</button></li>
                                @else
                                    <li class="list-group-item text-center py-3">
                                        <a href="{{ url('/user/buyer/payment?c_id=' . $currentmembership_id . '&id=' . $m->id) }}">
                                            <button class="btn btn-sm border-0 btn-success text-white {{ $loop->first ? 'bg-secondary' : 'bg-green' }}"> Get Started</button>
                                        </a>
                                    </li>
                                @endif
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
    (function($) {
        "use strict";
        $('.approveBtn').on('click', function() {
            var modal = $('#detailModal');
            var feedback = $(this).data('admin_feedback');
            modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endsection
