<div class="row mt-3">
    <div class="col-lg-3">
        <!-- Filters Section-->
        <div class="card shadow-sm p-2">
            <h6 class="ms-2 my-2 "> <i class="fas fa-sliders-h me-2"></i>Filters
                <a href="#" onclick="toggleDiv()" class="float-end d-lg-none links-c1 me-2"><i
                        class="fa-solid fa-chevron-down fs-18"></i></a>
            </h6>

            <div id="myDiv">

                <div class="accordion accordion-flush" id="accordionFlushExample">

                    <!----------Categoreies------------->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed fs-14"
                                style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                aria-expanded="false" aria-controls="flush-collapseOne">
                                Categories
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <input type="text" id="categorySearch" class="form-control fs-12 mb-2 keyword"
                                    placeholder="Search categories...">

                                <!-- List of Categories and Subcategories -->
                                <div class="accordion" id="categoryAccordion">
                                    @foreach ($categories as $category)
                                        <div class="accordion-item">
                                            <!-- Category Name with Expand/Collapse Button -->
                                            <h6 class="accordion-header" id="categoryHeading-{{ $category->id }}">
                                                <button class="accordion-button fs-14 collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#categoryCollapse-{{ $category->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="categoryCollapse-{{ $category->id }}"
                                                    category-id="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </button>
                                            </h6>

                                            <!-- List of Subcategories (Collapsible) -->
                                            <div id="categoryCollapse-{{ $category->id }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="categoryHeading-{{ $category->id }}"
                                                data-bs-parent="#categoryAccordion">
                                                <div class="accordion-body fs-12 p-1 ps-3 py-0 mb-2">
                                                    @foreach ($category->subCategory as $subcategory)
                                                        <div class="mt-1">
                                                            <input class="form-check-input subcategory-checkbox"
                                                                type="checkbox" id="subcat-box-{{ $subcategory->id }}"
                                                                name="{{ $subcategory->id }}" value="{{ $subcategory->id }}"
                                                                data-id="{{ $subcategory->id }}">
                                                            <label class="form-check-label ms-1"
                                                                for="subcat-box-{{ $subcategory->id }}">
                                                                {{ Str::limit($subcategory->name, 25, '...') }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------Budget------------->
                    <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed fs-14"
                                style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                aria-expanded="false" aria-controls="flush-collapseTwo">
                                Budget
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body p-3 pb-2 fs-14">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" id="minbudget" class="form-control fs-14 py-1 minbudget"
                                            placeholder="Min">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="maxbudget" class="form-control fs-14 py-1 maxbudget"
                                            placeholder="Max">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------Delivery Time------------->
                    <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed fs-14"
                                style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                aria-expanded="false" aria-controls="flush-collapseThree">
                                Delivery Time
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fs-14">
                                <div class="mt-1">
                                    <input class="form-check-input delivery_time" type="radio"
                                        id="delivery-any" name="delivery_time" value="0" data-id="0">
                                    <label class="form-check-label mx-1" for="delivery-any">
                                        Any Delivery Time
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input delivery_time" type="radio"
                                        id="delivery-24" name="delivery_time" value="1" data-id="1">
                                    <label class="form-check-label mx-1" for="delivery-24">
                                        24 Hours or Less
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input delivery_time" type="radio"
                                        id="delivery-7" name="delivery_time" value="7" data-id="7">
                                    <label class="form-check-label mx-1" for="delivery-7">
                                        7 Days or Less
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input delivery_time" type="radio"
                                        id="delivery-21" name="delivery_time" value="21" data-id="21">
                                    <label class="form-check-label mx-1" for="delivery-21">
                                        21 Days or Less
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------Rank / Experience Level------------->
                    <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="flush-headingFour">
                            <button class="accordion-button collapsed fs-14"
                                style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour"
                                aria-expanded="false" aria-controls="flush-headingFour">
                                Rank / Experience
                            </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fs-14">
                                <div class="mt-1">
                                    <input class="form-check-input exp_checkboxes" type="radio"
                                        id="rank-1" name="exp" value="1" data-id="1">
                                    <label class="form-check-label ms-1" for="rank-1">
                                        Rank 1 : Newcomer <br>
                                        <p class="fs-12 text-bc mb-2"> Earning: Less then $1000</p>
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input exp_checkboxes" type="radio"
                                        id="rank-2" name="exp" value="2" data-id="2">
                                    <label class="form-check-label ms-1" for="rank-2">
                                        Rank 2 : Rising Star <br>
                                        <p class="fs-12 text-bc mt-0 mb-2"> Earning: $1000 - $2000</p>
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input exp_checkboxes" type="radio"
                                        id="rank-3" name="exp" value="3" data-id="3">
                                    <label class="form-check-label ms-1" for="rank-3">
                                        Rank 3 : Mid Earner <br>
                                        <p class="fs-12 text-bc mt-0 mb-2"> Earning: $2000 - $5000</p>
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input exp_checkboxes" type="radio"
                                        id="rank-4" name="exp" value="4" data-id="4">
                                    <label class="form-check-label ms-1" for="rank-4">
                                        Rank 4 : High Earner <br>
                                        <p class="fs-12 text-bc mt-0 mb-2"> Earning: $5000 - $10000</p>
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input exp_checkboxes" type="radio"
                                        id="rank-5" name="exp" value="5" data-id="5">
                                    <label class="form-check-label ms-1" for="rank-5">
                                        Rank 5 : Top Earner <br>
                                        <p class="fs-12 text-bc mt-0"> Earning: $10000 +</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------Rating------------->
                    <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="flush-headingFive">
                            <button class="accordion-button collapsed fs-14"
                                style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive"
                                aria-expanded="false" aria-controls="flush-headingFive">
                                Rating
                            </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fs-14">
                                <!-- Note: Rating filter not implemented in current JS, you may need to add this -->
                                <div class="mt-1">
                                    <input class="form-check-input" type="radio"
                                        id="rating-any" name="rating" value="0">
                                    <label class="form-check-label ms-1" for="rating-any">
                                        Any Rating
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input" type="radio"
                                        id="rating-2" name="rating" value="2">
                                    <label class="form-check-label ms-1" for="rating-2">
                                        <span class="fs-12"> <i class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-regular text-warning fa-star"></i> <i
                                                class="fa-regular text-warning fa-star"></i> <i
                                                class="fa-regular text-warning fa-star"></i></span> 2 + Stars
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input" type="radio"
                                        id="rating-3" name="rating" value="3">
                                    <label class="form-check-label ms-1" for="rating-3">
                                        <span class="fs-12"> <i class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-regular text-warning fa-star"></i> <i
                                                class="fa-regular text-warning fa-star"></i></span> 3 + Stars
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input" type="radio"
                                        id="rating-4" name="rating" value="4">
                                    <label class="form-check-label ms-1" for="rating-4">
                                        <span class="fs-12"> <i class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-regular text-warning fa-star"></i></span> 4 + Stars
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input" type="radio"
                                        id="rating-5" name="rating" value="5">
                                    <label class="form-check-label ms-1" for="rating-5">
                                        <span class="fs-12"> <i class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i> <i
                                                class="fa-solid text-warning fa-star"></i></span> 5 Stars Only
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-------------- English Level--------------------->
                    <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="flush-headingSix">
                            <button class="accordion-button collapsed fs-14"
                                style="border:1px solid #ccc !important; border-radius:5px !important;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix"
                                aria-expanded="false" aria-controls="flush-headingSix">
                                English Level
                            </button>
                        </h2>
                        <div id="flush-collapseSix" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fs-14">
                                <div class="mt-1">
                                    <input class="form-check-input lang_level" type="checkbox"
                                        id="english-beginner" name="beginner" value="Beginner">
                                    <label class="form-check-label ms-1" for="english-beginner">
                                        Beginners
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input lang_level" type="checkbox"
                                        id="english-intermediate" name="intermediate" value="Intermediate">
                                    <label class="form-check-label ms-1" for="english-intermediate">
                                        Intermediate
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <input class="form-check-input lang_level" type="checkbox"
                                        id="english-advanced" name="advanced" value="Advanced">
                                    <label class="form-check-label ms-1" for="english-advanced">
                                        Advanced
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other languages-->
                    {{-- <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="flush-headingSeven">
                            <button class="accordion-button collapsed fs-14"
                                style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven"
                                aria-expanded="false" aria-controls="flush-headingSeven">
                                Other Languages
                            </button>
                        </h2>
                        <div id="flush-collapseSeven" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body p-3 fs-14">
                                <input type="text" id="languageSearch" class="form-control mb-2 fs-12"
                                    placeholder="Search a language...">

                                <!-- List of Other languages with Checkboxes -->
                                <div class="language-list custom-scroll mb-3"
                                    style="max-height: 200px; overflow-y: auto;">

                                    @foreach($languages as $language)
                                        <div class="mt-2">
                                            <input class="form-check-input otherlangbtn" type="checkbox"
                                                name="otherlang[]" value="{{ $language->code }}" id="language-{{ $language->code }}">
                                            <label class="form-check-label ms-1" for="language-{{ $language->code }}">
                                                {{ $language->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!------List of Countries with Checkboxes------->
                    <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="flush-headingCountry">
                            <button class="accordion-button collapsed fs-14" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseCountry" aria-expanded="false"
                                style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                                aria-controls="flush-headingCountry">
                                Country
                            </button>
                        </h2>
                        <div id="flush-collapseCountry" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingCountry" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fs-14">
                                <div class="selected-country mb-3 d-flex flex-wrap gap-2" id="selectedCountryContainer">
                                </div>
                                <!-- Search Input for Countries -->
                                <input type="text" id="countrySearch" class="form-control mb-2 fs-12"
                                    placeholder="Search countries...">

                                <!-- List of Countries with Checkboxes -->
                                <div class="country-list custom-scroll mb-3">
                                    @foreach ($countries as $code => $country)
                                        <div class="mt-2">
                                            <input class="form-check-input locbtn" type="checkbox"
                                                name="location[]" value="{{ $country['country'] }}"
                                                id="country-{{ $code }}">
                                            <label class="form-check-label ms-1" for="country-{{ $code }}">
                                                {{ $country['country'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!----Filters End --->
    </div>

    @php
        $helpers = new \App\Classes\Helpers();
        $currency_rates = $helpers->get_currency_rates();
        $currencies = $helpers->get_all_currencies();
    @endphp

    @if ($processedServices->count())
        <div class="col-lg-9">
            <div class="row services-filter-wrp">
                @include('service_filtered', ['processedServices' => $processedServices, 'currencySymbol' => '$'])
            </div>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $services->links() }}
            </div>
        </div>
    @else
        <div class="col-lg-9">
            <div class="text-center w-100 my-5">
                @if (request()->filled('title') || request()->filled('location') || request()->filled('city_state'))
                    <i class="fa fa-search fs-2 mb-2 text-green"></i>
                    <div class="fs-5 fw-semibold">No services found with your current search filters.</div>
                    <div class="fs-6 mt-2">Try changing or removing filters to see more results.</div>
                @else
                    <i class="fa fa-frown fs-2 mb-2"></i>
                    <div class="fs-5 fw-semibold">{{ $emptyMessage ?? 'No data found' }}</div>
                @endif
            </div>
        </div>
    @endif
</div>
