@extends($activeTemplate . 'layouts.wiserlance.layout')
<style>
    .tab-content {
        display: none;
        /* Hide all tab content by default */
    }

    .tab-content.active {
        display: block;
        /* Show active tab content */
    }

    .tab-link {
        cursor: pointer;
    }

    .testimonial-item {
        transition: opacity 0.5s ease-in-out;
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>
@section('content')
    <div class="container mb-5">
        <div class="row">
            <!--Breadcrumbs-->
            <p class=" mb-0 mt-3 text-3gray">
                <a class="text-3gray" href="{{ route('user.home') }}"><i class="fa-solid fa-house fs-14"></i></a>
                <i class="fa-solid fa-chevron-right fs-12"></i><a href="{{ route('user.home') }}" class="text-3gray"
                    style="text-decoration:none;"> Profile View </a>
                <i class="fa-solid fa-chevron-right fs-12"></i><a class="text-3gray tab-content-tag"
                    style="text-decoration:none;"> About
                </a>
            </p>

            <!--Left Section xxxxxxxxxxxxxxxxx-->
            <div class="d-md-flex flex-lg-column col-lg-3 mt-4">

                <!-- Profile Card -->
                <div class="col-md-6 col-lg-12 bg-white p-4 shadow-sm border fs-14 rounded-2 text-center">
                    <span class="badge rounded-pill bg-green p-2 position-absolute"> </span>
                    @if (!is_null($user?->image))
                        <div class="d-flex justify-content-center">
                            <img class="rounded-circle border border-secondary img-fluid"
                                alt="{{ $user['username'] ?? 'Seller' }}"
                                src="{{ $user['image'] ? Storage::url($user->image) : asset('assets/images/default-profile.png') }}"
                                style="width: 80px;height:80px;object-fit: cover;">
                        </div>
                    @else
                        <div class="d-flex justify-content-center">
                            <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                style="height:80px;width:80px;font-size:20px">
                                {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }}
                                {{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                    @endif
                    <h6 class="mb-0 mt-2">
                        {{ $user['username'] ?? 'Seller Name' }}
                        @if (array_key_exists('isPersonaVerified', $data) && $data['isPersonaVerified'])
                            <i class="fa-solid fa-circle-check text-green" title="Persona Verified"></i>
                        @endif
                    </h6>
                    <p class="text-secondary pt-0 mb-0">{{ $user['designation'] }}</p>
                    <p class="text-secondary pt-0 mb-0">{{ $user['country'] ?? 'Worldwide' }}</p>
                    <div class="col">
                        <div class="text-center fs-12">
                            <i class="fa fa-star star"></i>
                            {{ number_format($user->average_rating, 1) }}
                            ({{ $user->review_count }} {{ Str::plural('review', $user->review_count) }})

                        </div>
                    </div>
                    <div class="col-12 d-flex flex-column align-items-center mt-3">
                        @if (Auth::check() && Auth::user()?->is_employer == '1')
                            <button class="btn btn-sm btn-success bg-green border-0 w-75 mb-2 fw-semibold"
                                onclick="window.location.href='{{ route('professioan.hire', [encrypt($user->id)]) }}'">Hire
                                Me
                            </button>
                        @endif
                        @if (Auth::check() && Auth::id() != $user->id)
                            <a href="/chatify" class="btn btn-sm  btn-success bg-dgreen2 border-0 w-75 mb-2 fw-semibold"> <i
                                    class="fa fa-paper-plane"></i> Contact Me</a>
                        @endif
                    </div>
                    {{-- <div class="col-12 d-flex flex-column align-items-center mt-3">
                        <a href="{{ route('user', ['id' => $user->id]) }}" target="_blank"
                            class="btn btn-success bg-green border-0 w-75 mb-2 fw-semibold">
                            <i class="fa fa-paper-plane"></i> Contact Me
                        </a>
                    </div> --}}
                </div>
                <!-- Experience Box -->
                <div
                    class="bg-white mt-lg-3 mt-3 mt-md-0 ms-md-2 ms-lg-0  p-4 shadow-sm border fs-14 rounded-2 text-center col-md-6 col-lg-12">

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Total Service </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold"> {{ $data['totalServices'] }}</div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Inprogress jobs </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold"> {{ $data['inprogressJobs'] }}</div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Completed jobs </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold"> {{ $data['completedJobs'] }}</div>
                    </div>

                    {{-- <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Pending work </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">{{ $data['pendingJobs'] }}</div>
                    </div> --}}

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Member since</div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">
                            {{ showDateTime($user->created_at, 'd M Y') }}
                        </div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">User ID</div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">
                            {{ $data['isVerified'] ? 'Verified' : 'Unverified' }}
                        </div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7  text-start">Phone number</div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">
                            {{ $data['isSmsVerified'] ? 'Verified' : 'Unverified' }}
                        </div>
                    </div>

                    <div class="row border-bottom pb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Email</div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">
                            {{ $data['isEmailVerified'] ? 'Verified' : 'Unverified' }}
                        </div>
                    </div>
                </div>
            </div>

            <!--Right Section---xxxxxxxxxxxxxxx-->
            <div class="col-lg-9 mt-4" id="contents">

                <!--Top Button-Bar-->
                <div class="ms-3 rounded-2">
                    <div class="row w-100">
                        <div class="col text-center p-1 p-sm-2 py-sm-2 bg-dgreen rounded-start">
                            <a class="text-decoration-none text-white  tab-link " data-tab="about-tab">About Me</a>
                        </div>
                        <div class="col text-center p-1 p-sm-2 bg-lgreen2 border-start border-2 border-white">
                            <a class="text-decoration-none text-dark tab-link" data-tab="services-tab">Services</a>
                        </div>
                        <div class="col text-center p-1 p-sm-2 bg-lgreen2 border-start border-2 border-white">
                            <a class="text-decoration-none text-dark tab-link" data-tab="portfolio-tab">Portfolio</a>
                        </div>
                        <div class="col text-center p-1 p-sm-2 bg-lgreen2 rounded-end border-start border-2 border-white ">
                            <a class="text-decoration-none text-dark tab-link" data-tab="feedback-tab">Feedback</a>
                        </div>
                    </div>
                </div>
                <div id="about-tab" class="tab-content active">
                    <!--Bio-->
                    @if (!is_null($user['about_me']))
                        <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 gap-2 rounded-2">
                            <h5 class="mt-2"> Bio</h5>
                            <p class="fs-14 ps-3">{!! $user['about_me'] !!}</p>
                        </div>
                    @endif

                    @if (count($user->profileSkills) > 0)
                        <!-- Skills -->
                        <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                            <h5 class="mt-2">Skills</h5>
                            <div class="d-flex flex-wrap mt-3 ps-3">
                                @forelse ($user->profileSkills as $profileSkill)
                                    <span class="tags0 me-2 mb-2">{{ $profileSkill->skill->name }}</span>
                                @empty
                                    <span class="text-muted">No skills listed</span>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!--Education-->
                    <!-- Education -->
                    @if (count($user->activeCertificates) > 0)
                        <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                            <h5 class="mt-2">Education</h5>
                            @forelse ($user->activeEducations as $education)
                                <div class="row ps-3 pb-3 mb-3 border-bottom">
                                    <div class="col">
                                        <div class="col-12 text-start d-flex flex-column">
                                            <span>{{ $education->institue }}, {{ $education->countryinstitue }}</span>
                                            <span>{{ $education->degree }}</span>
                                            <span>{{ $education->yeardegree }}</span>
                                        </div>
                                    </div>
                                    @if (!is_null($education->degrecertificate))
                                        <div class="col-6 text-end">
                                            <a href="{{ Storage::url($education->degrecertificate) }}" download>
                                                <img src="{{ asset('assets/images/pdfIcon.png') }}" width="50px"
                                                    alt="pdf">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted ps-3">No education records found</p>
                            @endforelse
                        </div>
                    @endif

                    @if (count($user->activeCertificates) > 0)
                        <!-- Certifications -->
                        <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                            <h5 class="mt-2">Certification</h5>
                            @forelse ($user->activeCertificates as $certification)
                                <div class="row ps-3 pb-2 mb-3 border-bottom">
                                    <div class="col">
                                        <div class="col-12 text-start d-flex flex-column">
                                            <span>{{ $certification->namecertificate }}</span>
                                            <span>{{ $certification->nameinstitue }}</span>
                                            <span>{{ $certification->yearcertificate }}</span>
                                        </div>
                                    </div>
                                    @if (!is_null($certification->certificate))
                                        <div class="col-6 text-end">
                                            <a href="{{ Storage::url($certification->certificate) }}" download>
                                                <img src="{{ asset('assets/images/pdfIcon.png') }}" width="50px"
                                                    alt="pdf">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted ps-3">No certifications available</p>
                            @endforelse
                        </div>
                    @endif


                    <!-- Languages -->
                    @if (count($user->languages) > 0)
                        <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                            <h5 class="mt-2">Languages</h5>
                            <div class="ps-3">
                                <div class="row fs-14 pb-2">
                                    @forelse ($user->languages as $language)
                                        <div class="col-3 text-start mb-2">{{ $language->name }}</div>
                                        <div class="col-3 text-secondary mb-2 text-start">{{ $language->type }}</div>
                                    @empty
                                        <p class="text-muted">No languages added</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div id="services-tab" class="tab-content">
                    @if (count($data['services']) <= 0)
                        <div class="text-center bg-light py-5">
                            <h5 class="text-secondary">@lang('No jobs available')</h5>
                            <p class="text-muted">@lang('Please check back later or adjust your filters.')</p>
                        </div>
                    @else
                        <div class="fs-14">
                            <div class="row">
                                @foreach ($data['services'] as $key => $job)
                                    <div class="col-md-4 mt-4">
                                        <div class="card shadow-sm border">

                                            <img src="{{ getImage('assets/images/service/' . $job->image, imagePath()['service']['size']) }}"
                                                class="card-img-top p-2 pb-0" alt="{{ $job->title }}"
                                                style="height:165px;object-fit: cover;">
                                            <div class="card-body">
                                                <h6 class="fs-12 mb-2">
                                                    <a href="{{ route('job.details', [encrypt($job->id)]) }}"
                                                        class="text-dark text-decoration-none">
                                                        {{ Str::limit($job->title, 70) }}
                                                    </a>
                                                </h6>
                                                <p class="card-text fw-semibold fs-12 mb-1">
                                                    <i class="fa fa-star star"></i>
                                                    {{ number_format($job->rating ?? 0, 1) }}
                                                    ({{ $job->reviews_count ?? 0 }})
                                                    <br>
                                                    Starting From:
                                                    <strong
                                                        class="text-green">${{ number_format($job?->latestDetail->price, 2) }}</strong>
                                                </p>


                                                @if (!empty($job->skill) && is_array($job->skill))
                                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                                        @foreach ($job->skill as $skill)
                                                            <span
                                                                class="badge bg-light border text-dark fs-11">{{ $skill }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>


                <div id="portfolio-tab" class="tab-content">
                    <div class="fs-14 ">
                        <div class="row">
                            @foreach ($user->portfolioprofile as $portfolio)
                                <div class="col-md-4 mt-4">

                                    <div class="card shadow-sm border cursor-pointer" data-bs-toggle="modal"
                                        data-bs-target="#portfolioModal" data-title="{{ $portfolio->title }}"
                                        data-image="{{ Storage::url($portfolio->image) }}"
                                        data-id="{{ $portfolio->id }}">
                                        <div id="desc-{{ $portfolio->id }}" class="d-none">
                                            {!! $portfolio->description !!}
                                        </div>

                                        <img src="{{ Storage::url($portfolio->image) }}" class="card-img-top p-2"
                                            alt="{{ $portfolio->title }}">

                                        <div class="card-body d-flex justify-content-between align-items-start mb-0">
                                            <h6 class="fs-12 fw-bold">
                                                {{ \Illuminate\Support\Str::words($portfolio->title, 10, '...') }}</h6>
                                            <h6 class="text-end fs-10">View more <i
                                                    class="fa fa-arrow-right text-green"></i></h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="feedback-tab" class="tab-content">
                    <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 gap-2 rounded-2">
                        <h5 class="mt-2">{{ $data['totalReviews'] }} Reviews</h5>
                        <div class="row mb-3 ps-3">
                            <!-- Left (Overall Rating & Star Breakdown) -->
                            <div class="col-6 col-xl-6 col-lg-7 text-start">
                                <div class="col mb-3">
                                    <div class="text-start fw-semibold">Overall Rating</div>
                                    <div class="text-start fw-bold d-flex align-items-center">
                                        {{ $data['averageRating'] }}
                                        @for ($i = 0; $i < floor($data['averageRating']); $i++)
                                            <i class="fa fa-star star fs-10 ms-1"></i>
                                        @endfor
                                        @for ($i = 0; $i < 5 - floor($data['averageRating']); $i++)
                                            <i class="fa fa-star star fs-10 text-muted"></i>
                                        @endfor
                                    </div>
                                </div>

                                @foreach ($data['starCounts'] as $stars => $count)
                                    <div class="d-flex align-items-center mb-2 fw-semibold">
                                        <span class="d-flex align-items-center me-2 rating-text">{{ $stars }}
                                            <i class="fa fa-star star fs-12"></i>
                                        </span>
                                        <div class="progress flex-grow-1 mx-2">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $data['ratingPercentages'][$stars] }}%;"
                                                aria-valuenow="{{ $data['ratingPercentages'][$stars] }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="rating-text">({{ $count }})</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Right (Rating Breakdown) -->
                            <div class="col-6 col-xl-6 col-lg-7 text-start">
                                <div class="col mb-3">
                                    <div class="text-start fw-semibold">Rating Breakdown</div>
                                    <div class="text-start fw-bold d-flex align-items-center">
                                        {{ number_format($data['averageRating'], 1) }}
                                        @for ($i = 0; $i < floor($data['averageRating']); $i++)
                                            <i class="fa fa-star star fs-10 ms-1"></i>
                                        @endfor
                                        @for ($i = 0; $i < 5 - floor($data['averageRating']); $i++)
                                            <i class="fa fa-star star fs-10 text-muted"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="row mb-2 fw-semibold">
                                    <div class="col-6 text-start">Skilled</div>
                                    <div class="col-6 text-end"><i class="fa fa-star star fs-12"></i>
                                        {{ number_format($data['reviews']->avg('skill') ?? 0, 1) }}
                                    </div>
                                </div>
                                <div class="row mb-2 fw-semibold">
                                    <div class="col-6 text-start">Seller Communication</div>
                                    <div class="col-6 text-end"><i class="fa fa-star star fs-12"></i>
                                        {{ number_format($data['reviews']->avg('communicationrating') ?? 0, 1) }}
                                    </div>
                                </div>
                                <div class="row mb-2 fw-semibold">
                                    <div class="col-6 text-start">Recommended to others</div>
                                    <div class="col-6 text-end"><i class="fa fa-star star fs-12"></i>
                                        {{ number_format($data['reviews']->avg('recommendrating') ?? 0, 1) }}
                                    </div>
                                </div>
                                <div class="row mb-2 fw-semibold">
                                    <div class="col-6 text-start">Services as described</div>
                                    <div class="col-6 text-end"><i class="fa fa-star star fs-12"></i>
                                        {{ number_format($data['reviews']->avg('describedrating') ?? 0, 1) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client's Testimonials -->
                    <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 gap-2 rounded-2">
                        <h5 class="mt-3 text-center">Client's Testimonials</h5>
                        <div id="testimonial-carousel">
                            @foreach ($data['reviews'] as $index => $review)
                                <div class="testimonial-item {{ $index === 0 ? 'active' : 'd-none' }}">
                                    <p class="text-center mt-3">
                                        "{{ $review->review }}"
                                    </p>
                                    <div class="d-flex justify-content-center align-items-center p-4 text-center">
                                        <span class="me-5 cursor-pointer" onclick="showPreviousTestimonial()">
                                            <i class="fa fa-chevron-left text-green fs-18"></i>
                                        </span>
                                        <div>
                                            @if (!is_null($review->user->image))
                                                <img class="rounded-circle border border-secondary img-fluid"
                                                    alt="{{ optional($review->user)->name ?? 'Client' }}"
                                                    src="{{ Storage::url($review->user->image) ?? asset('assets/images/default-profile.png') }}"
                                                    width="75" height="75">
                                            @else
                                                <div class="d-flex justify-content-center">
                                                    <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                        style="height:80px;width:80px;font-size:20px">
                                                        {{ strtoupper(substr($review->user->firstname ?? 'A', 0, 1)) }}
                                                        {{ strtoupper(substr($review->user->lastname ?? 'A', 0, 1)) }}
                                                    </div>
                                                </div>
                                            @endif
                                            <h6 class="mb-0 mt-2">{{ optional($review->user)->name ?? 'Anonymous' }}</h6>
                                            <p class="fs-12 text-secondary pt-0 mb-2">
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                    <i class="fa fa-star star fs-12"></i>
                                                @endfor
                                                @for ($i = 0; $i < 5 - $review->rating; $i++)
                                                    <i class="fa fa-star star fs-12 text-muted"></i>
                                                @endfor
                                            </p>
                                        </div>
                                        <span class="ms-5 cursor-pointer" onclick="showNextTestimonial()">
                                            <i class="fa fa-chevron-right text-green fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="portfolioModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <img id="modalImage" class="img-fluid mb-3 rounded" />
                        </div>
                        <div class="col-md-6">
                            <p id="modalDescription" class="text-start"></p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        let currentTestimonialIndex = 0;
        const testimonialItems = document.querySelectorAll('.testimonial-item');

        function showTestimonial(index) {
            // If testimonialItems is undefined or not an array, do nothing
            if (!testimonialItems || !Array.isArray(testimonialItems) && !(testimonialItems instanceof NodeList)) return;
            // If index is invalid, do nothing
            if (index < 0 || index >= testimonialItems.length) return;

            // Hide all testimonials
            testimonialItems.forEach(item => item.classList.add('d-none'));

            // Show the selected testimonial, only if it exists
            if (testimonialItems[index]) {
                testimonialItems[index].classList.remove('d-none');
            }
        }


        function showNextTestimonial() {
            currentTestimonialIndex = (currentTestimonialIndex + 1) % testimonialItems.length;
            showTestimonial(currentTestimonialIndex);
        }

        function showPreviousTestimonial() {
            currentTestimonialIndex = (currentTestimonialIndex - 1 + testimonialItems.length) % testimonialItems.length;
            showTestimonial(currentTestimonialIndex);
        }

        // Initialize the first testimonial
        showTestimonial(currentTestimonialIndex);
        document.addEventListener("DOMContentLoaded", function() {
            // Get all tab links and contents
            const tabLinks = document.querySelectorAll(".tab-link");
            const tabContents = document.querySelectorAll(".tab-content");

            tabLinks.forEach((link) => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();

                    // Get the target tab
                    const targetTab = this.dataset.tab;

                    // Remove active class and reset tab link background
                    tabLinks.forEach((link) => {
                        link.classList.remove("text-white");
                        link.classList.add("text-dark");
                        link.parentElement.classList.remove("bg-dgreen");
                        link.parentElement.classList.add("bg-lgreen2");
                    });

                    // Add active class to the clicked tab and its parent
                    this.parentElement.classList.add("bg-dgreen");
                    this.classList.add("text-white");

                    this.parentElement.classList.remove("bg-lgreen2");

                    // Hide all tab contents
                    tabContents.forEach((content) => {
                        content.classList.remove("active");
                    });

                    // Show the target tab content
                    document.getElementById(targetTab).classList.add("active");
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Select all carousels
            const carousels = document.querySelectorAll(".carousel-container");

            carousels.forEach((carousel) => {
                const track = carousel.querySelector(".track");
                const prev = carousel.querySelector(".prev");
                const next = carousel.querySelector(".next");
                let width = carousel.offsetWidth;
                let index = 0;

                // Adjust width on window resize
                window.addEventListener("resize", function() {
                    width = carousel.offsetWidth;
                });

                // Next button functionality
                next.addEventListener("click", function(e) {
                    e.preventDefault();
                    index += 1;
                    prev.classList.add("show");
                    track.style.transform = "translateX(" + index * -width + "px)";
                    if (track.offsetWidth - index * width < width) {
                        next.classList.add("hide");
                    }
                });

                // Previous button functionality
                prev.addEventListener("click", function() {
                    index -= 1;
                    next.classList.remove("hide");
                    if (index === 0) {
                        prev.classList.remove("show");
                    }
                    track.style.transform = "translateX(" + index * -width + "px)";
                });
            });
        });

        function toggleDescription(key) {
            var dots = document.getElementById("dots-" + key);
            var shortDescription = document.getElementById("short-description-" + key);
            var fullDescription = document.getElementById("full-description-" + key);
            var btnText = document.getElementById("myBtn-" + key);

            if (dots.style.display === "none") {
                dots.style.display = "inline";
                shortDescription.style.display = "inline";
                fullDescription.style.display = "none";
                btnText.innerHTML = "Read more";
            } else {
                dots.style.display = "none";
                shortDescription.style.display = "none";
                fullDescription.style.display = "inline";
                btnText.innerHTML = "Read less";
            }
        }
        $(document).on('click', '.tab-link', function() {
            $('.tab-content-tag').text(($(this).text()));
        })

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('portfolioModal');
            modal.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget;
                let title = button.getAttribute('data-title');
                let image = button.getAttribute('data-image');


                document.getElementById('modalTitle').innerText = title;
                document.getElementById('modalImage').src = image;

                let id = button.getAttribute('data-id');
                let description = document.getElementById('desc-' + id).innerHTML;

                document.getElementById('modalDescription').innerHTML = description;
            });
        });
    </script>
@endsection
