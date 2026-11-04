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
</style>
@section('content')
    <div class="container mb-5">
        <div class="row">
            <!--Breadcrumbs-->
            <p class=" mb-0 mt-3 text-3gray">
                <a class="text-3gray" href="{{ route('user.buyer.dashboard') }}"><i class="fa-solid fa-house fs-14"></i></a>
                {{-- <i class="fa-solid fa-chevron-right fs-12"></i><a href="/job" class="text-3gray"
                    style="text-decoration:none;"> Jobs </a> --}}
                <i class="fa-solid fa-chevron-right fs-12"></i><a class="text-3gray" style="text-decoration:none;"> About Me
                </a>
            </p>

            <!--Left Section xxxxxxxxxxxxxxxxx-->
            <div class="d-md-flex flex-lg-column col-lg-3 mt-4">

                <!-- Profile Card -->
                <div class="col-md-6 col-lg-12 bg-white p-4 shadow-sm border fs-14 rounded-2 text-center">
                    <div style="display: flex;align-items:center;justify-content:center">
                        @if(!is_null($user->image))
                            <img class="rounded-circle border border-secondary" style="max-width: 80px;max-height: 80px;object-fit: cover;" alt="{{ $user['username'] ?? 'Buyer' }}"
                                src="{{ $user['image'] ? Storage::url($user->image) : asset('assets/images/default-profile.png') }}"
                                width="80" height="80">
                        @else
                            <div class="d-flex justify-content-center">
                                <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                    style="height:80px;width:80px;font-size:20px">
                                     {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <h6 class="mb-0 mt-2">
                        {{ $user['username'] ?? 'Buyer Name' }}
                        @if (array_key_exists('isPersonaVerified', $data) && $data['isPersonaVerified'])
                            <i class="fa-solid fa-circle-check text-green" title="Persona Verified"></i>
                        @endif
                    </h6>
                    <p class="text-secondary pt-0 mb-0">{{ $user['country'] ?? 'Worldwide' }}</p>
                    <p id="time-display" class="text-secondary pt-0 mb-7"></p>

                    {{-- <p class="text-secondary my-1 ps-3 text-start"><i class="fa-solid fa-phone text-green custom-pr-5 "></i>
                            {{ $user?->userCompany?->contact }}</p> --}}
                        {{-- <p class="text-secondary my-1 ps-3"><i class="fa-solid fa-envelope text-green"></i>
                            medicareindustries@com</p> --}}
                        <p class="text-secondary my-1 ps-3 text-start"><i class="fa-solid fa-globe text-green custom-pr-5 "></i>
                            {{ $user?->userCompany?->companyweb }}</p>
                        <p class="text-secondary py-1 ps-3 text-start"><i class="fa-solid fa-map-marker-alt custom-pr-5 text-green"></i>
                            {{ $user?->userCompany?->address }}</p>

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
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Services brought </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold"> {{ $data['servicesBought'] }}</div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Jobs posted </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold"> {{ $data['postedJobs'] }}</div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Completed work </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold"> {{ $data['completedJobs'] }}</div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Pending work </div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">{{ $data['pendingJobs'] }}</div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Member since</div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">
                            {{ showDateTime($user->created_at, 'd M Y') }}
                        </div>
                    </div>

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7 text-start">Payment</div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">
                            {{ $data['isPaymentVerified'] ? 'Verified' : 'Unverified' }}
                        </div>
                    </div>

                    {{--  <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-xl-6 col-lg-7  text-start">Phone number</div>
                        <div class="col-6 col-xl-6 col-lg-5 text-end fw-semibold">
                            {{ $data['isSmsVerified'] ? 'Verified' : 'Unverified' }}
                        </div>
                    </div>  --}}

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
                            <a class="text-decoration-none text-dark tab-link" data-tab="jobs-tab">Jobs</a>
                        </div>
                        <div class="col text-center p-1 p-sm-2 bg-lgreen2 rounded-end border-start border-2 border-white ">
                            <a class="text-decoration-none text-dark tab-link" data-tab="feedback-tab">Feedback</a>
                        </div>
                    </div>
                </div>
                <div id="about-tab" class="tab-content active">
                    <!--Bio-->
                    <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 gap-2 rounded-2">
                        <h5 class="mt-2"> Bio</h5>
                        <p class="fs-14 ps-3">{!! $user['about_me'] !!}</p>
                    </div>

                    <!--Details-->
                    <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 gap-2 rounded-2">
                        <h5 class="mt-2"> Bussiness Details</h5>
                        <h6 class="mb-0 mt-2 ps-3 fs-14">{{ $user?->userCompany?->companyname }}</h6>
                        {{-- <p class="text-secondary pt-0 mb-2 ps-3">Since 2012</p> --}}
                        <p class="fs-14 ps-3 pt-0 mb-2">{!!  $user?->userCompany?->description !!}</p>

                    </div>
                </div>
                <div id="jobs-tab" class="tab-content">
                    @if (count($data['jobs']) <= 0)
                        <div class="text-center bg-light py-5">
                            <h5 class="text-secondary">@lang('No jobs available')</h5>
                            <p class="text-muted">@lang('Please check back later or adjust your filters.')</p>
                        </div>
                    @else
                        @foreach ($data['jobs'] as $key => $job)
                            <div class="bg-white px-3 py-2  mt-3 shadow-sm border fs-14 gap-2 rounded-2">
                                <div class="row mb-0 pt-3">
                                    <h5 class="col-10 col-xl-6 col-lg-7 text-start">{{ $job->title }}</h5>
                                    <div class="col-2 col-xl-6 col-lg-5 text-end fw-semibold"><i
                                            class="fa-regular fa-bookmark fs-16"></i></div>
                                </div>
                                <div class="row mb-2 pt-0 ps-3">
                                    <div class="col-12 col-xl-6 col-lg-7 text-start d-flex flex-row gap-3 text-secondary">
                                        <p class="border-end pe-3"><i class="fa-solid fa-briefcase text-3gray"></i>
                                            {{ $job->exp_type_text }}</p>
                                        <p class="border-end pe-3">
                                            {{ $job->job_type }}</p>
                                        <p><i class="fa-solid fa-dollar-sign fs-6 text-3gray"></i>
                                            {{ number_format($job->amount, 2) }}</p>
                                    </div>
                                    <div class="col-12 col-xl-6 col-lg-5 text-end fw-semibold fs-12">Posted
                                        {{ $job->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <p class="fs-14 ps-3">
                                    <span id="short-description-{{ $key }}">
                                        {{ Str::limit(strip_tags(html_entity_decode($job->description)), 300) }}
                                    </span>
                                    <span id="dots-{{ $key }}">...</span>
                                    <span id="full-description-{{ $key }}" style="display: none;">
                                        {{ strip_tags(html_entity_decode($job->description)) }}
                                    </span>

                                    @if (strlen(strip_tags(html_entity_decode($job->description))) > 300)
                                        <a class="links-c" href="javascript:void(0);" onclick="toggleDescription({{ $key }})"
                                            id="myBtn-{{ $key }}">Read more</a>
                                    @endif
                                </p>
                                <!-- Dynamic Skills Carousel -->
                                <div class="carousel-container p-0">
                                    <div class="inner-carousel">
                                        <div class="track">
                                            @if (!empty($job->skill) && is_array($job->skill))
                                                @foreach ($job->skill as $skill)
                                                    <div class="card-container">
                                                        <div class="card-c">{{ $skill }}</div>
                                                    </div>
                                                @endforeach
                                                @foreach($job->extraSkills as $extraSkill)
                                                    <div class="card-container">
                                                        <div class="card-c">{{ __($extraSkill->name) }}</div>
                                                    </div>
                                                @endforeach
                                            @else
                                            @endif
                                        </div>
                                        <div class="navc">
                                            <button class="prev bg-white border-0"><i class="fa-solid fa-chevron-left"></i></button>
                                            <button class="next bg-white border-0"><i
                                                    class="fa-solid fa-chevron-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1 pt-0 ps-3 border-top border-1">
                                    <div class="col-12 col-md-6 text-start d-flex flex-row gap-3 fs-14 mt-3">
                                        <p><i class="fa-solid fa-check-circle text-green"></i>
                                            {{ $data['isPaymentVerified'] ? 'Verified' : 'Unverified' }}</p>
                                        <p><i class="fa-solid fa-user-friends text-green"></i>
                                            {{ $job->userBiding->count() }} Offers</p>
                                        <p><i class="fa-solid fa-map-marker-alt text-green"></i> {{ $user->country }}</p>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
                                        <button class="btn btn-success bg-green border-0 fs-14"
                                            onclick="location.href='{{ route('job.details', [encrypt($job->id)]) }}';">
                                            View Details <i class="fa-solid fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div id="feedback-tab" class="tab-content">
                    @forelse ($data['reviews'] as $review)
                        <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 gap-2 rounded-2">
                            <div class="d-flex flex-row gap-2 ps-3 align-items-center py-3">
                                <div>
                                    @if(!is_null($review->seller->image))
                                        <img class="rounded-circle border border-secondary img-fluid"
                                            alt="{{ $review->seller->username ?? 'User' }}"
                                            src="{{ $review->seller && $review->seller->image ? Storage::url($review->seller->image) : asset('assets/images/default-profile.png') }}"
                                            width="65" height="65">
                                    @else
                                    <div class="d-flex justify-content-center">
                                        <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                            style="height:45px;width:45px;font-size:18px">
                                            {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 mt-2 d-inline-flex align-items-center">
                                        {{ $review->seller->name ?? 'Anonymous' }}
                                        <span class="fs-10 ms-1 d-inline-flex align-items-center"><i
                                                class="fa-solid fa-star star fs-10"></i>{{ $review->rating }}</span>
                                    </h6>
                                    <p class="text-secondary pt-0 mb-0 fs-12">
                                        {{ $review->seller->designation ?? 'No designation' }}
                                    </p>
                                </div>
                            </div>
                            <p class="fs-14 ps-3 mb-2">{{ $review->review }}</p>
                            <p class="fs-14 ps-3 mt-0"> <strong>For:</strong>
                                {{ $review->job->title ?? 'Service not specified' }}</p>
                            <p class="text-end fs-12 text-secondary">{{ $review->created_at->format('d M Y') }}</p>
                        </div>
                    @empty
                        <div class="text-center bg-light py-5">
                            <h5 class="text-secondary">@lang('No reviews available')</h5>
                            <p class="text-muted">@lang('This user has not received any reviews yet.')</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $data['reviews']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
    @section('script')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Get the timezone offset from the backend
                const timezoneOffset = @json($user->userCompany->timezone); // e.g., "GMT +5:00"

                // Extract the hours and minutes using regex
                const match = timezoneOffset.match(/GMT ([+-])(\d{1,2}):(\d{2})/);
                if (!match) {
                    console.error("Invalid timezone format:", timezoneOffset);
                    return;
                }

                const sign = match[1] === "+" ? 1 : -1; // Determine if it's positive or negative
                const offsetHours = parseInt(match[2], 10);
                const offsetMinutes = parseInt(match[3], 10);

                // Calculate total offset in minutes
                const totalOffsetMinutes = sign * (offsetHours * 60 + offsetMinutes);

                // Current UTC time
                const now = new Date();
                const utcTime = new Date(now.getTime() + now.getTimezoneOffset() * 60000);

                // Adjust UTC time with the offset
                const userTime = new Date(utcTime.getTime() + totalOffsetMinutes * 60000);

                // Determine "hours behind/ahead"
                const localOffsetMinutes = now.getTimezoneOffset(); // Local timezone offset in minutes
                const differenceMinutes = totalOffsetMinutes + localOffsetMinutes;
                const differenceHours = Math.floor(differenceMinutes / 60);
                const differenceText =
                    differenceHours > 0 ?
                        `${differenceHours}h ahead` :
                        `${Math.abs(differenceHours)}h behind`;

                // Format user time without repeating GMT
                const formattedTime = userTime.toLocaleTimeString("en-US", {
                    hour: "2-digit",
                    minute: "2-digit",
                });

                // Update the paragraph with the result
                const timeDisplay = document.getElementById("time-display");
                timeDisplay.textContent = `${formattedTime}(${timezoneOffset}) (${differenceText})`;
            });
            document.addEventListener("DOMContentLoaded", function () {
                // Get all tab links and contents
                const tabLinks = document.querySelectorAll(".tab-link");
                const tabContents = document.querySelectorAll(".tab-content");

                tabLinks.forEach((link) => {
                    link.addEventListener("click", function (e) {
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
            document.addEventListener("DOMContentLoaded", function () {
                // Select all carousels
                const carousels = document.querySelectorAll(".carousel-container");

                carousels.forEach((carousel) => {
                    const track = carousel.querySelector(".track");
                    const prev = carousel.querySelector(".prev");
                    const next = carousel.querySelector(".next");
                    let width = carousel.offsetWidth;
                    let index = 0;

                    // Adjust width on window resize
                    window.addEventListener("resize", function () {
                        width = carousel.offsetWidth;
                    });

                    // Next button functionality
                    next.addEventListener("click", function (e) {
                        e.preventDefault();
                        index += 1;
                        prev.classList.add("show");
                        track.style.transform = "translateX(" + index * -width + "px)";
                        if (track.offsetWidth - index * width < width) {
                            next.classList.add("hide");
                        }
                    });

                    // Previous button functionality
                    prev.addEventListener("click", function () {
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
        </script>
    @endsection
