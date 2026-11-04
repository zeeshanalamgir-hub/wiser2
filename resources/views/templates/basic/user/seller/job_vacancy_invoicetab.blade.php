@extends($activeTemplate.'layouts.wiserlance.dashboard-layout-job')
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
                <form method="POST" action="{{route('user.proposal.approved')}}">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="confirm" value="approved">
                    @lang('Are you sure to approved this work delivery')
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
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
                <form method="POST" action="{{route('user.proposal.approved')}}">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="confirm" value="cancel">
                    <p>@lang('Are you sure to cancel this Proposal')</p>
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
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
    <!-- <div class="dashboard-tlbar d-block mb-5">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                        <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="dashboard-manage-service.html" class="theme-cl">Manage Services</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div> -->

    <style type="text/css">
    .btn{
    padding: 7px;
    }
    </style>
    <div class="dashboard-widg-bar d-block ">
        <div class="row">
            <div class="col-lg-12 pb-3" >

                @php


                $accepted1 = DB::table('user_biding')->where('job_id', $singlejob->id)->where('seller_offer_responce','2')->first();
                $accepted2 = DB::table('user_offers')->where('job_id',$singlejob->id)->where('buyer_offer_responce','2')->first();
                @endphp
                @if(@$accepted1->seller_offer_responce == '2')
                <a href="{{route('user.job.vacancy.bids',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium " ><i class="fa fa-angle-double-left"></i>Back</a>
                @elseif(@$accepted2->buyer_offer_responce == '2')
                <a href="{{route('user.job.vacancy.offers.detail', encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium " ><i class="fa fa-angle-double-left"></i>Back</a>
                @endif
                <!-- <a href="" class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium ml-2" >Offers </a> -->

                @if(@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                @if(@$accepted1->seller_offer_responce == '2')
                <a href="{{route('user.job.vacancy.accepted_bid',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Accepted Bid</a>
                @elseif(@$accepted2->buyer_offer_responce == '2')
                <a href="{{route('user.job.vacancy.accepted_bid',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Accepted Offer</a>
                @endif
                @endif
                @if(@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                @if(@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                <a href="{{route('user.job.vacancy.hourlytimesheet',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl  fs-sm ft-medium ml-2" >Hourly Timesheet</a>
                @else
                <a href="{{route('user.job.vacancy.workspace',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Workspace</a>
                @endif

                @php
                $userName = DB::table('users')->where('id', $accepted1->user_id)->first();
                if($userName->id == Auth()->user()->id){
                    $userName = DB::table('jobs')->where('id', $accepted1->job_id)->first();
                    $userName = DB::table('users')->where('id', $userName->user_id)->first();
                }
                @endphp
                <a href="{{url('chatify')}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Chat with {{$userName->firstname}}</a>
               <a href="{{route('user.job.vacancy.invoicetab',encrypt($singlejob->id))}}" class="btn rounded theme-bg text-light fs-sm ft-medium ml-2" >Invoice</a>
                <a href="{{route('user.job.vacancy.ratingtab',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Rating & review</a>



                @endif
            </div>

        </div>
        @if(@$acceptedbid)
        @php
        $job=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
        $seller=DB::table('users')->where('id',$accepteddata->seller_id)->first();
        $buyer=DB::table('users')->where('id',$job->user_id)->first();
        $add=json_encode($buyer->address);
        @endphp
        <div class="row bg-white p-3 mt-3">
            <div class="col-lg-12 text-right">
                <img src="{{asset('assets/wiserlance/assets/img/WL logo.jpg')}}" style="width:10%;">
            </div>
            <div class="col-lg-12 mt-3">
                <h5>INVOICE <span class="float-right"><a class="float-right mb-2" style="text-decoration: underline;font-size: 14px;" href="{{url('generate-invoice-pdf',$singlejob->id)}}">Download Invoice</a></span></h5>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Invoice Number</span><br>
                <span >#12345</span><br>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Date Created</span><br>
                <span >{{showDateTime($accepteddata->created_at, ('d M Y'))}}</span><br>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Buyer's Detail</span><br>
                <span >Name: {{$buyer->username}}</span><br>
                <span >Address:  {{$buyer->country}}</span><br>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Seller's Detail</span><br>
                <span >Name: <a href="{{route('profile', [slug($seller->username), encrypt($seller->id)])}}">{{$seller->username}}</a></span><br>
                <span >Address: {{$seller->country}}</span><br>
            </div>
            <style type="text/css">
            .table tr th, .table tr td {

            padding: 5px 8px !important;
            vertical-align: top;
            }
            </style>
            <div class="col-lg-12 mt-4">
                <table class="table ">

                    <tbody>


                        @if($job->budgettype == 'per_hour')
                        @php
                        @$counteroffer=DB::table('buyer_counter_offers')->where('bid_id',$accepteddata->bid_id)->first();
                        @$counterofferdata=DB::table('buyer_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->first();
                        $works=DB::table('job_work_delivery_houly_bid')->where('data_id',@$counterofferdata->id)->where('job_id',$accepteddata->job_id)->where('bid_id',$accepteddata->bid_id)->get();
                        $allwork=DB::table('job_work_delivery_houly_bid')->where('data_id',@$counterofferdata->id)->where('job_id',$accepteddata->job_id)->where('bid_id',$accepteddata->bid_id)->where('status',5)->get();
                        $total=$allwork->sum('amount');
                        $counterdata=DB::table('buyer_counter_offers_data')->where('bid_id',$accepteddata->bid_id)->first();
                        $usermembership = DB::table('freelancer_membership_transactions')->join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', Auth::user()->id)->get();

                        @endphp
                        <tr>
                            <th  scope="row">Particulars</th>
                            <th  scope="row">Rate ($)</th>
                            <th  scope="row">Quantity</th>
                            <th  scope="row">Unit</th>
                            <th  scope="row">Amount ($)</th>
                            <th  scope="row">Platform %</th>
                            <th  scope="row">Net Amount</th>

                        </tr>
                        @foreach($works as $work)
                        <tr>
                            <td>{{$work->title}} {{showDateTime($work->date_start, ('d M Y'))}} - {{showDateTime($work->date_end, ('d M Y'))}}</td>
                            <td>{{$counterofferdata->amount}}</td>
                            <td> {{$work->time}}.{{$work->minutes}}</td>
                            <td>hourly</td>
                            @if($work->status == 5)
                            <td>{{$work->total_cost}}</td>
                            @else
                            <td>0</td>
                            @endif

                            <td>{{showAmount($usermembership[0]->fee)}}</td>
                            <td>{{$work->total_cost  * (1 - (showAmount($usermembership[0]->fee) / 100))}}</td>

                            <?php $total += $work->total_cost  * (1 - (showAmount($usermembership[0]->fee) / 100));?>

                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Invoice Total</td>
                            <td style="border-bottom:1px solid black;border-top:1px solid black;">${{$total}}</td>

                        </tr>
                        <!--  <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->amount)}} /hour</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Accepted amount:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount(@$counterofferdata->amount)}} /hour</td>

                        </tr> -->

                        @else

                        @php
                        @$counteroffer=DB::table('buyer_counter_offers')->where('bid_id',$accepteddata->bid_id)->first();
                        @$counterofferdata=DB::table('buyer_counter_offers_data')->where('disputed_amount','0')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->get();
                        $counterofferdataorg=DB::table('buyer_counter_offers_data')->where('counter_offer_id',$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->get();
                        $payed=DB::table('buyer_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->where('status','5')->where('disputed_amount','0')->get();
                        $payed1=DB::table('buyer_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->where('status','5')->where('disputed_amount','!=','0')->get();


                        $all=(@$payed1->sum('disputed_amount')+$payed->sum('amount'));
                        $Jobname=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
                        @endphp
                        <tr>
                            <th  scope="row">Description</th>
                            <th  scope="row">Unit cost</th>
                            <th  scope="row">Qty/HR Rate</th>
                            <th  scope="row">Amount</th>

                        </tr>
                        @foreach($counterofferdataorg as $cd)
                        <tr>
                            <td>
                                @if($counteroffer->type == 'bymilestone')
                                {{$cd->title}}
                                @elseif($counteroffer->type == 'byproject')
                                {{$Jobname->title}}
                                @endif
                            </td>
                            <td>$ {{$cd->amount}}</td>
                            <td>1</td>
                            @if($cd->status == 5)
                            @if($cd->disputed_amount == 0)
                            <td>$ {{$cd->amount}}</td>
                            @else
                            <td>$ {{$cd->disputed_amount}}</td>
                            @endif
                            @else
                            <td>$ 0</td>
                            @endif
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Invoice Total</td>
                            <td style="border-bottom:1px solid black;border-top:1px solid black;">$ {{$all}}</td>

                        </tr>


                        @endif


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


                    </tbody>
                </table>
            </div>
            <div class="col-lg-12 mt-5">
                <p class="font-weight-bold">This is a computer-generated invoice.</p>
            </div>
        </div>
        @endif
        @if(@$acceptedoffer)
        @php
        $job=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
        $seller=DB::table('users')->where('id',$accepteddata->seller_id)->first();
        $buyer=DB::table('users')->where('id',$job->user_id)->first();
        @endphp
        <div class="row bg-white p-3 mt-3">
            <div class="col-lg-12 text-right">
                <img src="{{asset('assets/wiserlance/assets/img/WL logo.jpg')}}" style="width:10%;">
            </div>
            <div class="col-lg-12 mt-3">
                <h5>INVOICE <span class="float-right"><a class="float-right mb-2" style="text-decoration: underline;font-size: 14px;" href="{{url('generate-invoice-pdf-offer',$singlejob->id)}}">Download Invoice</a></span></h5>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Invoice Number</span><br>
                <span >#12345</span><br>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Date Created</span><br>
                <span >{{showDateTime($accepteddata->created_at, ('d M Y'))}}</span><br>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Buyer's Detail</span><br>
                <span >Name: {{$buyer->username}}</span><br>
                <span >Address:  {{$buyer->country}}</span><br>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-3">
                <span class="font-weight-bold">Seller's Detail</span><br>
                <span >Name: <a href="{{route('profile', [slug($seller->username), encrypt($seller->id)])}}">{{$seller->username}}</a></span><br>
                <span >Address: {{$seller->country}}</span><br>
            </div>
            <style type="text/css">
            .table tr th, .table tr td {

            padding: 5px 8px !important;
            vertical-align: top;
            }
            </style>
            <div class="col-lg-12 mt-4">
                <table class="table ">

                    <tbody>


                        @if($job->budgettype == 'per_hour')
                        @php
                        @$counteroffer=DB::table('seller_counter_offers')->where('offer_id',$accepteddata->offer_id)->first();
                        @$counterofferdata=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->first();
                        $works=DB::table('job_work_delivery_houly_offer')->where('data_id',@$counterofferdata->id)->where('job_id',$accepteddata->job_id)->where('offer_id',$accepteddata->offer_id)->get();
                        $allwork=DB::table('job_work_delivery_houly_offer')->where('data_id',@$counterofferdata->id)->where('job_id',$accepteddata->job_id)->where('offer_id',$accepteddata->offer_id)->where('status',5)->get();
                        $total=$allwork->sum('amount');
                        @endphp
                        <tr>
                            <th  scope="row">Particulars</th>
                            <th  scope="row">Rate ($)</th>
                            <th  scope="row">Quantity</th>
                            <th  scope="row">Unit</th>
                            <th  scope="row">Amount ($)</th>

                        </tr>
                        @foreach($works as $work)
                        <tr>
                            <td>{{$work->title}} {{showDateTime($work->date_start, ('d M Y'))}} - {{showDateTime($work->date_end, ('d M Y'))}}</td>
                            <td>{{$counterofferdata->amount}}</td>
                            <td> {{$work->time}}.{{$work->minutes}}</td>
                            <td>hourly</td>
                            @if($work->status == 5)
                            <td>{{$work->total_cost}}</td>
                            @else
                            <td>0</td>
                            @endif

                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Invoice Total</td>
                            <td style="border-bottom:1px solid black;border-top:1px solid black;">${{$total}}</td>

                        </tr>
                        <!--  <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->amount)}} /hour</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Accepted amount:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount(@$counterofferdata->amount)}} /hour</td>

                        </tr> -->

                        @else

                        @php
                        @$counteroffer=DB::table('seller_counter_offers')->where('offer_id',$accepteddata->offer_id)->first();
                        @$counterofferdata=DB::table('seller_counter_offers_data')->where('disputed_amount','0')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->get();
                        $counterofferdataorg=DB::table('seller_counter_offers_data')->where('counter_offer_id',$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->get();
                        $payed=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->where('status','5')->where('disputed_amount','0')->get();
                        $payed1=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->where('status','5')->where('disputed_amount','!=','0')->get();


                        $all=(@$payed1->sum('disputed_amount')+$payed->sum('amount'));
                        $Jobname=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
                        @endphp
                        <tr>
                            <th  scope="row">Description</th>
                            <th  scope="row">Unit cost</th>
                            <th  scope="row">Qty/HR Rate</th>
                            <th  scope="row">Amount</th>

                        </tr>
                        @foreach($counterofferdataorg as $cd)
                        <tr>
                            <td>
                                {{$cd->title}}
                            </td>
                            <td>$ {{$cd->amount}}</td>
                            <td>1</td>
                            @if($cd->status == 5)
                            @if($cd->disputed_amount == 0)
                            <td>$ {{$cd->amount}}</td>
                            @else
                            <td>$ {{$cd->disputed_amount}}</td>
                            @endif
                            @else
                            <td>$ 0</td>
                            @endif
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Invoice Total</td>
                            <td style="border-bottom:1px solid black;border-top:1px solid black;">$ {{$all}}</td>

                        </tr>


                        @endif


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


                    </tbody>
                </table>
            </div>
            <div class="col-lg-12 mt-5">
                <p class="font-weight-bold">This is a computer-generated invoice.</p>
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
