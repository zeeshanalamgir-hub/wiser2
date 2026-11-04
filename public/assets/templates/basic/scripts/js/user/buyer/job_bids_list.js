function viewJob(el) {
    let encId = $(el).data("encrypted-job-id");
    window.location.href = "/job/details/" + encId;
}

function hideBidDetails() {
    $("tr.buyer-bit-detail-view-section-6789565").remove();
    $("tr.buyer-bit-detail-view-footer-section-6789565").remove();
}

function hideBidOfferDetails() {
    $("tr.buyer-fixed-bid-response-template").remove();
    $("tr.buyer-bit-detail-view-footer-section-6789565").remove();
}

function openBidDetails(el) {
    let encodedJsonString = $(el).closest("tr").data("item");
    let jsonString = decodeURIComponent(encodedJsonString);
    let item = JSON.parse(jsonString);
    console.log(item);

    hideBidDetails();

    let templateContent = $($("#single-bid-detail-template").html());

    // setup data in view
    templateContent.find("#job-letter-proposal-78965").val(item.proposal);

    const bidAmount =
        item && item.collective_price_text ? item.collective_price_text : "$0";
    templateContent.find("#job-bid-amount-67896").text(bidAmount);

    const submittedOn = item && item.created_date ? item.created_date : "";
    templateContent.find("#job-bid-submit-date-67896").text(submittedOn);

    const expectedTime = item && item.seller_proposal_days;
    templateContent
        .find("#job-bid-delivery-time-67896")
        .text(expectedTime + " days - (8hr/d)");

    const platformFeePercentage =
        item && item.platform_fee_percentage
            ? item.platform_fee_percentage + "%"
            : "0%";
    templateContent
        .find("#platform-fee-percentage")
        .text(platformFeePercentage);

    const platformFee =
        item && item.platform_fee ? "$" + item.platform_fee : "$0";
    templateContent.find("#job-bid-platform-fee-67896").text(platformFee);

    const exTotalPrice =
        item && item.expected_total_price
            ? "$" + item.expected_total_price
            : "$0";
    templateContent.find("#job-bid-ex-total-price-67896").text(exTotalPrice);

    let matchedSkills = item.matched_skills;
    let matchedSkillsHtml = ``;

    for (var i = 0; i < matchedSkills.length; i++) {
        let skill = matchedSkills[i].skill;
        let skillName = skill.name;
        let isMatched = matchedSkills[i].matched;

        matchedSkillsHtml += `${isMatched ? '<b>'+ skillName +'</b>' : skillName}, `;
    }

    matchedSkillsHtml += ``;

    templateContent.find("#job-bid-matched-skills-67896").html(matchedSkillsHtml);

    // Find the div with class 'view-job-btn' and add the data attribute
    // let encId = $(el).data("encrypted-job-id");
    let encId = getEncJobId();

    console.log(templateContent.find("div.view-job-btn"));

    templateContent
        .find("div.view-job-btn")
        .attr("data-encrypted-job-id", encId);

    // mile stone to be set if available

    if (item.job_type == "bymilestone") {
        let umm = item["user_milestones"];
        let mtx = ``;
        if (umm.length == 0) {
            mtx = "No Milestones available";
        }

        if (umm.length > 0) {
            for (var i = 0; i < umm.length; i++) {
                mtx += `${umm[i]["title"]} - $${umm[i]["amount"]} - ${umm[i]["due_date"]} day <br>`;
            }
        }

        let mhtml =
            `
            <div class="line-item-section-7895">
                <div class="poppins-semibold ffs-12">Milestone</div>
                <div class="afacad-regular ffs-12 text-right" id="job-buyer-offer-milestones-67896"> ` +
            mtx +
            `</div>
            </div>
        `;

        templateContent.find(".line-item-section-7895").last().after(mhtml);
    }

    var currentRow = $(el).closest("tr");
    currentRow.after(templateContent);

    // call api that determine if seller has already post an offer

    // add action button to post an offer
    if (
        item.bid_status == "pending" &&
        (item.job.budgettype == "fixed" || item.job.budgettype == "per_hour")
    ) {
        let addOfferBtn = `<div class="poppins-semibold ffs-12 view-job-btn" onclick="openSendOfferSection(this)" data-encrypted-id="${item.encrypted_id}">Send Offer</div>`;
        $(
            ".buyer-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).prepend(addOfferBtn);
    }

    // if(item.bid_status == 'buyer_offer_received' && item.job.budgettype == 'fixed'){

    //     let addOfferBtn = `<div class="poppins-semibold ffs-12 view-job-btn" onclick="openbuyerFixedBidResponseDetails(this)" data-encrypted-id="${item.encrypted_id}">Show Offer</div>`;
    //     $(".buyer-bit-detail-view-footer-section-6789565 .action-buttons-789565").append(addOfferBtn);
    // }

    if ( item.bid_status == "seller_offer_decline") {
        let addOfferBtn = `<div class="poppins-semibold ffs-12 view-job-btn" onclick="openSellerFixedBidResponseDetails(this)" data-encrypted-id="${item.encrypted_id}">Show Declined Offer</div>`;
        $(".buyer-bit-detail-view-footer-section-6789565 .action-buttons-789565").prepend(addOfferBtn);
    }
}

async function openSellerFixedBidResponseDetails(el) {
    const biddingId = $(el).data("encrypted-id");
    const res = await getAjax(
        `/user/buyer/hire/employees/list/ajax/bids-list/buyer-offer-fixed-bid-details?encrypt_bid_id=${biddingId}`
    );

    const bid = res["bid"];
    const item = res["buyer_offer"];
    const itemChild = res["buyer_offer_child"];

    // console.log(item, itemChild );

    let templateContent = $(
        $("#seller-fixed-bid-decline-response-template").html()
    );

    // // Find the div with class 'view-job-btn' and add the data attribute
    templateContent
        .find("#job-offer-proposal-decline-8588913")
        .val(bid.seller_offer_responce_reason);

    // const bidAmount = itemChild && itemChild.amount ? "$" + itemChild.amount : "$0";
    templateContent.find("#resend-btn-6789599").data("encrypted-id", biddingId);

    // const submittedOn = item && item.created_date ? item.created_date : "";
    // templateContent.find("#job-buyer-offer-submit-date-67896").text(submittedOn);

    // const daysRequired = itemChild && itemChild.days ? itemChild.days + " days - (8hr/d)" : "";
    // templateContent.find("#job-buyer-offer-delivery-time-67896").text(daysRequired);

    var currentRow = $(el).closest("tr");
    currentRow.after(templateContent);
}

function convertString(input) {
    // Replace underscores, hyphens, and camelCase with spaces
    let result = input
        .replace(/([a-z])([A-Z])/g, "$1 $2") // Convert camelCase to space-separated words
        .replace(/[_-]+/g, " "); // Replace underscores and hyphens with spaces

    // Convert to title case
    result = result.replace(/\b\w/g, (char) => char.toUpperCase());

    return result;
}

function openSendOfferSection(el) {
    const id = $(el).data("encrypted-id");
    window.location.href = "/user/buyer/hire/employees/bids/send-offer/" + id;
}

function setBidsObject(BidsObject) {
    //alert(2);
    if (!BidsObject) {
        return;
    }
    $("#job-list-bids-data").empty();
    console.log(BidsObject.data.length);
    if (BidsObject.data.length == 0) {
        $("#job-list-offers-data").append(
            '<td colspan="8" class="afacad-regular ffs-12">No Enteries to display</td>'
        );
    }

    for (var i = 0; i < BidsObject.data.length; i++) {
        let item = BidsObject.data[i];

        let jsonString = JSON.stringify(item);
        let encodedJsonString = encodeURIComponent(jsonString);

        const title = item && item.job ? item.job.title : "";
        const type = item && item.job ? convertString(item.job.budgettype) : "";
        const seller = item && item.user ? item.user.username : "";
        const submittedOn = item && item.created_date ? item.created_date : "";
        const bidAmount =
            item && item.collective_price_text
                ? item.collective_price_text
                : "$0";
        const biddingStatus =
            item && item.bid_status ? convertString(item.bid_status) : "";

        $("#table-heading-67895").text(title);

        var lineHtml = `
        <tr class="br-line-set-8765785" data-item="${encodedJsonString}">
            <td class="afacad-regular ffs-12">${type}</td>
            <td class="afacad-regular ffs-12">${seller}</td>
            <td class="afacad-regular ffs-12">${submittedOn}</td>
            <td class="afacad-regular ffs-12">${bidAmount}</td>
            <td class="afacad-regular ffs-12">${biddingStatus}</td>
            <td class="afacad-regular ffs-12" onclick="openBidDetails(this)" data- style="cursor: pointer">Bid Detail</td>
        </tr>`;

        $("#job-list-bids-data").append(lineHtml);
    }

    $("#job-list-bids-bottom #ell-to").text(BidsObject.to);
    $("#job-list-bids-bottom #ell-from").text(BidsObject.from);
    $("#job-list-bids-bottom #ell-total").text(BidsObject.total);

    $("#job-list-bids-bottom .number-box").text(BidsObject.current_page);
    $("#job-list-bids-bottom .number-box").data(
        "current_page",
        BidsObject.current_page
    );

    $("#job-list-bids-bottom .forward-arrow").data(
        "last_page",
        BidsObject.last_page
    );

    $("#job-list-bids-bottom .forward-arrow").data(
        "last_page",
        BidsObject.last_page
    );

    $(".racket-table-footer").css("visibility", "visible");
}

$("#job-list-bids-search").on("keyup", function () {
    var query = $(this).val().toLowerCase();
    // Call the search function with the query
    initializeForBids(query, 1);
});

$("#job-list-bids-bottom .forward-arrow").on("click", function (e) {
    var nb = $("#job-list-bids-bottom .number-box").data("current_page");
    var lp = $(this).data("last_page");
    console.log(nb, lp);
    if (parseInt(nb) < parseInt(lp)) {
        console.log("move to next page");
        let nex = nb + 1;
        let search = $("#job-list-bids-search").val();
        initializeForBids(search, nex);
    }
});

$("#job-list-bids-bottom .backword-arrow").on("click", function (e) {
    var nb = $("#job-list-bids-bottom .number-box").data("current_page");
    if (parseInt(nb) != 1) {
        console.log("move to back page");
        let nex = nb - 1;
        let search = $("#job-list-bids-search").val();
        initializeForBids(search, nex);
    }
});

function getEncJobId() {
    let url = window.location.pathname;
    let lastParameter = url.split("/").pop();
    console.log(lastParameter);
    return lastParameter;
}

async function initializeForBidDetails(search = "", page = 1) {
    let encId = getEncJobId();
    const res = await getAjax(
        `/user/buyer/hire/employees/list/ajax/bids-list?encrypt_job_id=${encId}&search=${search}&page=${page}`
    );
    console.log(res);
    setBidsObject(res);
}

function milestone() {
    alert("milestone");
}
function message() {
    alert("message");
}
function invoice() {
    alert("invoice");
}
function rating() {
    alert("rating");
}
initializeForBidDetails();
