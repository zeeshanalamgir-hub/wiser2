@extends('templates.basic.layouts.wiserlance.dashboard-layout',  [
    'scripts' => [
        'assets/templates/basic/scripts/js/data-ajax.js',
        'assets/templates/basic/scripts/js/user/seller/job_vacancy_bids.js',
    ],
    'styles' => [
        'assets/templates/basic/styles/css/racket-tables.css',
        'assets/templates/basic/styles/css/user/seller/job_vacancy_bids.css'
    ]

])
@section('content')
    <!-- offer Cancel Modal -->
    <div class="modal fade" id="cancelofferModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h4 class="m-0 ft-regular">@lang('Cancel Offer')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.job.vacancy.bids.declineresponce') }}">
                        @csrf

                        <input type="hidden" name="cancelresponce_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to decline this offer ?')<h6>
                                <div class="form-group">
                                    <input type="text" name="reason" class="form-control" placeholder="Decline Reason"
                                        required>
                                </div>
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
    <!-- offer  Cancel Modal -->
    <!-- offer accept Modal -->
    <div class="modal fade" id="acceptofferModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h4 class="m-0 ft-regular">@lang('Accept Offer')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.job.vacancy.bids.acceptresponce') }}">
                        @csrf
                        <input type="hidden" name="acceptresponce_id">
                        <input type="hidden" name="job_id">
                        <input type="hidden" name="seller_id">
                        <input type="hidden" name="buyer_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to accept this offer ?')<h6>
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
    <!-- offer accpet Cancel Modal -->
    <!-- cancel bid Modal -->
    <div class="modal fade" id="cancelbidModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h4 class="m-0 ft-regular">@lang('Cancel Bid')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.job.vacancy.bids.sellercancelbid') }}">
                        @csrf
                        <input type="hidden" name="cancelbid_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to cancel your bid ?')<h6>
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
    <!-- cancel bid Modal -->
    <div class="modal fade" id="milestoneModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body py-4 py-1">
                    <div class="text-center mb-4">
                        <h6 class="m-0 ft-regular">@lang('Your Bid')</h6>
                    </div>

                    <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                        <tbody>



                            <tr>
                                <td style="width: 100px" style="width:33%;" scope="row">Milestone 1</td>
                                <td style="width: 100px" style="width:33%;" scope="row">04 January 2023</td>
                                <td style="width: 100px" style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width: 100px" style="width:33%;" scope="row">Milestone 2</td>
                                <td style="width: 100px" style="width:33%;" scope="row">14 January 2023</td>
                                <td style="width: 100px" style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width: 100px" style="width:33%;" scope="row">Milestone 3</td>
                                <td style="width: 100px" style="width:33%;" scope="row">24 January 2023</td>
                                <td style="width: 100px" style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <p><span class="font-weight-bold">Cover Letter:</span>Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum
                                    dui nisl, vestibulum in lectus a, lobortis elementum velit. <a href=""><i
                                            class="fa fa-paperclip"></i></a> </p>
                            </tr>

                        </tbody>
                    </table>
                    <hr>
                    <div class="text-center mb-4">
                        <h6 class="m-0 ft-regular">@lang('Bid Responce')</h6>
                    </div>

                    <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                        <tbody>



                            <tr>
                                <td style="width: 100px" style="width:33%;" scope="row">Milestone 1</td>
                                <td style="width: 100px" style="width:33%;" scope="row">04 January 2023</td>
                                <td style="width: 100px" style="width:34%;text-align: right;">$ 70.00</td>

                            </tr>
                            <tr>
                                <td style="width: 100px" style="width:33%;" scope="row">Milestone 2</td>
                                <td style="width: 100px" style="width:33%;" scope="row">14 January 2023</td>
                                <td style="width: 100px" style="width:34%;text-align: right;">$ 70.00</td>

                            </tr>
                            <tr>
                                <td style="width: 100px" style="width:33%;" scope="row">Milestone 3</td>
                                <td style="width: 100px" style="width:33%;" scope="row">24 January 2023</td>
                                <td style="width: 100px" style="width:34%;text-align: right;">$ 70.00</td>

                            </tr>
                            <tr>
                                <p><span class="font-weight-bold">Cover Letter:</span>Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum
                                    dui nisl, vestibulum in lectus a, lobortis elementum velit. <a href=""><i
                                            class="fa fa-paperclip"></i></a> </p>
                            </tr>

                        </tbody>
                    </table>
                    <div class="col-lg-12 float-right">
                        <a href="#" class="btn rounded theme-bg text-light fs-sm ft-medium ">Accept</a>
                        <a href="#" class="btn rounded bg-danger text-light fs-sm ft-medium ">Decline</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Work Delivery')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('user.jobwork.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="jobfile">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Upload Work File')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file" required="">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Delivery Details ...."
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success"
                                style="width:100%;">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-rounded text-white"
                        data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-content">
        <div class="container-header-table-100095">
            <div class="header-top-buttons-10065">

                <a href="{{ route('user.job.vacancy.bids', encrypt($singlejob->id)) }}"
                    class="  btn rounded sd-button-header-10068">Bid Detail</a>
                @php

                    $accepted1 = DB::table('user_biding')
                        ->where('job_id', $singlejob->id)
                        ->where('seller_offer_responce', '2')
                        ->first();
                    $accepted2 = DB::table('user_offers')
                        ->where('job_id', $singlejob->id)
                        ->where('buyer_offer_responce', '2')
                        ->first();
                @endphp
            </div>

            @php
                @$usermembership = DB::table('freelancer_membership_transactions')
                    ->join(
                        'freelancer_membership',
                        'freelancer_membership_transactions.membership_id',
                        '=',
                        'freelancer_membership.id',
                    )
                    ->where('user_id', $bisdetail->user_id)
                    ->get();
            @endphp

            @php
                $title = DB::table('jobs')
                    ->where('id', $singlejob->id)
                    ->first();
            @endphp

            <div class="table-container-985632">
                <div class="table-heading-10002">
                    <div class="table-heding-top-78563"
                        href="{{ route('job.details', [slug($title->title), encrypt($title->id)]) }}">JOB TITLE:
                        {{ $title->title }}</div>
                </div>
                <table class="table row-border">
                    <thead class="table-head-10004">
                        <tr>
                            <!-- <th scope="col" class="text-left">@lang('Job Title')</th> -->
                            <th scope="col" class="table-head-col-10005">@lang('Type')</th>
                            <th scope="col" class="table-head-col-10005">@lang('Buyer')</th>
                            <th scope="col" class="table-head-col-10005">@lang('Submitted on')</th>
                            <th scope="col" class="table-head-col-10005">@lang('Budget')</th>
                            <th scope="col" class="table-head-col-10005">@lang('Bid amount')</th>
                            <th scope="col" class="table-head-col-10005">Status</th>
                            <th scope="col" class="table-head-col-10005">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($bisdetailcount > 0)
                            <tr>
                                @if (@$bisdetail->job->budgettype == 'fixed')
                                    <td style="width: 130px" class="text-left">Fixed</td>
                                @elseif(@$bisdetail->job->budgettype == 'per_hour')
                                    <td style="width: 130px" class="text-left">Per Hour</td>
                                @endif
                                @php
                                    $buyer = DB::table('users')
                                        ->where('id', @$bisdetail->job->user_id)
                                        ->first();
                                @endphp
                                <td style="width: 130px" class="text-left">{{ @$buyer->username }}<br>

                                </td>
                                <td style="width: 130px" class="text-left">
                                    {{ showDateTime(@$bisdetail->created_date, 'd M Y') }}
                                </td>
                                @if (@$bisdetail->job->budgettype == 'fixed')
                                    <td style="width: 120px" class="text-left">{{ $general->cur_sym }}
                                        {{ $bisdetail->job->amount }}</td>
                                @elseif(@$bisdetail->job->budgettype == 'per_hour')
                                    <td style="width: 120px" class="text-left">{{ $general->cur_sym }}
                                        {{ $bisdetail->job->minBudget . ' - ' . $bisdetail->job->maxBudget }}/hour
                                    </td>
                                @endif
                                @if (@$bisdetail->job->budgettype == 'fixed')
                                    @if ($bisdetail->job_type == 'byproject')
                                        <td style="width: 130px" class="text-left">${{ $bisdetail->bid_price }}</td>
                                    @elseif($bisdetail->job_type == 'bymilestone')
                                        @php
                                            $milestones = DB::table('user_milestone')
                                                ->where('bid_id', $bisdetail->id)
                                                ->get();
                                            $sum = $milestones->sum('amount');
                                        @endphp
                                        <td style="width: 130px" class="text-left">${{ $sum }}</td>
                                    @endif
                                @elseif(@$bisdetail->job->budgettype == 'per_hour')
                                    <td style="width: 100px" class="text-left">${{ @$bisdetail->bid_price }}/hour</td>
                                @endif
                                @if ($accepted1 && @$accepted1->id != $bisdetail->id)
                                    <td style="width: 100px"><span class="">@lang('Bidding Closed')</span></td>
                                @else
                                    @if (@$bisdetail->is_cancel_bid == '1')
                                        <td style="width: 100px"><span class="">@lang('Declined')</span></td>
                                    @elseif(@$bisdetail->is_buyer_offer_sent == '1')
                                        @if (@$bisdetail->seller_offer_responce == '-1')
                                            <td style="width: 100px"><span class="">@lang('Offered')</span>
                                            </td>
                                        @elseif(@$bisdetail->seller_offer_responce == '1')
                                            <td style="width: 100px"><span class="">@lang('Cancelled')</span></td>
                                        @elseif(@$bisdetail->seller_offer_responce == '2')
                                            <td style="width: 100px"><span class="">@lang('Accepted')</span>
                                            </td>
                                        @endif
                                    @else
                                        <td style="width: 100px"><span class="badge badge-info">@lang('Bid Rejected')</span>
                                        </td>
                                    @endif
                                @endif
                                <td style="width: 100px">
                                    <div class="dash-action-98564">
                                        <a href="javascript:void(0);" title="View Detail"
                                            data-bid_id="{{ @$bisdetail->id }}"
                                            style="height:30px;width:30px;border-radius: 50%;"
                                            class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1">
                                            <i style="color: #00C535" class="fa fa-eye"></i>
                                        </a>
                                        @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                                            @if ($accepted1)
                                                @if (@$accepted1->id == $bisdetail->id)
                                                    <!-- <a href="javascript:void(0);" title="View Detail" data-bid_id="{{ @$bisdetail->id }}" style="height:30px;width:30px;border-radius: 50%;" class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="fa fa-eye"></i></a> -->
                                                    @if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                                                        <a title="View Timesheet"
                                                            href="{{ route('user.job.vacancy.hourlytimesheet', encrypt($singlejob->id)) }}"
                                                            class="btn circle theme-bg-light theme-cl fs-sm ft-medium">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <a title="View Workspace"
                                                            href="{{ route('user.job.vacancy.workspace', encrypt($singlejob->id)) }}"
                                                            class="btn circle theme-bg-light theme-cl fs-sm ft-medium">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            <!-- <a href="javascript:void(0);" title="View Detail" data-bid_id="{{ @$bisdetail->id }}" style="height:30px;width:30px;border-radius: 50%;" class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="fa fa-info"></i></a> -->

                                            @if (
                                                @$bisdetail->seller_offer_responce == '1' ||
                                                    @$bisdetail->seller_offer_responce == '2' ||
                                                    @$bisdetail->is_cancel_bid == '1')
                                            @else
                                                <a href="javascript:void(0);" title="Cancelbid"
                                                    data-cancelbid_id="{{ @$bisdetail->id }}" data-toggle="modal"
                                                    data-target="#cancelbidModal"
                                                    class="cancelbidBtn p-2 circle text-danger  d-inline-flex align-items-center justify-content-center ml-1"><i
                                                        class="lni lni-trash-can"></i></a>
                                            @endif
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @else
                            <tr>
                                <td style="width: 100px">no data found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td colspan="10" style="border-color: transparent !important;">
                                <div class=" showresponce " style="display:none;" id="target{{ @$bisdetail->id }}">

                                    <div class="form-heading" style="margin-bottom:10px">
                                        Your Offer
                                    </div>
                                    <table class="table table-bordered"
                                        style="border: 1px solid rgba(0,0,0,.125) !important;margin-bottom: 20px; ">

                                        <tbody>
                                            @if (@$bisdetail->is_cancel_bid == '1')
                                                <tr>
                                                    <p class="text-danger">Cancel Reason:
                                                        {{ $bisdetail->reason }}</p>
                                                </tr>
                                            @endif
                                            @if (@$bisdetail->job_type == 'hourly')
                                                <tr>
                                                    <!-- <td style="width: 100px" style="width:50%;" scope="row">Title</td> -->
                                                    <td style="width: 100px" style="width:100%;text-align: left;">
                                                        Amount
                                                    </td>

                                                </tr>
                                                <tr>

                                                    <td style="width: 100px" style="width:100%;text-align: left;">$
                                                        {{ @$bisdetail->bid_price }}/hour</td>

                                                </tr>
                                            @elseif(@$bisdetail->job_type == 'byproject')
                                                <tr>

                                                    <td style="width: 100px" style="width:50%;" scope="row">Days
                                                    </td>
                                                    <td style="width: 100px" style="width:50%;text-align: right;">
                                                        Amount
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td style="width: 100px" style="width:50%;" scope="row">
                                                        {{ @$bisdetail->job->delivery_time }}</td>
                                                    <td style="width: 100px" style="width:50%;text-align: right;">$
                                                        {{ @$bisdetail->bid_price }}</td>

                                                </tr>
                                                @php

                                                    $per = ($bisdetail->bid_price * $usermembership[0]->fee) / 100;
                                                    $total = $bisdetail->bid_price - $per;
                                                    // showAmount(@$bisdetail->job->amount) - $per;
                                                @endphp
                                                <tr>
                                                    <td style="width: 100px" colspan="3" style="width:1000%;">
                                                        Platform Fee:
                                                        <span style="float: right;">
                                                            ${{ showAmount($usermembership[0]->fee) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 100px" colspan="3" style="width:100%;">
                                                        You Will Get Payed:
                                                        <span style="float: right;">
                                                            ${{ $total }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @elseif(@$bisdetail->job_type == 'bymilestone')
                                                @php
                                                    $milestones = DB::table('user_milestone')
                                                        ->where('bid_id', @$bisdetail->id)
                                                        ->get();

                                                @endphp
                                                <tr>
                                                    <td style="width: 100px" style="width:33%;" scope="row">Title
                                                    </td>
                                                    <td style="width: 100px" style="width:33%;" scope="row">Days
                                                    </td>
                                                    <td style="width: 100px" style="width:34%;text-align: right;">
                                                        Amount
                                                    </td>
                                                </tr>
                                                @foreach ($milestones as $md)
                                                    <tr>
                                                        <td style="width: 100px" style="width:33%;" scope="row">
                                                            {{ $md->title }}
                                                        </td>
                                                        <td style="width: 100px" style="width:33%;" scope="row">
                                                            {{ $md->due_date }}
                                                        </td>
                                                        <td style="width: 100px" style="width:34%;text-align: right;">
                                                            $
                                                            {{ $md->amount }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @php
                                                    $sum = $milestones->sum('amount');
                                                    $per = ($sum * $usermembership[0]->fee) / 100;
                                                    $total = $sum - $per;
                                                    // showAmount(@$bisdetail->job->amount) - $per;
                                                @endphp
                                                <tr>
                                                    <td style="width: 100px" colspan="3" style="width:1000%;">
                                                        Platform Fee:
                                                        <span style="float: right;">
                                                            ${{ showAmount($usermembership[0]->fee) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 100px" colspan="3" style="width:100%;">
                                                        You Will Get Payed:

                                                        <span style="float: right;">
                                                            ${{ $total }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <p>
                                                    <span class="font-weight-bold">
                                                        Cover Letter:
                                                    </span>
                                                    {{ @$bisdetail->proposal }}
                                                    @if (@$bisdetail->file == '')
                                                    @else
                                                        <a title="Download attachment"
                                                            href="{{ route('user.bid.sent.file.download', encrypt(@$bisdetail->id)) }}">
                                                            <i class="fa fa-paperclip"></i>
                                                        </a>
                                                    @endif
                                                </p>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @php
                                        $counteroffer = DB::table('buyer_counter_offers')
                                            ->where('bid_id', @$bisdetail->id)
                                            ->first();
                                    @endphp
                                    @if ($counteroffer)

                                        <div class="form-heading" style="margin-bottom:10px">
                                            Counter Offer:
                                        </div>
                                        <table class="table table-bordered"
                                            style="width: 100%;border: 1px solid rgba(0,0,0,.125) !important;">

                                            <tbody>

                                                @if ($counteroffer->type == 'hourly')
                                                    @php
                                                        $counterdata = DB::table('buyer_counter_offers_data')
                                                            ->where('bid_id', $bisdetail->id)
                                                            ->first();
                                                    @endphp
                                                    <tr>

                                                        <td style="width: 100px" style="width:50%;text-align: right;">
                                                            Amount
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td style="width: 100px" style="width:50%;" scope="row">
                                                            {{ $counterdata->title }}</td>
                                                        <td style="width: 100px" style="width:50%;text-align: right;">
                                                            $
                                                            {{ $counterdata->amount }}/hour</td>

                                                    </tr>
                                                    @php
                                                        $per = ($counterdata->amount * $usermembership[0]->fee) / 100;
                                                        $total = showAmount(@$bisdetail->job->amount) - $per;
                                                    @endphp
                                                    <tr>
                                                        <td style="width: 100px" style="width:50%;" scope="row">
                                                            Platform
                                                            Fee %</td>
                                                        <td style="width: 100px" style="width:50%;text-align: right;">
                                                            ${{ showAmount($usermembership[0]->fee) }}
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td style="width: 100px" style="width:50%;" scope="row">
                                                            You will
                                                            get </td>
                                                        <td style="width: 100px" style="width:50%;text-align: right;">
                                                            $
                                                            {{ $counterdata->amount * (1 - showAmount($usermembership[0]->fee) / 100) }}/hour
                                                        </td>

                                                    </tr>
                                                @elseif($counteroffer->type == 'byproject')
                                                    @php
                                                        $counterdata = DB::table('buyer_counter_offers_data')
                                                            ->where('bid_id', $bisdetail->id)
                                                            ->first();
                                                    @endphp
                                                    <tr>
                                                        {{-- <td style="width: 100px" style="width:33%;" scope="row">Title
                                                        </td> --}}
                                                        <td style="width: 100px" style="width:33%;" scope="row">
                                                            Days
                                                        </td>
                                                        <td style="width: 100px" style="width:34%;text-align: right;">
                                                            Amount
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        {{-- <td style="width: 100px" style="width:33%;" scope="row">

                                                            {{ $counterdata->title }}</td> --}}
                                                        <td style="width: 100px" style="width:33%;" scope="row">
                                                            {{ $counterdata->days }}</td>
                                                        <td style="width: 100px" style="width:34%;text-align: right;">
                                                            $
                                                            {{ $counterdata->amount }}</td>

                                                    </tr>
                                                    @php
                                                        $per = ($counterdata->amount * $usermembership[0]->fee) / 100;
                                                        $total = $counterdata->amount - $per;
                                                        showAmount(@$bisdetail->job->amount) - $per;
                                                    @endphp
                                                    <tr>
                                                        <td style="width: 100px" colspan="3" style="width:1000%;">
                                                            Platform Fee: <span
                                                                style="float: right;">${{ showAmount($usermembership[0]->fee) }}</span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td style="width: 100px" colspan="3" style="width:100%;">
                                                            You
                                                            Will Get Payed: <span
                                                                style="float: right;">${{ $total }}</span>
                                                        </td>

                                                    </tr>
                                                @elseif($counteroffer->type == 'bymilestone')
                                                    @php
                                                        $counterdatas = DB::table('buyer_counter_offers_data')
                                                            ->where('bid_id', @$bisdetail->id)
                                                            ->get();
                                                    @endphp
                                                    <tr>
                                                        <td style="width: 100px" style="width:33%;" scope="row">
                                                            Title
                                                        </td>
                                                        <td style="width: 100px" style="width:33%;" scope="row">
                                                            Days
                                                        </td>
                                                        <td style="width: 100px" style="width:34%;text-align: right;">
                                                            Amount
                                                        </td>

                                                    </tr>
                                                    @foreach ($counterdatas as $counterdata)
                                                        <tr>
                                                            <td style="width: 100px" style="width:33%;" scope="row">
                                                                {{ $counterdata->title }}</td>
                                                            <td style="width: 100px" style="width:33%;" scope="row">
                                                                {{ $counterdata->days }}</td>
                                                            <td style="width: 100px" style="width:34%;text-align: right;">
                                                                $
                                                                {{ $counterdata->amount }}</td>

                                                        </tr>
                                                    @endforeach
                                                    @php
                                                        $sum = $counterdatas->sum('amount');
                                                        $per = ($sum * $usermembership[0]->fee) / 100;
                                                        $total = showAmount(@$bisdetail->job->amount) - $per;
                                                    @endphp
                                                    <tr>
                                                        <td style="width: 100px" colspan="3" style="width:1000%;">
                                                            Platform Fee: <span
                                                                style="float: right;">${{ showAmount($usermembership[0]->fee) }}</span>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td style="width: 100px" colspan="3" style="width:100%;">
                                                            You
                                                            Will Get Payed: <span
                                                                style="float: right;">${{ $total }}</span>
                                                        </td>

                                                    </tr>
                                                @endif



                                                <tr>

                                                    <p><span class="font-weight-bold">Cover
                                                            Letter:</span>{{ $counteroffer->description }}
                                                        @if ($counteroffer->file == '')
                                                        @else
                                                            <a title="Download attachment"
                                                                href="{{ route('user.bid.offer.file.download', encrypt($counteroffer->id)) }}"><i
                                                                    class="fa fa-paperclip"></i></a>
                                                        @endif
                                                    </p>
                                                </tr>

                                            </tbody>
                                        </table>
                                        @if (
                                            @$bisdetail->seller_offer_responce == '1' ||
                                                @$bisdetail->seller_offer_responce == '2' ||
                                                @$bisdetail->is_cancel_bid == '1')
                                        @else
                                            <div class="row" style="justify-content: end">

                                                <div class="col-lg-3">
                                                    <a href="javascript:void(0);"
                                                        data-acceptoffer_id="{{ @$bisdetail->id }}"
                                                        data-acceptjob_id="{{ @$bisdetail->job->id }}"
                                                        data-seller_id="{{ @$bisdetail->user_id }}"
                                                        data-buyer_id="{{ @$bisdetail->job->user_id }}"
                                                        data-toggle="modal" data-target="#acceptofferModal"
                                                        style="background-color: #00C535;
                                                                padding: 10px 30px 10px 30px;
                                                                width:100%;
                                                                font-size:15px;
                                                                font-weight: 700;
                                                                color: white;"
                                                        class="acceptofferBtn btn rounded text-light ">Accept</a>
                                                </div>
                                                <div class="col-lg-3">
                                                    <a href="javascript:void(0);"
                                                        data-canceloffer_id="{{ @$bisdetail->id }}" data-toggle="modal"
                                                        data-target="#cancelofferModal"
                                                        style="background-color: #00C535;
                                                                padding: 10px 30px 10px 30px;
                                                                font-weight: 700;
                                                                width:100%;
                                                                font-size:15px;
                                                                color: white;"
                                                        class="cancelofferBtn btn rounded bg-danger text-light ">Decline</a>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        "use strict";
        $('.workBtn').on('click', function() {
            var modal = $('#workModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
        $('.milestoneBtn').on('click', function() {
            var modal = $('#milestoneModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
        $('.toggle').click(function() {
            var id = $(this).data('bid_id');
            $('#target' + id).toggle();
        });
        $('.cancelofferBtn').on('click', function() {
            var modal = $('#cancelofferModal');
            modal.find('input[name=cancelresponce_id]').val($(this).data('canceloffer_id'))
            modal.modal('show');
        });
        $('.acceptofferBtn').on('click', function() {
            var modal = $('#acceptofferModal');
            modal.find('input[name=acceptresponce_id]').val($(this).data('acceptoffer_id'))
            modal.find('input[name=job_id]').val($(this).data('acceptjob_id'))
            modal.find('input[name=seller_id]').val($(this).data('seller_id'))
            modal.find('input[name=buyer_id]').val($(this).data('buyer_id'))
            modal.modal('show');
        });
        $('.cancelbidBtn').on('click', function() {
            var modal = $('#cancelbidModal');
            modal.find('input[name=cancelbid_id]').val($(this).data('cancelbid_id'))
            modal.modal('show');
        });
    </script>
@endsection
