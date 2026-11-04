@extends($activeTemplate.'layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="dashboard-content">
					<div class="dashboard-tlbar d-block mb-5">
						<div class="row">
							<div class="colxl-12 col-lg-12 col-md-12">
								<h1 class="ft-medium">Payment gateway integration</h1>
								<nav aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item text-muted"><a href="#">Home</a></li>
										<li class="breadcrumb-item text-muted"><a href="#">Dashboard</a></li>
										<li class="breadcrumb-item"><a href="#" class="theme-cl">Payment gateway integration</a></li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					
					<div class="dashboard-widg-bar d-block">
						<form method="post" action="{{ url('/user/buyer/payment') }}">
                                @csrf
                                <input name="current_id" value="{{$current_id}}" type="hidden"/>
                                <input name="new_id" value="{{$new_id}}" type="hidden"/>
                                @foreach ($membership as $m)
                                <input type="hidden" name="type" value="{{$m->type}}"/>
						<div class="row mb-5" style="margin-bottom: 30px;">

                               
<div class="col-lg-4 col-md-4 col-sm-12 col-12">
<div class="priceboxnew">
	
	<h4 class="mb-4 priclr1">{{$m->title}}</h4>
	<sup class="sup">$</sup>&nbsp<span class="sppr1 font-weight-bold">{{$m->price}}</span>&nbsp<sub class="sppr2">Price</sub><br>
	<img src="{{asset('assets/wiserlance/assets/img/plan/pricing-free.jpg')}}"  class="img-fluid pricingimg" alt="">

	<!-- <p class=" mt-3" >Price : ${{$m->price}}</p> -->
	<p class="" >Platform Fee <span class="sup">: {{$m->fee}}%</span></p>
	<p class="" >Normal Jobs<span class="sup">: {{$m->normal_jobs}}</span></p>
	<p class="" >Priority Jobs<span class="sup">: {{$m->priority_jobs}}</span></p>
	<p class="" >Featured Jobs<span class="sup">: {{$m->featured_jobs}}</span></p>
	
	
	<p class="" >Recruiting assistance<span class="sup">: {{$m->r_assistance == 0 ? "No" : "Yes"}} </span></p>
	<p class="" >Safe Payment<span class="sup">: {{$m->safe_payment == 0 ? "No" : "Yes"}}</span></p>
	<p class="" >Support<span class="sup">: 
@if ($m->support == 1)
                                            Basic
                                            @elseif($m->support == 2)
                                            Standard
                                            @elseif($m->support == 3)
                                            Premium
                                            @else
                                            No
                                            @endif
	</span></p>

	

</div>
</div>
@endforeach

<div class="col-lg-8 col-md-8 col-sm-12 col-12">
<div class="priceboxnew">

	 <form method="POST" action="{{ url('/user/buyer/payment') }}">
                @csrf
                <div class="modal-body">
                    <input name="current_id" value="{{$current_id}}" type="hidden"/>
                   <input name="new_id" value="{{$new_id}}" type="hidden"/>
                    <div class="form-group">
                        <h5>@lang('How you want to pay')</h5>
                        <select class="form-control" name="payment">
                            <option value="wallet">{{__($general->sitename)}} @lang('wallet')</option>
                            <!--<option value="checkout">@lang('Checkout')</option>-->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">

                    <button  type="submit" class="btn stretched-link borders">@lang('Pay')<i class="lni lni-arrow-right ml-2"></i></button>
                </div>
            
	</div></div>
</form>
<p style="color: #F6F8F7;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

</div>
					</div>	
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Payment You Order')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" action="{{ url('/user/seller/payment') }}">
                @csrf
                <div class="modal-body">
                    <input name="current_id" value="{{$current_id}}" type="hidden"/>
                                <input name="new_id" value="{{$new_id}}" type="hidden"/>
                    <div class="form-group">
                        <h5>@lang('How you want to pay')</h5>
                        <select class="form-control" name="payment">
                            <option value="wallet">{{__($general->sitename)}} @lang('wallet')</option>
                            <!--<option value="checkout">@lang('Checkout')</option>-->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--base btn-rounded text-white">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


    @section('script')

<script> 
        (function($){
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery);

    </script>

        @endsection
