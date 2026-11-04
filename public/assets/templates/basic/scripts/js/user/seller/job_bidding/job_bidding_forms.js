$("input[name=projectmethod]").change(function () {
    var test = $(this).val();
    $("#" + test).show();
});

$("input[name=projectmethod]:first").prop("checked", true).trigger("change");

function validateInput(input, fee, jobAmount) {
    // Get the entered value
    var enteredValue = input.value;

    // Convert the value to a number
    var numericValue = parseFloat(enteredValue);
    console.log(numericValue);
    console.log(jobAmount);
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

// Function to check if a value is a valid number
function isValidNumber(value) {
    // Use regular expression to check for valid number format
    return /^-?\d*\.?\d+$/.test(value);
}

function calculatetotal(amount, share) {
    console.log("calculatetotal");
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
        console.log("totall", totall);
        $("#total").val(totall - totall / share);
        $("#fee").val(totall / share);
    }
}

$(".jobBidingMore").on("click", function () {
    var link = $(this).data("link");
    var page = $(this).data("page");
    var href = link + page;
    var jobBidingCount = 0; //{{ $jobBidings->total() }};
    $.get(href, function (response) {
        var html = $(response).find("#jobBidingShow").html();
        $("#jobBidingShow").append(html);
        var loadMoreCount = 5 * page;
        if (loadMoreCount > jobBidingCount) {
            $(".jobBidingMore").hide();
        }
    });
    $(this).data("page", parseInt(page) + 1);
});
$(document).ready(function () {
    $("#Bidbtn").click(function () {
        $("#Bidmodel").show();
    });
});

function calculatetotal1(amount) {
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
        console.log(totall);
        $("#total").val(totall);
    }
}

$(document).on("input", ".bidamountmulti", function () {
    var total = 0;
    var jobprice = $("#job_price").val();
    var percent = $("#feemulti").val();
    $(".bidamountmulti").each(function (key, value) {
        var $this = $(this);
        var thisval = $this.val();
        console.log(thisval);
        total += parseFloat(thisval);
        console.log(total);
    });

    var points = total - total / percent;
    console.log(points);
    $("#totalmulti").val(points);
});

function showError(key, error) {
    $("#fixed-milestone-job-bidding-form-253243").find(key).show().text(error);
    setTimeout(() => {
        $("#fixed-milestone-job-bidding-form-253243").find(key).hide();
    }, 5000);
}

// byproject
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

        // check posted price should be less then or equal to job posted price

        resolve(valid);
    });
}

// bymilestone
function validateByMilestoneForm(formdata){

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



$("#fixed-milestone-job-bidding-form-253243").on('submit', async function(e){
    e.preventDefault();

    // check which prop is checked, fixed or milestone
    var checkedValue = $("input[name=projectmethod]:checked").val();

    let flag = false;

    const formdata = $(
        "#fixed-milestone-job-bidding-form-253243"
    ).serializeArray();
    console.log(checkedValue);

    if (checkedValue == "byproject" ) {
        flag = await validateByProjectForm(formdata);
    }

    if(checkedValue == 'bymilestone'){
        flag = await validateByMilestoneForm(formdata);
    }


    if(!flag){
        return;
    }


    if (checkedValue == "byproject" || checkedValue == "per_hour") {
        console.log("by project proposal can submit")
        this.submit()
    }

    if(checkedValue == 'bymilestone'){
        console.log("by milestone proposal can submit")
        this.submit()
    }
});

// document.getElementById("upload-icon").onclick = function () {
//     document.getElementById("attachment-files").click();
// };

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
            <button type="button" class="remove-file" data-index="${index}">
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
            <button type="button" class="remove-file" data-index="${index}">
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
}
