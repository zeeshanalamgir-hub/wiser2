@php
    $helpers = new \App\Classes\Helpers();
    $currency_rates = $helpers->get_currency_rates();

    $currencies = $helpers->get_all_currencies();
    // echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
@endphp
<!-- <script src="{{ asset('js/app.js') }}"></script> -->
<!-- Vikash Change -->
<!-- mobile toogle -->
<style>
    .bell-color {
        color: #28b661;
    }
</style>

<div class="container mobilenav">
    <nav class="navbar navbar-expand-lg navbar-new" style="padding:0px !important;">
        <div class="container">
            <button style="cursor: pointer;font-size: 20px;border:none;background:transparent;"
                id="carttoogle">&#9776;</button> &nbsp &nbsp &nbsp
            <a class="navbar-brand text-right" href="#">
                <img src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.png') }}" style="width:100%;"
                    alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>
    </nav>
</div>

<!-- mobile toogle -->
<!-- mobile nav -->
<div id="chat-box-main-wrap" class="sidenav mobilenav">
    <div class="row pb-2" style="border-bottom:1px solid black;">
        <div class="col-sm-12 col-12">
            <button style="cursor: pointer;font-size: 20px;border:none;background:transparent;" class="close-btn"
                id="carttoogle1"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="width:100%">
        <li class="nav-item">
            <a class="nav-link" href="#">Professionals</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Jobs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Software</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Corporate</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Blog</a>
        </li>
        <li class="nav-item">
            <a href="#" data-toggle="modal" data-target="#login" class="nav-link">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>Sign In
            </a>
        </li>
        <li style="margin-top:9px;">
            <div class="currency-selector dropdown js-dropdown float-left">
                <a href="javascript:void(0);" data-toggle="dropdown" class="popup-title" title="Currency"
                    aria-label="Currency dropdown">
                    <span id="vbvb" class="iso_code large ">$USD</span>
                    <i id="vbvb2" class="fa fa-angle-down medium "></i>
                </a>
                <ul class="popup-content dropdown-menu">
                    <li><a title="Euro" href="#" class="dropdown-item medium text-medium">EUR €</a></li>
                    <li class="current"><a title="US Dollar" href="#"
                            class="dropdown-item medium text-medium">USD$</a></li>
                </ul>
            </div>
        </li>
    </ul>
</div>
<!-- mobile nav -->
<!-- ============================================================== -->
<!-- Top header  -->
<!-- ============================================================== -->
<!-- Start Navigation -->
<div class="header <?php echo $pageTitle == 'Home' ? 'header-transparent change-logo' : 'header-light dark-text'; ?>  web-nav pt-2">
    <div class="container px-0" >
        <nav id="navigation2" class="navigation navigation-landscape" style="height:100px !important">
            <div class="nav-header">
                @if ($pageTitle == 'Home')
                    <a class="nav-brand static-logo pt-2" href="{{ route('home') }}">
                        <img src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.png') }}" alt="">
                    </a>
                    <a class="nav-brand fixed-logo pt-2" href="{{ route('home') }}">
                        <img src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.png') }}" alt="">
                    </a>
                @else
                    <a class="nav-brand fixed-logo pt-2" href="{{ route('home') }}">
                        <img src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.png') }}" alt="">
                    </a>
                @endif
                <div class="nav-toggle"></div>
                <div class="mobile_nav">
                    <ul>
                        <li>
                            <a href="#" data-toggle="modal" data-target="#login" class="theme-cl fs-lg">
                                <i class="lni lni-user"></i>
                            </a>
                        </li>
                        <li>
                            <a href="dashboard-post-job.html" class="crs_yuo12 w-auto text-white theme-bg">
                                <span class="embos_45"><i class="fas fa-plus-circle mr-1 mr-1"></i>Post Job</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="nav-menus-wrapper" style="transition-property: none;">
                <ul class="nav-menu">
                    <li @if (request()->routeIs('professionals')) class="active" @endif><a href="{{ route('professionals') }}"
                            class="text-dark">@lang('Professionals') </a></li>
                    <li @if (request()->routeIs('service')) class="active" @endif><a href="{{ route('service') }}"
                            class="text-dark">@lang('Service')</a></li>
                    <li @if (request()->routeIs('job')) class="active" @endif><a href="{{ route('job') }}"
                            class="text-dark">@lang('Job')</a></li>
                    <!-- <li><a href="all-softs.html">Software</a></li> -->
                    <li><a href="#" class="text-dark">@lang('Corporate')</a></li>
                    <!-- <li @if (request()->routeIs('blog') || request()->routeIs('blog.details')) class="active" @endif ><a class="text-dark" href="{{ route('blog') }}">@lang('Blog')</a></li>
                    <li @if (request()->routeIs('contact')) class="active" @endif><a class="text-dark" href="{{ route('contact') }}">@lang('Contact')</a></li> -->
                    <li style="margin-top:23px;margin-left:15px;">
                        <!-- <div class="currency-selector dropdown js-dropdown float-right" >
                            <a class="text-dark" href="javascript:void(0);" data-toggle="dropdown" class="popup-title"  title="Currency" aria-label="Currency dropdown">
                                <span id="<?php echo $pageTitle == 'Home' ? 'vbvb11' : ''; ?>"  class="iso_code large <?php echo $pageTitle == 'Home' ? 'text-light-new' : 'text-light-new1'; ?>">$USD</span>
                                <i id="<?php echo $pageTitle == 'Home' ? 'vbvb12' : ''; ?>" class="fa fa-angle-down medium <?php echo $pageTitle == 'Home' ? 'text-light-new' : 'text-light-new1'; ?>"></i>
                            </a>
                            <ul class="popup-content dropdown-menu" >
                                <li><a title="Euro" href="#" class="dropdown-item medium text-medium">EUR €</a></li>
                                <li class="current"><a title="US Dollar" href="#" class="dropdown-item medium text-medium">USD $</a></li>
                            </ul>
                        </div> -->


                        <select id="currency-symbol" class="language-select langSel" style="border:none;">


                            @foreach ($currencies as $key => $item)
                                <option {{ Session::get('currency') == $item->code ? 'selected' : '' }}
                                    value="{{ $item->code }}">{{ $item->code }}</option>
                            @endforeach
                        </select>
                    </li>

                    <script type="text/javascript">
                        $(document).on('change', '#currency-symbol', function() {
                            var currency = $('#currency-symbol').val();
                            if (currency == '') {
                                currency = 'THB';
                            }
                            var url = "{{ route('currency.change', ['currency' => ':currency']) }}";
                            url = url.replace(':currency', currency);
                            location.assign(url);
                        });
                    </script>
                    <li style="margin-top:22px;margin-left:25px !important;">

                        <div class="language-selector-wrapper dropdown js-dropdown float-right mr-3">
                            <!--  <a class="popup-title" href="javascript:void(0)" data-toggle="dropdown" title="Language" aria-label="Language dropdown">

                                <span id="<?php echo $pageTitle == 'Home' ? 'vbvb1' : ''; ?>" class="iso_code large <?php echo $pageTitle == 'Home' ? 'text-light-new' : 'text-light-new1'; ?>">English</span>
                                <i id="<?php echo $pageTitle == 'Home' ? 'vbvb3' : ''; ?>" class="fa fa-angle-down medium <?php echo $pageTitle == 'Home' ? 'text-light-new' : 'text-light-new1'; ?> "></i>
                            </a> -->
                            <!--  <ul class="dropdown-menu popup-content link">
                                <li class="current"><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img src="{{ asset('assets/wiserlance/assets/img/1.jpg') }}" alt="en" width="16" height="11" /><span>English</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img src="{{ asset('assets/wiserlance/assets/img/2.jpg') }}" alt="fr" width="16" height="11" /><span>Français</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img src="{{ asset('assets/wiserlance/assets/img/3.jpg') }}" alt="de" width="16" height="11" /><span>Deutsch</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img src="{{ asset('assets/wiserlance/assets/img/4.jpg') }}" alt="it" width="16" height="11" /><span>Italiano</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img src="{{ asset('assets/wiserlance/assets/img/5.jpg') }}" alt="es" width="16" height="11" /><span>Español</span></a></li>
                                <li ><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img src="{{ asset('assets/wiserlance/assets/img/6.jpg') }}" alt="ar" width="16" height="11" /><span>اللغة العربية</span></a></li>
                            </ul> -->
                            <select class="language-select langSel" style="border:none;">
                                @foreach ($language as $item)
                                    <option value="{{ $item->code }}"
                                        @if (session('lang') == $item->code) selected @endif>{{ __($item->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                    <style type="text/css">
                        .blob {
                            background: black;
                            border-radius: 50%;
                            box-shadow: 0 0 0 0 rgba(0, 0, 0, 1);
                            margin: 10px;
                            height: 5px;
                            width: 5px;
                            transform: scale(1);
                            animation: pulse-black 2s infinite;
                        }

                        .blob.red {
                            background: rgba(255, 82, 82, 1);
                            box-shadow: 0 0 0 0 rgba(255, 82, 82, 1);
                            animation: pulse-red 2s infinite;
                            position: relative;
                            top: -32px;
                        }

                        .blob.green {
                            background: transparent;

                        }

                        @keyframes pulse-red {
                            0% {
                                transform: scale(0.95);
                                box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.7);
                            }

                            70% {
                                transform: scale(1);
                                box-shadow: 0 0 0 10px rgba(255, 82, 82, 0);
                            }

                            100% {
                                transform: scale(0.95);
                                box-shadow: 0 0 0 0 rgba(255, 82, 82, 0);
                            }
                        }
                    </style>
                    @auth

                        @php
                            $noti = DB::table('notifications')
                                ->select('id', 'click_url', 'data', 'read_at', 'created_at')
                                ->where('notifiable_id', auth()->user()->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();

                            $unreadCount = DB::table('notifications')
                                ->where('notifiable_id', auth()->user()->id)
                                ->whereNull('read_at')
                                ->count();
                        @endphp

                        <li @if (request()->routeIs('chatify')) class="active" @endif><a href="{{ route('chatify') }}"
                                class="text-dark">@lang('Message')</a></li>
                        <!-- <li id="bellnew" @if (request()->routeIs('chat-secreen')) class="active" @endif><a class="text-dark" href="{{ route('chat-secreen', ['sid' => Auth::user()->id, 'rid' => '0']) }}">@lang('Messages') </a></li> -->
                        <!--        <li @if (request()->routeIs('chat-secreen')) class="active" @endif><a href="{{ route('chat-secreen', ['sid' => Auth::user()->id, 'rid' => '0']) }}"><i class="fa fa-bell text-primary"></i>
                                    <div class="blob red"></div> </a></li> -->
                        <li style="margin-top:23px;margin-left: 10px;">
                            <div class="currency-selector dropdown js-dropdown float-right">
                                <a class="text-dark" href="javascript:void(0);" data-toggle="dropdown"
                                    class="popup-title">
                                    @if ($noti->count() > 0)
                                        <span class="iso_code large "><i class="fa fa-bell text-primary"></i>
                                            <div class="blob red"></div>
                                        </span>
                                    @else
                                        <span class="iso_code large  "><i class="fa fa-bell bell-color"></i>
                                            <div class="blob green"></div>
                                        </span>
                                    @endif
                                </a>
                                @if ($noti->count() > 0)
                                    <ul class="popup-content dropdown-menu"
                                        style="width:300px;margin-top: -20px;height: 300px !important;overflow-x: hidden;overflow-y: auto;">
                                    @else
                                        <ul class="popup-content dropdown-menu"
                                            style="width:300px;margin-top: -20px;height: 300px !important;overflow-x: hidden;overflow-y: auto;">
                                @endif
                        <li class="current" style="padding: 10px !important;"><span
                                style="font-weight: 600;">Notifications</span><span style="float:right !important;"><a
                                    style="color: #28B661 !important;font-size: 12px;"
                                    href="{{ route('all-notifications') }}">See All <i
                                        class="fa fa-arrow-right"></i></a></span><br><span style="font-size: 13px;">You
                                have {{ $noti->count() }} unread notifications</span>
                        </li>

                        <hr>

                        @if ($noti->count() > 0)
                            @foreach ($noti as $no)
                                @php
                                    $sen = DB::table('users')
                                        ->where('id', $no->sender_id)
                                        ->first();

                                    $job = DB::table('jobs')
                                        ->where('id', $no->job_id)
                                        ->first();
                                @endphp

                                @if ($no->type == 'job_bid')
                                    <li class="current"><a
                                            href="{{ route('user.buyer.hire.employ.bids', encrypt($job->id)) }}"
                                            class="pl-2 medium text-medium">
                                            <img src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;" alt="" />
                                            {{ $sen->username }} submit a bid on
                                            {{ __(str_limit($job->title, 17)) }}.</a></li>
                                @elseif($no->type == 'job_created')
                                    <li class="current"><a
                                            href="{{ route('user.job.vacancy.offers.detail', encrypt($job->id)) }}"
                                            class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} Created new Job
                                            {{ __(str_limit($job->title, 17)) }}.</a></li>
                                @elseif($no->type == 'job_offer')
                                    <li class="current"><a
                                            href="{{ route('user.job.vacancy.offers.detail', encrypt($job->id)) }}"
                                            class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} send you an offer on
                                            {{ __(str_limit($job->title, 17)) }}.</a></li>
                                @elseif($no->type == 'chat')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} sent you a message.</a></li>
                                @elseif($no->type == 'buyer_offer_sent')
                                    <li class="current"><a
                                            href="{{ route('user.buyer.hire.employ.bids', encrypt($job->id)) }}"
                                            class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} offerd you on your bid.</a></li>
                                @elseif($no->type == 'buyer_offer_cancel')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} canceled the offer on your bid.</a>
                                    </li>
                                @elseif($no->type == 'buyer_bid_cancel')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} canceled your bid.</a></li>
                                @elseif($no->type == 'seller_offer_declined')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} declined your offer .</a></li>
                                @elseif($no->type == 'seller_offer_accepted')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} accepted your offer.</a></li>
                                @elseif($no->type == 'seller_cancel_his_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} canceled his bid.</a></li>
                                @elseif($no->type == 'seller_send_work')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} send you work.</a></li>
                                @elseif($no->type == 'buyer_reject_bid_work')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} reject your work.</a></li>
                                @elseif($no->type == 'buyer_accept_bid_work')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} accept your work.</a></li>
                                @elseif($no->type == 'buyer_request_milestone_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} request a milestone.</a></li>
                                @elseif($no->type == 'seller_request_milestone_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} request a milestone.</a></li>
                                @elseif($no->type == 'seller_accept_request_milestone_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }}accepted the request of
                                            milestone.</a></li>
                                @elseif($no->type == 'seller_reject_request_milestone_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} rejected the request of
                                            milestone..</a></li>
                                @elseif($no->type == 'seller_send_worksheet_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} send you timesheet.</a></li>
                                @elseif($no->type == 'buyer_accept_timesheet_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} accept your timesheet.</a></li>
                                @elseif($no->type == 'buyer_reject_timesheet_bid')
                                    <li class="current"><a href="#" class="pl-2 medium text-medium"><img
                                                src="{{ getImage('assets/images/user/profile/' . $sen->image, '400x400') }}"
                                                class="img-fluid circle mr-1"
                                                style="width: 40px;height: 40px;border-radius: 50%;"
                                                alt="" />{{ $sen->username }} rejected your timesheet.</a></li>
                                @endif
                                <hr>
                            @endforeach
                        @else
                            <li class="current"><a title="US Dollar" href="#" class="pl-2 medium text-medium ">No
                                    notification found.</a></li>
                        @endif
                    </ul>
                </div>
                </li>
                <!--                                 <li>

                                                <i class="fa fa-bell text-primary"></i>
                                                <div class="blob red"></div>

                                                </button>
                                            </li> -->
            @endauth
            </ul>

            <ul class="nav-menu nav-menu-social rightleft align-to-right " style="float: right !important;">
                @guest
                    <style type="text/css">
                        .autoheaderwidth {
                            width: 88px;
                        }

                        .proshead {
                            width: 120px !important;
                        }

                        .serhead {
                            width: 88px;
                        }

                        .jobhead {
                            width: 65px;
                        }

                        .corhead {
                            width: 95px;
                        }
                    </style>
                    <li>
                        <form action="{{ url('/search') }}" method="get" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="keyword" class="form-control searchtop showhide"
                                placeholder="Search" aria-label="Username" aria-describedby="addon-wrapping">
                            <select name="type" class=" searchtopss showhide autoheader autoheaderwidth"
                                style="position: absolute;right: 210px;border-radius: 20px;top:20px">
                                <option value="services">@lang('Services')</option>
                                <option value="jobs">@lang('Jobs')</option>
                                <option value="professionals">@lang('Professionals')</option>
                                <option value="corporate">@lang('Corporate')</option>
                            </select>
                            <button type="submit" class="searchtopss showhide btn text-dark "
                                style="position: absolute;right: 280px;background: transparent;border: none;top:12px"><i
                                    class="fa fa-search"></i></button>
                        </form>
                    </li>


                @endguest
                @auth
                    <style type="text/css">
                        .autoheaderwidth1 {
                            width: 88px;
                        }

                        .proshead1 {
                            width: 120px !important;
                        }

                        .serhead1 {
                            width: 88px;
                        }

                        .jobhead1 {
                            width: 65px;
                        }

                        .corhead1 {
                            width: 95px;
                        }
                    </style>
                    &nbsp&nbsp
                    <li style="position: relative !important;top: -7px;">
                        <form action="{{ url('/search') }}" method="get" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="keyword" class="form-control searchtop showhide"
                                placeholder="Search" aria-label="Username" aria-describedby="addon-wrapping">
                            <select name="type" class=" searchtopss showhide autoheader1 autoheaderwidth1"
                                style="position: absolute;right: 35px;border-radius: 20px;top:7px">
                                <option value="services">@lang('Services')</option>
                                <option value="jobs">@lang('Jobs')</option>
                                <option value="professionals">@lang('Professionals')</option>
                                <option value="corporate">@lang('Corporate')</option>
                            </select>
                            <button type="submit" class="searchtopss showhide btn text-dark "
                                style="position: absolute;right: -15px;background: transparent;border: none;top:0px"><i
                                    class="fa fa-search"></i></button>
                        </form>
                    </li>
                    &nbsp
                @endauth
                @guest
                    <li>
                        <a class="text-dark" href="{{ url('/login') }}" class="ft-medium">
                            <i class="lni lni-user mr-2"></i>@lang('Sign in')
                        </a>
                    </li>
                    <li class="theme-bg p-1" style="border-radius:5px;">
                        <a href="{{ url('/register') }}" class="btn ft-medium"
                            style="padding:7px;border-radius: 5px !important;">
                            <i class="lni lni-circle-plus mr-1"></i> @lang('Sign Up')
                        </a>
                    </li>
                @endguest
                @auth
                    <li style="margin-top:15px;">
                        <div class="language-selector-wrapper dropdown js-dropdown float-right mr-3">
                            <a class="text-dark" class="popup-title" href="javascript:void(0)" data-toggle="dropdown"
                                title="Language" aria-label="Language dropdown">
                                @if (auth()->user()->image == '')
                                    <img src="{{ asset('assets/images/user/profile/userProfile.png') }}"
                                        class="img-fluid circle mr-1" style="width: 30px;height: 30px;border-radius: 50%;"
                                        alt="">
                                @else
                                    <img src="{{ getImage('assets/images/user/profile/' . auth()->user()->image, '400x400') }}"
                                        class="img-fluid circle mr-1" style="width: 30px;height: 30px;border-radius: 50%;"
                                        alt="" />
                                @endif
                                <span class=" namehide iso_code large text-light-new1">{{ Auth::user()->firstname }}
                                    {{ Auth::user()->lastname }}</span>
                                <i class="fa fa-angle-down medium text-light-new1 ?> "></i>
                            </a>
                            <ul class="dropdown-menu popup-content link">
                                <!--  <li class="current"><a href="{{ route('user.profile.setting') }}" class="dropdown-item medium text-medium"><span>@lang('Profile Settings')</span></a></li> -->
                                @php
                                    $bpcount = DB::table('user_company')
                                        ->where('user_id', Auth::user()->id)
                                        ->count();
                                @endphp
                                <script>
                                    // Echo.channel('events')
                                    // .listen('RealTimeMessage', (e) => console.log('RealTimeMessage: ' + e.message));
                                </script>
                                @if (Auth::user()->is_employer == '1')
                                    <li class="current"><a href="{{ route('user.buyer.dashboard') }}"
                                            class="dropdown-item medium text-medium"><span>@lang('Go To Buyer Dashboard')</span></a>
                                    </li>
                                    @if ($bpcount > 0)
                                        <li class="current"><a href="{{ route('user.buyer.profile') }}"
                                                class="dropdown-item medium text-medium"><span>@lang('Update Buyer Profile')</span></a>
                                        </li>
                                    @else
                                        <li class="current"><a href="{{ route('user.buyer.profile') }}"
                                                class="dropdown-item medium text-medium"><span>@lang('Create Buyer Profile')</span></a>
                                        </li>
                                    @endif
                                @endif

                                @if (Auth::user()->is_freelancer == '1')
                                    <li class="current"><a href="{{ route('user.home') }}"
                                            class="dropdown-item medium text-medium"><span>@lang('Go To Seller Dashboard')</span></a>
                                    </li>

                                    @if (Auth::user()->per_hour_rate != '0')
                                        <li class="current"><a href="{{ route('user.home2') }}"
                                                class="dropdown-item medium text-medium"><span>@lang('Update Professional')</span></a>
                                        </li>
                                    @else
                                        <li class="current"><a href="{{ route('user.home1') }}"
                                                class="dropdown-item medium text-medium"><span>@lang('Become A Professional')</span></a>
                                        </li>
                                    @endif
                                @endif

                                <li class="current"><a href="{{ route('user.change.password') }}"
                                        class="dropdown-item medium text-medium"><span>@lang('Change Password')</span></a></li>
                                <li class="current"><a href="{{ route('user.twofactor') }}"
                                        class="dropdown-item medium text-medium"><span>@lang('2FA Security')</span></a></li>
                                <li class="current"><a href="{{ route('user.logout') }}"
                                        class="dropdown-item medium text-medium"><span>@lang('Logout')</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endauth
            </ul>
    </div>
    </nav>
</div>
</div>
<div class="header header-transparent change-logo showhide2 showhide web-nav" id="header" style="margin-top: 10px">
    <div class="container">
        <nav id="navigation" class="navigation navigation-landscape">
            @php
                $cats = DB::table('categories')
                    ->where('status', 1)
                    ->where('type', 'job')
                    ->take('5')
                    ->orderBy('id', 'asc')
                    ->get();
                $cats2 = DB::table('categories')
                    ->where('status', 1)
                    ->where('type', 'job')
                    ->take('5')
                    ->where('id', '>', 5)
                    ->get();
            @endphp
            <div class="nav-menus-wrapper cvv" style="transition-property: none;">
                <ul class="nav-menu">
                    @foreach ($cats as $cat)
                        @php
                            $subcats = DB::table('sub_categories')
                                ->where('category_id', $cat->id)
                                ->get();
                        @endphp
                        <li><a href="{{ route('category.services', [encrypt($cat->id)]) }}">{{ $cat->name }}</a>
                            <ul class="nav-dropdown nav-submenu">
                                <li>
                                    <div class="row">
                                        @foreach ($subcats as $sub)
                                            <div class="col-lg-4 mt-3">
                                                <a class="newdropa"
                                                    href="{{ route('subcategory.services', [encrypt($sub->id)]) }}">{{ $sub->name }}</a>
                                            </div>
                                        @endforeach

                                    </div>
                                </li>
                                <!-- <li><a href="landing-page.html">Item 2</a></li>
                                    <li><a href="landing-page.html">Item 3</a></li>
                                    <li><a href="landing-page.html">Item 4</a></li>
                                    <li><a href="landing-page.html">Item 5</a></li>
                                    <li><a href="landing-page.html">Item 6</a></li>
                                    <li><a href="landing-page.html">Item 7</a></li>
                                    <li><a href="landing-page.html">Item 8</a></li> -->
                            </ul>
                        </li>
                    @endforeach
                    <!--  <li><a href="javascript:void(0);">Accounting & Finance</a>
                            <ul class="nav-dropdown nav-submenu">
                                <li><a href="landing-page.html">Item 1</a></li>
                                <li><a href="landing-page.html">Item 2</a></li>
                                <li><a href="landing-page.html">Item 3</a></li>
                                <li><a href="landing-page.html">Item 4</a></li>
                                <li><a href="landing-page.html">Item 5</a></li>
                                <li><a href="landing-page.html">Item 6</a></li>
                                <li><a href="javascript:void(0);">Items new</a>
                                <ul class="nav-dropdown nav-submenu">
                                    <li><a href="landing-page.html">Item 1</a></li>
                                    <li><a href="landing-page.html">Item 2</a></li>
                                    <li><a href="landing-page.html">Item 3</a></li>
                                    <li><a href="landing-page.html">Item 4</a></li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0);">Links new</a>
                            <ul class="nav-dropdown nav-submenu">
                                <li><a href="landing-page.html">Item 1</a></li>
                                <li><a href="landing-page.html">Item 2</a></li>
                                <li><a href="landing-page.html">Item 3</a></li>
                                <li><a href="landing-page.html">Item 4</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li><a href="javascript:void(0);">Sales & Marketing</a>
                <ul class="nav-dropdown nav-submenu">
                    <li><a href="landing-page.html">Item 1</a></li>
                    <li><a href="landing-page.html">Item 2</a></li>
                    <li><a href="landing-page.html">Item 3</a></li>
                    <li><a href="landing-page.html">Item 4</a></li>
                    <li><a href="landing-page.html">Item 5</a></li>
                    <li><a href="landing-page.html">Item 6</a></li>
                    <li><a href="landing-page.html">Item 7</a></li>
                    <li><a href="landing-page.html">Item 8</a></li>
                </ul>
            </li>

            <li><a href="javascript:void(0);">Data Science & Analytics</a>
            <ul class="nav-dropdown nav-submenu">
                <li><a href="landing-page.html">Item 1</a></li>
                <li><a href="landing-page.html">Item 2</a></li>
                <li><a href="landing-page.html">Item 3</a></li>
                <li><a href="landing-page.html">Item 4</a></li>
                <li><a href="landing-page.html">Item 5</a></li>
                <li><a href="landing-page.html">Item 6</a></li>
                <li><a href="landing-page.html">Item 7</a></li>
                <li><a href="landing-page.html">Item 8</a></li>
            </ul>
        </li>

        <li><a href="javascript:void(0);">Admin & Customer Support</a>
        <ul class="nav-dropdown nav-submenu">
            <li><a href="blog.html">Blog Style</a></li>
            <li><a href="about-us.html">About Us</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="404.html">404 Page</a></li>
            <li><a href="privacy.html">Privacy Policy</a></li>
            <li><a href="faq.html">FAQs</a></li>
            <li><a href="docs.html">Docs</a></li>
        </ul>
    </li> -->
                    <!-- <li><a href="{{ route('allcategories') }}">More <i class="fa fa-angle-right"></i></a></li> -->


                    <li><a href="{{ route('category.services', [encrypt($cat->id)]) }}">More</a>
                        <ul class="nav-dropdown nav-submenu" style="width: 20%;">
                            <li>
                                <div class="row">
                                    @foreach ($cats as $cat)
                                        <div class="col-lg-12 mt-3">
                                            <a class="newdropa"
                                                href="{{ route('category.services', [encrypt($cat->id)]) }}">{{ $cat->name }}</a>
                                        </div>
                                    @endforeach

                                </div>
                            </li>
                            <!-- <li><a href="landing-page.html">Item 2</a></li>
                                    <li><a href="landing-page.html">Item 3</a></li>
                                    <li><a href="landing-page.html">Item 4</a></li>
                                    <li><a href="landing-page.html">Item 5</a></li>
                                    <li><a href="landing-page.html">Item 6</a></li>
                                    <li><a href="landing-page.html">Item 7</a></li>
                                    <li><a href="landing-page.html">Item 8</a></li> -->
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>
<!-- End Navigation -->
<div class="clearfix"></div>
<!-- getting app.js file which is build -->
<script src="{{ asset('build/assets/app-09567f37.js') }}"></script>

<!-- <script src="{{ asset('js/app.js') }}"></script> -->
<!-- getting real time notifications -->
<script>
    Echo.private('App.Models.User.<?php echo auth()->user()->id; ?>').notification((notification) => {
        console.log(notification.message);
        $('.blob').css({
            'background': 'red',
            'margin-top': '-26px'
        });

        $('.blob.green').css({
            'background': 'rgba(255, 82, 82, 0.5)',
            'box-shadow': '0 0 0 5px rgba(255, 82, 82, 0.5)',
            'animation': 'pulse-red 3s infinite',
            'top': '-50px'
        });
        $('.bell-color').css('color', 'red')

    });
</script>
<!-- ============================================================== -->
