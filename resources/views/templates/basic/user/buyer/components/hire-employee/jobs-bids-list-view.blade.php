<div class="racket-table-view-2648652">
    <div class="racket-table-top-header-with-heading">
        <div class="racket-table-heading-green afacad-medium ffs-14" id="table-heading-67895">
            geen title just check text and clolrs
        </div>
        <div class="search-bar-outer-5432634">
            <input placeholder="Search..." type="text" class="poppins-regular ffs-12" id="job-list-bids-search" />
            <i class="fas fa-search" style="opacity: 0.5"></i>
        </div>
    </div>
    <table class="racket-table">
        <thead class="racket-table-line-header">
            <tr>
                <th class="poppins-semibold ffs-12">Job Type</th>
                <th class="poppins-semibold ffs-12">Seller</th>
                <th class="poppins-semibold ffs-12">Submitted on</th>
                <th class="poppins-semibold ffs-12">Bid Amount</th>
                <th class="poppins-semibold ffs-12">Status</th>
                <th class="poppins-semibold ffs-12" colspan="1">Action</th>
            </tr>
        </thead>
        <tbody class="racket-table-body" id="job-list-bids-data">
            <tr>
                <td colspan="8" class="afacad-regular ffs-12">No Enteries to display</td>
            </tr>
        </tbody>
    </table>
    <div class="racket-table-footer" id="job-list-bids-bottom">
        <div class="poppins-regular ffs-12 to-from-counts">Showing <span id="ell-from">0</span> to <span
                id="ell-to">0</span> of <span id="ell-total">0</span> enteries</div>
        <div class="page-counter-div-outer">
            <div class="backword-arrow"><i class="fas fa-chevron-left"></i></div>
            <div class="number-box">0</div>
            <div class="forward-arrow"><i class="fas fa-chevron-right"></i></div>
        </div>
    </div>

    <template id="single-bid-detail-template">
        <tr class="buyer-bit-detail-view-section-6789565">
            <td colspan="9">
                <div class="cover-letter-details-outer-76755">
                    <div class="letter-section-7895">
                        <div class="poppins-semibold ffs-12">Cover Letter</div>
                        <textarea rows="7" readonly id="job-letter-proposal-78965" class="afacad-regular ffs-12 p-2"></textarea>
                    </div>
                    <div class="letter-section-7895">
                        <div class="d-flex justify-content-between">
                            <div class="poppins-semibold ffs-12">Matched Skills</div>
                            <div class="afacad-regular ffs-12 text-right" id="job-bid-matched-skills-67896"></div>
                        </div>
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
                    {{-- <div class="line-item-section-7895">
                        <div class="poppins-semibold ffs-12">MileStone</div>
                        <div class="afacad-regular ffs-12" id="job-bid-milestone-title"></div>
                    </div> --}}

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
                        <div class="poppins-semibold ffs-12 view-job-btn" data-encrypted-job-id=""
                            onclick="viewJob(this)">View Job</div>
                        <div class="poppins-semibold ffs-12 edit-bit-btn" onclick="hideBidDetails()">Hide Details</div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="9">
                <div id="skills"></div> <!-- Add a separate container for skills -->
            </td>
        </tr>


    </template>

    <template id="buyer-fixed-bid-response-template">
        <tr class="buyer-bit-detail-view-section-6789565">
            <td colspan="9">
                <div class="cover-letter-details-outer-76755">
                    <div class="letter-section-7895">
                        <div class="poppins-semibold ffs-12">Cover Letter</div>
                        <textarea rows="7" readonly id="job-letter-proposal-8588966" class="afacad-regular ffs-12 p-2"></textarea>
                    </div>
                    <div class="line-item-section-7895">
                        <div class="poppins-semibold ffs-12">Bid Amount</div>
                        <div class="afacad-regular ffs-12" id="job-bid-offer-amount-67899">$0.00</div>
                    </div>
                    <div class="line-item-section-7895">
                        <div class="poppins-semibold ffs-12">Submit Date</div>
                        <div class="afacad-regular ffs-12" id="job-buyer-offer-submit-date-67896"></div>
                    </div>
                    <div class="line-item-section-7895">
                        <div class="poppins-semibold ffs-12">Time</div>
                        <div class="afacad-regular ffs-12" id="job-buyer-offer-delivery-time-67896"></div>
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
                        <div class="poppins-semibold ffs-12 view-job-btn" onclick="viewJob(this)">View Job</div>
                        <div class="poppins-semibold ffs-12 edit-bit-btn" onclick="hideBidDetails()">Hide Details</div>
                    </div>

                </div>
            </td>
        </tr>
    </template>

    <template id="seller-fixed-bid-decline-response-template">
        <tr class="buyer-bit-detail-view-section-6789565">
            <td colspan="9">
                <div class="cover-letter-details-outer-76755">
                    <div class="letter-section-7895">
                        <div class="poppins-semibold ffs-12">Decline Reason</div>
                        <textarea rows="7" readonly id="job-offer-proposal-decline-8588913" class="afacad-regular ffs-12 p-2"></textarea>
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
                        <div class="poppins-semibold ffs-12 view-job-btn" id="resend-btn-6789599"
                            onclick="openSendOfferSection(this)">Resend Offer</div>
                        <div class="poppins-semibold ffs-12 edit-bit-btn" onclick="hideBidDetails()">Hide Details
                        </div>
                    </div>

                </div>
            </td>
        </tr>
    </template>

</div>


<style>
#skills {
    text-align: center;
}

#skills .skill-box {
    display: inline-block;
    /* padding: 5px;
    margin: 2px; */
    font-size: 10px;
    font-family: "Afacad Regular", sans-serif;
    font-weight: 400;
    font-style: normal;
    color: black;
    border-radius: 3px;
}

.matched-true {
    background-color:#f0f0f0;
    color: black;
}

.matched-false {
    color: black;
}


  </style>
