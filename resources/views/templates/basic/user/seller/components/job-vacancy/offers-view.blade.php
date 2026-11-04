<div class="racket-table-view-2648652">
    <div class="racket-table-top-header">
        <div class="search-bar-outer-5432634">
            <input placeholder="Search..." type="text" class="poppins-regular ffs-12" id="job-list-offers-search" />
            <i class="fas fa-search" style="opacity: 0.5"></i>
        </div>
    </div>
    <table class="racket-table">
        <thead class="racket-table-line-header">
            <tr>
                <th class="poppins-semibold ffs-12">Job Title</th>
                <th class="poppins-semibold ffs-12">Job Type</th>
                <th class="poppins-semibold ffs-12">Bid Amount</th>
                <th class="poppins-semibold ffs-12">Submitted on</th>
                <th class="poppins-semibold ffs-12">Status</th>
                <th class="poppins-semibold ffs-12" colspan="3">Action</th>
            </tr>
        </thead>
        <tbody class="racket-table-body" id="job-list-offers-data">
            <tr>
                <td colspan="8" class="afacad-regular ffs-12">No Enteries to display</td>
            </tr>
        </tbody>
    </table>
    <div class="racket-table-footer" id="job-list-offers-bottom">
        <div class="poppins-regular ffs-12 to-from-counts">Showing <span id="ell-from">0</span> to <span id="ell-to">0</span> of <span id="ell-total">0</span> enteries</div>
        <div class="page-counter-div-outer">
            <div class="backword-arrow"><i class="fas fa-chevron-left"></i></div>
            <div class="number-box">0</div>
            <div class="forward-arrow"><i class="fas fa-chevron-right"></i></div>
        </div>
    </div>
   

    <template id="buyer-fixed-bid-decline-response-template">
        <tr class="buyer-bit-detail-view-section-6789565">
            <td colspan="9">
                <div class="cover-letter-details-outer-76755">
                    <div class="letter-section-7895">
                        <div class="poppins-semibold ffs-12">Decline Reason</div>
                        <textarea rows="7" id="job-offer-proposal-decline-8588913" class="afacad-regular ffs-12 p-2"></textarea>
                    </div>

                </div>
            </td>
        </tr>
        <tr class="buyer-bit-detail-view-footer-section-6789565">
            <td colspan="9">
                <div class="cover-letter-details-footer-76755">
                    <div class="info-labels-789565 afacad-regular ffs-12">

                    </div>
                    <div class="action-buttons-789565">
                        <div class="poppins-semibold ffs-12 view-job-btn" id="decline-btn-6789564" onclick="declineBuyerFixedBid(this)">Decline</div>
                        <div class="poppins-semibold ffs-12 edit-bit-btn" onclick="hideBidOfferDetails()">Hide Details</div>
                    </div>

                </div>
            </td>
        </tr>
    </template>

</div>
