

function prepareLineItemHtml(item){

    let templateContent = $($('#job-list-lineitem-outer-79067875').html());

    const jobPosterName = item && item.user ? item.user.name : ''
    templateContent.find('#job-poster-6767').text(jobPosterName)

    const jobPosterAbout = item && item.user ? item.user.about_me : ''
    // const bidAmount = item && item.bid_price ? '$' + item.bid_price : '';
    templateContent.find('#job-poster-about-6767').text(jobPosterAbout)

    const submittedOn = item && item.created_date ? item.created_date : '';
    templateContent.find('#job-posted-ago-6767').text(submittedOn)

    const jobTitle = (item ? item.title : '');
    templateContent.find('#job-data-title-6767').text(jobTitle)

    const experLevel = (item ? item.experience_level : '');
    templateContent.find('#job-dta-experience-level-6767').text(experLevel)

    const priceText = (item ? item.collective_price_text : '');
    templateContent.find('#job-dta-price-text-6767').text(priceText)

    const descriptionText = (item ? item.description : '');
    templateContent.find('#job-data-description-6767').html(descriptionText)

    const bidCountText = (item ? item.bid_count : 0);
    templateContent.find('#job-data-bid-count-6767').text(bidCountText)

    const jobLink = (item ? '/job/details/' + item.encrypted_job_id : '');
    templateContent.find('#job-view-detail-link-a-6767').attr('href', jobLink)


    if(item.skill && item.skill.length > 0){
        templateContent.find('#job-skills-list-6767m').empty();
        for(var j = 0; j < item.skill.length; j++){
            let skill = item.skill[j]
            if(skill){
                let skhtml = `<li class="item skills-list"><span class="px-2 py-1 medium skill-bg rounded text-dark">${skill}</span></li>`
                templateContent.find('#job-skills-list-6767m').append(skhtml);
            }

        }
    }


    //



    // const platformFeePercentage = (item && item.platform_fee_percentage ? item.platform_fee_percentage + '%' : '0%');
    // templateContent.find('#platform-fee-percentage').text(platformFeePercentage)

    // const platformFee = (item && item.platform_fee ? '$' + item.platform_fee : '$0');
    // templateContent.find('#job-bid-platform-fee-67896').text(platformFee)

    // const exTotalPrice = (item && item.expected_total_price ? '$' + item.expected_total_price : '$0');
    // templateContent.find('#job-bid-ex-total-price-67896').text(exTotalPrice)


    // Find the div with class 'view-job-btn' and add the data attribute
    // let encId = $(el).data('encrypted-job-id');
    // templateContent.find('div.view-job-btn').attr('data-encrypted-job-id', encId);

    return templateContent

}


async function getJobListData(search = '', page = 1, filters = null){

    let obj = {
        search:search,
        page:page,
        filters: filters
    }
    const res = await postAjax(`/job/list/ajax/line-items`, obj, false )
    console.log(res);

    let data = res.data;

    if(page = 1){
        $("#job-list-outer-79067875").empty()
    }

    for(var i = 0; i < data.length; i++){
        let html = prepareLineItemHtml(data[i])
        $("#job-list-outer-79067875").append(html)

    }

    // set page counters
    const totalPages = res.last_page;
    $("#pagination-box-outer-785645 .pagination-page-input-box").text(totalPages)
    const currentPage = res.current_page;
    $("#pagination-box-outer-785645 .number-box").text(currentPage)


}

getJobListData();

function getAllFilters(){

    // get to know all categories checked
    var checkedCategoryIds = [];

    // Use a jQuery selector to find all checked checkboxes with the class 'category-checkbox'
    $('.category-checkbox:checked').each(function() {
        // Get the value of the checkbox, which is the category ID, and add it to the array
        checkedCategoryIds.push($(this).val());
    });

    // At this point, 'checkedCategoryIds' contains all the checked category IDs
    console.log(checkedCategoryIds);

    let filterObj = {
        categories: checkedCategoryIds
    }

    return filterObj;

}

$(document).on('click', '.category-checkbox', function() {
    var $this = $(this);
    var category_id = $this.val();
    console.log(category_id)
    const fl = getAllFilters()
    getJobListData($('#job-search-input-67856').val(), 1, fl)
});

$('#job-search-input-67856').keypress(function(event) {
    if (event.which === 13) {  // 13 is the Enter key code
        event.preventDefault(); // Prevent the default action (form submission)
        var searchQuery = $(this).val();
        const fl = getAllFilters()
        getJobListData(searchQuery, 1, fl)
    }
});

// set page counters

$("#pagination-box-outer-785645 .backword-arrow").on('click', function(e){
    const tx = $("#pagination-box-outer-785645 .number-box").text();
    const ty = $("#pagination-box-outer-785645 #total-page-678888").text()

    const txi = parseInt(tx)
    const tyi = parseInt(ty)
    if(txi != 1){
        var searchQuery = $(this).val();
        const fl = getAllFilters()
        getJobListData(searchQuery, txi - 1, fl)
    }


})
$("#pagination-box-outer-785645 .forward-arrow").on('click', function(e){

    const tx = $("#pagination-box-outer-785645 .number-box").text();
    const ty = $("#pagination-box-outer-785645 #total-page-678888").text()

    const txi = parseInt(tx)
    const tyi = parseInt(ty)
    console.log(txi, tyi)
    if(txi < tyi){
        var searchQuery = $(this).val();
        const fl = getAllFilters()
        getJobListData(searchQuery, txi + 1, fl)
    }
})
