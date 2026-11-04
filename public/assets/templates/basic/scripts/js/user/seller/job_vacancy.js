// "use strict";
// $(".workBtn").on("click", function () {
//     var modal = $("#workModal");
//     modal.find("input[name=order_number]").val($(this).data("order_number"));
//     modal.modal("show");
// });
// $(".completedBtn").on("click", function () {
//     var modal = $("#completedModal");
//     modal.find("input[name=id]").val($(this).data("id"));
//     modal.modal("show");
// });
// {{ $activeTab == 'openJob' ? 'sd-button-header-10068' : 'sd-button-header-10067' }}

function getActiveButton() {
    const activeClass = "sd-button-header-10068";
    return $(`.${activeClass}`);
}

function toggleActive(el, flag) {
    const activeClass = "sd-button-header-10068";
    const inactiveClass = "sd-button-header-10067";
    $(el).toggleClass(activeClass, flag).toggleClass(inactiveClass, !flag);
}

const buttons = [
    { id: "#btn-bids-5432534", init: initializeForBids },
    { id: "#btn-offers-5432534", init: initializeForOffers },
    { id: "#btn-active-jobs-5432534", init: initializeForActiveJobs },
    { id: "#btn-completed-jobs-5432534", init: initializeForCompletedJobs },
];

buttons.forEach((button) => {
    $(button.id).on("click", function (e) {
        e.preventDefault();
        buttons.forEach((btn) => toggleActive(btn.id, btn.id === button.id));
        button.init();
    });
});

function setActiveTab(activeTabId) {
    // List of all tab IDs
    const tabIds = ["#bids", "#offers", "#active-jobs", "#completed-jobs"];

    // Loop through all tab IDs
    tabIds.forEach((tabId) => {
        if (tabId === activeTabId) {
            // Add 'show active' classes to the active tab
            $(`#racket-job-vacancy-view-tabs ${tabId}`).addClass("show active");
        } else {
            // Remove 'show active' classes from inactive tabs
            $(`#racket-job-vacancy-view-tabs ${tabId}`).removeClass(
                "show active"
            );
        }
    });
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

function setBidsObject(BidsObject) {
    //alert(6);
    if (!BidsObject) {
        return;
    }

    $("#job-list-bids-data").empty();

    if (BidsObject.data.length == 0) {
        $("#job-list-active-data").append(
            '<td colspan="8" class="afacad-regular ffs-12">No Enteries to display</td>'
        );
    }

    for (var i = 0; i < BidsObject.data.length; i++) {
        let item = BidsObject.data[i];

        let jsonString = JSON.stringify(item);
        let encodedJsonString = encodeURIComponent(jsonString);

        const title = item && item.job ? item.job.title : "";
        const type = item && item.job ? convertString(item.job.budgettype) : "";
        const bidAmount = item && item.collective_price_text;
        function formatDate(dateString) {
            const date = new Date(dateString);

            const day = ("0" + date.getDate()).slice(-2); // Pad day with leading zero if needed
            const monthNames = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        }

        const submittedOn =
            item && item.created_date ? formatDate(item.created_date) : "";

        // const biddingStatus = item && item.bid_status ? convertString(item.bid_status) : "";

        var lineHtml = `
        <tr class="br-line-set-8765785" id="bid-list-id-${item.id}" data-item="${encodedJsonString}">
            <td class="afacad-regular ffs-12">${title}</td>
            <td class="afacad-regular ffs-12">${type}</td>
            <td class="afacad-regular ffs-12">${bidAmount}</td>
            <td class="afacad-regular ffs-12">${submittedOn}</td>
            <td class="afacad-regular ffs-12" onclick="openBidDetails(this)" style="cursor: pointer">Bid Detail</td>
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

function setOffersObject(OffersObject) {
    if (!OffersObject) {
        return;
    }

    $("#job-list-offers-data").empty();

    if (OffersObject.data.length == 0) {
        $("#job-list-offers-data").append(
            '<td colspan="8" class="afacad-regular ffs-12">No Enteries to display</td>'
        );
    }

    for (var i = 0; i < OffersObject.data.length; i++) {
        let item = OffersObject.data[i];

        let jsonString = JSON.stringify(item);
        let encodedJsonString = encodeURIComponent(jsonString);

        const title = item && item.job ? item.job.title : "";
        const type = item && item.job ? convertString(item.job.budgettype) : "";
        const bidAmount = item.collective_price_text;
        function formatDate(dateString) {
            const date = new Date(dateString);

            const day = ("0" + date.getDate()).slice(-2); // Pad day with leading zero if needed
            const monthNames = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        }

        const submittedOn =
            item && item.created_date ? formatDate(item.created_date) : "";
        function convertString(status) {
            const statusMap = {
                buyer_offer_received: "Received",
                seller_offer_received: "Received",
                buyer_offer_accepted: "Accepted",
                seller_offer_accepted: "Accepted",
                buyer_offer_rejected: "Rejected",
                seller_offer_rejected: "Rejected",
            };

            return statusMap[status] || status;
        }

        const biddingStatus =
            item && item.bid_status ? convertString(item.bid_status) : "";

        var lineHtml = `
        <tr class="br-line-set-8765785" id="offer-list-id-${item.id}" data-item="${encodedJsonString}">
            <td class="afacad-regular ffs-12">${title}</td>
            <td class="afacad-regular ffs-12">${type}</td>
            <td class="afacad-regular ffs-12">${bidAmount}</td>
            <td class="afacad-regular ffs-12">${submittedOn}</td>
            <td class="afacad-regular ffs-12" id="bid-status">${biddingStatus}</td>
            <td class="afacad-regular ffs-12" onclick="openBidDetails(this)" data-encrypted-id="${item.encrypted_id}" style="cursor: pointer">Offer Detail</td>
        </tr>`;

        $("#job-list-offers-data").append(lineHtml);
    }

    $("#job-list-offers-bottom #ell-to").text(OffersObject.to);
    $("#job-list-offers-bottom #ell-from").text(OffersObject.from);
    $("#job-list-offers-bottom #ell-total").text(OffersObject.total);

    $("#job-list-offers-bottom .number-box").text(OffersObject.current_page);
    $("#job-list-offers-bottom .number-box").data(
        "current_page",
        OffersObject.current_page
    );

    $("#job-list-offers-bottom .forward-arrow").data(
        "last_page",
        OffersObject.last_page
    );

    $("#job-list-offers-bottom .forward-arrow").data(
        "last_page",
        OffersObject.last_page
    );

    $(".racket-table-footer").css("visibility", "visible");
}

function setActiveObject(ActivesObject) {
    if (!ActivesObject) {
        return;
    }

    $("#job-list-active-data").empty();

    if (ActivesObject.data.length == 0) {
        $("#job-list-active-data").append(
            '<td colspan="8" class="afacad-regular ffs-12">No Enteries to display</td>'
        );
    }

    for (var i = 0; i < ActivesObject.data.length; i++) {
        let item = ActivesObject.data[i];

        let jsonString = JSON.stringify(item);
        let encodedJsonString = encodeURIComponent(jsonString);

        const title = item && item.job ? item.job.title : "";
        const type = item && item.job ? convertString(item.job.budgettype) : "";
        const bidAmount = item.collective_price_text;
        function formatDate(dateString) {
            const date = new Date(dateString);

            const day = ("0" + date.getDate()).slice(-2); // Pad day with leading zero if needed
            const monthNames = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        }

        const submittedOn =
            item && item.created_date ? formatDate(item.created_date) : "";
        function convertString(status) {
            const statusMap = {
                buyer_offer_accepted: "Buyer Accepted",
                seller_offer_accepted: "Seller Accepted",
            };

            return statusMap[status] || status;
        }

        const biddingStatus =
            item && item.bid_status ? convertString(item.bid_status) : "";

        var lineHtml = `
        <tr class="br-line-set-8765785" id="offer-list-id-${item.id}" data-item="${encodedJsonString}">
            <td class="afacad-regular ffs-12">${title}</td>
            <td class="afacad-regular ffs-12">${type}</td>
            <td class="afacad-regular ffs-12">${submittedOn}</td>
            <td class="afacad-regular ffs-12" onclick="openAactiveBidDetails(this)" data-encrypted-id="${item.encrypted_id}" style="cursor: pointer">Workroom</td>
        </tr>`;

        $("#job-list-active-data").append(lineHtml);
    }

    $("#job-list-active-bottom #ell-to").text(ActivesObject.to);
    $("#job-list-active-bottom #ell-from").text(ActivesObject.from);
    $("#job-list-active-bottom #ell-total").text(ActivesObject.total);

    $("#job-list-active-bottom .number-box").text(ActivesObject.current_page);
    $("#job-list-active-bottom .number-box").data(
        "current_page",
        ActivesObject.current_page
    );

    $("#job-list-active-bottom .forward-arrow").data(
        "last_page",
        ActivesObject.last_page
    );

    $("#job-list-active-bottom .forward-arrow").data(
        "last_page",
        ActivesObject.last_page
    );

    $(".racket-table-footer").css("visibility", "visible");
}

function openAactiveBidDetails(el) {
    let encodedJsonString = $(el).closest("tr").data("item");
    let jsonString = decodeURIComponent(encodedJsonString);
    let item = JSON.parse(jsonString);
    let itemString = encodeURIComponent(JSON.stringify(item));
    window.location.href = '/user/seller/job/workroom/' + item.encrypted_id;
}


function setCompletedObject(ActivesObject) {
    console.log(ActivesObject, "asfasfasfsa");
    if (!ActivesObject) {
        return;
    }

    $("#job-list-completed-data").empty();

    if (ActivesObject.data.length == 0) {
        $("#job-list-completed-data").append(
            '<td colspan="8" class="afacad-regular ffs-12">No Enteries to display</td>'
        );
    }

    for (var i = 0; i < ActivesObject.data.length; i++) {
        let item = ActivesObject.data[i];

        let jsonString = JSON.stringify(item);
        let encodedJsonString = encodeURIComponent(jsonString);

        const title = item && item.job ? item.job.title : "";
        const type = item && item.job ? convertString(item.job.budgettype) : "";
        const bidAmount = item.collective_price_text;
        function formatDate(dateString) {
            const date = new Date(dateString);

            const day = ("0" + date.getDate()).slice(-2); // Pad day with leading zero if needed
            const monthNames = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        }

        const submittedOn =
            item && item.created_date ? formatDate(item.created_date) : "";
        function convertString(status) {
            const statusMap = {
                buyer_offer_accepted: "Buyer Accepted",
                seller_offer_accepted: "Seller Accepted",
            };

            return statusMap[status] || status;
        }

        const biddingStatus =
            item && item.bid_status ? convertString(item.bid_status) : "";

        var lineHtml = `
        <tr class="br-line-set-8765785" id="offer-list-id-${item.id}" data-item="${encodedJsonString}">
            <td class="afacad-regular ffs-12">${title}</td>
            <td class="afacad-regular ffs-12">${type}</td>
            <td class="afacad-regular ffs-12">${submittedOn}</td>
            <td class="afacad-regular ffs-12" onclick="openBidDetails(this)" data-encrypted-id="${item.encrypted_id}" style="cursor: pointer">Offer Detail</td>
        </tr>`;

        $("#job-list-completed-data").append(lineHtml);
    }

    $("#job-list-completed-bottom #ell-to").text(ActivesObject.to);
    $("#job-list-completed-bottom #ell-from").text(ActivesObject.from);
    $("#job-list-completed-bottom #ell-total").text(ActivesObject.total);

    $("#job-list-completed-bottom .number-box").text(
        ActivesObject.current_page
    );
    $("#job-list-completed-bottom .number-box").data(
        "current_page",
        ActivesObject.current_page
    );

    $("#job-list-completed-bottom .forward-arrow").data(
        "last_page",
        ActivesObject.last_page
    );

    $("#job-list-completed-bottom .forward-arrow").data(
        "last_page",
        ActivesObject.last_page
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

async function initializeForBids(search = "", page = 1) {
    setActiveTab("#bids");

    const res = await getAjax(
        `/user/seller/job/list/ajax/bids?search=${search}&page=${page}`
    );
    console.log(res);
    setBidsObject(res);
}

async function initializeForOffers(search = "", page = 1) {
    setActiveTab("#offers");

    const res = await getAjax(
        `/user/seller/job/list/ajax/offers?search=${search}&page=${page}`
    );
    console.log(res);
    setOffersObject(res);
}

async function initializeForActiveJobs(search = "", page = 1) {
    setActiveTab("#active-jobs");
    const res = await getAjax(
        `/user/seller/job/list/ajax/active?search=${search}&page=${page}`
    );
    setActiveObject(res);
}

async function initializeForCompletedJobs(search = "", page = 1) {
    setActiveTab("#completed-jobs");
    const res = await getAjax(
        `/user/seller/job/list/ajax/completed?search=${search}&page=${page}`
    );
    setCompletedObject(res);
}

// call first tab by default
toggleActive("#btn-bids-5432534", true);
initializeForBids();

async function openBidDetails(el) {
    // console.log(id);

    let encodedJsonString = $(el).closest("tr").data("item");
    let jsonString = decodeURIComponent(encodedJsonString);
    let item = JSON.parse(jsonString);
    console.log(item);

    $("tr.seller-bit-detail-view-section-6789565").remove();
    $("tr.seller-bit-detail-view-footer-section-6789565").remove();

    let templateContent = $($("#single-bid-detail-template").html());

    // setup data in view
    templateContent.find("#job-letter-proposal-78965").val(item.proposal);

    const bidAmount = item.collective_price_text;
    templateContent.find("#job-bid-amount-67896").text(bidAmount);

    const submittedOn = item && item.created_date ? item.created_date : "";
    templateContent.find("#job-bid-submit-date-67896").text(submittedOn);

    const expectedTime = item.seller_proposal_days;
    templateContent
        .find("#job-bid-delivery-time-67896")
        .text(expectedTime + " days - (8hr/d)");

    const platformFeePercentage = item.platform_fee_percentage + "%";
    templateContent
        .find("#platform-fee-percentage")
        .text(platformFeePercentage);

    const platformFee = item.platform_fee_text;
    templateContent.find("#job-bid-platform-fee-67896").text(platformFee);

    const exTotalPrice = item.getpaid_amount_text;
    templateContent.find("#job-bid-ex-total-price-67896").text(exTotalPrice);

    // Find the div with class 'view-job-btn' and add the data attribute
    let encId = item.encrypted_job_id;
    templateContent
        .find("div.view-job-btn")
        .attr("data-encrypted-job-id", encId);

    let slug = slugify(item.job.title);
    let encBidId = item.encrypted_id;
    let url =
        "/user/seller/job/bidding/" + slug + "/" + encId + "?bidId=" + encBidId;
    templateContent.find("div.edit-bit-btn").attr("data-link", url);

    if (item.bid_status != "pending") {
        templateContent.find("div.edit-bit-btn").remove();
    }

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

    console.log(item.bid_status == "buyer_offer_received");
    if (item.bid_status == "buyer_offer_received") {
        let addOfferBtn = `<div class="poppins-semibold ffs-12 view-job-btn" onclick="acceptBuyerFixedBid(this)" data-encrypted-id="${item.encrypted_id}">Accept Offer</div>`;
        $(
            ".seller-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).append(addOfferBtn);

        let removeOfferBtn = `<div class="poppins-semibold ffs-12 view-job-btn" onclick="declineBuyerFixedBidView(this)" data-encrypted-id="${item.encrypted_id}">Decline Offer</div>`;
        $(
            ".seller-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).append(removeOfferBtn);
    }

    if (
        item.bid_status == "buyer_offer_accepted" ||
        item.bid_status == "seller_offer_accepted"
    ) {
        let invoicesBtn = `<div class="poppins-semibold ffs-12 view-job-btn" data-encrypted-id="${item.encrypted_id}">Invoice</div>`;
        $(
            ".seller-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).prepend(invoicesBtn);

        let timesheetBtn = `<div class="poppins-semibold ffs-12 view-job-btn" data-encrypted-id="${item.encrypted_id}">Timesheet</div>`;
        $(
            ".seller-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).prepend(timesheetBtn);

        let messagesBtn = `<div class="poppins-semibold ffs-12 view-job-btn" data-encrypted-id="${item.encrypted_id}">Messages</div>`;
        $(
            ".seller-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).prepend(messagesBtn);
    }
}

function viewJob(el) {
    let encId = $(el).data("encrypted-job-id");
    window.location.href = "/job/details/" + encId;
}

function editJob(el) {
    let link = $(el).data("link");
    window.location.href = link;
}

function hideBidOfferDetails() {
    $("tr.buyer-bit-detail-view-section-6789565").remove();
    $("tr.buyer-bit-detail-view-footer-section-6789565").remove();

    $("tr.seller-bit-detail-view-section-6789565").remove();
    $("tr.seller-bit-detail-view-footer-section-6789565").remove();
}

async function acceptBuyerFixedBid(el) {
    const biddingId = $(el).data("encrypted-id");
    const res = await getAjax(
        "/user/seller/job/list/ajax/offers/accept-offer?encrypt_bid_id=" +
            biddingId
    );
    console.log(res);

    const activeButton = getActiveButton();
    if (activeButton.length > 0) {
        console.log("Active button ID:", activeButton.attr("id"));

        let statement = activeButton.attr("id");
        if (statement == "btn-offers-5432534") {
            var nb = $("#job-list-offers-bottom .number-box").data(
                "current_page"
            );
            let search = $("#job-list-offers-search").val();
            initializeForOffers(search, nb);
        }

        if (statement == "btn-bids-5432534") {
            var nb = $("#job-list-bids-bottom .number-box").data(
                "current_page"
            );
            let search = $("#job-list-bids-search").val();
            initializeForBids(search, nb);
        }
    } else {
        console.log("No active button found.");
    }

    // const biddingId = $(el).data("encrypted-id");
    // const res = await getAjax(
    //     "/user/seller/job/list/ajax/offers/accept-offer?encrypt_bid_id=" +
    //         biddingId
    // );
    // console.log(res);
    // if (res) {
    //     const bid_status = convertString(res.bid_status);
    //     let row = $("#offer-list-id-" + res.id).find("#bid-status");
    //     $(row).text(bid_status);

    //     hideBidOfferDetails();
    // }
}

async function declineBuyerFixedBidView(el) {
    const biddingId = $(el).data("encrypted-id");
    let templateContent = $(
        $("#buyer-fixed-bid-decline-response-template").html()
    );

    templateContent
        .find("#decline-btn-6789564")
        .data("encrypted-id", biddingId);

    var currentRow = $(el).closest("tr");
    currentRow.after(templateContent);
}

async function declineBuyerFixedBid(el) {
    const biddingId = $(el).data("encrypted-id");
    const reason = $("#job-offer-proposal-decline-8588913").val();
    const res = await getAjax(
        `/user/seller/job/list/ajax/offers/decline-offer?encrypt_bid_id=${biddingId}&reason=${reason}`
    );
    console.log(res);
    if (res) {
        const bid_status = convertString(res.bid_status);
        let row = $("#offer-list-id-" + res.id).find("#bid-status");
        $(row).text(bid_status);

        hideBidOfferDetails();
    }
}

async function openBuyerOfferDetails(el) {
    let encodedJsonString = $(el).closest("tr").data("item");
    let jsonString = decodeURIComponent(encodedJsonString);
    let item = JSON.parse(jsonString);
    console.log(item);

    hideBidOfferDetails();

    const biddingId = $(el).data("encrypted-id");
    const res = await getAjax(
        "/user/buyer/hire/employees/list/ajax/bids-list/buyer-offer-fixed-bid-details?encrypt_bid_id=" +
            biddingId
    );
    console.log(res);
    const bid = res["bid"];
    // const item = res['buyer_offer'];
    const itemChild = res["buyer_offer_child"];

    console.log(item, itemChild);

    let templateContent = $($("#buyer-fixed-bid-response-template").html());

    // Find the div with class 'view-job-btn' and add the data attribute
    templateContent.find("#job-letter-proposal-8588966").val(item.description);

    const bidAmount =
        itemChild && itemChild.amount ? "$" + itemChild.amount : "$0";
    templateContent.find("#job-bid-offer-amount-67899").text(bidAmount);

    const submittedOn = item && item.created_date ? item.created_date : "";
    templateContent
        .find("#job-buyer-offer-submit-date-67896")
        .text(submittedOn);

    const daysRequired =
        itemChild && itemChild.days ? itemChild.days + " days - (8hr/d)" : "";
    templateContent
        .find("#job-buyer-offer-delivery-time-67896")
        .text(daysRequired);

    var currentRow = $(el).closest("tr");
    currentRow.after(templateContent);

    if (
        bid.bid_status == "buyer_offer_received" &&
        bid.job.budgettype == "fixed"
    ) {
        let addOfferBtn = `<div class="poppins-semibold ffs-12 view-job-btn" onclick="acceptBuyerFixedBid(this)" data-encrypted-id="${bid.encrypted_id}">Accept Offer</div>`;
        $(
            ".buyer-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).append(addOfferBtn);

        let removeOfferBtn = `<div class="poppins-semibold ffs-12 view-job-btn" onclick="declineBuyerFixedBidView(this)" data-encrypted-id="${bid.encrypted_id}">Decline Offer</div>`;
        $(
            ".buyer-bit-detail-view-footer-section-6789565 .action-buttons-789565"
        ).append(removeOfferBtn);
    }
}
