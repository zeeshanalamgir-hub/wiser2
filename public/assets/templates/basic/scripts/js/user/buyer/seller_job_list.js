window.initializeTabs = function () {
    console.log("only seller tabs");
    let search = "";
    page = 1;
    const tabs = {
        '#btn-open-job': { url: '/user/buyer/hire/employees/list/ajax/open-jobs', target: 'jobs', newUrl: '/user/buyer/hire/open', pageTitle: 'All Jobs' },
        '#btn-offers': { url: '/user/buyer/hire/employees/list/ajax/offered-jobs', target: 'jobs', newUrl: '/user/buyer/hire/offered-jobs', pageTitle: 'Offered Jobs' },
        '#btn-active-jobs': { url: '/user/buyer/hire/employees/list/ajax/active-offers', target: 'jobs', pageTitle: 'Active Jobs' },
        '#btn-completed-jobs': { url: '/user/buyer/hire/employees/list/ajax/completed-job', target: 'jobs', pageTitle: 'Completed Jobs' },
        '#btn-closed-jobs': { url: '/user/buyer/hire/employees/list/ajax/closed-jobs', target: 'jobs', pageTitle: 'Closed Jobs' },
        '#btn-bids': { url: `/user/seller/job/list/ajax/bids?search=${search}&page=${page}`, target: 'jobs', pageTitle: 'Bids' },
        '#btn-seller-offers': { url: `/user/seller/job/list/ajax/offers?search=${search}&page=${page}`, target: 'jobs', pageTitle: 'Offers' },
        '#btn-seller-active-jobs': { url: `/user/seller/job/list/ajax/active?search=${search}&page=${page}`, target: 'jobs', pageTitle: 'Active Jobs' },
        '#btn-seller-completed-jobs': { url: `/user/seller/job/list/ajax/completed?search=${search}&page=${page}`, target: 'jobs', pageTitle: 'Completed Jobs' },
    };

    Object.keys(tabs).forEach(buttonId => {
        $(buttonId).off('click').on('click', function (e) {
            e.preventDefault();
            $('.btn').removeClass('btn-success bg-green').addClass('btn-outline-secondary');
            $(this).addClass('btn-success bg-green').removeClass('btn-outline-secondary');
            setActiveTab(buttonId, tabs[buttonId].url, tabs[buttonId].target, tabs[buttonId].newUrl, tabs[buttonId].pageTitle);
        });
    });
};
function handlePaginationClick(url) {
    fetchAndUpdateContent(url, 'jobs', url, document.getElementById('page-title').innerText);
}
function fetchAndUpdateContent(url, targetId, newUrl, pageTitle) {
    const targetElement = document.getElementById(targetId);
    document.getElementById('breadcrumb-title').innerText = pageTitle;
    // if (currentTitle === pageTitle && targetElement && targetElement.innerHTML.trim() !== "") {
    //     console.log(`Content for "${pageTitle}" already loaded.`);
    //     return;
    // }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        cache: 'no-store'
    })
        .then(response => {
            if (response.status === 401) {
                return response.json().then(data => {
                    console.error("User not authenticated. Redirecting...");
                    window.location.href = data.redirect_url; // Redirect to login page
                });
            }
            return response.text();
        })
        .then(html => {
            $(`#${targetId}`).html(html);
            if (history.pushState) {
                console.log("Pushing state:", newUrl || url);
                $('#search').val('');
                history.pushState({ path: newUrl || url }, '', newUrl || url);
            }
             const params = new URLSearchParams(window.location.search);
            const searchValue = params.get('search');

            if (searchValue) {
                $('#search').val(searchValue);
            } else {
                $('#search').val('');
            }
            if (document.getElementById('page-title')) {
                document.getElementById('page-title').innerText = pageTitle;
            }
            initializeTabs();
        })
        .catch(error => console.error("Fetch error: ", error));
}
function fetchBids(url) {
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.text())
        .then(html => {
            // Assuming you have a div with ID `job-details` to display the bids.
            document.getElementById('jobs').innerHTML = html;
        })
        .catch(error => console.error('Error fetching bids:', error));
}

function setActiveTab(currentId, url, targetId, newUrl, pageTitle) {
    // console.log("currentId => ",currentId , "url => ",url, "targetId => ",targetId,"newUrl => ",newUrl,"pageTitle => ",pageTitle);
    $('.tab-pane').removeClass('show active'); // Hide all tabs
    $(`#${targetId}`).addClass('show active'); // Show the target tab

    fetchAndUpdateContent(url, targetId, newUrl, pageTitle);
}

// Initial tab setup
document.addEventListener('DOMContentLoaded', function () {
    initializeTabs();

    const activeTab = document.querySelector('.btn-success.seller'); // Adjust selector if needed
    if (activeTab) {
        let activeTabUrl = activeTab.getAttribute('data-url');
        const activeTabPageTitle = activeTab.getAttribute('data-page-title');
        const activeTabTarget = activeTab.getAttribute('data-target');

        let finalUrl = activeTabUrl;
        if (window.location.search) {
            let prefix = finalUrl.includes('?') ? '&' : '?';
            finalUrl += prefix + window.location.search.substring(1);
        }

        if (finalUrl && activeTabTarget) {
            fetchAndUpdateContent(finalUrl, activeTabTarget, finalUrl, activeTabPageTitle);
        }
    }
});
$('.cancelbidBtn').on('click', function () {
    var modal = $('#cancelbidModal');
    modal.find('input[name=cancelbid_id]').val($(this).data('cancelbid_id'))
    modal.modal('show');
});