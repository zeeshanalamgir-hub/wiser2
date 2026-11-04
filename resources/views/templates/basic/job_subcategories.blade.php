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

     $jobs =  DB::table('jobs')->where('sub_category_id',  $sub->id)->where('status', 1)->limit(4)->orderBy('id', 'DESC')->get();
       $jobs_count= DB::table('jobs')->where('sub_category_id',  $sub->id)->where('status', 1)->count();
    @endphp
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-left pl-2">
            <h6 class="font-weight-bold" >{{$sub->name}}</h6>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-right">
            <a href="{{route('jobsubcategories.jobs', [slug($sub->name), encrypt($sub->id)])}}"><button class="btn" Style="color:#069170;border: none;font-size: 12px;background: transparent;">Browse all <i class="fa fa-arrow-circle-right"></i></button></a>
        </div>

    </div>
    <div class="row align-items-center">
        <!-- Single -->
        @if($jobs_count > 0)
        @foreach($jobs as $job)

        @php
        $user=DB::table('users')->where('id',$job->user_id)->first();
        @endphp
        @if(@$user->id == '')
        @else
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
                    <!--  <div class="feature" style="position: absolute;top: -9px;z-index: 1 !important;    right: -7px;">
                        <img src="https://flance.scriptlisting.com/assets/images/featured.png">
                    </div> -->
                    <div class="row bg-white">
                        <div class="col-xl-9 col-lg-9 text-left px-3 py-3  ">
                            <div class="d-block mb-2 position-relative">
                                <span class="text-muted medium"><i class="fa fa-user mr-1"></i>{{$user->username}}
                                    @if($job->priority  == 2)
                                    <span style="float: right;" class="feature text-uppercase">featured</span>
                                    @endif
                                </span>
                                @if(isset($user->status) && $user->status == 1)
                                <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
                                @else
                                <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
                                @endif
                            </div>
                            <h6 class="font-weight-bold">{{__($job->title)}}</h6>
                            <p style="height: 110px;">{!!Str::limit($job->description, 370, '...')!!}</p>
                            <div class="jbd-details mb-1 menu-wrapper menu-wrapper-{{ $job->id }}">
                                <ul class="p-0 skills_tag text-left menu" id="parentscrol-{{ $job->id }}">
                                    @if (is_array($job->skill) || is_object($job->skill))
                                    @foreach(@$job->skill as $skill)
                                    <li class="item"><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($skill)}}</span></li>
                                    @endforeach
                                    @else
                                    @endif
                                </ul>
                                <div class="paddles">
                                    <button class="left-paddle paddle left-paddle-{{ $job->id }} hidden" data-id="{{$job->id}}">
                                    <i class="fa fa-arrow-left"></i>
                                    </button>
                                    <button class="right-paddle paddle right-paddle-{{ $job->id }}" data-id="{{$job->id}}">
                                    <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @php
                        $bids=DB::table('user_biding')->where('job_id',$job->id)->count();
                        $offers=DB::table('user_offers')->where('job_id',$job->id)->count();
                        @endphp
                        <div class="col-xl-3 col-lg-3 px-3 py-3  " style="background:rgba(106, 5, 106, 0.05);" >
                            @if(@$job->exp_type == 1)
                            <p style="margin-top:-10px;"><i class="fa fa-user  mr-2" style="color:#007bff !important;"></i>Entry Level</p>
                            @elseif(@$job->exp_type == 2)
                            <p style="margin-top:-10px;"><i class="fa fa-user  mr-2" style="color:#007bff !important;"></i>Intermediate</p>
                            @else(@$job->exp_type == 3)
                            <p style="margin-top:-10px;"><i class="fa fa-user  mr-2" style="color:#007bff !important;"></i>Expert</p>
                            @endif
                            <p style="margin-top:-10px;"><i class="fa fa-map-marker-alt mr-2" style="color:#6f42c1 !important;"></i>{{$job->country}} </p>
                            <!-- <p style="margin-top:-10px;"><i class="fa fa-clock mr-2"></i>{{$job->delivery_time}} @lang('Days')</p> -->
                            @if($job->budgettype == 'per_hour')
                            <p style="margin-top:-10px;"><i class="fa fa-clock mr-2" style="color:#ffc107 !important;"></i>Hourly</p>
                            @else
                            <p style="margin-top:-10px;"><i class="fa fa-clock mr-2" style="color:#ffc107 !important;"></i>Fixed</p>
                            @endif
                            @if($job->budgettype == 'per_hour')
                            <p style="margin-top:-10px;"><i class="fa fa-dollar-sign mr-2" style="color:#17a2b8 !important;"></i>{{ Session::get('currency_symbol') }} {{
                                number_format($helpers->convert_to_currency(Session::get('currency'),
                            @$job->amount),2) }}/hour</p>
                            @else
                            <p style="margin-top:-10px;"><i class="fa fa-dollar-sign mr-2" style="color:#17a2b8 !important;"></i>{{ Session::get('currency_symbol') }} {{
                                number_format($helpers->convert_to_currency(Session::get('currency'),
                            @$job->amount),2) }}</p>

                            @endif
                            <p style="margin-top:-10px;"><i class="fa fa-calendar mr-2" style="color:#dc3545 !important;"></i>@lang('Posted '): {{diffforhumans($job->created_at)}}</p>
                            <p style="margin-top:-10px;"><i class="fa fa-file-alt mr-2" style="color:#28a745 !important"></i>@lang('Bids'): {{$bids}}</p>
                            <p style="margin-top:-10px;"><i class="fa fa-file-alt mr-2" style="color:#7393B3 !important;"></i>@lang('Offers'): {{$offers}}</p>
                            <!--  <p><i class="fa fa-map mr-2"></i>{{@$job->user->address->country}}</p> -->
                            <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">@lang('View Details')<i class="lni lni-arrow-right-circle ml-1"></i></a>
                        </div>
                    </div>
                </div>
        @endif
        @endforeach
        @else
        <div class="col-lg-12">
            <p style="margin-top: -5px;">No jobs found</p>
        </div>
        @endif

    </div>
    @endforeach





</div>
</section>
@endsection
