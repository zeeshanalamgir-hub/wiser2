@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout')
@section('content')
    <!-- Bid Cancel Modal -->
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
                        <h4 class="m-0 ft-regular">@lang('Cancel Bid')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.job.vacancy.offers.canceloffer') }}">
                        @csrf
                        <input type="hidden" name="canceloffer_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <h6>@lang('Are you sure to cancel this bid ?')<h6>
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
    <!-- Offer Cancel Modal -->
    <div class="modal fade" id="cancelresponceModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h4 class="m-0 ft-regular">@lang('Cancel Offer')</h4>
                    </div>
                    <form method="POST" action="{{ route('user.job.vacancy.offers.cancelresponce') }}">
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
                        <h6 class="m-0 ft-regular">@lang('Offer detail')</h6>
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
    <div class="dashboard-content">
        <div class="dashboard-tlbar d-block">
            <div class="row">

            </div>
        </div>
        <style type="text/css">
            .btn {
                padding: 7px;
            }
        </style>
        <div class="dashboard-widg-bar d-block ">
            <div class="row">
                <div class="col-lg-12 pb-3">



                    {{-- <a href="{{route('user.job.vacancy.bids',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium " >Bid Detail</a> --}}
                    <a href="{{ route('user.job.vacancy.offers.detail', encrypt($singlejob->id)) }}"
                        class="btn rounded theme-bg text-light fs-sm ft-medium ">Offer Detail</a>

                    <!-- <a href="" class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium ml-2" >Offers </a> -->
                    @php

                        $accepted1 = DB::table('user_biding')
                            ->where('job_id', $singlejob->id)
                            ->first();
                        $accepted2 = DB::table('user_offers')
                            ->where('job_id', $singlejob->id)
                            ->first();
                    @endphp
                    <!--  @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
    @if (@$accepted1->seller_offer_responce == '2')
    <a href="{{ route('user.job.vacancy.accepted_bid', encrypt($singlejob->id)) }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Accepted Bid</a>
@elseif(@$accepted2->buyer_offer_responce == '2')
    <a href="{{ route('user.job.vacancy.accepted_bid', encrypt($singlejob->id)) }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Accepted Offer</a>
    @endif
    @endif
                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
    @if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
    <a href="{{ route('user.job.vacancy.hourlytimesheet', encrypt($singlejob->id)) }}" class="btn rounded theme-bg text-light fs-sm ft-medium ml-2" >Hourly Timesheet</a>
@else
    <a href="{{ route('user.job.vacancy.workspace', encrypt($singlejob->id)) }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Workspace</a>
    @endif

                    <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Chat with Buyer</a>
    @endif  -->
                </div>

            </div>
            <div class="row">
                <div class="col-lg-12 mb-3">
                    @php
                        $title = DB::table('jobs')
                            ->where('id', $singlejob->id)
                            ->first();
                    @endphp
                    <span> <span class="text-dark">JOB TITLE:</span> <a style="color:blue;text-decoration: underline;"
                            href="{{ route('job.details', [slug($title->title), encrypt($title->id)]) }}">{{ $title->title }}</a></span>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <!-- <th scope="col" class="text-left">@lang('Title')</th> -->
                                        <th scope="col" class="text-left">@lang('Type')</th>
                                        <th scope="col" class="text-left">@lang('Buyer')</th>
                                        <th scope="col" class="text-left">@lang('Submitted on')</th>
                                        <th scope="col" class="text-left">@lang('Budget')</th>
                                        <th scope="col" class="text-left">@lang('Offer amount')</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($offerdetailcount > 0)
                                        <tr>
                                            <!-- <td class="text-left"><a style="color:blue;text-decoration: underline;" href="{{ route('job.details', [slug(@$singlejob->title), encrypt($offerdetail->job->id)]) }}">{{ __(str_limit(@$singlejob->title, 20)) }} </a></td> -->
                                            @if ($offerdetail->job->budgettype == 'fixed')
                                                <td class="text-left">Fixed</td>
                                            @elseif($offerdetail->job->budgettype == 'per_hour')
                                                <td class="text-left">Per Hour</td>
                                            @endif
                                            @php
                                                $buyer = DB::table('users')
                                                    ->where('id', $offerdetail->job->user_id)
                                                    ->first();
                                            @endphp
                                            <td class="text-left">{{ $buyer->username }}<br>
                                                <a href="#">@ {{ $buyer->username }}</a>
                                            </td>

                                            <td class="text-left">
                                                {{ showDateTime($offerdetail->created_date, 'd M Y') }}
                                            </td>
                                            @if ($offerdetail->job->budgettype == 'fixed')
                                                <td class="text-left">
                                                    {{ $general->cur_sym }}{{ showAmount($offerdetail->job->amount) }}
                                                </td>
                                            @elseif($offerdetail->job->budgettype == 'per_hour')
                                                <td class="text-left">
                                                    {{ $general->cur_sym }}{{ showAmount($offerdetail->job->amount) }}/hour
                                                </td>
                                            @endif
                                            @if ($offerdetail->job->budgettype == 'fixed')
                                                <td class="text-left">${{ @$total_count }}</td>
                                            @elseif($offerdetail->job->budgettype == 'per_hour')
                                                <td class="text-left">$ {{ $offerdetail->hourlyrate }} /hour</td>
                                            @endif
                                            @if ($offerdetail->is_cancel_offer == '1')
                                                <td class="text-left"><span
                                                        class="badge badge-danger">@lang('Canceled')</span></td>
                                            @elseif($offerdetail->is_seller_offer_sent == '1')
                                                @if ($offerdetail->buyer_offer_responce == '1')
                                                    <td><span class="badge badge-danger">@lang('Declined')</span></td>
                                                @elseif($offerdetail->buyer_offer_responce == '2')
                                                    <td><span class="badge badge-success">@lang('Accepted')</span></td>
                                                @else
                                                    <td><span class="badge badge-warning">@lang('Bidding ongoing')</span></td>
                                                @endif
                                            @else
                                                <td><span class="badge badge-info">@lang('Ongoing Bidding')</span></td>
                                            @endif
                                            <td>
                                                <div class="dash-action-98563">
                                                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                                                        @if ($accepted2)
                                                            @if (@$accepted2->id == $offerdetail->id)
                                                                <a title="View Detail" href="javascript:void(0)"
                                                                    data-order_number="{{ $offerdetail->id }}"
                                                                    class="toggle p-2 circle text-info bg-light-info  d-inline-flex align-items-center justify-content-center mr-1"><i
                                                                        class="fa fa-eye"></i></a>
                                                                @if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                                                                    <a title="View Timesheet"
                                                                        href="{{ route('user.job.vacancy.hourlytimesheet', encrypt($singlejob->id)) }}"
                                                                        class="btn circle theme-bg text-light fs-sm ft-medium ml-2"><i
                                                                            class="fa fa-edit"></i></a>
                                                                @else
                                                                    <a title="View Workspace"
                                                                        href="{{ route('user.job.vacancy.workspace', encrypt($singlejob->id)) }}"
                                                                        class="btn circle theme-bg-light theme-cl fs-sm ft-medium ml-2"><i
                                                                            class="fa fa-edit"></i></a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($offerdetail->is_seller_offer_sent == '1')
                                                            <a title="View Detail" href="javascript:void(0)"
                                                                data-order_number="{{ $offerdetail->id }}"
                                                                class="toggle p-2 circle text-info bg-light-info  d-inline-flex align-items-center justify-content-center mr-1"><i
                                                                    class="fa fa-eye"></i></a>
                                                            @if (
                                                                $offerdetail->buyer_offer_responce == '1' ||
                                                                    $offerdetail->buyer_offer_responce == '2' ||
                                                                    $offerdetail->is_cancel_offer == '1')
                                                            @else
                                                                <a title="Cancel Offer" href="javascript:void(0);"
                                                                    data-sendoffer_id="{{ $offerdetail->id }}"
                                                                    data-sendofferjob_id="{{ $offerdetail->job->id }}"
                                                                    data-sendofferrec_id="{{ $offerdetail->buyer_id }}"
                                                                    data-sendoffersender_id="{{ $offerdetail->pro_id }}"
                                                                    data-toggle="modal" data-target="#cancelresponceModal"
                                                                    class="cancelresponceBtn p-2 circle  text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i
                                                                        class="fa fa-times"></i></a>
                                                            @endif
                                                            <a href="javascript:void(0)" title="Open Chat"
                                                                class=" p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center ml-1"><i
                                                                    class="fa fa-comments"></i></a>
                                                        @else
                                                            <a href="javascript:void(0)" title="View Detail"
                                                                data-order_number="{{ $offerdetail->id }}"
                                                                class="toggle p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                                    class="lni lni-eye"></i></a>

                                                            @if ($offerdetail->is_cancel_offer == '1')
                                                            @else
                                                                <a title="Send Offer"
                                                                    href="{{ route('user.job.vacancy.offers.offer', encrypt($offerdetail->id)) }}"
                                                                    class="p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center mr-1"><i
                                                                        class="fa fa-gift"></i></a>
                                                                <a href="javascript:void(0);"
                                                                    data-canceloffer_id="{{ $offerdetail->id }}"
                                                                    title="Cancel Offer" data-toggle="modal"
                                                                    data-target="#cancelofferModal"
                                                                    class="cancelofferBtn p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i
                                                                        class="lni lni-trash-can"></i></a>
                                                            @endif
                                                        @endif
                                                    @endif

                                                </div>
                                            </td>
                                        <tr>
                                            <td colspan="9" style="border-color: transparent !important;">
                                                <div class="row showresponce " style="display:none;"
                                                    id="target{{ $offerdetail->id }}">



                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-left">

                                                        <table class="table table-bordered"
                                                            style="border: 1px solid rgba(0,0,0,.125) !important;margin-bottom: 20px;">

                                                            <tbody>
                                                                @if ($offerdetail->job_type == 'per_hour')
                                                                    <tr>
                                                                        <!-- <td style="width:33%;" scope="row">Title</td> -->
                                                                        <td style="width:50%;text-align: left;">Amount</td>
                                                                        <td style="width:50%;text-align: left;">Hours per
                                                                            week</td>

                                                                    </tr>
                                                                    <tr>

                                                                        <td style="width:50%;text-align: left;">$
                                                                            {{ $offerdetail->hourlyrate }}/hour</td>
                                                                        <td style="width:50%;text-align: left;">
                                                                            {{ $offerdetail->hours }}</td>

                                                                    </tr>
                                                                @elseif($offerdetail->job_type == 'fixed')
                                                                    @php
                                                                        $milestones = DB::table('user_offer_milestone')
                                                                            ->where('offer_id', $offerdetail->id)
                                                                            ->get();
                                                                    @endphp
                                                                    <tr>
                                                                        <td style="width:33%;" scope="row">Title</td>
                                                                        <td style="width:33%;" scope="row">Days</td>
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
                                                                            Letter:</span>{{ $offerdetail->proposal }}

                                                                        @if (@$offerdetail->file == '')
                                                                        @else
                                                                            <a title="Download attachment"
                                                                                href="{{ route('user.job.vacancy.offers.file.download', encrypt(@$offerdetail->id)) }}"><i
                                                                                    class="fa fa-paperclip"></i></a>
                                                                        @endif
                                                                    </p>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                        @php
                                                            $counteroffer = DB::table('seller_counter_offers')
                                                                ->where('offer_id', $offerdetail->id)
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
                                                                            $counterdata = DB::table(
                                                                                'seller_counter_offers_data',
                                                                            )
                                                                                ->where('offer_id', $offerdetail->id)
                                                                                ->first();
                                                                        @endphp
                                                                        <tr>

                                                                            <td style="width:50%;text-align: left;">Amount
                                                                            </td>
                                                                            <td style="width:50%;text-align: left;"> Hours
                                                                                per week
                                                                            </td>



                                                                        </tr>
                                                                        <tr>

                                                                            <td style="width:50%;text-align: left;">$
                                                                                {{ $counterdata->amount }}/hour</td>
                                                                            <td style="width:50%;text-align: left;">
                                                                                {{ $counterdata->days }}</td>

                                                                        </tr>
                                                                    @elseif($counteroffer->type == 'fixed')
                                                                        @php
                                                                            $counterdatas = DB::table(
                                                                                'seller_counter_offers_data',
                                                                            )
                                                                                ->where('offer_id', $offerdetail->id)
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
                                                                        @foreach ($counterdatas as $counterdata)
                                                                            <tr>
                                                                                <td style="width:33%;" scope="row">
                                                                                    {{ $counterdata->title }}</td>
                                                                                <td style="width:33%;" scope="row">
                                                                                    {{ $counterdata->days }}</td>
                                                                                <td style="width:34%;text-align: right;">$
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
                                                                                    href="{{ route('user.job.vacancy.offersresponce.file.download', encrypt(@$counteroffer->id)) }}"><i
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
                                    @else
                                        <tr>
                                            <td>no data found</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!--
        <style type="text/css">
        .table-bordered td, .table-bordered th {
        border: 1px solid rgba(0,0,0,.125) !important;
        }
        .table tr th, .table tr td {
        border-color: #eaeff5;
        padding: 12px 15px;
        vertical-align: top;
        }
        #downbtn{
        padding: 1px 13px !important;
        }
        </style>
        <div class="row" >

            <div class="col-lg-12">

                <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                    <tbody>
                        <tr>

                            <th colspan="3" >Buyer Offer <span style="float:right;"><a href="#" id="downbtn" class="toggle2 btn rounded  fs-sm ft-medium mr-2" ><i class="fa fa-arrow-down"></i> </a></span></th>


                        </tr>


                        <tr>
                            <td style="width:33%;" scope="row">Milestone 1</td>
                            <td style="width:33%;" scope="row">04 January 2023</td>
                            <td style="width:34%;text-align: right;">$ 80.00</td>

                        </tr>
                        <tr>
                            <td style="width:33%;" scope="row">Milestone 2</td>
                            <td style="width:33%;" scope="row">14 January 2023</td>
                            <td style="width:34%;text-align: right;">$ 80.00</td>

                        </tr>
                        <tr>
                            <td style="width:33%;" scope="row">Milestone 3</td>
                            <td style="width:33%;" scope="row">24 January 2023</td>
                            <td style="width:34%;text-align: right;">$ 80.00</td>

                        </tr>
                        <tr class="showresponce " style="display:none;" id="target2">
                            <td colspan="3">
                                <div class="row ">



                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                        <p><span class="font-weight-bold">Cover Letter:</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl, vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt tristique risus ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est. Quisque mattis venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac nec magna. Nulla nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse consequat, dui non congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit amet mauris. Aenean consectetur sem eu lectus egestas tincidunt. Proin augue tellus, venenatis ac tempus nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida sit amet, condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales pellentesque risus. Nullam sit amet nisi eu neque feugiat finibus.</p>
                                        <a data-order_number="" data-toggle="modal" data-target="#approvedModal"  class="approvedBtn btn btn-sm rounded theme-bg-light theme-cl ft-medium ml-2 float-left"  >Accept </a>
                                        <a data-order_number="" data-toggle="modal" data-target="#cancelModal"  class="cancelBtn btn btn-sm rounded bg-danger text-white ft-medium ml-2 float-left"  >Decline </a>
                                        <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->
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
            //      $('.toggle').click(function() {
            // $('#target').toggle();
            // });
            // $('.toggle2').click(function() {
            // $('#target2').toggle();
            // });
            $('.toggle').click(function() {
                var id = $(this).data('order_number');
                $('#target' + id).toggle();
            });
            $('.cancelofferBtn').on('click', function() {
                var modal = $('#cancelofferModal');
                modal.find('input[name=canceloffer_id]').val($(this).data('canceloffer_id'))
                modal.modal('show');
            });
            $('.cancelresponceBtn').on('click', function() {
                var modal = $('#cancelresponceModal');
                modal.find('input[name=sendoffer_id]').val($(this).data('sendoffer_id'))
                modal.find('input[name=sendofferjob_id]').val($(this).data('sendofferjob_id'))
                modal.find('input[name=sendoffersender_id]').val($(this).data('sendoffersender_id'))
                modal.find('input[name=sendofferrec_id]').val($(this).data('sendofferrec_id'))
                modal.modal('show');
            });
        </script>
    @endsection
