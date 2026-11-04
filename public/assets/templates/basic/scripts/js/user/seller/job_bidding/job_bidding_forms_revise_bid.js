$(document).ready(function () {

    var $form = $('#fixed-milestone-job-bidding-form-253243');
    var urlParams = new URLSearchParams(window.location.search);
    var bidId = urlParams.get('bidId');

    if (bidId) {
        var currentActionUrl = $form.attr('action');
        var newActionUrl = new URL(currentActionUrl, window.location.origin);
        newActionUrl.searchParams.append('bidId', bidId);

        $form.attr('action', newActionUrl.toString());
    }

    setTimeout(() => {
        setDataInReviseBid();
    }, 500);
});

function setDataInReviseBid() {
    let reviseBidData = $("#reviseBidData").val();
    // var reviseBidData = JSON.parse(
    //     document.getElementById("reviseBidData").value
    // );



    if (reviseBidData) {
        reviseBidData = JSON.parse(reviseBidData);
        console.log(reviseBidData);

        let checkType = reviseBidData.job_type;
        console.log(checkType);

        if (checkType == "per_hour") {
            setByHourData(reviseBidData);
        } else if (checkType == "byproject") {
            setByProjectData(reviseBidData);
        } else if (checkType == "bymilestone") {
            setByMilestoneData(reviseBidData);
        }


    }
}

function setByProjectData(data) {
    var reviseBidData = JSON.parse(
        document.getElementById("reviseBidData").value
    );

    if (reviseBidData) {
        document.getElementById("input_bid").value = reviseBidData.bid_price;
        document.getElementById("due_date").value = Number(
            reviseBidData.due_date
        );
        document.getElementById("proposal").value = reviseBidData.proposal;
        document.getElementById("attachment-files").value = reviseBidData.file;
        const formdata = $(
            "#fixed-milestone-job-bidding-form-253243"
        ).serializeArray();

        const job_amount = formdata.find((x) => x.name == "job_amount").value;
        const fee = formdata.find((x) => x.name == "fee").value;

        calculatetotal(job_amount, fee);
    }
}

function setByHourData(data) {
    var reviseBidData = JSON.parse(
        document.getElementById("reviseBidData").value
    );

    if (reviseBidData) {
        document.getElementById("input_bid").value = reviseBidData.bid_price;
        document.getElementById("due_date").value = Number(
            reviseBidData.due_date
        );
        document.getElementById("proposal").value = reviseBidData.proposal;
        document.getElementById("attachment-files").value = reviseBidData.file;

        let el = document.getElementById("input_bid");
        let mfee = $("input[name=job_membershipfee]").val();
        let maxBgt = $("input[name=job_max_budget]").val();
        console.log(mfee, maxBgt);
        validateInput(el, mfee, maxBgt);

        // const formdata = $(
        //     "#fixed-milestone-job-bidding-form-253243"
        // ).serializeArray();

        // console.log(formdata)

        // const job_amount = formdata.find((x) => x.name == "job_amount").value;
        // const fee = formdata.find((x) => x.name == "fee").value;

        // calculatetotal(job_amount, fee);
    }
}

function setByMilestoneData(data) {
    // switch form to milestone
    $("input[name=projectmethod]:last").prop("checked", true).trigger("change");
    console.log(data);

    // get milestone data
    const msd = data.user_milestones;

    const tf = totalFields();
    console.log(tf);

    if (tf > 0 && msd && msd.length > 0) {
        let el = $(className).first();

        el.find('#field').val(msd[0].title);
        el.find('#milestone_amount').val(msd[0].amount);
        el.find('#milestone_days').val(msd[0].due_date);
        // Append the cloned element to the container
        // $(className).after(el);

        // Loop through the msd array to create elements
        for (let i = 1; i < msd.length; i++) {
            // Clone the element
            let ele = $(className).first().clone();
            // Set the value from the msd array
            ele.find('#field').val(msd[i].title);
            ele.find('#milestone_amount').val(msd[i].amount);
            ele.find('#milestone_days').val(msd[i].due_date);
            // Append the cloned element to the container
            $(className).after(ele);
        }
    }

    document.getElementById("proposal").value = data.proposal;
    document.getElementById("attachment-files").value = data.file;

    // if (msd && msd.length > 0) {
    //     // $(className).empty();

    //     const tf = totalFields();
    //     console.log(tf)

    //     if (tf > 0 ) {

    //         let el = $(className).clone();
    //         el.find('#field').val(md[0].title)

    //         $(className).afer().append(el)






    //     }

    //     setTimeout(() => {

    //         addNewField();
    //         enableButtonRemove();
    //         disableButtonAdd();

    //         // // Add event listeners for milestone amount changes
    //         // document
    //         //     .getElementById("milestone_amount")
    //         //     .addEventListener("input", updateJobBudgetWithMilestoneAmount);
    //         // document
    //         //     .getElementById("milestone_amount")
    //         //     .addEventListener("change", updateJobBudgetWithMilestoneAmount);
    //     }, 500);

    //     // by milestone
    //     // console.log(reviseBidData.user_milestones[0], "revised bid data");
    //     // document.getElementById("field").value =
    //     //     reviseBidData.user_milestones[0].title;
    //     // document.getElementById("milestone_amount").value =
    //     //     reviseBidData.user_milestones[0].amount;
    //     // document.getElementById("milestone_days").value =
    //     //     reviseBidData.user_milestones[0].due_date;
    // }

    // Update the total amounts and other related fields after setting the data
    updateJobBudgetWithMilestoneAmount();
}
