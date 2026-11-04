 <div id="jobSideBar" class="col-3 d-none d-lg-block">
    <div class="shadow-sm border bg-white rounded-2 p-2" style="height:auto">
        <p class="ps-2 fs-6"> <i class="fas fa-sliders-h me-2"></i> Filter by</p>

        <div class="accordion mt-3" id="accordionExample">
            <!-- Categories Filter -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed p-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Categories
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse collapse-categories" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body p-1 ps-3 py-0 custom-scroll">
                        <!-- Search Input for Categories -->
                        <input type="text" id="categorySearch" class="form-control mb-3" placeholder="Search categories...">

                        <!-- List of Categories and Subcategories -->
                        <div class="accordion" id="categoryAccordion">
                            @foreach ($categories as $category)
                                <div class="accordion-item">
                                    <!-- Category Name with Expand/Collapse Button -->
                                    <h6 class="accordion-header" id="categoryHeading-{{ $category->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#categoryCollapse-{{ $category->id }}" aria-expanded="false"
                                            aria-controls="categoryCollapse-{{ $category->id }}">
                                            {{ $category->name }}
                                        </button>
                                    </h6>

                                    <!-- List of Subcategories (Collapsible) -->
                                    <div id="categoryCollapse-{{ $category->id }}" class="accordion-collapse collapse"
                                        aria-labelledby="categoryHeading-{{ $category->id }}" data-bs-parent="#categoryAccordion">
                                        <div class="accordion-body p-1 ps-3 py-0">
                                            @foreach ($category->subCategory as $subcategory)
                                                <div class="mt-1">
                                                    <input class="form-check-input subcategory-checkbox" type="checkbox"
                                                        id="subcat-box-{{ $subcategory->id }}" name="{{ $subcategory->id }}"
                                                        value="{{ $subcategory->id }}" data-id="{{ $subcategory->id }}">
                                                    <label class="form-check-label ms-1" for="subcat-box-{{ $subcategory->id }}">
                                                        {{ Str::limit($subcategory->name, 25, '...') }}
                                                    </label>
                                                </div>
                                                <!-- Skills Section (Initially Hidden) -->
                                                {{-- <div id="skill-cat-{{ $subcategory->id }}" class="ms-1 skills-cat-div" style="display: none;">
                                                    <!-- Skills will be dynamically loaded here -->
                                                </div> --}}
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
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Experience Level
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                    <div class="accordion-body p-1 ps-3 py-0">
                        <div class="mt-2">
                            <input class="form-check-input exp_checkboxes" type="checkbox" name="Beginner"
                                value="1" id="d1">
                            <label class="form-check-label" for="d1">Beginner</label>
                        </div>
                        <div class="mt-2">
                            <input class="form-check-input exp_checkboxes" type="checkbox" name="Intermedite"
                                value="2" id="d2">
                            <label class="form-check-label" for="d2">Intermediate</label>
                        </div>
                        <div class="mt-2">
                            <input class="form-check-input exp_checkboxes" type="checkbox" name="Expert"
                                value="3" id="d3">
                            <label class="form-check-label" for="d3">Expert</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Filter -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Hourly Rate
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse show" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                    <div class="accordion-body p-1 ps-3 py-0">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                <input type="text" id="minhourlyrate" class="form-control" placeholder="Minrate">
                            </div>
                            <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                <input type="text" id="maxhourlyrate" class="form-control" placeholder="Maxrate">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Days Filter -->
            {{--  <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        Hours Per Week
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse show" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                    <div class="accordion-body p-1 ps-3 py-0">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                <input type="text" id="minhoursperweek" class="form-control" placeholder="Min Hours">
                            </div>
                            <div class="col-lg-6 col-md-6 col-cm-6 col-6">
                                <input type="text" id="maxhoursperweek" class="form-control" placeholder="Max Hours">
                            </div>
                        </div>
                    </div>
                </div>
            </div>  --}}
            <!-- Country Filter -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        Country
                    </button>
                </h2>
                <div id="collapseSeven" class="accordion-collapse show" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                    <div class="accordion-body p-1 ps-3 py-0">
                        <!-- Search Input for Countries -->
                        <input type="text" id="countrySearch" class="form-control mb-3" placeholder="Search countries...">

                        <!-- List of Countries with Checkboxes -->
                        <div class="country-list custom-scroll" style="max-height: 200px; overflow-y: auto;">
                            @foreach ($countries as $code => $country)
                                <div class="mt-2">
                                    <input class="form-check-input country-checkbox" type="checkbox" name="country[]"
                                        value="{{ $country['country'] }}" id="country-{{ $code }}">
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
</div>
