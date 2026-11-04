@extends($activeTemplate.'layouts.wiserlance.dashboard-layout')
@section('content')
<div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Send your work duration')</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('user.jobwork.upload')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="work_type" value="jobfile">
                    <div class="form-group">
                        <div>
                            <label for="formFileLg" class="form-label font-weight-bold">@lang('Attachment')</label>
                            <input class="form-control form-control-lg" name="file" id="formFileLg" type="file" required="">
                            <small>@lang('Supported files:zip and max size:25 Mb')</small>
                        </div>
                    </div>
                    <div class="form-group">
                            <input type="text" name="" class="form-control" name="title" placeholder="Title " required>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Details ...." required></textarea>
                        </div>
                         <div class="form-group">
                            <div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="formFileLg" class="form-label font-weight-bold">@lang('Start Date')</label>
                                            <input type="date" name="" class="form-control" name="Total Hours" placeholder="Total Hours" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="formFileLg" class="form-label font-weight-bold">@lang('End Date')</label>
                                            <input type="date" name="" class="form-control" name="Total Hours" placeholder="Total Hours" required>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Total duration')</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="number" name="" class="form-control" name="Total Hours" placeholder="Total Hours" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <select class="form-control">
                                               <option>Minutes</option>
                                               <option>5</option>
                                               <option>10</option>
                                               <option>15</option>
                                               <option>20</option>
                                           </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="width:100%;">@lang('Submit')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
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
                 <a href="{{route('user.job.vacancy.accepted_bid',encrypt($singlejob->id))}}" class="btn rounded theme-bg text-light fs-sm ft-medium ml-2" >Accepted Bid</a>
                 @elseif(@$accepted2->buyer_offer_responce == '2')
                 <a href="{{route('user.job.vacancy.accepted_bid',encrypt($singlejob->id))}}" class="btn rounded theme-bg text-light fs-sm ft-medium ml-2" >Accepted Offer</a>
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
                <a href="{{route('user.job.vacancy.invoicetab',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Invoice</a>

                 <a href="{{route('user.job.vacancy.ratingtab',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Rating & review</a>

                @endif
            </div>

        </div>

        @if(@$acceptedbid)
        @php
        $job=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
        $buyer=DB::table('users')->where('id',$accepteddata->buyer_id)->first();
        @endphp
         <div class="row" >
            <div class="col-lg-12">
                <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                    <tbody>
                        <tr>
                                <a class="float-right mb-2" style="text-decoration: underline;" href="{{url('generate-invoice-pdftwo',$singlejob->id)}}">Download Invoice</a>
                            </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Title:
                                <!-- <img src80https://beta.bizlanceguru.com/wiselancer/freelance%20dshboard/assets/img/about-1.png" class="img-fluid" style="width:70%;" alt=""> -->
                            </th>
                            <td style="width:85%;"><a href="#">{{$job->title}}</a></td>

                        </tr>

                        @if($job->budgettype == 'per_hour')
                        @php
                          @$counteroffer=DB::table('buyer_counter_offers')->where('bid_id',$accepteddata->bid_id)->first();
                @$counterofferdata=DB::table('buyer_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->first();
                        @endphp
                        <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->maxBudget)}} /hour</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Accepted amount:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount(@$counterofferdata->amount)}} /hour</td>

                        </tr>

                        @else
                        <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->amount)}}</td>

                        </tr>
                        @php
                        @$counteroffer=DB::table('buyer_counter_offers')->where('bid_id',$accepteddata->bid_id)->first();

                         @$counterofferdata=DB::table('buyer_counter_offers_data')->where('disputed_amount','0')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->get();

                         $payed=DB::table('buyer_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->where('status','5')->where('disputed_amount','0')->get();

                         $extra_cut=DB::table('buyer_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->where('status','5')->where('disputed_amount','!=','0')->get();
                         @$dispute_pay=@$extra_cut->sum('disputed_amount');
                         $rem=(@$counterofferdata->sum('amount')-$payed->sum('amount'));
                          $counterofferdataorg=DB::table('buyer_counter_offers_data')->where('counter_offer_id',$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->get();
                         $Jobname=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
                        @endphp
                         @foreach($counterofferdataorg as $cd)

                         <tr>
                            <th style="width:20%;" scope="row">@if($counteroffer->type == 'bymilestone')
                                {{$cd->title}}
                                @elseif($counteroffer->type == 'byproject')
                                {{$Jobname->title}}
                                @endif:</th>

                            <td style="width:80%;text-align: right;">$ {{$cd->amount}}</td>


                        </tr>
                         @endforeach
                        <tr>
                            <th style="width:20%;" scope="row">Pending Payment:</th>
                            <td style="width:80%;text-align: right;">$ {{@$counterofferdata->sum('amount')}}</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Payment Recieved:</th>
                            <td style="width:80%;text-align: right;">$ {{$payed->sum('amount')}}</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Disputes Payment Recieved:</th>
                            <td style="width:80%;text-align: right;">$ {{$dispute_pay}}</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Remaining:</th>
                            <td style="width:80%;text-align: right;">$ {{$rem}}</td>

                        </tr>
                        @endif
                        <tr>
                            <th style="width:20%;" scope="row">Platform fee:</th>
                            <td style="width:80%;text-align: right;">{{$acceptedbid->cutout}} %

                            </td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Order Number:</th>
                            <td style="width:80%;text-align: right;">{{$acceptedbid->order_number}}

                            </td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Buyer:</th>
                            <td style="width:80%;text-align: right;">{{$buyer->username}}:
                                <a href="#"><span>@</span>{{$buyer->username}}</a>
                            </td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Start Date:</th>
                            <td style="width:80%;text-align: right;">{{showDateTime($accepteddata->created_at, ('d M Y'))}}</td>

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
                                @if($job->is_completed == '1')
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


@if(@$acceptedoffer)
        @php
        $job=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
        $buyer=DB::table('users')->where('id',$accepteddata->buyer_id)->first();
        @endphp
         <div class="row" >
            <div class="col-lg-12">
                <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                    <tbody>
                        <tr>
                                <a class="float-right mb-2" style="text-decoration: underline;" href="{{url('generate-invoice-pdf-offertwo',$singlejob->id)}}">Download Invoice</a>
                            </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Title:
                                <!-- <img src80https://beta.bizlanceguru.com/wiselancer/freelance%20dshboard/assets/img/about-1.png" class="img-fluid" style="width:70%;" alt=""> -->
                            </th>
                            <td style="width:85%;"><a href="#">{{$job->title}}</a></td>

                        </tr>

                         @if($job->budgettype == 'per_hour')
                        @php
                         @$counteroffer=DB::table('seller_counter_offers')->where('offer_id',$accepteddata->offer_id)->first();
                @$counterofferdata=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->first();
                        @endphp
                        <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->amount)}} /hour</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Accepted amount:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount(@$counterofferdata->amount)}} /hour</td>

                        </tr>

                        @else
                        <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->amount)}}</td>

                        </tr>

                         @php
                        @$counteroffer=DB::table('seller_counter_offers')->where('offer_id',$accepteddata->offer_id)->first();
                        @$counterofferdata=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('disputed_amount','0')->where('offer_id',$accepteddata->offer_id)->get();
                         $payed=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->where('disputed_amount','0')->where('status','5')->get();

                         $extra_cut=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->where('disputed_amount','!=','0')->where('status','5')->get();
                         @$dispute_pay=@$extra_cut->sum('disputed_amount');

                         $rem=(@$counterofferdata->sum('amount')-$payed->sum('amount'));
                        $counterofferdataorg=DB::table('seller_counter_offers_data')->where('counter_offer_id',$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->get();
                         $Jobname=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
                        @endphp
                         @foreach($counterofferdataorg as $cd)

                         <tr>
                            <th style="width:20%;" scope="row">@if($counteroffer->type == 'bymilestone')
                                {{$cd->title}}
                                @elseif($counteroffer->type == 'byproject')
                                {{$Jobname->title}}
                                @endif:</th>

                            <td style="width:80%;text-align: right;">$ {{$cd->amount}}</td>


                        </tr>
                         @endforeach
                        <tr>
                            <th style="width:20%;" scope="row">Pending Payment:</th>
                            <td style="width:80%;text-align: right;">$ {{@$counterofferdata->sum('amount')}}</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Payment Recieved:</th>
                            <td style="width:80%;text-align: right;">$ {{$payed->sum('amount')}}</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Disputes Payment Recieved:</th>
                            <td style="width:80%;text-align: right;">$ {{$dispute_pay}}</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Remaining:</th>
                            <td style="width:80%;text-align: right;">$ {{$rem}}</td>

                        </tr>
                        @endif
                        <tr>
                            <th style="width:20%;" scope="row">Platform fee:</th>
                            <td style="width:80%;text-align: right;">{{$acceptedoffer->cutout}} %

                            </td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Order Number:</th>
                            <td style="width:80%;text-align: right;">{{$acceptedoffer->order_number}}

                            </td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Buyer:</th>
                            <td style="width:80%;text-align: right;">{{$buyer->username}}:
                                <a href="#"><span>@</span>{{$buyer->username}}</a>
                            </td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Start Date:</th>
                            <td style="width:80%;text-align: right;">{{showDateTime($accepteddata->created_at, ('d M Y'))}}</td>

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
                                @if($job->is_completed == '1')
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
    <script>
    "use strict";
    $('.workBtn').on('click', function() {
    var modal = $('#workModal');
    modal.find('input[name=order_number]').val($(this).data('order_number'))
    modal.modal('show');
    });
    $('.toggle').click(function() {
    $('#target').toggle();
    });
    $('.toggle2').click(function() {
    $('#target2').toggle();
    });
    </script>
    @endsection
