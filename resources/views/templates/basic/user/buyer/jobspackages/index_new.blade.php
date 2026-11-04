  @extends($activeTemplate.'layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="dashboard-content">
					<div class="dashboard-tlbar d-block mb-5">
						<div class="row">
							<div class="colxl-12 col-lg-12 col-md-12">
								<h1 class="ft-medium">Buyer Bids Packages</h1>
								<nav aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item text-muted"><a href="#">Home</a></li>
										<li class="breadcrumb-item text-muted"><a href="#">Dashboard</a></li>
										<li class="breadcrumb-item"><a href="#" class="theme-cl">Buyer Bids Packages</a></li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					
					<div class="dashboard-widg-bar d-block">
						<div class="row mb-5">


@foreach ($jobspakages as $key=>$m)
<div class="col-lg-4 col-md-4 col-sm-12 col-12">
<div class="priceboxnew">
	
	<h4 class="mb-4 priclr1">{{$m->title}}</h4>
	<!-- <sup class="sup">$</sup>&nbsp<span class="sppr1 font-weight-bold">23</span>&nbsp<sub class="sppr2">/Service</sub><br> -->
	<img src="{{asset('assets/wiserlance/assets/img/plan/pricing-free.jpg')}}"  class="img-fluid pricingimg" alt="">
	<p class=" mt-3" >Type :  @if ($m->type == 2)
                                            Featured
                                            @elseif($m->type == 1)
                                            Priority
                                            @else
                                                Normal    
                                            @endif</p>
	<p class=" mt-3" >Jobs : {{$m->jobs}}</p>
	<p class=" mt-3" >Price : ${{$m->price}}</p>
	
	<a href="{{url('/user/buyer/jobspayment?id='.$m->id)}}" class="btn stretched-link borders">Get<i class="lni lni-arrow-right ml-2"></i></a>
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
