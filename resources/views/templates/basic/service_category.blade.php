@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')


<!-- ======================= Searchbar Banner ======================== -->
<!--             <div class="bg-cover py-5" style="background:#28b661 url(assets/img/bn-2.jpg) no-repeat;" data-overlay="2">
                <div class="ht-200"></div>
            </div> -->
<!-- ======================= Searchbar Banner ======================== -->

<section>

    <div class="container-fluid">

        <!-- row Start -->
        <div class="row">

            <!-- Blog Detail -->
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="row align-items-center">
                    <!-- Single -->
                    @foreach($services as $service)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">

                        <div class="job_grid border rounded service-hov">
                            @if($service->featured == 1)
                            <div class="feature text-uppercase">@lang('Featured')</div>
                            @endif
                            @if($service->favorite > 0)
                            <a href="javascript:void(0)">
                                <div class="position-absolute ab-left"><button type="button" class="p-3 border circle d-flex align-items-center justify-content-center bg-white text-danger"><i class="lni lni-heart-filled position-absolute snackbar-wishlist loveHeartAction"></i><span style="position: absolute;right:6px;top:2px;font-size:10px;">{{__($service->favorite)}}</span></button></div>
                            </a>
                            @else
                            <a href="javascript:void(0)">
                                <div class="position-absolute ab-left"><button type="button" class="p-3 border circle d-flex align-items-center justify-content-center bg-white text-gray"><i class="lni lni-heart-filled position-absolute snackbar-wishlist loveHeartAction"></i></button></div>
                            </a>
                            @endif
                            @if($service->optionalImage->count() > '0')
                            <div id="servicescarousel" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">

                                    @foreach($service->optionalImage as $index => $value)
                                    <li data-target="#servicescarousel" data-slide-to="{{$index}}" class="<?php echo ($index == '0') ? 'active' : ''; ?>"></li>
                                    @endforeach

                                </ol>
                                <div class="carousel-inner">
                                    @foreach($service->optionalImage as $key => $value)
                                    <div class="carousel-item <?php echo ($index == '0') ? 'active' : ''; ?>">
                                        <img class="d-block w-100" src="{{getImage('assets/images/optionalService/'.$value->image, imagePath()['optionalService']['size']) }}" alt="@lang('item-banner')" style="max-height:206px;">
                                    </div>
                                    @endforeach
                                    <!-- <div class="carousel-item">
                    <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images1.jpeg')}}" alt="Second slide" style="max-height:206px;">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="{{asset('assets/wiserlance/assets/img/images3.jpeg')}}" style="max-height:206px;" alt="Third slide">
                </div> -->
                                </div>
                                <a class="carousel-control-prev controls" href="#servicescarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next controls" href="#servicescarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            @else
                            <img class="d-block w-100" src="{{getImage('assets/images/service/'.$service->image,imagePath()['service']['size'])}}" alt="@lang('Service Image')" style="max-height:206px;">
                            @endif
                            <div class="job_grid_thumbmb-2 ml-2" style="margin-top:-40px;position: relative;">
                                <a href="{{route('profile', $service->user->username)}}" class="d-inline-flex text-center m-auto circle border p-2"><img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $service->user->image, 'profile_image') }}" class="img-fluid circle" width="70" alt="{{__($service->user->username)}}" /></a>
                            </div>
                            <div class="job_grid_caption  pb-3 px-3">
                                <div class="row">
                                    <div class="col-lg-6 col-md-8 col-sm-8 col-8">
                                        <h4 class="mb-0 ft-medium medium"><a href="{{route('profile', $service->user->username)}}" class="text-dark fs-md">{{__($service->user->username)}}</a></h4>
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-sm-4 col-4 text-right">
                                        <div class="jbl_location"><i class="fa fa-star mr-1"></i><span>{{__(getAmount($service->rating, 2))}}</span></div>
                                    </div>
                                    <div class="col-xl-12">
                                        <p>{{__($service->title)}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="job_grid_footer px-3">
                                <ul class="p-0 skills_tag text-center">
                                    @foreach($service->tag as $index => $tags)
                                    <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($tags)}}</span></li>
                                    @endforeach

                                    <li><span onclick="showmore()" id="more" class="px-2 py-1 medium theme-bg rounded text-light">More</span></li>
                                    <div id="extra">
                                        @foreach($service->tag as $tags)
                                        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($tags)}}</span></li>
                                        @endforeach

                                        <li><span id="less" onclick="showless()" class="px-2 py-1 medium theme-bg rounded text-light">Show less</span></li>
                                    </div>
                                </ul>

                            </div>
                            <hr>
                            <div class="row px-3 pb-3 no-gutters text-center">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <span>@lang('Delivery')</span>
                                    <h6>{{__($service->delivery_time)}} @lang('Days')</h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 order-xl-1 order-lg-1 order-md-2 order-sm-2 order-2">
                                    <a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}" class="btn theme-bg text-light apply-btn rounded full-width">Order <i class="lni lni-arrow-right-circle ml-1"></i></a>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 order-xl-2 order-lg-2 order-md-1 order-sm-1 order-1">
                                    <span>Starting</span>
                                    <h6>{{__($general->cur_sym)}}{{__(showAmount($service->price))}}</h6>
                                </div>
                            </div>

                        </div>
                    </div>

                    @endforeach


                </div>
                <!-- row -->



            </div>

            <!-- Single blog Grid -->
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">

                <!-- Searchbard -->
                <!-- <div class="single_widgets widget_search">
                                <h4 class="title">Search</h4>
                                <form action="#" class="sidebar-search-form">
                                    <input type="search" name="search" placeholder="Search..">
                                    <button type="submit"><i class="ti-search"></i></button>
                                </form>
                            </div> -->

                <!-- Categories -->
                <div class="single_widgets widget_category">
                    <h4 class="title">@lang('CATEGORIES')</h4>
                    <ul>
                        @foreach($categorys as $category)
                        @php
                        $services=DB::table('services')->where('category_id',$category->id)->count();
                        @endphp
                        <li><a href="{{route('service.category', [slug($category->name),$category->id])}}">{{$category->name}} <span>{{$services}}</span></a></li>
                        @endforeach

                    </ul>
                </div>

                <!-- Trending Posts -->
                <div class="single_widgets widget_thumb_post">
                    <h4 class="title">@lang('FEATURED SERVICE')</h4>
                    <ul>
                        @foreach($fservices as $ser)
                        <li>
                            <span class="left">
                                <img src="{{getImage('assets/images/service/'.$ser->image, imagePath()['service']['size']) }}" alt="@lang('service image')" class="">
                            </span>
                            <span class="right">
                                <a class="feed-title" href="{{route('service.details', [slug($ser->title), encrypt($ser->id)])}}">{{__($ser->title)}}</a>
                                <span class="post-date"><i class="fa fa-thumbs-up"></i>({{__($ser->likes)}})</span>
                            </span>
                        </li>
                        @endforeach

                    </ul>
                </div>

                <!-- Tags Cloud -->
                <!-- <div class="single_widgets widget_tags">
                                <h4 class="title">Tags Cloud</h4>
                                <ul>
                                    <li><a href="#">Lifestyle</a></li>
                                    <li><a href="#">Travel</a></li>
                                    <li><a href="#">Fashion</a></li>
                                    <li><a href="#">Branding</a></li>
                                    <li><a href="#">Music</a></li>
                                </ul>
                            </div>
                             -->
            </div>

        </div>
        <!-- /row -->

    </div>

</section>

@endsection
@section('script')

@endsection