@extends($activeTemplate . 'layouts.wiserlance.layout')
@section('content')
<p class="mb-0 mt-3 text-3gray"> <!--Breadcrumbs-->
    <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
    <i class="fa-solid fa-chevron-right fs-12"></i><a href="/job" class="text-3gray" style="text-decoration:none;"> Jobs</a>
    <i class="fa-solid fa-chevron-right fs-12"></i> <a href="/job/details/{{ $job_id }}" class="text-3gray" style="text-decoration:none;">Job Details</a>
    <i class="fa-solid fa-chevron-right fs-12"></i> Invite Freelancers
</p>

<div id="contents" class="col-xxl-12 col-xl-12 mb-3">
    <!--Box 1-->
    <div class="bg-white p-4 mt-3 shadow-sm border fs-14 rounded-2">
        <h6 class="fw-bold">Details</h6>
        <div class="row pt-3 ps-2">
            <div class="col-md-3">
                <label class="text-green fw-bold mb-1">Seller*</label>
                <select name="freelancer_id" id="freelancer" class="form-select">
                    <option value="">Select Seller</option>
                    @foreach ($professionals as $professional)
                        <option value="{{ $professional->id }}"
                            {{ old('freelancer_id') == $professional->id ? 'selected' : '' }}
                            data-designation="{{ $professional->designation }}">
                            {{ $professional->firstname . ' ' . $professional->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-none" id="designation-div">
                <label class="text-green fw-bold mb-1">Designation*</label>
                <p class="text-dark fw-normal border p-2 rounded-2" id="designation"></p>
            </div>
            <div class="col-md-6">
                <label for="jobType" class="text-green fw-bold mb-1">Select Job *</label>
                <select id="changejob"
                    class="p-2 mx-2 border-2 border mb-2 rounded-2 d-flex justify-content-between align-items-center col-12 col-md-6"
                    name="changejob" required>
                    <option value="">Selected Job</option>
                    @foreach ($buyersPostedJobs as $job)
                        <option value="{{ $job->id }}" data-job-type="{{ $job->budgettype }}" data-job-amount="{{ $job->amount }}"
                            {{ old('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!--Box 3 - Offer Form-->
    <div id="box3" class="bg-white mb-3 p-4 mt-3 shadow-sm border fs-14 rounded-2"
        style="display: {{ old('job_id') ? 'block' : 'none' }};">
        
        <form action="{{ url('user/seller/job/addbiding') }}" method="POST" enctype="multipart/form-data" id="submit-offer-form" onsubmit="return validateInviteForm()">
            @csrf
            <input type="hidden" name="job_type" id="selectedJobType" value="{{ old('job_type') }}">
            <input type="hidden" name="job_id" id="job_id" value="{{ old('job_id') }}">
            <input type="hidden" name="reciever" id="reciever" value="{{ old('reciever') }}">
            <input type="hidden" name="invite_professional" value="1">
            <input type="hidden" name="job_amount" id="job_amount" value="">
            <input type="hidden" name="job_membershipfee" id="job_membershipfee" value="{{ $membershipFee ?? 0 }}">
            <input type="hidden" name="projectmethod" id="projectmethod" value="hourly">


            <!-- Hourly Section -->
            <div id="hourlySection" class="row g-3 ps-3 fs-14 pt-3"
                style="display: {{ old('job_id') && old('job_type') == 'hourly' ? 'block' : 'none' }};">
                <h5 class="card-title main-color fw-bold fs-5 text-dgreen2 mt-2">Hourly Bidding Details</h5>
                
                <div class="row mb-3 ps-2">
                    <div class="col-4">
                        <label class="form-label fw-semibold">Hourly Rate *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-end-0">$</span>
                            </div>
                            <input type="number" id="hourlyRate" name="hourly_amount"
                                class="form-control border-start-0 border-end-0" aria-label="Hourly Rate"
                                value="{{ old('hourly_amount') }}" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light border-start-0">hr</span>
                            </div>
                        </div>
                        <div id="hourly_rate_error" class="text-danger" style="display: none"></div>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold">Minimum Hour Per Week *</label>
                        <div class="input-group">
                            <input type="number" id="minHoursPerWeek" name="weekly_hours"
                                class="form-control border-end-0" aria-label="Minimum Hour Per Week"
                                value="{{ old('weekly_hours') }}" step="1">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light border-start-0">hr</span>
                            </div>
                        </div>
                        <div id="weekly_hours_error" class="text-danger" style="display: none"></div>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold">Deposit Fund</label>
                        <div class="input-group">
                            <input type="number" id="depositFund" name="depositfund" class="form-control border-end-0"
                                aria-label="Deposit Fund" value="{{ old('depositfund') }}" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text bg-light border-start-0">$</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Platform Fee for Hourly -->
                <div class="row mb-3 fw-semibold" id="hourly_platform_fee">
                    <div class="col-md-6">
                        <label for="hourlyPlatformFee" class="form-label">Platform Fee ({{ $membershipFee ?? 4 }}%):</label>
                        <input id="hourlyPlatformFee" name="hourly_cutfix" placeholder="$0" readonly class="form-control fw-semibold" value="0">
                    </div>
                    <div class="col-md-6">
                        <label for="hourlyYouGetPaid" class="form-label">You'll Get Paid:</label>
                        <input id="hourlyYouGetPaid" readonly class="form-control fw-semibold" placeholder="$0" value="0">
                    </div>
                </div>
            </div>

            <!-- Fixed Section -->
            <div id="fixedSection" style="display: {{ old('job_type') == 'fixed' ? 'block' : 'none' }};">
                <h5 class="card-title main-color fw-bold fs-5 text-dgreen2 mt-2">Fixed Price Bidding Details</h5>
                
                <div id="paymentMethodSection" class="ps-2 py-2">
                    <h6 class="fw-semibold fs-14">How would you like to pay?</h6>
                    <div class="row mb-3 ps-2">
                        {{-- <div class="col-2">
                            <input class="form-check-input" type="radio" name="projectmethod" id="byProject"
                                value="byproject" {{ old('projectmethod', 'byproject') == 'byproject' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="byProject">By Project</label>
                        </div>
                        <div class="col-2">
                            <input class="form-check-input" type="radio" name="projectmethod" id="byMilestone"
                                value="bymilestone" {{ old('projectmethod') == 'bymilestone' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="byMilestone">By Milestone</label>
                        </div> --}}

                        <div class="col-2">
                            <input class="form-check-input" type="radio" name="projectmethod" id="byProject"
                                value="byproject" selected
                                {{ old('projectmethod', isset($existingBid) && trim($existingBid->job_type) === 'byproject') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="byProject">By Project</label>
                        </div>
                        <div class="col-2">
                            <input class="form-check-input" type="radio" name="projectmethod" id="byMilestone"
                                value="bymilestone"
                                {{ old('projectmethod', isset($existingBid) && trim($existingBid->job_type) === 'bymilestone') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="byMilestone">By Milestone</label>
                        </div>
                    </div>

                    

                    <!-- Project Section -->
                    <div id="projectSection" class="mt-3"
                        style="display: {{ old('projectmethod', 'byproject') == 'byproject' ? 'block' : 'none' }};">
                        <div class="row mb-3 ps-2">
                            <div class="col-6">
                                <label for="projectAmount" class="form-label fw-semibold">Bid Amount *</label>
                                <input type="number" class="form-control fw-semibold" id="project_amount" name="bid_price"
                                    placeholder="$0" value="{{ old('bid_price') }}" step="0.01"
                                    oninput="validateProjectAmount(this)">
                                <div id="project_amount_error" class="text-danger" style="display: none"></div>
                            </div>
                            <div class="col-6">
                                <label for="projectDueDate" class="form-label fw-semibold">Days Required *</label>
                                <input type="number" name="due_date" class="form-control fw-semibold"
                                    value="{{ old('due_date') }}" placeholder="Days required" step="1">
                                <div id="due_date_error" class="text-danger" style="display: none"></div>
                            </div>
                        </div>
                        
                        <!-- Platform Fee for Project -->
                        <div class="row mb-3 d-none fw-semibold" id="project_platform_fee">
                            <div class="col-md-4">
                                <label class="form-label">Job Budget:</label>
                                <input id="job_budget_display" readonly class="form-control fw-semibold" value="$0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Platform Fee ({{ $membershipFee ?? 4 }}%):</label>
                                <input id="project_cutfix" name="cutfix" readonly class="form-control fw-semibold" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">You'll Get Paid:</label>
                                <input id="project_total" readonly class="form-control fw-semibold" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Milestone Section -->
                    <div id="milestoneSection" class="mt-3"
                        style="display: {{ old('projectmethod') == 'bymilestone' ? 'block' : 'none' }};">
                        <input type="hidden" value="{{ old('job_price', 0) }}" name="job_price" id="milestone_job_price" />
                        
                        <div id="milestoneContainer">
                            <div class="milestone-item row dynamic-field mb-3" id="dynamic-field-1">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="milestone" class="form-label fw-semibold">Milestone Title *</label>
                                        <input type="text" class="form-control" name="milestone_title[]" 
                                            placeholder="Enter milestone title" value="{{ old('milestone_title.0') }}">
                                        <div class="milestone_title_error text-danger" style="display: none"></div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="milestoneAmount" class="form-label fw-semibold">Amount *</label>
                                        <input type="number" step="0.01" class="form-control milestone_amount" 
                                            name="milestone_amount[]" placeholder="$0" 
                                            value="{{ old('milestone_amount.0') }}" oninput="calculateMilestoneTotal()">
                                        <div class="milestone_amount_error text-danger" style="display: none"></div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="milestoneDueDate" class="form-label fw-semibold">Days *</label>
                                        <input type="number" step="1" class="form-control" name="milestone_due_date[]" 
                                            placeholder="Number of days" value="{{ old('milestone_due_date.0') }}">
                                        <div class="milestone_due_date_error text-danger" style="display: none"></div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    {{-- <button type="button" class="btn btn-danger btn-sm removeMilestoneBtn mt-4" 
                                        style="display: none;" onclick="removeMilestone(this)">
                                        <i class="fas fa-times"></i> Remove
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="addNewMilestoneBtn" class="btn btn-sm btn-outline-success text-green mt-2">
                            <i class="fas fa-plus"></i> Add More Milestone
                        </button>
                        
                        <!-- Platform Fee for Milestone -->
                        <div class="row mb-3 fw-semibold mt-3 d-none" id="milestone_platform_fee">
                            <div class="col-md-4">
                                <label class="form-label">Total Milestone Amount:</label>
                                <input id="milestone_total_amount" readonly class="form-control milestone_job_total_price fw-semibold" value="0">
                                <div id="milestone_total_error" class="text-danger" style="display: none"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Platform Fee ({{ $membershipFee ?? 4 }}%):</label>
                                <input id="milestone_cutfix" name="cutmile" readonly class="form-control fw-semibold" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">You'll Get Paid:</label>
                                <input id="milestone_total_paid" readonly class="form-control fw-semibold" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cover Letter -->
            <div class="row ps-3 fs-14 mt-3">
                <div class="col-md-12">
                    <label for="coverLetter" class="form-label fw-semibold">Cover Letter *</label>
                    <textarea name="proposal" class="p-2 mx-2 border-2 border mb-1 rounded-2 col-12 with-light md:col-5"
                        style="height: 150px;" required>{{ old('proposal') }}</textarea>
                    <div id="proposal_error" class="text-danger" style="display: none;"></div>
                </div>
            </div>

            <!-- Attachment -->
            <div class="row mt-3 ps-3 fs-14">
                <label for="attachment" class="form-label fw-semibold">Attachment *</label>
                <input type="file" id="attachment" name="attachment[]" multiple
                    class="p-2 mx-2 border-2 border rounded-2 col-11 md:col-5"
                    accept=".png, .jpg, .jpeg, .pdf, .doc, .docx">
                <p class="fst-italic fs-12 ms-2 mt-1">Note: Maximum 5 files, each up to 2MB. Allowed: png, jpg, pdf, doc, docx</p>
                <div id="file_error" class="text-danger ms-2" style="display: none;"></div>
            </div>

            <!-- Submit Button -->
            <div class="mt-4 ps-3">
                <button type="submit" id="submitOfferBtn" class="btn btn-success bg-green border-0">Submit Offer</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    // Global variables
    const amntPercent = {{ $membershipFee ?? 4 }};
    let currentJobAmount = 0;
    
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize nicEditor for cover letter
       

        // Set up event listeners
        const jobTypeSelect = document.getElementById("changejob");
     
        const paymentMethodRadios = document.querySelectorAll("input[name='projectmethod']");
        const box3 = document.getElementById("box3");
        const hourlySection = document.getElementById("hourlySection");
        const fixedSection = document.getElementById("fixedSection");
        const projectSection = document.getElementById("projectSection");
        const milestoneSection = document.getElementById("milestoneSection");

          
        
        // Freelancer selection
        const freelancerSelect = document.getElementById("freelancer");
        const receiverInput = document.getElementById("reciever");
        const designationDiv = document.getElementById("designation-div");
        const designationText = document.getElementById("designation");
        
        if (freelancerSelect) {
            freelancerSelect.addEventListener('change', function() {
                const freelancerId = this.value;
                if (receiverInput) receiverInput.value = freelancerId;
                
                const designation = this.options[this.selectedIndex]?.getAttribute('data-designation');
                
                if (designation && designation !== '' && designation !== null) {
                    designationDiv.classList.remove('d-none');
                    designationText.textContent = designation;
                } else {
                    designationDiv.classList.add('d-none');
                    designationText.textContent = '';
                }
            });
        }
        
        // Job selection change handler
        if (jobTypeSelect) {
            jobTypeSelect.addEventListener("change", function() {
                const selectedOption = this.options[this.selectedIndex];
                const selectedJobType = selectedOption.getAttribute("data-job-type");
                const selectedJobAmount = selectedOption.getAttribute("data-job-amount");
                
                currentJobAmount = parseFloat(selectedJobAmount) || 0;
                
                // Update hidden fields
                const jobIdInput = document.getElementById("job_id");
                const selectedJobTypeInput = document.getElementById("selectedJobType");
                const jobAmountInput = document.getElementById("job_amount");
                const jobBudgetDisplay = document.getElementById("job_budget_display");
                const milestoneJobPrice = document.getElementById("milestone_job_price");
                
                if (jobIdInput) jobIdInput.value = this.value;
                if (selectedJobTypeInput) selectedJobTypeInput.value = selectedJobType;
                if (jobAmountInput) jobAmountInput.value = currentJobAmount;
                if (jobBudgetDisplay) jobBudgetDisplay.value = '$' + currentJobAmount.toFixed(2);
                if (milestoneJobPrice) milestoneJobPrice.value = currentJobAmount;
                
                // Show box3
                box3.style.display = "block";
                
                // Show/hide sections based on job type
                if (selectedJobType === "hourly") {
                    hourlySection.style.display = "block";
                    fixedSection.style.display = "none";
                } else if (selectedJobType === "fixed") {
                    hourlySection.style.display = "none";
                    fixedSection.style.display = "block";
                    
                    // Trigger payment method change to update display
                    const selectedPaymentMethod = document.querySelector("input[name='projectmethod']:checked");
                    if (selectedPaymentMethod) {
                        togglePaymentSections(selectedPaymentMethod.value);
                    }
                }
            });
        }
        
        // Toggle payment sections function
        function togglePaymentSections(value) {
            const projectSectionEl = document.getElementById("projectSection");
            const milestoneSectionEl = document.getElementById("milestoneSection");
            
            if (value === 'byproject') {
                if (projectSectionEl) projectSectionEl.style.display = "block";
                if (milestoneSectionEl) milestoneSectionEl.style.display = "none";
            } else if (value === 'bymilestone') {
                if (projectSectionEl) projectSectionEl.style.display = "none";
                if (milestoneSectionEl) milestoneSectionEl.style.display = "block";
            }
        }
        
        // Payment method radio change handler
        paymentMethodRadios.forEach(radio => {
            radio.addEventListener("change", function() {
                if (this.checked) {
                    togglePaymentSections(this.value);
                }
            });
        });
        
        // Hourly rate calculation
        const hourlyRateInput = document.getElementById("hourlyRate");
        const minHoursPerWeekInput = document.getElementById("minHoursPerWeek");
        const depositFundInput = document.getElementById("depositFund");
        const hourlyPlatformFee = document.getElementById("hourlyPlatformFee");
        const hourlyYouGetPaid = document.getElementById("hourlyYouGetPaid");
        
        function calculateHourlyDeposit() {
            const hourlyRate = parseFloat(hourlyRateInput?.value) || 0;
            const minHoursPerWeek = parseFloat(minHoursPerWeekInput?.value) || 0;
            const depositFund = hourlyRate * minHoursPerWeek;
            
            if (depositFundInput) depositFundInput.value = depositFund.toFixed(2);
            
            // Calculate platform fee
            const platformFee = (depositFund * amntPercent) / 100;
            const youGetPaid = depositFund + platformFee; // hourly we add the platform not deduct
            // const youGetPaid = depositFund - platformFee;
            
            if (hourlyPlatformFee) hourlyPlatformFee.value = '$' + platformFee.toFixed(2);
            if (hourlyYouGetPaid) hourlyYouGetPaid.value = '$' + youGetPaid.toFixed(2);
        }
        
        if (hourlyRateInput) hourlyRateInput.addEventListener("input", calculateHourlyDeposit);
        if (minHoursPerWeekInput) minHoursPerWeekInput.addEventListener("input", calculateHourlyDeposit);
        
        // Project amount validation and calculation
        const projectAmountInput = document.getElementById("project_amount");
        
        window.validateProjectAmount = function(input) {
            const value = parseFloat(input.value) || 0;
            if (value > currentJobAmount && currentJobAmount > 0) {
                document.getElementById("project_amount_error").style.display = "block";
                document.getElementById("project_amount_error").textContent = `Bid amount cannot exceed job budget of $${currentJobAmount.toFixed(2)}`;
                input.value = currentJobAmount;
            } else {
                document.getElementById("project_amount_error").style.display = "none";
            }
            calculateProjectFees();
        };
        
        function calculateProjectFees() {
            const bidPrice = parseFloat(projectAmountInput?.value) || 0;
            const platformFee = (bidPrice * amntPercent) / 100;
            const youGetPaid = bidPrice - platformFee;
            
            const projectCutfix = document.getElementById("project_cutfix");
            const projectTotal = document.getElementById("project_total");
            
            if (projectCutfix) projectCutfix.value = '$' + platformFee.toFixed(2);
            if (projectTotal) projectTotal.value = '$' + youGetPaid.toFixed(2);
        }
        
        if (projectAmountInput) projectAmountInput.addEventListener("input", calculateProjectFees);
        
        // Milestone functions
        let milestoneCounter = document.querySelectorAll('.milestone-item').length;
        
        window.calculateMilestoneTotal = function() {
            const milestoneAmounts = document.querySelectorAll('.milestone_amount');
            let total = 0;
            
            milestoneAmounts.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            
            const totalAmountInput = document.getElementById("milestone_total_amount");
            const milestoneCutfix = document.getElementById("milestone_cutfix");
            const milestoneTotalPaid = document.getElementById("milestone_total_paid");
            const totalError = document.getElementById("milestone_total_error");
            
            if (totalAmountInput) totalAmountInput.value = '$' + total.toFixed(2);
            
            // Validate against job budget
            if (currentJobAmount > 0 && total > currentJobAmount) {
                if (totalError) {
                    totalError.style.display = "block";
                    totalError.textContent = `Total milestone amount ($${total.toFixed(2)}) exceeds job budget of $${currentJobAmount.toFixed(2)}`;
                }
            } else {
                if (totalError) totalError.style.display = "none";
            }
            
            // Calculate fees
            const platformFee = (total * amntPercent) / 100;
            const youGetPaid = total - platformFee;
            
            if (milestoneCutfix) milestoneCutfix.value = platformFee.toFixed(2);
            if (milestoneTotalPaid) milestoneTotalPaid.value = youGetPaid.toFixed(2);
        };
        
        // Add milestone button
        const addMilestoneBtn = document.getElementById("addNewMilestoneBtn");
        if (addMilestoneBtn) {
            addMilestoneBtn.addEventListener("click", function() {
                const milestoneContainer = document.getElementById("milestoneContainer");
                const milestoneCount = document.querySelectorAll('.milestone-item').length + 1;
                const newMilestone = document.createElement('div');
                newMilestone.classList.add('milestone-item', 'row', 'dynamic-field', 'mb-3');
                newMilestone.innerHTML = `
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Milestone Title *</label>
                            <input type="text" class="form-control" name="milestone_title[]" placeholder="Enter milestone title">
                            <div class="milestone_title_error text-danger" style="display: none"></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Amount *</label>
                            <input type="number" step="0.01" class="form-control milestone_amount" name="milestone_amount[]" placeholder="$0" oninput="calculateMilestoneTotal()">
                            <div class="milestone_amount_error text-danger" style="display: none"></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Days *</label>
                            <input type="number" step="1" class="form-control" name="milestone_due_date[]" placeholder="Number of days">
                            <div class="milestone_due_date_error text-danger" style="display: none"></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger btn-sm removeMilestoneBtn mt-4" onclick="removeMilestone(this)">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                `;
                milestoneContainer.appendChild(newMilestone);
                
                // Show all remove buttons if more than one milestone
                if (document.querySelectorAll('.milestone-item').length > 1) {
                    document.querySelectorAll('.removeMilestoneBtn').forEach(btn => btn.style.display = 'block');
                }
            });
        }
        
        window.removeMilestone = function(button) {
            const milestoneItem = button.closest('.milestone-item');
            if (milestoneItem) {
                milestoneItem.remove();
                calculateMilestoneTotal();
                
                // Hide remove buttons if only one milestone left
                if (document.querySelectorAll('.milestone-item').length === 1) {
                    document.querySelectorAll('.removeMilestoneBtn').forEach(btn => btn.style.display = 'none');
                }
            }
        };
        
        // Initialize remove buttons visibility
        if (document.querySelectorAll('.milestone-item').length === 1) {
            document.querySelectorAll('.removeMilestoneBtn').forEach(btn => btn.style.display = 'none');
        }
        
        // Form validation
        window.validateInviteForm = function() {
            let isValid = true;
            let firstError = null;
            
            // Reset all error messages
            document.querySelectorAll('.text-danger').forEach(el => el.style.display = 'none');
            
            // Validate freelancer selection
            const freelancer = document.getElementById("freelancer");
            if (!freelancer?.value) {
                alert('Please select a seller/freelancer');
                return false;
            }
            
            // Validate job selection
            const jobSelect = document.getElementById("changejob");
            if (!jobSelect?.value) {
                alert('Please select a job');
                return false;
            }
            
            // Get job type
            const selectedJobType = document.getElementById("selectedJobType")?.value;
            
            if (selectedJobType === "hourly") {
                // Validate hourly rate
                const hourlyRate = document.getElementById("hourlyRate");
                if (!hourlyRate?.value || parseFloat(hourlyRate.value) <= 0) {
                    const errorDiv = document.getElementById("hourly_rate_error");
                    if (errorDiv) {
                        errorDiv.style.display = "block";
                        errorDiv.textContent = "Hourly rate is required and must be greater than 0";
                    }
                    isValid = false;
                    if (!firstError) firstError = hourlyRate;
                }
                
                // Validate minimum hours
                const minHours = document.getElementById("minHoursPerWeek");
                if (!minHours?.value || parseInt(minHours.value) <= 0) {
                    const errorDiv = document.getElementById("weekly_hours_error");
                    if (errorDiv) {
                        errorDiv.style.display = "block";
                        errorDiv.textContent = "Minimum hours per week is required and must be greater than 0";
                    }
                    isValid = false;
                    if (!firstError) firstError = minHours;
                }
                
            } else if (selectedJobType === "fixed") {
                const paymentMethod = document.querySelector("input[name='projectmethod']:checked")?.value;
                
                if (paymentMethod === "byproject") {
                    // Validate project amount
                    const projectAmount = document.getElementById("project_amount");
                    if (!projectAmount?.value || parseFloat(projectAmount.value) <= 0) {
                        const errorDiv = document.getElementById("project_amount_error");
                        if (errorDiv) {
                            errorDiv.style.display = "block";
                            errorDiv.textContent = "Bid amount is required and must be greater than 0";
                        }
                        isValid = false;
                        if (!firstError) firstError = projectAmount;
                    }
                    
                    // Validate due date
                    const dueDate = document.querySelector("input[name='due_date']");
                    if (!dueDate?.value || parseInt(dueDate.value) <= 0) {
                        const errorDiv = document.getElementById("due_date_error");
                        if (errorDiv) {
                            errorDiv.style.display = "block";
                            errorDiv.textContent = "Days required is required and must be greater than 0";
                        }
                        isValid = false;
                        if (!firstError) firstError = dueDate;
                    }
                    
                } else if (paymentMethod === "bymilestone") {
                    // Validate milestone fields
                    const milestoneTitles = document.querySelectorAll("input[name='milestone_title[]']");
                    const milestoneAmounts = document.querySelectorAll("input[name='milestone_amount[]']");
                    const milestoneDates = document.querySelectorAll("input[name='milestone_due_date[]']");
                    
                    for (let i = 0; i < milestoneTitles.length; i++) {
                        if (!milestoneTitles[i].value.trim()) {
                            const errorDiv = milestoneTitles[i].closest('.form-group')?.querySelector('.milestone_title_error');
                            if (errorDiv) {
                                errorDiv.style.display = "block";
                                errorDiv.textContent = "Milestone title is required";
                            }
                            isValid = false;
                            if (!firstError) firstError = milestoneTitles[i];
                            break;
                        }
                        
                        if (!milestoneAmounts[i].value || parseFloat(milestoneAmounts[i].value) <= 0) {
                            const errorDiv = milestoneAmounts[i].closest('.form-group')?.querySelector('.milestone_amount_error');
                            if (errorDiv) {
                                errorDiv.style.display = "block";
                                errorDiv.textContent = "Milestone amount is required and must be greater than 0";
                            }
                            isValid = false;
                            if (!firstError) firstError = milestoneAmounts[i];
                            break;
                        }
                        
                        if (!milestoneDates[i].value || parseInt(milestoneDates[i].value) <= 0) {
                            const errorDiv = milestoneDates[i].closest('.form-group')?.querySelector('.milestone_due_date_error');
                            if (errorDiv) {
                                errorDiv.style.display = "block";
                                errorDiv.textContent = "Days required is required and must be greater than 0";
                            }
                            isValid = false;
                            if (!firstError) firstError = milestoneDates[i];
                            break;
                        }
                    }
                    
                    // Validate total milestone amount
                    const totalAmount = document.getElementById("milestone_total_amount");
                    const totalError = document.getElementById("milestone_total_error");
                    if (totalAmount && parseFloat(totalAmount.value.replace('$', '')) <= 0) {
                        if (totalError) {
                            totalError.style.display = "block";
                            totalError.textContent = "Total milestone amount must be greater than 0";
                        }
                        isValid = false;
                    }
                }
            }
            
            //Validate cover letter (proposal)
            // const proposal = document.getElementById("proposal");
            // let proposalContent = proposal?.value || '';
            
            // if (typeof nicEditors !== 'undefined' && nicEditors.findEditor('proposal')) {
            //     proposalContent = nicEditors.findEditor('proposal').getContent();
            // }
            
            // const plainText = proposalContent.replace(/<[^>]*>/g, '').trim();
            // if (!plainText) {
            //     const errorDiv = document.getElementById("proposal_error");
            //     if (errorDiv) {
            //         errorDiv.style.display = "block";
            //         errorDiv.textContent = "Cover letter is required";
            //     }
            //     isValid = false;
            //     if (!firstError) firstError = proposal;
            // }
            
            // Validate attachment
            const attachment = document.getElementById("attachment");
            if (attachment && attachment.files.length === 0) {
                const errorDiv = document.getElementById("file_error");
                if (errorDiv) {
                    errorDiv.style.display = "block";
                    errorDiv.textContent = "At least one attachment is required";
                }
                isValid = false;
                if (!firstError) firstError = attachment;
            }
            
            if (attachment && attachment.files.length > 5) {
                const errorDiv = document.getElementById("file_error");
                if (errorDiv) {
                    errorDiv.style.display = "block";
                    errorDiv.textContent = "Maximum 5 files allowed";
                }
                isValid = false;
                if (!firstError) firstError = attachment;
                attachment.value = '';
            }
            
            if (attachment && attachment.files.length > 0) {
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 
                                     'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                for (let i = 0; i < attachment.files.length; i++) {
                    if (!allowedTypes.includes(attachment.files[i].type)) {
                        const errorDiv = document.getElementById("file_error");
                        if (errorDiv) {
                            errorDiv.style.display = "block";
                            errorDiv.textContent = "Only PNG, JPG, PDF, DOC, DOCX files are allowed";
                        }
                        isValid = false;
                        if (!firstError) firstError = attachment;
                        attachment.value = '';
                        break;
                    }
                    if (attachment.files[i].size > 2 * 1024 * 1024) {
                        const errorDiv = document.getElementById("file_error");
                        if (errorDiv) {
                            errorDiv.style.display = "block";
                            errorDiv.textContent = "Each file must be less than 2MB";
                        }
                        isValid = false;
                        if (!firstError) firstError = attachment;
                        attachment.value = '';
                        break;
                    }
                }
            }
            
            // Scroll to first error
            if (!isValid && firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            
            // Sync nicEditor content before submission
            if (typeof nicEditors !== 'undefined' && nicEditors.findEditor('proposal')) {
                nicEditors.findEditor('proposal').saveContent();
            }
            
            return isValid;
        };
        
        // File input validation on change
        const fileInput = document.getElementById("attachment");
        if (fileInput) {
            fileInput.addEventListener("change", function() {
                const errorDiv = document.getElementById("file_error");
                if (errorDiv) errorDiv.style.display = "none";
                
                if (this.files.length > 5) {
                    if (errorDiv) {
                        errorDiv.style.display = "block";
                        errorDiv.textContent = "Maximum 5 files allowed";
                    }
                    this.value = '';
                }
                
                for (let i = 0; i < this.files.length; i++) {
                    if (this.files[i].size > 2 * 1024 * 1024) {
                        if (errorDiv) {
                            errorDiv.style.display = "block";
                            errorDiv.textContent = "Each file must be less than 2MB";
                        }
                        this.value = '';
                        break;
                    }
                }
            });
        }
    });
</script>
@endsection