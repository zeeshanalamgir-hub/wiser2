 @extends($activeTemplate.'layouts.wiserlance.dashboard-layout-job')
@section('content')

<style type="text/css">
.star-rating {
display:flex;
flex-direction: row-reverse;
font-size:1.5em;
justify-content:left;
text-align:center;
width:5em; 
margin-top: -10px;
}
.star-rating .stars {
display:none !important;c
}
.star-rating label {
color:#ccc; 
cursor:pointer;
}
.star-rating :checked ~ label {
color:#f90; 
}
.star-rating label:hover,
.star-rating label:hover ~ label {
color:#fc0;
}
</style>
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
                @$review=DB::table('review_ratings')->where('data_id',$accepteddata->id)->where('type','offer')->first();
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
                                <label class="mt-3">Rating</label><br>
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
                <a href="{{route('user.job.vacancy.invoicetab',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Invoice</a>

                 <a href="{{route('user.job.vacancy.ratingtab',encrypt($singlejob->id))}}" class="btn rounded theme-bg theme-cl fs-sm ft-medium ml-2" >Rating & review</a>
                
				@endif
			</div>
			
		</div>
		@if(@$acceptedoffer)
		@php
		$job=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
		$seller=DB::table('users')->where('id',$accepteddata->seller_id)->first();
		$buyer=DB::table('users')->where('id',$job->user_id)->first();
		$review=DB::table('review_ratings')->where('data_id',@$accepteddata->id)->where('type','offer')->first();
		$reviewcount=DB::table('review_ratings')->where('data_id',@$accepteddata->id)->where('type','offer')->count();
                $reviewer=DB::table('users')->where('id',@$review->user_id)->first();
		@endphp
		
		@if($reviewcount > 0)
		<div class="row bg-white p-3 mt-3">
			<div class="col-lg-12">
				<h5>Review by ">{{$buyer->username}}</h5>
				<span>From: <span class="font-weight-bold">{{$buyer->username}}</span></span> &nbsp <span>To: <span class="font-weight-bold">{{$seller->username}}</span></span><br>
				<div class="mt-1">
				<span>Reason: <span class="font-weight-bold">{{$review->reason}}</span></span> 
				</div>
				<div class="mt-2">
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
                                </div>
                                <style type="text/css">
                                	.toggled-on .toggle-content {
    display: block;
}


.toggled-on .fa-angle-down {
    display: none; 
}

.toggle-title {
    cursor: pointer;
    position: relative;
    color: blue;
}

.toggle-title i {
    font-size: 13px; 
    color: blue;
}

.toggled-off .toggle-content {
    display: none; 
}

.toggled-off .fa-angle-up {
    display: none; 
}
                                </style>
                                <div class="toggled-off mt-2 mb-2">
  <div class="toggle-title">
  	<a>See all skills rating</a>
    <i class="fa fa-angle-down fa-fw"></i> 
    <i class="fa fa-angle-up fa-fw"></i> 
  </div>
  <!-- end toggle-title --->
  <div class="toggle-content"> 
  	<label style="margin-top: 10px;">Skills</label><br>
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

  </div>
  <!-- end section-toggle--> 
</div>
<!-- end toggle-on-->
<span class="font-weight-bold ">Buyer Comment</span>
                                <p>{{@$review->review}}</p>

<h5>Your FeedBack</h5>
 @if(@$review->reply == '')
                                <span class="font-weight-bold "> Your Reply</span>
                                <p>Not replyed yet</p>
                                @else
                                <span class="font-weight-bold ">Your Reply</span>
                                <p>{{@$review->reply}}</p>
                                @endif
                                <a  data-toggle="modal" data-target="#replyModal" href="javascript:void(0);"  class="contractBtn btn rounded bg-dark text-white border fs-sm ft-medium mr-2" ><i class="fa fa-edit"></i>@lang('Feedback')</a>
			</div>
		</div>
@else
<div class="row bg-white p-3 mt-3">
<div class="col-lg-12">
	<h5 class="text-center">No Review Yet</h5>
</div>
</div>
@endif

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
	<script type="text/javascript">
		    // toggle class show hide text section
$(document).on('click', '.toggle-title', function() {
    $(this).parent()
        .toggleClass('toggled-on')
        .toggleClass('toggled-off');
});

	</script>
	@endsection