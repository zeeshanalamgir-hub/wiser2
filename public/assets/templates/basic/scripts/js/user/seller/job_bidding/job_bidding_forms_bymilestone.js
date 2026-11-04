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
    field.attr("id", "dynamic-field" + count);
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
    if (totalFields() === maxFields - 1) {
        buttonAdd.removeAttr("disabled");
        buttonAdd.addClass("shadow-sm");
    }
}
buttonAdd.click(function () {
    addNewField();
    enableButtonRemove();
    disableButtonAdd();
});
buttonRemove.click(function () {
    removeLastField();
    disableButtonRemove();
    enableButtonAdd();
});


function setByProjectData(data) {}

function setByHourData(data) {
    console.log(data);
}

function setByMilestoneData(data) {
    // by milestone
    console.log(reviseBidData.user_milestones[0], "revised bid data");
    document.getElementById("field").value =
        reviseBidData.user_milestones[0].title;
    document.getElementById("milestone_amount").value =
        reviseBidData.user_milestones[0].amount;
    document.getElementById("milestone_days").value =
        reviseBidData.user_milestones[0].due_date;

    // Add event listeners for milestone amount changes
    document
        .getElementById("milestone_amount")
        .addEventListener("input", updateJobBudgetWithMilestoneAmount);
    document
        .getElementById("milestone_amount")
        .addEventListener("change", updateJobBudgetWithMilestoneAmount);

    // Update the total amounts and other related fields after setting the data
    updateJobBudgetWithMilestoneAmount();
}

function updateJobBudgetWithMilestoneAmount() {
    let valid = true;

    const formdata = $(
        "#fixed-milestone-job-bidding-form-253243"
    ).serializeArray();
    console.log("formdata", formdata);

    const milestone_amount = formdata.filter(
        (x) => x.name == "milestone_amount[]"
    );
    console.log("milestone_amountddddddddd", milestone_amount);

    var total = 0;
    for (var i = 0; i < milestone_amount.length; i++) {
        let item = milestone_amount[i];
        console.log(item.value);
        const v = item.value ? parseInt(item.value) : 0;
        total = total + v;
    }

    $(".milestone_job_total_price").val(total);
    $(".job_milestone_total").val(total);

    const job_amount = formdata.find((x) => x.name == "job_amount").value;
    const int_job_amount = parseInt(job_amount);

    if (total > int_job_amount) {
        showError(
            "#job_milestone_total_error",
            "Total budget cannot exceed job budget"
        );
        valid = false;
    }

    // feemulti
    // get percentage of platform fee
    const job_membershipfee = formdata.find(
        (x) => x.name == "job_membershipfee"
    ).value;
    const int_job_membershipfee = parseInt(job_membershipfee);

    const cut_amount = parseFloat(
        total * (int_job_membershipfee / 100)
    ).toFixed(2);
    $("#feemulti").val(cut_amount);

    $("#totalmulti").val(total - cut_amount);

    return valid;
}
