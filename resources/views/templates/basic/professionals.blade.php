@extends('templates.basic.layouts.wiserlance.layout')
@section('content')
    <div class="container mb-5">
        <div class="row pt-3">
            <!--Breadcrumbs-->
            <div class="col-lg-3">
                <p class="text-3gray">
                    @if(Auth::check())
                        @if ($user->is_employer)
                            <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                        @else
                            <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                        @endif
                    @else
                        <a class="text-3gray" href="/"><i class="fa-solid fa-house fs-14"></i></a>
                    @endif
                    <i class="fa-solid fa-chevron-right fs-12"></i><a href="{{ route('professionals') }}" class="text-3gray"
                        style="text-decoration:none;"> Professionals </a>
                </p>
            </div>
            <!--Breadcrumbs End-->
            <div class="col-6 col-lg-4 ps-md-3">
                <!-- Type Skill Filter -->
                <form class="d-flex" role="search" action="{{ route('professionals') }}" method="GET">
                    <input class="form-control bg-white shadow-sm float-end bg-lgreen skill"
                        style="height:30px; font-size: 14px;" type="search" id="skill" name="skill"
                        placeholder="Type Skill" aria-label="Search" value="{{ request('skill') }}">
                    <button class="skill btn btn-sm btn-success ms-1 bg-green border-0" type="submit"
                        style="height: 30px;">
                        <img src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}" width="20">
                    </button>
                </form>
            </div>
            <div class="col-6 col-lg-4 pe-4">
                <!-- Search Professionals by Name Filter -->
                <form class="d-flex" role="search" action="{{ route('professionals') }}" method="GET">
                    <input class="form-control name bg-white shadow-sm float-end bg-lgreen"
                        style="height:30px; font-size: 14px;" type="search" name="name"
                        placeholder="Search Professionals by name" id="name" aria-label="Search"
                        value="{{ request('name') }}">
                    <button class="btn btn-sm ms-1 btn-success bg-green border-0" type="submit" style="height: 30px;">
                        <img src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}" width="20">
                    </button>
                </form>
            </div>
            {{-- <div class="col-6 col-lg-1 mt-3 mt-lg-0">
            <div class="btn-group float-end">
                <a type="button" class="list-group-item dropdown-toggle fs-14" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Sort by: <span class="cl-green">Choose<span> </a>
                <ul class="dropdown-menu fs-14 px-1">
                    <li><a class="dropdown-item" href="#">Date</a></li>
                    <li><a class="dropdown-item" href="#">Job Title</a></li>
                    <li><a class="dropdown-item" href="#"> Job Posted</a></li>
                </ul>
            </div>
        </div> --}}
        </div>
        <!--sideBar xxxxxxxxxxxxxxxxxxxxxxxx-->
        <p class="my-2 mt-lg-1 col-md-6">
            <span class="text-green professionalsCount"> {{ $professionals->total() ?? 0 }} </span>
            professionals available on Wiserlance
        </p>
        <div class="row pt-3">

            @include('templates.basic.professionals_filters')
            <div id="contents" class="col-lg-9 professionals-filter-wrp">
                @if ($professionals->isEmpty())
                    <div class="text-center bg-light py-5">
                        <h5 class="text-secondary">@lang('No professionals available')</h5>
                        <p class="text-muted">@lang('Please check back later or adjust your filters.')</p>
                    </div>
                @else
                    @foreach ($professionals as $key => $professional)
                        <div
                            class="row bg-white px-2 py-3 p-md-3 ms-md-1 @if ($key != 0) mt-md-2 @endif shadow-sm border fs-14 rounded-2">
                            <div class="col-8 col-md-9 d-flex">
                                <a href="{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}"
                                    class="text-decoration-none text-dark">
                                    @if (!is_null($professional->image))
                                        <img src="{{ Storage::url($professional->image) ?? asset('assets/images/default-profile.png') }}"
                                            alt="@lang('Profile Image')" class="rounded-circle me-2" width="45"
                                            height="45">
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                style="height:45px;width:45px;font-size:20px">
                                                {{ strtoupper(substr($professional->firstname ?? 'A', 0, 1)) }}
                                                {{ strtoupper(substr($professional->lastname ?? 'A', 0, 1)) }}
                                            </div>
                                        </div>
                                    @endif
                                </a>
                                <div style="width:400px; margin-left:10px;">
                                    <h5 class="mb-0 pt-1">
                                        <a href="{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $professional->username }}
                                        </a>
                                        @if($professional->persona == 1 || $professional->persona == '1')
                                        <i class="fa-solid fa-circle-check fs-12 text-green"></i>
                                        @endif
                                    </h5>
                                    <p class="fs-12 text-secondary">
                                        {{ $professional->designation }}

                                        {{--  @php
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
                                    @endfor  --}}
                                        @php
                                            $rating = $professional->average_rating ?? 0;
                                            $fullStars = floor($rating);
                                            $hasHalfStar = $rating - $fullStars >= 0.5;
                                        @endphp

                                        @for ($i = 1; $i <= 5; $i++)
                                            <a href="{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}"
                                                class="text-decoration-none text-dark">
                                                @if ($i <= $fullStars)
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                @elseif ($i == $fullStars + 1 && $hasHalfStar)
                                                    <i class="fa-solid fa-star-half-stroke text-warning"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-warning"></i>
                                                @endif
                                            </a>
                                        @endfor

                                        <span class="ps-sm-2">{{ number_format($professional->average_rating ?? 0, 1) }}
                                            ({{ $professional->completedJobs }})
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-lg-3 float-end text-end mt-1">
                                <button class="btn btn-sm btn-success bg-green border-0 float-md-end"
                                    onclick="window.location.href='{{ route('profile', [slug($professional->username), encrypt($professional->id)]) }}'">
                                    View Profile
                                </button>
                            </div>
                            <p class="fs-14 text-3gray"> <i class="fas fa-briefcase"></i> Job Success Rate
                                <span class="mx-3"> ${{ $professional->per_hour_rate }}/hr <i
                                        class="fas fa-map-marker-alt fs-6 ms-3"></i>
                                    {{ $professional->country ?? 'Worldwide' }}
                            </p>
                            <span class="fs-14 mb-1 " id="short-description-{{ $key }}">
                                {!! Str::limit($professional->about_me, 150) !!}
                            </span>

                            <span id="full-description-{{ $key }}" style="display: none;">
                                {!! $professional->about_me !!}
                            </span>

                            @if (strlen(strip_tags(html_entity_decode($professional->about_me))) > 150)
                                <span id="dots-{{ $key }}"></span>
                                <a class="links-c" href="javascript:void(0);"
                                    onclick="toggleDescription({{ $key }})" id="myBtn-{{ $key }}">Read
                                    more</a>
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
            </div>
            <!-- Pagination Links -->
            <div class="mt-4 pagination-wrapper paginationDiv">
                {!! $professionals->links() !!}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const stars = document.querySelectorAll(".rating .star");
        const ratingValue = document.getElementById("ratingValue");
        const resetButton = document.getElementById("resetRating");

        resetButton.addEventListener("click", () => {
            ratingValue.value = null;
            resetStars();
            get_jobs();
        });

        stars.forEach((star, index) => {
            // Hover effect
            star.addEventListener("mouseover", () => {
                resetStars();
                highlightStars(index);
            });

            // Reset when mouse leaves
            star.addEventListener("mouseout", () => {
                resetStars();
                if (ratingValue.value) {
                    highlightStars(ratingValue.value - 1);
                }
            });

            // Click: lock selection
            star.addEventListener("click", () => {
                ratingValue.value = star.dataset.value;
                resetStars();
                highlightStars(index);
                get_jobs();
            });
        });

        function resetStars() {
            stars.forEach(s => {
                s.classList.remove("fa-solid", "text-warning");
                s.classList.add("fa-regular");
            });
        }

        function highlightStars(index) {
            for (let i = 0; i <= index; i++) {
                stars[i].classList.remove("fa-regular");
                stars[i].classList.add("fa-solid", "text-warning");
            }
        }
        document.getElementById('languageSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const languageItems = document.querySelectorAll('.language-list > div');

            languageItems.forEach(item => {
                const label = item.querySelector('label');
                const languageName = label.textContent.toLowerCase();

                if (languageName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
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
        //document.querySelectorAll('#categoryAccordion .accordion-button').forEach(button => {
        //    button.addEventListener('click', function () {
        //        const categoryId = this.getAttribute('category-id');
        //        cat_id = categoryId;
        //        get_jobs();
        //    });
        //});

        document.querySelectorAll('#categoryAccordion .accordion-collapse').forEach(collapseEl => {
            collapseEl.addEventListener('show.bs.collapse', function() {
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
        document.addEventListener("DOMContentLoaded", function() {
            const carousels = document.querySelectorAll(".carousel-container");

            carousels.forEach((carousel) => {
                const track = carousel.querySelector(".track");
                const prev = carousel.querySelector(".prev");
                const next = carousel.querySelector(".next");
                let width = carousel.offsetWidth;
                let index = 0;

                window.addEventListener("resize", function() {
                    width = carousel.offsetWidth;
                    updateButtons();
                });

                function updateButtons() {
                    const maxTranslate = track.scrollWidth - carousel.offsetWidth;
                    const currentTranslate = index * width;

                    if (currentTranslate <= 0) {
                        prev.classList.remove("show");
                    } else {
                        prev.classList.add("show");
                    }

                    if (currentTranslate >= maxTranslate) {
                        next.classList.add("hide");
                    } else {
                        next.classList.remove("hide");
                    }
                }

                next.addEventListener("click", function(e) {
                    e.preventDefault();
                    index += 1;
                    track.style.transform = `translateX(${index * -width}px)`;
                    updateButtons();
                });

                prev.addEventListener("click", function() {
                    index -= 1;
                    track.style.transform = `translateX(${index * -width}px)`;
                    updateButtons();
                });

                updateButtons(); // initial state
            });
        });
        {{--  document.addEventListener("DOMContentLoaded", function () {
    const carousels = document.querySelectorAll(".carousel-container");

    carousels.forEach((carousel) => {
        const track = carousel.querySelector(".track");
        const prev = carousel.querySelector(".prev");
        const next = carousel.querySelector(".next");
        let width = carousel.offsetWidth;
        let index = 0;

        window.addEventListener("resize", function () {
            width = carousel.offsetWidth;
        });

        next.addEventListener("click", function (e) {
            e.preventDefault();
            index += 1;
            prev.classList.add("show");
            track.style.transform = "translateX(" + index * -width + "px)";
            if (track.offsetWidth - index * width < width) {
                next.classList.add("hide");
            }
        });

        prev.addEventListener("click", function () {
            index -= 1;
            next.classList.remove("hide");
            if (index === 0) {
                prev.classList.remove("show");
            }
            track.style.transform = "translateX(" + index * -width + "px)";
        });
    });
});  --}}


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

        $(document).on('click', '.subcategory-checkbox', function() {
            var subcategoryId = $(this).data('id');
            var skillsCatDiv = $('#skill-cat-' + subcategoryId);

            if ($(this).is(':checked')) {
                // Fetch skills for the selected subcategory
                // fetchSkillCategories(subcategoryId, skillsCatDiv);
                fetchSkillsBySubCat(subcategoryId, skillsCatDiv)
            } else {
                // Hide the skills section if the subcategory is unchecked
                skillsCatDiv.hide();
            }
        });

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
                        $('.paginationDiv').addClass('d-none');
                        skillsDiv.html(response.content_data); // Render skills
                        skillsDiv.show(); // Show the skills section
                    } else {
                        alert(response.message);
                        $('.paginationDiv').removeClass('d-none');
                    }
                },
                error: function() {
                    alert('Something went wrong');
                }
            });
        }
        $(document).on('input', '#minhourlyrate,#maxhourlyrate', function() {
            get_jobs();
        });

        $(document).on('input', '.minweeklyhours, .maxweeklyhours', function() {
            get_jobs();
        });


        $(document).on('click',
            '.subcategory-checkbox, .rank-checkbox,.exp_checkboxes, .lang_level-checkbox, .language-checkbox, .fixhour_checkboxes, .lang_level, .otherlangbtn, .delivery_timebtn,.country-checkbox',
            function() {
                get_jobs();
            });
        var languages = [],
            country = [],
            subcategory = [];

        function removeLanguage(item_id) {
            languages = languages.filter(id => id !== item_id);
            $(`.selected-language[data-id="${item_id}"]`).remove();
            $(`.language-checkbox[value="${item_id}"]`).prop('checked', false);
            get_jobs();
        }

        function removeCountry(item_id) {
            country = country.filter(id => id !== item_id);
            $(`.selected-country[data-id="${item_id}"]`).remove();
            $(`.country-checkbox[value="${item_id}"]`).prop('checked', false);
            get_jobs();
        }

        function removeSubCategory(item_id) {
            subcategory = subcategory.filter(id => id !== item_id);
            $(`.selected-subcategory[data-id="${item_id}"]`).remove();
            $(`.subcategory-checkbox[value="${item_id}"]`).prop('checked', false);
            get_jobs();
        }

        $(document).on('click', '.ajax-pagination-wrapper a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            get_jobs(page);
        });

        function get_jobs(page = 1) {
            var keyword = $('.keyword').val();
            var otherlang = $('.otherlang').val();
            var delivery_time = $('.delivery_time').val();
            var min_hourly_rate = $('#minhourlyrate').val();
            var max_hourly_rate = $('#maxhourlyrate').val();

            var min_weekly_hours = $('.minweeklyhours').val();
            var max_weekly_hours = $('.maxweeklyhours').val();
            console.log("min_weekly_hours =>",min_weekly_hours,"max_weekly_hours =>",max_weekly_hours);

            var skill = $('#skill').val();
            var name = $('#name').val();

            var location = $('.location').val();
            var price = $('.pricerange:checked').val();

            var category = [],
                childcategory = [],
                skills = [],
                exp = [],
                fix = [],
                lang_level = [],
                rank = [];

            //
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

            $('.language-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.val();
                    if (!languages.includes(item_id)) {
                        languages.push(item_id);
                        $('#selectedLanguagesContainer').append(
                            `<span class="badge bg-green selected-language" data-id="${item_id}">${$this.next('label').text()} <span class="ms-1" style="cursor:pointer;" onclick="removeLanguage('${item_id}')">&times;</span></span>`
                        );
                    }
                } else {
                    var item_id = $this.val();
                    languages = languages.filter(id => id !== item_id);
                    $(`.selected-language[data-id="${item_id}"]`).remove();
                }
            });

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

            $('.lang_level-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.val();
                    lang_level.push(item_id);
                }
            });
            $('.exp_checkboxes').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    exp.push(item_id);
                }
            });

            $('.rank-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.val();
                    rank.push(item_id);
                }
            });

            $('.fixhour_checkboxes').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    fix.push(item_id);
                }
            });


            $('.category-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    category.push(item_id);
                    // alert(category);
                }
            });

            $('.childcategory-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    childcategory.push($this.val());
                }
            });
            $('.skills-checkbox').each(function(key, value) {
                var $this = $(this);
                if ($this.is(':checked')) {
                    var item_id = $this.attr('name');
                    skills.push(item_id);
                }
            });

            var year = $('#selectedyear').val();
            var minprice = $('#minprice').val();
            var maxprice = $('#maxprice').val();
            var minbudget = $('#minbudget').val();
            var maxbudget = $('#maxbudget').val();
            $("#loading").show();
            $.ajax({
                //url: "{{ url('/filter_pros') }}",
                url: "{{ url('/filter_pros') }}?page=" + page,
                method: "POST",
                //dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'category': category,
                    'subcategory': subcategory,
                    'childcategory': childcategory,
                    'skills': skills,
                    'skill': skill,
                    'name': name,
                    'minbudget': minbudget,
                    'maxbudget': maxbudget,
                    'keyword': keyword,
                    'location': location,
                    'exp': exp,
                    'otherlang': otherlang,
                    'delivery_time': delivery_time,
                    'fix': fix,
                    'lang_level': lang_level,
                    'country': country,
                    'min_hourly_rate': min_hourly_rate,
                    'max_hourly_rate': max_hourly_rate,
                    'cat_id': cat_id,
                    'languages': languages,
                    'rating': ratingValue.value,
                    'rank': rank,
                    'min_weekly_hours': min_weekly_hours,
                    'max_weekly_hours': max_weekly_hours,
                },
                success: function(response) {
                    if (response.status === 'true' && response.count > 0) {
                        $('.paginationDiv').addClass('d-none');
                        $('.professionals-filter-wrp').html(response.professionals); // Update job listings
                        $('.professionalsCount').text(response.total_records);
                    } else {
                        $('.professionals-filter-wrp').html(response.professionals);
                        $('.paginationDiv').removeClass('d-none');
                        $('.professionalsCount').text(response.total_records);
                    }

                    $("#loading").hide();
                },
                error: function(data) {
                    $('.paginationDiv').removeClass('d-none');
                    $('.professionals-filter-wrp').html(`
                        <div class="text-center row bg-white px-2 py-3 p-md-3 shadow-sm border fs-14 rounded-2 my-2 my-md-0">
                            <h5 class="text-danger">Something went wrong</h5>
                            <p class="text-muted">Please try again later.</p>
                        </div>
                    `);
                    $("#loading").hide();
                }
            });
        }
        $(document).ready(function() {
            $('#countrySearch').on('input', function() {
                var searchText = $(this).val().trim().toLowerCase();

                $('.country-list > div').each(function() {
                    var countryName = $(this).find('label').text().toLowerCase();
                    $(this).toggle(countryName.includes(searchText));
                });
            });
        });



        {{--  $('#categorySearch').on('input', function() {
        let searchTerm = $(this).val().trim().toLowerCase();
        console.log('Search term:', searchTerm); // Debug: Log search term

        // Show all categories by default
        $('.accordion-item').show();

        // If search term is empty, reset to initial state
        if (searchTerm === '') {
            $('.accordion-collapse').each(function() {
                let collapse = new bootstrap.Collapse(this, { toggle: false });
                collapse.hide();
            });
            $('.subcategory-checkbox').parent().show();
            console.log('Search cleared, resetting state');
            return;
        }

        // Iterate through each category
        $('.accordion-item').each(function() {
            let category = $(this);
            let hasVisibleSubcategory = false;

            // Find all subcategories within this category
            category.find('.subcategory-checkbox').each(function() {
                let $checkbox = $(this);
                let subcategoryLabel = $checkbox.next('label').text().trim().toLowerCase();
                let subcategoryDiv = $checkbox.parent();

                // Show or hide subcategory based on search match
                if (subcategoryLabel.includes(searchTerm)) {
                    subcategoryDiv.show();
                    hasVisibleSubcategory = true;
                } else {
                    subcategoryDiv.hide();
                }
            });

            // Get the collapse element and button for this category
            let collapseElement = category.find('.accordion-collapse').get(0);
            let collapse = new bootstrap.Collapse(collapseElement, { toggle: false });

            // Auto-expand or collapse category based on visible subcategories
            if (hasVisibleSubcategory) {
                collapse.show();
                console.log('Expanding category:', category.find('.accordion-button').text().trim());
            }
        });
    });  --}}

        {{--  $('#categorySearch').on('input', function() {
        let searchTerm = $(this).val().trim().toLowerCase();
        console.log('Search term:', searchTerm); // Debug: Log search term

        // If search term is empty, reset to initial state
        if (searchTerm === '') {
            $('.accordion-item').show();
            $('.accordion-collapse').each(function() {
                let collapse = new bootstrap.Collapse(this, { toggle: false });
                collapse.hide();
            });
            $('.subcategory-checkbox').parent().show();
            return;
        }

        // Iterate through each category
        $('.accordion-item').each(function() {
            let category = $(this);
            let hasVisibleSubcategory = false;

            // Find all subcategories within this category
            category.find('.subcategory-checkbox').each(function() {
                let $checkbox = $(this);
                let subcategoryLabel = $checkbox.next('label').text().trim().toLowerCase();
                let subcategoryDiv = $checkbox.parent();


                // Show or hide subcategory based on search match
                if (subcategoryLabel.includes(searchTerm)) {
                    subcategoryDiv.show();
                    hasVisibleSubcategory = true;
                } else {
                    subcategoryDiv.hide();
                }
            });

            // Get the collapse element for this category
            let collapseElement = category.find('.accordion-collapse').get(0);
            let collapse = new bootstrap.Collapse(collapseElement, { toggle: false });

            // Show or hide category based on visible subcategories
            if (hasVisibleSubcategory) {
                category.show();
                collapse.show();
                console.log('Showing and expanding category:', category.find('.accordion-button').text().trim());
            } else {
                category.hide();
                //jab koi be match nahi krta ye sab filters hide hojaty hai
                //collapse.hide();
            }
        });
    });  --}}


        {{--  $('#categorySearch').on('input', function() {
    let searchTerm = $(this).val().trim().toLowerCase();

    // If search term is empty, reset to initial state
    if (searchTerm === '') {
        $('.accordion-item').show();
        $('.accordion-collapse').each(function() {
            let collapse = new bootstrap.Collapse(this, { toggle: false });
            collapse.hide(); // or keep as is if you prefer preserving open state
        });
        $('.subcategory-checkbox').parent().show();
        return;
    }

    $('.accordion-item').each(function() {
        let category = $(this);
        let hasMatch = false;

        category.find('.subcategory-checkbox').each(function() {
            let $checkbox = $(this);
            let labelText = $checkbox.next('label').text().trim().toLowerCase();
            let subDiv = $checkbox.parent();

            if (labelText.includes(searchTerm)) {
                subDiv.show();
                hasMatch = true;
            } else {
                subDiv.hide();
            }
        });

        // Always show category header
        category.show();

        // Expand the collapse if a match was found in subcategories
        let collapseElement = category.find('.accordion-collapse').get(0);
        let collapse = new bootstrap.Collapse(collapseElement, { toggle: false });

        if (hasMatch) {
            collapse.show();
        }
    });
});  --}}
    </script>
@endsection
