

<div id="per_hour" class="show-hide row" style="display: none;">
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Bid Amount Per Hour *</div>
                <input oninput="validateInput(this, {{ showAmount($membershipFee) }}, {{ $job->maxBudget }})" id="input_bid"  type="number" name="bid_price" max="{{ showAmount($job->maxBudget) }}" min="1"
                class="form-control afacad-regular ffs-12" placeholder="amount">
                <div id="job_bid_price_error" class="text-danger" style="display: none"></div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Days*</div>
                <input id="due_date" type="number" name="due_date" max="255" min="1"
                    class="form-control afacad-regular ffs-12" placeholder="day">
                <div id="job_due_date_error" class="text-danger" style="display: none"></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Platform Fee ( {{ $amntPercent }}%)</div>
                <input id="fee"  name="cutfix" readonly value="0"
                    class="form-control afacad-regular ffs-12">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">You Will Get Paid</div>
                <input id="total" readonly name value="0" class="form-control afacad-regular ffs-12">
            </div>
        </div>

</div>

