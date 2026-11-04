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
                <div class="col-md-3" id="designation-div">
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
                            <option value="{{ $job->id }}" data-job-type="{{ $job->budgettype }}"
                                {{ old('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>



        <!--Box 3-->
        <div id="box3" class="bg-white mb-3 p-4 mt-3 shadow-sm border fs-14 rounded-2"
            style="display: {{ old('job_id') ? 'block' : 'none' }};">
            <!-- Hourly Section -->
            <form action="{{ url('user/seller/job/addbiding') }}" method="POST" enctype="multipart/form-data"
                id="submit-offer-form">
                @csrf
                <div id="hourlySection" class="row g-3 ps-3 fs-14 pt-3"
                    style="display: {{ old('job_id') && old('job_type') == 'hourly' ? 'block' : 'none' }};">
                    <input type="hidden" name="job_type" id="selectedJobType" value="{{ old('job_type') }}">
                    <input type="hidden" name="job_id" id="job_id">
                    <input type="hidden" name="reciever" id="reciever">
                    <input type="hidden" name="invite_professional" value="1">
                    <!-- Hourly Rate -->
                    <div class="row mb-3 ps-2">
                        <div class="col-4">
                            <label class="form-label">Hourly Rate</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-end-0">$</span>
                                </div>
                                <input type="number" id="hourlyRate" name="hourly_amount"
                                    class="form-control border-start-0 border-end-0" aria-label="Hourly Rate"
                                    value="{{ old('hourly_amount') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light border-start-0">hr</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Minimum Hour Per Week</label>
                            <div class="input-group">
                                <input type="number" id="minHoursPerWeek" name="weekly_hours"
                                    class="form-control border-end-0" aria-label="Minimum Hour Per Week"
                                    value="{{ old('weekly_hours') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light border-start-0">hr</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Deposit Fund</label>
                            <div class="input-group">
                                <input type="number" id="depositFund" name="depositfund" class="form-control border-end-0"
                                    aria-label="Deposit Fund" value="{{ old('depositfund') }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light border-start-0">$</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fixed Section -->
                <div id="fixedSection" style="display: {{ old('job_type') == 'fixed' ? 'block' : 'none' }};">
                    <h6 class="fw-bold">Send Offer</h6>
                    <div id="paymentMethodSection" class="ps-2 py-2">
                        <p class="fw-semibold fs-14">How do you want to pay?</p>
                        <div class="row mb-3 ps-2">
                            <div class="col-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="byProject"
                                    value="project" {{ old('paymentMethod', 'project') == 'project' ? 'checked' : '' }}>
                                <label class="form-check-label" for="byProject">By Project</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="byMilestone"
                                    value="milestone" {{ old('paymentMethod') == 'milestone' ? 'checked' : '' }}>
                                <label class="form-check-label" for="byMilestone">By Milestone</label>
                            </div>
                        </div>

                        <div id="projectSection" class="mt-3"
                            style="display: {{ old('paymentMethod', 'project') == 'project' ? 'block' : 'none' }};">
                            <div class="row mb-3 ps-2">
                                <div class="col-6">
                                    <label for="projectAmount" class="form-label">Amount*</label>
                                    <input type="text" class="form-control" id="project_amount" name="project_amount"
                                        placeholder="$0" value="{{ old('amount') }}">
                                </div>
                                <div class="col-6">
                                    <label for="milestoneDueDate" class="form-label">Due Date*</label>
                                    <input type="date" name="due_date" class="form-control"
                                        value="{{ old('due_date') }}">
                                </div>
                            </div>
                        </div>
                        <div id="milestoneSection" class="mt-3"
                            style="display: {{ old('paymentMethod') == 'milestone' ? 'block' : 'none' }};">
                            <div class="row mb-3 ps-2" id="milestoneContainer">
                                <div class="col-4">
                                    <label for="milestone" class="form-label">Milestone 1*</label>
                                    <input type="text" class="form-control" name="milestone_title[]"
                                        placeholder="Title">
                                </div>
                                <div class="col-3">
                                    <label for="milestoneAmount" class="form-label">Amount*</label>
                                    <input type="text" class="form-control" name="milestone_amount[]"
                                        placeholder="$0">
                                </div>
                                <div class="col-3">
                                    <label for="milestoneDueDate" class="form-label">Due Date*</label>
                                    <input type="date" name="due_date[]" class="form-control">
                                </div>
                                <div class="col-2">
                                    <p id="addMilestoneBtn"><i class="mt-4 fas fa-plus-circle text-green fs-5"></i> Add
                                        Milestone</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row mb-3 ps-2" id="platformFeeSection">
                <div class="col-6">
                    <label for="platformFee" class="form-label mt-3">Platform Fee (10%):</label>
                    <input type="text" class="form-control fw-semibold" id="platformFee" placeholder="$0"
                        disabled>
                </div>
                <div class="col-6 mt-3">
                    <label for="youGetPaid" class="form-label">You'll Get Paid:</label>
                    <input type="text" class="form-control fw-semibold" id="youGetPaid" placeholder="$0"
                        disabled>
                </div>
            </div> --}}
                <div class="row ps-3 fs-14">
                    <div class="col-md-12">
                        <label for="hourly">Cover Letter*</label>
                        <textarea id="hourly" name="description" class="p-2 mx-2 border-2 border mb-1 rounded-2 col-12 with-light md:col-5"
                            style="height: 150px;" required>{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="row mt-3 ps-3 fs-14">
                    <label for="hourly">Attachment *</label>
                    <input type="file" id="hourly" name="attachment"
                        class="p-2 mx-2 border-2 border rounded-2 col-11 md:col-5">
                    <p class="fst-italic fs-12 ">Note: Maximum 5 files can be uploaded. Maximum file size should be 250M
                    </p>
                </div>
                <!-- Button -->
                <button class="btn btn-success bg-green border-0 mb-4"> Submit Offer</button>
            </form>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            const jobTypeSelect = document.getElementById("changejob");
            const paymentMethodRadios = document.querySelectorAll("input[name='paymentMethod']");

            const selectedJobType = document.getElementById("selectedJobType");
            const box3 = document.getElementById("box3");
            const hourlySection = document.getElementById("hourlySection");
            const fixedSection = document.getElementById("fixedSection");

            // Function to toggle required attributes
            function toggleRequiredAttributes() {
                const isHourlyVisible = hourlySection.style.display === "block";
                const isFixedVisible = fixedSection.style.display === "block";

                // Hourly Section Fields
                document.getElementById("hourlyRate").required = isHourlyVisible;
                document.getElementById("minHoursPerWeek").required = isHourlyVisible;

                // Fixed Section Fields
                if (isFixedVisible) {
                    const isProjectVisible = projectSection.style.display === "block";
                    const isMilestoneVisible = milestoneSection.style.display === "block";

                    // Project Fields
                    document.getElementById("projectAmount").required = isProjectVisible;
                    document.getElementById("projectDays").required = isProjectVisible;

                    // Milestone Fields
                    const milestoneInputs = milestoneSection.querySelectorAll("input[required]");
                    milestoneInputs.forEach(input => {
                        input.required = isMilestoneVisible;
                    });
                }
            }

            function updateHiddenFields() {
                const selectedOption = jobTypeSelect.options[jobTypeSelect.selectedIndex];
                const selectedJobIdValue = selectedOption.value;
                const selectedJobTypeValue = selectedOption.getAttribute("data-job-type");
                // Update hidden fields
                const selectedJobId = document.getElementById("job_id");
                if (selectedJobId)
                    selectedJobId.value = selectedJobIdValue;
                selectedJobType.value = selectedJobTypeValue;
            }
            // Initial call to set hidden fields on page load
            updateHiddenFields();
            // Update hidden fields when the job selection changes
            jobTypeSelect.addEventListener("change", updateHiddenFields);

            jobTypeSelect.addEventListener("change", function() {
                const selectedJobType = this.options[this.selectedIndex].getAttribute("data-job-type");
                box3.style.display = "block";
                hourlySection.style.display = selectedJobType === "hourly" ? "block" : "none";
                fixedSection.style.display = selectedJobType === "fixed" ? "block" : "none";
            });

            const hourlyRateInput = document.getElementById("hourlyRate");
            const minHoursPerWeekInput = document.getElementById("minHoursPerWeek");
            const depositFundInput = document.getElementById("depositFund");

            function calculateDepositFund() {
                const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
                const minHoursPerWeek = parseFloat(minHoursPerWeekInput.value) || 0;
                const depositFund = hourlyRate * minHoursPerWeek;
                depositFundInput.value = depositFund.toFixed(2);
            }

            hourlyRateInput.addEventListener("input", calculateDepositFund);
            minHoursPerWeekInput.addEventListener("input", calculateDepositFund);
        });
        document.addEventListener("DOMContentLoaded", function() {
            const byProjectRadio = document.getElementById("byProject");
            const byMilestoneRadio = document.getElementById("byMilestone");
            const projectSection = document.getElementById("projectSection");
            const milestoneSection = document.getElementById("milestoneSection");

            // Toggle sections based on radio button selection
            byProjectRadio.addEventListener("change", function() {
                if (this.checked) {
                    projectSection.style.display = "block";
                    milestoneSection.style.display = "none";
                }
            });

            byMilestoneRadio.addEventListener("change", function() {
                if (this.checked) {
                    milestoneSection.style.display = "block";
                    projectSection.style.display = "none";
                }
            });

            // Add Milestone Button
            const addMilestoneBtn = document.getElementById("addMilestoneBtn");
            const milestoneContainer = document.getElementById("milestoneContainer");

            addMilestoneBtn.addEventListener("click", function() {
                const milestoneCount = milestoneContainer.querySelectorAll('.milestone-item').length + 1;
                const newMilestone = document.createElement('div');
                newMilestone.classList.add('milestone-item', 'row', 'mb-3', 'mt-3', 'ps-2');
                newMilestone.innerHTML = ` <div class="col-4">
                    <label for="milestone" class="form-label">Milestone ${milestoneCount}*</label>
                    <input type="text" class="form-control" name="milestone_title[]"  placeholder="Title" required>
                </div>
                <div class="col-3">
                    <label for="milestoneAmount" class="form-label">Amount*</label>
                    <input type="text" class="form-control" name="milestone_amount[]" placeholder="$0" required>
                </div>
                <div class="col-3">
                    <label for="milestoneDueDate" class="form-label">Due Date*</label>
                    <input type="date"  name="due_date[]"  class="form-control">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-sm delete-milestone mt-4">
                        <i class="fas fa-times"></i> Delete
                    </button>
                </div>
        `;
                milestoneContainer.appendChild(newMilestone);
                // Add event listener to the new delete button
                const deleteButton = newMilestone.querySelector('.delete-milestone');
                deleteButton.addEventListener('click', function() {
                    milestoneContainer.removeChild(newMilestone);
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const submitOfferForm = document.getElementById("submit-offer-form");

            submitOfferForm.addEventListener("submit", function(event) {
                const jobType = document.getElementById("selectedJobType").value;
                const paymentMethod = document.querySelector("input[name='paymentMethod']:checked")?.value;

                // Disable unnecessary fields before submission
                if (jobType === "hourly") {
                    // Disable fixed section fields
                    document.querySelectorAll(
                        "#fixedSection input, #fixedSection textarea, #fixedSection select").forEach(
                        field => {
                            field.disabled = true;
                        });
                } else if (jobType === "fixed") {
                    if (paymentMethod === "project") {
                        // Disable milestone section fields
                        document.querySelectorAll(
                            "#milestoneSection input, #milestoneSection textarea, #milestoneSection select"
                            ).forEach(field => {
                            field.disabled = true;
                        });
                    } else if (paymentMethod === "milestone") {
                        // Disable project section fields
                        document.querySelectorAll(
                                "#projectSection input, #projectSection textarea, #projectSection select")
                            .forEach(field => {
                                field.disabled = true;
                            });
                    }
                }

                // Validate form fields
                let isValid = true;

                if (jobType === "hourly") {
                    const hourlyRate = document.getElementById("hourlyRate").value.trim();
                    const minHoursPerWeek = document.getElementById("minHoursPerWeek").value.trim();

                    if (!hourlyRate || isNaN(hourlyRate)) {
                        alert("Please enter a valid hourly rate.");
                        isValid = false;
                    }

                    if (!minHoursPerWeek || isNaN(minHoursPerWeek)) {
                        alert("Please enter a valid number of minimum hours per week.");
                        isValid = false;
                    }
                } else if (jobType === "fixed") {
                    if (paymentMethod === "project") {
                        const projectAmount = document.getElementById("amount").value.trim();
                        const dueDate = document.querySelector("input[name='due_date']").value.trim();

                        if (!projectAmount || isNaN(projectAmount)) {
                            alert("Please enter a valid amount for the project.");
                            isValid = false;
                        }

                        if (!dueDate) {
                            alert("Please select a due date.");
                            isValid = false;
                        }
                    } else if (paymentMethod === "milestone") {
                        const milestoneItems = document.querySelectorAll(".milestone-item");
                        milestoneItems.forEach((item, index) => {
                            const title = item.querySelector('input[name="milestone_title[]"]')
                                .value.trim();
                            const amount = item.querySelector('input[name="milestone_amount[]"]')
                                .value.trim();
                            const dueDate = item.querySelector('input[name="due_date[]"]').value
                                .trim();

                            if (!title || !amount || !dueDate) {
                                alert(`Please fill all fields for Milestone ${index + 1}.`);
                                isValid = false;
                            }

                            if (isNaN(amount)) {
                                alert(`Please enter a valid amount for Milestone ${index + 1}.`);
                                isValid = false;
                            }
                        });
                    }
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });
        });
        // document.getElementById("changejob").onchange = function() {
        // $('#submit').trigger('click');
        // };
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            designation_div = $("#designation-div");
            designation_div.addClass("d-none");
            var buttonAdd = $("#add-button");
            var buttonRemove = $("#remove-button");
            var className = ".dynamic-field";
            var count = 0;
            var field = "";
            var maxFields = 50;

            function totalFields() {
                return $(className).length;
            }

            function addNewField() {
                count = totalFields() + 1;
                field = $("#dynamic-field-1").clone();
                field.attr("id", "dynamic-field-" + count);
                field.children("label").text("Field " + count);
                field.find("input").val("");
                $(className + ":last").after($(field));
            }

            function removeLastField() {
                if (totalFields() > 1) {
                    $(className + ":last").remove();
                }
            }

            function enableButtonRemove() {
                if (totalFields() === 2) {
                    buttonRemove.removeAttr("disabled");
                    buttonRemove.addClass("shadow-sm");
                }
            }

            function disableButtonRemove() {
                if (totalFields() === 1) {
                    buttonRemove.attr("disabled", "disabled");
                    buttonRemove.removeClass("shadow-sm");
                }
            }

            function disableButtonAdd() {
                if (totalFields() === maxFields) {
                    buttonAdd.attr("disabled", "disabled");
                    buttonAdd.removeClass("shadow-sm");
                }
            }

            function enableButtonAdd() {
                if (totalFields() === (maxFields - 1)) {
                    buttonAdd.removeAttr("disabled");
                    buttonAdd.addClass("shadow-sm");
                }
            }
            buttonAdd.click(function() {
                addNewField();
                enableButtonRemove();
                disableButtonAdd();
            });
            buttonRemove.click(function() {
                removeLastField();
                disableButtonRemove();
                enableButtonAdd();
            });
        });

        $(document).on('change', '#freelancer', function() {
            const freelancerId = $(this).val();
            $('#designation-div').addClass('d-none');
            $('#reciever').val(freelancerId);

            const designation = $(this).find(':selected').data('designation');
            console.log("designation =>", designation);

            $('.skills-container').html('');

            if (designation !== '' && designation !== null && designation !== undefined) {
                $('#designation-div').removeClass('d-none');
                $('#designation').text(designation);

                if (freelancerId) {
                    $.ajax({
                        url: `/freelancer/skills/${freelancerId}`,
                        method: 'GET',
                        success: function(response) {
                            if (response.success) {
                                let html = '';
                                response.skills.forEach(function(skill) {
                                    html += `<span class="tags0 me-2 mb-2">${skill}</span>`;
                                });
                                $('.skills-container').html(html);
                            } else {
                                $('.skills-container').html(
                                    `<p class="text-danger">${response.message}</p>`);
                            }
                        },
                        error: function() {
                            $('.skills-container').html(
                                '<p class="text-danger">Something went wrong!</p>');
                        }
                    });
                }
            } else {
                $('#designation-div').addClass('d-none');
                $('#designation').text('');
                $('.skills-container').html('');
            }
        });
    </script>
@endsection
