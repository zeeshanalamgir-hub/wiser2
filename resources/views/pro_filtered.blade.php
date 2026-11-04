@if ($professionals->isEmpty())
<div class="text-center bg-light py-5">
    <h5 class="text-secondary">@lang('No professionals available')</h5>
    <p class="text-muted">@lang('Please check back later or adjust your filters.')</p>
</div>
@else
    @foreach ($professionals as $key => $professional)
        <div class="row bg-white px-2 py-3 p-md-3 ms-md-1 @if($key != 0) mt-md-2 @endif shadow-sm border fs-14 rounded-2">
            <div class="col-8 col-md-9 d-flex">
                @if(!is_null($professional->image))
                    <a href="{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}" class="text-decoration-none text-dark">
                        <img src="{{ Storage::url($professional->image) ??  asset('assets/images/default-profile.png')}}" alt="@lang('Profile Image')" class="rounded-circle me-2" width="55" height="55">
                    </a>
                @else
                    <div class="d-flex justify-content-center">
                        <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                            style="height:45px;width:45px;font-size:20px">
                            {{ strtoupper(substr($professional->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($professional->lastname ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                @endif
                <div style="width:400px; margin-left:10px;">
                    <h5 class="mb-0 pt-1">
                        <a href="{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}" class="text-decoration-none text-dark">
                            {{ $professional->username }}
                        </a>
                        <i class="fa-solid fa-circle-check fs-12 text-green"></i>
                    </h5>
                    <p class="fs-12 text-secondary">
                        {{ $professional->designation }}

                        @php
                            $rating = round($professional->average_rating ?? 0);
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $rating)
                                <a href="{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}" class="text-decoration-none text-dark">
                                    <i class="fa-solid fa-star text-warning"></i>
                                </a>
                            @else
                                <a href="{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}" class="text-decoration-none text-dark">
                                    <i class="fa-regular fa-star text-warning"></i>
                                </a>
                            @endif
                        @endfor

                        {{--  <span class="ps-sm-2">{{ number_format($professional->average_rating ?? 0, 1) }} ({{ $professional->completedJobs ?? 0 }})</span>  --}}
                        <span class="ps-sm-2">{{ number_format($professional->average_rating ?? 0, 1) }} ({{ $professional?->completedJobs->count() ?? 0 }})</span>
                    </p>
                </div>
            </div>
            <div class="col-3 col-sm-4 col-lg-3 float-end text-end mt-1">
                <button class="btn btn-sm btn-success bg-green border-0 float-md-end" onclick="window.location.href='{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}'">
                    View Profile
                </button>
            </div>
            <p class="fs-14 text-3gray"> <i class="fas fa-briefcase"></i> Job Success Rate
                <span class="mx-3"> ${{ $professional->per_hour_rate }}/hr <i class="fas fa-map-marker-alt fs-6 ms-3"></i> {{ $professional->country ?? 'Worldwide' }}
            </p>
            <span class="fs-14 mb-1" id="short-description-{{ $key }}">
                {!! Str::limit($professional->about_me,150) !!}
            </span>

            <span id="full-description-{{ $key }}" style="display: none;">
                {!! $professional->about_me !!}
            </span>

            @if (strlen(strip_tags(html_entity_decode($professional->about_me))) > 150)
                <span id="dots-{{ $key }}"></span>
                <a class="links-c" href="javascript:void(0);" onclick="toggleDescription({{ $key }})"
                    id="myBtn-{{ $key }}">Read more</a>
            @endif

            <!-- Dynamic Skills Carousel -->
            <div class="carousel-container p-0">
                <div class="inner-carousel">
                    <div class="track">
                        @if ($professional->profileSkills->isNotEmpty())
                            @foreach ($professional->profileSkills as $skill)
                                <div class="card-container">
                                    <div class="card-c">{{ $skill->skill->name }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="navc">
                        <button class="prev bg-white border-0">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button class="next bg-white border-0">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
<div class="mt-4 ajax-pagination-wrapper">
    {!! $professionals->links() !!}
</div>

