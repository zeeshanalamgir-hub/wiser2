@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'scripts' => ['assets/templates/basic/scripts/js/data-ajax.js', 'assets/templates/basic/scripts/js/user/buyer/manage-jobs-edit.js'],
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
        <div class="col-12">
            <div class="card card-body shadow mt-2 p-4">
                <form action="{{ route('user.job.update', $job->id) }}" method="POST" enctype="multipart/form-data"
                    id="edit-job-form-5432346">
                    @csrf
                    <!-- Experience Level -->
                    <div class="mb-3">
                        <h6>Experience Level:</h6>
                        @foreach ([
            1 => 'Beginner',
            2 => 'Intermediate',
            3 => 'Expert',
        ] as $level => $label)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="experience_level"
                                    id="inlineRadio{{ $level }}" value="{{ $level }}"
                                    {{ old('experience_level', $job->exp_type) == $level ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="inlineRadio{{ $level }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('experience_level')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <!-- Job Title -->
                    <div class="my-3">
                        <label for="jobTitle" class="form-label">Job Title:</label>
                        <textarea class="form-control" name="job_title" id="jobTitle" rows="1">{{ old('job_title', $job->title) }}</textarea>
                        @error('job_title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Job Description -->
                    <div class="mb-3">
                        <label for="jobDescription" class="form-label">Describe your Job in details:</label>
                        <textarea class="form-control" name="job_description" id="jobDescription" rows="4">{!! old('job_description', $job->description) !!}</textarea>
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
                        <textarea class="form-control" name="requirements" id="requirements" rows="4">{!! old('requirements', $job->requirements) !!}</textarea>
                        <div id="title" class="form-text fs-12 text-secondary">
                            What are your requirements regarding Professional?
                        </div>
                        @error('requirements')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Attachment Section -->
                    <div class="input-group bg-lsgreen">
                        <input type="file" class="form-control" name="attachment[]" id="inputGroupFile02" multiple>

                    </div>
                    <div id="title" class="form-text fs-12 text-secondary mb-3">
                        Allowed file types: pdf, png, jpg, jpeg, doc, docx, xls, xlsx, ppt, pptx. Maximum file size: 250MB.
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <!-- Display existing attachments as small previews -->
                            @if ($job->attachments->isNotEmpty())
                                <div class="d-flex flex-wrap border rounded p-3" id="attachments-container">
                                    @foreach ($job->attachments as $attachment)
                                        <!-- Check if the attachment is an image -->
                                        @if (in_array(pathinfo($attachment->image_url, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx','xls','xlsx','ppt','pptx']))
                                            <div class="me-3 mb-2 position-relative attachment-item"
                                                data-attachment-id="{{ $attachment->id }}" style="display: inline-block;">
                                                <a href="{{ asset('storage/' . $attachment->image_url) }}" target="_blank" class="links-c text-center d-inline-block">
                                                    @php
                                                        $extension = strtolower(pathinfo($attachment->image_url, PATHINFO_EXTENSION));
                                                    @endphp
                                                    
                                                    <!-- Show different icons based on file type -->
                                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <img src="{{ asset('storage/' . $attachment->image_url) }}"
                                                            alt="View Attachment" class="img-thumbnail" style="width:100px; height:100px; object-fit:cover;">
                                                    @elseif($extension == 'pdf')
                                                        <i class="fas fa-file-pdf text-danger" style="font-size: 60px;"></i>
                                                    @elseif(in_array($extension, ['doc', 'docx']))
                                                        <i class="fas fa-file-word text-primary" style="font-size: 60px;"></i>
                                                    @elseif(in_array($extension, ['xls', 'xlsx']))
                                                        <i class="fas fa-file-excel text-success" style="font-size: 60px;"></i>
                                                    @elseif(in_array($extension, ['ppt', 'pptx']))
                                                        <i class="fas fa-file-powerpoint text-warning" style="font-size: 60px;"></i>
                                                    @elseif(in_array($extension, ['txt', 'rtf']))
                                                        <i class="fas fa-file-alt text-secondary" style="font-size: 60px;"></i>
                                                    @elseif(in_array($extension, ['zip', 'rar', '7z']))
                                                        <i class="fas fa-file-archive text-muted" style="font-size: 60px;"></i>
                                                    @else
                                                        <i class="fas fa-file text-muted" style="font-size: 60px;"></i>
                                                    @endif
                                                    
                                                    <div class="small text-muted mt-1">{{ strtoupper($extension) }}</div>
                                                </a>
                                                <!-- Cross Button to Remove -->
                                                <a class="text-decoration-none position-absolute start-100 translate-middle "
                                                    onclick="removeAttachment({{ $attachment->id }})"
                                                    style="cursor: pointer;">
                                                    <img src="{{ asset('assets/images/icons8-cross-48.png') }}"
                                                        width="15">
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p>No attachments found.</p>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="remove_attachments" id="remove_attachments" value="[]">
                    <!-- Categories and Subcategories -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text bg-lsgreen" for="categorySelect">Category</label>
                                <select class="form-select fs-14" name="category" id="categorySelect">
                                    <option disabled>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == old('category', $job->category_id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text bg-lsgreen" for="subcategorySelect">Sub Category</label>
                                <select class="form-select fs-14" name="subcategory" id="subcategorySelect">
                                    <option disabled>Choose...</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            {{ $subcategory->id == old('subcategory', $job->sub_category_id) ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subcategory')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Features (after subcategory selection) -->
                    <div class="row">
                        <div class="col-12 ms-3 mb-3 border rounded bg-light p-3" id="features_section">
                            <h5>Features:</h5>
                            <div id="skills_result" class="selected-features-row">
                                @foreach ($existingSkills as $index => $skill)
                                    <button type="button" onclick="unselectskill({{ $skill->id }})"
                                        id="skill_btn{{ $skill->id }}" value="{{ $skill->id }}"
                                        class="selected-feature-item">
                                        {{ $skill->name }} <i class="fa fa-times ml-2"></i>
                                    </button>&nbsp;
                                    <input type="hidden" name="skills[{{ $index }}][id]"
                                        value="{{ $skill->id }}">
                                    <input type="hidden" name="skills[{{ $index }}][name]"
                                        value="{{ $skill->name }}">
                                @endforeach
                            </div>
                            @error('skills')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <ul class="nav nav-tabs mt-3" role="tablist" id="result">
                                @forelse ($skillsBySubcategory as $skillCategory)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            id="tab{{ $skillCategory['id'] }}" data-bs-toggle="tab"
                                            href="#content{{ $skillCategory['id'] }}">{{ $skillCategory['name'] }}</a>
                                    </li>
                                @empty
                                    <li class="nav-item">
                                        <span class="nav-link">No skill categories available</span>
                                    </li>
                                @endforelse
                            </ul>
                            <div class="tab-content bg-white border border-top-0 rounded-bottom grid-container"
                                id="demos">
                                @foreach ($skillsBySubcategory as $skillCategory)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                        id="content{{ $skillCategory['id'] }}">
                                        @forelse ($skillCategory['skills'] as $skill)
                                            <button type="button"
                                                onclick="selectskills({{ $skill->id }}, '{{ addslashes($skill->name) }}', {{ $skillCategory['id'] }}, false)"
                                                id="skill_btn{{ $skill->id }}" value="{{ $skill->id }}"
                                                class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                                                {{ $skill->name }} <i class="fa fa-plus ml-2"></i>
                                            </button>&nbsp;
                                        @empty
                                            <p>No skills available for this category.</p>
                                        @endforelse
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Skills -->
                    <div class="mb-3">
                        <label for="skills" class="form-label">Find Pre-defined Skills or Type to Add (Max. 5):</label>
                        <!-- Display the selected skills -->
                        <div id="skills_result_extra" class="form-group m-0 px-0">
                            @if ($job->extraSkills->isNotEmpty())
                                @foreach ($job->extraSkills as $extraSkill)
                                    <div class="btn btn-secondary btn-sm m-1" id="badge_{{ $extraSkill->id }}">
                                        <span>{{ $extraSkill?->name }}</span>
                                        <button class="close" type="button" aria-label="Close" onclick="removeExtraSkill({{ $extraSkill->id }})">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            @endif
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

                    <div class="row">
                        <!-- Job Type -->
                        <div class="col-6 mb-3">
                            <h6>Budget Type:</h6>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="job_type" id="fixedType"
                                    value="fixed" {{ old('job_type', $job->budgettype) == 'fixed' ? 'checked' : '' }}>
                                <label class="form-check-label" for="fixedType">Fixed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="job_type" id="hourlyType"
                                    value="hourly" {{ old('job_type', $job->budgettype) == 'hourly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="hourlyType">Hourly</label>
                            </div>
                            @error('job_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6" id="budgetFields">
                            <h6>Budget:</h6>
                            <input type="number" class="form-control" min="0" step="1" id="amount"
                                name="amount" value="{{ old('amount', $job->amount) }}"
                                placeholder="@lang('Enter Budget')">
                            @foreach ($errors->get('amount') as $error)
                                <span class="text-danger">{{ $error }}</span><br>
                            @endforeach
                        </div>
                    </div>

                    <!-- Min and Max Budget + Due Date in the same row -->
                    <div class="row">
                        <div class="col-6" id="dueDateField"
                            style="display: {{ $job->delivery_date ? 'block' : 'none' }};">
                            <h6>Delivery Date:</h6>
                            <input type="date" class="form-control" name="due_date" id="dueDate"
                                value="{{ old('due_date', \Carbon\Carbon::parse($job->delivery_date)->format('Y-m-d')) }}">
                            @error('due_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="col-6" id="weeklyHoursField" style="display: none;">
                            <h6>Required weekly hours:</h6>
                            <input type="number" min=0 class="form-control" name="weekly_hours" id="weekly_hours"
                                placeholder="Enter no of max hours weekly"
                                value="{{ old('weekly_hours', $job?->weekly_hours) }}">
                            @error('weekly_hours')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <h6>Post as a*</h6>
                            <select class="form-select fs-14" name="priority" id="check1" required>
                                <option value="" {{ old('priority', $job->priority) === '' ? 'selected' : '' }}>
                                    Choose...</option>
                                <option value="1" {{ old('priority', $job->priority) == '1' ? 'selected' : '' }}>
                                    Regular</option>
                                <option value="2" {{ old('priority', $job->priority) == '2' ? 'selected' : '' }}>
                                    Featured</option>
                            </select>
                            @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <button class="btn btn-success bg-green mt-4  border-0" href=""
                        style="width:100px">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const existingSkills = @json($existingSkills);

            existingSkills.forEach(skill => {
                skills_arr.push(skill.id);
                skills_name.push(skill.name);
            });

            selectskills();

            const extraSkills = @json($job->extraSkills);

            extraSkills.forEach(skill => {
                selectextraskills(skill.id, skill.name, true);
            });

            if ($('#skil_result div').length === 0) {
                $('#skil_result').css('display', 'none');
            }
        });
        let removeAttachments = [];

        function removeAttachment(id) {
            removeAttachments.push(id);

            document.getElementById("remove_attachments").value = JSON.stringify(removeAttachments);
            const attachmentElement = document.querySelector(`[data-attachment-id="${id}"]`);
            if (attachmentElement) {
                attachmentElement.style.display = "none";
            }
        }

        $(document).on('click', '.close', function() {
            $(this).closest('.btn').remove();
        })

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
    </script>
@endsection
