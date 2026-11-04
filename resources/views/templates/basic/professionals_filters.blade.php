<style>
    .star {
        cursor: pointer;
        color: #ccc;
        transition: color 0.2s;
    }

    .star.filled {
        color: #f39c12;
    }

    .form-check-input:checked {
        background-color: #00C535;
        border-color: #00C535;
        box-shadow: none;
    }
</style>
<div class="col-lg-3 col-md-3 col-12 p-0">

    <!-- Filters Section-->
    <div class="card shadow-sm p-2 lg:mb-0 md:mb-0 mb-2">
        <h6 class="ms-2 my-2">
            <i class="fas fa-sliders-h me-2"></i>Filters
            <a href="#" onclick="toggleDiv()" class="float-end d-lg-none links-c1 me-2">
                <i class="fa-solid fa-chevron-down fs-18"></i>
            </a>
        </h6>

        <div id="myDiv">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <!-- Categories Filter -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed fs-14" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                            aria-controls="flush-collapseOne">
                            Categories
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="selected-country mb-3 d-flex flex-wrap gap-2" id="selectedSubCategoryContainer">
                            </div>
                            <input type="text" id="categorySearch" class="form-control fs-12 mb-2"
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

                <!-- Experience Level Filter -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingExperience">
                        <button class="accordion-button collapsed fs-14" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseExperience" aria-expanded="false"
                            aria-controls="flush-collapseExperience">
                            Experience Level
                        </button>
                    </h2>
                    <div id="flush-collapseExperience" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingExperience" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <div class="mt-2">
                                <input class="form-check-input exp_checkboxes" type="checkbox" name="Beginner"
                                    value="1" id="d1">
                                <label class="form-check-label ms-1" for="d1">Beginner</label>
                            </div>
                            <div class="mt-2">
                                <input class="form-check-input exp_checkboxes" type="checkbox" name="Intermedite"
                                    value="2" id="d2">
                                <label class="form-check-label ms-1" for="d2">Intermediate</label>
                            </div>
                            <div class="mt-2">
                                <input class="form-check-input exp_checkboxes" type="checkbox" name="Expert"
                                    value="3" id="d3">
                                <label class="form-check-label ms-1" for="d3">Expert</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hourly Rate Filter -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingHourly">
                        <button class="accordion-button collapsed fs-14" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseHourly" aria-expanded="false"
                            aria-controls="flush-collapseHourly">
                            Hourly Rate
                        </button>
                    </h2>
                    <div id="flush-collapseHourly" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingHourly" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body p-3 pb-2 fs-14">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" id="minhourlyrate" class="form-control fs-14 py-1"
                                        placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="text" id="maxhourlyrate" class="form-control fs-14 py-1"
                                        placeholder="Max">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--  Weekly Hours  --}}

                <!-- Weekly Hours Section -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingWeekly">
                        <button class="accordion-button collapsed fs-14" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseWeekly" aria-expanded="false"
                            aria-controls="flush-collapseWeekly">
                            Weekly Hours
                        </button>
                    </h2>
                    <div id="flush-collapseWeekly" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingWeekly" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body p-3 pb-2 fs-14">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" name="minweeklyhours"
                                        class="form-control fs-14 py-1 minweeklyhours" placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="maxweeklyhours"
                                        class="form-control maxweeklyhours fs-14 py-1" placeholder="Max">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  English  --}}
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-engLevel">
                        <button class="accordion-button collapsed fs-14 p-2" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseEngLevel" aria-expanded="false"
                            aria-controls="flush-collapseEngLevel">
                            English
                        </button>
                    </h2>
                    <div id="flush-collapseEngLevel" class="accordion-collapse collapse"
                        aria-labelledby="flush-engLevel" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <!-- List of Languages with Checkboxes -->
                            <div class="lang_level-list custom-scroll mb-3"
                                style="max-height: 200px; overflow-y: auto;">
                                @foreach (languageLevel() as $lang_level)
                                    <div class="mt-2">
                                        <input class="form-check-input lang_level-checkbox" type="checkbox"
                                            name="lang_level[]" value="{{ $lang_level }}"
                                            id="lang_level-{{ $lang_level }}">
                                        <label class="form-check-label ms-1" for="lang_level-{{ $lang_level }}">
                                            {{ $lang_level }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{--  English End  --}}



                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingLanguage">
                        <button class="accordion-button collapsed fs-14 p-2" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseLanguage" aria-expanded="false"
                            aria-controls="flush-headingLanguage">
                            Language
                        </button>
                    </h2>
                    <div id="flush-collapseLanguage" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingLanguage" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <div class="selected-languages mb-3 d-flex flex-wrap gap-2"
                                id="selectedLanguagesContainer">
                                <!-- Selected languages will appear here as chips -->
                            </div>
                            <input type="text" id="languageSearch" class="form-control mb-2 fs-12"
                                placeholder="Search languages...">

                            <!-- List of Languages with Checkboxes -->
                            <div class="language-list custom-scroll mb-3"
                                style="max-height: 200px; overflow-y: auto;">
                                @foreach (profileLanguages() as $id => $language)
                                    <div class="mt-2">
                                        <input class="form-check-input language-checkbox" type="checkbox"
                                            name="language[]" value="{{ $language }}"
                                            id="language-{{ $id }}">
                                        <label class="form-check-label ms-1" for="language-{{ $id }}">
                                            {{ $language }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-rank">
                        <button class="accordion-button collapsed fs-14 p-2" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapserank" aria-expanded="false"
                            aria-controls="flush-collapserank">
                            Rank
                        </button>
                    </h2>
                    <div id="flush-collapserank" class="accordion-collapse collapse" aria-labelledby="flush-rank"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <div class="rank-list custom-scroll mb-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach (ranks() as $key => $rank)
                                    <div class="mt-2">
                                        <input class="form-check-input rank-checkbox" type="checkbox" name="rank[]"
                                            value="{{ $rank['id'] }}" id="rank-{{ $rank['id'] }}">
                                        <label class="form-check-label ms-1" for="rank-{{ $rank['id'] }}">
                                            {{ $rank['level'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingRating">
                        <button class="accordion-button collapsed fs-14 p-2" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseRating" aria-expanded="false"
                            aria-controls="flush-headingRating">
                            Rating
                        </button>
                    </h2>
                    <div id="flush-collapseRating" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingRating" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14 text-center">
                            <!-- Star Rating -->
                            <div class="rating ">
                                <span class="fa-regular fa-star fs-2 star" style="font-size:20px !important"
                                    data-value="1"></span>
                                <span class="fa-regular fa-star fs-2 star" style="font-size:20px !important"
                                    data-value="2"></span>
                                <span class="fa-regular fa-star fs-2 star" style="font-size:20px !important"
                                    data-value="3"></span>
                                <span class="fa-regular fa-star fs-2 star" style="font-size:20px !important"
                                    data-value="4"></span>
                                <span class="fa-regular fa-star fs-2 star" style="font-size:20px !important"
                                    data-value="5"></span>
                            </div>
                            <input type="hidden" id="ratingValue" name="rating" value="">
                            <button type="button" id="resetRating"
                                class="btn btn-sm btn-success bg-green border-0 mt-2">Reset Rating</button>
                        </div>
                    </div>
                </div>


                <!-- Country Filter -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingCountry">
                        <button class="accordion-button collapsed fs-14 p-2" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseCountry" aria-expanded="false"
                            aria-controls="flush-headingCountry">
                            Country
                        </button>
                    </h2>
                    <div id="flush-collapseCountry" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingCountry" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <div class="selected-country mb-3 d-flex flex-wrap gap-2" id="selectedCountryContainer">
                            </div>
                            <input type="text" id="countrySearch" class="form-control mb-2 fs-12"
                                placeholder="Search countries...">

                            <!-- List of Countries with Checkboxes -->
                            <div class="country-list custom-scroll mb-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($countries as $code => $country)
                                    <div class="mt-2">
                                        <input class="form-check-input country-checkbox" type="checkbox"
                                            name="country[]" value="{{ $country['country'] }}"
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
    </div><!-- Filters End -->

</div>
