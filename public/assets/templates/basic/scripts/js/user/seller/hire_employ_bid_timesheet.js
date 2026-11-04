// js validation for submit offer

function getCurrentWalletBalance() {
    return new Promise(async (resolve) => {
        const res = await getAjax(
            "/user/buyer/global/ajax/get-current-wallet-balance"
        );
        resolve(res);
    });
}

function updateJobBudgetWithMilestoneAmount() {
    let valid = true;

    const formdata = $("#submit-work-dpuration-form").serializeArray();
    console.log("formdata", formdata);

    const milestone_amount = formdata.filter(
        (x) => x.name == "milestone_amount[]"
    );
    console.log("milestone_amountddddddddd", milestone_amount);

    const milestone_due_date = formdata.filter(
        (x) => x.name == "milestone_due_date[]"
    );
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

function showError(key, error) {
    $("#submit-work-duration-form").find(key).show().text(error);
    setTimeout(() => {
        $("#submit-work-duration-form").find(key).hide();
    }, 5000);
}

function validateHourlyData(formdata) {
    let valid = true;
    const hmi = formdata.find((x) => x.name == "hourly_amount").value;

    if (!hmi) {
        showError("#project_amount_error", "Bid Amount is required");
        valid = false;
    }

    const daysInput = formdata.find((x) => x.name == "end_date").value;

    if (!daysInput) {
        showError("#days_error", "Days required cannot be null");
        valid = false;
    }

    const coverLetterInput = formdata
        .find((x) => x.name == "coverletter")
        .value.trim();

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

    const coverLetterInput = formdata
        .find((x) => x.name == "coverletter")
        .value.trim();

    if (!coverLetterInput) {
        showError("#cover_letter_error", "Cover letter cannot be null");
        valid = false;
    }

    return valid;
}

function validateBymilestoneData(formdata) {
    let valid = true;

    const milestone_title = formdata.filter(
        (x) => x.name == "milestone_title[]"
    );

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
            $(`.dynamic-field:nth(${i})`)
                .find("#job_milestone_title_error")
                .hide();
        }
    }, 5000);

    const milestone_amount = formdata.filter(
        (x) => x.name == "milestone_amount[]"
    );

    console.log(milestone_amount);

    for (var i = 0; i < milestone_amount.length; i++) {
        $(`.dynamic-field:nth(${i})`)
            .find("#job_milestone_amount_error")
            .hide();
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
            $(`.dynamic-field:nth(${i})`)
                .find("#job_milestone_amount_error")
                .hide();
        }
    }, 5000);

    const milestone_due_date = formdata.filter(
        (x) => x.name == "milestone_due_date[]"
    );

    console.log(milestone_due_date);

    for (var i = 0; i < milestone_due_date.length; i++) {
        $(`.dynamic-field:nth(${i})`)
            .find("#job_milestone_due_date_error")
            .hide();
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
            $(`.dynamic-field:nth(${i})`)
                .find("#job_milestone_due_date_error")
                .hide();
        }
    }, 5000);

    const coverLetterInput = formdata
        .find((x) => x.name == "coverletter")
        .value.trim();

    if (!coverLetterInput) {
        showError("#cover_letter_error", "Cover letter cannot be null");
        valid = false;
    }

    return valid;
}

document.getElementById("upload-icon").onclick = function () {
    document.getElementById("attachment-files").click();
};
function jobBudgetGet() {
    var jobBudget = $("#jobBudget").val();
    jobBudget = JSON.parse(jobBudget);
    if (jobBudget == "per_hour") {
        console.log("perHour", jobBudget);

        document.getElementById("byProjectFields").style.display = "block";
        document.getElementById("byMilestoneMessage").style.display = "none";
    } else if (jobBudget == "fixed") {
        console.log("fixeeeeedddddd", jobBudget);
        document.getElementById("byProjectFields").style.display = "none";
        document.getElementById("byMilestoneMessage").style.display = "block";
    }
}

document.getElementById("addMoreButton").addEventListener("click", function () {
    addMilestoneField();
});

function addMilestoneField() {
    const milestonesContainer = document.getElementById("milestonesContainer");

    const newField = document.createElement("div");
    newField.classList.add("racket-form-body-section");
    newField.innerHTML = `
    <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="milestone-header">
            <div class="racket-form-body-section-heading poppins-light ffs-12">Milestone title</div>
            <div class="remove-field" onclick="removeField(this)">remove</div>
        </div>
        <div class="form-group">
            <input type="text" name="title[]" value="" class="form-control afacad-regular ffs-12">
            <div class="text-danger" style="display: none"></div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="racket-form-body-section-heading poppins-light ffs-12">Amount</div>
        <div class="form-group">
            <input type="number" name="amount[]" value="" class="form-control afacad-regular ffs-12">
            <div class="text-danger" style="display: none"></div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="racket-form-body-section-heading poppins-light ffs-12">Due Date</div>
        <div class="form-group">
            <input type="date" name="due_date[]" value="" class="form-control afacad-regular ffs-12">
            <div class="text-danger" style="display: none"></div>
        </div>
    </div>
</div>

    `;

    milestonesContainer.appendChild(newField);
}

function removeField(element) {
    console.log("salfmnsdklgnsdjk");

    const fieldToRemove = element.closest(".racket-form-body-section");
    fieldToRemove.remove();
}

document.getElementById("jobBudget").addEventListener("change", function () {
    var jobBudget = this.value;

    if (jobBudget == "fixed") {
        document.getElementById("byProjectFields").style.display = "none";
        document.getElementById("byMilestoneMessage").style.display = "block";
        document.getElementById("milestonesContainer").style.display = "block";
    } else if (jobBudget == "per_hour") {
        document.getElementById("byProjectFields").style.display = "block";
        document.getElementById("byMilestoneMessage").style.display = "none";
        document.getElementById("milestonesContainer").style.display = "none";
    }
});

function jobAmountGet() {
    var jobAmount = $("#jobAmount").val();
    console.log("amount", jobAmount);
}

$("#submit-milestone-form").on("submit", function (e) {
    e.preventDefault();

    var isValid = true;
    var totalAmount = 0;
    var jobAmountObj = $("#jobAmount").val();
    var jobAmount = JSON.parse(jobAmountObj);
    var amount = 0;

    $(".text-danger").hide();

    $("#milestonesContainer .racket-form-body-section").each(function () {
        var title = $(this).find('input[name="title[]"]').val().trim();
        amount = parseFloat(
            $(this).find('input[name="amount[]"]').val().trim()
        );
        var dueDate = $(this).find('input[name="due_date[]"]').val().trim();

        if (!title) {
            $(this)
                .find('input[name="title[]"]')
                .next(".text-danger")
                .text("Title is required")
                .show();
            isValid = false;
        }
        if (!amount) {
            $(this)
                .find('input[name="amount[]"]')
                .next(".text-danger")
                .text("Valid amount is required")
                .show();
            isValid = false;
        } else {
            totalAmount += amount;
        }
        if (!dueDate) {
            $(this)
                .find('input[name="due_date[]"]')
                .next(".text-danger")
                .text("Due date is required")
                .show();
            isValid = false;
        }
        console.log(jobAmount <= amount);

        if (jobAmount <= amount) {
            $(this)
                .find('input[name="amount[]"]')
                .next(".text-danger")
                .text(
                    "Total milestone amount exceeds the job amount. Please adjust the amounts."
                )
                .show();
            isValid = false;
        } else {
            $("#totalAmountError").hide();
        }
    });
    const url = window.location.href;
    const parts = url.split("/");
    const encryptedId = parts[parts.length - 1];
    $("#milestone_bid_id").val(encryptedId);
    if (isValid) {
        this.submit();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("submit-milestone-form");

    var jobBudget = $("#jobBudget").val();
    jobBudget = JSON.parse(jobBudget);
    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);

        const url = window.location.href;
        const parts = url.split("/");
        const encryptedId = parts[parts.length - 1];

        formData.append('bid_id', encryptedId);

        const response = await postAjax(`/user/seller/payment/request`, formData)
        console.log(response);

    });
});

$("#submit-timesheet-form").on("submit", async function (e) {
    e.preventDefault();

    var isValid = true;
    $(".text-danger").hide();

    var title = $('input[name="title"]').val().trim();
    if (!title) {
        $('input[name="title"]')
            .next(".text-danger")
            .text("Title is required")
            .show();
        isValid = false;
    }

    var startDate = $('input[name="start_date"]').val().trim();
    if (!startDate) {
        $('input[name="start_date"]')
            .next(".text-danger")
            .text("Start date is required")
            .show();
        isValid = false;
    }

    var endDate = $('input[name="end_date"]').val().trim();
    if (!endDate) {
        $('input[name="end_date"]')
            .next(".text-danger")
            .text("End date is required")
            .show();
        isValid = false;
    }

    // Convert dates to a comparable format (e.g., YYYY-MM-DD)
    var start = new Date(startDate);
    var end = new Date(endDate);

    if (end < start) {
        $('input[name="end_date"]')
            .next(".text-danger")
            .text("End date must be greater than or equal to the start date")
            .show();
        isValid = false;
    }

    var hours = $('input[name="hours"]').val().trim();
    var minutes = $('input[name="minutes"]').val().trim();
    if (!hours || isNaN(hours) || hours < 0) {
        $('input[name="hours"]')
            .next(".text-danger")
            .text("Valid hours are required")
            .show();
        isValid = false;
    }
    if (!minutes || isNaN(minutes) || minutes < 0 || minutes > 59) {
        $('input[name="minutes"]')
            .next(".text-danger")
            .text("Valid minutes are required (0-59)")
            .show();
        isValid = false;
    }

    var comments = $('textarea[name="coverletter"]').val().trim();
    if (!comments) {
        $('textarea[name="coverletter"]')
            .next(".text-danger")
            .text("Comments are required")
            .show();
        isValid = false;
    }

    var files = $("#attachment-files")[0].files;
    if (files.length > 5) {
        $("#selected-files-list")
            .next(".text-danger")
            .text("You can only upload up to 5 files")
            .show();
        isValid = false;
    }
    for (var i = 0; i < files.length; i++) {
        if (files[i].size > 250 * 1024 * 1024) {
            $("#selected-files-list")
                .next(".text-danger")
                .text("File size should not exceed 250MB")
                .show();
            isValid = false;
            break;
        }
    }

    if (isValid) {
        console.log({
            title: title,
            start_date: startDate,
            end_date: endDate,
            hours: hours,
            minutes: minutes,
            comments: comments,
            files: files,
        });

        const url = window.location.href;
        const parts = url.split("/");
        const encryptedId = parts[parts.length - 1];
        console.log(url, parts, encryptedId);
        $("#bid_id").val(encryptedId);

        this.submit();
    }
});

$("#attachment-files").on("change", function () {
    var fileList = $(this)[0].files;
    var fileNames = [];
    for (var i = 0; i < fileList.length; i++) {
        fileNames.push(fileList[i].name);
    }
    $("#racket-attachment-file-name").text(fileNames.join(", "));
});

$(document).ready(function () {
    jobBudgetGet();
    jobAmountGet();

    console.log("Document ready and jobBudgetGet executed");
});
