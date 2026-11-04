@extends($activeTemplate.'layouts.wiserlance.dashboard-layout')
@section('content')
<div class="dashboard-content">
					<div class="dashboard-tlbar d-block mb-5">
						<div class="row">
							<div class="colxl-12 col-lg-12 col-md-12">
								<h1 class="ft-medium">Freelancer Membership Packages</h1>
								<nav aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item text-muted"><a href="#">Home</a></li>
										<li class="breadcrumb-item text-muted"><a href="#">Dashboard</a></li>
										<li class="breadcrumb-item"><a href="#" class="theme-cl">Freelancer Membership Packages</a></li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					
					<div class="dashboard-widg-bar d-block">
						<div class="row mb-5">
 @foreach ($membership as $key=>$m)
<div class="col-lg-4 col-md-4 col-sm-12 col-12">
<div class="priceboxnew">
	
	<h4 class="mb-4 priclr1">{{$m->title}}</h4>
	<sup class="sup">$</sup>&nbsp<span class="sppr1 font-weight-bold">{{$m->price}}</span>&nbsp<sub class="sppr2">Price</sub><br>
	<img src="{{asset('assets/wiserlance/assets/img/plan/pricing-free.jpg')}}"  class="img-fluid pricingimg" alt="">
	<!-- <p class=" mt-3" >Price : ${{$m->price}}</p> -->
	<p class="" >Platform Fee <span class="sup">: {{$m->fee}}%</span></p>
	<p class="" >Number of Bids <span class="sup">: {{$m->bids}}</span></p>
	<p class="" >Normal Services<span class="sup">: {{$m->normal_services}}</span></p>
	<p class="" >Priority Services<span class="sup">: {{$m->priority_services}}</span></p>
	<p class="" >Featured Services<span class="sup">: {{$m->featured_services}}</span></p>
	<p class="" >Profile Display<span class="sup">: {{$m->profile_display}}</span></p>
	<p class="" >Portfolio Size<span class="sup">: {{$m->portfolio_size}}</span></p>
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

	@if($m->default == 0)
                                        @if($currentmembership_id == $m->id)

                                            <p class="text-danger">Current Membership</p>
                                        @else
	<a href="{{url('/user/seller/payment?c_id='.$currentmembership_id.'&id='.$m->id)}}" class="btn stretched-link borders">Get Started<i class="lni lni-arrow-right ml-2"></i></a>
	 @endif
                                    @endif
</div>
</div>
@endforeach

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
