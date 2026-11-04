<div class="racket-table-view-2648652">
    <div class="racket-table-top-header">
        <div class="search-bar-outer-5432634">
            <input placeholder="Search..." type="text" class="poppins-regular ffs-12" id="job-list-active-search" />
            <i class="fas fa-search" style="opacity: 0.5"></i>
        </div>
    </div>
    <table class="racket-table">
        <thead class="racket-table-line-header">
            <tr>
                <th class="poppins-semibold ffs-12">Job Title</th>
                <th class="poppins-semibold ffs-12">Job Type</th>
                {{-- <th class="poppins-semibold ffs-12">Price</th> --}}
                <th class="poppins-semibold ffs-12">Created At</th>
                <th class="poppins-semibold ffs-12">Username</th>
                <th class="poppins-semibold ffs-12" colspan="1">Action</th>
            </tr>
        </thead>
        <tbody class="racket-table-body" id="job-list-completed-data">
            <tr>
                <td colspan="6" class="afacad-regular ffs-12">No Enteries to display</td>
            </tr>
        </tbody>
    </table>
    <div class="racket-table-footer" id="job-list-completed-bottom">
        <div class="poppins-regular ffs-12 to-from-counts">Showing <span id="ell-from">0</span> to <span id="ell-to">0</span> of <span id="ell-total">0</span> enteries</div>
        <div class="page-counter-div-outer">
            <div class="backword-arrow"><i class="fas fa-chevron-left"></i></div>
            <div class="number-box">0</div>
            <div class="forward-arrow"><i class="fas fa-chevron-right"></i></div>
        </div>
    </div>

</div>

<template id="completed-job-bid-detail-template">
    <tr class="buyer-bit-detail-view-section-6789565">
        <td colspan="9">
            <div class="cover-letter-details-outer-76755">
                <div class="letter-section-7895">
                    <div class="poppins-semibold ffs-12">Cover Letter</div>
                    <textarea rows="7" readonly id="job-letter-proposal-78965" class="afacad-regular ffs-12 p-2"></textarea>
                </div>
                <div class="line-item-section-7895">
                    <div class="poppins-semibold ffs-12">Bid Amount</div>
                    <div class="afacad-regular ffs-12" id="job-bid-amount-67896">$0.00</div>
                </div>
                <div class="line-item-section-7895">
                    <div class="poppins-semibold ffs-12">Submit Date</div>
                    <div class="afacad-regular ffs-12" id="job-bid-submit-date-67896"></div>
                </div>
                <div class="line-item-section-7895">
                    <div class="poppins-semibold ffs-12">Time</div>
                    <div class="afacad-regular ffs-12" id="job-bid-delivery-time-67896"></div>
                </div>

            </div>
        </td>
    </tr>
    <tr class="buyer-bit-detail-view-footer-section-6789565">
        <td colspan="9">
            <div class="cover-letter-details-footer-76755">
                <div class="info-labels-789565 afacad-regular ffs-12">
                    Last Updated <span id="last-updated-id-87685"></span>
                </div>
                <div class="action-buttons-789565">
                    <div class="poppins-semibold ffs-12 view-job-btn" onclick="viewJob(this)">Messages</div>
                    <div class="poppins-semibold ffs-12 view-job-btn" onclick="view(this)">Timesheet</div>
                    <div class="poppins-semibold ffs-12 view-job-btn" onclick="view(this)">Invoice</div>
                    <div class="poppins-semibold ffs-12 view-job-btn" onclick="viewJob(this)">View Job</div>
                    <div class="poppins-semibold ffs-12 edit-bit-btn" onclick="hideBidDetails()">Hide Details</div>
                </div>

            </div>
        </td>
    </tr>
</template>
