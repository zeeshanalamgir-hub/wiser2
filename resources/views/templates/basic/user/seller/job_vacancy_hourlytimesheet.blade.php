@extends($activeTemplate.'layouts.wiserlance.dashboard-layout')
@section('content')
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog  " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Service Review')</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                @php
                @$review=DB::table('review_ratings')->where('data_id',$accepteddata->id)->where('type','bid')->first();
                $reviewer=DB::table('users')->where('id',@$review->user_id)->first();
                @endphp
                <form method="POST" action="{{route('user.rating.reply.service')}}">
                    @csrf
                    <input type="hidden" name="review_id" value="{{@$review->id}}">
                    <div class="modal-body">

                        <h5>Rating & review</h5>
                        <span class="font-weight-bold">Review by:
                            <a href="{{route('profile', [slug(@$reviewer->username), encrypt(@$reviewer->id)])}}">
                                @if(@$reviewer->image == '')
                                <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg" class="img-fluid circle mr-2" width="40" alt="" >
                                @else
                                <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. @$reviewer->image,'profile_image') }}" alt="{{__(@$reviewer->username)}}" class="img-fluid circle mr-2 " style="border-radius: 50%;height: 40px;width: 40px;border: 2px solid #e5e5e5;">
                                @endif
                                </span><span>{{@$reviewer->firstname}} {{@$reviewer->lastname}}</span></a><br>

                                <label class="mt-3">Rating</label><br>
                                @if(@$review->rating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <span style="color:gold;">5</span></span>
                                @elseif(@$review->rating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <span style="color:gold;">4</span></span>
                                @elseif(@$review->rating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">3</span></span>
                                @elseif(@$review->rating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">2</span></span>
                                @elseif(@$review->rating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">1</span></span>
                                @endif
                                <br>
                                <label class="mt-3">Skill</label><br>
                                @if(@$review->skill == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <span style="color:gold;">5</span></span>
                                @elseif(@$review->skill == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <span style="color:gold;">4</span></span>
                                @elseif(@$review->skill == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">3</span></span>
                                @elseif(@$review->skill == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">2</span></span>
                                @elseif(@$review->skill == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">1</span></span>
                                @endif
                                <br>
                                <label style="margin-top: 10px;">Seller communication level</label><br>
                                @if(@$review->communicationrating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <span style="color:gold;">5</span></span>
                                @elseif(@$review->communicationrating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <span style="color:gold;">4</span></span>
                                @elseif(@$review->communicationrating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">3</span></span>
                                @elseif(@$review->communicationrating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">2</span></span>
                                @elseif(@$review->communicationrating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">1</span></span>
                                @endif
                                <br><label style="margin-top: 10px;">Recommend to a friend</label><br>
                                @if(@$review->recommendrating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <span style="color:gold;">5</span></span>
                                @elseif(@$review->recommendrating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <span style="color:gold;">4</span></span>
                                @elseif(@$review->recommendrating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">3</span></span>
                                @elseif(@$review->recommendrating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">2</span></span>
                                @elseif(@$review->recommendrating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">1</span></span>
                                @endif
                                <br><label style="margin-top: 10px;">Service as described</label><br>
                                @if(@$review->describedrating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <span style="color:gold;">5</span></span>
                                @elseif(@$review->describedrating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <span style="color:gold;">4</span></span>
                                @elseif(@$review->describedrating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">3</span></span>
                                @elseif(@$review->describedrating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">2</span></span>
                                @elseif(@$review->describedrating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <i class="fa fa-star" ></i> <span style="color:gold;">1</span></span>
                                @endif
                                <br> <label style="margin-top: 10px;">Comment</label>
                                <p>{{@$review->review}}</p>
                                @if(@$review->reply == '')
                                <label > Your Reply</label>
                                <textarea rows="5" class="form-control" name="reply" placeholder="Type your reply here..." required></textarea>
                                @else
                                <label >Your Reply</label>
                                <p>{{@$review->reply}}</p>
                                @endif

                            </div>
                            @if(@$review->reply == '')
                            <div class="modal-footer">

                                <button type="submit" class="btn theme-bg text-light apply-btn rounded ">@lang('Submit')</button>
                            </div>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
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
                <form method="POST" action="{{route('user.job.send.timesheet.work.bid')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="accepted_id">
                    <input type="hidden" name="dataa_id">
                    <input type="hidden" name="work_type" value="jobfile">
                    <div class="form-group">
                        <div>
                            <label for="formFileLg" class="form-label font-weight-bold">@lang('Attachment')</label>
                            <input class="form-control form-control-lg" name="file" id="formFileLg" type="file" >
                            <small>@lang('Supported files:zip and max size:25 Mb')</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="title" class="form-control" name="title" placeholder="Title " required>
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
                                        <input type="date" name="date_start" id="date_start" class="form-control" name="Total Hours" placeholder="Total Hours" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="formFileLg" class="form-label font-weight-bold">@lang('End Date')</label>
                                        <input type="date" name="date_end" id="date_end" class="form-control" name="Total Hours" placeholder="Total Hours" required>
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
                    <input type="number" id="totalHours" class="form-control" name="Total Hours" placeholder="Total Hours" required onchange="calculateTotalAmount()">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select id="totalMinutes" class="form-control" name="minutes" required onchange="calculateTotalAmount()">
                        <option value="">Minutes</option>
                        <option value="0">0</option>
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                        <option value="60">60</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div>
        <label for="formFileLg" class="form-label font-weight-bold">@lang('You Will Get')</label>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" id="total_get" disabled class="form-control" placeholder="$ 0" >
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assuming you have the job amount available -->
            {{-- <input type="hidden" id="jobAmount" value="{{$job->amount}}"> --}}
                    <div class="form-group">
                        <!-- <button type="submit" class="btn btn-success" style="width:100%;">@lang('Submit')</button> -->
                        <button type="submit" class="btn btn-primary" onclick="return validateDates()">@lang('Submit')</button>

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
                @if(@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                @if(@$accepted1->seller_offer_responce == '2')
                <a href="{{route('user.job.vacancy.accepted_bid',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Accepted Bid</a>
                @elseif(@$accepted2->buyer_offer_responce == '2')
                <a href="{{route('user.job.vacancy.accepted_bid',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Accepted Offer</a>
                @endif
                @endif
                @if(@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                @if(@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                <a href="{{route('user.job.vacancy.hourlytimesheet',encrypt($singlejob->id))}}" class="btn rounded theme-bg text-light fs-sm ft-medium ml-2" >Hourly Timesheet</a>
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
        @php
        $seller=DB::table('users')->where('id',$accepteddata->seller_id)->first();
        $counteroffer=DB::table('buyer_counter_offers')->where('bid_id',$accepteddata->bid_id)->first();
        $counterofferdata=DB::table('buyer_counter_offers_data')->where('counter_offer_id',$counteroffer->id)->where('bid_id',$accepteddata->bid_id)->first();
        $works=DB::table('job_work_delivery_houly_bid')->where('data_id',@$counterofferdata->id)->where('job_id',$accepteddata->job_id)->where('bid_id',$accepteddata->bid_id)->get();
        @endphp

        <div class="workboxmain mt-3">
            <div class="row">
                <div class="col-lg-12 topboxwork">
                    @if($accepteddata->is_endcontract == 1)
                        <a data-toggle="modal" data-target="#replyModal" style="border: 1px solid #ea2b33 !important;" href="javascript:void(0);"  class=" btn rounded bg-white  text-danger fs-sm ft-medium mr-2" >@lang('Contract Ended by ' ) {{@$reviewer->firstname}} {{@$reviewer->lastname}} </a>
                        @else

                        @endif
                    <a data-target="#workModal" data-dataa_id="{{@$counteroffer->id}}" data-accepted_id="{{@$accepteddata->id}}" href="javascript:void(0);" class="workBtn btn rounded theme-bg text-light fs-sm ft-medium mr-2" >Add Timesheet </a>
                </div>
                <!-- work one -->
                {{--<div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <h5>Timsheet 1 &nbsp <span style="font-size: 10px;" class="badge badge-success">@lang('payed')</span></h5>
                            <p><span class="font-weight-normal"> $20.00/hour</span>  &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span class="font-weight-normal"><i class="fa fa-clock"></i> 4 hour 10 min</span> &nbsp &nbsp <span class="font-weight-normal"><i class="fa fa-clock"></i> Cost: $200</span> &nbsp &nbsp <a href="#"><span class="font-weight-normal"><i class="fa fa-paperclip"></i> </span></a>  </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right" style="padding-top: 12px;">

                            <!-- <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a> -->
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->


                        </div>
                    </div>

                </div>
                <div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <h5>Timsheet 2 &nbsp <span style="font-size: 10px;" class="badge badge-danger">@lang('Declined')</span></h5>
                            <p><span class="font-weight-normal"> $20.00/hour</span>  &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span class="font-weight-normal"><i class="fa fa-clock"></i> 4 hour 10 min</span> &nbsp &nbsp <span class="font-weight-normal"> Cost: $200</span>&nbsp &nbsp &nbsp <a href="#"><span class="font-weight-normal"><i class="fa fa-paperclip"></i> </span></a>  </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right" style="padding-top: 12px;">

                            <!-- <a href="#" class=" btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-trash"></i> </a> -->
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->


                        </div>
                    </div>
                    <div class="row showresponce " >



                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">

                            <p><span class="font-weight-bold">Reason:</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl, vestibulum in lectus a, lobortis elementum velit.   <a href=""><i class="fa fa-paperclip"></i></a> </p>
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a> -->

                        </div>
                    </div>

                </div>--}}

                @if($works->count() > 0)
                @foreach($works as $work)
                <div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-12">
                            <h5>{{$work->title}} &nbsp
                            @if($work->status == '2')
                            <span style="font-size: 10px;" class="badge badge-info">@lang('Delivered')</span>
                            @elseif($work->status == '1')
                            <span style="font-size: 10px;" class="badge badge-success">@lang('accepted')</span>
                            @elseif($work->status == '3')
                            <span style="font-size: 10px;" class="badge badge-danger">@lang('declined')</span>
                            @elseif($work->status == '5')
                            <span style="font-size: 10px;" class="badge badge-success">@lang('Payed')</span>
                            @elseif($work->status == '6')
                            <span style="font-size: 10px;" class="badge badge-danger">@lang('dispute')</span>
                            @endif
                            </h5>
                            <?php
                                // Convert hours and minutes to decimal hours
                                $totalHours = $work->time + ($work->minutes / 60);

                                // Calculate the total cost
                                $totalCost = $totalHours * $counterofferdata->amount;

                                // Format the total cost to two decimal places
                                $totalCostFormatted = number_format($totalCost, 2);
                                ?>

                                <p>
                                    <span class="font-weight-normal">
                                        <span>$</span>{{$counterofferdata->amount}}/hour
                                    </span> &nbsp &nbsp
                                    <span class="font-weight-normal">
                                        <i class="lni lni-calendar"></i> {{showDateTime($work->date_start, ('d M Y'))}} - {{showDateTime($work->date_end, ('d M Y'))}}
                                    </span> &nbsp &nbsp
                                    <span class="font-weight-normal">
                                        <i class="fa fa-clock"></i> {{$work->time}} hour {{$work->minutes}} min
                                    </span> &nbsp &nbsp
                                    <span class="font-weight-normal">
                                        Cost: <span>$</span>{{$totalCostFormatted}}
                                    </span>
                                </p>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-12 text-right" style="padding-top: 12px;">

                        <!-- <a href="#" class=" btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-trash"></i> </a> -->
                        <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->
                        @if($work->status == '5')
                            <a href="javascript:void(0);" class="btn rounded theme-bg text-light  fs-sm ft-medium mr-2" >Payed </a>
                            @endif

                    </div>
                    <div class="row showresponce " >



                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">

                            <p><span class="font-weight-bold">Detail:</span>{{$work->details}} @if(@$work->file == '')
                            @else
                            <a title="Download attachment" href="{{route('user.timesheet.bid.file.download', encrypt(@$work->id))}}"><i class="fa fa-paperclip"></i></a>
                        @endif  </p>
                        <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a> -->

                    </div>
                    @if($work->status == '3')

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2" style="background:#F6F8F7;padding: 10px;border-radius: 10px !important;">
                        <p><span class="font-weight-bold">Reason:</span>{{$work->reason}}
                        @if(@$work->response_file == '')
                        @else
                        <a title="Download attachment" href="{{route('user.timesheet.response.bid.file.download', encrypt(@$work->id))}}"><i class="fa fa-paperclip"></i></a>
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>


    </div>
    @endforeach
    @else
    <div class="col-lg-12 innerbox">
        <div class="row">
            <div class="col-lg-12">
                <h6>No Timesheet Found</h6>
            </div>
        </div>


    </div>
    @endif

</div>
</div>
</div>
@endsection
@section('script')
<script>
    // JavaScript function to validate start and end dates
    function validateDates() {
        var startDate = document.getElementById('date_start').value;
        var endDate = document.getElementById('date_end').value;

        // Convert date strings to Date objects
        var startDateObj = new Date(startDate);
        var endDateObj = new Date(endDate);

        // Compare start and end dates
        // if (startDateObj >= endDateObj) {
        //     alert('End date must be greater than start date.');
        //     return false;
        // }

        return true; // Allow form submission
    }

    // Function to calculate the total amount based on hours, minutes, and job amount
    function calculateTotalAmount() {
    let hours = parseFloat(document.getElementById('totalHours').value) || 0;
    let minutes = parseFloat(document.getElementById('totalMinutes').value) || 0;
    let jobAmount = parseFloat(document.getElementById('jobAmount').value) || 0;

    let totalDuration = hours + (minutes / 60);

    // Calculate total amount
    let totalAmount = totalDuration * jobAmount;

    // Update the "You Will Get" field
    document.getElementById('total_get').value = '$ ' + totalAmount.toFixed(2);

    // Log the calculated amount to the console
    console.log('Total Amount:', totalAmount);
}

    // Attach event listeners to the input fields to recalculate on change
    document.getElementById('totalHours').addEventListener('input', calculateTotalAmount);
    document.getElementById('totalMinutes').addEventListener('input', calculateTotalAmount);
    document.getElementById('jobAmount').addEventListener('input', calculateTotalAmount);

    // Initial calculation on page load
    calculateTotalAmount();
</script>

<script>
"use strict";
$('.workBtn').on('click', function() {
var modal = $('#workModal');
modal.find('input[name=dataa_id]').val($(this).data('dataa_id'))
modal.find('input[name=accepted_id]').val($(this).data('accepted_id'))
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
