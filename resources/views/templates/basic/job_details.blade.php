@extends('templates.basic.layouts.wiserlance.layout')
<style>
    .matching-skill {
        background-color: #d1e7dd;
        color: #0f5132;
        padding: 5px;
        margin-left: 5px;
        border-radius: 4px;
        border: 1px solid #0f5132;
    }
</style>
@section('content')
    <p class="mb-0 mt-3 text-3gray"> <!--Breadcrumbs-->
        @if ($user->is_employer)
            <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
        @else
            <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
        @endif
        <i class="fa-solid fa-chevron-right fs-12"></i><a href="/job" class="text-3gray" style="text-decoration:none;">
            Jobs</a>
        <i class="fa-solid fa-chevron-right fs-12"></i> Job Details
    </p>

    <div class="row py-2">
        <!-- Main Content -->
        <div class="col-lg-8  mt-lg-0" id="contents">
            {{-- <h5 class="mt-2">{{ $job->title }}</h5> --}}
            <!-- Job Description -->
            <div class="bg-white px-3 py-2  mt-2 shadow-sm border fs-14 gap-2 rounded-2">
                {{-- <h5 class="mt-2">Description</h5> --}}
                <h5 class="mt-2">{{ $job->title }}</h5>
                <p class="fs-14 ps-3">{!! $job->description !!}</p>
            </div>

            <!-- Job Requirements -->
            <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                <h5 class="mt-2">Requirements</h5>
                <p class="ps-3">{!! $job->requirements !!}</p>
            </div>

            <!-- Job Title and Basic Info -->
            <div class="bg-white px-0 px-md-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                <div class="row pt-2 ps-3">
                    <div class="col-5 col-lg-4">
                        <p><i class="fas fa-briefcase"></i> Level: {{ $job->exp_type_text }}</p>
                    </div>
                    <div class="col-5 col-lg-4">
                        <p><i class="fas fa-coins me-2"></i>Budget Type: {{ $job->job_type }}</p>
                    </div>
                    <div class="col-5 col-lg-4">
                        <p><i class="fa-solid fa-clock-rotate-left"></i> Posted: {{ diffforhumans($job->created_at) }}</p>
                    </div>
                    <div class="col-5 col-lg-4">
                        <p><i class="fas fa-map-marker-alt"></i> Location: {{ $job->user->country ?? 'Worldwide' }}
                        </p>
                    </div>
                    {{-- <div class="col-5 col-lg-4 pe-sm-0">
                       <p>
                            <i class="fa-regular fa-clock"></i>
                            Delivery Time:
                            @if(!empty($job->delivery_date))
                                {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($job->delivery_date), false) }} Days
                            @else
                                N/A
                            @endif
                        </p>
                    </div> --}}
                    <div class="col-5 col-lg-4">
                        @if($job->budgettype == 'hourly')
                            <p><i class="fa-solid fa-filter-circle-dollar text-dark"></i> Budget: ${{ $job->amount * $job->weekly_hours }}</p>
                        @else
                            <p><i class="fa-solid fa-filter-circle-dollar text-dark"></i> Budget: ${{ $job->amount }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Skills & Expertise -->
            @php
                $hasMatchingSkills = false;

                foreach ($job->skills ?? [] as $skill) {
                    if ($matchingSkills->contains($skill->name)) {
                        $hasMatchingSkills = true;
                        break;
                    }
                }

                if (!$hasMatchingSkills) {
                    foreach ($job->extraSkills ?? [] as $extraSkill) {
                        if ($matchingSkills->contains($extraSkill->name)) {
                            $hasMatchingSkills = true;
                            break;
                        }
                    }
                }
            @endphp
            <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2" style="line-height:40px;">
                <h5 class="mt-2">Skills & Expertise</h5>

                @if($hasMatchingSkills)
                    <p class="text-success fw-semibold mb-2">
                        Your highlighted skills align with project requirements
                    </p>
                @endif

                <p class="mt-3 text-left">
                    @if (isset($job->skills) && count($job->skills) > 0 && $job->extraSkills && count($job->extraSkills) > 0)

                        @foreach ($job->skills as $skill)
                            <span class="{{ $matchingSkills->contains($skill->name) ? 'matching-skill' : 'tags' }} {{ !$loop->first ? 'ms-2' : '' }}">
                                {{ __($skill?->name) }}
                            </span>
                        @endforeach

                        @foreach($job->extraSkills as $extraSkill)
                            <span class="{{ $matchingSkills->contains($extraSkill->name) ? 'matching-skill' : 'tags' }} ms-2">
                                {{ __($extraSkill->name) }}
                            </span>
                        @endforeach

                    @else
                        <span class="ms-2 text-muted">No skills available.</span>
                    @endif
                </p>
            </div>
            <!-- Activity on this Job -->
            <div class="bg-white px-3 py-2 mt-3 shadow fs-14 rounded-2">
                <h5 class="mt-2">Job Activity</h5>
                <div class="row pt-2 ps-md-3">
                    <div class="col-4 col-md-4">
                        <p>Bids: {{ $bids }}</p>
                    </div>
                    <div class="col-4 col-md-4">
                        <p>Offers: {{ $offers }}</p>
                    </div>
                    @php
                        $statusClass = $job->status == 1 ? 'text-green' : 'text-danger';
                    @endphp
                    <div class="col-4 col-md-4">
                        <p>Status: <span class="{{ $statusClass  }}">{{ $job->status_text }}</span></p>
                    </div>
                    {{-- <div class="col-6 col-md-4">
                        <p>Interviewing: None</p>
                    </div> --}}
                    <div class="col-6 col-md-4">
                        <p>Hired: None</p>
                    </div>

                </div>
            </div>
            <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                <h5 class="mt-2">Attachments</h5>
                @if ($job->attachments->isNotEmpty())
                    @foreach ($job->attachments as $attachment)
                        <p class="w-75 ms-3 mb-1 pb-2 ">
                            <span>
                                <a href="{{ Storage::url($attachment->image_url) }}" download>
                                    <i class="fa-solid fa-download text-green"></i> Download
                                </a>
                            </span>
                        </p>
                        {{-- <p class="w-75 ms-3 mb-1 pb-2 border-bottom">
                            {{ basename($attachment->image_url) }}
                            <span class="float-end">
                                <a href="{{ Storage::url($attachment->image_url) }}" download>
                                    <i class="fa-solid fa-download text-green"></i> Download
                                </a>
                            </span>
                        </p> --}}
                    @endforeach
                @else
                    <p class="w-75 ms-3">No attachments available for this job.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 order-last order-lg-last mt-2">
            @if ($user->is_freelancer)
                @if ($job->status != 4)
                    <div class="bg-white p-4 shadow-sm border mb-3 fs-14 rounded-2 text-center">
                        {{-- Ensure bidding is not closed --}}
                        <button class="btn btn-sm btn-success bg-dgreen2" style="cursor: default;"> Bids Quota: {{ $totalUserBid }}
                        </button>
                        @if ($checkbidexists)
                            @php
                                $redirectLink = route(
                                    $job->budgettype === 'per_hour' || $job->budgettype === 'hourly'
                                        ? 'job.bid.hourly'
                                        : 'job.bid.fixed',
                                    [encrypt($job->id)],
                                );

                                if ($userBiding->is_cancel_bid == 1) {
                                    $redirectLink = route('job.offer.detail', [encrypt($job->buyerCounterOffer->id)]);
                                }
                            @endphp
                            <button class="btn btn-sm ms-3 btn-success bg-green border-0"
                                onclick="window.location.href='{{ $redirectLink }}'"> @lang('Revise Bid')</button>
                        @else
                            {{-- If user hasn't bid yet, show the Bid Now button --}}
                            @if ($job->priority == 2 && $featured_job_bids > 0)
                                <button class="btn btn-sm ms-3 btn-success bg-green border-0"
                                    onclick="window.location.href='{{ route($job->budgettype === 'hourly' ? 'job.bid.hourly' : 'job.bid.fixed', [encrypt($job->id)]) }}'">
                                    Bid Now
                                </button>
                            @elseif($job->priority != 2)
                                <button class="btn btn-sm ms-3 btn-success bg-green border-0"
                                    onclick="window.location.href='{{ route($job->budgettype === 'hourly' ? 'job.bid.hourly' : 'job.bid.fixed', [encrypt($job->id)]) }}'">
                                    Bid Now
                                </button>
                            @else
                                <button class="btn btn-sm ms-3 btn-success bg-green border-0">
                                    No Featured Bids
                                </button>
                            @endif
                        @endif
                    </div>
                @endif
            @endif
            <div class="bg-white p-3 mt- shadow-sm border fs-14 rounded-2 text-center">
                <h6 class="p-1"> Posted By</h6>
                @php
                $user = $buyerProfile['user'];
                $imagePath = $user->image;

                $imageExists = !empty($imagePath) && Storage::exists($imagePath);
            @endphp

            <a class="text-decoration-none"
            style="display:flex;justify-content:center;"
            href="{{ route('buyerprofile', [slug(optional($user)->username), encrypt(optional($user)->id)]) }}">

                @if ($imageExists)
                    <img src="{{ Storage::url($imagePath) }}"
                        alt="@lang('Profile Image')"
                        class="rounded-circle me-2"
                        width="80"
                        height="80"
                        style="object-fit:cover">
                @else
                    <div class="d-flex justify-content-center">
                        <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                            style="height:70px;width:70px;font-size:20px">
                            {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }}{{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                @endif

            </a>

                <h6 class=" mb-0 mt-2 ">{{ $buyerProfile['user']->username }} <i
                        class="fa-solid fa-circle-check text-green"></i></h6>
                <p class="fs-12 text-secondary pt-0">{{ $buyerProfile['user']->designation }} </p>

                <div class="row ">
                    <div class="col-6 col-xl-6 col-lg-7  text-start"><i class="fa-regular fa-building fs-6  text-3gray"></i>
                        {{ $buyerProfile['user']->userCompany->companyname }} </div>
                    <div class="col-6 col-xl-6 col-lg-5 text-end"><i class="fa-solid fa-location-dot text-3gray"></i>
                        {{ $buyerProfile['user']->userCompany->country }} </div>
                </div>

                <div class="row mt-2 border-top border-bottom py-2">
                    <div class="col-4 text-start"> Feedback:
                        {{ number_format(($buyerRatings / 5) * 100, 1) }}%
                        {{-- <p class="text-warning fs-14">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($buyerRatings))
                                    <i class="fa-solid fa-star"></i>
                                @elseif ($i == ceil($buyerRatings) && $buyerRatings - floor($buyerRatings) > 0)
                                    <i class="fa-solid fa-star-half-alt"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </p> --}}
                    </div>
                    @if($job_user->is_login == 1)
                        <div class="col-4 col-lg-4 col-xl-5 col-xxl-3 text-end"><i
                            class="fa-solid fa-circle text-success fs-10"></i> Online</div>
                    @else
                        <div class="col-4 col-lg-4 col-xl-5 col-xxl-3 text-end"><i
                            class="fa-solid fa-circle text-danger fs-10"></i> Offline</div>
                    @endif

                    <div class="col-4 text-end">
                        @if ($buyerProfile['isPaymentVerified'])
                            <i class="fa-solid fa-check fs-6 text-green"></i> Verified
                        @else
                            <i class="fa-solid fa-times fs-6 text-danger"></i> Unverified
                        @endif
                    </div>
                </div>

                <div class="row mt-2 py-1 bg-light">
                    <div class="col-6 col-xl-6 text-start"><i class="fa-solid fa-briefcase text-3gray"></i> Services Bought:
                        {{ $buyerProfile['serviceBoughtCount'] }} </div>
                    <div class="col-6 col-xl-6 text-start text-xl-end"><i class="fa-regular fa-building text-3gray"></i>
                        Work Completed: {{ $buyerProfile['completedProjects'] }}</div>
                </div>

                <div class="row py-1 bg-light">
                    <div class="col-6 col-xl-6 text-start"><i class="fa-regular fa-envelope fs-6 text-3gray"></i> Jobs
                        Posted: {{ $otherJobs->count() }} </div>
                    <div class="col-6 col-xl-6 text-start text-xl-end"><i class="fa-solid fa-stopwatch text-3gray"></i>
                        Work
                        Pending:{{ $buyerProfile['pendingProjects'] }}</div>
                </div>
                <div class="row py-1 bg-light">
                    <div class="col-12  text-start">
                        <i class="fa-solid fa-dollar-sign fs-6 text-3gray"></i>
                        Total Spent: ${{ $buyerProfile['totaltransactions'] }}
                    </div>
                </div>
                <div class="row py-1 bg-light">
                    <div class="col-12 pb-2 text-start">
                        <i class="fa-solid fa-user-tie fs-6 text-3gray"></i>
                        Member Since: {{ $user->created_at->format('d M Y') }}
                    </div>
                </div>

                <div class="row py-1 ">
                    <button class="btn btn-sm btn-light mt-2 text-start rounded-0 ps-3" data-bs-toggle="collapse"
                        data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        More Jobs from Buyer <i class="fa-solid fa-chevron-down float-end text-3gray mt-1"></i>
                    </button>

                    <div class="collapse mt-2" id="collapseExample">
                        @forelse ($otherJobs as $ojb)
                            <p class="w-100 border-bottom mb-1 pb-1 text-start">
                                {{ $ojb->title }}
                                {{-- <span
                                    class="float-end
                                    {{ $ojb->status == 1 ? 'text-green' : ($ojb->status == 2 ? 'text-danger' : 'text-danger') }}">
                                    @if ($ojb->status == 1)
                                        {{ 'Active' }}
                                    @elseif ($ojb->status == 3)
                                        {{ 'Completed' }}
                                    @else
                                        {{ 'Closed' }}
                                    @endif
                                </span> --}}
                            </p>
                        @empty
                            <p class="w-100 border-bottom mb-1 pb-1 text-start text-muted">
                                No other jobs from this buyer.
                            </p>
                        @endforelse
                    </div>
                </div>

            </div>
            @php
                $user_status = Auth::user();
            @endphp
            @if($user_status->is_employer == 1)
                @if ($job->status == 1 && $job->job_status == 0)
                    <div class="bg-white p-4 mt-2 shadow fs-14 rounded-2 text-center">
                        <a href="{{ url('/invite-freelancers/' . encrypt($job->id)) }}"
                            class="btn btn-sm btn-success bg-dgreen">Invite
                            Freelancers</a>
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection
