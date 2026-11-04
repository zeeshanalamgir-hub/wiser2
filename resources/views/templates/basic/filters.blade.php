<style>
    .form-check-input:checked {
        background-color: #00C535;
        border-color: #00C535;
        box-shadow: none;
    }
</style>

<!-- Filters Section -->
<div class="col-lg-3">
    <div class="card p-2">
        <h6 class="ms-2 my-2">
            <i class="fas fa-sliders-h me-2"></i>Filters
            <a href="#" onclick="toggleDiv()" class="float-end d-lg-none links-c1 me-2">
                <i class="fa-solid fa-chevron-down fs-18"></i>
            </a>
        </h6>

        <div id="myDiv" class="d-lg-block">
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
                            <!-- Search Input for Categories -->
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

                <!-- Job Type Filter -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingJobType">
                        <button class="accordion-button collapsed fs-14" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseJobType" aria-expanded="false"
                            aria-controls="flush-collapseJobType">
                            Job Type
                        </button>
                    </h2>
                    <div id="flush-collapseJobType" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingJobType" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <div class="mt-2">
                                <input class="form-check-input job_type_checkboxes" type="checkbox" name="regular"
                                    value="1" id="regular">
                                <label class="form-check-label ms-1" for="regular">Regular</label>
                            </div>
                            <div class="mt-2">
                                <input class="form-check-input job_type_checkboxes" type="checkbox" name="featured"
                                    value="2" id="featured">
                                <label class="form-check-label ms-1" for="featured">Featured</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingJobPrice">
                        <button class="accordion-button collapsed fs-14" type="button"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseJobPrice" aria-expanded="false"
                            aria-controls="flush-collapseJobPrice">
                            Job Price
                        </button>
                    </h2>
                    <div id="flush-collapseJobPrice" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingJobPrice" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <div class="mt-2">
                                <input class="form-check-input jtype_checkboxes" type="checkbox" name="fixed"
                                    value="fixed" id="fixed">
                                <label class="form-check-label ms-1" for="fixed">Fixed</label>
                            </div>
                            <div class="mt-2">
                                <input class="form-check-input jtype_checkboxes" type="checkbox" name="hourly"
                                    value="hourly" id="per_hour">
                                <label class="form-check-label ms-1" for="per_hour">Per Hour</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Experience Level Filter -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingExperience">
                        <button class="accordion-button collapsed fs-14" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseExperience" aria-expanded="false"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            aria-controls="flush-collapseExperience">
                            Experience Level
                        </button>
                    </h2>
                    <div id="flush-collapseExperience" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingExperience" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-14">
                            <div class="mt-2">
                                <input class="form-check-input exp_checkboxes" type="checkbox" name="1"
                                    value="Beginner" id="d1">
                                <label class="form-check-label ms-1" for="d1">Beginner</label>
                            </div>
                            <div class="mt-2">
                                <input class="form-check-input exp_checkboxes" type="checkbox" name="2"
                                    value="Intermedite" id="d2">
                                <label class="form-check-label ms-1" for="d2">Intermediate</label>
                            </div>
                            <div class="mt-2">
                                <input class="form-check-input exp_checkboxes" type="checkbox" name="3"
                                    value="Expert" id="d3">
                                <label class="form-check-label ms-1" for="d3">Expert</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget Filter -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingBudget">
                        <button class="accordion-button collapsed fs-14" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseBudget" aria-expanded="false"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            aria-controls="flush-collapseBudget">
                            Budget
                        </button>
                    </h2>
                    <div id="flush-collapseBudget" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingBudget" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body p-3 pb-2 fs-14">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" id="minbudget" class="form-control fs-14 py-1"
                                        placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="text" id="maxbudget" class="form-control fs-14 py-1"
                                        placeholder="Max">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hours Per Week Filter -->
                <div class="accordion-item mt-2">
                    <h2 class="accordion-header" id="flush-headingHours">
                        <button class="accordion-button collapsed fs-14" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseHours" aria-expanded="false"
                            style="border:1px solid #ccc !important; border-radius:5px !important; padding:6px !important;"
                            aria-controls="flush-collapseHours">
                            Hours Per Week
                        </button>
                    </h2>
                    <div id="flush-collapseHours" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingHours" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body p-3 pb-2 fs-14">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" id="minhoursperweek" class="form-control fs-14 py-1"
                                        placeholder="Min Hours">
                                </div>
                                <div class="col-6">
                                    <input type="text" id="maxhoursperweek" class="form-control fs-14 py-1"
                                        placeholder="Max Hours">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Country Filter -->
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
