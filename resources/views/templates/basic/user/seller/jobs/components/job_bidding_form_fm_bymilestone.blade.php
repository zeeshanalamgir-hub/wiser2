<div id="bymilestone" class="show-hide milestone" style="display: none;">
    <input type="hidden" value="{{ showAmount($job->amount) }}" name="job_price" />
    <input type="hidden" value="{{ showAmount($membershipFee) }}" name="fee" />
    <input name="milestone_job" value="{{ $job->id }}" type="hidden" />
    <input name="job_milestone_total" value="0" type="hidden" />



    <div class="row dynamic-field" id="dynamic-field-1">
        <div class="col-md-4">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Milestone
                </div>
                <input type="text" name="milestone_title[]" id="field" maxlength="255" min="1"
                    class="form-control afacad-regular ffs-12" placeholder="@lang('Title')">
                <div id="job_milestone_title_error" class="text-danger" style="display: none"></div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Amount
                </div>
                <input type="number" name="milestone_amount[]" id="milestone_amount"  maxlength="255" min="1"
                    class="form-control afacad-regular ffs-12" placeholder="@lang('Amount')" onkeyup="updateJobBudgetWithMilestoneAmount(this)">
                <div id="job_milestone_amount_error" class="text-danger" style="display: none"></div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Days
                </div>
                <input type="number" name="milestone_due_date[]" id="milestone_days" maxlength="255" min="1"
                    class="form-control afacad-regular ffs-12" placeholder="@lang('Days')">
                <div id="job_milestone_due_date_error" class="text-danger" style="display: none"></div>
            </div>
        </div>

    </div>


    <div class="row mt-3">
        <div class="col-12">
            <button type="button" id="add-button" class="milestone-add-button-outer poppins-semibold ffs-14">Add
                milestone</button>
            <button type="button" id="remove-button"
                class="milestone-remove-button-outer poppins-semibold ffs-14">remove last milestone</button>
        </div>
    </div>


    <div class="row ">
        <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Job Budget*</div>
                <input id="job_price" readonly class="form-control milestone_job_total_price" value="0">
                <div id="job_milestone_total_error" class="text-danger" style="display: none"></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">Platform Fee ( {{ $amntPercent }} %)</div>
                <input id="feemulti" name="cutmile" readonly value="0" class="form-control">

            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2">
            <div class="form-group">
                <div class="racket-form-body-section-heading poppins-light ffs-12">You Will Get Paid</div>
                <input id="totalmulti" readonly value="0" class="form-control">

            </div>
        </div>
    </div>

</div>
