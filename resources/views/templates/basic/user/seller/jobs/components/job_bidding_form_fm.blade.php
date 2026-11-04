<form enctype="multipart/form-data" method="post" id="fixed-milestone-job-bidding-form-253243"
    action="{{ url('user/seller/job/addbiding') }}">
    {{-- <input type="hidden" id="reviseBidData" value="{{ json_encode($bidw) }}"> --}}
    @csrf
    <input name="job_id" value="{{ $job->id }}" type="hidden" />
    <input name="job_budgettype" value="{{ $job->budgettype }}" type="hidden" />
    <input name="job_amount" value="{{ $job->amount }}" type="hidden" />
    <input name="job_min_budget" value="{{ $job->minBudget }}" type="hidden" />
    <input name="job_max_budget" value="{{ $job->maxBudget }}" type="hidden" />
    <input name="job_membershipfee" value="{{ $membershipFee }}" type="hidden" />


    <div class="racket-forms-outer">

        <div class="racket-forms-input-section">
            <div class="racket-forms-header-outer rounded">
                <div class="racket-forms-header">
                    <div class="racket-form-heading poppins-semibold ffs-12">Bidding Details1235</div>
                </div>
            </div>
        </div>
        <div class="racket-forms-body">
            <div class="racket-form-body-section">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                Client's Budget ${{ $job->amount }}
                            </div>
                            <div class="col-6">
                                Client's Due Date: {{ $formattedDeliveryDate }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="poppins-semibold ffs-14">@lang('How do you want to be paid?')</div>
                            <div class="radio">
                                <input type="radio" id="byproject" value="byproject" name="projectmethod"
                                    {{ isset($existingBid) && trim($existingBid->job_type) === 'byproject' ? 'checked' : '' }}
                                    style="border: 2px solid red;" />
                                <label for="byproject">By Project</label> &nbsp;

                                <input type="radio" id="bymilestone" value="bymilestone" name="projectmethod"
                                    {{ isset($existingBid) && trim($existingBid->job_type) === 'bymilestone' ? 'checked' : '' }}
                                    style="border: 2px solid red;" />
                                <label for="bymilestone">By Milestone</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="racket-form-body-section">
                @include('templates.basic.user.seller.jobs.components.job_bidding_form_fm_byproject')
                @include('templates.basic.user.seller.jobs.components.job_bidding_form_fm_bymilestone')
            </div>
            <div class="racket-form-body-section">
                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                            Cover Letter*
                        </div>
                        <div class="form-group">
                            <textarea rows="10" cols="10" class="form-control racket-forms-text-area" id="proposal" name="proposal"></textarea>
                            <div id="job_proposal_error" class="text-danger" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="racket-form-body-section-heading poppins-light ffs-12">
                                    Attachment.
                                </div>
                                <div class="racket-attachment-box-outer">
                                    <div class="racket-attachment-file-name-outer"><span
                                            id="racket-attachment-file-name" class="poppins-light ffs-12">Choose file
                                            (png, jpg, pdf, doc)</span></div>


                                    <div class="input-group bg-lsgreen">
                                        <input type="file" class="form-control" name="file[]" id="attachment-files"
                                            multiple>

                                    </div>
                                </div>
                                <div id="selected-files-list" class="poppins-light ffs-12"></div>
                                <div class="racket-attachment-hint-below poppins-light ffs-12">Note: Maximum 5 files can
                                    be uploaded. Maximum file size should be 250M</div>
                            </div>
                            <div id="file_error" class="text-danger" style="display:none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

    <div class="col-lg-12 col-md-12 col-dm-12 col-12 my-3 mb-2 px-0">
        <button type="submit" class="btn rounded text-light   ft-medium"
            style="width:100%; background-color:#00C535; padding:15px; ">@lang('Submit Bid')</button>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var existingBidJobType = "{{ isset($existingBid) ? $existingBid->job_type : '' }}";
        console.log(existingBidJobType);
        if (existingBidJobType === 'byproject') {
            document.getElementById('byproject').checked = true;
        } else if (existingBidJobType === 'bymilestone') {
            document.getElementById('bymilestone').checked = true;
        }
    });
    </script>
