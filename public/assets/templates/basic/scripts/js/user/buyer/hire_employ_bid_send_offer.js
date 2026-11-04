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

    const formdata = $(
        "#submit-offer-form"
    ).serializeArray();
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

    return total

    // const job_amount = formdata.find((x) => x.name == "job_amount").value;
    // const int_job_amount = parseInt(job_amount);

    // if (total > int_job_amount) {
    //     showError(
    //         "#job_milestone_total_error",
    //         "Total budget cannot exceed job budget"
    //     );
    //     valid = false;
    // }

    // return valid;
}

function showError(key, error) {
    $("#submit-offer-form").find(key).show().text(error);
    setTimeout(() => {
        $("#submit-offer-form").find(key).hide();
    }, 5000);
}

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
    var projectAmount = formdata.find((x) => x.name == "project_amount").value;

    if (!projectAmount) {
        showError("#project_amount_error", "Bid Amount is required");
        valid = false;
    }

    const daysInput = formdata.find((x) => x.name == "project_days").value;

    if (!daysInput) {
        showError("#days_error", "Days required cannot be null");
        valid = false;
    }

    const coverLetterInput = formdata.find(
        (x) => x.name == "coverletter"
    ).value.trim();

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

    const coverLetterInput = formdata.find(
        (x) => x.name == "coverletter"
    ).value.trim();

    if (!coverLetterInput) {
        showError("#cover_letter_error", "Cover letter cannot be null");
        valid = false;
    }



    return valid;
}

async function validateAndSubmitSendOfferForm() {
    const formdata = $("#submit-offer-form").serializeArray();
    console.log(formdata);

    const jobType = formdata.find((x) => x.name == "job_type").value;

    var valid = true;
    if (jobType == "byproject") {
        valid = validateByprojectData(formdata);
    }

    if (jobType == "bymilestone") {
        valid = validateBymilestoneData(formdata);
    }

    if (jobType == "per_hour") {
        valid = validateHourlyData(formdata);
    }

    if (!valid) {
        return;
    }

    const walletBalance = await getCurrentWalletBalance();

    var projectAmount = 0;

    if (jobType == "byproject") {
        projectAmount = formdata.find((x) => x.name == "project_amount").value;
    }

    if (jobType == "per_hour") {
        const hmi = parseInt(
            formdata.find((x) => x.name == "hourly_amount").value
        );
        const hpv = parseInt(
            formdata.find((x) => x.name == "project_days").value
        );
        projectAmount = hmi * hpv;
    }

    if (jobType == "bymilestone") {
        projectAmount = updateJobBudgetWithMilestoneAmount();
    }

    if (walletBalance < projectAmount) {
        showError(
            "#project_amount_error",
            "Insufficient Balance - " +
                walletBalance +
                " available, required " +
                projectAmount
        );
        return;
    }

    const formdata2 = $("#submit-offer-form").serializeArray();
    console.log(formdata2);

    $("#submit-offer-form").submit();
}

$("#send-offer-submit-btn-678956").on("click", function () {
    validateAndSubmitSendOfferForm();
});

document.getElementById("upload-icon").onclick = function () {
    document.getElementById("attachment-files").click();
};

document
    .getElementById("attachment-files")
    .addEventListener("change", function () {
        const fileList = this.files;
        const selectedFilesList = document.getElementById(
            "selected-files-list"
        );
        selectedFilesList.innerHTML = "";

        if (fileList.length > 5) {
            alert("You can only upload a maximum of 5 files.");
            this.value = "";
            return;
        }

        Array.from(fileList).forEach((file, index) => {
            const fileItem = document.createElement("div");
            fileItem.className = "file-item";
            fileItem.innerHTML = `
            <span>${file.name}</span>
            <button type="button"  data-index="${index}">
                    <i class="fas fa-times"></i>
                </button>
        `;
            selectedFilesList.appendChild(fileItem);
        });

        const removeButtons = document.querySelectorAll(".remove-file");
        removeButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const index = this.getAttribute("data-index");
                removeFile(index);
            });
        });
    });

function removeFile(index) {
    const fileInput = document.getElementById("attachment-files");
    const dataTransfer = new DataTransfer();

    Array.from(fileInput.files).forEach((file, i) => {
        if (i != index) {
            dataTransfer.items.add(file);
        }
    });

    fileInput.files = dataTransfer.files;

    const selectedFilesList = document.getElementById("selected-files-list");
    selectedFilesList.innerHTML = ""; // Clear previous selection

    Array.from(fileInput.files).forEach((file, index) => {
        const fileItem = document.createElement("div");
        fileItem.className = "file-item";
        fileItem.innerHTML = `
            <span>${file.name}</span>
            <button type="button" class="remove-file" data-index="${index}">Remove</button>
        `;
        selectedFilesList.appendChild(fileItem);
    });

    const removeButtons = document.querySelectorAll(".remove-file");
    removeButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const index = this.getAttribute("data-index");
            removeFile(index);
        });
    });
}
