@extends('templates.basic.layouts.wiserlance.layout', [
    'scripts' => [
        'assets/templates/basic/scripts/js/data-ajax.js',

    ],
    'styles' => [

    ]

])
@section('content')
    @php
        $helpers = new \App\Classes\Helpers();
        $currency_rates = $helpers->get_currency_rates();
        $currencies = $helpers->get_all_currencies();
    @endphp

    <style>
        .bitems-per-heading-78965875 {

            display: flex;
            flex-direction: row;
            margin: 16px;

        }

        .bitems-per-heading-78965875 .bitems-per-heading-78965875-left {
            width: 70%;
            padding-right: 16px;
            border-right: 1px solid #6F6F6F;
        }

        .bitems-per-heading-78965875 .bitems-per-heading-78965875-left .bitems-per-heading-78965875-title {
            color: var(--secondary-color);
        }

        .bitems-per-heading-78965875 .bitems-per-heading-78965875-left .bitems-per-heading-78965875-content {
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-line-clamp: 2;
            /* Number of lines to show */
        }



        .bitems-per-heading-78965875 .bitems-per-heading-78965875-right {
            width: 30%;
            padding: 0 16px;
            display: flex;
            ;
            align-items: center;
        }

        .sor-card-7669-option-ic-outer {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .sor-card-7669-option-ic-outer .sor-card-7669-option-ic-item {
            width: calc(90% - 10px);
            margin-bottom: 10px;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;

        }
    </style>
    <section class="bg-light py-5 position-relative">
        <div class="container">
            <div class="dashboard-tlbar d-block mb-3">
                <div class="row">
                    <div class="colxl-12 col-lg-12 col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">

                                <div class="breadcrum-box-678968">
                                    <a href="{{ route('home') }}"> <img class="home-breadcrum-77897"
                                            src="{{ asset('assets/wiserlance/assets/img/icons/home-gray-breadcrum.svg') }}" />
                                    </a>
                                    <img class="home-breadcrum-77899"
                                        src="{{ asset('assets/wiserlance/assets/img/icons/chevron-left.svg') }}" />
                                    <a class="ntn-poppins-semibold home-breadcrum-77852">Job Details</a>
                                    <img class="home-breadcrum-77899"
                                        src="{{ asset('assets/wiserlance/assets/img/icons/chevron-left.svg') }}" />
                                    <a class="ntn-poppins-semibold home-breadcrum-77852">Bid Now</a>
                                </div>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="sor-card-7668">
                        <div class="form-heading">
                            <div class="bar-title-678975 ntn-poppins-bold">Job Details</div>
                        </div>
                        <div class="sor-card-7668-content">

                            <div class="bitems-per-heading-78965875">

                                <div class="bitems-per-heading-78965875-left">

                                    <div class="bitems-per-heading-78965875-title ntn-poppins-semibold">
                                        Title:
                                    </div>

                                    <div class="bitems-per-heading-78965875-content">
                                        {!! $job->title !!}
                                    </div>

                                    <div class="bitems-per-heading-78965875-title ntn-poppins-semibold">
                                        Description:
                                    </div>

                                    <div class="bitems-per-heading-78965875-content">
                                        {!! $job->description !!}
                                    </div>

                                    <div class="bitems-per-heading-78965875-title ntn-poppins-semibold">
                                        Requirements:
                                    </div>

                                    <div class="bitems-per-heading-78965875-content">
                                        {!! $job->requirements !!}
                                    </div>

                                </div>

                                <div class="bitems-per-heading-78965875-right">

                                    <div class="sor-card-7669-option-ic-outer">
                                        <div class="sor-card-7669-option-ic-item">
                                            <img
                                                src="{{ asset('assets/wiserlance/assets/img/icons/briefcase-gray.svg') }}" />
                                            <div> Level -
                                                @if ($job->exp_type == '1')
                                                    <span class="newdesign">Entry level</span>
                                                @elseif($job->exp_type == '2')
                                                    <span class="newdesign">intermediate</span>
                                                @elseif($job->exp_type == '3')
                                                    <span class="newdesign">Expert</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sor-card-7669-option-ic-item">
                                            <img src="{{ asset('assets/wiserlance/assets/img/icons/budget-gray.svg') }}" />
                                            <div> Budget Type -
                                                @if ($job->budgettype == 'fixed')
                                                    <span class="newdesign">Fixed</span>
                                                @elseif($job->budgettype == 'per_hour')
                                                    <span class="newdesign">Per hour</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sor-card-7669-option-ic-item">
                                            <img
                                                src="{{ asset('assets/wiserlance/assets/img/icons/ref-time-gray.svg') }}" />
                                            <div> Posted -
                                                <span class="newdesign">{{ diffforhumans($job->created_at) }} </span>
                                            </div>
                                        </div>
                                        <div class="sor-card-7669-option-ic-item">
                                            <img
                                                src="{{ asset('assets/wiserlance/assets/img/icons/ref-time-g-gray.svg') }}" />
                                            <div> Delivery Time -
                                                <span class="newdesign">{{ $job->delivery_time }} @lang('Days')</span>
                                            </div>
                                        </div>
                                        <div class="sor-card-7669-option-ic-item">
                                            <img
                                                src="{{ asset('assets/wiserlance/assets/img/icons/budget-f-gray.svg') }}" />
                                            <div> Budget -
                                                @if ($job->budgettype == 'per_hour')
                                                    <span class="newdesign"> {{ "$" . $job->minBudget }} -
                                                        {{ "$" . $job->maxBudget }}
                                                        /
                                                        hour</span>
                                                @else
                                                    <span class="newdesign"> {{ $job->amount . "$" }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sor-card-7669-option-ic-item">
                                            <img
                                                src="{{ asset('assets/wiserlance/assets/img/icons/location-pin-gray.svg') }}" />
                                            <div> Location - Worldwise</div>
                                        </div>

                                    </div>

                                </div>

                            </div>



                        </div>

                    </div>
                </div>

                <div class="col-xl-12 col-lg-12">
                    <div class="sor-card-7668">

                        <div class="form-heading">
                            <div class="bar-title-678975 ntn-poppins-bold">Skills & Expertise</div>
                        </div>

                        <div class="sor-card-7668-content py-3">
                            <div class="sor-card-7668-badge-ic-outer">

                                @if (isset($job->skill) && $job->skill > 0)
                                    @foreach ($job->skill as $skill)
                                        <div class="sor-card-7668-badge-ic-item">
                                            {{ __($skill) }}
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>


                    </div>
                </div>

                <div class="col-xl-12 col-lg-12">
                    <div class="sor-card-7668">
                        <div class="form-heading">
                            <div class="bar-title-678975 ntn-poppins-bold">@lang('Matching Skills')</div>
                        </div>

                        <div class="sor-card-7668-content py-3">
                            <div class="sor-card-7668-badge-ic-outer">

                                @if ($job->skill > 0)
                                    @foreach ($matchingSkills as $skill)
                                        <div class="sor-card-7668-badge-ic-item">{{ __($skill->name) }}</div>
                                    @endforeach
                                @else
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $amntPercent = 0;

                if ($job->budgettype == 'per_hour') {
                    $amntPercent = ceil(($membershipFee / $job->maxBudget) * 100);
                } else {
                    $amntPercent = $membershipFee;
                }

            @endphp
            <form enctype="multipart/form-data" method="post" action="{{ url('/job/addbiding') }}">
                @csrf
                <input name="job_id" value="{{ $job->id }}" type="hidden" />

                <div class="bg-white rounded mb-4">

                    <div class="jbd-01 px-3 py-4">
                        <div class="form-heading">
                            <div class="bar-title-678975 ntn-poppins-bold">Bidding Details</div>
                        </div>
                        <div class="jbd-details mb-4 mt-4">
                            <h6 class="fs-md" style="font-weight: 600">@lang('How do you want to be paid?')</h6>
                            <div class="radio">
                                <input type="radio" id="female-rb" value="byproject" name="projectmethod" />
                                <label for="female-rb">By Project</label> &nbsp
                                <input type="radio" id="female-rb1" value="bymilestone" name="projectmethod" />
                                <label for="female-rb1">By Milestone</label>
                            </div>
                            <div id="byproject" class="show-hide pl-2" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-6 mt-12">
                                        <label>Amount*</label>
                                        <div class="input-group">
                                            <input class="form-control"
                                            oninput="validateInput(this, {{ showAmount($membershipFee) }}, {{ $job->amount }})"
                                                id="input_bid" type="number" name="bid_price"
                                                style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;"
                                                min="1" max="{{ showAmount($job->amount) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text "
                                                    style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;
												border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">


                                        <label>Platform Fee ( {{ $amntPercent }} %)</label>
                                        <div class="input-group">
                                            <input id="fee" name="cutfix" readonly
                                                value="0" class="form-control"
                                                style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text "
                                                    style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
                                        <label>You Will Get Paid</label>
                                        <div class="input-group">
                                            <input id="total" readonly value="0" class="form-control"
                                                style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text "
                                                    style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <label>Cover Letter*</label>
                                        <textarea name="proposal" class="form-control" cols="10" rows="10" style="border-radius: 5px;"></textarea>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                        <label>Attachment</label><small>(file type: doc, docx, gif, jpeg, jpg, pdf,
                                            png, ppt, word, xls, xlsx, zip)</small>
                                        <input type="file" name="attachment" class="form-control"
                                            style="border: 2px solid #00C535">
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-dm-12 col-12 mt-2 mb-2">
                                        <button type="submit" class="btn rounded text-light   ft-medium"
                                            style="width:100%; background-color:#00C535; padding:15px; ">@lang('Submit Bid')</button>
                                    </div>
                                </div>
                            </div>
                            <div id="bymilestone" class=" show-hide pl-2" style="display: none;">
                                <div class="form-heading">
                                    Bidding Details
                                </div>
                                <div class="row">
                                    <input type="hidden" value="{{ showAmount($job->amount) }}" name="job_price" />
                                    <input type="hidden" value="{{ showAmount($membershipFee) }}"
                                        name="fee" />
                                    <input name="milestone_job" value="{{ $job->id }}" type="hidden" />


                                    <div class="col-md-10 dynamic-field " id="dynamic-field-1">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="field" class="hidden-md">Milestone Title</label>
                                                <input type="text" id="field" placeholder="Milestone Title"
                                                    class="form-control" name="milestone_title[]" />

                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="field" class="hidden-md">Bid Amount</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control bidamountmulti"
                                                            placeholder="$0" min="0" max="{{ $job->maxBudget }}"
                                                            name="milestone_amount[]" id="milestone_amount"
                                                            style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text "
                                                                style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;
												border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Days</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control"
                                                            name="milestone_due_date[]"
                                                            style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text "
                                                                style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;
												border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">Days</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 append-buttons" style="margin-top:1.8rem !important">
                                        <div class="clearfix">
                                            <button type="button" id="add-button"
                                                class="btn rounded theme-bg text-light fs-sm ft-mediume float-left shadow-sm"><i
                                                    class="fa fa-plus fa-fw"></i>
                                            </button>
                                            <button type="button" id="remove-button"
                                                class="btn rounded bg-danger text-light fs-sm ft-mediume float-left shadow-sm ml-1"
                                                disabled="disabled"><i class="fa fa-minus fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
                                        <label>Job Budget</label>
                                        <input type="hidden" value="{{ showAmount($job->maxBudget) }}"
                                            name="job_price" />
                                        <input type="hidden" id=""
                                            value="{{ showAmount($membershipFee) }}" name="fee" />
                                        <div class="input-group">
                                            <input id="job_price" readonly value="{{ showAmount($job->maxBudget) }}"
                                                class="form-control"
                                                style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text "
                                                    style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												    border: 1px solid #ced4da;
												    border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2 mb-2">
                                        <label>Platform Fee ( {{ $amntPercent }} %)</label>
                                        <div class="input-group">
                                            <input id="feemulti" name="cutmile" readonly
                                                value="{{ showAmount($membershipFee) }}" class="form-control"
                                                style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text "
                                                    style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												    border: 1px solid #ced4da;
												    border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4 mt-2">
                                        <label>You Will Get Paid</label>
                                        <div class="input-group">
                                            <input id="totalmulti" readonly value="0" class="form-control"
                                                style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                            <div class="input-group-append">
                                                <span class="input-group-text "
                                                    style="    display: -webkit-box;
													display: -ms-flexbox;
													display: flex;
													-webkit-box-align: center;
													-ms-flex-align: center;
													align-items: center;
													height: 40px !important;
													padding: 6px 15px;
													font-size: 15px;
													margin-bottom: 0;
													font-weight: 400;
													line-height: 1.5;
													color: #495057;
													text-align: center;
													white-space: nowrap;
													background-color: #e9ecef;
												border: 1px solid #ced4da;
												border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">$</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <label>Cover Letter</label>
                                        <textarea name="milestine_proposal" class="form-control" cols="10" rows="10" style="border-radius:5px;"></textarea>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                        <label>Attachment</label><small>(file type: doc, docx, gif, jpeg, jpg, pdf,
                                            png, ppt, word, xls, xlsx, zip)</small>
                                        <input type="file" name="attachment" class="form-control"
                                            style="border: 2px solid #00C535">
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-dm-12 col-12 mt-2 mb-2">
                                        <button type="submit" class="btn rounded text-light   ft-medium"
                                            style="width:100%; background-color:#00C535; padding:15px; ">@lang('Submit Bid')</button>
                                    </div>
                                    <div class="zohaib">

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
            </form>
        </div>

        </div>
        </div>
        </div>
    </section>
    @if (!empty($user))
        @if ($user->is_freelancer == 1)
            <!-- The Bid Modal -->
        @endif
    @endif
@endsection
@section('script')
    <script>
        "use strict";

        function validateInput(input, fee, jobAmount) {
            // Get the entered value
            var enteredValue = input.value;

            // Convert the value to a number
            var numericValue = parseFloat(enteredValue);
            console.log(numericValue);
            console.log(jobAmount);
            // Check if the entered value is negative
            if (numericValue > jobAmount) {
                // If negative, set the value to empty
                input.value = "";
                $('#total').val(0);
                $('#fee').val(0);
            } else if (numericValue < 0) {
                // If negative, set the value to empty
                input.value = "";
                $('#total').val(0);
                $('#fee').val(0);
            } else if (!isValidNumber(numericValue)) {
        // If not a valid number, set the value to empty
        input.value = "";
                $('#total').val(0);
                $('#fee').val(0);
    }
    else {
                // If not negative, call calculatetotal function
                calculatetotal(numericValue, fee);
            }
        }

        // Function to check if a value is a valid number
            function isValidNumber(value) {
                // Use regular expression to check for valid number format
                return /^-?\d*\.?\d+$/.test(value);
            }

        function calculatetotal(amount, share) {
            let job_price = parseFloat($('#job_price').val());
            let fee = parseInt($('#fee').val());
            let total = 0
            let price = parseFloat(amount);
            let per = 0;
            if (price > job_price) {
                $('#err-msg').text('You biding price is greater then job price!');
            } else {
                $('#err-msg').text('');
                per = (job_price * fee) / 100;
                total = price - per;
            }
            if (amount == "") {
                $('#total').val(0);
            } else {
                var totall = $("#input_bid").val();
                console.log(totall)
                $('#total').val(totall - (totall / share));
                $('#fee').val(totall / share);
            }
        }

        function closemodel() {
            $('#Bidmodel').hide();
        }
        $('.jobBidingMore').on('click', function() {
            var link = $(this).data('link');
            var page = $(this).data('page');
            var href = link + page;
            var jobBidingCount = {{ $jobBidings->total() }};
            $.get(href, function(response) {
                var html = $(response).find("#jobBidingShow").html();
                $("#jobBidingShow").append(html);
                var loadMoreCount = 5 * page;
                if (loadMoreCount > jobBidingCount) {
                    $('.jobBidingMore').hide()
                }
            });
            $(this).data('page', (parseInt(page) + 1));
        });
        $(document).ready(function() {
            $('#Bidbtn').click(function() {
                $('#Bidmodel').show();
            })
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[name=projectmethod]").change(function() {
                var test = $(this).val();
                $(".show-hide").hide();
                $("#" + test).show();
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var buttonAdd = $("#add-button");
            var buttonRemove = $("#remove-button");
            var className = ".dynamic-field";
            var count = 0;
            var field = "";
            var maxFields = 50;

            function totalFields() {
                return $(className).length;
            }

            function addNewField() {
                count = totalFields() + 1;
                field = $("#dynamic-field-1").clone();
                field.attr("id", "dynamic-field-" + count);
                field.children("label").text("Field " + count);
                field.find("input").val("");
                $(className + ":last").after($(field));
            }

            function removeLastField() {
                if (totalFields() > 1) {
                    $(className + ":last").remove();
                }
            }

            function enableButtonRemove() {
                if (totalFields() === 2) {
                    buttonRemove.removeAttr("disabled");
                    buttonRemove.addClass("shadow-sm");
                }
            }

            function disableButtonRemove() {
                if (totalFields() === 1) {
                    buttonRemove.attr("disabled", "disabled");
                    buttonRemove.removeClass("shadow-sm");
                }
            }

            function disableButtonAdd() {
                if (totalFields() === maxFields) {
                    buttonAdd.attr("disabled", "disabled");
                    buttonAdd.removeClass("shadow-sm");
                }
            }

            function enableButtonAdd() {
                if (totalFields() === (maxFields - 1)) {
                    buttonAdd.removeAttr("disabled");
                    buttonAdd.addClass("shadow-sm");
                }
            }
            buttonAdd.click(function() {
                addNewField();
                enableButtonRemove();
                disableButtonAdd();
            });
            buttonRemove.click(function() {
                removeLastField();
                disableButtonRemove();
                enableButtonAdd();
            });
        });
    </script>
    <script type="text/javascript">
        function calculatetotal1(amount) {
            let job_price = parseFloat($('#job_price').val());
            let fee = parseInt($('#fee').val());
            let total = 0
            let price = parseFloat(amount);
            let per = 0;
            if (price > job_price) {
                $('#err-msg').text('You biding price is greater then job price!');
            } else {
                $('#err-msg').text('');
                per = (job_price * fee) / 100;
                total = price - per;
            }
            if (amount == "") {
                $('#total').val(0);
            } else {
                var totall = $("#input_bid").val();
                console.log(totall)
                $('#total').val(totall);
            }
        }
    </script>
    <script type="text/javascript">
        $(document).on('input', '.bidamountmulti', function() {
            var total = 0;
            var jobprice = $('#job_price').val();
            var percent = $('#feemulti').val();
            $('.bidamountmulti').each(function(key, value) {
                var $this = $(this);
                var thisval = $this.val();
                console.log(thisval);
                total += parseFloat(thisval);
                console.log(total);
            });

            var points = total - (total / percent);
            console.log(points);
            $('#totalmulti').val(points);
        });
    </script>
@endsection
