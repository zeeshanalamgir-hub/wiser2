function toggleActive(el, flag) {
    const activeClass = "sd-button-header-10068";
    const inactiveClass = "sd-button-header-10067";
    $(el).toggleClass(activeClass, flag).toggleClass(inactiveClass, !flag);
}

const buttons = [{ id: "#btn-manage-job-5432534", init: initializeForBids }];

buttons.forEach((button) => {
    $(button.id).on("click", function (e) {
        e.preventDefault();
        buttons.forEach((btn) => toggleActive(btn.id, btn.id === button.id));
        button.init();
    });
});

function setActiveTab(activeTabId) {
    // List of all tab IDs
    const tabIds = ["#open-job"];

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

async function initializeForBids(search = "", page = 1) {
    setActiveTab("#manage-job");

    const res = await getAjax(
        `/user/buyer/list/ajax/manage-jobs?search=${search}&page=${page}`
    );
    console.log(res);
    setBidsObject(res);
}

function setBidsObject(res) {
    if (!res) {
        return;
    }

    $("#job-list-bids-data").empty();

    if (res.data.length === 0) {
        $("#job-list-bids-data").append(
            '<td colspan="8" class="afacad-regular ffs-12">No Entries to display</td>'
        );
    }

    res.data.forEach(item => {
        const title = item.title || "";
        const type = item.budgettype || "";
        const status = item.status_text || "Unknown";
        const bidCount = item.bid_count || "0";
        const submittedOn = item.created_date || "";

        const lineHtml = `
        <tr class="br-line-set-8765785" id="open-job-item-${item.id}">
            <td class="afacad-regular ffs-12">${title}</td>
            <td class="afacad-regular ffs-12">${type}</td>
            <td class="afacad-regular ffs-12">${bidCount}</td>
            <td class="afacad-regular ffs-12">${submittedOn}</td>
            <td class="afacad-regular ffs-12">${status}</td>
            <td class="afacad-regular ffs-12" onclick="openJobViewPage(this)" data-job-id="${item.encrypted_job_id}" style="cursor: pointer; user-selection: none">View Job</td>
            <td class="afacad-regular ffs-12" onclick="openBidDetails('${item.encrypted_job_id}')" style="cursor: pointer; user-select: none;">Edit Job</td>
        </tr>`;

        $("#job-list-bids-data").append(lineHtml);
    });

    // Update pagination information
    $("#job-list-bids-bottom #ell-to").text(res.to);
    $("#job-list-bids-bottom #ell-from").text(res.from);
    $("#job-list-bids-bottom #ell-total").text(res.total);
    $("#job-list-bids-bottom .number-box").text(res.current_page);
    $("#job-list-bids-bottom .number-box").data("current_page", res.current_page);
    $("#job-list-bids-bottom .forward-arrow").data("last_page", res.last_page);
    $("#job-list-bids-bottom .backword-arrow").data("last_page", res.last_page);

    $(".racket-table-footer").css("visibility", "visible");
}


function handleSearch() {
    const searchInput = $("#job-list-bids-search").val();
    initializeForBids(searchInput);
}

function handlePagination(direction) {
    const currentPage = $("#job-list-bids-bottom .number-box").data(
        "current_page"
    );
    const lastPage = $("#job-list-bids-bottom .forward-arrow").data(
        "last_page"
    );
    let newPage = currentPage;

    if (direction === "next" && currentPage < lastPage) {
        newPage++;
    } else if (direction === "prev" && currentPage > 1) {
        newPage--;
    }

    const searchInput = $("#job-list-bids-search").val();
    initializeForBids(searchInput, newPage);
}

$(document).ready(function () {
    $("#job-list-bids-search").on("input", handleSearch);

    $("#job-list-bids-bottom .forward-arrow").on("click", function () {
        handlePagination("next");
    });

    $("#job-list-bids-bottom .backword-arrow").on("click", function () {
        handlePagination("prev");
    });

    toggleActive("#btn-manage-job-5432534", true);
    initializeForBids();
});


async function openJobViewPage(el) {
    let encId = $(el).data("job-id");
    if (!encId) {
        return;
    }
    let routeUrl = "/job/details/" + encodeURIComponent(encId);
    window.location.href = routeUrl;
}

function openBidDetails(jobId) {
    const url = `edit/${jobId}`;

    // Redirect to the constructed URL
    window.location.href = url;
}
