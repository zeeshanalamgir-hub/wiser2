@extends('templates.basic.layouts.wiserlance.dashboard-layout', [
    'hideSecondaryHeader' => true,
    'scripts' => ['assets/templates/basic/scripts/js/data-ajax.js', 'assets/templates/basic/scripts/js/user/seller/hire_employ_bid_timesheet.js'],
    'styles' => ['assets/templates/basic/styles/css/racket-tables.css', 'assets/templates/basic/styles/css/user/buyer/job_bids_list.css', 'assets/templates/basic/styles/css/user/buyer/hire_employ_bid_send_offer.css', 'assets/templates/basic/styles/css/racket-forms.css'],
])

@section('content')
    <form action="{{ route('user.seller.add.timesheet') }}" method="POST" enctype="multipart/form-data"
        id="submit-timesheet-form">
        @csrf
        <input type="hidden" name="bid_id" id="bid_id" value="">
        <input type="hidden" id="jobBudget" value="{{ json_encode($jobBudget) }}">
        <div class="racket-forms-input-section">
            <div id="byProjectFields" style="display: none;">
                <div class="racket-forms-outer">
                    <div class="racket-forms-header-outer rounded">
                        <div class="racket-forms-header">
                            <div class="racket-form-heading poppins-semibold ffs-12">Send your work duration</div>
                        </div>
                    </div>
                    <div class="racket-forms-body py-2">
                        <div class="racket-form-body-section">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">Title</div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Title" name="title" value=""
                                            class="form-control afacad-regular ffs-12">
                                        <div class="text-danger" style="display: none"></div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">Start Date</div>
                                    <div class="form-group">
                                        <input type="date" placeholder="date" name="start_date" value=""
                                            class="form-control afacad-regular ffs-12">
                                        <div class="text-danger" style="display: none"></div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">End Date</div>
                                    <div class="form-group">
                                        <input type="date" placeholder="Days" name="end_date" value=""
                                            class="form-control afacad-regular ffs-12">
                                        <div class="text-danger" style="display:none;"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row dynamic-field">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="racket-form-body-section-heading poppins-light ffs-12">Total
                                                    Duration</div>
                                                <input type="number" name="hours" maxlength="255" min="0"
                                                    class="form-control afacad-regular ffs-12" placeholder="Total hours"
                                                    value="">
                                                <div class="text-danger" style="display: none"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="racket-form-body-section-heading poppins-light ffs-12">Minutes
                                                </div>
                                                <input type="number" name="minutes" maxlength="255" min="0"
                                                    max="59" class="form-control afacad-regular ffs-12"
                                                    placeholder="Minutes" value="">
                                                <div class="text-danger" style="display: none"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12 col-lg-12 mt-3">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">Comments</div>
                                    <div class="form-group">
                                        <textarea rows="7" class="form-control racket-forms-text-area nicEdit" placeholder="Description here..."
                                            name="coverletter"></textarea>
                                        <div class="text-danger" style="display:none;"></div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-3">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">Attachment</div>
                                    <div class="racket-attachment-box-outer">
                                        <div class="racket-attachment-file-name-outer">
                                            <span id="racket-attachment-file-name" class="poppins-light ffs-12">Choose file
                                                (png, jpg, pdf, doc)</span>
                                        </div>
                                        <div class="racket-attachment-file-icon">
                                            <img src="{{ asset('assets/wiserlance/assets/img/icons/upload-icon.png') }}"
                                                id="upload-icon">
                                        </div>
                                        <input type="file" name="file[]" id="attachment-files" style="display: none"
                                            multiple>
                                    </div>
                                    <div id="selected-files-list" class="poppins-light ffs-12"></div>
                                    <div class="racket-attachment-hint-below poppins-light ffs-12">
                                        Note: Maximum 5 files can be uploaded. Maximum file size should be 250MB
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-0 mt-3">
                    <div class="col-lg-12 col-md-12 p-0">
                        <button type="submit" id="send-work-timesheet-btn-678956" class="btn btn-md rounded"
                            style="width:100%; background-color:#00C535; padding:15px;color: white">
                            @lang('SEND')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>



    <form action="{{ route('user.seller.add.milestone') }}" method="POST" enctype="multipart/form-data" id="submit-milestone-form">
        @csrf
        <input type="hidden" name="bid_id" id="milestone_bid_id" value="">
        <input type="hidden" id="jobBudget" value="{{ json_encode($jobBudget) }}">
        <div id="byMilestoneMessage" style="display: none;">
            <div class="racket-forms-outer">
                <div class="racket-forms-header-outer rounded">
                    <div class="racket-forms-header">
                        <div class="racket-form-heading poppins-semibold ffs-12">Add Milestone</div>
                    </div>
                </div>
                <div class="racket-forms-body py-2">
                    <div id="milestonesContainer" style="display: block;">
                        <div class="racket-form-body-section">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">Milestone title
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="title[]" value=""
                                            class="form-control afacad-regular ffs-12">
                                        <div class="text-danger" style="display: none"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">Amount</div>
                                    <input type="hidden" id="jobAmount" value="{{ json_encode($jobAmount) }}">
                                    <div class="form-group">
                                        <input type="number" name="amount[]" value=""
                                            class="form-control afacad-regular ffs-12">
                                        <div class="text-danger" style="display: none"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">Due Date</div>
                                    <div class="form-group">
                                        <input type="date" name="due_date[]" value=""
                                            class="form-control afacad-regular ffs-12">
                                        <div class="text-danger" style="display: none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="addMoreButton" style="cursor: pointer;">Add More</div>
                </div>
            </div>
            <div class="row m-0 mt-3">
                <div class="col-lg-12 col-md-12 p-0">
                    <button type="submit" id="send-work-duration-btn-678956" class="btn btn-md rounded"
                        style="width:100%; background-color:#00C535; padding:15px;color: white">
                        @lang('SEND')
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
