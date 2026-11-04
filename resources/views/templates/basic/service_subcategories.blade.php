@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
@php
$helpers = new \App\Classes\Helpers();
$currency_rates = $helpers->get_currency_rates();
$currencies = $helpers->get_all_currencies();
// echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
@endphp
<div class="home-banner1 margin-bottom-0" style="background:#28b661 url(assets/img/banner-2.jpg) no-repeat;height: 200px; " data-overlay="5" >
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12">
            <div class="banner_caption text-center mb-2">
                <h1 class="ft-bold mb-4">{{$cat->name}}</h1>
               <!--  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque officia exercitationem ab accusantium ex voluptatibus soluta culpa aut saepe? Delectus illo, optio quisquam inventore possimus vero, impedit quo enim eum.</p> -->
            </div>
        </div>
    </div>
</div> 
</div>
<section class="middle bg-light">
	<div class="container">
		
		<!-- Single Category -->
		
		@php
		$subcats=DB::table('sub_categories')->where('category_id',$cat->id)->get();
		@endphp
		<!-- <div class="row align-items-start ">
			<div class="col-xl-12 mb-3">
				<div class="d-block full-width mt-2">
					<h2 class="ft-medium mb-0">{{$cat->name}}</h2>
				</div>
			</div>
		</div> -->
			@foreach($subcats as $sub)
			@php
			$Services = DB::table('services')->where('sub_category_id',  $sub->id)->where('status', 1)->limit(4)->orderBy('id', 'DESC')->get();
			$services_count=DB::table('services')->where('sub_category_id',$sub->id)->where('status',1)->count();
			@endphp
			<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-12 text-left pl-2">
						<h6 class="font-weight-bold" >{{$sub->name}}</h6>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-12 text-right">
						<a href="{{route('servicesubcategories.services', [slug($sub->name), encrypt($sub->id)])}}"><button class="btn" Style="color:#069170;border: none;font-size: 12px;background: transparent;">Browse all <i class="fa fa-arrow-circle-right"></i></button></a>
					</div>

					
			</div>
			 <div class="row align-items-center">
                        <!-- Single -->
                        @if($services_count > 0)
                        @foreach($Services as $service)
                       
                        @php
                        $user=DB::table('users')->where('id',$service->user_id)->first();
                        @endphp
                         @if(@$user->id == '')

                        @else
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <div class="job_grid border rounded service-hov">
                                @if($service->priority == 2)
                                <div class="feature text-uppercase">@lang('Featured')</div>
                                @endif
                                <!--  @if($service->favorite > 0)
                                <a href="javascript:void(0)">
                                    <div class="position-absolute ab-left"><button type="button" class="p-3 border circle d-flex align-items-center justify-content-center bg-white text-danger"><i class="lni lni-heart-filled position-absolute snackbar-wishlist loveHeartAction"></i><span style="position: absolute;right:6px;top:2px;font-size:10px;">{{__($service->favorite)}}</span></button></div>
                                </a>
                                @else
                                <a href="javascript:void(0)">
                                    <div class="position-absolute ab-left"><button type="button" class="p-3 border circle d-flex align-items-center justify-content-center bg-white text-gray"><i class="lni lni-heart-filled position-absolute snackbar-wishlist loveHeartAction"></i></button></div>
                                </a>
                                @endif -->
                                {{--@if($service->optionalImage->count() > '0')
                                <div id="servicescarousel{{$service->id}}" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        @foreach($service->optionalImage as $index => $value)
                                        <li data-target="#servicescarousel" data-slide-to="{{$index}}" class="<?php echo ($index == '0') ? 'active' : ''; ?>"></li>
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($service->optionalImage as $key => $value)
                                        <div class="carousel-item <?php echo ($key == '0') ? 'active' : ''; ?>">
                                            <img class="d-block w-100" src="{{getImage('assets/images/optionalService/'.$value->image, imagePath()['optionalService']['size']) }}" alt="@lang('item-banner')" style="height:165px;object-fit: cover;">
                                        </div>
                                        @endforeach
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images1.jpeg')}}" alt="Second slide" style="max-height:165px;object-fit: cover;">
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images3.jpeg')}}" style="max-height:165px;object-fit: cover;" alt="Third slide">
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev controls" href="#servicescarousel{{$service->id}}" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next controls" href="#servicescarousel{{$service->id}}" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                @else--}}
                                <a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}"><img class="d-block w-100" src="{{getImage('assets/images/service/'.$service->image,imagePath()['service']['size'])}}" alt="@lang('Service Image')" style="height:165px;object-fit: cover;"></a>
                                {{--<!-- @endif -->--}}
                                @php 
                                $rate1=DB::table('review_ratings')->where('pro_id',$user->id)->avg('rating');

                                @endphp
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2 text-right">
                                    <div class="jbl_location" style="line-height: 4px !important;font-size:12px;color: gray !important;">Selling level <i style="color:gold;" class="fa fa-star mr-1"></i><span>{{round($rate1,0)}}</span></div>
                                </div> 
                                <div class="row no-gutters text-left pr-2">
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-3">
                                        @if($user->image == '')
                                        <div class="job_grid_thumbmb-2 ml-2" >
                                            <a href="{{route('profile', [slug($user->username), encrypt($user->id)])}}" class="d-inline-flex text-center m-auto "><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoMIkoUpzbBzDvTP_ePhyi0SsKSE57dvr89InSN1Ud_0-UDC7WRoTm3noAVg4c97VS538&usqp=CAU" class="img-fluid " style="width:width: 30px;height: 30px;border-radius: 50px;object-fit: cover;" alt="{{__($user->username)}}" /></a>
                                        </div>
                                        @else
                                        <div class="job_grid_thumbmb-2 ml-2" >
                                            <a href="{{route('profile', [slug($user->username), encrypt($user->id)])}}" class="d-inline-flex text-center m-auto circle border p-2"><img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $user->image, 'profile_image') }}" class="img-fluid circle" style="width:width: 30px;height: 30px;border-radius: 50px;object-fit: cover;" alt="{{__($user->username)}}" /></a>
                                        </div>
                                        @endif 
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-9 col-9 text-left" >
                                        <h4 class="mb-0 ft-medium medium"><a href="{{route('profile', [slug($user->username), encrypt($user->id)])}}" class="text-dark fs-md"> {{__($user->username)}}</a></h4>
                                        <div style="height: 55px !important;">
                                            <h6 class="mb-0 ft-medium medium"><a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}" class="text-dark fs-md"> {{substr($service->title,0,20)}}...</a></h6>
                                            <a style="line-height: 4px !important;font-size:12px;color: gray !important;" href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}"><p>{{substr($service->description,0,75)}}...</p></a>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="job_grid_footer " >
                                    <ul class="p-0 skills_tag text-center">
                                        @if($service->tag > 0)
                                        @foreach(array_slice($service->tag,0,3) as $index => $tags)
                                        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($tags)}}</span></li>
                                        @endforeach
                                        <br>
                                        @if(count($service->tag) > 3)
                                        <!-- <li><span  id="more" class="px-2 py-1 medium theme-bg rounded text-light">{{count($service->tag) - 3}} More</span></li> -->
                                        @endif
                                        
                                        @else
                                        no tags available
                                        @endif
                                    </ul>
                                </div>--}}
                                
                                @php
                                $n = DB::table('services_details')->where('service_id', $service->id)->select('price1', 'price2', 'price3', 'title1', 'title2', 'title3', 'desc1', 'desc2', 'desc3', 'time1', 'time2', 'time3')->first();
                                @endphp
                                <div class="row pt-2 no-gutters text-center bg-light px-2">
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 text-left">
                                        <span  style="font-size:10px;color: gray !important;">Starting From</span>
                                        <h6 class="font-weight-bold" style="font-size:14px;color: black; !important;"> {{ Session::get('currency_symbol') }} {{
                                        number_format($helpers->convert_to_currency(Session::get('currency'),
                                        @$n->price1),2) }}</h6>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 text-left">
                                        <span style="font-size:10px;color: gray !important;">@lang('Delivery Time')</span>
                                        <h6 class="font-weight-bold" style="font-size:14px;color: #28B661 !important;">{{@$n->time1}} @lang('Days')</h6>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 mt-2">
                                        <a href="javascript:void(0)" class="item-love me-2 loveHeartAction" data-serviceid="{{$service->id}}"><h6 class="font-weight-bold" style="font-size:24px;color: black !important;"><i class="fa fa-heart"></i></h6></a>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 mt-2">
                                        <a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}" class="btn p-1 px-1 bg-dark " style="font-size: 12px;background: #28B661 !important;">Order Now</a>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @else

                        <div class="col-lg-12">
                        	<p style="margin-top: -5px;">No services found</p>
                        </div>
                        @endif
                        
                    </div>
				@endforeach
			
		
		
		
		
	</div>
</section>
@endsection