@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
@php
$content = getContent('contact_us.content', true);
@endphp
<!-- ======================= Searchbar Banner ======================== -->
<!--  <div class="bg-cover py-5" style="background:#28b661 url(assets/img/bn-2.jpg) no-repeat;" data-overlay="2">
    <div class="ht-200"></div>
</div> -->
<!-- ======================= Searchbar Banner ======================== -->
<section class="middle mt-4">
    <div class="container-fluid newhometopsearch  py-4">

        <div class="row">
            <div class="col-lg-7">
                <h2 class="font-weight-normal text-white">@lang('searchhead') <i class="fa fa-arrow-circle-right"></i></h2>
            </div>

            <div class="col-lg-5 text-right">
                 <form action="{{url('/search')}}" method="get" enctype="multipart/form-data" >
                    @csrf
                <div class="row no-gutters">

                    <div class="col-lg-7">
                        <input type="text" name="keyword" class="form-control" name="" placeholder="@lang('look')">
                    </div>
                    <div class="col-lg-3">
                        <select name="type" class="form-control gray">
                           <option value="jobs">@lang('Job')</option>
                                    <option value="services">@lang('Service')</option>
                                    <option value="professionals">@lang('Professionals')</option>
                                    <option value="corporate">@lang('Corporate')</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn bg-warning text-white ml-1" style="width:80%;"><i class="fa fa-search"></i></button>
                    </div>

                </div>
            </form>
            </div>

        </div>

    </div>

    <div class="container-fluid px-0" >
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/banner-4.jpg')}}" alt="First slide" style="height:300px;object-fit: cover;">
                    <div class="carousel-caption  d-md-block">
                        <div class="row text-left">
                            <div class="col-lg-6">
                                <h1 style="position: absolute;bottom:40px;font-size: 40px;line-height: 1.2 !important;" class="font-weight-bold">@lang('banner1') <br> @lang('banner2')  <br> @lang('banner3')</h1>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="carousel-item ">
                    <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/banner-2.jpg')}}" alt="First slide" style="height:300px;object-fit: cover;position: top;">
                    <div class="carousel-caption  d-md-block">
                        <div class="row text-left">
                            <div class="col-lg-6">
                                <h1 style="position: absolute;bottom:40px;font-size: 40px;line-height: 1.2 !important;" class="font-weight-bold">@lang('banner1'), <br> @lang('banner2')  <br> @lang('banner3')</h1>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="carousel-item ">
                    <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/banner-5.jpg')}}" alt="First slide" style="height:300px;object-fit: cover;">
                    <div class="carousel-caption  d-md-block">
                        <div class="row text-left">
                            <div class="col-lg-6">
                                <h1 style="position: absolute;bottom:40px;font-size: 40px;line-height: 1.2 !important;" class="font-weight-bold">@lang('banner1'), <br> @lang('banner2')  <br> @lang('banner3')</h1>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="container-fluid py-5" style="background:url({{asset('assets/wiserlance/assets/img/banner-3.jpg')}}) no-repeat;    padding: 20px 0 50px;    background-position: top !important;background-size: cover !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 text-left">
                    <button class=" btn theme-bg-light theme-cl">@lang('buyer')</button>
                    <h1 class="font-weight-bold mt-3">@lang('bh1')</h1>
                    <p>@lang('bd1')</p>
                    <h1 class="font-weight-bold">@lang('bh2') <span style="color:#28B661;">@lang('bh21')!</span></h1>
                    <p>@lang('bd2')</p>
                    <div class="row mx-1" >
                        <div class="col-lg-5 col-md-5 col-sm-12 col-12 theme-bg-light  py-3 px-3  mt-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="text-white">Search and Buy Services</h4>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-8 col-8">
                                    <p class="text-white"><i class="fa fa-check"></i> &nbsp Clearly defined</p>
                                    <p class="text-white"><i class="fa fa-check"></i> &nbsp Zero Shocks</p>
                                    <p class="text-white"><i class="fa fa-check"></i> &nbsp Establish Cost</p>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-4 col-4 pt-4">
                                    <h1 class="text-white"><i class="fa fa-arrow-circle-left"></i></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-0 col-0 py-3 px-3  mt-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 bg-warning drip2 py-3 px-3  mt-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="text-white">Create a Job and hire a professional</h4>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-9">
                                    <p class="text-white"><i class="fa fa-check"></i> &nbsp Verified Professional</p>
                                    <p class="text-white"><i class="fa fa-check"></i> &nbsp Strong Work Ethic</p>
                                    <p class="text-white"><i class="fa fa-check"></i> &nbsp Expertise in their field</p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-3 col-3 pt-4">
                                    <h1 class="text-white"><i class="fa fa-arrow-circle-left"></i></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid split-two py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-left">
                    <h1 class="font-weight-bold" style="color:#28B661;">Featured Services</h1>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-right">
                    <span style="color: #28B661;font-size: 25px;margin-top: 20px !important;">Browse all services &nbsp <i class="fa fa-arrow-circle-right"></i></span> &nbsp <button class="btn theme-bg-light theme-cl" style="margin-top:-10px;">ALL SERVICES</button>
                </div>
            </div>
            <div class="row align-items-center service-slide mt-5">
                @foreach($services as $service)
                <div class="mx-2">
                    <div class="job_grid border rounded service-hov">
                        @if($service->featured == 1)
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
                       {{-- @if($service->optionalImage->count() > '0')
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
                                <!-- <div class="carousel-item">
                                    <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images1.jpeg')}}" alt="Second slide" style="max-height:165px;object-fit: cover;">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images3.jpeg')}}" style="max-height:165px;object-fit: cover;" alt="Third slide">
                                </div> -->
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
                        {{--@endif--}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2 text-right">
                            <div class="jbl_location" style="line-height: 4px !important;font-size:12px;color: gray !important;">Selling level <i style="color:gold;" class="fa fa-star mr-1"></i><span>{{__(getAmount($service->rating, 2))}}</span></div>
                        </div>
                        <div class="row no-gutters text-left pr-2">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-3">
                                @if($service->user->image == '')
                                <div class="job_grid_thumbmb-2 ml-2" >
                                    <a href="{{route('profile', [slug($service->user->username), encrypt($service->user->id)])}}" class="d-inline-flex text-center m-auto "><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoMIkoUpzbBzDvTP_ePhyi0SsKSE57dvr89InSN1Ud_0-UDC7WRoTm3noAVg4c97VS538&usqp=CAU" class="img-fluid " style="width:width: 30px;height: 30px;border-radius: 50px;object-fit: cover;" alt="{{__($service->user->username)}}" /></a>
                                </div>
                                @else
                                <div class="job_grid_thumbmb-2 ml-2" >
                                    <a href="{{route('profile', [slug($service->user->username), encrypt($service->user->id)])}}" class="d-inline-flex text-center m-auto circle border p-2"><img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $service->user->image, 'profile_image') }}" class="img-fluid circle" style="width:width: 30px;height: 30px;border-radius: 50px;object-fit: cover;" alt="{{__($service->user->username)}}" /></a>
                                </div>
                                @endif
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-9 col-9 text-left">
                                <h4 class="mb-0 ft-medium medium"><a href="{{route('profile', [slug($service->user->username), encrypt($service->user->id)])}}" class="text-dark fs-md"> {{__($service->user->username)}}</a></h4>
                                 <div style="height: 55px !important;">
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
                                <h6 class="font-weight-bold" style="font-size:14px;color: black; !important;">{{__($general->cur_sym)}}{{@$n->price1}}</h6>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 text-left">
                                <span style="font-size:10px;color: gray !important;">@lang('Delivery Time')</span>
                                <h6 class="font-weight-bold" style="font-size:14px;color: #28B661 !important;">{{@$n->time1}} @lang('Days')</h6>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 mt-2">
                               <a href="javascript:void(0)" class="item-love me-2 loveHeartAction" data-serviceid="{{$service->id}}"> <h6 class="font-weight-bold" style="font-size:24px;color: black !important;"><i class="fa fa-heart"></i></h6></a>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 mt-2">
                                <a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}" class="btn p-1 px-1 bg-dark " style="font-size: 12px;background: #28B661 !important;">Order Now</a>
                            </div>


                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 pt-3">
                    <h1 class="font-weight-bold" style="color:#28B661;">Why Choose Wiserlance?</h1>
                    <span class="mt-3" style="color: gray;line-height: 1;font-size: 22px;">Talented and professionals from around the world at your fingertips now & why should businesses trust Wiserlance?</span>
                    <p class="mt-4"><span><i style="color: #28B661;font-size: 20px !important;" class="fa fa-arrow-circle-right"></i></span> &nbsp <span style="color: gray;font-size: 15px !important;">Pay and reward the right people, based on your budget and requirements</span></p>
                    <p ><span><i style="color: #28B661;font-size: 20px !important;" class="fa fa-arrow-circle-right"></i></span> &nbsp <span style="color: gray;font-size: 15px !important;">Registration on Wiserlance will be limited to only professional and talented individuals with identity verified, as our goal is to offer quality services.</span></p>
                    <p ><span><i style="color: #28B661;font-size: 20px !important;" class="fa fa-arrow-circle-right"></i></span> &nbsp <span style="color: gray;font-size: 15px !important;">Work and quality is at the top of your priority list, while protection of your data, privacy and money is at ours</span></p>
                    <p ><span><i style="color: #28B661;font-size: 20px !important;" class="fa fa-arrow-circle-right"></i></span> &nbsp <span style="color: gray;font-size: 15px !important;">Do you have a question? please send us your question, and we'll be happy to assist you?</span></p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <img class="img-fluid" src="{{asset('assets/wiserlance/assets/img/b-6.jpg')}}">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <h1 class="font-weight-bold" style="color:#28B661;">Popular Categories</h1>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-right">
                    <span style="color: #28B661;font-size: 25px;margin-top: 20px !important;">Browse all categories &nbsp <i class="fa fa-arrow-circle-right"></i></span> &nbsp <a href="{{route('allcategories')}}" class="btn theme-bg-light theme-cl" style="margin-top:-10px;">ALL CATEGORIES</a>
                </div>
            </div>
            <div class="row text-center  my-3">
                @foreach($cat as $cate)
                @php
                $ser=DB::table('jobs')->where('status',1)->where('category_id',$cate->id)->count();
                @endphp
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                    <img class="img-fluid mb-2" style="width:30%;" src="{{asset('assets/wiserlance/assets/img/icons/13.png')}}">
                    <h6 class="font-weight-bold" style="color: #28B661;">{{$cate->name}}</h6>
                    <a href="{{route('category.services', [encrypt($cate->id)])}}"><p class="text-warning">{{$ser}} @lang('Jobs')</p></a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container-fluid py-5 theme-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-0 col-0">
                    <button class="btn bg-warning text-white btn-block" >Sellers</button>
                    <img class="img-fluid mt-2" style="width:100%;height: 90%" src="{{asset('assets/wiserlance/assets/img/b-3.jpg')}}">
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-12">
                    <h1 class="text-white">Discover outstanding opportunities</h1>
                    <span class="text-white" style="font-size: 23px;">Are you looking to take control of your career and work on your own terms? Freelancing might be the perfect option for you! As a freelancer, you have the freedom to choose your own projects, set your own hours, and work from anywhere in the world.</span>
                    <p class="text-white mt-2">Working as a freelancer can be a rewarding and fulfilling career choice. With the right skills, tools, and mindset, you can enjoy the benefits of a flexible and independent lifestyle. So why wait? Start exploring Wiserlance for freelancing today!</p>
                    <p class="text-white">Wiselance enables you to reach out to clients all over the world with predefined services in various categories. </p>
                    <p class="text-white">Wiserlance provides freelancers with the opportunity to work on an hourly basis as Wiserlance offers flexible pricing options for clients, including fixed-price or hourly contracts. </p>
                    <!-- <h6 class="font-weight-bold" style="color:yellow;">So why wait?</h6>
                    <h3 class="text-white ">Lorem ipsum dolor sit amet, consectetur adipisic.</h3>
                    <p class="text-white"> <span class="font-weight-bold">Wiserlance</span> Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    <p class="text-white"> <span class="font-weight-bold">Wiserlance</span> Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    <p class="text-white"> <span class="font-weight-bold">Wiserlance</span> Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p> -->
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <h3 class="font-weight-bold" style="color:yellow;">Discover Opportunities</h3>
                            <p class="text-white"><i class="fa fa-check"></i> &nbsp Verified Clients</p>
                            <p class="text-white"><i class="fa fa-check"></i> &nbsp Earn money in different ways</p>
                            <p class="text-white"><i class="fa fa-check"></i> &nbsp Work when, where, and how you want</p>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <h3 class="font-weight-bold" style="color:yellow;">Create Service In Various Categories</h3>
                            <p class="text-white"><i class="fa fa-check"></i> &nbsp Service with a predefined cost</p>
                            <p class="text-white"><i class="fa fa-check"></i> &nbsp Present your portfolio to the world</p>
                            <p class="text-white"><i class="fa fa-check"></i> &nbsp Customize services for clients</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 pt-1">
                    <h1 class="font-weight-bold" style="color:#28B661;">Corporate Business</h1>

                    <p ><span style="color: gray;font-size: 15px !important;">Wiserlance facilitates business-to-business connections by registering and onboarding service providers with highly qualified personnel who have a strong commercial awareness and who give not only professional services but also a pragmatic perspective in reaching targeted business goals.
Juggling everything internally when your company starts to expand can be challenging. By outsourcing to the right business partners, you can free up time and money to concentrate on other areas of your business while still giving clients the high-caliber service they've come to expect from their favorite brands.
Every professional organisation registered on Wiserlance undergoes a thorough due diligence process before being allowed to provide services. This brings businesses peace of mind when they procure or outsource services.
</span></p>
                    <button class="btn theme-bg-light theme-cl">READ MORE</button>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <img class="img-fluid" src="{{asset('assets/wiserlance/assets/img/b-2.jpg')}}">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
// item Slide
$('.service-slide').slick({
slidesToShow:4,
arrows: true,
dots: false,
infinite: true,
speed: 500,
cssEase: 'linear',
autoplaySpeed: 2000,
autoplay:true,
responsive: [
{
breakpoint: 1024,
settings: {
arrows: true,
dots: false,
slidesToShow:3
}
},
{
breakpoint: 600,
settings: {
arrows: true,
dots: false,
slidesToShow:1
}
}
]
});
</script>
@endsection
