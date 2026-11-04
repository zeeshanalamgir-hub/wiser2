@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'scripts' => ['assets/templates/basic/scripts/js/data-ajax.js', 'assets/templates/basic/scripts/js/user/buyer/manage-jobs.js'],
])
<style>
    .close {
        border-radius: 4px;
        margin-left: 5px;
        font-size: 10px;
    }
</style>
@section('content')
    <div class="row">
        <h5 class="text-dgreen2 mt-2">Create a New Job</h5>

        <div class="col-12">
            @if (!auth()->user()->is_payment_verified)
                <div class="col-md-12">
                    <p class="mb-1 text-danger">
                        You can create a job, but must
                        <a class="text-danger fw-bold" style="text-decoration: underline"
                            href="{{ route('user.deposittwo') }}">verify payment</a> and fund the job before sending offers
                        on received bids.
                    </p>
                    {{-- @include('templates.basic.user.common_components.profile_completeness') --}}
                </div>
            @endif

            <div class="card card-body shadow mt-2 p-4">
                <form action="{{ route('user.job.store') }}" method="POST" enctype="multipart/form-data"
                    id="jobForm">
                    @csrf

                    <!-- Experience Level -->
                    <div class="mb-3">
                        <h6>Experience Level:</h6>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="experience_level" id="inlineRadio1"
                                value="1" {{ old('experience_level') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio1">Beginner</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="experience_level" id="inlineRadio2"
                                value="2" {{ old('experience_level') == '2' ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio2">Intermediate</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="experience_level" id="inlineRadio3"
                                value="3" {{ old('experience_level') == '3' ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio3">Expert</label>
                        </div>
                        @error('experience_level')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Job Title -->
                    <div class="my-3">
                        <label for="jobTitle" class="form-label">Job Title:</label>
                        <textarea class="form-control" name="job_title" id="jobTitle" rows="1">{{ old('job_title') }}</textarea>
                        @error('job_title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Job Description -->
                    <div class="mb-3">
                        <label for="jobDescription" class="form-label">Describe your Job in details:</label>
                        <textarea class="form-control with-light" name="job_description" id="jobDescription" rows="4">{{ old('job_description') }}</textarea>
                        <div id="title" class="form-text fs-12 text-secondary">
                            What challenges do you need solving? What does the work consist of and what is its purpose?
                        </div>
                        @error('job_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Requirements -->
                    <div class="mb-3">
                        <label for="requirements" class="form-label">Requirements:</label>
                        <textarea class="form-control" name="requirements" id="requirements" rows="4">{{ old('requirements') }}</textarea>
                        <div id="title" class="form-text fs-12 text-secondary">
                            What are your requirements regarding Professional?
                        </div>
                        @error('requirements')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Attachment Section -->
                    <div class="input-group bg-lsgreen">
                        <input type="file" class="form-control" name="attachment[]" id="inputGroupFile02"  accept=".pdf,.png,.jpg,.jpeg,.doc,.docx,.xls,.xlsx,.ppt,.pptx">

                    </div>
                    <div id="title" class="form-text fs-12 text-secondary mb-3">
                        Allowed file types: pdf, png, jpg, jpeg, doc, docx, xls, xlsx, ppt, pptx. Maximum file size: 250MB.
                    </div>

                    <!-- Categories and Subcategories -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text bg-lsgreen" for="categorySelect">Category</label>
                                <select class="form-select fs-14" name="category" id="categorySelect">
                                    <option selected disabled>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $oldCategory == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text bg-lsgreen" for="subcategorySelect">Sub Category</label>
                                <select class="form-select fs-14" name="subcategory" id="subcategorySelect">
                                    <option selected disabled>Choose...</option>
                                    @if ($oldCategory)
                                        <!-- Load subcategories based on the old category -->
                                        <script>
                                            $(document).ready(function() {
                                                // Simulate fetching subcategories based on the old category
                                                getSubCategories({{ $oldCategory }}, {{ $oldSubcategory }});
                                            });
                                        </script>
                                    @endif
                                </select>
                            </div>
                            @error('subcategory')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <!-- Features (after subcategory selection) -->
                    <div class="row">
                        <div class="col-12 ms-3 mb-3 border rounded bg-light p-3" id="features_section"
                            style="display: none;">
                            <h5>Features:</h5>
                            <div id="skills_result" class="selected-features-row"></div>
                            @error('skills')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <ul class="nav nav-tabs mt-3" role="tablist" id="result"></ul>
                            <div class="tab-content bg-white border border-top-0 rounded-bottom grid-container"
                                id="demos"></div>
                        </div>
                    </div>
                    <!-- Skills -->
                    
                    <div class="mb-3">
                        <label for="skills" class="form-label">Find Pre-defined Skills or Type to Add (Max. 5):</label>
                        <!-- Display the selected skills -->
                        <div id="skills_result_extra" class="form-group m-0 px-0">

                        </div>

                        <!-- Skill search input -->
                        <div class="form-group m-0">
                            <input onkeyup="getSkillbuname(this.value)" id="search_skill" class="form-control"
                                type="text" autocomplete="off" name="keyword" placeholder="Search Skills...">
                            @error('extra_skills')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Error message for keyword search -->
                        <div id="keyword_error" class="text-danger" style="display:none;"></div>

                        <!-- Display dynamic skill search result -->
                        <div id="skil_result" class="form-group m-0"></div>
                    </div>

                    <div id="error" class="d-none alert alert-danger">
                        <p id="error-message"></p>
                    </div>
                    <!-- Job Type -->

                    <div class="row">
                        <!-- Job Type -->
                        <div class="col-md-6 mb-3">
                            <h6>Budget Type:</h6>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="job_type" id="fixedType"
                                    value="fixed" {{ old('job_type') == 'fixed' ? 'checked' : '' }}>
                                <label class="form-check-label" for="fixedType">Fixed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="job_type" id="hourlyType"
                                    value="hourly" {{ old('job_type') == 'hourly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="hourlyType">Hourly</label>
                            </div>
                            @error('job_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0" id="budgetFields">
                            <h6>Budget:</h6>
                            <input type="number" class="form-control" min="0" step="1" id="amount"
                                name="amount" value="{{ old('amount') }}" placeholder="@lang('Enter Budget')">
                            @foreach ($errors->get('amount') as $error)
                                <span class="text-danger">{{ $error }}</span><br>
                            @endforeach
                        </div>
                    </div>

                    <!-- Min and Max Budget + Due Date in the same row -->
                    <div class="row">
                        <div class="col-6" id="dueDateField" style="display: none;">
                            <h6>Delivery Date:</h6>
                            <input type="date" class="form-control" name="due_date" id="dueDate"
                                value="{{ old('due_date') }}">
                            @error('due_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6" id="weeklyHoursField" style="display: none;">
                            <h6>Required weekly hours:</h6>
                            <input type="number" min=0 class="form-control" name="weekly_hours" id="weekly_hours"
                                placeholder="Enter no of max hours weekly" value="{{ old('weekly_hours') }}">
                            @error('weekly_hours')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <h6>Post as a*</h6>
                            <select class="form-select fs-14" name="priority" id="check1" required>
                                <option value="" selected>Choose...</option>
                                <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Regular</option>
                                <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Featured</option>
                            </select>
                            @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-success bg-green mt-4  border-0" href=""
                        style="width:100px">Submit</button>
                </form>

            </div>
        </div>
    </div>
@endsection
@php
    $oldExtraSkills = old('extra_skills', []);
@endphp
@section('script')
    <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>

    <script>
        var oldSkills = {!! json_encode(old('skills')) !!};
        var extraSkills = {!! json_encode(old('extra_skills')) !!};
        if (extraSkills == null) {
            $('#skil_result').css('display', 'none');
        }
        // Ensure `extraSkills` is an array
        if (Array.isArray(extraSkills)) {
            extraSkills.forEach(skillString => {
                // Parse each string directly and access `id` and `name`
                const skill = JSON.parse(skillString);
                console.log("Skill =>",skill);
                if (skill.id && skill.name) {
                    selectextraskills(skill.id, skill.name, true); // Pass the skill ID and name to the function
                }
            });
        }

        const customNicEditors = {};

        bkLib.onDomLoaded(function() {
            const elementId = 'jobDescription';
            const description = document.getElementById(elementId);

            if (description) {
                if (!customNicEditors[elementId]) {
                    customNicEditors[elementId] = new nicEditor().panelInstance(elementId);
                }
            }
        });

        bkLib.onDomLoaded(function() {
            const elementId = 'requirements';
            const description = document.getElementById(elementId);

            if (description) {
                if (!customNicEditors[elementId]) {
                    customNicEditors[elementId] = new nicEditor().panelInstance(elementId);
                }
            }
        });

        let oldExtraSkills = @json($oldExtraSkills);

        if (oldExtraSkills.length > 0) {
            oldExtraSkills.forEach(skill => {
                console.log("Old extra skills =>",skill);
                try {
                    let parsed = JSON.parse(skill); // convert string → object
                    console.log("parsed extra skills",parsed);

                    selectextraskills(parsed.id, parsed.name, true);
                } catch (e) {
                    console.error("Invalid JSON skill:", skill);
                }
            });
        }
        
    </script>

@endsection
