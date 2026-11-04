@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job-withoutsidebar')

@section('content')
    <style>
        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            background-color: #037e89;
        }

        .nav {
            --bs-nav-link-color: #037e89;
        }

        .carousel {
            max-width: 830px;
            margin: 10px auto;
            position: relative;
        }

        .slides {
            display: none;
            width: 100%;
        }

        .active-slide {
            display: block;
        }

        .nav-buttons {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .prev,
        .next {
            background-color: rgb(2, 103, 103);
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            font-size: 18px;
        }

        .thumbnails {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .thumbnail {
            width: 60px;
            height: 40px;
            object-fit: cover;
            margin: 0 4px;
            cursor: pointer;
            opacity: 0.6;
            border: 2px solid transparent;
        }

        .thumbnail.active {
            opacity: 1;
            border-color: #333;
        }

        .prfl_img {
            object-fit: cover;
            border-radius: 100% !important;
            width: 70px;
            height: 70px;
        }

        @media screen and (max-width: 600px) {
            .fs12 {
                font-size: 12px !important;
            }

            .fs14 {
                font-size: 14px !important;
            }
        }

        /* Added for related services */
        .full-width-section {
            margin-left: calc(var(--bs-gutter-x) * -.5);
            margin-right: calc(var(--bs-gutter-x) * -.5);
            padding-left: calc(var(--bs-gutter-x) * .5);
            padding-right: calc(var(--bs-gutter-x) * .5);
        }

        @media (min-width: 992px) {
            .full-width-section {
                margin-left: calc((100vw - 960px) / -2);
                margin-right: calc((100vw - 960px) / -2);
                padding-left: calc((100vw - 960px) / 2);
                padding-right: calc((100vw - 960px) / 2);
            }
        }

        @media (min-width: 1200px) {
            .full-width-section {
                margin-left: calc((100vw - 1140px) / -2);
                margin-right: calc((100vw - 1140px) / -2);
                padding-left: calc((100vw - 1140px) / 2);
                padding-right: calc((100vw - 1140px) / 2);
            }
        }

        @media (min-width: 1400px) {
            .full-width-section {
                margin-left: calc((100vw - 1320px) / -2);
                margin-right: calc((100vw - 1320px) / -2);
                padding-left: calc((100vw - 1320px) / 2);
                padding-right: calc((100vw - 1320px) / 2);
            }
        }
    </style>
    @php
        $helpers = new \App\Classes\Helpers();
        $currency_rates = $helpers->get_currency_rates();
        $currencies = $helpers->get_all_currencies();
    @endphp
    <div class="col-12 pt-md-0 p-md-4 pb-md-2">
        <p class="mb-0 mt-3 text-3gray">
            @if ($user?->is_employer)
                <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
            @else
                <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
            @endif
            <i class="fa-solid fa-chevron-right fs-12"></i>
            <a href="/service" class="text-3gray" style="text-decoration:none;">Services</a>
            <i class="fa-solid fa-chevron-right fs-12"></i> View
        </p>
    </div>

    <div class="row"> <!-- Main row wrapper -->
        <!-- Right Section-->
        <div class="col-lg-4 order-second order-lg-last" style="overflow: visible;">
            <div class="bg-white p-4 shadow-sm border fs-14 rounded">
                <div class="nav nav-pills row mb-3" id="pills-tab" role="tablist">
                    @if ($service->service_type == '0')
                        <!-- Only show LIGHT if service_type is 0 -->
                        <div class="nav-item bg-light col-4 px-lg-0 rounded-1" role="presentation">
                            <button class="nav-link active py-1 rounded-1 fs-6 px-lg-2 px-xl-3 px-xxl-4 fw-bold"
                                id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-basic" type="button"
                                role="tab" aria-controls="pills-home" aria-selected="true">
                                LIGHT
                            </button>
                        </div>
                    @else
                        <!-- Show all tabs (LIGHT, PLUS, ADVANCE) for any other service_type -->
                        <div class="nav-item bg-light col-4 px-lg-0 rounded-1" role="presentation">
                            <button class="nav-link active py-1 rounded-1 fs-6 px-lg-2 px-xl-3 px-xxl-4 fw-bold"
                                id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-basic" type="button"
                                role="tab" aria-controls="pills-home" aria-selected="true">
                                LIGHT
                            </button>
                        </div>
                        <div class="nav-item bg-light col-4 px-lg-0 px-2 rounded-1" role="presentation">
                            <button class="nav-link py-1 rounded-1 fs-6 px-lg-2 px-xl-3 px-xxl-4 fw-bold"
                                id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-standard" type="button"
                                role="tab" aria-controls="pills-profile" aria-selected="false">
                                PLUS
                            </button>
                        </div>
                        <div class="nav-item bg-light col-4 px-lg-0 rounded-1" role="presentation">
                            <button class="nav-link py-1 rounded-1  px-lg-2 px-xl-3 px-xxl-4 fs-6 fw-bold"
                                id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-premium" type="button"
                                role="tab" aria-controls="pills-contact" aria-selected="false">
                                ADVANCE
                            </button>
                        </div>
                    @endif
                </div>

                <div class="tab-content" id="pills-tabContent">
                    @if ($service->service_type == 0)
                        {{-- 🟢 Single Package --}}
                        @php $detail = $serviceDetails->first(); @endphp
                        <div class="tab-pane fade show active" id="pills-basic" data-detail_id="{{$detail->id}}" role="tabpanel">
                            <h5 class="w-100">{{ $detail->title }}
                                <span class="float-end">
                                    {{ Session::get('currency_symbol') }}
                                    {{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
                                </span>
                            </h5>

                            <p class="w-100 fs-14 mt-3">
                                <i class="fa-regular fa-clock"></i> Delivery
                                <span class="float-end">{{ $detail->delivery_time }} days</span>
                            </p>

                            <p class="w-100 fs-14">
                                <i class="fas fa-sync-alt"></i> Revisions
                                <span class="float-end">{{ $detail->revision }}</span>
                            </p>
                            {{-- 🔹 Features --}}
                            @foreach ($availableFeatures as $tier)
                                @php
                                    $selected = data_get($userFeatures, "{$detail->id}")?->firstWhere(
                                        'service_tier_id',
                                        $tier->id,
                                    );
                                @endphp
                                <p class="w-100 fs-14">{{ $tier->name }}
                                    <span
                                        class="float-end {{ $selected && $selected->service_value_tier === 'yes' ? 'text-green' : 'text-danger' }}">
                                        <i
                                            class="fa-solid {{ $selected && $selected->service_value_tier === 'yes' ? 'fa-check' : 'fa-xmark' }} fs-16"></i>
                                    </span>
                                </p>
                            @endforeach
                        </div>
                    @elseif($service->service_type == 1)
                        {{-- 🔵 Multi-Package --}}
                        @foreach ($serviceDetails as $index => $detail)
                            @php
                                $labels = ['Basic', 'Standard', 'Premium'];
                                $slug = strtolower($labels[$index] ?? 'package');
                            @endphp

                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                id="pills-{{ $slug }}" role="tabpanel" data-detail_id="{{$detail->id}}">
                                <h5 class="w-100">{{ $detail->title }}
                                    <span class="float-end">
                                        {{ Session::get('currency_symbol') }}
                                        {{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
                                    </span>
                                </h5>

                                <p class="w-100 fs-14 mt-3">
                                    <i class="fa-regular fa-clock"></i> Delivery
                                    <span class="float-end">{{ $detail->delivery_time }} days</span>
                                </p>

                                <p class="w-100 fs-14">
                                    <i class="fas fa-sync-alt"></i> Revisions
                                    <span class="float-end">{{ $detail->revision }}</span>
                                </p>

                                {{-- 🔹 Features --}}
                                @foreach ($availableFeatures as $tier)
                                    @php
                                        $selected =
                                            $userFeatures[$detail->id]->firstWhere('service_tier_id', $tier->id) ??
                                            null;
                                    @endphp
                                    <p class="w-100 fs-14">{{ $tier->name }}
                                        <span
                                            class="float-end {{ $selected && $selected->service_value_tier === 'yes' ? 'text-green' : 'text-danger' }}">
                                            <i
                                                class="fa-solid {{ $selected && $selected->service_value_tier === 'yes' ? 'fa-check' : 'fa-xmark' }} fs-16"></i>
                                        </span>
                                    </p>
                                @endforeach

                                {{-- ✅ Order Now Button --}}
                                <button class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold mt-3"
                                    onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';">
                                    Order Now
                                </button>
                            </div>
                        @endforeach
                    @else
                        <p class="text-danger">Invalid service type.</p>
                    @endif
                </div>


                <div class="w-100 mt-1">
                    {{--  <button onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';"
                        class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold">Order Nowfff</button>  --}}
                    <button class="btn btn-sm btn-success bg-dgreen border-0 w-100 fw-bold mt-2"
                        onclick="location.href='#compare';">Compare Plans</button>
                </div>
            </div>

            <!-- Short Profile  -->
            <div class="p-4 mt-3 shadow-sm border rounded-2 bg-white text-center">
                <!-- <img class="rounded-circle"
                                                                                                                                                                                                                src="{{ $service->user->image == '' ? 'https://ionicframework.com/docs/demos/api/avatar/avatar.svg' : userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, 'profile_image') }}"
                                                                                                                                                                                                                alt="{{ $service->user->username }}" width="70"> -->

                <img src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, null) }}"
                    alt="@lang('Profile Image')" class="rounded-circle me-2 prfl_img" width="70">

                <div class="ms-3 ms-md-2">
                    <a href="{{ route('profile', [slug($service->user->username), encrypt($service->user->id)]) }}"
                        class="m-0 fs-18 fw-bold links-c2">
                        {{ $service->user->username }}
                    </a>
                    <p class="m-0 text-secondary fs-14">
                        <i class="fas fa-star fs-12 text-warning"></i>
                        @php
                            $rate = DB::table('review_ratings')->where('pro_id', $service->user->id)->avg('rating');
                        @endphp
                        {{ round($rate, 0) }}
                        <i class="fas fa-map-marker-alt ms-2"></i> {{ __(@$service->user->address->country) }}
                        <span class="text-green ms-2">
                            @if ($service->user->status == 1)
                                Verified <i class="fas fa-check fs-12"></i>
                            @else
                                Unverified <i class="fas fa-times fs-12"></i>
                            @endif
                        </span>
                    </p>
                    <span class="me-2 text-secondary fs-14">
                        <i class="fas fa-user fs-12"></i> Member Since
                        {{ showDateTime($service->user->created_at, 'd M Y') }}
                    </span>
                </div>


                <a href="{{ route('user', ['id' => $service->user_id]) }}"
                    class="btn btn-sm btn-dark bg-dgreen w-100 fw-semibold mt-3">
                    Contact Me
                </a>

            </div>


            <!--links section-->
            {{--  <div class="bg-white p-4 mt-3 shadow-sm border fs-14 rounded-2">

                <h6 class="">Shared</h6>
                <p class="bg-light overflow-hidden p-1 mb-0">
                    https://titanlance.com/job/details/eyJpdiI6IjlPMVh1a1NwVFBWQ2hMd0tTM253UEE9PSIsInZhbHVlIjoiUnowVEU5Nk1CQS8ya0h3K3N4bDhydz09IiwibWFjIjoiNTY2YTdiMTZkMzEyZjFkMmZjMWRjNjE4ZDdjYmUyOGU1OWY5MjkxYjFhN2EzMjM0M2QwOWY0ZjQ3MjBhMjcyOCIsInRhZyI6IiJ9
                </p>
                <a class="" href=""> Copy link </a> <br> <br>

                <i class="fa-brands fa-square-facebook fs-4"></i>
                <i class="fa-brands fa-instagram ms-2 fs-4"></i>
                <i class="fa-brands fa-x-twitter ms-2 fs-4"></i>
                <i class="fa-brands fa-linkedin ms-2 fs-4"></i>

            </div>  --}}
        </div>

        <!--Left Section---xxxxxxxxxxxxxxx-->
        <div class="col-lg-8 mt-3 mt-lg-0 mb-3" id="contents">
            <!-- Main Box 1 -->
            @if ($service->image)
                <div class="px-3 py-2 shadow-sm border rounded-2 bg-white">
                    @php
                        $images = [];

                        // Main image
                        $images[] = getImage(
                            'assets/images/service/' . $service->image,
                            imagePath()['service']['size'],
                        );

                        // Additional images
                        if ($service->optionalImage && $service->optionalImage->count()) {
                            foreach ($service->optionalImage as $img) {
                                $images[] = getImage(
                                    'assets/images/optionalService/' . $img->image,
                                    imagePath()['service']['size'],
                                );
                            }
                        }

                    @endphp

                    @if (count($images) > 0)
                        <div class="carousel">
                            {{-- Slides --}}
                            @foreach ($images as $index => $img)
                                <img class="slides {{ $index === 0 ? 'active-slide' : '' }} " src="{{ $img }}"
                                    alt="@lang('service-image')">
                            @endforeach

                            {{-- Navigation --}}
                            <div class="nav-buttons">
                                <button class="prev" onclick="changeSlide(-1)">❮</button>
                                <button class="next" onclick="changeSlide(1)">❯</button>
                            </div>

                            {{-- Thumbnails --}}
                            <div class="thumbnails">
                                @foreach ($images as $index => $img)
                                    <img class="thumbnail {{ $index === 0 ? 'active' : '' }}" src="{{ $img }}"
                                        onclick="showSlide({{ $index }})">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{--  Details  --}}
            @if (!is_null($service?->description))
                <div class=" px-3 py-2 shadow-sm border border-1 rounded-2 bg-white mt-3">
                    <p class="mt-2 fs-5"> Service Details</p>
                    <div class="ms-3 fs-14">
                        {!! $service?->description !!}
                    </div>
                </div>
            @endif

            {{--  Skills & Experties  --}}
            <div class="px-3 py-2 mt-3 shadow-sm border border-1 rounded-2 bg-white">
                <p class="mt-2 fs-5">Skills and Expertise</p>
                <div class="ms-3 fs-14">

                    @if ($service->skills && $service->skills->count() > 0)
                        @foreach ($service->skills->groupBy('category') as $category => $categorySkills)
                            @php
                                $cate = json_decode($category, true);
                            @endphp
                            <h6 class="mb-0 mt-3">{{ $cate['name'] }}</h6>
                            <div class="inner-carousel">
                                <div class="track">
                                    @foreach ($categorySkills as $skill)
                                        <div class="card-container">
                                            <div class="card-c">{{ $skill->name }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No skills listed</p>
                    @endif
                </div>
            </div>

            {{--  Compare Plans  --}}
            <div class="px-md-3 py-3 mt-3 shadow-sm border rounded-2 bg-white" id="compare">
                <p class="fs-5 ms-3 ms-md-0">Compare Plans</p>

                <div class="d-flex mt-3">
                    <!-- Features Column -->
                    <div class="w-100 border fs12">
                        <h5 class="bg-lgreen text-dark fw-bold rounded m-md-2 p-3 p-md-4 mb-3">Plans</h5>
                        <p class="ps-2 py-2 m-0 ps-md-3 border-top fw-bold">Price</p>
                        <p class="ps-2 py-2 m-0 ps-md-3 border-top"><i class="fa-regular fa-clock"></i> Delivery</p>
                        <p class="ps-2 py-2 m-0 ps-md-3 border-top"><i class="fas fa-sync-alt"></i> Revisions</p>

                        @foreach ($availableFeatures as $tier)
                            <p class="ps-2 py-2 m-0 ps-md-3 border-top">{{ $tier->name }}</p>
                        @endforeach

                        <p class="ps-2 py-2 m-0 ps-md-3 border-top border-bottom">Order</p>
                    </div>

                    @if ($service->service_type == 0)
                        <!-- Single Package -->
                        @php $detail = $serviceDetails->first(); @endphp
                        <div class="w-50 text-center border-top border-bottom border-end fs12 bg-light">
                            <h5 class="bg-lgreen text-dark fw-bold rounded m-md-2 p-3 p-md-4 mb-3">{{ $detail->title }}
                            </h5>
                            <p class="fw-bold border-top border-light">
                                {{ Session::get('currency_symbol') }}
                                {{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
                            </p>
                            <p class="border-top py-2 m-0">{{ $detail->delivery_time }} days</p>
                            <p class="border-top py-2 m-0">{{ $detail->revision }}</p>

                            @foreach ($availableFeatures as $tier)
                                @php
                                    $selected = data_get($userFeatures, "{$detail->id}")?->firstWhere(
                                        'service_tier_id',
                                        $tier->id,
                                    );
                                    $hasFeature = $selected && $selected->service_value_tier === 'yes';
                                @endphp
                                <p class="border-top py-2 m-0">
                                    <i
                                        class="fa-solid {{ $hasFeature ? 'fa-check text-green' : 'fa-xmark text-danger' }}"></i>
                                </p>
                            @endforeach

                            <p class="border-top py-2 m-0">
                                <button class="btn btn-success bg-dgreen2 fs-6 px-1 px-md-3"
                                    onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';">
                                    Order
                                </button>
                            </p>
                        </div>
                    @elseif($service->service_type == 1)
                        <!-- Multi-Package -->
                        @foreach ($serviceDetails as $index => $detail)
                            @php
                                $labels = ['Light', 'Plus', 'Advance'];
                                $slug = strtolower($labels[$index] ?? 'package');
                                $bgClass = $index % 2 == 0 ? 'bg-light' : '';
                                $borderClass = $index < count($serviceDetails) - 1 ? 'border-end' : '';
                            @endphp

                            <div
                                class="w-50 text-center border-top border-bottom {{ $borderClass }} fs12 {{ $bgClass }}">
                                <h5 class="bg-lgreen fw-bold rounded m-md-2 p-3 p-md-4 mb-3">
                                    {{ $labels[$index] ?? $detail->title }}</h5>
                                <p class="fw-bold border-top {{ $bgClass ? 'border-light' : 'border-white' }}">
                                    {{ Session::get('currency_symbol') }}
                                    {{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
                                </p>
                                <p class="border-top py-2 m-0">{{ $detail->delivery_time }} days</p>
                                <p class="border-top py-2 m-0">{{ $detail->revision }}</p>

                                @foreach ($availableFeatures as $tier)
                                    @php
                                        $selected =
                                            $userFeatures[$detail->id]->firstWhere('service_tier_id', $tier->id) ??
                                            null;
                                        $hasFeature = $selected && $selected->service_value_tier === 'yes';
                                    @endphp
                                    <p class="border-top py-2 m-0">
                                        <i
                                            class="fa-solid {{ $hasFeature ? 'fa-check text-green' : 'fa-xmark text-danger' }}"></i>
                                    </p>
                                @endforeach

                                <p class="border-top py-2 m-0">
                                    <button class="btn btn-success bg-dgreen2 fs-6 px-1 px-md-3"
                                        onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';">
                                        Order
                                    </button>
                                </p>
                            </div>
                        @endforeach
                    @else
                        <div class="w-100 text-center p-4">
                            <p class="text-danger">Invalid service type.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!--Reviews-->
            <div class=" px-3 py-2 mt-3 shadow-sm border border-1 rounded-2 bg-white">
                <h5 class="mt-2">{{ $allcount + $otherallcount + $joballcount }} @lang('reviews')</h5>
                <div class="row mb-3 ps-3">
                    @php
                        // $rate1 = DB::table('review_ratings')->where('pro_id', $service->user->id)->avg('rating');
                        // $rate_count = DB::table('review_ratings')->where('pro_id', $service->user->id)->count();

                        $rate1 = DB::table('review_ratings')->where('service_id', $service->id)->avg('rating');
                        $rate_count2 = DB::table('review_ratings')->where('service_id', $service->id)->count();

                        // $rate3 = DB::table('review_ratings')->where('provider_id', $service->user->id)->avg('rating');
                        // $rate_count3 = DB::table('review_ratings')->where('provider_id', $service->user->id)->count();

                        $overallRating = round(($rate1) / 2, 1);

                        $filledStars = floor($overallRating);
                        $halfStar = $overallRating - $filledStars >= 0.5;
                        $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                    @endphp
                    <!-- Left  -->
                    <div class="col-md-6 col-xl-6 col-lg-6 text-start">
                        <div class="col mb-3">
                            <div class="text-start fw-semibold">Overall Rating
                                <span class="text-start ms-2 fw-bold align-items-center">
                                    {{ $overallRating }}

                                    {{-- Filled Stars --}}
                                    @for ($i = 0; $i < $filledStars; $i++)
                                        <i class="fa fa-star star fs-10 text-warning"></i>
                                    @endfor

                                    {{-- Half Star --}}
                                    @if ($halfStar)
                                        <i class="fa fa-star-half-alt star fs-10 text-warning"></i>
                                    @endif

                                    {{-- Empty Stars --}}
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <i class="fa fa-star star fs-10 text-secondary"></i>
                                    @endfor
                                </span>
                            </div>
                        </div>
                        @php
                            $total =
                                $five +
                                $otherfive +
                                $jobfive +
                                ($four + $otherfour + $jobfour) +
                                ($three + $otherthree + $jobthree) +
                                ($two + $othertwo + $jobtwo) +
                                ($one + $otherone + $jobone);
                        @endphp
                        <!-- 5-star rating row -->
                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">5<i
                                    class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($five + $otherfive + $jobfive, $total) }}%;"
                                    aria-valuenow="{{ getPercent($five + $otherfive + $jobfive, $total) }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="rating-text">({{ $five + $otherfive + $jobfive }})</span>
                        </div>
                        <!-- 4-star rating row -->
                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">4<i
                                    class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($four + $otherfour + $jobfour, $total) }}%;"
                                    aria-valuenow="{{ getPercent($four + $otherfour + $jobfour, $total) }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="rating-text">({{ $four + $otherfour + $jobfour }})</span>
                        </div>

                        <!-- 3-star rating row -->
                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">3<i
                                    class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($three + $otherthree + $jobthree, $total) }}%;"
                                    aria-valuenow="{{ getPercent($three + $otherthree + $jobthree, $total) }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="rating-text">({{ $three + $otherthree + $jobthree }})</span>
                        </div>

                        <!-- 2-star rating row -->
                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">2<i
                                    class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($two + $othertwo + $jobtwo, $total) }}%;"
                                    aria-valuenow="{{ getPercent($two + $othertwo + $jobtwo, $total) }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="rating-text">({{ $two + $othertwo + $jobtwo }})</span>
                        </div>

                        <!-- 1-star rating row -->
                        <div class="d-flex align-items-center fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">1<i
                                    class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($one + $otherone + $jobone, $total) }}%;"
                                    aria-valuenow="{{ getPercent($one + $otherone + $jobone, $total) }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="rating-text">({{ $one + $otherone + $jobone }})</span>
                        </div>
                    </div>

                    <div class="col-md-5 col-xl-5 col-lg-5 pt-4 pt-md-5 ps-5 text-start">
                        <p class="fs-5">Rating Breakdown</p>

                        {{--  <p class="fs-14 mb-1">
                            Skills
                            <span class="float-end">
                                {{ round(($skill + $otherskill + $jobskill) / 3, 2) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>  --}}

                        <p class="fs-14 mb-1">
                            Seller Communication level
                            <span class="float-end">
                                {{ round(($communication + $othercommunication + $jobcommunication) / 3, 2) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>

                        <p class="fs-14 mb-1">
                            Recommended to a friend
                            <span class="float-end">
                                {{ round(($recommend + $otherrecommend + $jobrecommend) / 3, 2) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>

                        <p class="fs-14 mb-1">
                            Service & described
                            <span class="float-end">
                                {{ round(($described + $otherdescribed + $jobdescribed) / 3, 2) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>
                    </div>
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="nav-link" data-toggle="tab" href="#tabs-this"
                                role="tab" style="padding: 8px;">This service</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-link" data-toggle="tab" href="#tabs-other" role="tab"
                                style="padding: 8px;">All Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="nav-link" data-toggle="tab" href="#tabs-jobs" role="tab"
                                style="padding: 8px;">All Jobs</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-this" role="tabpanel">
                            @foreach ($all as $rev)
                                @php
                                    $user = DB::table('users')->where('id', $rev->user_id)->first();
                                @endphp
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="row no-gutters">
                                            <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                                @if ($user->image == '')
                                                    <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                        style="width:50px;height:50px;border-radius:50%;">
                                                @else
                                                    <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $user->image, 'profile_image') }}"
                                                        alt="{{ __($user->username) }}"
                                                        style="width:50px;height:50px;border-radius:50%;">
                                                @endif

                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-10 col-10">
                                                <h6>{{ $user->firstname }} {{ $user->lastname }}</h6>
                                                <p><i
                                                        class="lni lni-map-marker mr-2"></i>{{ $rev->user->address->country }}
                                                </p>
                                                <div class="mb-2">
                                                    @if ($rev->rating == 5)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <span
                                                                style="color:gold;">5</span></span>
                                                    @elseif($rev->rating == 4)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i>
                                                            <span style="color:gold;">4</span></span>
                                                    @elseif($rev->rating == 3)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                                                style="color:gold;">3</span></span>
                                                    @elseif($rev->rating == 2)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                                class="fa fa-star"></i> <span
                                                                style="color:gold;">2</span></span>
                                                    @elseif($rev->rating == 1)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                                class="fa fa-star"></i> <span
                                                                style="color:gold;">1</span></span>
                                                    @endif
                                                    <span style="border-right: 1px solid #e4e5e7;margin: 2px 8px;"
                                                        class="inline-divider "></span><span>{{ diffforhumans($rev->created_at) }}</span><br>
                                                </div>
                                                <p>{{ $rev->review }}</p>
                                                @if ($rev->reply != '')
                                                    <div class="row no-gutters">
                                                        <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                                            @if ($service->user->image == '')
                                                                <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                                    style="width:50px;height:50px;border-radius:50%;">
                                                            @else
                                                                <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, 'profile_image') }}"
                                                                    alt="{{ __($service->user->username) }}"
                                                                    style="width:50px;height:50px;border-radius:50%;">
                                                            @endif

                                                        </div>
                                                        <div class="col-lg-11 col-md-11 col-sm-11 col-11">
                                                            <h6>{{ $service->user->firstname }}
                                                                {{ $service->user->lastname }}
                                                            </h6>
                                                            <p>{{ $rev->reply }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                            @endforeach
                            <div class="row" style="margin-top: -10px;">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <!-- links here -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="tabs-other" role="tabpanel">
                            @foreach ($otherall as $rev)
                                @php
                                    $user = DB::table('users')->where('id', $rev->user_id)->first();
                                @endphp
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="row no-gutters">
                                            <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                                @if ($user->image == '')
                                                    <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                        style="width:50px;height:50px;border-radius:50%;">
                                                @else
                                                    <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $user->image, 'profile_image') }}"
                                                        alt="{{ __($user->username) }}"
                                                        style="width:50px;height:50px;border-radius:50%;">
                                                @endif

                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-10 col-10">
                                                <h6>{{ $user->firstname }} {{ $user->lastname }}</h6>
                                                <p><i
                                                        class="lni lni-map-marker mr-2"></i>{{ $rev->user->address->country }}
                                                </p>
                                                <div class="mb-2">
                                                    @if ($rev->rating == 5)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <span
                                                                style="color:gold;">5</span></span>
                                                    @elseif($rev->rating == 4)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i>
                                                            <span style="color:gold;">4</span></span>
                                                    @elseif($rev->rating == 3)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                                                style="color:gold;">3</span></span>
                                                    @elseif($rev->rating == 2)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                                class="fa fa-star"></i> <span
                                                                style="color:gold;">2</span></span>
                                                    @elseif($rev->rating == 1)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                                class="fa fa-star"></i> <span
                                                                style="color:gold;">1</span></span>
                                                    @endif
                                                    <span style="border-right: 1px solid #e4e5e7;margin: 2px 8px;"
                                                        class="inline-divider "></span><span>{{ diffforhumans($rev->created_at) }}</span><br>
                                                </div>
                                                <p>{{ $rev->review }}</p>
                                                @if ($rev->reply != '')
                                                    <div class="row no-gutters">
                                                        <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                                            @if ($service->user->image == '')
                                                                <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                                    style="width:50px;height:50px;border-radius:50%;">
                                                            @else
                                                                <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, 'profile_image') }}"
                                                                    alt="{{ __($service->user->username) }}"
                                                                    style="width:50px;height:50px;border-radius:50%;">
                                                            @endif

                                                        </div>
                                                        <div class="col-lg-11 col-md-11 col-sm-11 col-11">
                                                            <h6>{{ $service->user->firstname }}
                                                                {{ $service->user->lastname }}
                                                            </h6>
                                                            <p>{{ $rev->reply }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                            @endforeach
                            <div class="row" style="margin-top: -10px;">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <!--  links here -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="tabs-jobs" role="tabpanel">
                            @foreach ($joball as $rev)
                                @php
                                    $user = DB::table('users')->where('id', $rev->user_id)->first();
                                @endphp
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="row no-gutters">
                                            <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                                @if ($user->image == '')
                                                    <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                        style="width:50px;height:50px;border-radius:50%;">
                                                @else
                                                    <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                        style="width:50px;height:50px;border-radius:50%;">
                                                    {{--  <img src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $user->image, 'profile_image') }}"
                                                        alt="{{ __($user->username) }}"
                                                        style="width:50px;height:50px;border-radius:50%;">  --}}
                                                @endif

                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-10 col-10">
                                                <h6>{{ $user->firstname }} {{ $user->lastname }}</h6>
                                                <p><i
                                                        class="lni lni-map-marker mr-2"></i>{{ $rev->user->address->country }}
                                                </p>
                                                <div class="mb-2">
                                                    @if ($rev->rating == 5)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <span
                                                                style="color:gold;">5</span></span>
                                                    @elseif($rev->rating == 4)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i>
                                                            <span style="color:gold;">4</span></span>
                                                    @elseif($rev->rating == 3)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i>
                                                            <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                                                style="color:gold;">3</span></span>
                                                    @elseif($rev->rating == 2)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                                class="fa fa-star"></i> <span
                                                                style="color:gold;">2</span></span>
                                                    @elseif($rev->rating == 1)
                                                        <span><i class="fa fa-star" style="color:gold;"></i> <i
                                                                class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                                class="fa fa-star"></i> <span
                                                                style="color:gold;">1</span></span>
                                                    @endif
                                                    <span style="border-right: 1px solid #e4e5e7;margin: 2px 8px;"
                                                        class="inline-divider "></span><span>{{ diffforhumans($rev->created_at) }}</span><br>
                                                </div>
                                                <p>{{ $rev->review }}</p>
                                                @if ($rev->reply != '')
                                                    <div class="row no-gutters">
                                                        <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                                            @if ($service->user->image == '')
                                                                <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                                    style="width:50px;height:50px;border-radius:50%;">
                                                            @else
                                                                <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, 'profile_image') }}"
                                                                    alt="{{ __($service->user->username) }}"
                                                                    style="width:50px;height:50px;border-radius:50%;">
                                                            @endif

                                                        </div>
                                                        <div class="col-lg-11 col-md-11 col-sm-11 col-11">
                                                            <h6>{{ $service->user->firstname }}
                                                                {{ $service->user->lastname }}
                                                            </h6>
                                                            <p>{{ $rev->reply }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                            @endforeach
                            <div class="row" style="margin-top: -10px;">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <!-- links here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--Compare plans-->
            {{--  @if ($service->service_type == 1)
                <div class="px-md-3 py-3 mt-3 shadow-sm border rounded-2 bg-white" id="compare">
                    <h5 class="fw-bold fs-18 text-dgreen ms-3 ms-md-0">Compare Plans</h5>
                    <div class="d-flex mt-3 overflow-auto">
                        <!-- Column 1: Features List -->
                        <div class="w-100 border fs12 border-end-0">
                            <h6 class="bg-dgreen2 text-light fs12 fw-bold p-2 ps-md-3">Plans</h6>
                            <p class="ps-2 ps-md-3 fw-bold">Price</p>
                            <p class="ps-2 ps-md-3"><i class="fa-regular fa-clock"></i> Delivery</p>
                            <p class="ps-2 ps-md-3"><i class="fas fa-sync-alt"></i> Revisions</p>
                            @foreach ($availableFeatures as $feature)
                                <p class="ps-2 ps-md-3">{{ $feature->name }}</p>
                            @endforeach
                        </div>

                        <!-- Loop through each plan -->
                        @foreach ($serviceDetails as $detail)
                            <div class="w-50 text-center fs12 border border-end-0">
                                <h6 class="bg-dgreen2 text-light fw-bold fs12 p-2">{{ $detail->title }}</h6>
                                <p class="fw-bold">
                                    {{ Session::get('currency_symbol') }}{{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
                                </p>
                                <p>{{ $detail->delivery_time }} days</p>
                                <p>{{ $detail->revision }}</p>

                                @foreach ($availableFeatures as $feature)
                                    @php
                                        $selected =
                                            $userFeatures[$detail->id]->firstWhere('service_tier_id', $feature->id) ??
                                            null;
                                    @endphp

                                    @if ($feature->type === 'checkbox')
                                        <p>
                                            @if ($selected && $selected->service_value_tier === 'yes')
                                                <i class="fa-solid fa-check text-success"></i>
                                            @else
                                                <i class="fa-solid fa-xmark text-danger"></i>
                                            @endif
                                        </p>
                                    @else
                                        <p>{{ $selected->service_value_tier ?? '-' }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif  --}}

            <div class="px-md-3 py-3 mt-3 shadow-sm border rounded-2 bg-white">
                <p class=" fs-5 ms-3 ms-md-0">Other services by {{ $service?->user?->name }}.</p>

                <div class="row">
                    @foreach ($otherServices as $index => $service)
                        @php
                            $progress = ($service->rating / 5) * 100;
                        @endphp

                        <div class="col-md-4">
                            <div class="card shadow-sm border h-100">


                                <img src="{{ getImage('assets/images/service/' . $service->image, imagePath()['service']['size']) }}"
                                    class="card-img-top p-2 pb-0" alt="{{ $service->title }}">

                                <div class="card-body">

                                    <div class="row align-items-center">

                                        {{-- Seller Info --}}
                                        <div class="col-6 col-lg-8 mb-2">
                                            <div class="d-flex flex-row gap-2 align-items-center fs-10">

                                                <img class="rounded-circle"
                                                    src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, null) }}"
                                                    width="40" height="40" alt="{{ $service->user->name }}">

                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="mb-0 fs-12 fw-semibold">
                                                        {{ $service->user->name }}
                                                    </p>
                                                    <p class="mb-0 text-secondary fs-10">
                                                        {{ $service->user->designation ?? 'Service Provider' }}
                                                    </p>
                                                </div>

                                            </div>
                                        </div>

                                        {{-- Rank & Rating --}}
                                        <div class="col-6 col-lg-4 text-end">
                                            <div class="d-flex flex-column align-items-end justify-content-center">

                                                <p class="mb-0 fs-10 fw-semibold">
                                                    Rank {{ $index + 1 }}
                                                </p>

                                                <div class="progress" style="width: 80%; height: 3px;">
                                                    <div class="progress-bar bg-green" role="progressbar"
                                                        style="width: {{ $progress }}%;"
                                                        aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                <p class="mb-0 fs-10 fw-semibold">
                                                    <i class="fa fa-star star"></i>
                                                    {{ number_format($service->rating, 1) }}
                                                    ({{ $service->reviews_count ?? 0 }})
                                                </p>

                                            </div>
                                        </div>

                                    </div>

                                    {{-- Service Title --}}
                                    <h6 class="fs-12 fw-normal mt-2">
                                        {{ Str::limit($service->title, 80) }}
                                    </h6>

                                    {{-- Pricing --}}
                                    <div class="w-100 mt-2">
                                        <p class="mb-0 fs-10 fw-semibold float-end">
                                            Starting From:
                                            <span class="text-green">
                                                ${{ number_format($service?->latestDetail->price, 2) }}
                                            </span>
                                        </p>

                                        <p class="mb-0 fs-10 fw-semibold">
                                            Delivery:
                                            <span class="text-green">
                                                {{ $service?->latestDetail->delivery_time }} Days
                                            </span>
                                        </p>
                                    </div>

                                    {{-- CTA --}}
                                    <p class="mt-3 mb-0">
                                        <a href="#" class="btn btn-sm btn-success bg-dgreen border-0 float-end">
                                            Order Now
                                        </a>
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div> <!-- End of col-lg-8 -->
    </div> <!-- End of main row -->

    <!-- Related Services - Moved outside the main column to cover full width -->
    @if (count($releatedServices) > 0)
        <div class="full-width-section mb-5">
            <div class="px-3 px-md-0"> <!-- Container for content -->
                <h4 class="mb-3">Related Services</h4>
                <div class="row">
                    @foreach ($releatedServices as $index => $service)
                        @php
                            $progress = ($service->rating / 5) * 100;
                        @endphp

                        <div class="col-md-4">
                            <div class="card shadow-sm border h-100">

                                <img src="{{ getImage('assets/images/service/' . $service->image, imagePath()['service']['size']) }}"
                                    class="card-img-top p-2 pb-0" alt="{{ $service->title }}">

                                <div class="card-body">

                                    <div class="row align-items-center">

                                        {{-- Seller Info --}}
                                        <div class="col-6 col-lg-8 mb-2">
                                            <div class="d-flex flex-row gap-2 align-items-center fs-10">

                                                <img class="rounded-circle"
                                                    src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, null) }}"
                                                    width="40" height="40" alt="{{ $service->user->name }}">

                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="mb-0 fs-12 fw-semibold">
                                                        {{ $service->user->name }}
                                                    </p>
                                                    <p class="mb-0 text-secondary fs-10">
                                                        {{ $service->user->designation ?? 'Service Provider' }}
                                                    </p>
                                                </div>

                                            </div>
                                        </div>

                                        {{-- Rank & Rating --}}
                                        <div class="col-6 col-lg-4 text-end">
                                            <div class="d-flex flex-column align-items-end justify-content-center">

                                                <p class="mb-0 fs-10 fw-semibold">
                                                    Rank {{ $index + 1 }}
                                                </p>

                                                <div class="progress" style="width: 80%; height: 3px;">
                                                    <div class="progress-bar bg-green" role="progressbar"
                                                        style="width: {{ $progress }}%;"
                                                        aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                <p class="mb-0 fs-10 fw-semibold">
                                                    <i class="fa fa-star star"></i>
                                                    {{ number_format($service->rating, 1) }}
                                                    ({{ $service->reviews_count ?? 0 }})
                                                </p>

                                            </div>
                                        </div>

                                    </div>

                                    {{-- Service Title --}}
                                    <h6 class="fs-12 fw-normal mt-2">
                                        {{ Str::limit($service->title, 80) }}
                                    </h6>

                                    {{-- Pricing --}}
                                    <div class="w-100 mt-2">
                                        <p class="mb-0 fs-10 fw-semibold float-end">
                                            Starting From:
                                            <span class="text-green">
                                                ${{ number_format($service?->latestDetail->price, 2) }}
                                            </span>
                                        </p>

                                        <p class="mb-0 fs-10 fw-semibold">
                                            Delivery:
                                            <span class="text-green">
                                                {{ $service?->latestDetail->delivery_time }} Days
                                            </span>
                                        </p>
                                    </div>

                                    {{-- CTA --}}
                                    <p class="mt-3 mb-0">
                                        <a href="#" class="btn btn-sm btn-success bg-dgreen border-0 float-end">
                                            Order Now
                                        </a>
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    @endif

@endsection
@section('script')
    <script type="text/javascript">
        $(document).on("click", ".loveHeartAction", function() {
            let id = $(this).data('serviceid');
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                url: "{{ route('user.favorite.service') }}",
                method: "POST",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                    } else {
                        $.each(response, function(i, val) {
                            notify('error', val);
                        });
                    }
                }
            });
        });

        let slideIndex = 0;
        const slides = document.querySelectorAll('.slides');
        const thumbnails = document.querySelectorAll('.thumbnail');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active-slide', i === index);
                thumbnails[i].classList.toggle('active', i === index);
            });
            slideIndex = index;
        }

        function changeSlide(direction) {
            slideIndex += direction;
            if (slideIndex < 0) slideIndex = slides.length - 1;
            if (slideIndex >= slides.length) slideIndex = 0;
            showSlide(slideIndex);
        }

        $(document).ready(function() {
            $('#show-reply-area').click(function() {
                $('.reply-form-area').slideToggle("slow");
            });
        });

        document.addEventListener('shown.bs.tab', function (event) {
        // Get target tab pane ID
        let targetPaneId = event.target.getAttribute('data-bs-target');

        // Find the active tab pane
        let activePane = document.querySelector(targetPaneId);

        if (activePane) {
            let detailId = activePane.getAttribute('data-detail_id');
            console.log('Selected detail ID:', detailId);
        }
    });
    </script>
@endsection
