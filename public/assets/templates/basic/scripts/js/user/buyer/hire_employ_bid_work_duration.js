// js validation for submit offer



function getCurrentWalletBalance() {
    return new Promise(async (resolve) => {
        const res = await getAjax("/user/buyer/global/ajax/get-current-wallet-balance");
        resolve(res);
    });
}

function updateJobBudgetWithMilestoneAmount() {
    let valid = true;

    const formdata = $("#submit-work-dpuration-form").serializeArray();
    console.log("formdata", formdata);

    const milestone_amount = formdata.filter((x) => x.name == "milestone_amount[]");
    console.log("milestone_amountddddddddd", milestone_amount);

    const milestone_due_date = formdata.filter((x) => x.name == "milestone_due_date[]");
    console.log(milestone_due_date);

    var total = 0;
    for (var i = 0; i < milestone_amount.length; i++) {
        let item = milestone_amount[i];
        console.log(item.value);
        const v = item.value ? parseInt(item.value) : 0;
        total = total + v;
    }

    return total;
}

// function showError(key, error) {
//     $("#submit-work-duration-form").find(key).show().text(error);
//     setTimeout(() => {
//         $("#submit-work-duration-form").find(key).hide();
//     }, 5000);
// }

function validateHourlyData(formdata) {
    let valid = true;
    const hmi = formdata.find((x) => x.name == "hourly_amount").value;

    if (!hmi) {
        showError("#project_amount_error", "Bid Amount is required");
        valid = false;
    }

    const daysInput = formdata.find((x) => x.name == "project_days").value;

    if (!daysInput) {
        showError("#days_error", "Days required cannot be null");
        valid = false;
    }

    const coverLetterInput = formdata.find((x) => x.name == "coverletter").value.trim();

    console.log("coverLetterInput", !coverLetterInput);
    if (!coverLetterInput) {
        showError("#cover_letter_error", "Cover letter cannot be null");
        valid = false;
    }

    return valid;
}

function validateByprojectData(formdata) {
    let valid = true;
    var title = formdata.find((x) => x.name == "title").value;

    if (!title) {
        showError("#title_error", "title is required");
        valid = false;
    }

    const startData = formdata.find((x) => x.name == "start_date").value;

    if (!startData) {
        showError("#start_date_error", "startData cannot be null");
        valid = false;
    }

    const endData = formdata.find((x) => x.name == "end_date").value;

    if (!endData) {
        showError("#end_date_error", "endData cannot be null");
        valid = false;
    }

    const coverLetterInput = formdata.find((x) => x.name == "coverletter").value.trim();

    if (!coverLetterInput) {
        showError("#cover_letter_error", "Cover letter cannot be null");
        valid = false;
    }

    return valid;
}

function validateBymilestoneData(formdata) {
    let valid = true;

    const milestone_title = formdata.filter((x) => x.name == "milestone_title[]");

    console.log(milestone_title);

    for (var i = 0; i < milestone_title.length; i++) {
        $(`.dynamic-field:nth(${i})`).find("#job_milestone_title_error").hide();
    }

    for (var i = 0; i < milestone_title.length; i++) {
        let item = milestone_title[i];
        if (!item.value) {
            $(`.dynamic-field:nth(${i})`)
                .find("#job_milestone_title_error")
                .show()
                .text("Milestone title required");
            valid = false;
        }
    }

    setTimeout(() => {
        for (var i = 0; i < milestone_title.length; i++) {
            $(`.dynamic-field:nth(${i})`).find("#job_milestone_title_error").hide();
        }
    }, 5000);

    const milestone_amount = formdata.filter((x) => x.name == "milestone_amount[]");

    console.log(milestone_amount);

    for (var i = 0; i < milestone_amount.length; i++) {
        $(`.dynamic-field:nth(${i})`).find("#job_milestone_amount_error").hide();
    }

    for (var i = 0; i < milestone_amount.length; i++) {
        let item = milestone_amount[i];
        if (!item.value) {
            $(`.dynamic-field:nth(${i})`)
                .find("#job_milestone_amount_error")
                .show()
                .text("Milestone amount required");
            valid = false;
        }
    }

    setTimeout(() => {
        for (var i = 0; i < milestone_amount.length; i++) {
            $(`.dynamic-field:nth(${i})`).find("#job_milestone_amount_error").hide();
        }
    }, 5000);

    const milestone_due_date = formdata.filter((x) => x.name == "milestone_due_date[]");

    console.log(milestone_due_date);

    for (var i = 0; i < milestone_due_date.length; i++) {
        $(`.dynamic-field:nth(${i})`).find("#job_milestone_due_date_error").hide();
    }

    for (var i = 0; i < milestone_due_date.length; i++) {
        let item = milestone_due_date[i];
        if (!item.value) {
            $(`.dynamic-field:nth(${i})`)
                .find("#job_milestone_due_date_error")
                .show()
                .text("Milestone days required");
            valid = false;
        }
    }

    setTimeout(() => {
        for (var i = 0; i < milestone_due_date.length; i++) {
            $(`.dynamic-field:nth(${i})`).find("#job_milestone_due_date_error").hide();
        }
    }, 5000);

    const coverLetterInput = formdata.find((x) => x.name == "coverletter").value.trim();

    if (!coverLetterInput) {
        showError("#cover_letter_error", "Cover letter cannot be null");
        valid = false;
    }

    return valid;
}

// async function validateAndSubmitSendOfferForm(event) {
//     event.preventDefault();

//     const formdata = $("#submit-work-duration-form").serializeArray();
//     console.log(formdata);

//     const jobType = formdata.find((x) => x.name == "job_type").value;

//     var valid = true;
//     if (jobType == "byproject") {
//         valid = validateByprojectData(formdata);
//     }

//     if (jobType == "bymilestone") {
//         valid = validateBymilestoneData(formdata);
//     }

//     if (jobType == "per_hour") {
//         valid = validateHourlyData(formdata);
//     }

//     if (!valid) {
//         return;
//     }

//     const walletBalance = await getCurrentWalletBalance();

//     var projectAmount = 0;

//     if (jobType == "byproject") {
//         projectAmount = formdata.find((x) => x.name == "project_amount").value;
//     }

//     if (jobType == "per_hour") {
//         const hmi = parseInt(formdata.find((x) => x.name == "hourly_amount").value);
//         const hpv = parseInt(formdata.find((x) => x.name == "project_days").value);
//         projectAmount = hmi * hpv;
//     }

//     if (jobType == "bymilestone") {
//         projectAmount = updateJobBudgetWithMilestoneAmount();
//     }

//     if (walletBalance < projectAmount) {
//         showError(
//             "#project_amount_error",
//             "Insufficient Balance - " +
//             walletBalance +
//             " available, required " +
//             projectAmount
//         );
//         return;
//     }

//     $("#submit-work-duration-form").submit();
// }

// $("#send-work-duration-btn-678956").on("click", validateAndSubmitSendOfferForm);

document.getElementById("upload-icon").onclick = function () {
    document.getElementById("attachment-files").click();
};
function jobBudgetGet() {
    console.log("agsdgsfgfsd");

    var jobBudget = $("#jobBudget").val();
    console.log("agsdgsfgfsd", jobBudget);

    if (jobBudget === 'per_hour') {
        document.getElementById('byProjectFields').style.display = 'block';
        document.getElementById('byMilestoneMessage').style.display = 'none';
    } else if (jobBudget === 'fixed') {
        document.getElementById('byProjectFields').style.display = 'none';
        document.getElementById('byMilestoneMessage').style.display = 'block';
    }
}


// $(document).ready(function () {

//     var $form = $('#fixed-milestone-job-bidding-form-253243');
//     var urlParams = new URLSearchParams(window.location.search);
//     var bidId = urlParams.get('bidId');

//     if (bidId) {
//         var currentActionUrl = $form.attr('action');
//         var newActionUrl = new URL(currentActionUrl, window.location.origin);
//         newActionUrl.searchParams.append('bidId', bidId);

//         $form.attr('action', newActionUrl.toString());
//     }

//     setTimeout(() => {
//         setDataInReviseBid();
//     }, 500);
// });

// function addMoreFields() {
//     fieldCount++;
//     const fieldSet = `
//         <div class="racket-form-body-section">
//             <span class="remove-field">&#10006;</span>
//             <div class="row">
//                 <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
//                     <div class="racket-form-body-section-heading poppins-light ffs-12">Milestone title</div>
//                     <div class="form-group">
//                         <input type="text" name="title[]" value="" class="form-control afacad-regular ffs-12">
//                         <div class="text-danger" style="display: none"></div>
//                     </div>
//                 </div>
//                 <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
//                     <div class="racket-form-body-section-heading poppins-light ffs-12">Amount</div>
//                     <div class="form-group">
//                         <input type="number" name="amount[]" value="" class="form-control afacad-regular ffs-12">
//                         <div class="text-danger" style="display: none"></div>
//                     </div>
//                 </div>
//                 <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
//                     <div class="racket-form-body-section-heading poppins-light ffs-12">Due Date</div>
//                     <div class="form-group">
//                         <input type="date" name="due_date[]" value="" class="form-control afacad-regular ffs-12">
//                         <div class="text-danger" style="display: none"></div>
//                     </div>
//                 </div>
//             </div>
//         </div>
//     `;
//     $('#milestonesContainer').append(fieldSet);
// }

$(document).ready(function () {

    var jobBudget = $("#jobBudget").val();
    console.log("agsdgsfgfsd", jobBudget);
    if (jobBudget === 'per_hour') {
        document.getElementById('byProjectFields').style.display = 'block';
        document.getElementById('byMilestoneMessage').style.display = 'none';
    } else if (jobBudget === 'fixed') {
        document.getElementById('byProjectFields').style.display = 'none';
        document.getElementById('byMilestoneMessage').style.display = 'block';
    }
    jobBudgetGet();
    console.log("Document ready and jobBudgetGet executed");
});

