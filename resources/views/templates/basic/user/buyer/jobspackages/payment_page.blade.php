@extends($activeTemplate.'layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="dashboard-content">
	<div class="dashboard-tlbar d-block mb-5">
		<div class="row">
			<div class="colxl-12 col-lg-12 col-md-12">
				<p class="mb-0 mt-3 text-bc">
					<a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
						<i class="fa-solid fa-house fs-14"></i>
					</a>
					<i class="fa-solid fa-chevron-right fs-12 px-1"></i>
					<span id="current-breadcrumb">Upgrade Job Package Payment</span>
				</p>
			</div>
		</div>
	</div>
   
	<style type="text/css">
		.border1 {
			background-color: #0cb48c !important;
		}
		.border1 h4 {
			color: white !important;
			font-size: 12px !important;
			font-weight: 700;
		}
		.border h4 {
			font-size: 14px;
		}
	</style>

	<div class="dashboard-widg-bar d-block">
		<input name="id" value="{{ $id }}" type="hidden"/>

		<div class="row mb-5">
			<div class="col-md-3 mt-3">
				<div class="row">
					<div class="col-md-12 pt-2">
						<h4 style="color: transparent;">p</h4>
					</div>
					<div class="col-md-12 border pt-2 border1 pl-1"><h4 class="text-dark">Price</h4></div>
					<div class="col-md-12 border pt-2 border1 pl-1"><h4 class="text-dark">Regular Jobs</h4></div>
					<div class="col-md-12 border pt-2 border1 pl-1"><h4 class="text-dark">Featured Jobs</h4></div>
					<div class="col-md-12 border pt-2 border1 pl-1"><h4 class="text-dark">Invites</h4></div>
				</div>
			</div>

			@foreach ($bidspakages as $key => $m)
			<div class="col-md-3 mt-3"> 
				<div style="height: 60px;width: 60px;border-radius: 50px;background: transparent;text-align: center;color:#fa7610 !important;border: 1px solid #fa7610 !important;margin: auto;margin-top: -26px;" class="pt-1">
					<i class="fa fa-building"></i>
				</div>
				<div class="mb-2 py-2" style="width:100%;margin: auto;background: #0cb48c !important;text-align: center;margin-top: -35px;color: white;border-radius: 5px;">
					{{ $m->title }}
				</div>
				<div class="row text-center">
					<div class="col-md-12 border pt-2" style="border-top:transparent !important;"><h4>${{ $m->price }}</h4></div>
					<div class="col-md-12 border pt-2"><h4>{{ $m->jobs }}</h4></div>
					<div class="col-md-12 border pt-2"><h4>{{ $m->f_jobs }}</h4></div>
					<div class="col-md-12 border pt-2"><h4>{{ $m->invites }}</h4></div>
				</div>
			</div>
			@endforeach

			{{-- ✅ Only ONE form, properly closed --}}
			<div class="col-lg-6 col-md-6 col-sm-12 col-12">
				<div class="priceboxnew">
					<form method="POST" action="{{ url('/user/buyer/jobspayment') }}">
						@csrf
						<input name="id" value="{{ $id }}" type="hidden"/>
						<div class="modal-body">
							<div class="form-group">
								<h5>@lang('How you want to pay')</h5>
								<select class="form-control" name="payment">
									<option value="wallet">{{ __($general->sitename) }} @lang('wallet')</option>
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success borders">
								@lang('Pay') <i class="lni lni-arrow-right ml-2"></i>
							</button>
						</div>
					</form>
				</div>
			</div>
			{{-- ✅ End single form --}}
		</div>
	</div>
</div>
@endsection

