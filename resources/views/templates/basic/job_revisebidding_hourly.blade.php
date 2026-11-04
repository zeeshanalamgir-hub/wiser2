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
</style>@extends($activeTemplate . 'layouts.wiserlance.layout')
@section('content')
    @php
        $helpers = new \App\Classes\Helpers();
        $currency_rates = $helpers->get_currency_rates();
        $currencies = $helpers->get_all_currencies();
        // echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
    @endphp
    <section class="bg-light py-5 position-relative">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="sor-card-7668">
                        <div class="bar-heading-678975">
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
                                                        {{ "$" . $job->maxBudget }} /
                                                        hour</span>
                                                @else
                                                    <span class="newdesign"> {{ $job->minBudget . "$" }} -
                                                        {{ $job->maxBudget . "$" }}
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

                        <div class="bar-heading-678975">
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
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <form enctype="multipart/form-data" method="post" action="{{ url('/job/addbidinghourly') }}">
                        @csrf
                        <input name="cutfix" type="hidden" value="{{ showAmount($usermembership[0]->fee) }}"
                            class="form-control">
                        <div class="bg-white rounded mb-4">
                            <div class="jbd-01 px-3 py-4">
                                <div class="form-heading">
                                    <div class="bar-title-678975 ntn-poppins-bold">Bidding Details</div>
                                </div>
                                <div class="jbd-details mb-4 mt-4">
                                    <h6 class=" fs-md" style="font-weight: 600">@lang('Hourly Bidding Information')</h6>
                                    <div class="row pl-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                            <label>Hourly Bid Amount</label>
                                            <div class="input-group">
                                                <input class="form-control" id="hourlyBidAmount" type="number"
                                                    min="1" max="{{ $job->maxBudget }}"
                                                    oninput="validity.valid||(value='');"
                                                    style="border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;">
                                                <input type="hidden" name="bid_price" id="calculatedValue" min="1">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"
                                                        style="display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-align: center; -ms-flex-align: center;  align-items: center; height: 40px !important; padding: 6px 15px; font-size: 15px;  margin-bottom: 0; font-weight: 400; line-height: 1.5; color: #495057; text-align: center; white-space: nowrap; background-color: #e9ecef; border: 1px solid #ced4da; border-top-right-radius: 5px !important; border-bottom-right-radius: 5px !important; ">
                                                        $/hr
                                                    </span>
                                                </div>
                                            </div>
                                            <input name="job_id" value="{{ $job->id }}" type="hidden" />
                                            <input name="projectmethod" value="hourly" type="hidden" />
                                            <div id="error-message" style="color: red;"></div>
                                        </div>
                                        <div class="row col-sm-12 mt-4">
                                            <div class="col-sm-6">
                                                @php
                                                $bidAmount = 100; // Replace this with the actual bid amount
                                                $membershipFeePercentage = $usermembership[0]->fee; // Get the membership fee percentage
                                                $amountAfterMembershipDeduction =
                                                $bidAmount - $bidAmount * ($membershipFeePercentage / 100);
                                                @endphp

                                                <label style="font-weight: 500; font-size: large; color: black">
                                                    Fee: {{ $usermembership[0]->fee }}%
                                                </label>
                                            </div>
                                            <div style="margin-left: 120px">
                                                <label id="paidAmountLabel" style="font-weight: 500;font-size: large;color:black">You'll be
                                                    paid: $<span id="paidAmount"
                                                        style=" font-weight:lighter;margin-left: 5">0</span></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                            <label>Attachment</label> <small>(file type: doc, docx, gif, jpeg, jpg, pdf,
                                                png, ppt, word, xls, xlsx, zip)</small>
                                            <input type="file" name="file" class="form-control"
                                                style="border: 2px solid #00C535">
                                        </div>
                                        <div class="col-lg-12 mt-2">
                                            <label>Cover Letter</label>
                                            <textarea name="proposal" class="form-control nicEdit" cols="10" rows="10"></textarea>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-dm-12 col-12 mt-2">
                                            <button type="submit" class="btn rounded text-light  ft-medium"
                                                style="width:100%; background-color:#00C535; padding:15px; ">@lang('Revise Bid')</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </form>
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
    document.getElementById('hourlyBidAmount').addEventListener('input', function () {
        var bidAmount = parseFloat(document.getElementById('hourlyBidAmount').value);
        var membershipFeePercentage = parseFloat({{ $usermembership[0]->fee }});
        var amountAfterMembershipDeduction = bidAmount - (bidAmount * (membershipFeePercentage / 100));

        document.getElementById('paidAmount').innerText = amountAfterMembershipDeduction.toFixed(2);
    });
</script>
    <script>
        "use strict";

        function calculatetotal(amount) {
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
                $('#total').val(total);
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

    <script>
        $(document).ready(function() {
            var maxbudget = {{ $job->maxBudget }};

            $('#hourlyBidAmount').on('input', function() {
                var amount = parseFloat($(this).val());
                if (isNaN(amount)) {
                    $('#error-message').text(
                    'Error: The bid should be entered within the parameters given');
                } else if (amount > maxbudget) {
                    $('#error-message').text('Error: Amount exceeds maximum budget');
                } else {
                    $('#error-message').text('');
                }
                // Update the value of the hidden input
                $('#calculatedValue').val(amount);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hourlyBidAmountInput = document.getElementById('hourlyBidAmount');
            const milestonePayAmtSpan = document.getElementById('milestone_pay_amt');
            const calculatedValueInput = document.getElementById('calculatedValue'); // Get the hidden input

            hourlyBidAmountInput.addEventListener('input', function() {
                let bidAmount = parseFloat(hourlyBidAmountInput.value);
                if (isNaN(bidAmount)) {
                    milestonePayAmtSpan.textContent = '0.00'; // Display 0 if NaN
                    calculatedValueInput.value = ''; // Clear the hidden input if NaN
                } else {
                    const amountAfterDeduction = bidAmount - (bidAmount * 0.20); // Deduct 20%
                    milestonePayAmtSpan.textContent = amountAfterDeduction.toFixed(2); // Update display
                    calculatedValueInput.value = amountAfterDeduction.toFixed(2); // Update the hidden input
                }
            });
        });
    </script>
    @if (!empty($user))
        @if ($user->is_freelancer == 1)
            <!-- The Bid Modal -->
        @endif
    @endif
@endsection
@section('script')
    <script>
        "use strict";

        function calculatetotal(amount) {
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
                $('#total').val(total);
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
    <script>
        document.getElementById('hourlyBidAmount').addEventListener('keyup', function() {
            var amount = parseFloat(this.value);
            var errorMessage = document.getElementById('error-message');
            if (isNaN(amount) || amount < 0) {
                errorMessage.textContent = 'Amount cannot be negative';
            } else {
                errorMessage.textContent = '';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // API call code here
            fetch('https://example.com/api/data')
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Handle API response data
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>
@endsection
