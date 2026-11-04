@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
<style>
    .nicEdit-main {
        min-height: 200px !important;
        /* Change value as needed */
    }
</style>
@section('content')
    <h5 class="text-dgreen2 mt-2">Edit Services</h5>

    <div class="dashboard-content">
        @if ($review == 'true')
        @else
            <form class="user-profile-form" id="serviceForm" action="{{ route('user.service.update', $service->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
        @endif

        <!-- Portfolio Section -->
        <div class="bg-white p-3 p-md-4 shadow-sm border fs-14 rounded-2">
            <h6 class="mb-0 fw-bold text-dgreen">PORTFOLIO</h6>
            <p class="pt-3 fs-16 text-secondary">Add images to showcase your service</p>

            <div class="row">
                <!-- Main Image Upload -->
                <div class="col-lg-4">
                    <div class="border-dash-g2 text-center p-0" style="height: 200px;" id="main-img-box">
                        <label for="main-image" id="drop-area"
                            style="cursor: pointer; display: block; height: 100%; width: 100%; margin: 0;">
                            <input type="file" accept="image/jpeg,image/png" id="main-image" name="image" onchange="readURL(this)"
                                hidden>
                            <div id="main-img-view"
                                style="height: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <img id="blah"
                                    src="{{ getImage('assets/images/service/' . $service->image, '590x300') }}"
                                    alt="Service Image" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </label>
                        @if ($review != 'true')
                            <label for="main-image" class="asdlabel"></label>
                        @endif
                    </div>
                    <p class="text-secondary mb-0 fs-14 pt-2">Images (.jpg, .jpeg, .png)</p>
                </div>
                <!-- Additional Images Upload with Preview -->
                <div class="col-md-8">
                    <div class="border-dash-g2 p-3 h-100">
                        <label for="more-images" class="w-100 text-center d-block" style="cursor: pointer;">
                            <input type="file" accept="image/jpeg,image/png" id="more-images" name="optional_image[]" hidden multiple
                                @if ($review == 'true') disabled @endif>
                            <div>
                                <p class="text-muted small mb-2">Add up to 5 <span class="text-primary">More Images</span>
                                    <span class="text-muted small"> Drag or click — single or multiple</span>
                                </p>
                                <i class="fa fa-plus-circle text-success fs-3"></i>
                            </div>
                        </label>

                        <!-- Preview Container -->
                        <div id="more-images-preview" class="d-flex flex-wrap gap-2 mt-3 justify-content-start">
                            <!-- Existing Uploaded Images -->
                            @if (!empty($service->optionalImage))
                                @foreach ($service->optionalImage as $data)
                                    <div class="position-relative" style="width: 120px; height: 120px;"
                                        id="cer-{{ $data->id }}">
                                        <img src="{{ getImage('assets/images/optionalService/' . $data->image, '590x300') }}"
                                            style="width:100%;height:100%;object-fit:cover;border-radius:5px;">
                                        @if ($review != 'true')
                                            <button type="button"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                onclick="deletecer({{ $data->id }})">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <input type="hidden" name="deleted_optional_images" id="deleted-optional-images">

                    </div>
                </div>
            </div>
        </div>

        <!-- Service Details Section -->
        <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border rounded-2">
            <h6 class="fw-bold mb-3 text-dgreen">SERVICE DETAILS</h6>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="service_title" id="tt" maxlength="60"
                    value="{{ __($service->title) }}" placeholder="Enter Title"
                    @if ($review == 'true') readonly @else required @endif>
                <label>@lang('Title')* <small class="text-danger">(max 90 characters)</small> <span
                        id="ttcount"></span>/90</label>
            </div>

            <div class="form-floating mb-3">
                @if ($review == 'true')
                    <div class="p-3 border rounded bg-light">
                        {!! $service->description !!}
                    </div>
                @else
                    <textarea class="form-control nicEdit" name="description" id="description" placeholder="@lang('Description')"
                        style="min-height: 200px;">{{ old('description', $service->description) }}</textarea>
                    <label for="description">
                        <!-- @lang('Description')*
                                                                                                <small class="text-danger">(max 2000 characters)</small> -->
                    </label>
                    <div class="text-end text-secondary mt-1" id="descCounterBox">
                        <span id="descCharCount">0</span>/2000 characters
                        <span id="descOverMsg" style="display:none;" class="ms-2 text-danger fs-13">Max 2000 characters
                            allowed!</span>
                    </div>
                @endif
            </div>

            <select name="priority" id="priority" class="form-select" @if ($review == 'true') disabled @endif>
                <option selected disabled>@lang('Post as a')*</option>
                <option {{ (int) $service->priority == 0 ? 'selected' : '' }} value="0">Normal</option>
                <option {{ (int) $service->priority == 1 ? 'selected' : '' }} value="1">Priority</option>
                <option {{ (int) $service->priority == 2 ? 'selected' : '' }} value="2">Featured</option>
            </select>
        </div>

        <!-- Service Categorization Section -->
        <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border rounded-2">
            <h6 class="fw-bold mb-3 text-dgreen">SERVICE CATEGORIZATION</h6>
            <p class="fs-16 text-secondary">Choose a category so clients can easily find your Service.</p>

            <div class="row g-1 g-md-3">
                <div class="col-md-4">
                    <select class="form-select" name="category" id="category"
                        @if ($review == 'true') disabled @endif>
                        <option selected disabled>@lang('Select Category Level 1')</option>
                        @foreach ($categoryss as $category)
                            <option {{ $service->category_id == $category->id ? 'selected' : '' }}
                                value="{{ __($category->id) }}">{{ __($category->name) }}</option>
                        @endforeach
                    </select>
                </div>
                @php
                    $subs = DB::table('sub_categories')->where('category_id', $service->category_id)->get();
                    $childs = DB::table('child_categories')->where('subcat_id', $service->sub_category_id)->get();
                    $childscount = DB::table('child_categories')
                        ->where('subcat_id', $service->sub_category_id)
                        ->count();
                @endphp
                <div class="col-md-4">
                    <select name="subcategory" class="form-select mySubCatgry getchild" id="subCategorys"
                        @if ($review == 'true') disabled @endif>
                        @foreach ($subs as $category)
                            <option {{ $service->sub_category_id == $category->id ? 'selected' : '' }}
                                value="{{ __($category->id) }}">{{ __($category->name) }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if ($childscount > 0)
                    <div class="col-md-4 myChildCatgry">
                        <select name="childcategory" class="form-select tierchildcat" id="childCategorys"
                            @if ($review == 'true') disabled @endif>
                            @foreach ($childs as $category)
                                <option {{ $service->childcat_id == $category->id ? 'selected' : '' }}
                                    value="{{ __($category->id) }}">{{ __($category->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <!-- Selected Service Features -->
        <!-- <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2">
                                                                                    <h6 class="fw-bold text-dgreen">SELECTED SERVICE FEATURES</h6>
                                                                                    <div id="skills_result1" class="col-xl-12 col-lg-12 form-group"></div>
                                                                                </div> -->

        @if ($review != 'true')
            <!-- Service Features -->
            <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2">
                <h6 class="fw-bold text-dgreen">SERVICE FEATURES</h6>
                <p class="pt-2 fs-16 text-secondary">Choose features that best describe your service.</p>
                <div id="selected_skills_result" class="col-xl-12 col-lg-12 form-group">
                    @if (!empty(old('skills', $service->skills ?? [])))
                        @foreach (old('skills', $service->skills->pluck('id')->toArray()) as $skillId)
                            @php
                                $skillObj = \App\Models\Skill::find($skillId);

                            @endphp
                            @if ($skillObj)
                                <button type="button" onclick="unselectskill({{ $skillObj->id }})"
                                    id="skill_btn{{ $skillObj->id }}" value="{{ $skillObj->id }}"
                                    class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                                    {{ $skillObj->name }} <i class="fa fa-times ml-2"></i>
                                </button>&nbsp;
                                <input type="hidden" name="skills[]" id="job_skills{{ $skillObj->id }}"
                                    value="{{ $skillObj->id }}">
                            @endif
                        @endforeach
                    @endif
                </div>
                <div id="skills_result" class="col-xl-12 col-lg-12 form-group">
                </div>
                <div class="col-xl-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist" id="result"></ul>
                    <div class="tab-content" id="demos">
                    </div>
                </div>
            </div>

            <!-- Keywords Section -->
            <div class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2">
                <h6 class="fw-bold text-dgreen">KEYWORDS FOR SEARCH</h6>
                <p class="pt-2 fs-16 text-secondary">Find pre-defined Skills or Type to add (Max. 5).</p>

                <div id="skills_result_extra" class="col-xl-12 col-lg-12 form-group">
                    @php
                        $existing = $keywords
                            ->map(
                                fn($k) => [
                                    'id' => (string) $k->id,
                                    'name' => $k->name,
                                ],
                            )
                            ->values();
                    @endphp
                    @foreach ($existing as $idx => $skill)
                        <button type="button" onclick="unselectnewskill('{{ $skill['id'] }}')"
                            id="newskill_btn{{ $skill['id'] }}" value="{{ $skill['id'] }}"
                            class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                            {{ $skill['name'] }} <i class="fa fa-times"></i>
                        </button>&nbsp;
                        <input type="hidden" name="newskills[]" id="newjob_skills{{ $skill['id'] }}"
                            value="{{ $skill['id'] }}">
                    @endforeach
                </div>

                <div class="form-floating mb-3">
                    <input onkeyup="getSkillbuname(this.value)" id="search_skill" name="tag[]" class="form-control"
                        type="text" autocomplete="off" placeholder="Search ...">
                    <label>Type to search...</label>
                </div>

                <!-- Added style="display:none;" to hide initially -->
                <div id="skil_result" class="col-xl-12 col-lg-12 form-group" style="display:none;"></div>
                <input type="hidden" name="price" value="0">
                <p class="fs-14"><em>5 tags maximum.</em></p>
            </div>
        @endif

        <div class="bg-white p-3 px-2 p-md-4 mt-3 shadow-sm border fs-14 rounded-2 mb-3">
            <h6 class="fw-bold text-dgreen w-100">SCOPE & PRICE
                @if ($service->service_type == 1)
                    <span class="float-end fs-16 text-secondary fw-normal">
                        <label class="switch">
                            <input type="checkbox" id="msval" name="msval" value="1"
                                {{ $service->service_type ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <span class="fs-14">Multi Packages</span>
                    </span>
                @endif
            </h6>
            <p class="pt-2 fs-14 text-secondary">Choose from 1 or 3 pricing plans for your service</p>

            <div class="row mt-3" id="a" style="{{ $service->service_type ? '' : 'display:none;' }}">
                <div class="col-lg-12 col-md-12">
                    <div class="bg-light rounded mb-4 px-2">
                        <div class="py-3 px-3">
                            <div class="row">
                                <div class="col-3 px-0">
                                    <h5 class="text-green fs-5"> Packages </h5>
                                </div>
                                @foreach ($serviceDetails as $detail)
                                    <div class="col-3 pe-0 text-center">
                                        <p class="bg-lgreen p-1 fs-16 fw-bold mb-0">
                                            {{ $detail->title ?? ['Lite', 'Plus', 'Ultimate'][$loop->index] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-2">
                                <div class="col-3 px-0">
                                    <h6>Title <small class="fs-14 text-secondary">(max 60 chars)</small></h6>
                                </div>
                                @foreach ($serviceDetails as $detail)
                                    <div class="col-3 pe-0">
                                        <input type="text" class="form-control rounded" maxlength="60"
                                            placeholder="Title" name="title[{{ $loop->index + 1 }}]" value="{{ $detail->title }}">
                                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0">
                                    <h6 class="mb-0 ft-medium fs-md">Description</h6>
                                </div>
                                @foreach ($serviceDetails as $detail)
                                    <div class="col-3 pe-0">
                                        <textarea name="desc[{{ $loop->index + 1 }}]" class="form-control with-light" maxlength="200" placeholder="Description">{{ $detail->desc }}</textarea>
                                    </div>
                                @endforeach
                            </div>
        
                            {{-- @foreach ($tiersCheckbox as $tier)
                                <div class="row border-top mt-2 pt-2">
                                    <div class="col-3 px-0">
                                        <p class="mb-0 ft-medium fs-md">{{ $tier->name }}</p>
                                        <input type="hidden" name="tier_id[]" value="{{ $tier->id }}">
                                    </div>
                                    @foreach ($serviceDetails as $detail)
                                        @php 
                                            // Get the tier values for this specific detail
                                            $tierValuesForDetail = DB::table('service_detail_tiers')
                                                ->where('service_detail_id', $detail->id)
                                                ->pluck('service_value_tier', 'service_tier_id')
                                                ->toArray();
                                            
                                            $checked = isset($tierValuesForDetail[$tier->id]) && $tierValuesForDetail[$tier->id] === 'yes';
                                        @endphp
                                        <div class="col-3 text-center">
                                            <input type="checkbox"
                                                name="tier_service_value[{{ $detail->id }}][{{ $tier->id }}]"
                                                value="yes" {{ $checked ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach --}}

                            @foreach ($tiersCheckbox as $tier)
                                <div class="row border-top mt-2 pt-2">
                                    <div class="col-3 px-0">
                                        <p class="mb-0 ft-medium fs-md">{{ $tier->name }}</p>
                                        <input type="hidden" name="tier_id[]" value="{{ $tier->id }}">
                                    </div>

                                    @foreach ($serviceDetails as $detail)

                                        @php
                                            $checked = isset($tierValues[$detail->id][$tier->id]) 
                                                && $tierValues[$detail->id][$tier->id] === 'yes';
                                        @endphp

                                        <div class="col-3 text-center">
                                            <input type="checkbox"
                                                name="tier_service_value[{{ $detail->id }}][{{ $tier->id }}]"
                                                value="yes"
                                                {{ $checked ? 'checked' : '' }}>
                                        </div>

                                    @endforeach
                                </div>
                            @endforeach
        
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0">
                                    <h6 class="mb-0 ft-medium fs-md">Price</h6>
                                </div>
                                @foreach ($serviceDetails as $detail)
                                    <div class="col-3 pe-0 text-center">
                                        <input type="text" class="form-control rounded" placeholder="Price"
                                            name="price[{{ $loop->index + 1 }}]" value="{{ $detail->price }}">
                                    </div>
                                @endforeach
                            </div>
        
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0 pt-2">
                                    <h6 class="mb-0 ft-medium fs-md">Revisions</h6>
                                </div>
                                @foreach ($serviceDetails as $detail)
                                    <div class="col-3 pe-0 text-center">
                                        <select class="form-control" name="revision[{{ $loop->index + 1 }}]">
                                            <option>Select Revisions</option>
                                            @foreach (range(1, 9) as $i)
                                                <option {{ $detail->revision == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">{{ $i }}</option>
                                            @endforeach
                                            <option {{ $detail->revision == 'unlimited' ? 'selected' : '' }}
                                                value="unlimited">Unlimited</option>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
        
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0 pt-2">
                                    <h6 class="mb-0 ft-medium fs-md">Delivery Time</h6>
                                </div>
                                @foreach ($serviceDetails as $detail)
                                    <div class="col-3 pe-0 text-center">
                                        <input type="text" class="form-control rounded" placeholder="Delivery Time"
                                            name="time[{{ $loop->index + 1 }}]" value="{{ $detail->delivery_time }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row mt-3" id="b" style="{{ !$service->service_type ? '' : 'display:none;' }}">
                <div class="col-lg-12 col-md-12">
                    <div class="bg-light rounded mb-4 px-2">
                        <div class="py-3 px-3">
                            <div class="row">
                                <div class="col-3 px-0">
                                    <h5 class="text-green fs-5"> Packages </h5>
                                </div>
                                <div class="col-3 pe-0 text-center">
                                    <p class="bg-lgreen fw-bold mb-0 fs-16 fw-bold p-2">Lite</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-3 px-0">
                                    <h6 class="mb-0 ft-medium fs-md">Title <small class="fs-14 text-secondary">(max 60
                                            chars)</small></h6>
                                </div>
                                <div class="col-lg-3 pe-0 col-md-3 col-sm-3 col-4">
                                    <input type="text" class="form-control rounded" name="titlesin"
                                        value="{{ $serviceDetails[0]->title ?? '' }}" maxlength="60"
                                        placeholder="Title">
                                </div>
                            </div>
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0">
                                    <h6 class="mb-0 ft-medium fs-md">Description</h6>
                                </div>
                                <div class="col-3 pe-0">
                                    <textarea name="descsin" class="form-control with-light" maxlength="200" placeholder="Description">{{ $serviceDetails[0]->desc ?? '' }}</textarea>
                                </div>
                            </div>
        
                            @foreach ($tiersCheckbox as $tier)
                                @php
                                    // For single package, get tier values for the first service detail
                                    $tierValuesForSingle = DB::table('service_detail_tiers')
                                        ->where('service_detail_id', $serviceDetails[0]->id)
                                        ->pluck('service_value_tier', 'service_tier_id')
                                        ->toArray();
                                    
                                    $checked = isset($tierValuesForSingle[$tier->id]) && $tierValuesForSingle[$tier->id] === 'yes';
                                @endphp
        
                                <div class="row fv py-2">
                                    <div class="col-3 px-0">
                                        <h6 class="mb-0 ft-medium fs-md">{{ $tier->name }}</h6>
                                    </div>
        
                                    <div class="col-3 text-center">
                                        <input type="checkbox" name="tier_service_value1[{{ $tier->id }}]" value="yes"
                                            {{ $checked ? 'checked' : '' }}>
                                    </div>
                                </div>
                            @endforeach
        
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0 pt-2">
                                    <h6 class="mb-0 ft-medium fs-md">Price</h6>
                                </div>
                                <div class="col-3 pe-0">
                                    <input type="text" class="form-control rounded" placeholder="Price"
                                        name="pricesin" value="{{ $serviceDetails[0]->price ?? '' }}">
                                </div>
                            </div>
        
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0 pt-1">
                                    <h6 class="mb-0 ft-medium fs-md">Revisions</h6>
                                </div>
                                <div class="col-3 pe-0">
                                    <select class="form-control" name="revisionin">
                                        <option>Select Revisions</option>
                                        @foreach (range(1, 9) as $i)
                                            <option {{ ($serviceDetails[0]->revision ?? '') == $i ? 'selected' : '' }}
                                                value="{{ $i }}">{{ $i }}</option>
                                        @endforeach
                                        <option {{ ($serviceDetails[0]->revision ?? '') == 'unlimited' ? 'selected' : '' }}
                                                value="unlimited">Unlimited</option>
                                    </select>
                                </div>
                            </div>
        
                            <div class="row border-top mt-2 pt-2">
                                <div class="col-3 px-0 pt-2">
                                    <h6 class="mb-0 ft-medium fs-md">Delivery Time</h6>
                                </div>
                                <div class="col-3 pe-0">
                                    <input type="text" class="form-control rounded" placeholder="Delivery Time"
                                        name="timesin" value="{{ $serviceDetails[0]->delivery_time ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="extra-options-box" class="bg-white p-3 p-md-4 mt-3 shadow-sm border fs-14 rounded-2 mb-3">
            <h6 class="fw-bold text-dgreen">Add Extra (Optional)</h6>

            @foreach ($mergedCheckbox as $tier)
                @php
                    $hasAddons = $tier->addons->isNotEmpty();

                    $validAddons = $tier->addons->filter(function ($addon) {
                        return $addon->price > 0 || $addon->delivery_time > 0;
                    });
                @endphp

                <div class="form-check mb-3 border p-2 rounded">
                    <input class="form-check-input toggle-addon" type="checkbox" name="extra_tiers[]"
                        value="{{ $tier->id }}" id="tier_{{ $tier->id }}"
                        {{ $validAddons->isNotEmpty() ? 'checked' : '' }}>

                    <label class="form-check-label fw-bold" for="tier_{{ $tier->id }}">
                        {{ $tier->name }}
                    </label>
                </div>

                <div class="addon-fields mt-2" id="addon_fields_{{ $tier->id }}"
                    style="{{ $validAddons->isNotEmpty() ? 'display:block;' : 'display:none;' }}">

                    @forelse($validAddons as $addon)
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label>Delivery Time (in days)</label>
                                <input type="number" name="addon_delivery_time[{{ $tier->id }}][]"
                                    class="form-control" min="0" value="{{ $addon->delivery_time }}">
                            </div>
                            <div class="col-md-6">
                                <label>Extra Cost</label>
                                <input type="number" name="addon_price[{{ $tier->id }}][]" class="form-control"
                                    min="0" value="{{ $addon->price }}">
                            </div>
                        </div>

                    @empty
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label>Delivery Time (in days)</label>
                                <input type="number" name="addon_delivery_time[{{ $tier->id }}][]"
                                    class="form-control" min="0">
                            </div>
                            <div class="col-md-6">
                                <label>Extra Cost</label>
                                <input type="number" name="addon_price[{{ $tier->id }}][]" class="form-control"
                                    min="0">
                            </div>
                        </div>
                    @endforelse

                </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-end">
                @if ($review == 'true')
                    <a href="{{ route('user.service.edit', [$service->id, slug($service->title)]) }}"
                        class="btn btn-dark bg-dgreen me-2">Edit Service</a>
                    <a href="{{ url('/user/seller/dashboard') }}" class="btn btn-success bg-green">Post Service</a>
                @else
                    <button type="submit" class="btn btn-success bg-green">Update Service</button>
                @endif
            </div>
        </div>

        @if ($review == 'true')
        @else
            </form>
        @endif
    </div>
@endsection
@section('script')
    <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
    <script type="text/javascript">
        "use strict";

        // Put these at the very top of your main JS block or layout file, only once.

        let newskillcount = 0;
        let newexskillcount = 0;
        var checkload = [];
        let skills_arr = @json(old('skills', isset($service) && $service->skills ? $service->skills->pluck('id')->toArray() : []));
        let skills_name = [];
        let exskills_arr = new Set(@json($existing->pluck('id')));
        let exskills_name = @json($existing->pluck('name'));
        var oldTiers = @json(old('extra_tiers', []));
        var oldPrices = @json(old('addon_price', []));
        var oldDeliveryTimes = @json(old('addon_delivery_time', []));
        var oldFastTierTimes = @json(old('fast_tier_time', []));
        var oldFastTierPrices = @json(old('fast_tier_price', []));

        function gettiers(subcategory, childcategory) {
            // alert(subcategory);
            $.ajax({
                url: "{{ url('/Search_tiers') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    subcategory: subcategory,
                    childcategory: childcategory
                },
                success: function(response) {
                    if (response !== 0) {
                        var result = response;
                        console.log(result);
                        // Render the full HTML view in .filtered-tiers (Box 6)\
                        // alert(result.status);
                        if (result.status === 'true') {
                            $("#tiers-box").show();

                            $('.filtered-tiers').html(result.content_data);
                            $('.filtered-tiers').show();
                        }
                        let oldVarsScript = document.getElementById('scope-old-values');
                        console.log(oldVarsScript);
                        if (oldVarsScript) {
                            eval(oldVarsScript
                                .innerText); // make sure the window.old... variables are always refreshed!
                        }
                        console.log(typeof restoreTierOldValues);
                        if (typeof restoreTierOldValues === "function") {
                            restoreTierOldValues();
                        }

                        // Render only checkbox list in .extra_options (Box 7)
                        if (result.tiers_checkbox && result.tiers_checkbox.length > 0) {
                            let checkboxes = '';
                            let isMulti = $('#msval').is(':checked');

                            result.tiers_checkbox.forEach(function(item, index) {
                                let isChecked = oldTiers.includes(item.id.toString());
                                let price = oldPrices[item.id] || '';
                                let delivery = oldDeliveryTimes[item.id] || '';

                                let isFastDelivery = item.name.toLowerCase().includes('fast delivery');
                                let tierSelectHtml = '';

                                if (isFastDelivery) {
                                    if (isMulti) {
                                        tierSelectHtml = `<div class="multi-package-block">
                                                            ${generateTierBlock('lite', item.id)}
                                                            ${generateTierBlock('plus', item.id)}
                                                            ${generateTierBlock('ultimate', item.id)}
                                                        </div>`;
                                    } else {
                                        tierSelectHtml = `<div class="single-package-block">
                                                            ${generateTierBlock('lite', item.id)}
                                                        </div>`;
                                    }
                                } else {
                                    tierSelectHtml = `<div class="row mt-2">
                                            <div class="col-md-6">
                                                <label>Delivery Time (in days)</label>
                                                <input type="number" name="addon_delivery_time[${item.id}]" class="form-control" min="0" value="${delivery}">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Extra Cost</label>
                                                <input type="number" name="addon_price[${item.id}]" class="form-control" min="0" value="${price}">
                                            </div>
                                        </div>`;
                                }

                                checkboxes += `<div class="form-check mb-3 border p-2 rounded">
                                            <input class="form-check-input toggle-addon" type="checkbox" name="extra_tiers[]" value="${item.id}" id="tier_${index}" ${isChecked ? 'checked' : ''}>
                                            <label class="form-check-label fw-bold" for="tier_${index}">
                                                ${item.name}
                                            </label>
                                            <div class="addon-fields mt-2" id="addon_fields_${index}" style="${isChecked ? 'display:block;' : 'display:none;'}">
                                                ${tierSelectHtml}
                                            </div>
                                        </div>`;
                            });

                            $('.extra_options').html(checkboxes);
                            $('#extra-options-box').show();
                        } else {
                            $('.extra_options').empty();
                            $('#extra-options-box').hide();
                        }
                    }
                },
                error: function() {
                    alert('Failed to fetch tiers');
                }
            });
        }

        function getskills(c_id) {
            if (checkload.indexOf(c_id) !== -1) {
                //alert("Value exists!")
                return
            }
            $.ajax({
                type: "GET",
                url: "{{ route('user.service.getskills') }}",
                data: {
                    c_id: c_id
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {

                        html += 'No Skill  Availble';
                    } else {
                        $.each(data, function(name, id) {
                            html +=
                                `<div class="tab-pane nbv " style="display:inline;" id="tab-'+c_id+'" role="tabpanel">
                            <button type="button" onclick="selectskills(${id},'${name}',false)" value="${id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                            ${name}<i class="fa fa-plus ml-2"></i>
                            </button>&nbsp</div>`;
                        });
                    }
                    $('#demos').html(html);
                    // checkload.push(c_id);
                }
            });
        }

        function generateTierBlock(tierName, addonId) {
            let tierIndex = tierName === 'lite' ? 1 : tierName === 'plus' ? 2 : 3;

            let time = oldFastTierTimes[tierIndex] || '';
            let price = oldFastTierPrices[tierIndex] || '';

            return `<div class="tier-addon-block mb-2 border p-2 rounded">
                <strong class="text-capitalize">${tierName}</strong>
                <input type="hidden" name="fast_tier[${tierIndex}]" value="${addonId}">
                <div class="row mt-1">
                    <div class="col-md-6">
                        <label>Delivery Time (in days)</label>
                        <input type="number" name="fast_tier_time[${tierIndex}]" class="form-control" min="0" value="${time}">
                    </div>
                    <div class="col-md-6">
                        <label>Extra Cost</label>
                        <input type="number" name="fast_tier_price[${tierIndex}]" class="form-control" min="0" value="${price}">
                    </div>
                </div>
            </div>`;
        }

        function deletecer(id) {
            const imgDiv = document.getElementById('cer-' + id);
            if (imgDiv) {
                imgDiv.remove();
                deletedImageIDs.push(id);
                totalImages--;
                document.getElementById('deleted-optional-images').value = deletedImageIDs.join(',');
            }
        }

        bkLib.onDomLoaded(function() {
            const descriptionId = 'description';
            new nicEditor({
                fullPanel: true
            }).panelInstance(descriptionId);
            @if (old('description'))
                // Set the old content after a small delay to ensure the editor is ready
                setTimeout(function() {
                    nicEditors.findEditor(descriptionId).setContent(`{!! old('description') !!}`);
                }, 500);
            @endif
            setInterval(function() {
                let editor = nicEditors.findEditor(descriptionId);
                if (!editor) return;

                let rawHtml = editor.getContent();
                let plainText = rawHtml.replace(/<[^>]*>/g, '');
                const counter = document.getElementById('descCharCount');
                const counterBox = document.getElementById('descCounterBox');
                const overMsg = document.getElementById('descOverMsg');

                counter.textContent = plainText.length;

                if (plainText.length >= 2000) {
                    overMsg.style.display = '';
                } else {
                    overMsg.style.display = 'none';
                }
            }, 400);

            document.getElementById('serviceForm').addEventListener('submit', function(e) {
                let editor = nicEditors.findEditor(descriptionId);
                let plainText = editor.getContent().replace(/<[^>]*>/g, '');
                if (plainText.length > 2000) {
                    e.preventDefault();
                    alert('Service Description cannot exceed 2000 characters!');
                }
            });
        });

        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $(input).parents('.preview-thumb').find('.profilePicPreview');
                    $(preview).css('background-image', 'url(' + e.target.result + ')');
                    $(preview).addClass('has-image');
                    $(preview).hide();
                    $(preview).fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".profilePicUpload").on('change', function() {
            proPicURL(this);
        });
        $(".remove-image").on('click', function() {
            $(".profilePicPreview").css('background-image', 'none');
            $(".profilePicPreview").removeClass('has-image');
        })

        $('.addExtra').on('click', function() {
            var html = `
        <div class="col-lg-12 extraServiceRemove">
            <div class="row">
                <div class="col-xl-9 col-lg-12 form-group">
                        <input type="text" name="extra_title[]" class="form-control" placeholder="@lang('Enter title')" required>
                </div>
                <div class="col-xl-2 col-lg-12 form-group">
                    <div class="input-group mb-3">
                            <input type="text" class="form-control" name="extra_price[]" placeholder="@lang('Enter Price')" required="">
                            <span class="input-group-text" id="basic-addon2">{{ __($general->cur_text) }}</span>
                    </div>
                </div>
                <div class="col-xl-1">
                    <button type="button" class="btn btn--danger text-white border--rounded btn-sm mt-1 removeBtn">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>`;
            $('.addExtraService').append(html);
        });

        $(document).on('click', '.removeBtn', function() {
            $(this).closest('.extraServiceRemove').remove();
        });

        $('.addExtraImage').on('click', function() {
            var html = `
        <div class="custom-file-wrapper removeImage">
                <div class="custom-file" style="margin-top:20px">
                        <input type="file" class="custom-file-input" name="optional_image[]" id="customFile" required>
                        <label class="custom-file-label" for="customFile">@lang('Choose file')</label>
                </div>
                <button class="btn btn-danger text-white border-rounded removeExtraImage"><i class="fa fa-times"></i></button>
        </div>`;
            $('.addImage').append(html);
        });

        $(document).on('click', '.removeExtraImage', function() {
            $(this).closest('.removeImage').remove();
        });

        (function($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                $('.nicEdit-main').focus();
            });
        })(jQuery);

        $(document).on("change", ".custom-file-input", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $('#category').on('change', function() {
            const selectedCategoryId = $(this).val();
            loadSubcategories(selectedCategoryId);
        });

        function loadSubcategories(categoryId, selectedSubcat = null, selectedChildcat = null) {
            // $('.myChildCatgry').empty();
            // $('#demos').empty();
            // $('.filtered-tiers').empty();
            // $('#result').empty();
            // $('#skills_result').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('user.service.category') }}",
                data: {
                    category: categoryId
                },
                success: function(data) {
                    var html = '';
                    $("#subCategorys").empty();
                    if (data.error) {
                        html += `<option value="" selected disabled>${data.error}</option>`;
                        $(".mySubCatgry").html(html);
                    } else {
                        html += `<option value="" selected disabled>@lang('Select Category Level 2')</option>`;
                        $.each(data, function(index, item) {
                            let selected = selectedSubcat == item.id ? 'selected' : '';
                            html += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                        });
                        $(".mySubCatgry").html(html);

                        if (selectedSubcat) {
                            // Set subcategory and trigger change to load childcategories
                            setTimeout(function() {
                                $('#subCategorys').val(selectedSubcat).trigger('change');

                                // Optionally trigger load of childcategories with old value
                                if (selectedChildcat) {
                                    loadChildCategories(selectedSubcat, selectedChildcat);
                                }
                            }, 50);
                        }
                    }
                }
            });
        }

        $('#subCategorys').on('change', function() {
            var subcategory = $(this).val();
            $('#selected_skills_result').html('');
            getServiceFeatures(subcategory);
            gettiers(subcategory);
            $.ajax({
                type: "GET",
                url: "{{ route('user.subcategory') }}",
                data: {
                    subcategory: subcategory
                },
                success: function(data) {
                    var html = '';
                    //console.log(data);
                    if (data.error) {
                        $("#childCategorys").empty();
                        $(".myChildCatgry").hide();
                        // html += `<option value="" selected disabled>${data.error}</option>`;
                        html += ``;
                        $(".myChildCatgry").html(html);
                    } else {
                        $("#childCategorys").empty();
                        html +=
                            `<select name="childcategory" class="form-select col-6 tierchildcat" id="childCategorys" required><option value="" selected disabled >@lang('Select Category Level 3')</option>`;
                        $.each(data, function(index, item) {
                            html += `<option value="${item.id}">${item.name}</option>`;

                            $(".myChildCatgry").html(html);
                            $(".myChildCatgry").show();
                        });
                    }
                }
            });
        });

        $(".addcl").click(function() {
            $(".nbv").addClass("active");
            $(this).addClass("active");
        });

        function selectskills(s_id, name, check) {
            // prevent duplicate
            console.log(skills_arr);
            console.log(s_id);
            if (skills_arr.indexOf(s_id) !== -1) {
                return;
            }

            skills_arr.push(s_id);
            skills_name.push(name);

            let newButton = '<button type="button" onclick="unselectskill(' + s_id + ')" id="skill_btn' +
                s_id + '" value="' + s_id +
                '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + name +
                ' <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
                s_id + '" value="' + s_id + '"/>';


            $('#selected_skills_result').append(newButton);


            if (check == true) {
                $('#skil_result').html('');
                $('#search_skill').val('');
            }
        }
        //select new skill added
        function selectnewskill(name) {

            console.log("selectnewskill");
            let button_html = '';

            //check if skill already selected
            if (newskills_name.indexOf(name) !== -1) {
                console.log(newskills_arr);
                return
            }
            newskillcount++;
            //add new skill in array
            newskills_arr.push(newskillcount);
            newskills_name.push(name);
            for (let i = 0; i < skills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectskill(' + skills_arr[i] + ')" id="skill_btn' +
                    skills_arr[i] + '" value="' + skills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + skills_name[i] +
                    ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
                    skills_arr[i] + '" value="' + skills_arr[i] + '"/>';

            }
            for (let i = 0; i < newskills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectnewskill(' + newskills_arr[i] +
                    ')" id="newskill_btn' + newskills_arr[i] + '" value="' + skills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + newskills_name[i] +
                    ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
                    newskills_arr[i] + '" value="' + newskills_name[i] + '"/>';

            }
            $('#selected_skills_result').html(button_html);
            $('#skil_result').html('');
            $('#search_skill').val('');
        }

        function unselectskill(s_id) {
            //removing skill from skill array
            var index = skills_arr.indexOf(s_id);
            if (index !== -1) {
                skills_arr.splice(index, 1);
                skills_name.splice(index, 1);
            }
            $('#skill_btn' + s_id).remove();
            $('#job_skills' + s_id).remove();

        }

        function unselectnewskill(idx) {
            // turn Set into array so you can splice
            let ids = Array.from(exskills_arr);
            ids.splice(idx, 1);
            exskills_name.splice(idx, 1);

            // rebuild the Set
            exskills_arr = new Set(ids);

            // re-render exactly like above
            let html = '';
            for (let i = 0; i < ids.length; i++) {
                html +=
                    '<button type="button" onclick="unselectnewskill(' + i + ')" id="newskill_btn' + i +
                    '" value="' + ids[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' +
                    exskills_name[i] +
                    ' <i class="fa fa-times"></i></button>&nbsp;' +
                    '<input type="hidden" name="newskills[]" id="newjob_skills' + i +
                    '" value="' + ids[i] + '">';
            }
            $('#skills_result_extra').html(html);
        }
        
        //getting existing skill by search - MODIFIED to show/hide skil_result
        function getSkillbuname(search) {
            let html = '';
            const skilResultDiv = $('#skil_result');

            const maxSkills = 5;
            const skillsContainer = $('#skills_result_extra');
            const currentSkillCount = skillsContainer.find('button').length;

           
            if (currentSkillCount >= maxSkills) {
                skilResultDiv.html(`
                    <div class="alert alert-warning p-2 m-0" style="font-size: 12px;">
                        <i class="fas fa-exclamation-circle"></i> 
                        Maximum ${maxSkills} skills reached. 
                        <small>Remove skills before adding more.</small>
                    </div>
                `).show();
                if (search == "") {
                    skilResultDiv.html(''); // Clear content
                    skilResultDiv.hide(); // Hide the div
                    return;
                }
                return;
            }
            
            if (search == "") {
                skilResultDiv.html(''); // Clear content
                skilResultDiv.hide();   // Hide the div
                return;
            }
            
            // Show the skil_result div when there's a search
            skilResultDiv.show();
            
            $.ajax({
                type: "GET",
                url: "{{ route('user.service.getskillsbyname') }}",
                data: {
                    search: search
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {
                        html += `<b><a onclick="selectnewskill('${search}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${search}</a></b><hr>`;
                    } else {
                        html += "<div style='height:100px;overflow:scroll; overflow-x: hidden;'>";
                        $.each(data, function(name, id) {
                            html += `<p><b><a onclick="selectextraskills(${id},'${name}',true)" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;
                        });
                        html += '</div>';
                    }

                    skilResultDiv.html(html);
                    
                    // Also hide if no results
                    if (data.error && data.error === 'No skills found') {
                        skilResultDiv.hide();
                    }
                },
                error: function() {
                    skilResultDiv.html('');
                    skilResultDiv.hide();
                }
            });
        }

        $(function() {
            $('#colorselector').change(function() {
                $('.colors').hide();
                $('#' + $(this).val()).show();
                if ($(this).val() == 'all') {
                    $('.colors').show();
                }
            });
        });

        $('.tags').ready(function(s_id) {
            var t = document.getElementById('t');
            t.style.visibility = 'hidden';
            let button_html = '';

            var tags = $('.tags').val();
            console.log(tags.length);
            if (tags.indexOf(s_id) !== -1) {
                //alert("Value exists!")
                return
            }
            for (let i = 0; i < tags.length; i++) {

                button_html += '<button type="button" onclick="unselectnewskill(' + [i] + ')" id="newskill_btn' + [
                        i
                    ] + '" value="' + tags[i] + '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' +
                    tags[i] +
                    ' <i class="fa fa-times-circle"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
                    [i] + '" value="' + tags[i] + '"/>';

            }
            $('#skills_result1').html(button_html);
        });

        $('.deleteOptionalImage').on('click', function() {
            var modal = $('#deleteModal');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const fileExtension = input.files[0].name.split('.').pop().toLowerCase();
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                
                if (!allowedTypes.includes(input.files[0].type) || !allowedExtensions.includes(fileExtension)) {
                    alert('Only JPEG, JPG, and PNG images are allowed.');
                    input.value = '';
                    return;
                }
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#tt').bind('keyup', function(e) {
            var a = $(this).val().length;
            $('#ttcount').html(a);
        });

        function selectextraskills(s_id, name, check) {
            let button_html = '';
            s_id = String(s_id).trim();

            // already using a Set, so use .has and .add
            if (exskills_arr.has(s_id)) {
                return;
            }
            exskills_arr.add(s_id);
            exskills_name.push(name);
            console.log(exskills_arr);
            // to loop over a Set you must first turn it into an Array
            const ids = Array.from(exskills_arr);

            for (let i = 0; i < ids.length; i++) {
                button_html +=
                    '<button type="button" onclick="unselectnewskill(' + i + ')" id="newskill_btn' + i +
                    '" value="' + ids[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' +
                    exskills_name[i] +
                    ' <i class="fa fa-times"></i></button>&nbsp;' +
                    '<input type="hidden" name="newskills[]" id="newjob_skills' + i +
                    '" value="' + ids[i] + '">';
            }

            $('#skills_result_extra').html(button_html);
            
            // Hide skil_result after selecting a skill
            $('#skil_result').hide();
            $('#search_skill').val('');
            
            if (check === true) {
                $('#skil_result').html('');
                $('#search_skill').val('');
            }
        }

        let totalImages = {{ count($service->optionalImage ?? []) }};
        let deletedImageIDs = [];

        document.getElementById('more-images').addEventListener('change', function() {
            const preview = document.getElementById('more-images-preview');
            const newFiles = Array.from(this.files);

            if ((totalImages + newFiles.length) > 5) {
                alert('You can upload up to 5 additional images only.');
                this.value = ''; // Reset so user can try again
                return;
            }

            newFiles.forEach(file => {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                
                if (!allowedTypes.includes(file.type) || !allowedExtensions.includes(fileExtension)) {
                    alert('Only JPEG, JPG, and PNG images are allowed.');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'position-relative';
                    wrapper.style.width = '120px';
                    wrapper.style.height = '120px';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '5px';

                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1';
                    removeBtn.style.zIndex = '2';

                    removeBtn.onclick = () => {
                        preview.removeChild(wrapper);
                        totalImages--;
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    preview.appendChild(wrapper);

                    totalImages++;
                };
                reader.readAsDataURL(file);
            });
        });



        // delete more images
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('deleted-optional-images').value = JSON.stringify(deletedImageIDs);
        });


        const initialSubCategoryId = $('#subCategorys').val();
        if (initialSubCategoryId) {
            console.log(initialSubCategoryId);
            getServiceFeatures(initialSubCategoryId);
        }

        $(document).on('change', '.tierchildcat', function() {
            var subcategory = $('#subCategorys').val();
            var childcategory = $('#childCategorys').val();
            console.log(subcategory, childcategory);
            if (subcategory && childcategory)
                gettiers(subcategory, childcategory);
        });

        function getServiceFeatures(subCategory) {
            $.ajax({
                type: "GET",
                url: "{{ route('user.service.skillcategory') }}",
                data: {
                    subcategory: subCategory
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {

                        html += 'No Skill Category Availble';

                    } else {
                        $.each(data, function(name, id) {
                            html += '<li class="nav-item"><a onclick="getskills(' + id +
                                ')" type="button" class="nav-link addcl " id="nav-link"  data-toggle="tab" href="#tab-' +
                                id + '" role="tab">' + name + '</a></li>';
                        });
                    }

                    $('#result').html(html);


                    // Auto-click the first tab (if any)
                    setTimeout(function() {
                        $('#result .nav-link').first().trigger('click');
                    }, 100); // slight delay to ensure DOM is ready
                }
            });
        }

        $(function() {
            const isMulti = @json($isMulti);
            const fastDeliveryKey = 'fast delivery';
            var oldFastTierTimes = @json(old('fast_tier_time'));
            var oldFastTierPrices = @json(old('fast_tier_price'));
            // helper to build the inner HTML for "fast delivery" tiers
            function buildFastDeliveryHtml(addonId) {
                console.log("buildFastDeliveryHtml");
                if (isMulti) {
                    const packages = ['lite', 'plus', 'ultimate'];
                    const blocks = packages
                        .map(pkg => generateTierBlock(pkg, addonId))
                        .join('');
                    return `<div class="multi-package-block">${blocks}</div>`;
                } else {
                    return `<div class="single-package-block">
                        ${generateTierBlock('lite', addonId)}
                    </div>`;
                }
            }

            // core updater: show/hide & inject HTML if needed
            function updateAddon(checkbox) {
                console.log("updateAddon");
                const $cb = $(checkbox);
                const id = $cb.attr('id').split('_')[1];
                const $fields = $(`#addon_fields_${id}`);
                const labelTxt = $cb.next('label').text().toLowerCase();

                if ($cb.is(':checked')) {
                    $fields.show();
                    if (labelTxt.includes(fastDeliveryKey)) {
                        $fields.html(buildFastDeliveryHtml($cb.val()));
                    }
                } else {
                    $fields.hide();
                }
            }

            // init on page-load
            // $('.toggle-addon').each(function(){
            //     console.log("toggle-addon");
            //     updateAddon(this);
            // });

            // handle user toggles
            $(document).on('change', '.toggle-addon', function() {
                console.log("toggle-addon1");
                updateAddon(this);

            });

            function generateTierBlock(tierName, addonId) {
                console.log("function generateTierBlock");
                console.log(tierName);
                let tierIndex = tierName === 'lite' ? 1 : tierName === 'plus' ? 2 : 3;

                let time = oldFastTierTimes[tierIndex] || '';
                let price = oldFastTierPrices[tierIndex] || '';

                return `<div class="tier-addon-block mb-2 border p-2 rounded">
                    <strong class="text-capitalize">${tierName}</strong>
                    <input type="hidden" name="fast_tier[${tierIndex}]" value="${addonId}">
                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label>Delivery Time (in days)</label>
                            <input type="number" name="fast_tier_time[${tierIndex}]" class="form-control" min="0" value="${time}">
                        </div>
                        <div class="col-md-6">
                            <label>Extra Cost</label>
                            <input type="number" name="fast_tier_price[${tierIndex}]" class="form-control" min="0" value="${price}">
                        </div>
                    </div>
                </div>`;
            }
        });
    </script>
@endsection