 @extends($activeTemplate.'layouts.wiserlance.dashboard-layout')
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
						 <form method="post" action="{{ url('/user/seller/bidspayment') }}">
                                @csrf
                                <input name="id" value="{{$id}}" type="hidden"/>
                                @foreach ($bidspakages as $m)
						<div class="row mb-5">


<div class="col-lg-4 col-md-4 col-sm-12 col-12">
<div class="priceboxnew">
	
	<h4 class="mb-4 priclr1">{{$m->title}}</h4>
	<!-- <sup class="sup">$</sup>&nbsp<span class="sppr1 font-weight-bold">23</span>&nbsp<sub class="sppr2">/Service</sub><br> -->
	<img src="{{asset('assets/wiserlance/assets/img/plan/pricing-free.jpg')}}"  class="img-fluid pricingimg" alt="">
	<p class=" mt-3" >Bids : {{$m->bids}}</p>
	<p class=" mt-3" >Price : ${{$m->price}}</p>
	
	
</div>
</div>
<div class="col-lg-8 col-md-8 col-sm-12 col-12">
<div class="priceboxnew">

	  <form method="POST" action="{{ url('/user/seller/bidspayment') }}">
                @csrf
                <div class="modal-body">
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

</div>
@endforeach

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
