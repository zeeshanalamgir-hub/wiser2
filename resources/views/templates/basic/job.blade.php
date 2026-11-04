@extends('templates.basic.layouts.wiserlance.layout')
@section('content')
    <style>
        #myInput:focus {
            background-color: #003f45 !important;
        }

        .short-desc {
            display: block;
            line-height: 1.5;
        }

        .full-desc {
            display: none;
            line-height: 1.5;
            white-space: normal;

        }

        /* Preserve formatting for HTML content */
        .full-desc p,
        .full-desc ul,
        .full-desc ol,
        .full-desc li {
            margin-bottom: 0.5rem;
        }

        .full-desc ul,
        .full-desc ol {
            padding-left: 1.5rem;
        }

        .full-desc b,
        .full-desc strong {
            font-weight: bold;
        }

        .links-c {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .links-c:hover {
            text-decoration: underline;
        }
    </style>
    @php
        $user = Auth::user();
    @endphp
    <div class="mb-3">
        <div class="row pt-3">
            <!--Breadcrumbs-->
            <div class="col-lg-3">
                <p class="text-3gray">
                    @if (Auth::check())
                        @if ($user?->is_employer)
                            <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                        @else
                            <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                        @endif
                    @else
                        <a class="text-3gray" href="/"><i class="fa-solid fa-house fs-14"></i></a>
                    @endif
                    <i class="fa-solid fa-chevron-right fs-12"></i> Jobs
                </p>
            </div>
            <!--Breadcrumbs End-->
            <div class="col-12 col-lg-4 ps-md-0">
                <form class="d-flex" role="search">
                    <input class="form-control bg-white shadow-sm float-end bg-lgreen "
                        style="height:30px; font-size: 14px;" type="search" placeholder="Search by Job Title"
                        id="job_title" name="job_title" value="{{ request()?->job_title }}" aria-label="Search">
                    <button class="btn btn-sm btn-success ms-1 bg-green border-0 " type="submit" style="height: 30px;"><img
                            src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}"
                            width="20"></button>
                </form>
            </div>
            <div class="col-8 pt-md-0 pt-2 col-lg-4 pe-4 pe-lg-5">
                <form class="d-flex" role="search">
                    <input class="form-control bg-white shadow-sm float-end bg-lgreen "
                        style="height:30px; font-size: 14px;" type="search" placeholder="Search by Location" id="location"
                        name="location" value="{{ request()?->location }}" aria-label="Search">
                    <button class="btn btn-sm btn-success ms-1 bg-green border-0 " type="submit" style="height: 30px;"><img
                            src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}"
                            width="20"></button>
                </form>
            </div>
            <div class="col-4 col-lg-1 mt-3 mt-lg-0">
                <div class="btn-group float-end">
                    <a type="button" class="list-group-item dropdown-toggle fs-14" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Sort by: <span class="cl-green">Choose<span> </a>
                    <ul class="dropdown-menu fs-14 px-1">
                        <form role="search" method="GET">
                            <input type="hidden" name="sort" id="sortInput">
                            <li><button type="submit" class="dropdown-item" onclick="setSortValue('Date')">Date</button>
                            </li>
                            <li><button type="submit" class="dropdown-item" onclick="setSortValue('JobTitle')">Job
                                    Title</button></li>
                            <li><button type="submit" class="dropdown-item" onclick="setSortValue('JobPosted')">Job
                                    Posted</button></li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
        @php
            // Normalize $jobs to be always a collection for simplicity
            $jobsData = is_array($jobs) && isset($jobs['jobs']) ? collect($jobs['jobs']) : $jobs;
        @endphp
        <!--sideBar xxxxxxxxxxxxxxxxxxxxxxxx-->
        <p class="my-2 mt-lg-1 col-md-6">
            <span class="text-green professionalsCount"> {{ $jobsData->total() ?? 0 }} </span>
            jobs available on Wiserlance
        </p>
        <div class="row pt-1">
            @include('templates.basic.filters')
            <div id="contents" class="col-lg-9 jobs-filter-wrp ">
                @if (empty($jobsData) || count($jobsData) == 0)
                    <div class="text-center row bg-white px-2 py-3 p-md-3 shadow-sm border fs-14 rounded-2 my-2">
                        <h5 class="text-secondary">@lang('No jobs available')</h5>
                        <p class="text-muted">@lang('Please check back later or adjust your filters.')</p>
                    </div>
                @else
                    @foreach ($jobsData as $key => $job)
                        <div
                            class="row bg-white px-2 py-3 p-md-3 shadow-sm border fs-14 rounded-2 my-2 mt-0 mb-2 position-relative">
                            @if ($job->priority == 2)
                                <div class="position-absolute top-0 start-0">
                                    <span class="badge text-white m-2" style="background-color: #4caf50">
                                        <i class="fas fa-star me-1"></i>Featured
                                    </span>
                                </div>
                            @endif

                            <div class="col-8 col-md-9 d-flex mb-1">
                                <a href="{{ route('buyerprofile', [slug(optional($job->user)->username), encrypt(optional($job->user)->id)]) }}"
                                    class="text-decoration-none text-dark">
                                    @if (!is_null($job?->user?->image))
                                        <img src="{{ Storage::url($job?->user?->image) ?? asset('assets/images/default-profile.png') }}"
                                            alt="@lang('Profile Image')" class="rounded-circle me-2" width="45"
                                            height="45" style="object-fit:cover">
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                style="height:45px;width:45px;font-size:20px">
                                                <span style="font-size: 18px">
                                                    {{ strtoupper(substr($job?->user?->firstname ?? 'A', 0, 1)) }}
                                                    {{ strtoupper(substr($job?->user?->lastname ?? 'A', 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                                <div style="width:400px;display:flex;align-items:center;margin-left:10px">
                                    <h6 class="mb-0">
                                        <a href="{{ route('buyerprofile', [slug(optional($job->user)->username), encrypt(optional($job->user)->id)]) }}"
                                            class="text-decoration-none text-dark">
                                            {{ optional($job->user)->username }}
                                        </a>
                                        @if (optional($job->user)->persona == 1 || optional($job->user)->persona == '1')
                                            <i class="fa-solid fa-circle-check text-green" title="Persona Verified"></i>
                                        @endif
                                    </h6>
                                    <p class="fs-12 text-secondary">{{ optional($job->user)->designation }}</p>
                                </div>
                            </div>
                            <div class="col-4 col-md-3 float-end text-end mt-1">
                                <p class="text-secondary fs-12"> <i
                                        class="fa-regular fa-clock me-1 bg-light text-warning"></i>{{ $job->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <h6>
                                <a href="{{ route('buyerprofile', [slug(optional($job->user)->username), encrypt(optional($job->user)->id)]) }}"
                                    class="text-black text-decoration-none">
                                    {{ $job->title }}
                                </a>
                            </h6>
                            <p class="fs-14 text-3gray">
                                <i class="fas fa-briefcase"></i> {{ $job->exp_type_text }} |
                                <i class="fas" style="color:#a2a2a2"></i> {{ $job->job_type }} |
                                <i class="fas"></i> ${{ number_format($job->amount, 2) }}
                            </p>
                            <p class="fs-14 ps-3">
                                <!-- Short description with HTML tags removed but entities decoded -->
                                <span id="short-description-{{ $key }}" class="short-desc">
                                    {{ Str::limit(strip_tags(html_entity_decode($job->description)), 410, '...') }}
                                </span>

                                <!-- Full description with HTML tags preserved -->
                            <div id="full-description-{{ $key }}" class="full-desc" style="display: none;">
                                {!! $job->description !!}
                            </div>

                            @if (strlen(strip_tags($job->description)) > 410)
                                <a class="links-c" href="javascript:void(0);"
                                    onclick="toggleDescription({{ $key }})" id="myBtn-{{ $key }}">
                                    Read more
                                </a>
                            @endif
                            </p>
                            <!-- Dynamic Skills Carousel -->
                            <div class="carousel-container">
                                <div class="inner-carousel">
                                    <div class="track">
                                        @if (!empty($job->jobSkills) && $job->jobSkills->isNotEmpty())
                                            @foreach ($job->jobSkills as $skl)
                                                <div class="card-container">
                                                    <div class="card-c">{{ $skl?->skill?->name }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (!empty($job->extraSkills))
                                            @foreach ($job->extraSkills as $extraSkill)
                                                <div class="card-container">
                                                    <div class="card-c">{{ $extraSkill->name }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="navc">
                                        <button class="prev bg-white border-0"><i
                                                class="fa-solid fa-chevron-left"></i></button>
                                        <button class="next bg-white border-0"><i
                                                class="fa-solid fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-9 col-lg-10">
                                    <p>
                                        @if ($job?->user?->is_payment_verified)
                                            <i class="fas fa-user-check text-green"></i> Verified
                                        @else
                                            <i class="fas fa-user-check text-secondary"></i> Unverified
                                        @endif
                                        <i class="fas fa-user-friends text-green ms-4"></i> {{ $job->user_biding_count }}
                                        Offers
                                        <i class="fas fa-map-marker-alt text-green fs-6 ms-4"></i>
                                        {{ $job?->user?->country }}
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
                @endif
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let cat_id = null;

        function toggleDiv() {
            const myDiv = document.getElementById('myDiv');
            if (myDiv.style.display === 'none') {
                myDiv.style.display = 'block';
            } else {
                myDiv.style.display = 'none';
            }
        }

        document.getElementById('categorySearch').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const categories = document.querySelectorAll('#categoryAccordion .accordion-item');

            categories.forEach(category => {
                const categoryName = category.querySelector('.accordion-button').textContent.toLowerCase();
                if (categoryName.includes(searchValue)) {
                    category.style.display = 'block';
                } else {
                    category.style.display = 'none';
                }
            });

        });
        {{--  document.querySelectorAll('#categoryAccordion .accordion-button').forEach(button => {
            button.addEventListener('click', function () {
                const categoryId = this.getAttribute('category-id');
                cat_id = categoryId;
                get_jobs();
            });
        });  --}}
        document.querySelectorAll('#categoryAccordion .accordion-collapse').forEach(collapseEl => {
            collapseEl.addEventListener('show.bs.collapse', function() {
                // find the related button
                const button = this.previousElementSibling.querySelector('.accordion-button');
                const categoryId = button.getAttribute('category-id');
                cat_id = categoryId;
                get_jobs();
            });

            collapseEl.addEventListener('hide.bs.collapse', function() {
                cat_id = null;
                get_jobs();
            });
        });
        $(".jtype_checkboxes").on("change", function() {
            let selected = [];

            $(".jtype_checkboxes:checked").each(function() {
                selected.push($(this).next("label").text());
            });

            if (selected.length > 0) {
                $("#budgetButton").text(selected.join(", "));
            } else {
                $("#budgetButton").text("Budget"); // default text
            }
        });

        function setSortValue(value) {
            document.getElementById('sortInput').value = value;
        }

        // document.addEventListener("DOMContentLoaded", function () {
        //     // Select all carousels
        //     const carousels = document.querySelectorAll(".carousel-container");

        //     carousels.forEach((carousel) => {
        //         const track = carousel.querySelector(".track");
        //         const prev = carousel.querySelector(".prev");
        //         const next = carousel.querySelector(".next");
        //         let width = carousel.offsetWidth;
        //         let index = 0;

        //         // Adjust width on window resize
        //         window.addEventListener("resize", function () {
        //             width = carousel.offsetWidth;
        //         });

        //         // Next button functionality
        //         next.addEventListener("click", function (e) {
        //             e.preventDefault();
        //             index += 1;
        //             prev.classList.add("show");
        //             track.style.transform = "translateX(" + index * -width + "px)";
        //             if (track.offsetWidth - index * width < width) {
        //                 next.classList.add("hide");
        //             }
        //         });

        //         // Previous button functionality
        //         prev.addEventListener("click", function () {
        //             index -= 1;
        //             next.classList.remove("hide");
        //             if (index === 0) {
        //                 prev.classList.remove("show");
        //             }
        //             track.style.transform = "translateX(" + index * -width + "px)";
        //         });
        //     });
        // });

        document.addEventListener("DOMContentLoaded", function() {

            // Select all carousels
            const carousels = document.querySelectorAll(".carousel-container");

            carousels.forEach((carousel) => {

                const prev = carousel.querySelector(".prev");
                const next = carousel.querySelector(".next");
                const track = carousel.querySelector(".track");

                let index = 0;
                let width = carousel.clientWidth;

                // -----------------------------
                // Resize
                // -----------------------------
                window.addEventListener("resize", function() {

                    width = carousel.clientWidth;

                    index = 0;

                    track.style.transform = "translateX(0px)";

                    prev.classList.remove("show");
                    next.classList.remove("hide");
                });


                // -----------------------------
                // Next Button
                // -----------------------------
                next.addEventListener("click", function(e) {

                    e.preventDefault();

                    // Total amount the track can scroll
                    const maxScroll = track.scrollWidth - carousel.clientWidth;

                    // Calculate next position
                    const nextPosition = Math.min(
                        (index + 1) * width,
                        maxScroll
                    );

                    // Don't increase index unnecessarily
                    if (nextPosition === index * width) {
                        return;
                    }

                    index++;

                    track.style.transform =
                        `translateX(-${nextPosition}px)`;

                    // Show previous button
                    prev.classList.add("show");

                    // Hide next button when reaching the end
                    if (nextPosition >= maxScroll) {
                        next.classList.add("hide");
                    }
                });


                // -----------------------------
                // Previous Button
                // -----------------------------
                prev.addEventListener("click", function(e) {

                    e.preventDefault();

                    index--;

                    if (index < 0) {
                        index = 0;
                    }

                    const position = index * width;

                    track.style.transform =
                        `translateX(-${position}px)`;

                    // Show next button again
                    next.classList.remove("hide");

                    // Hide previous button at first slide
                    if (index === 0) {
                        prev.classList.remove("show");
                    }
                });

            });


            // -----------------------------
            // Read More / Read Less
            // -----------------------------
            function myFunction() {

                const dots = document.getElementById("dots");
                const moreText = document.getElementById("more");
                const btnText = document.getElementById("myBtn");

                if (dots.style.display === "none") {

                    dots.style.display = "inline";
                    btnText.innerHTML = "Read more";
                    moreText.style.display = "none";

                } else {

                    dots.style.display = "none";
                    btnText.innerHTML = "Read less";
                    moreText.style.display = "inline";

                }
            }

            // Make myFunction available to onclick="myFunction()"
            window.myFunction = myFunction;

        });

        function toggleDescription(key) {
            const short = document.getElementById("short-description-" + key);
            const full = document.getElementById("full-description-" + key);
            const btn = document.getElementById("myBtn-" + key);

            if (!short || !full || !btn) return;

            const isHidden = full.style.display === "none" || full.style.display === "";

            if (isHidden) {
                short.style.display = "none";
                full.style.display = "block"; // Use block for better HTML rendering
                btn.textContent = "Read less";
            } else {
                short.style.display = "block";
                full.style.display = "none";
                btn.textContent = "Read more";
            }
        }

        var country = [];

        function removeCountry(item_id) {
            country = country.filter(id => id !== item_id);
            $(`.selected-country[data-id="${item_id}"]`).remove();
            $(`.country-checkbox[value="${item_id}"]`).prop('checked', false);
            get_jobs();
        }

        function fetchSkills(skillcategoryId, skillsDiv) {
            $.ajax({
                url: "{{ url('/filterpro_skills') }}", // Endpoint to fetch skills
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'skillcategory_id': skillcategoryId
                },
                success: function(response) {
                    if (response.status === 'true') {
                        skillsDiv.html(response.content_data); // Render skills
                        skillsDiv.show(); // Show the skills section
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Something went wrong');
                }
            });
        }

        function fetchSkillsBySubCat(subcategoryId, skillsDiv) {
            $.ajax({
                url: "{{ url('/filterpro_skills') }}", // Endpoint to fetch skills
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'subcategory_id': subcategoryId
                },
                success: function(response) {
                    if (response.status === 'true') {
                        skillsDiv.html(response.content_data); // Render skills
                        skillsDiv.show(); // Show the skills section
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Something went wrong');
                }
            });
        }

        function fetchSkillCategories(subcategoryId, skillsDiv) {
            $.ajax({
                url: "{{ url('/filterpro_childcats') }}", // Endpoint to fetch skills
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'subcategory_id': subcategoryId
                },
                success: function(response) {
                    if (response.status === 'true') {
                        skillsDiv.html(response.content_data); // Render skills
                        skillsDiv.show(); // Show the skills section
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Something went wrong');
                }
            });
        }

        $(document).on('click', '.subcategory-checkbox', function() {
            var subcategoryId = $(this).data('id');
            var skillsCatDiv = $('#skill-cat-' + subcategoryId);

            if ($(this).is(':checked')) {
                // Fetch skills for the selected subcategory
                fetchSkillCategories(subcategoryId, skillsCatDiv);
                //fetchSkillsBySubCat(subcategoryId, skillsCatDiv)
            } else {
                // Hide the skills section if the subcategory is unchecked
                skillsCatDiv.hide();
            }
        });

        $(document).on('click', '.skill-cat-checkbox', function() {
            var skillcategoryId = $(this).data('id');
            var skillsDiv = $('#skills-' + skillcategoryId);

            if ($(this).is(':checked')) {
                // Fetch skills for the selected subcategory
                fetchSkills(skillcategoryId, skillsDiv);
            } else {
                // Hide the skills section if the subcategory is unchecked
                skillsDiv.hide();
            }
        });

        $(document).on('click',
            '.subcategory-checkbox, .job_type_checkboxes, .exp_checkboxes, .jtype_checkboxes, .country-checkbox',
            function() {
                get_jobs(); // Call the get_jobs function to filter jobs
            });

        $(document).on('keyup', ' #minbudget,#maxbudget', function() {
            get_jobs();
        });

        $(document).on('keyup', ' #minhoursperweek,#maxhoursperweek', function() {
            get_jobs();
        });
        $(document).on('click', '.ajax-pagination-wrapper a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            get_jobs(page);
        });
        var subcategory = [];

        function removeSubCategory(item_id) {
            subcategory = subcategory.filter(id => id !== item_id);
            $(`.selected-subcategory[data-id="${item_id}"]`).remove();
            $(`.subcategory-checkbox[value="${item_id}"]`).prop('checked', false);
            get_jobs();
        }

        function get_jobs(page = 1) {
            var keyword = $('.keyword').val();
            var delivery_time = $('.delivery_time').val();
            var location = $('.locationss').val();
            var price = $('.pricerange:checked').val();
            var job_title = $('#job_title').val();
            var search_location = $('#location').val();
            var category = [],
                childcategory = [],
                skills = [],
                exp = [],
                jtype = [],
                fix = [],
                lang_level = [],
                otherlang = [],
                job_type = [];


            // Collect selected subcategories
            {{--  $('.subcategory-checkbox').each(function(key, value) {
        var $this = $(this);
        if ($this.is(':checked')) {
            var item_id = $this.attr('name');
            subcategory.push(item_id);
        }
    });  --}}
            $('.subcategory-checkbox').each(function(key, value) {
                // alert("category");
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    if (!subcategory.includes(item_id)) {
                        subcategory.push(item_id);

                        $('#selectedSubCategoryContainer').append(
                            `<span class="badge bg-green selected-category" data-id="${item_id}">${$this.next('label').text()} <span class="ms-1" style="cursor:pointer;" onclick="removeSubCategory('${item_id}')">&times;</span></span>`
                        );
                    }
                } else {
                    var item_id = $this.val();
                    subcategory = subcategory.filter(id => id !== item_id);
                    $(`.selected-category[data-id="${item_id}"]`).remove();
                }
            });
            // Collect selected childcategories
            $('.skill-cat-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    childcategory.push($this.val());
                }
            });

            // Collect selected skills
            $('.skills-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    //var item_id = $this.attr('name');
                    skills.push($this.val());
                }
            });

            // Collect other filters (experience, job type, etc.)
            $('.exp_checkboxes').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    exp.push(item_id);
                }
            });

            $('.jtype_checkboxes').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    jtype.push(item_id);
                }
            });

            $('.job_type_checkboxes').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.val();
                    job_type.push(item_id);
                }
            });

            $('.lang_level').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    lang_level.push(item_id);
                }
            });

            $('.fixhour_checkboxes').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    fix.push(item_id);
                }
            });

            $('.otherlangbtn').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    otherlang.push(item_id);
                }
            });

            {{--  $('.country-checkbox').each(function(key, value) {
        var $this = $(this);
        if ($this.is(':checked')) {
            if (!country.includes(item_id)) {
                country.push($this.val());
                $('#selectedCountryContainer').append(
                    `<span class="badge bg-green selected-country" data-id="${item_id}">${$this.next('label').text()} <span class="ms-1" style="cursor:pointer;" onclick="removeCountry('${item_id}')">&times;</span></span>`
                );
            }else{
                var item_id = $this.val();
                country = country.filter(id => id !== item_id);
                $(`.selected-country[data-id="${item_id}"]`).remove();
            }
        }
    });  --}}
            $('.country-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.val();
                    if (!country.includes(item_id)) {
                        country.push(item_id);
                        $('#selectedCountryContainer').append(
                            `<span class="badge bg-green selected-country" data-id="${item_id}">${$this.next('label').text()} <span class="ms-1" style="cursor:pointer;" onclick="removeCountry('${item_id}')">&times;</span></span>`
                        );
                    }
                } else {
                    var item_id = $this.val();
                    country = country.filter(id => id !== item_id);
                    $(`.selected-country[data-id="${item_id}"]`).remove();
                }
            });

            $('.subcategory-checkbox').each(function(key, value) {
                // alert("category");
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    subcategory.push(item_id);
                }
            });

            var minbudget = $('#minbudget').val();
            var maxbudget = $('#maxbudget').val();
            var minhoursperweek = $('#minhoursperweek').val();
            var maxhoursperweek = $('#maxhoursperweek').val();

            $("#loading").show();

            // Send AJAX request to filter jobs
            $.ajax({
                url: "{{ url('/filterjobs') }}?page=" + page,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'category': category,
                    'subcategory': subcategory,
                    'childcategory': childcategory,
                    'skills': skills,
                    'minbudget': minbudget,
                    'maxbudget': maxbudget,
                    'minhoursperweek': minhoursperweek,
                    'maxhoursperweek': maxhoursperweek,
                    'keyword': keyword,
                    'location': location,
                    'exp': exp,
                    'jtype': jtype,
                    'otherlang': otherlang,
                    'delivery_time': delivery_time,
                    'fix': fix,
                    'lang_level': lang_level,
                    'countries': country,
                    'cat_id': cat_id,
                    'job_title': job_title,
                    'search_location': search_location,
                    'job_type': job_type
                },
                success: function(response) {
                    if (response.status === 'true' && response.count > 0) {
                        $('.professionalsCount').text(response.count || 0);
                        $('.jobs-filter-wrp').html(response.jobs); // Update job listings
                    } else {
                        // Show "No Jobs Found" message inside the div
                        $('.jobs-filter-wrp').html(response.jobs);
                        $('.professionalsCount').text(response.count || 0);
                    }

                    $("#loading").hide();
                },
                error: function(data) {
                    $('.jobs-filter-wrp').html(`
                <div class="text-center row bg-white px-2 py-3 p-md-3 shadow-sm border fs-14 rounded-2 my-2 my-md-0">
                    <h5 class="text-danger">Something went wrong</h5>
                    <p class="text-muted">Please try again later.</p>
                </div>
            `);
                    $("#loading").hide();
                    $('.professionalsCount').text(0);
                }
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            const countrySearch = document.getElementById('countrySearch');
            const countryList = document.querySelector('.country-list');

            countrySearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const countries = countryList.querySelectorAll('.form-check-label');

                countries.forEach(country => {
                    const countryName = country.textContent.toLowerCase();
                    if (countryName.includes(searchTerm)) {
                        country.closest('.mt-2').style.display = 'block'; // Show matching countries
                    } else {
                        country.closest('.mt-2').style.display =
                        'none'; // Hide non-matching countries
                    }
                });
            });
        });

        {{--  document.addEventListener("DOMContentLoaded", function () {
    const categorySearch = document.getElementById('categorySearch');
    const categoryItems = document.querySelectorAll('.accordion-item');

    categorySearch.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        categoryItems.forEach(category => {
            const categoryName = category.querySelector('.accordion-button').textContent.toLowerCase();
            const subcategories = category.querySelectorAll('.form-check-label');

            if (categoryName.includes(searchTerm)) {
                category.style.display = 'block'; // Show matching categories
                subcategories.forEach(subcategory => subcategory.closest('.mt-1').style.display = 'block'); // Show all subcategories under matching categories
            } else {
                // Hide non-matching categories
                category.style.display = 'none';

                // Check if any subcategory matches the search term
                let hasMatchingSubcategory = false;
                subcategories.forEach(subcategory => {
                    const subcategoryName = subcategory.textContent.toLowerCase();
                    if (subcategoryName.includes(searchTerm)) {
                        subcategory.closest('.mt-1').style.display = 'block'; // Show matching subcategories
                        hasMatchingSubcategory = true;
                    } else {
                        subcategory.closest('.mt-1').style.display = 'none'; // Hide non-matching subcategories
                    }
                });

                // Show the category if any of its subcategories match the search term
                if (hasMatchingSubcategory) {
                    category.style.display = 'block';
                }
            }
        });
    });
});  --}}
        document.addEventListener("DOMContentLoaded", function() {
            const categorySearch = document.getElementById('categorySearch');
            // Only target the categories inside the #categoryAccordion
            const categoryItems = document.querySelectorAll('#categoryAccordion .accordion-item');

            categorySearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                categoryItems.forEach(category => {
                    const categoryName = category.querySelector('.accordion-button').textContent
                        .toLowerCase();
                    const subcategories = category.querySelectorAll('.form-check-label');

                    if (categoryName.includes(searchTerm)) {
                        category.style.display = 'block'; // Show matching categories
                        subcategories.forEach(subcategory => {
                            const wrapper = subcategory.closest('.mt-1');
                            if (wrapper) wrapper.style.display = 'block';
                        });
                    } else {
                        // Check if any subcategory matches the search term
                        let hasMatchingSubcategory = false;
                        subcategories.forEach(subcategory => {
                            const subcategoryName = subcategory.textContent.toLowerCase();
                            const wrapper = subcategory.closest('.mt-1');
                            if (subcategoryName.includes(searchTerm)) {
                                if (wrapper) wrapper.style.display = 'block';
                                hasMatchingSubcategory = true;
                            } else {
                                if (wrapper) wrapper.style.display = 'none';
                            }
                        });

                        // Show or hide the category based on subcategory matches
                        category.style.display = hasMatchingSubcategory ? 'block' : 'none';
                    }
                });
            });
        });
    </script>
@endsection
