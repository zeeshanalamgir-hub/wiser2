
function viewJob(el){
    let encId = $(el).data('encrypted-job-id');
    window.location.href = '/job/details/' + encId
}


function hideBidDetails(){
    $('tr.seller-bit-detail-view-section-6789565').remove();
    $('tr.seller-bit-detail-view-footer-section-6789565').remove();
}

function openBidDetails(el){

    let encodedJsonString = $(el).closest('tr').data('item');
    let jsonString = decodeURIComponent(encodedJsonString);
    let item = JSON.parse(jsonString);
    console.log(item)


    $('tr.seller-bit-detail-view-section-6789565').remove();
    $('tr.seller-bit-detail-view-footer-section-6789565').remove();

    let templateContent = $($('#single-bid-detail-template').html());

    // setup data in view
    templateContent.find('#job-letter-proposal-78965').val(item.proposal)

    const bidAmount = item && item.bid_price ? '$' + item.bid_price : '';
    templateContent.find('#job-bid-amount-67896').text(bidAmount)

    const submittedOn = item && item.created_date ? item.created_date : '';
    templateContent.find('#job-bid-submit-date-67896').text(submittedOn)

    const expectedTime = (item && item.job ? item.job.delivery_time : '0');
    templateContent.find('#job-bid-delivery-time-67896').text(expectedTime + ' days - (8hr/d)')

    const platformFeePercentage = (item && item.platform_fee_percentage ? item.platform_fee_percentage + '%' : '0%');
    templateContent.find('#platform-fee-percentage').text(platformFeePercentage)

    const platformFee = (item && item.platform_fee ? '$' + item.platform_fee : '$0');
    templateContent.find('#job-bid-platform-fee-67896').text(platformFee)

    const exTotalPrice = (item && item.expected_total_price ? '$' + item.expected_total_price : '$0');
    templateContent.find('#job-bid-ex-total-price-67896').text(exTotalPrice)


    // Find the div with class 'view-job-btn' and add the data attribute
    let encId = $(el).data('encrypted-job-id');
    templateContent.find('div.view-job-btn').attr('data-encrypted-job-id', encId);


    var currentRow = $(el).closest('tr');
    currentRow.after(templateContent);

}



function setBidDetailsObject(BidsObject){

    function convertString(input) {
        switch (input) {
            case 'per_hour':
                return 'Per Hour';
            case 'fixed':
                return 'Fixed';
            default:
                return input;
        }
    }

    if(!BidsObject){
        return;
    }


    $("#job-list-bids-details-data").empty();

    if(BidsObject.data.length == 0){
        $("#job-list-offers-data").append('<td colspan="9" class="afacad-regular ffs-12">No Enteries to display</td>');
    }


    for(var i = 0; i < BidsObject.data.length; i++){
        let item = BidsObject.data[i];

        let jsonString = JSON.stringify(item);
        let encodedJsonString = encodeURIComponent(jsonString);

        const title = item && item.job ? item.job.title : '';
        const type = item && item.job ? convertString(item.job.budgettype) : '';
        const buyer = item && item.job && item.job.user ? item.job.user.name : ''
        const bidAmount = item && item.bid_price ? '$' + item.bid_price : '';
        const budget = item && item.job && item.job.amount ? '$' + item.job.amount : '';
        const submittedOn = item && item.created_date ? item.created_date : '';
        const biddingStatus = item && item.bidding_status ? item.bidding_status.status_text : ''

        $("#table-heading-67895").text(title);



        var lineHtml = `
        <tr class="br-line-set-8765785" data-item="${encodedJsonString}">
            <td class="afacad-regular ffs-12">${title}</td>
            <td class="afacad-regular ffs-12">${type}</td>
            <td class="afacad-regular ffs-12">${buyer}</td>
            <td class="afacad-regular ffs-12">${submittedOn}</td>
            <td class="afacad-regular ffs-12">${bidAmount}</td>
            <td class="afacad-regular ffs-12">${budget}</td>
            <td class="afacad-regular ffs-12">${biddingStatus}</td>
            <td class="afacad-regular ffs-12" onclick="openBidDetails(this)" data-encrypted-job-id="${item.encrypted_job_id}" data-user-bidding-id="${item.id}" style="cursor: pointer">Bid Detail</td>
            <td class="afacad-regular ffs-12">Cancel Bid</td>
        </tr>`

        $("#job-list-bids-details-data").append(lineHtml);
    }


    $("#job-list-bids-details-bottom #ell-to").text(BidsObject.to);
    $("#job-list-bids-details-bottom #ell-from").text(BidsObject.from);
    $("#job-list-bids-details-bottom #ell-total").text(BidsObject.total);

    $("#job-list-bids-details-bottom .number-box").text(BidsObject.current_page);
    $("#job-list-bids-details-bottom .number-box").data('current_page', BidsObject.current_page);

    $("#job-list-bids-details-bottom .forward-arrow").data('last_page', BidsObject.last_page);

    $("#job-list-bids-details-bottom .forward-arrow").data('last_page', BidsObject.last_page);

    $(".racket-table-footer").css("visibility", "visible");

}


function getEncJobId(){

    let url = window.location.pathname;
    let lastParameter = url.split('/').pop();
    console.log(lastParameter);
    return lastParameter;
}

async function initializeForBidDetails(search = '', page = 1) {

    let encId = getEncJobId();
    const res = await getAjax(`/user/seller/job/list/ajax/bids/details?encrypt_job_id=${encId}&search=${search}&page=${page}`);
    console.log(res);
    setBidDetailsObject(res)
}


initializeForBidDetails();
