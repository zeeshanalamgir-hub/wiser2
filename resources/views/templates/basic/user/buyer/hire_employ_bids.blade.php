@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <!-- Offer Cancel Modal -->
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
                    <form method="POST" action="{{ route('user.buyer.cancel.bid.offer') }}">
                        @csrf
                        <input type="hidden" name="sendoffer_id">
                        <input type="hidden" name="sendofferjob_id">
                        <input type="hidden" name="sendoffersender_id">
                        <input type="hidden" name="sendofferrec_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to cancel this Offer ?')<h6>
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
    <!-- End Offer Cancel Modal -->
    <!-- Bid Cancel Modal -->
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
                    <form method="POST" action="{{ route('user.buyer.cancel.bid') }}">
                        @csrf
                        <input type="hidden" name="cancelbid_id">
                        <input type="hidden" name="confirm" value="cancel">

                        <h6>@lang('Are you sure to cancel this bid ? ')<h6>
                                <div class="form-group">
                                    <input type="text" name="cancenreason" class="form-control"
                                        placeholder="Cancel Reason" required>
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
    <!-- End Bid Cancel Modal -->
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
                        <h6 class="m-0 ft-regular">@lang('Proposal detail')</h6>
                        <input type="" name="order_number">
                    </div>

                    <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                        <tbody>



                            <tr>
                                <td style="width:33%;" scope="row">Milestone 1</td>
                                <td style="width:33%;" scope="row">04 January 2023</td>
                                <td style="width:34%;text-align: left;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 2</td>
                                <td style="width:33%;" scope="row">14 January 2023</td>
                                <td style="width:34%;text-align: left;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 3</td>
                                <td style="width:33%;" scope="row">24 January 2023</td>
                                <td style="width:34%;text-align: left;">$ 100.00</td>

                            </tr>
                            <tr>
                                <p><span class="font-weight-bold">Cover Letter:</span>Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum
                                    dui nisl, vestibulum in lectus a, lobortis elementum velit. <a href=""><i
                                            class="fa fa-paperclip"></i></a> </p>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
    <div class="dashboard-content">
        <div class="container-header-table-100095">
            <div class="header-top-buttons-10065">
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
                <a href="{{ route('user.buyer.hire.employ.bids', encrypt($singlejob->id)) }}"
                    class="btn rounded sd-button-header-10068 ">Bids List</a>
            </div>
        </div>
        <div class="table-container-985632">
            <div class="table-heading-10002">
                @php
                    $title = DB::table('jobs')
                        ->where('id', $singlejob->id)
                        ->first();
                @endphp
                <div class="table-heding-top-78563">JOB TITLE: <a
                        href="{{ route('job.details', [slug($title->title), encrypt($title->id)]) }}">{{ $title->title }}</a>
                </div>
            </div>
            <table class="table row-border">
                <thead class="table-head-10004">
                    <tr>
                        <th scope="col" class="table-head-col-10005">@lang('Job Type')</th>
                        <th scope="col" class="table-head-col-10005">@lang('Seller')</th>
                        <th scope="col" class="table-head-col-10005">@lang('Submitted on')</th>
                        <th scope="col" class="table-head-col-10005">@lang('Bid amount')</th>
                        <th scope="col" class="table-head-col-10005">@lang('Status')</th>
                        <th scope="col" class="table-head-col-10005">@lang('Action')
                        </th>
                    </tr>
                </thead>
                <tbody class="table-body-10004">
                    @forelse($singlejobbids as $bid)
                        @php
                            $seller = DB::table('users')
                                ->where('id', $bid->user_id)
                                ->first();
                        @endphp
                        <tr>
                            @if ($bid->job->budgettype == 'fixed')
                                <td style="width: 280px">Fixed</td>
                            @elseif($bid->job->budgettype == 'per_hour')
                                <td style="width: 280px">Per Hour</td>
                            @endif
                            <td style="width: 150px">{{ $seller->username }} <br>
                                <a href="{{ route('profile', [slug($seller->username), encrypt($seller->id)]) }}">
                                </a>
                            </td>
                            <td style="width: 150px">
                                {{ showDateTime($bid->created_at, 'd M Y') }}
                            </td>
                            @if ($bid->job->budgettype == 'fixed')
                                @if ($bid->job_type == 'byproject')
                                    <td style="width: 150px">${{ $bid->bid_price }}</td>
                                @elseif($bid->job_type == 'bymilestone')
                                    @php
                                        $milestones = DB::table('user_milestone')
                                            ->where('bid_id', $bid->id)
                                            ->get();
                                        $sum = $milestones->sum('amount');
                                    @endphp
                                    <td style="width: 150px">${{ $sum }}</td>
                                @endif
                            @elseif($bid->job->budgettype == 'per_hour')
                                <td style="width: 150px">${{ $bid->bid_price }}/hour</td>
                            @endif
                            @if ($bid->is_buyer_offer_sent == '1')
                                @if ($bid->seller_offer_responce == '1')
                                    <td style="width: 150px">@lang('Declined')</span>
                                    </td>
                                @elseif($bid->seller_offer_responce == '2')
                                    <td style="width: 150px">@lang('Accepted')</span></td>
                                    </td>
                                @else
                                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                                        <td style="width: 150px"><span style="color: black">@lang('Bidding Closed')</span></td>
                                    @else
                                        <td style="width: 150px"><span style="color: black">@lang('Bidding Ongoing')</span></td>
                                    @endif
                                @endif
                            @else
                                @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                                    <td style="width: 150px">
                                        <span style="color: black">
                                            @lang('Bidding Closed')
                                        </span>
                                    </td>
                                @elseif ($bid->seller_offer_responce == 1 && $bid->is_buyer_offer_sent == 0)
                                    <td style="width: 150px">
                                        <span style="color: black">
                                            <!-- There should be a reason to show bid rejected instead of just show it in else -->
                                            @lang('Bid Rejected')
                                        </span>
                                    </td>
                                @else
                                    <td style="width: 150px">
                                        <span style="color: black">
                                            @lang('Panding')
                                        </span>
                                    </td>
                                @endif
                            @endif
                            <td style="width: 100px">
                                <div class="dash-action-98563">
                                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                                        @if ($accepted1)
                                            @if ($accepted1->id == $bid->id)
                                                @if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                                                    <a title="Timesheet"
                                                        href="{{ route('user.buyer.hire.employ.timesheet', encrypt($singlejob->id)) }}"
                                                        class="circle text-success  d-inline-flex align-items-center "
                                                        style="justify-content: space-between; gap:8px">
                                                        <i style="color: #00C535; " class="fa fa-edit"></i>
                                                        Edit
                                                    </a>
                                                @else
                                                    <a title="workspace"
                                                        href="{{ route('user.buyer.hire.employ.workspace', encrypt($singlejob->id)) }}"
                                                        class="circle text-success  d-inline-flex align-items-center "
                                                        style="justify-content: space-between; gap:8px">
                                                        <i style="color: #00C535; " class="fa fa-edit">
                                                        </i>
                                                        Edit
                                                    </a>
                                                @endif
                                                <a title="View Detail" href="javascript:void(0)"
                                                    data-order_number="{{ $bid->id }}"
                                                    class="circle text-info bg-light-info d-inline-flex align-items-center justify-content-center"
                                                    style="gap: 16px">
                                                    <i style="color: #00C535;" class="fa fa-eye"> </i>
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        @if ($bid->is_buyer_offer_sent == '1')
                                            <a title="View Detail" href="javascript:void(0)"
                                                data-order_number="{{ $bid->id }}"
                                                class="toggle p-2 circle text-info bg-light-info  d-inline-flex align-items-center justify-content-center mr-1"><i
                                                    class="fa fa-info"></i></a>
                                            @if ($bid->seller_offer_responce == '1' || $bid->seller_offer_responce == '2' || $bid->is_cancel_bid == '1')
                                            @else
                                                <a title="Cancel Offer" href="javascript:void(0);"
                                                    data-sendoffer_id="{{ $bid->id }}"
                                                    data-sendofferjob_id="{{ $bid->job->id }}"
                                                    data-sendofferrec_id="{{ $bid->user_id }}"
                                                    data-sendoffersender_id="{{ $bid->job->user_id }}"
                                                    data-toggle="modal" data-target="#cancelofferModal"
                                                    class="cancelofferBtn p-2 circle  text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i
                                                        class="fa fa-times"></i></a>
                                            @endif
                                            <a href="javascript:void(0)" title="Open Chat"
                                                class=" p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center ml-1"><i
                                                    class="fa fa-comments"></i></a>
                                        @else
                                            <a href="javascript:void(0)" title="View Bidding Detail"
                                                data-order_number="{{ $bid->id }}"
                                                class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                    class="lni lni-eye"></i></a>
                                            <a title="Send Offer"
                                                href="{{ route('user.buyer.send.bid.offer', encrypt($bid->id)) }}"
                                                class="p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center mr-1"><i
                                                    class="fa fa-gift"></i></a>
                                            @if ($bid->is_cancel_bid == '1')
                                            @else
                                                <a href="javascript:void(0);" data-cancelbid_id="{{ $bid->id }}"
                                                    title="Cancel Bid" data-toggle="modal" data-target="#cancelbidModal"
                                                    class="cancelbidBtn p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1">
                                                    <i class="lni lni-trash-can"></i>
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </td>
                        <tr>
                            <td colspan="9" style="border-color: transparent !important;" style="width: 150px">
                                <div class="row showresponce " style="display:none;" id="target{{ $bid->id }}">



                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-left">
                                        <table class="table table-bordered"
                                            style="border: 1px solid rgba(0,0,0,.125) !important;margin-bottom: 20px;">

                                            <tbody>
                                                @if (@$bid->seller_offer_responce == '1')
                                                    <tr>
                                                        <p class="text-danger">Cancel Reason:
                                                            {{ $bid->seller_offer_responce_reason }}
                                                        </p>
                                                    </tr>
                                                @endif
                                                @if ($bid->job_type == 'hourly')
                                                    <tr>
                                                        <td style="width:100%;text-align: left;">
                                                            Amount
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td style="width:100%;text-align: left;">
                                                            ${{ $bid->bid_price }}/hour
                                                        </td>
                                                    </tr>
                                                @elseif($bid->job_type == 'byproject')
                                                    <tr>
                                                        <td style="width:50%;" scope="row">
                                                            Days
                                                        </td>
                                                        <td style="width:50%;text-align: left;">
                                                            Amount
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:50%;" scope="row">
                                                            {{-- @php
                                                                                print_r($bid);
                                                                            @endphp --}}
                                                            {{ $bid->job->delivery_time }}</td>
                                                        <td style="width:50%;text-align: left;">$
                                                            {{ $bid->bid_price }}</td>

                                                    </tr>
                                                @elseif($bid->job_type == 'bymilestone')
                                                    @php
                                                        $milestones = DB::table('user_milestone')
                                                            ->where('bid_id', $bid->id)
                                                            ->get();
                                                    @endphp
                                                    <tr>
                                                        <td style="width:33%;" scope="row">Title
                                                        </td>
                                                        <td style="width:33%;" scope="row">Days
                                                        </td>
                                                        <td style="width:34%;text-align: left;">Amount
                                                        </td>

                                                    </tr>
                                                    @foreach ($milestones as $md)
                                                        <tr>
                                                            <td style="width:33%;" scope="row">
                                                                {{ $md->title }}</td>
                                                            <td style="width:33%;" scope="row">
                                                                {{ $md->due_date }}</td>
                                                            <td style="width:34%;text-align: left;">$
                                                                {{ $md->amount }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <p><span class="font-weight-bold">Cover
                                                            Letter:</span>{{ $bid->proposal }}

                                                        @if (@$bid->file == '')
                                                        @else
                                                            <a title="Download attachment"
                                                                href="{{ route('user.buyer.hire.offer.file.download', encrypt(@$bid->id)) }}">
                                                                <i class="fa fa-paperclip"></i>
                                                            </a>
                                                        @endif
                                                    </p>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @php
                                            $counteroffer = DB::table('buyer_counter_offers')
                                                ->where('bid_id', $bid->id)
                                                ->first();
                                        @endphp

                                        @if ($counteroffer)
                                            <hr>
                                            <h5>Counter Offer:</h5>
                                            <table class="table table-bordered"
                                                style="border: 1px solid rgba(0,0,0,.125) !important;">

                                                <tbody>
                                                    @if ($counteroffer->type == 'hourly')
                                                        @php
                                                            $counterdata = DB::table('buyer_counter_offers_data')
                                                                ->where('bid_id', $bid->id)
                                                                ->first();
                                                        @endphp
                                                        <tr>
                                                            <!--  <td style="width:50%;" scope="row">Title</td> -->
                                                            <td style="width:100%;text-align: left;">
                                                                Amount</td>

                                                        </tr>
                                                        <tr>
                                                            <!-- <td style="width:50%;" scope="row">{{ @$counterdata->title }}</td> -->
                                                            <td style="width:100%;text-align: left;">$
                                                                {{ $counterdata->amount }}/hour</td>

                                                        </tr>
                                                    @elseif($counteroffer->type == 'byproject')
                                                        @php
                                                            $counterdata = DB::table('buyer_counter_offers_data')
                                                                ->where('bid_id', $bid->id)
                                                                ->first();
                                                        @endphp
                                                        <tr>
                                                            <!-- <td style="width:33%;" scope="row">Title</td> -->
                                                            <td style="width:50%;" scope="row">Days
                                                            </td>
                                                            <td style="width:50%;text-align: lett;">
                                                                Amount
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <!-- <td style="width:33%;" scope="row">{{ @$counterdata->title }}</td> -->
                                                            <td style="width:50%;" scope="row">
                                                                {{ $counterdata->days }}</td>
                                                            <td style="width:50%;text-align: left;">$
                                                                {{ $counterdata->amount }}</td>

                                                        </tr>
                                                    @elseif($counteroffer->type == 'bymilestone')
                                                        @php
                                                            $counterdatas = DB::table('buyer_counter_offers_data')
                                                                ->where('bid_id', $bid->id)
                                                                ->get();
                                                        @endphp
                                                        <tr>
                                                            <td style="width:33%;" scope="row">
                                                                Title
                                                            </td>
                                                            <td style="width:33%;" scope="row">Days
                                                            </td>
                                                            <td style="width:34%;text-align: left;">
                                                                Amount
                                                            </td>

                                                        </tr>
                                                        @foreach ($counterdatas as $counterdata)
                                                            <tr>
                                                                <td style="width:33%;" scope="row">
                                                                    {{ $counterdata->title }}</td>
                                                                <td style="width:33%;" scope="row">
                                                                    {{ $counterdata->days }}</td>
                                                                <td style="width:34%;text-align: left;">
                                                                    $
                                                                    {{ $counterdata->amount }}</td>

                                                            </tr>
                                                        @endforeach
                                                    @endif


                                                    <tr>
                                                        <p><span class="font-weight-bold">Cover
                                                                Letter:</span>{{ $counteroffer->description }}

                                                            @if (@$counteroffer->file == '')
                                                            @else
                                                                <a title="Download attachment"
                                                                    href="{{ route('user.buyer.counter.offer.file.download', encrypt(@$counteroffer->id)) }}"><i
                                                                        class="fa fa-paperclip"></i></a>
                                                            @endif

                                                        </p>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        @endif

                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Send Offer')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('user.work.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="service">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Attachment')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file" required="">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="" class="form-control" name="title"
                                placeholder="Title " required>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Details ...." required></textarea>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Milestones')</label>
                                <div class="row">
                                    <div class="col-md-4 pt-3">
                                        Milestone1
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="number" name="" class="form-control" name="title"
                                                placeholder="$ 32" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="date" name="" class="form-control" name="title"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 pt-3">
                                        Milestone2
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="number" name="" class="form-control" name="title"
                                                placeholder="$ 32" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="date" name="" class="form-control" name="title"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 pt-3">
                                        Milestone3
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="number" name="" class="form-control" name="title"
                                                placeholder="$ 32" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="date" name="" class="form-control" name="title"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-white"
                            data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success btn-rounded text-white">@lang('Send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        "use strict";
        $('.approvedBtn').on('click', function() {
            var modal = $('#approvedModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
        $('.milestoneBtn').on('click', function() {
            var modal = $('#milestoneModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
        $('.cancelofferBtn').on('click', function() {
            var modal = $('#cancelofferModal');
            modal.find('input[name=sendoffer_id]').val($(this).data('sendoffer_id'))
            modal.find('input[name=sendofferjob_id]').val($(this).data('sendofferjob_id'))
            modal.find('input[name=sendoffersender_id]').val($(this).data('sendoffersender_id'))
            modal.find('input[name=sendofferrec_id]').val($(this).data('sendofferrec_id'))
            modal.modal('show');
        });
        $('.cancelbidBtn').on('click', function() {
            var modal = $('#cancelbidModal');
            modal.find('input[name=cancelbid_id]').val($(this).data('cancelbid_id'))
            modal.modal('show');
        });
        $('.workBtn').on('click', function() {
            var modal = $('#workModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
        $('.toggle').click(function() {
            var id = $(this).data('order_number');
            $('#target' + id).toggle();
        });
    </script>
@endsection
