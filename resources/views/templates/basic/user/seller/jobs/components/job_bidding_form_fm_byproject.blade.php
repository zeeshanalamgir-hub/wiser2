<div id="byproject" class="show-hide row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
        <div class="form-group">
            <div class="racket-form-body-section-heading poppins-light ffs-12">Amount*</div>
            <!-- Use old value or set bid_price when editing -->
            <input oninput="validateInput(this, {{ showAmount($
            membershipFee) }}, {{ $job->amount }})" id="input_bid"
                type="number" name="bid_price" max="{{ showAmount($job->amount) }}" min="1"
                class="form-control afacad-regular ffs-12"
                placeholder="amount" value="{{ old('bid_price', isset($bid) ? $bid->bid_price : '') }}">
            <div id="job_bid_price_error" class="text-danger" style="display: none"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
        <div class="form-group">
            <div class="racket-form-body-section-heading poppins-light ffs-12">Days*</div>
            <!-- Use old value or set due_date when editing -->
            <input id="due_date" type="number" name="due_date" max="255" min="1"
                class="form-control afacad-regular ffs-12"
                placeholder="day" value="{{ old('due_date', isset($bid) ? $bid->due_date : '') }}">
            <div id="job_due_date_error" class="text-danger" style="display: none"></div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
        <div class="form-group">
            <div class="racket-form-body-section-heading poppins-light ffs-12">
                Platform Fee ({{ $amntPercent }}%)
            </div>
            <input id="fee" name="cutfix" readonly
                class="form-control afacad-regular ffs-12"
                value="{{ isset($bid) ? ($bid->bid_price * $amntPercent / 100) : 0 }}">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
        <div class="form-group">
            <div class="racket-form-body-section-heading poppins-light ffs-12">You Will Get Paid</div>
            <input id="total" readonly name value="{{ isset($bid) ? $bid->total : 0 }}"
                class="form-control afacad-regular ffs-12">
        </div>
    </div>
</div>
