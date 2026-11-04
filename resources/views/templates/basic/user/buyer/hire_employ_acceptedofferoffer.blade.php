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
	<div class="dashboard-tlbar d-block mb-5">
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
	</div>

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
$accepted2 = DB::table('user_offers')->where('job_id', $singlejob->id)->where('buyer_offer_responce','2')->first();
                @endphp
                <!-- <a href="{{route('user.buyer.hire.employ')}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium " >Open jobs</a> -->
                @if(@$accepted1->seller_offer_responce == '2')
                <a href="{{route('user.buyer.hire.employ.bids',encrypt($singlejob->id))}}" class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium " >Bids</a>
                @elseif(@$accepted2->buyer_offer_responce == '2')
                <a href="{{route('user.buyer.hire.employ.offers',encrypt($singlejob->id))}}" class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium ml-2" >Offers</a>
                @endif
                  @if(@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                 @if(@$accepted1->seller_offer_responce == '2')
                 <a href="{{route('user.buyer.hire.employ.acceptedbidoffer',encrypt($singlejob->id))}}" class="btn rounded theme-bg text-light fs-sm ft-medium ml-2" >Accepted Bid</a>
                 @elseif(@$accepted2->buyer_offer_responce == '2')
                 <a href="{{route('user.buyer.hire.employ.acceptedbidoffer',encrypt($singlejob->id))}}" class="btn rounded theme-bg text-light fs-sm ft-medium ml-2" >Accepted Offer</a>
                 @endif
                 @endif

                @if(@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                @if(@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                <a href="{{route('user.buyer.hire.employ.timesheet',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Hourly Timesheet</a>
                @else
                <a href="{{route('user.buyer.hire.employ.workspace',encrypt($singlejob->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Workspace</a>
                @endif
                <!-- <a href="{{route('user.buyer.hire.employ.hourlytimesheet')}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Timesheet(Hourly)</a> -->
                <!-- <a href="{{route('user.buyer.hire.employ.completed')}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Completed</a>
                <a href="{{route('user.buyer.hire.employ.closed')}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Closed</a> -->
                <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Chat with seller</a>
                @endif
			</div>

		</div>



		@php
		$job=DB::table('jobs')->where('id',$singlejob->id)->first();
		$seller=DB::table('users')->where('id',$acceptedoffer->seller_id)->first();
		@endphp
		 <div class="row" >
            <div class="col-lg-12">
                <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                    <tbody>
                        <tr>
                            <th style="width:20%;" scope="row">Title:
                                <!-- <img src80https://beta.bizlanceguru.com/wiselancer/freelance%20dshboard/assets/img/about-1.png" class="img-fluid" style="width:70%;" alt=""> -->
                            </th>
                            <td style="width:85%;"><a href="#">{{$job->title}}</a></td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Description:</th>
                            <td style="width:80%;">{{$job->description}}</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->amount)}}</td>

                        </tr>

                        <tr>
                            <th style="width:20%;" scope="row">Pending Payment:</th>
                            <td style="width:80%;text-align: right;">$ 0</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Paid:</th>
                            <td style="width:80%;text-align: right;">$ 0</td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Remaining:</th>
                            <td style="width:80%;text-align: right;">$ 0</td>

                        </tr>

                        <tr>
                            <th style="width:20%;" scope="row">Start Date:</th>
                            <td style="width:80%;text-align: right;">{{showDateTime($accepteddata->created_at, ('d M Y'))}}</td>

                        </tr>
                        <!-- <tr>
                            <th style="width:20%;" scope="row">Delivery Days:</th>
                            <td style="width:80%;text-align: right;">04 January 2023</td>

                        </tr> -->

                        <tr>
                            <th style="width:20%;" scope="row">Working Status :</th>
                            <td style="width:80%;text-align: right;">
                            	<!-- <span class="badge badge-primary">@lang('Pending')</span> -->
                            </td>

                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Status :</th>
                            <td style="width:80%;text-align: right;">
                            	@if($job->job_status == '1')
                            	<span class="badge badge-primary">@lang('Assigned')</span>
                            	@elseif($job->job_status == '2')
                            	<span class="badge badge-success">@lang('Completed')</span>
                            	@endif
                            </td>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>



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
