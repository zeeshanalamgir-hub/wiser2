console.log(isEditMode);
if (!isEditMode) {
    $("input[name=projectmethod]:first").prop("checked", true);
    document.getElementById('projectSection').style.display = 'block';
    document.getElementById('milestoneSection').style.display = 'none';

}

document.getElementById('byProject').addEventListener('click', function () {
    document.getElementById('projectSection').style.display = 'block';
    document.getElementById('milestoneSection').style.display = 'none';
});

document.getElementById('byMilestone').addEventListener('click', function () {
    document.getElementById('milestoneSection').style.display = 'block';
    document.getElementById('projectSection').style.display = 'none';
});

if (isEditMode) {
    console.log("is edit mode",isEditMode);
    const byProject = document.getElementById("byProject");
    const byMilestone = document.getElementById("byMilestone");
    if (byProject.checked) {
        document.getElementById('projectSection').style.display = 'block';
    }
    if (byMilestone.checked) {
        document.getElementById('milestoneSection').style.display = 'block';
    }
    setByMilestoneData(reviseBidData);
}


$('#addNewMilestoneBtn').on('click', function () {
    const milestoneContainer = $('#milestoneContainer');

    // Find the last dynamic-field ID and calculate the next ID number
    const lastMilestone = milestoneContainer.children('.dynamic-field').last();
    const lastIdNumber = lastMilestone.length ? parseInt(lastMilestone.attr('id').split('-').pop()) : 1;
    const nextIdNumber = lastIdNumber + 1;

    // Create a new milestone item with the next ID number
    const newMilestone = $(`
      <div class="milestone-item row dynamic-field" id="dynamic-field-${nextIdNumber}">
        <div class="col-md-4">
          <div class="form-group">
            <div class="form-label fw-semibold mt-2"">Milestone*</div>
            <input type="text" name="milestone_title[]" id="field-${nextIdNumber}" maxlength="255" min="1"
                   class="form-control afacad-regular ffs-12" >
            <div id="job_milestone_title_error" class="text-danger" style="display: none"></div>
          </div>
        </div>
        <div class="col-4">
          <div class="form-group">
            <div class="form-label fw-semibold mt-2"">Amount*</div>
            <input type="number" name="milestone_amount[]" id="milestone_amount-${nextIdNumber}" maxlength="255" min="1"
                   class="form-control afacad-regular ffs-12"  onkeyup="updateJobBudgetWithMilestoneAmount(this)">
            <div id="job_milestone_amount_error" class="text-danger" style="display: none"></div>
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <div class="form-label fw-semibold mt-2"">Days*</div>
            <input type="number" name="milestone_due_date[]" id="milestone_days-${nextIdNumber}" maxlength="255" min="1"
                   class="form-control afacad-regular ffs-12" >
            <div id="job_milestone_due_date_error" class="text-danger" style="display: none"></div>
          </div>
        </div>
        <div class="col-1">
          <button type="button"  class="btn btn-sm btn-outline-danger text-danger  removeMilestoneBtn  mt-4"><i class="fa-regular fa-circle-xmark"></i> Delete</button>
        </div>
      </div>
    `);

    // Append the new milestone item to the container
    milestoneContainer.append(newMilestone);

    // Attach click event to the remove button within this new milestone
    newMilestone.find('.removeMilestoneBtn').on('click', function () {
        newMilestone.remove();
    });
});




$("#fixed-milestone-job-bidding-form-253243").on('submit', async function(e){
    //alert(123);
    e.preventDefault();
    var checkedValue = $("input[name=projectmethod]:checked").val();
    let flag = false;
    const formdata = $("#fixed-milestone-job-bidding-form-253243").serializeArray();
    if (checkedValue == "byproject" ) {
        flag = await validateByProjectForm(formdata);
    }
    if(checkedValue == 'bymilestone'){
        flag = await validateByMilestoneForm(formdata);
    }
    if(!flag){
        return;
    }
    if (checkedValue == "byproject" || checkedValue == "hourly") {
        this.submit()
    }
    if(checkedValue == 'bymilestone'){
        this.submit()
    }
});
function validateByProjectForm(formdata){
    return new Promise( resolve => {
        let valid = true;
        const bid_price = formdata.find((x) => x.name == "bid_price").value;
        if (!bid_price) {
            showError("#job_bid_price_error", "Bid price is required");
            valid = false;
        }
        if (bid_price) {
            var checkedValue = $("input[name=projectmethod]:checked").val();
            const kl = checkedValue == "byproject" ? 'job_amount' : 'job_max_budget'
            const job_amount = formdata.find(
                (x) => x.name == kl
            ).value;
            const bid_price_int = parseInt(bid_price);
            const job_amount_int = parseInt(job_amount);

            if (bid_price_int > job_amount_int) {
                showError(
                    "#job_bid_price_error",
                    "Bid price should not exceed job total amount"
                );
                valid = false;
            }
        }

        const due_date = formdata.find((x) => x.name == "due_date").value;

        if (!due_date) {
            showError("#job_due_date_error", "Days is required");
            valid = false;
        }

        let dued = parseInt(due_date);
        if (dued < 1 || dued > 255) {
            showError("#job_due_date_error", "Days range from 1 to 255");
            valid = false;
        }

        const proposal = formdata.find((x) => x.name == "proposal").value;

        if (!proposal) {
            showError("#job_proposal_error", "Proposal is required");
            valid = false;
        }
        resolve(valid);
    });
}
function validateInput(input, fee, jobAmount) {
    var enteredValue = input.value;
    // Convert the value to a number
    var numericValue = parseFloat(enteredValue);
    // Check if the entered value is negative
    if (numericValue > jobAmount) {
        // If negative, set the value to empty
        input.value = "";
        $("#total").val(0);
        $("#fee").val(0);
    } else if (numericValue < 0) {
        // If negative, set the value to empty
        input.value = "";
        $("#total").val(0);
        $("#fee").val(0);
    } else if (!isValidNumber(numericValue)) {
        // If not a valid number, set the value to empty
        input.value = "";
        $("#total").val(0);
        $("#fee").val(0);
    } else {
        // If not negative, call calculatetotal function
        calculatetotal(numericValue, fee);
    }
}
function isValidNumber(value) {
    // Use regular expression to check for valid number format
    return /^-?\d*\.?\d+$/.test(value);
}

function calculatetotal(amount, share) {

    let job_price = parseFloat($("#job_price").val());
    let fee = parseInt($("#fee").val());
    let total = 0;
    let price = parseFloat(amount);
    let per = 0;
    if (price > job_price) {
        $("#err-msg").text("You biding price is greater then job price!");
    } else {
        $("#err-msg").text("");
        per = (job_price * fee) / 100;
        total = price - per;
    }
    if (amount == "") {
        $("#total").val(0);
    } else {
        var totall = $("#input_bid").val();
        var platformFee = totall * share / 100;
        $("#total").val(totall - platformFee);
        $("#fee").val(platformFee);
    }
}
function showError(key, error) {
    $("#fixed-milestone-job-bidding-form-253243").find(key).show().text(error);
    setTimeout(() => {
        $("#fixed-milestone-job-bidding-form-253243").find(key).hide();
    }, 5000);
}
function validateByMilestoneForm(formdata){
    console.log("validateByMilestoneForm")
    return new Promise( resolve => {

        let valid = true;

        const milestone_title = formdata.filter(
            (x) => x.name == "milestone_title[]"
        );

        console.log(milestone_title);

        for(var i = 0; i < milestone_title.length; i++){
            $(`.dynamic-field:nth(${i})`).find('#job_milestone_title_error').hide();
        }

        for (var i = 0; i < milestone_title.length; i++) {
            let item = milestone_title[i];
            if(!item.value){
                $(`.dynamic-field:nth(${i})`).find('#job_milestone_title_error').show().text("Milestone title required");
                valid = false
            }
        }

        setTimeout( () => {
            for(var i = 0; i < milestone_title.length; i++){
                $(`.dynamic-field:nth(${i})`).find('#job_milestone_title_error').hide();
            }
        }, 5000);

        const milestone_amount = formdata.filter(
            (x) => x.name == "milestone_amount[]"
        );

        console.log(milestone_amount);

        for(var i = 0; i < milestone_amount.length; i++){
            $(`.dynamic-field:nth(${i})`).find('#job_milestone_amount_error').hide();
        }

        for (var i = 0; i < milestone_amount.length; i++) {
            let item = milestone_amount[i];
            if(!item.value){
                $(`.dynamic-field:nth(${i})`).find('#job_milestone_amount_error').show().text("Milestone amount required");
                valid = false
            }
        }

        setTimeout( () => {
            for(var i = 0; i < milestone_amount.length; i++){
                $(`.dynamic-field:nth(${i})`).find('#job_milestone_amount_error').hide();
            }
        }, 5000);

        const milestone_due_date = formdata.filter(
            (x) => x.name == "milestone_due_date[]"
        );

        console.log(milestone_due_date);

        for(var i = 0; i < milestone_due_date.length; i++){
            $(`.dynamic-field:nth(${i})`).find('#job_milestone_due_date_error').hide();
        }

        for (var i = 0; i < milestone_due_date.length; i++) {
            let item = milestone_due_date[i];
            if(!item.value){
                $(`.dynamic-field:nth(${i})`).find('#job_milestone_due_date_error').show().text("Milestone days required");
                valid = false
            }
        }

        setTimeout( () => {
            for(var i = 0; i < milestone_due_date.length; i++){
                $(`.dynamic-field:nth(${i})`).find('#job_milestone_due_date_error').hide();
            }
        }, 5000);

        // if (!proposal) {
        //     showError("#job_proposal_error", "Proposal is required");
        //     valid = false;
        // }

        const proposal = formdata.find((x) => x.name == "proposal").value;

        if (!proposal) {
            showError("#job_proposal_error", "Proposal is required");
            valid = false;
        }

        if (valid) {
            valid = updateJobBudgetWithMilestoneAmount();
        }

        resolve(valid);
    });
}
function setByMilestoneData(data) {
    // Clear existing milestone fields
    $('#milestoneContainer').empty();

    // Loop through each milestone in the data and create a field
    data.user_milestones.forEach((milestone, index) => {
        const nextIdNumber = index + 1;

        // Create the new milestone element with dynamic ID
        const newMilestone = $(`
            <div class="milestone-item row dynamic-field" id="dynamic-field-${nextIdNumber}">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mt-2">Milestone*</label>
                        <input type="text" name="milestone_title[]" id="field-${nextIdNumber}" value="${milestone.title}" maxlength="255" min="1"
                               class="form-control afacad-regular ffs-12" required>
                        <div id="job_milestone_title_error" class="text-danger" style="display: none"></div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mt-2">Amount*</label>
                        <input type="number" name="milestone_amount[]" id="milestone_amount-${nextIdNumber}" value="${milestone.amount}" maxlength="255" min="1"
                               class="form-control afacad-regular ffs-12" onkeyup="updateJobBudgetWithMilestoneAmount(this)" required>
                        <div id="job_milestone_amount_error" class="text-danger" style="display: none"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label fw-semibold mt-2">Days*</label>
                        <input type="number" name="milestone_due_date[]" id="milestone_days-${nextIdNumber}" value="${milestone.due_date}" maxlength="255" min="1"
                               class="form-control afacad-regular ffs-12" required>
                        <div id="job_milestone_due_date_error" class="text-danger" style="display: none"></div>
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-sm btn-outline-danger text-danger removeMilestoneBtn mt-4" ${index === 0 ? 'style="display: none;"' : ''}>
                        <i class="fa-regular fa-circle-xmark"></i> Delete
                    </button>
                </div>
            </div>
        `);

        // Append the new milestone element to the container
        $('#milestoneContainer').append(newMilestone);

        // Attach event listener for the remove button to delete the milestone on click
        if (index !== 0) {  // Only add the delete functionality if it's not the first milestone
            newMilestone.find('.removeMilestoneBtn').on('click', function () {
                newMilestone.remove();
                updateJobBudgetWithMilestoneAmount(); // Update total after deletion
            });
        }
    });

    // Update the total amounts and related fields after setting the milestones
    updateJobBudgetWithMilestoneAmount();
}

// This function updates the total based on the values of milestone_amount[] inputs
function updateJobBudgetWithMilestoneAmount() {
    let valid = true;

    // Gather form data
    const formdata = $("#fixed-milestone-job-bidding-form-253243").serializeArray();

    // Filter milestone_amount[] inputs and calculate the total
    const milestone_amount = formdata.filter((x) => x.name == "milestone_amount[]");

    var total = 0;
    milestone_amount.forEach(item => {
        const value = item.value ? parseFloat(item.value) : 0;
        total += value;
    });

    // Set the total value in the desired fields
    $(".milestone_job_total_price, .job_milestone_total").val(total);

    // Additional budget and fee calculations if needed
    const job_amount = parseFloat(formdata.find((x) => x.name == "job_amount").value) || 0;
    const job_membershipfee = parseFloat(formdata.find((x) => x.name == "job_membershipfee").value) || 0;

    if (total > job_amount) {
        showError("#job_milestone_total_error", "Total budget cannot exceed job budget");
        valid = false;
    }

    const cut_amount = parseFloat(total * (job_membershipfee / 100)).toFixed(2);
    console.log(cut_amount);
    $("#cutmile").val(cut_amount);
    $("#totalmulti").val(total - cut_amount);

    return valid;
}

// Event listener to dynamically update the budget on input changes
$(document).on("input", 'input[name="milestone_amount[]"]', function () {
    updateJobBudgetWithMilestoneAmount();
});

function customValidate(input) {
    const container = document.getElementById("projectSection");
    const min = parseFloat(input.dataset.min);
    const max = parseFloat(input.dataset.max);

    if (container.style.display === "none") {
        return;
    }

    const value = parseFloat(input.value);
    if (value < min || value > max) {
        input.focus();
        document.getElementById("job_bid_price_error").innerText =
            `Value must be between ${min} and ${max}`;
        document.getElementById("job_bid_price_error").style.display = "block";
    } else {
        document.getElementById("job_bid_price_error").style.display = "none";
    }
}
