@if(count($jobs) > 0)
    @foreach ($jobs as $key => $job)
        <div class="row bg-white px-2 py-3 p-md-3 shadow-sm border fs-14 rounded-2 @if($key != 0) my-2 @endif">
            <div class="col-8 col-md-9 d-flex mb-1">
                <a href="{{ route('buyerprofile', [slug(optional($job->user)->username), encrypt(optional($job->user)->id)]) }}" class="text-decoration-none text-dark">
                    @if(!is_null($job?->user?->image))
                        <img src="{{ Storage::url($job?->user?->image) ?? asset('assets/images/default-profile.png')}}" alt="@lang('Profile Image')" class="rounded-circle me-2" width="55" height="55">
                    @else
                        <div class="d-flex justify-content-center">
                            <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                style="height:45px;width:45px;font-size:20px">
                                    {{ strtoupper(substr($job?->user?->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($job?->user?->lastname ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                    @endif
                </a>
                <div style="width:400px;display:flex;align-items:center;margin-left:10px">
                    <h6 class="mb-0 pt-1">
                        <a href="{{ route('buyerprofile', [slug(optional($job->user)->username), encrypt(optional($job->user)->id)]) }}" class="text-decoration-none text-dark">
                            {{ optional($job->user)->username }}
                        </a>
                    </h6>
                    <p class="fs-12 text-secondary">{{ optional($job->user)->designation }}</p>
                </div>
            </div>
            <div class="col-4 col-md-3 float-end text-end mt-1">
                <p class="text-secondary fs-12"><i class="fa-regular fa-clock me-1 bg-light text-warning"></i>{{ $job->created_at->diffForHumans() }}</p>
            </div>
            <h6>{{ $job->title }}</h6>
            <p class="fs-14 text-3gray">
                <i class="fas fa-briefcase"></i> {{ $job->exp_type_text }} |
                <i class="fas" style="color:#a2a2a2"></i> {{ $job->job_type }} |
                <i class="fas"></i> ${{ number_format($job->amount, 2) }}
            </p>
            <p class="fs-14 ps-3">
                <span id="short-description-{{ $key }}">
                    {{ Str::limit(strip_tags(html_entity_decode($job->description)), 150) }}
                </span>
                <span id="full-description-{{ $key }}" style="display: none;">
                    {{ strip_tags(html_entity_decode($job->description)) }}
                </span>

                @if (strlen(strip_tags(html_entity_decode($job->description))) > 150)
                    <span id="dots-{{ $key }}"></span>
                    <a class="links-c" href="javascript:void(0);" onclick="toggleDescription({{ $key }})"
                        id="myBtn-{{ $key }}">Read more</a>
                @endif
            </p>
            <div class="carousel-container">
                <div class="inner-carousel">
                    <div class="track">
                        @if (!empty($job->skill) && is_array($job->skill))
                            @foreach ($job->skill as $skill)
                                <div class="card-container">
                                    <div class="card-c">{{ $skill }}</div>
                                </div>
                            @endforeach
                        @else
                            <div class="card-container">
                                <div class="card-c text-muted">No skills listed</div>
                            </div>
                        @endif
                    </div>
                    <div class="navc">
                        <button class="prev bg-white border-0"><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="next bg-white border-0"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-lg-10">
                    <p>
                        @if($job?->user?->is_payment_verified)
                            <i class="fas fa-user-check text-green"></i> Verified
                        @else
                            <i class="fas fa-user-check text-secondary"></i> Unverified
                        @endif
                        <i class="fas fa-user-friends text-green ms-4"></i> {{ $job->user_biding_count }} Offers
                        <i class="fas fa-map-marker-alt text-green fs-6 ms-4"></i> {{ $job->country }}
                    </p>
                </div>
                <div class="col-md-3 col-lg-2 pe-0">
                    <button class="btn btn-sm btn-success bg-green border-0 float-md-end"
                        onclick="location.href='{{ route('job.details', [encrypt($job->id)]) }}';"> View
                        Details</button>
                </div>
            </div>
        </div>
    @endforeach
    <div class="mt-4 ajax-pagination-wrapper">
        {!! $jobs->links() !!}
    </div>
@else
    <div class="text-center row bg-white px-2 py-3 p-md-3 shadow-sm border fs-14 rounded-2 my-2">
        <h5 class="text-secondary">@lang('No jobs available')</h5>
        <p class="text-muted">@lang('Please check back later or adjust your filters.')</p>
    </div>
@endif
