@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job-withoutsidebar')
@section('content')
    <p class="mb-0 mt-3 text-3gray">
        @if (Auth::check())
            @if ($user?->is_employer)
                <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
            @else
                <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
            @endif
        @else
            <a class="text-3gray" href="/"><i class="fa-solid fa-house fs-14"></i></a>
        @endif
        <i class="fa-solid fa-chevron-right fs-12"></i> Services
    </p>
    <div id="contents" class="col-12 my-2">
        <div class="row pb-lg-3">
            <div class="col-6 col-lg-5 ps-md-3">
                <form class="d-flex" role="search" method="GET" action="{{ route('service') }}">
                    <input class="form-control bg-white shadow-sm float-end bg-lgreen "
                        style="height:30px; font-size: 14px;" type="search" name="title" value="{{ request('title') ?? request('search') }}"
                        placeholder="Search by Service" aria-label="Search">
                    <button class="btn btn-sm btn-success ms-1 bg-green border-0 " type="submit" style="height: 30px;"><img
                            src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}"
                            width="20"></button>
                </form>
            </div>
            <div class="col-6 col-lg-5">
                <form class="d-flex" role="search" method="GET" action="{{ route('service') }}">
                    <input class="form-control bg-white shadow-sm float-end bg-lgreen "
                        style="height:30px; font-size: 14px;" type="search" name="location"
                        value="{{ request('location') }}" placeholder="Search by Country" aria-label="Search">
                    <button class="btn btn-sm ms-1 btn-success bg-green border-0" type="submit" style="height: 30px;"><i
                            class="fas fa-search-location fs-6 text-white"></i></button>
                </form>
            </div>

            <div class="col-lg-2 md-0 mt-3 mt-lg-0">
                <div class="btn-group float-end">
                    <a type="button" class="list-group-item dropdown-toggle fs-14" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Sort by:
                        <span class="cl-green">
                            @switch(request('sort'))
                                @case('date')
                                    Newest First
                                @break

                                @case('price_asc')
                                    Price: Low to High
                                @break

                                @case('price_desc')
                                    Price: High to Low
                                @break

                                @default
                                    Choose
                            @endswitch
                        </span>
                    </a>
                    <ul class="dropdown-menu fs-14 px-1">
                        <li>
                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'date']) }}">
                                Newest First
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">
                                Price: Low to High
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">
                                Price: High to Low
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ request()->fullUrlWithQuery(['sort' => null]) }}">
                                Reset
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- <p class="my-2 mt-lg-1 col-md-6"> <span class="text-green "> {{ $professionalCount }} </span>professionals available
            on Wiserlance</p> --}}
        @include('templates.basic.service_filters')
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function left_menu_ajax_call(param = null, param_id = null) {
            $.ajax({
                url: "{{ url('/filters_left_menu') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'param': param,
                    'param_id': param_id
                },
                success: function(response) {
                    if (response != 0) {
                        var result = jQuery.parseJSON(response);
                        if (result.status == 'true') {
                            if (param == 'category') {
                                $('.subcategory-div').show();
                                $('.filteredsubs').append(result.content_data);
                            } else if (param == 'subcategory') {
                                $('.filteredchild').append(result.content_data);
                            }
                        } else {
                            alert(result.message);
                        }
                        get_jobs();
                        $("#loading").hide();
                    } else {
                        $("#loading").hide();
                        alert('Something went wrong');
                    }
                },
                error: function() {
                    $("#loading").hide();
                }
            });
        }

        $(document).on('click', '.category-checkbox', function() {
            var $this = $(this);
            var category_id = $this.val();
            var id = $this.attr('data-id');

            if (category_id == id) {
                $('.category-list').addClass("hide");
                $('#category-list-' + id).removeClass("hide");
            } else {
                $('.category-list').removeClass("hide");
            }

            if ($this.is(':checked')) {
                left_menu_ajax_call('category', category_id);
            } else {
                $('.subcategory-checkboxes-' + category_id).parent().remove();
                $('.childcategory-div-category-' + category_id).remove();
                $('.category-list').removeClass("hide");
            }

            if ($('.subcategory-checkbox').length == 0) {
                $('.subcategory-div').hide();
            }
        });

        $(document).on('click', '.subcategory-checkbox', function() {
            var $this = $(this);
            var subcategory_id = $this.val();
            var sid = $this.attr('data-id');

            if (subcategory_id == sid) {
                $('.subcategory-list').addClass("hide");
                $('#s-list-' + sid).removeClass("hide");
            }

            if ($this.is(':checked')) {
                left_menu_ajax_call('subcategory', subcategory_id);
            } else {
                $('.childcategory-div-subcategory-' + subcategory_id).remove();
                $('.subcategory-list').removeClass("hide");
            }
        });

        // Event listeners for all filter inputs
        $(document).on('click',
            '.category-checkbox, .subcategory-checkbox, .childcategory-checkbox, .skills-checkbox, .keywordbtn, .locbtn, .delivery_time, .exp_checkboxes, .lang_level, .otherlangbtn',
            function() {
                get_jobs();
            });

        $(document).on('keyup', '#minbudget, #maxbudget', function() {
            get_jobs();
        });

        $(document).on('change', '.delivery_time', function() {
            get_jobs();
        });

        $(document).on('change', '.exp_checkboxes', function() {
            get_jobs();
        });

        $(document).on('change', '.lang_level', function() {
            get_jobs();
        });

        $(document).on('change', '.otherlangbtn', function() {
            get_jobs();
        });

        $(document).on('change', '.locbtn', function() {
            get_jobs();
        });

        function get_jobs() {
            // Get keyword from search input
            var keyword = $('input[name="title"]').val();

            // Get delivery time
            var delivery_time = $('.delivery_time:checked').val();
            if (delivery_time === undefined) {
                delivery_time = '';
            }

            // Get location from country checkboxes
            var location = [];
            $('.locbtn:checked').each(function() {
                location.push($(this).val());
            });

            // Get category IDs
            var category = [];
            $('.category-checkbox:checked').each(function() {
                category.push($(this).val());
            });

            // Get subcategory IDs
            var subcategory = [];
            $('.subcategory-checkbox:checked').each(function() {
                subcategory.push($(this).val());
            });

            // Get skills
            var skills = [];
            $('.skills-checkbox:checked').each(function() {
                skills.push($(this).val());
            });

            // Get experience/rank
            var exp = [];
            $('.exp_checkboxes:checked').each(function() {
                exp.push($(this).val());
            });

            // Get language level
            var lang_level = [];
            $('.lang_level:checked').each(function() {
                lang_level.push($(this).val());
            });

            // Get other languages
            var otherlang = [];
            $('.otherlangbtn:checked').each(function() {
                otherlang.push($(this).val());
            });

            // Get budget values
            var minbudget = $('#minbudget').val();
            var maxbudget = $('#maxbudget').val();

            // Get sort value from URL
            var sort = getUrlParameter('sort') || '';

            $("#loading").show();

            $.ajax({
                url: "{{ url('/search-filter-services') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'category': category,
                    'subcategory': subcategory,
                    'skills': skills,
                    'minbudget': minbudget,
                    'maxbudget': maxbudget,
                    'keyword': keyword,
                    'location': location,
                    'exp': exp,
                    'otherlang': otherlang,
                    'delivery_time': delivery_time,
                    'lang_level': lang_level,
                    'sort': sort
                },
                success: function(response) {
                    if (response) {
                        if (response.status == 'true') {
                            $('.count').html(response.count + ' <strong>Result Found</strong>');
                            $('.services-filter-wrp').html(response.content_data);
                        } else {
                            console.log('Filter returned false status');
                        }
                    } else {
                        console.log('Empty response received');
                    }
                    $("#loading").hide();
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', error);
                    console.log('Response:', xhr.responseText);
                    $("#loading").hide();
                }
            });
        }

        // Helper function to get URL parameters
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        // Initialize event listeners for search inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Search by service title
            const titleSearch = document.querySelector('input[name="title"]');
            if (titleSearch) {
                titleSearch.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        get_jobs();
                    }
                });
            }

            // Search by country (from the top search bar)
            const locationSearch = document.querySelector('input[name="location"]');
            if (locationSearch) {
                locationSearch.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        get_jobs();
                    }
                });
            }
        });

        // Category search functionality
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

        // Country Search
        document.addEventListener("DOMContentLoaded", function() {
            const countrySearch = document.getElementById('countrySearch');
            const countryList = document.querySelector('.country-list');

            if (countrySearch) {
                countrySearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const countries = countryList.querySelectorAll('.form-check-label');

                    countries.forEach(country => {
                        const countryName = country.textContent.toLowerCase();
                        if (countryName.includes(searchTerm)) {
                            country.closest('.mt-2').style.display = 'block';
                        } else {
                            country.closest('.mt-2').style.display = 'none';
                        }
                    });
                });
            }
        });

        // Language search
        document.addEventListener("DOMContentLoaded", function() {
            const languageSearch = document.getElementById('languageSearch');
            const languageList = document.querySelector('.language-list');

            if (languageSearch) {
                languageSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const languages = languageList.querySelectorAll('.form-check-label');

                    languages.forEach(language => {
                        const languageName = language.textContent.toLowerCase();
                        if (languageName.includes(searchTerm)) {
                            language.closest('.mt-2').style.display = 'block';
                        } else {
                            language.closest('.mt-2').style.display = 'none';
                        }
                    });
                });
            }
        });

        // Favorite service functionality
        $(document).on("click", ".loveHeartAction", function() {
            let id = $(this).data('serviceid');
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                url: "{{ route('user.favorite.service') }}",
                method: "POST",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                    } else {
                        $.each(response, function(i, val) {
                            notify('error', val);
                        });
                    }
                }
            });
        });
    </script>
@endsection
