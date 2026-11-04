@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
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
                        <h4 class="m-0 ft-regular">@lang('Cancel Offer Responce')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.cancel.hisofferresponce') }}">
                        @csrf
                        <input type="hidden" name="cancelresponce_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to decline this offer responce ?')<h6>
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
                        <h4 class="m-0 ft-regular">@lang('Accept Offer Responce')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.accept.hisofferresponce') }}">
                        @csrf
                        <input type="hidden" name="acceptresponce_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to accept this offer responce?')<h6>
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
                        <h4 class="m-0 ft-regular">@lang('Cancel offer')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.cancel.hisoffer') }}">
                        @csrf
                        <input type="hidden" name="cancelbid_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to cancel your offer ?')<h6>
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
                        <h6 class="m-0 ft-regular">@lang('Proposal detail')</h6>
                    </div>

                    <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                        <tbody>



                            <tr>
                                <td style="width:33%;" scope="row">Milestone 1</td>
                                <td style="width:33%;" scope="row">04 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 2</td>
                                <td style="width:33%;" scope="row">14 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 3</td>
                                <td style="width:33%;" scope="row">24 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

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
    <!-- End Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
    <!-- End Modal -->
    <div class="dashboard-content">
        <div class="container-header-table-100095">
            @include('templates.basic.user.buyer.components.buyer-hire-employees-timesheet-top-button')

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
                            <!-- <th scope="col" class="text-left">@lang('Title')</th> -->
                            <th scope="col" class="text-left">@lang('Job Type')</th>
                            <th scope="col" class="text-left">@lang('Seller')</th>
                            <th scope="col" class="text-left">@lang('Submitted on')</th>
                            <th scope="col" class="text-left">@lang('Budget')</th>
                            <th scope="col" class="text-left">@lang('Offer amount')</th>
                            <th scope="col" class="text-center">@lang('Status')</th>
                            <th style="width:20%;" scope="col" class="text-center">@lang('Action')
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($singlejoboffers as $bid)
                            @php
                                $seller = DB::table('users')
                                    ->where('id', $bid->pro_id)
                                    ->first();
                            @endphp
                            <tr>

                                <!-- <td class="text-left"><a style="color:blue;text-decoration: underline;" href="{{ route('job.details', [slug($bid->job->title), encrypt($bid->job->id)]) }}">{{ __(str_limit($bid->job->title, 20)) }} </a></td> -->
                                @if ($bid->job->budgettype == 'fixed')
                                    <td class="text-left">Fixed</td>
                                @elseif($bid->job->budgettype == 'per_hour')
                                    <td class="text-left">Per Hour</td>
                                @endif

                                <td class="text-left">{{ $seller->username }} <br>
                                    <a href="{{ route('profile', [slug($seller->username), encrypt($seller->id)]) }}">@
                                        {{ $seller->username }}</a>
                                </td>

                                <td class="text-left">
                                    {{ showDateTime($bid->created_at, 'd M Y') }}
                                </td>
                                @if ($bid->job->budgettype == 'fixed')
                                    <td class="text-left">
                                        {{ $general->cur_sym }}{{ showAmount($bid->job->amount) }}</td>
                                @elseif($bid->job->budgettype == 'per_hour')
                                    <td class="text-left">
                                        {{ $general->cur_sym }}{{ showAmount($bid->job->amount) }}/hour
                                    </td>
                                @endif
                                @php
                                    $milestones = DB::table('user_offer_milestone')
                                        ->where('offer_id', $bid->id)
                                        ->get();
                                    $sum = $milestones->sum('amount');

                                @endphp

                                @if ($bid->job->budgettype == 'fixed')
                                    <td class="text-left">$ {{ $sum }}</td>
                                @elseif($bid->job->budgettype == 'per_hour')
                                    <td class="text-left">${{ $bid->hourlyrate }}/hour</td>
                                @endif
                                @if (@$bid->is_cancel_offer == '1')
                                    <td><span class="badge badge-danger">@lang('Canceled')</span></td>
                                @elseif(@$bid->is_seller_offer_sent == '1')
                                    @if (@$bid->buyer_offer_responce == '-1')
                                        <td><span class="badge badge-success">@lang('Offered')</span>
                                        </td>
                                    @elseif(@$bid->buyer_offer_responce == '1')
                                        <td><span class="badge badge-danger">@lang('Declined')</span></td>
                                    @elseif(@$bid->buyer_offer_responce == '2')
                                        <td><span class="badge badge-success">@lang('Accepted')</span>
                                        </td>
                                    @else
                                        <td><span class="badge badge-success">@lang('Pending')</span>
                                        </td>
                                    @endif
                                @else
                                    <td><span class="badge badge-warning">@lang('Pending')</span></td>
                                @endif
                                <td>
                                    <div class="dash-action-98563">


                                        @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                                            @if ($accepted2)
                                                @if ($accepted2->id == $bid->id)
                                                    <a href="javascript:void(0);" title="View Detail"
                                                        data-bid_id="{{ @$bid->id }}"
                                                        style="height:30px;width:30px;border-radius: 50%;"
                                                        class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                            class="fa fa-info"></i></a>
                                                    @if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                                                        <a title="Timesheet"
                                                            href="{{ route('user.buyer.hire.employ.timesheet', encrypt($singlejob->id)) }}"
                                                            class=" p-2 circle text-info bg-light-info  d-inline-flex align-items-center justify-content-center mr-1"><i
                                                                class="fa fa-edit"></i></a>
                                                    @else
                                                        <a title="workspace"
                                                            href="{{ route('user.buyer.hire.employ.workspace', encrypt($singlejob->id)) }}"
                                                            class=" p-2 circle text-info bg-light-info  d-inline-flex align-items-center justify-content-center mr-1"><i
                                                                class="fa fa-edit"></i></a>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            @if (@$bid->is_cancel_offer == '1')
                                                <a target="_blank"
                                                    href="{{ route('professioan.hire.edit', [encrypt($bid->id)]) }}"
                                                    title="Revise Offer"
                                                    style="height:30px;width:30px;border-radius: 50%;"
                                                    class="p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center mr-1"><i
                                                        class="fa fa-gift"></i></a>
                                            @endif

                                            @if (@$bid->buyer_offer_responce == '1' || @$bid->buyer_offer_responce == '2' || @$bid->is_cancel_offer == '1')
                                                <a href="javascript:void(0);" title="View Detail"
                                                    data-bid_id="{{ @$bid->id }}"
                                                    style="height:30px;width:30px;border-radius: 50%;"
                                                    class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                        class="fa fa-info"></i></a>
                                            @else
                                                <a href="javascript:void(0);" title="View Detail"
                                                    data-bid_id="{{ @$bid->id }}"
                                                    style="height:30px;width:30px;border-radius: 50%;"
                                                    class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                        class="fa fa-info"></i></a>
                                                <a href="javascript:void(0);" title="Cancelbid"
                                                    data-cancelbid_id="{{ @$bid->id }}" data-toggle="modal"
                                                    data-target="#cancelbidModal"
                                                    class="cancelbidBtn p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i
                                                        class="lni lni-trash-can"></i></a>
                                            @endif
                                        @endif
                                        <!-- <a href="javascript:void(0);" data-order_number="" data-order_number="" data-toggle="modal" data-target="#milestoneModal" class="milestoneBtn p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="lni lni-eye"></i></a> -->
                                        <!-- <a data-toggle="modal" data-target="#workModal" href="javascript:void(0);" class="workBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center mr-1"><i class="fa fa-gift"></i></a> -->
                                        <!-- <a href="javascript:void(0);" class=" p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="lni lni-trash-can"></i></a> -->

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" style="border-color: transparent !important;">
                                    <div class="row showresponce " style="display:none;" id="target{{ @$bid->id }}">



                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-left">

                                            <table class="table table-bordered"
                                                style="border: 1px solid rgba(0,0,0,.125) !important;margin-bottom: 20px;">

                                                <tbody>
                                                    @if ($bid->job_type == 'per_hour')
                                                        <tr>

                                                            <td style="width:50%;text-align: left;">Amount
                                                            </td>
                                                            <td style="width:50%;text-align: left;">Hours
                                                                per week</td>

                                                        </tr>
                                                        <tr>

                                                            <td style="width:50%;text-align: left;">$
                                                                {{ @$bid->hourlyrate }}/hour</td>
                                                            <td style="width:50%;text-align: left;">
                                                                {{ @$bid->hours }}</td>

                                                        </tr>
                                                    @elseif($bid->job_type == 'fixed')
                                                        @php
                                                            $milestones = DB::table('user_offer_milestone')
                                                                ->where('offer_id', $bid->id)
                                                                ->get();
                                                        @endphp
                                                        <tr>
                                                            <td style="width:33%;" scope="row">Title
                                                            </td>
                                                            <td style="width:33%;" scope="row">Days
                                                            </td>
                                                            <td style="width:34%;text-align: right;">Amount
                                                            </td>

                                                        </tr>
                                                        @foreach ($milestones as $md)
                                                            <tr>
                                                                <td style="width:33%;" scope="row">
                                                                    {{ $md->title }}</td>
                                                                <td style="width:33%;" scope="row">
                                                                    {{ $md->due_date }}</td>
                                                                <td style="width:34%;text-align: right;">$
                                                                    {{ $md->amount }}</td>

                                                            </tr>
                                                        @endforeach
                                                    @endif


                                                    <tr>
                                                        <p><span class="font-weight-bold">Cover
                                                                Letter:</span>{{ $bid->proposal }}

                                                            @if (@$bid->file == '')
                                                            @else
                                                                <a title="Download attachment"
                                                                    href="{{ route('user.buyer.hisoffer.file.download', encrypt(@$bid->id)) }}"><i
                                                                        class="fa fa-paperclip"></i></a>
                                                            @endif

                                                        </p>
                                                    </tr>

                                                </tbody>
                                            </table>
                                            @php
                                                $counteroffer = DB::table('seller_counter_offers')
                                                    ->where('offer_id', $bid->id)
                                                    ->first();
                                            @endphp

                                            @if ($counteroffer)
                                                <hr>
                                                <h5>Counter Offer:</h5>
                                                <table class="table table-bordered"
                                                    style="border: 1px solid rgba(0,0,0,.125) !important;">

                                                    <tbody>
                                                        @if ($counteroffer->type == 'per_hour')
                                                            @php
                                                                $counterdata = DB::table('seller_counter_offers_data')
                                                                    ->where('offer_id', $bid->id)
                                                                    ->first();
                                                            @endphp
                                                            <tr>
                                                                <td style="width:50%;text-align: left;">
                                                                    Amount</td>
                                                                <td style="width:50%;text-align: left;">
                                                                    Hours per week</td>

                                                            </tr>
                                                            <tr>

                                                                <td style="width:50%;text-align: left;">$
                                                                    {{ $counterdata->amount }}/hour</td>
                                                                <td style="width:50%;text-align: left;">
                                                                    {{ $counterdata->days }}</td>

                                                            </tr>
                                                        @elseif($counteroffer->type == 'fixed')
                                                            @php
                                                                $counterdatas = DB::table('seller_counter_offers_data')
                                                                    ->where('offer_id', $bid->id)
                                                                    ->get();
                                                            @endphp
                                                            <tr>
                                                                <td style="width:33%;" scope="row">
                                                                    Title</td>
                                                                <td style="width:33%;" scope="row">Days
                                                                </td>
                                                                <td style="width:34%;text-align: right;">
                                                                    Amount</td>

                                                            </tr>
                                                            @foreach ($counterdatas as $counterdata)
                                                                <tr>
                                                                    <td style="width:33%;" scope="row">
                                                                        {{ $counterdata->title }}</td>
                                                                    <td style="width:33%;" scope="row">
                                                                        {{ $counterdata->days }}</td>
                                                                    <td style="width:34%;text-align: right;">
                                                                        $ {{ $counterdata->amount }}</td>

                                                                </tr>
                                                            @endforeach
                                                        @endif


                                                        <tr>
                                                            <p><span class="font-weight-bold">Cover
                                                                    Letter:</span>{{ $counteroffer->description }}

                                                                @if (@$counteroffer->file == '')
                                                                @else
                                                                    <a title="Download attachment"
                                                                        href="{{ route('user.buyer.hisofferresponce.file.download', encrypt(@$counteroffer->id)) }}"><i
                                                                            class="fa fa-paperclip"></i></a>
                                                                @endif

                                                            </p>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                                @if (@$bid->buyer_offer_responce == '1' || @$bid->buyer_offer_responce == '2' || @$bid->is_cancel_offer == '1')
                                                @else
                                                    <div class="col-lg-12 float-right">
                                                        <a href="javascript:void(0);"
                                                            data-acceptoffer_id="{{ @$bid->id }}" data-toggle="modal"
                                                            data-target="#acceptofferModal"
                                                            class="acceptofferBtn btn rounded theme-bg text-light fs-sm ft-medium ">Accept</a>
                                                        <a href="javascript:void(0);"
                                                            data-canceloffer_id="{{ @$bid->id }}" data-toggle="modal"
                                                            data-target="#cancelofferModal"
                                                            class="cancelofferBtn btn rounded bg-danger text-light fs-sm ft-medium ">Decline</a>
                                                    </div>
                                                @endif
                                            @endif
                                        @empty
                            <tr>
                                <td colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
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
        $('.cancelBtn').on('click', function() {
            var modal = $('#cancelModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
        $('.workBtn').on('click', function() {
            var modal = $('#workModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
        // $('.toggle').click(function() {
        // $('#target').toggle();
        // });
        // $('.toggle2').click(function() {
        // $('#target2').toggle();
        // });
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
            modal.modal('show');
        });
        $('.cancelbidBtn').on('click', function() {
            var modal = $('#cancelbidModal');
            modal.find('input[name=cancelbid_id]').val($(this).data('cancelbid_id'))
            modal.modal('show');
        });
    </script>
@endsection
