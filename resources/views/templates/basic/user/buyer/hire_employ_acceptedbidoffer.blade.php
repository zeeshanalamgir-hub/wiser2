<style>
    .header-top-button {
        border: 2px solid #003F45;
        padding: 10px 30px 10px 30px;
        font-weight: 700;
        color: #003F45;
    }

    .header-top-button-active {
        background-color: #00C535;
        padding: 10px 30px 10px 30px;
        font-weight: 700;
        color: white;
    }
</style>

@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Accept Proposal')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.proposal.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to approved this work delivery')
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Cancel Proposal')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.proposal.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="confirm" value="cancel">
                        <p>@lang('Are you sure to cancel this Proposal')</p>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-content">
        <div class="container-header-table-100095">

                @include('templates.basic.user.buyer.components.buyer-hire-employees-timesheet-top-button')

            @if (@$acceptedbid)
                @php
                    $job = DB::table('jobs')
                        ->where('id', $accepteddata->job_id)
                        ->first();
                    $seller = DB::table('users')
                        ->where('id', $accepteddata->seller_id)
                        ->first();
                @endphp
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                            <tbody>
                                <tr>
                                    <a class="float-right mb-2" style="text-decoration: underline;"
                                        href="{{ url('generate-invoice-pdf', $singlejob->id) }}">Download Invoice</a>
                                </tr>

                                <tr>
                                    <th style="width:20%;" scope="row">Title:
                                        <!-- <img src80https://beta.bizlanceguru.com/wiselancer/freelance%20dshboard/assets/img/about-1.png" class="img-fluid" style="width:70%;" alt=""> -->
                                    </th>
                                    <td style="width:85%;"><a href="#">{{ $job->title }}</a></td>


                                </tr>

                                @if ($job->budgettype == 'per_hour')
                                    @php
                                        @$counteroffer = DB::table('buyer_counter_offers')
                                            ->where('bid_id', $accepteddata->bid_id)
                                            ->first();
                                        @$counterofferdata = DB::table('buyer_counter_offers_data')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('bid_id', $accepteddata->bid_id)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <th style="width:20%;" scope="row">Job Budget:</th>
                                        <td style="width:80%;text-align: right;">
                                            {{ $general->cur_sym }}{{ showAmount($job->amount) }} /hour</td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Accepted amount:</th>
                                        <td style="width:80%;text-align: right;">
                                            {{ $general->cur_sym }}{{ showAmount(@$counterofferdata->amount) }} /hour</td>

                                    </tr>
                                @else
                                    <tr>
                                        <th style="width:20%;" scope="row">Job Budget:</th>
                                        <td style="width:80%;text-align: right;">
                                            {{ $general->cur_sym }}{{ showAmount($job->amount) }}</td>

                                    </tr>
                                    @php
                                        @$counteroffer = DB::table('buyer_counter_offers')
                                            ->where('bid_id', $accepteddata->bid_id)
                                            ->first();
                                        @$counterofferdata = DB::table('buyer_counter_offers_data')
                                            ->where('disputed_amount', '0')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('bid_id', $accepteddata->bid_id)
                                            ->get();

                                        $payed = DB::table('buyer_counter_offers_data')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('bid_id', $accepteddata->bid_id)
                                            ->where('status', '5')
                                            ->where('disputed_amount', '0')
                                            ->get();
                                        $extra_cut = DB::table('buyer_counter_offers_data')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('bid_id', $accepteddata->bid_id)
                                            ->where('status', '5')
                                            ->where('disputed_amount', '!=', '0')
                                            ->get();
                                        @$dispute_pay = @$extra_cut->sum('disputed_amount');
                                        $rem = @$counterofferdata->sum('amount') - $payed->sum('amount');
                                        $counterofferdataorg = DB::table('buyer_counter_offers_data')
                                            ->where('counter_offer_id', $counteroffer->id)
                                            ->where('bid_id', $accepteddata->bid_id)
                                            ->get();
                                        $Jobname = DB::table('jobs')
                                            ->where('id', $accepteddata->job_id)
                                            ->first();
                                    @endphp
                                    @foreach ($counterofferdataorg as $cd)
                                        <tr>
                                            <th style="width:20%;" scope="row">
                                                @if ($counteroffer->type == 'bymilestone')
                                                    {{ $cd->title }}
                                                @elseif($counteroffer->type == 'byproject')
                                                    {{ $Jobname->title }}
                                                @endif:
                                            </th>

                                            <td style="width:80%;text-align: right;">$ {{ $cd->amount }}</td>


                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th style="width:20%;" scope="row">Pending Payment:</th>
                                        <td style="width:80%;text-align: right;">$ {{ @$counterofferdata->sum('amount') }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Paid:</th>
                                        <td style="width:80%;text-align: right;">$ {{ $payed->sum('amount') }}</td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Disputes Paid:</th>
                                        <td style="width:80%;text-align: right;">$ {{ $dispute_pay }}</td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Remaining:</th>
                                        <td style="width:80%;text-align: right;">$ {{ $rem }}</td>

                                    </tr>
                                @endif
                                <tr>
                                    <th style="width:20%;" scope="row">Order Number:</th>
                                    <td style="width:80%;text-align: right;">{{ $acceptedbid->order_number }}

                                    </td>

                                </tr>
                                <tr>
                                    <th style="width:20%;" scope="row">Seller:</th>
                                    <td style="width:80%;text-align: right;">{{ $seller->username }}:
                                        <a
                                            href="{{ route('profile', [slug($seller->username), encrypt($seller->id)]) }}"><span>@</span>{{ $seller->username }}</a>
                                    </td>

                                </tr>
                                <tr>
                                    <th style="width:20%;" scope="row">Start Date:</th>
                                    <td style="width:80%;text-align: right;">
                                        {{ showDateTime($accepteddata->created_at, 'd M Y') }}</td>

                                </tr>
                                <!-- <tr>
                                        <th style="width:20%;" scope="row">Delivery Days:</th>
                                        <td style="width:80%;text-align: right;">04 January 2023</td>

                                    </tr> -->

                                <!-- <tr>
                                        <th style="width:20%;" scope="row">Working Status :</th>
                                        <td style="width:80%;text-align: right;">
                                        <span class="badge badge-primary">@lang('Pending')</span>
                                        </td>

                                    </tr> -->
                                <tr>
                                    <th style="width:20%;" scope="row">Status :</th>
                                    <td style="width:80%;text-align: right;">
                                        @if ($job->is_completed == '1')
                                            <span class="badge badge-success">@lang('Completed')</span>
                                        @else
                                            <span class="badge badge-primary">@lang('In Progress')</span>
                                        @endif
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if (@$acceptedoffer)
                @php
                    $job = DB::table('jobs')
                        ->where('id', $accepteddata->job_id)
                        ->first();
                    $seller = DB::table('users')
                        ->where('id', $accepteddata->seller_id)
                        ->first();

                @endphp
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                            <tbody>
                                <tr>
                                    <a class="float-right mb-2" style="text-decoration: underline;"
                                        href="{{ url('generate-invoice-pdf-offer', $singlejob->id) }}">Download
                                        Invoice</a>
                                </tr>
                                <tr>
                                    <th style="width:20%;" scope="row">Title:
                                        <!-- <img src80https://beta.bizlanceguru.com/wiselancer/freelance%20dshboard/assets/img/about-1.png" class="img-fluid" style="width:70%;" alt=""> -->
                                    </th>
                                    <td style="width:85%;"><a href="#">{{ $job->title }}</a></td>

                                </tr>

                                @if ($job->budgettype == 'per_hour')
                                    @php
                                        @$counteroffer = DB::table('seller_counter_offers')
                                            ->where('offer_id', $accepteddata->offer_id)
                                            ->first();
                                        @$counterofferdata = DB::table('seller_counter_offers_data')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('offer_id', $accepteddata->offer_id)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <th style="width:20%;" scope="row">Job Budget:</th>
                                        <td style="width:80%;text-align: right;">
                                            {{ $general->cur_sym }}{{ showAmount($job->amount) }} /hour</td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Accepted amount:</th>
                                        <td style="width:80%;text-align: right;">
                                            {{ $general->cur_sym }}{{ showAmount(@$counterofferdata->amount) }} /hour</td>

                                    </tr>
                                @else
                                    <tr>
                                        <th style="width:20%;" scope="row">Job Budget:</th>
                                        <td style="width:80%;text-align: right;">
                                            {{ $general->cur_sym }}{{ showAmount($job->amount) }}</td>

                                    </tr>

                                    @php
                                        @$counteroffer = DB::table('seller_counter_offers')
                                            ->where('offer_id', $accepteddata->offer_id)
                                            ->first();
                                        @$counterofferdata = DB::table('seller_counter_offers_data')
                                            ->where('disputed_amount', '0')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('offer_id', $accepteddata->offer_id)
                                            ->get();

                                        $payed = DB::table('seller_counter_offers_data')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('offer_id', $accepteddata->offer_id)
                                            ->where('disputed_amount', '0')
                                            ->where('status', '5')
                                            ->get();

                                        $extra_cut = DB::table('seller_counter_offers_data')
                                            ->where('counter_offer_id', @$counteroffer->id)
                                            ->where('offer_id', $accepteddata->offer_id)
                                            ->where('disputed_amount', '!=', '0')
                                            ->where('status', '5')
                                            ->get();
                                        @$dispute_pay = @$extra_cut->sum('disputed_amount');
                                        $rem = @$counterofferdata->sum('amount') - $payed->sum('amount');
                                        $counterofferdataorg = DB::table('seller_counter_offers_data')
                                            ->where('counter_offer_id', $counteroffer->id)
                                            ->where('offer_id', $accepteddata->offer_id)
                                            ->get();
                                        $Jobname = DB::table('jobs')
                                            ->where('id', $accepteddata->job_id)
                                            ->first();
                                    @endphp
                                    @foreach ($counterofferdataorg as $cd)
                                        <tr>
                                            <th style="width:20%;" scope="row">
                                                @if ($counteroffer->type == 'bymilestone')
                                                    {{ $cd->title }}
                                                @elseif($counteroffer->type == 'byproject')
                                                    {{ $Jobname->title }}
                                                @endif:
                                            </th>

                                            <td style="width:80%;text-align: right;">$ {{ $cd->amount }}</td>


                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th style="width:20%;" scope="row">Pending Payment:</th>
                                        <td style="width:80%;text-align: right;">$ {{ @$counterofferdata->sum('amount') }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Paid:</th>
                                        <td style="width:80%;text-align: right;">$ {{ $payed->sum('amount') }}</td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Disputes Paid:</th>
                                        <td style="width:80%;text-align: right;">$ {{ $dispute_pay }}</td>

                                    </tr>
                                    <tr>
                                        <th style="width:20%;" scope="row">Remaining:</th>
                                        <td style="width:80%;text-align: right;">$ {{ $rem }}</td>

                                    </tr>
                                @endif
                                <tr>
                                    <th style="width:20%;" scope="row">Order Number:</th>
                                    <td style="width:80%;text-align: right;">{{ $acceptedoffer->order_number }}

                                    </td>

                                </tr>
                                <tr>
                                    <th style="width:20%;" scope="row">Seller:</th>
                                    <td style="width:80%;text-align: right;">{{ $seller->username }}:
                                        <a
                                            href="{{ route('profile', [slug($seller->username), encrypt($seller->id)]) }}"><span>@</span>{{ $seller->username }}</a>
                                    </td>

                                </tr>
                                <tr>
                                    <th style="width:20%;" scope="row">Start Date:</th>
                                    <td style="width:80%;text-align: right;">
                                        {{ showDateTime(@$accepteddata->created_at, 'd M Y') }}</td>

                                </tr>
                                <!-- <tr>
                                        <th style="width:20%;" scope="row">Delivery Days:</th>
                                        <td style="width:80%;text-align: right;">04 January 2023</td>

                                    </tr> -->

                                <!-- <tr>
                                        <th style="width:20%;" scope="row">Working Status :</th>
                                        <td style="width:80%;text-align: right;">
                                        </td>

                                    </tr> -->
                                <tr>
                                    <th style="width:20%;" scope="row">Status :</th>
                                    <td style="width:80%;text-align: right;">
                                        @if ($job->is_completed == '1')
                                            <span class="badge badge-success">@lang('Completed')</span>
                                        @else
                                            <span class="badge badge-primary">@lang('Ongoing')</span>
                                        @endif
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif


        </div>
    @endsection
    @section('script')
        <script type="text/javascript">
            "use strict";
            $('.approvedBtn').on('click', function() {
                var modal = $('#approvedModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
            $('.cancelBtn').on('click', function() {
                var modal = $('#cancelModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
        </script>
    @endsection
