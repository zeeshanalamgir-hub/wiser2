@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'hideSecondaryHeader' => true,
    'scripts' => ['assets/templates/basic/scripts/js/bidding.js'],
])
<style>
    .matching-skill {
        background-color: #d1e7dd;
        color: #0f5132;
        padding: 5px;
        margin-left: 5px;
        border-radius: 4px;
        border: 1px solid #0f5132;
    }
    .removeMilestoneBtn:hover {
        background: white !important;
    }
    .read-more-content {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        overflow: hidden;
    }

    .read-more-content.expanded {
        display: block;
    }

    .read-more-btn {
        border: none;
        background: none;
        padding: 5px 0;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }

    .links-c {
        color: #007bff;
    }

    .read-more-btn:hover {
        text-decoration: underline;
    }

</style>
@section('content')
    <p class="mt-3 text-3gray"> <!--Breadcrumbs-->
        <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12"></i><a href="/job" class="text-3gray" style="text-decoration:none;"> Jobs</a>
        <i class="fa-solid fa-chevron-right fs-12"></i> <a href="/job/details/{{ encrypt($job->id) }}" class="text-3gray" style="text-decoration:none;"> Job Details </a>
        <i class="fa-solid fa-chevron-right fs-12"></i> Bid Now
    </p>
    <!-- User Info Section -->
    <div class="row bg-white px-2 py-3 p-md-3 ms-md-1 mt-md-2 shadow-sm border fs-14 rounded-2">
        <div class="col-8 col-md-9 d-flex">
            @if (!is_null(Auth::user()->image))
                {{--  <img src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . Auth::user()->image, null) }}"  --}}
                <img src="{{ Storage::url(Auth::user()->image) }}"
                    alt="@lang('Profile Image')" class="rounded-circle me-2" width="45" height="45">
            @else
                <div class="d-flex justify-content-center">
                    <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                        style="height:45px;width:45px;font-size:20px">
                        {{ strtoupper(substr($job?->user?->firstname ?? 'A', 0, 1)) }}
                        {{ strtoupper(substr($job?->user?->lastname ?? 'A', 0, 1)) }}
                    </div>
                </div>
            @endif
            <div style="width:400px; margin-left:10px;">
                <h6 class="mb-0 pt-1 ">{{ Auth::user()->fullname }}</h6>
                <p class="fs-12 text-secondary">{{ Auth::user()->designation }} </p>
            </div>
        </div>

        <div class="col-4 col-md-3 float-end text-end mt-1">
            <p class="text-secondary"> <i class="fa-regular fa-clock me-1 bg-light text-warning"></i> Posted
                {{ diffforhumans($job->created_at) }}</p>
        </div>

        <h5 class=""> {!! $job->title !!}</h5>
        <p class="fs-14 text-3gray"> <i class="fas fa-briefcase"></i> {{ $job->exp_type_text }} |
            {{--  <i class="fas fa-dollar-sign" style="color:#a2a2a2"></i>  --}}
             {{ $job->job_type }} |
            <i class="fas fa-dollar-sign"></i>{{ $job->amount }}
        </p>

        {{-- <p class="fs-14"><strong>Description</strong>: {!! $job->description !!}</p>
        <p class="fs-14"><strong>Requirements</strong>: {!! $job->requirements !!}</p> --}}
        <div class="job-text">
            <strong>Description</strong>
            <div class="read-more-content">
                {!! $job->description !!}
            </div>
            <button type="button" class="read-more-btn links-c">Read More</button>
        </div>

        <div class="job-text my-2">
            <strong>Requirements</strong>
            <div class="read-more-content">
                {!! $job->requirements !!}
            </div>
            <button type="button" class="read-more-btn links-c">Read More</button>
        </div>

        {{-- <p style="line-height:40px;">
            @if (isset($job->skills) && count($job->skills) > 0 && $job->extraSkills && count($job->extraSkills) > 0)
                @foreach ($job->skills as $skill)
                    @if ($matchingSkills->contains( $skill->name ))
                        <span class="{{ !$loop->first ? 'ms-2' : '' }} matching-skill">{{ __($skill->name) }}</span>
                    @else
                        <span class="tags {{ !$loop->first ? 'ms-2' : '' }}">{{ __($skill->name) }}</span>
                    @endif
                @endforeach
                @foreach($job->extraSkills as $extraSkill)
                    @if ($matchingSkills->contains( $extraSkill->name))
                        <span class=" ms-2 matching-skill">{{ __($extraSkill->name) }}</span>
                    @else
                        <span class="tags ms-2">{{ __($extraSkill->name) }}</span>
                    @endif
                @endforeach
            @else
                <span class="ms-2 text-muted">No skills available.</span>
            @endif
        </p> --}}

        @php
            $hasMatchingSkills = false;

            foreach ($job->skills ?? [] as $skill) {
                if ($matchingSkills->contains($skill->name)) {
                    $hasMatchingSkills = true;
                    break;
                }
            }

            if (!$hasMatchingSkills) {
                foreach ($job->extraSkills ?? [] as $extraSkill) {
                    if ($matchingSkills->contains($extraSkill->name)) {
                        $hasMatchingSkills = true;
                        break;
                    }
                }
            }
        @endphp

        @if($hasMatchingSkills)
            <p class="text-success fw-semibold my-2">
                Your highlighted skills align with project requirements
            </p>
        @endif

        <p style="line-height:40px;">
            @if (isset($job->skills) && count($job->skills) > 0 || isset($job->extraSkills) && count($job->extraSkills) > 0)

                @foreach ($job->skills as $skill)
                    @if ($matchingSkills->contains($skill->name))
                        <span class="{{ !$loop->first ? 'ms-2' : '' }} matching-skill">
                            {{ __($skill->name) }}
                        </span>
                    @else
                        <span class="tags {{ !$loop->first ? 'ms-2' : '' }}">
                            {{ __($skill->name) }}
                        </span>
                    @endif
                @endforeach

                @foreach($job->extraSkills as $extraSkill)
                    @if ($matchingSkills->contains($extraSkill->name))
                        <span class="ms-2 matching-skill">
                            {{ __($extraSkill->name) }}
                        </span>
                    @else
                        <span class="tags ms-2">
                            {{ __($extraSkill->name) }}
                        </span>
                    @endif
                @endforeach

            @else
                <span class="ms-2 text-muted">No skills available.</span>
            @endif
        </p>
        <div class="row">
            <div class="col-md-9 col-lg-10">
                <p> <i class="fas fa-user-check text-secondary"></i>
                    {{ $job?->user?->is_payment_verified == 1 ? 'Verified' : 'Unverified' }} <i {{--  --}}
                        class="fas fa-user-friends text-green ms-4"></i> {{ count($job?->user?->jobWorkDeliveryBids) }}
                    {{ Str::plural('Offer', count($job?->user?->jobWorkDeliveryBids)) }} <i
                        class="fas fa-map-marker-alt text-green fs-6 ms-4"></i> {{ $job?->user?->country ?? 'Worldwide' }}
                </p>
            </div>
            <div class="col-md-3 col-lg-2 pe-0">
                <button class="btn btn-sm btn-success bg-green border-0 float-md-end"
                    onclick="location.href='{{ url('/job/details/' . encrypt($job->id)) }}';">
                    View Details
                </button>
            </div>
        </div> <!--Card end-->

    </div>

            <!-- Bidding Details Section -->

    <form enctype="multipart/form-data" method="post" id="fixed-milestone-job-bidding-form-253243"
            action="{{ url('user/seller/job/addbiding') }}" onsubmit="return validateBidForm()">
            @csrf <!-- Laravel CSRF Token -->
            <input name="job_amount" value="{{ $job->amount }}" type="hidden" />
            <input name="job_id" value="{{ $job->id }}" type="hidden" />
            @if (isset($existingBid))
                <input name="bidId" value="{{ encrypt($existingBid->id) }}" type="hidden" />
            @endif
            <input name="job_membershipfee" value="{{ $membershipFee }}" type="hidden" />
            <div class="row bg-white px-2 py-3  ms-md-1  shadow-sm border fs-14 rounded-2 mt-4">
                <div class="row">
                    <div class="col-12 col-md-6 ">
                        <h5 class="card-title main-color fw-bold fs-5 text-dgreen2 mt-2">Bidding Details</h5>
                    </div>
                    <!-- Client Budget -->
                    <div class="col-12 col-md-6  my-2 text-end">
                        <label for="clientBudget" class="form-label text-secondary fs-6">Client Budget: ${{ $job->amount }}</label>
                        {{-- <label for="clientBudget" class="form-label fs-6">DueDate:
                            {{ \Carbon\Carbon::parse($job->delivery_date)->format('F j, Y') }} |</label>
                        <label for="clientBudget" class="form-label  fs-6">In Days:
                            <span>{{ $deliveryTimeInDays }} </span></label> --}}
                    </div>

                    <!-- Payment Method -->
                    <div id="paymentMethodSection">

                        <!-- Payment Option -->
                        {{-- <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="projectmethod" id="byProject"
                                value="byproject"
                                {{ old('projectmethod', isset($existingBid) && trim($existingBid->job_type) === 'byproject') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="byProject">By Project</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="projectmethod" id="byMilestone"
                                value="bymilestone"
                                {{ old('projectmethod', isset($existingBid) && trim($existingBid->job_type) === 'bymilestone') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="byMilestone">By Milestone</label>
                        </div> --}}

                        <button type="button" id="showProjectMethods" class="btn btn-sm {{ isset($existingBid) ? 'd-none' : '' }} btn-success bg-green border-0 mt-3">
                            Choose Payment Method
                        </button>

                        <div id="projectMethodsContainer" class="d-none mt-3">
                            <h6>How do you want to pay?</h6>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="projectmethod" id="byProject"
                                    value="byproject"
                                    {{ old('projectmethod', isset($existingBid) && trim($existingBid->job_type) === 'byproject') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="byProject">
                                    By Project
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="projectmethod" id="byMilestone"
                                    value="bymilestone"
                                    {{ old('projectmethod', isset($existingBid) && trim($existingBid->job_type) === 'bymilestone') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="byMilestone">
                                    By Milestone
                                </label>
                            </div>

                        </div>

                        <!-- Project Section -->
                        <div id="projectSection" class="mt-3">
                            <label for="projectAmount" class="form-label fw-semibold">Amount*</label>
                            <input oninput="validateInput(this, {{ showAmount($membershipFee) }}, {{ $job->amount }})"
                                id="input_bid" type="number" name="bid_price" oninput="customValidate(this)" data-min="1"
                                data-max="{{ showAmount($job->amount) }}" class="form-control fw-semibold" placeholder=""
                                value="{{ old('bid_price', isset($existingBid) ? $existingBid->bid_price : '') }}">
                            <div id="job_bid_price_error" class="text-danger" style="display: none"></div>

                            <label for="projectDays" class="form-label mt-2 fw-semibold">Days*</label>
                            <input type="number" min="0" class="form-control fw-semibold" name="due_date" id="due_date"
                                placeholder=""
                                value="{{ old('due_date', isset($existingBid) ? $existingBid->due_date : '') }}">
                            <div id="job_due_date_error" class="text-danger" style="display: none"></div>
                            <div class="row mb-3 fw-semibold mt-2" id="platform_fee">
                                <div class="col-md-6">
                                    <label for="platformFee" class="form-label">Platform Fee
                                        ({{ $amntPercent }}%):</label>

                                    <input id="fee" name="cutfix" placeholder="$0" readonly
                                        class="form-control fw-semibold"
                                         style="background-color:#e9ecef;"
                                        value="{{ isset($existingBid) ? ($existingBid->bid_price * $amntPercent) / 100 : 0 }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="youGetPaid" class="form-label">You'll Get Paid:</label>
                                    <input id="total" readonly name
                                        value="{{ isset($existingBid) ? $existingBid->bid_price - ($existingBid->bid_price * $amntPercent) / 100 : 0 }}"
                                        class="form-control fw-semibold" style="background-color:#e9ecef;" placeholder="$0">
                                </div>
                            </div>
                        </div>
                        <!-- Milestone Section -->
                        <div id="milestoneSection" class="mt-3">
                            <input type="hidden" value="{{ showAmount($job->amount) }}" name="job_price" />

                            <div id="milestoneContainer">
                                <div class="milestone-item row dynamic-field" id="dynamic-field-1">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="milestone" class="form-label fw-semibold mt-2">Milestone*</label>
                                            <input id="field" type="text" class="form-control"
                                                name="milestone_title[]">
                                            <div id="job_milestone_title_error" class="text-danger"
                                                style="display: none"></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="milestoneAmount"
                                                class="form-label fw-semibold mt-2">Amount*</label>
                                            <input type="text" class="form-control"
                                                name="milestone_amount[]" id="milestone_amount"
                                                min="0"
                                                step="1"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                >
                                            <div id="job_milestone_amount_error" class="text-danger"
                                                style="display: none"></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="milestoneDays" class="form-label fw-semibold mt-2">Days*</label>
                                            <input type="number" id="milestone_days" class="form-control"
                                                name="milestone_due_date[]">
                                            <div id="job_milestone_due_date_error" class="text-danger"
                                                style="display: none"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <button type="button" id="addNewMilestoneBtn"
                                class="btn btn-sm btn-outline-success mt-3">+ Add More</button>

                            <div class="row mb-3 fw-semibold mt-2" id="platform_fee">
                                <div class="col-md-4">
                                    <label for="platformFee" class="form-label">Job Budget*:</label>
                                    <input id="job_price" readonly
                                        class="form-control milestone_job_total_price fw-semibold">
                                    <div id="job_milestone_total_error" class="text-danger" style="display: none"></div>
                                </div>
                                <div class="col-md-4">
                                    <label for="platformFee" class="form-label">Platform Fee
                                        ({{ $amntPercent }}%):</label>
                                    <input id="cutmile" name="cutmile" readonly class="form-control fw-semibold"
                                        value="{{ $amntPercent }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="youGetPaid" class="form-label">You'll Get Paid:</label>
                                    <input id="totalmulti" readonly name value="{{ isset($bid) ? $bid->total : 0 }}"
                                        class="form-control fw-semibold">
                                </div>
                            </div>

                        </div>


                    </div>

                    <!-- Cover Letter -->
                    <div class="mb-3">
                        <label for="coverLetter" class="form-label fw-semibold">Cover Letter <span class="text-danger">*</span></label>
                        <textarea rows="10" cols="10" id="proposal" name="proposal"
                            class="form-control border-2 with-light p-3" required>{!! $existingBid->proposal ?? '' !!}</textarea>

                        <div id="job_proposal_error" class="text-danger" style="display:none;"></div>
                    </div>

                    <!-- Attachment -->
                    <div class="mb-3">
                        <label for="attachment" class="form-label fw-semibold">Attachment <span class="text-danger">*</span></label>
                        <input class="form-control border-2 border-secondary-subtle" type="file" id="attachment"
                            name="attachment[]" accept=".png, .jpg, .jpeg, .pdf, .doc, .docx" multiple required>
                        <small class="form-text text-muted">Maximum 5 files, each up to 2MB. Choose file(png, jpg, pdf,
                            doc)</small>
                        <div id="file_error" class="text-danger" style="display:none;">
                        </div>
                        @if ($editMode && $attachments->isNotEmpty())
                            <div class="mt-3">
                                <label class="form-label fw-semibold">Existing Attachments:</label>
                                <ul>
                                    @foreach ($attachments as $attachment)
                                        <li>
                                            <a href="{{ asset('storage/' . $attachment->image_url) }}" download>
                                                <i class="fa-solid fa-download text-green"></i>
                                                Download <!-- Display the file name -->
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Submit Button -->
                        <div class="mt-2">
                            <button type="submit" id="submitBidBtn" class="btn bg-green border-0 btn-success">Submit Bid</button>
                        </div>
                    </div>
                </div>
        </form>
</div>
@endsection
<script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
<script>
    const isEditMode = {{ json_encode($editMode) }};
    const reviseBidData = {!! $reviseBidData !!};

    const customNicEditors = {};

    document.addEventListener('DOMContentLoaded', function () {
        const elementId = 'proposal';

        if (typeof bkLib !== 'undefined') {
            bkLib.onDomLoaded(function () {
                // Initialize nicEditor
                new nicEditor({ fullPanel: true }).panelInstance(elementId);
            });
        }

        // Ensure editor content is synced BEFORE form submission
        const submitBtn = document.getElementById('submitBidBtn');
        submitBtn?.addEventListener('click', function (e) {
            if (typeof nicEditors !== 'undefined' && nicEditors.findEditor(elementId)) {
                nicEditors.findEditor(elementId).saveContent();
            }
            
            // Manual validation before form submission
            if (!validateBidForm()) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Form validation function
    function validateBidForm() {
        let isValid = true;
        let firstError = null;
        
        // Reset all error messages
        document.querySelectorAll('.text-danger').forEach(el => el.style.display = 'none');
        
        // Check payment method selection
        const projectMethod = document.querySelector('input[name="projectmethod"]:checked');
        if (!projectMethod) {
            alert('Please select a payment method');
            return false;
        }
        
        // Validate based on selected payment method
        if (projectMethod.value === 'byproject') {
            // Validate bid amount
            const bidPrice = document.querySelector('input[name="bid_price"]');
            if (!bidPrice.value || parseFloat(bidPrice.value) <= 0) {
                document.getElementById('job_bid_price_error').style.display = 'block';
                document.getElementById('job_bid_price_error').textContent = 'Bid amount is required';
                isValid = false;
                if (!firstError) firstError = bidPrice;
            }
            
            // Validate due date
            const dueDate = document.querySelector('input[name="due_date"]');
            if (!dueDate.value || parseInt(dueDate.value) <= 0) {
                document.getElementById('job_due_date_error').style.display = 'block';
                document.getElementById('job_due_date_error').textContent = 'Days required is required';
                isValid = false;
                if (!firstError) firstError = dueDate;
            }
        } else if (projectMethod.value === 'bymilestone') {
            // Validate milestone fields
            const milestoneTitles = document.querySelectorAll('input[name="milestone_title[]"]');
            const milestoneAmounts = document.querySelectorAll('input[name="milestone_amount[]"]');
            const milestoneDates = document.querySelectorAll('input[name="milestone_due_date[]"]');
            
            for (let i = 0; i < milestoneTitles.length; i++) {
                if (!milestoneTitles[i].value.trim()) {
                    document.getElementById('job_milestone_title_error').style.display = 'block';
                    document.getElementById('job_milestone_title_error').textContent = 'Milestone title is required';
                    isValid = false;
                    if (!firstError) firstError = milestoneTitles[i];
                    break;
                }
                
                if (!milestoneAmounts[i].value || parseFloat(milestoneAmounts[i].value) <= 0) {
                    document.getElementById('job_milestone_amount_error').style.display = 'block';
                    document.getElementById('job_milestone_amount_error').textContent = 'Milestone amount is required';
                    isValid = false;
                    if (!firstError) firstError = milestoneAmounts[i];
                    break;
                }
                
                if (!milestoneDates[i].value || parseInt(milestoneDates[i].value) <= 0) {
                    document.getElementById('job_milestone_due_date_error').style.display = 'block';
                    document.getElementById('job_milestone_due_date_error').textContent = 'Milestone days is required';
                    isValid = false;
                    if (!firstError) firstError = milestoneDates[i];
                    break;
                }
            }
            
            // Validate total milestone amount
            const totalAmount = document.querySelector('.milestone_job_total_price');
            if (totalAmount && parseFloat(totalAmount.value) <= 0) {
                document.getElementById('job_milestone_total_error').style.display = 'block';
                document.getElementById('job_milestone_total_error').textContent = 'Total milestone amount must be greater than 0';
                isValid = false;
            }
        }
        
        // Validate cover letter (proposal)
        const proposal = document.getElementById('proposal');
        let proposalContent = proposal.value;
        
        // If using nicEditor, get the content from editor
        if (typeof nicEditors !== 'undefined' && nicEditors.findEditor('proposal')) {
            proposalContent = nicEditors.findEditor('proposal').getContent();
        }
        
        // Strip HTML tags and check if there's actual content
        const plainText = proposalContent.replace(/<[^>]*>/g, '').trim();
        if (!plainText) {
            document.getElementById('job_proposal_error').style.display = 'block';
            document.getElementById('job_proposal_error').textContent = 'Cover letter is required';
            isValid = false;
            if (!firstError) firstError = proposal;
        }
        
        // Validate attachment
        const attachment = document.getElementById('attachment');
        if (!isEditMode || (isEditMode && attachment.files.length === 0)) {
            if (attachment.files.length === 0) {
                document.getElementById('file_error').style.display = 'block';
                document.getElementById('file_error').textContent = 'At least one attachment is required';
                isValid = false;
                if (!firstError) firstError = attachment;
            }
            
            // Validate file count (max 5)
            if (attachment.files.length > 5) {
                document.getElementById('file_error').style.display = 'block';
                document.getElementById('file_error').textContent = 'Maximum 5 files allowed';
                isValid = false;
                if (!firstError) firstError = attachment;
            }
            
            // Validate file size (max 2MB each)
            for (let i = 0; i < attachment.files.length; i++) {
                if (attachment.files[i].size > 2 * 1024 * 1024) {
                    document.getElementById('file_error').style.display = 'block';
                    document.getElementById('file_error').textContent = 'Each file must be less than 2MB';
                    isValid = false;
                    if (!firstError) firstError = attachment;
                    break;
                }
            }
            
            // Validate file types
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'application/msword', 
                                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            for (let i = 0; i < attachment.files.length; i++) {
                if (!allowedTypes.includes(attachment.files[i].type)) {
                    document.getElementById('file_error').style.display = 'block';
                    document.getElementById('file_error').textContent = 'Only PNG, JPG, PDF, DOC, DOCX files are allowed';
                    isValid = false;
                    if (!firstError) firstError = attachment;
                    break;
                }
            }
        }
        
        // Scroll to first error
        if (!isValid && firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
        
        return isValid;
    }

    // Add real-time validation for file input
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('attachment');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const errorDiv = document.getElementById('file_error');
                errorDiv.style.display = 'none';
                
                if (this.files.length > 5) {
                    errorDiv.style.display = 'block';
                    errorDiv.textContent = 'Maximum 5 files allowed';
                    this.value = ''; // Clear the input
                }
                
                for (let i = 0; i < this.files.length; i++) {
                    if (this.files[i].size > 2 * 1024 * 1024) {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = 'Each file must be less than 2MB';
                        this.value = ''; // Clear the input
                        break;
                    }
                }
            });
        }
        
        document.getElementById('showProjectMethods').addEventListener('click', function () {

            let projectMethodsContainer = document.getElementById('projectMethodsContainer');
            let showButton = document.getElementById('showProjectMethods');

            projectMethodsContainer.classList.remove('d-none');
            showButton.classList.add('d-none');

        });
    });

    // Read More Script
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.read-more-btn').forEach(function (button) {
            const content = button.previousElementSibling;

            // Hide button if content doesn't need expanding
            if (content.scrollHeight <= 100) {
                button.style.display = 'none';
                return;
            }

            button.addEventListener('click', function () {
                content.classList.toggle('expanded');

                if (content.classList.contains('expanded')) {
                    button.textContent = 'Read Less';
                } else {
                    button.textContent = 'Read More';
                }
            });
        });
    });

</script>