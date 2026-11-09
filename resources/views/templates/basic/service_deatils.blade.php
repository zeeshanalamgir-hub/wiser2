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
        .active-slide {
            width: 500px;           /* fixed width for all slides */
            height: 500px;          /* fixed height for all slides */
            overflow: hidden;       /* crop extra part of image if needed */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;     /* optional for nice corners */
            margin: 0 auto;         /* center the slide */
            object-fit: cover !important;
        }

        /* Image inside slide */
        .active-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;       /* fills container, maintains aspect ratio, crops excess */
            display: block;
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

        /* Review Section Styles */
        .fs-12 { font-size: 12px; }
        .fs-14 { font-size: 14px; }
        .text-c8 { color: #8c8c8c; }
        .text-green { color: #28a745; }
        .text-dgreen2 { color: #2d6a4f; }
        .bg-lgreen3 { background-color: #e8f5e9; }
        .btn-light:hover { background-color: #e0e0e0; }
        .rounded-circle { object-fit: cover; }
        hr { margin: 1rem 0; opacity: 0.5; }
        .ms-4 { margin-left: 1.5rem; }
        .nav-tabs .nav-link {
            color: #037e89;
            cursor: pointer;
        }
        .nav-tabs .nav-link.active {
            color: #037e89;
            font-weight: bold;
            border-bottom: 2px solid #037e89;
        }
        .progress-bar {
            background-color: #28a745;
        }
        .review-item-wrapper {
            transition: all 0.3s ease;
        }
        .review-item-wrapper .d-flex {
            min-width: 0;
        }
        .review-item-wrapper .d-flex > img {
            flex-shrink: 0;
        }
        .review-item-wrapper .w-100 {
            min-width: 0;
            overflow-wrap: break-word;
            word-break: break-word;
        }
        .review-item-wrapper h5,
        .review-item-wrapper h6,
        .review-item-wrapper p {
            overflow-wrap: break-word;
            word-break: break-word;
        }
        .rating-text {
            min-width: 45px;
        }

        /* The shared .carousel-container is hard-coded to 900px (sized for
           the wider job-listing column) — here it sits in a narrower
           col-lg-8, so cap it to the available width instead of overflowing
           the card. */
        .skills-carousel .carousel-container {
            width: 100%;
            max-width: 900px;
        }



        /* This page's image-gallery slider (further down) also uses plain
           .prev/.next classes for its own nav buttons, with an unscoped
           ".prev, .next { color: white; background-color: ... }" rule —
           that bled into these carousel arrows too, making the icon
           invisible (white on white). Win on specificity instead of
           touching the gallery's rule. */
        .skills-carousel .navc .prev,
        .skills-carousel .navc .next {
            background-color: transparent;
            color: #444;
            border: none;
            padding: 0;
            font-size: 18px;
            width: auto;
            height: auto;
            box-shadow: none;
        }

        /* The shared .navc styles position the arrows with negative
           left/right (-15px / -30px) so they sit OUTSIDE the container,
           overflowing past the card's edge. Flush them against the
           container's own edge instead — no gap. */
        .skills-carousel .navc .prev {
            left: 0;
        }
        .skills-carousel .navc .next {
            right: 0;
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
        <h4>{{ $service?->title ?? '--' }}</h4>
        <div class="col-lg-4 order-second order-lg-last" style="overflow: visible;">
            <div class="bg-white p-4 shadow-sm border fs-14 rounded">
                <div class="nav nav-pills row mb-3" id="pills-tab" role="tablist">
                    @if ($service->service_type == '0' || is_null($service->service_type))
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
                                    $
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
                                        ${{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
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
                                {{-- @dd(Auth::user()->id,$service->user_id); --}}
                                @if($service->service_type == 1 && Auth::check() && Auth::id() != $service->user_id)
                                    <button class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold mt-3"
                                        onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';">
                                        Order Now
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-danger">Invalid service type.</p>
                    @endif
                </div>

                {{-- For Single Service --}}
                <div class="w-100 mt-1">
                    @if($service->service_type == 0 && Auth::check() && Auth::id() != $service->user_id)
                        <button onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';"
                            class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold">Order Now</button>
                    @endif
                        <button class="btn btn-sm btn-success bg-dgreen border-0 w-100 fw-bold mt-2"
                            onclick="location.href='#compare';">Compare Plans</button>
                </div>
            </div>

            <!-- Short Profile  -->
            <div class="p-4 mt-3 shadow-sm border rounded-2 bg-white text-center">
                @if (!is_null($service->user?->image))
                    <div class="d-flex justify-content-center">
                        <img class="rounded-circle border border-secondary img-fluid"
                            alt="{{ $service->user->username ?? 'Seller' }}"
                            src="{{ $service->user->image ? Storage::url($service->user->image) : asset('assets/images/default-profile.png') }}"
                            style="width: 80px;height:80px;object-fit: cover;">
                    </div>
                @else
                    <div class="d-flex justify-content-center">
                        <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                            style="height:80px;width:80px;font-size:20px">
                            {{ strtoupper(substr($service->user->firstname ?? 'A', 0, 1)) }}
                            {{ strtoupper(substr($service->user->lastname ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                @endif

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
                        <i class="fas fa-map-marker-alt ms-2"></i> {{ __(@$service?->user?->address?->country ?? 'N/A') }}
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
                @if(Auth::check() && Auth::id() != $service->user_id)
                    <a href="{{ route('user', ['id' => $service->user_id]) }}"
                        class="btn btn-sm btn-dark bg-dgreen w-100 fw-semibold mt-3">
                        Contact Me
                    </a>
                @endif
            </div>
        </div>

        <!--Left Section-->
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

            {{-- Details --}}
            @if (!is_null($service?->description))
                <div class="mt-3 px-3 py-2 shadow-sm border border-1 rounded-2 bg-white">
                    <p class="mt-2 fs-5"> Service Details</p>
                    <div class="ms-3 fs-14">
                        {!! $service?->description !!}
                    </div>
                </div>
            @endif

            {{-- Skills & Expertise --}}
            <div class="px-3 py-2 mt-3 shadow-sm border border-1 rounded-2 bg-white">
                <p class="mt-2 fs-5">Skills and Expertise</p>
                    <div class="ms-3 fs-14 skills-carousel">
                       @php
                            $hasSkills = $service->skills?->isNotEmpty();
                            $hasExtraSkills = $service->extraSkills?->isNotEmpty();
                        @endphp

                        @if ($hasSkills)
                            @foreach ($service->skills->groupBy('category') as $category => $categorySkills)
                                @php
                                    $cate = json_decode($category, true);
                                @endphp
                                <h6 class="mb-0 mt-3">{{ $cate['name'] ?? 'Category' }}</h6>
                                <div class="carousel-container">
                                    <div class="inner-carousel">
                                        <div class="track">
                                            @foreach ($categorySkills as $skill)
                                                <div class="card-container">
                                                    <div class="card-c">{{ $skill->name }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="navc">
                                        <button class="prev bg-white border-0"><i class="fa-solid fa-chevron-left"></i></button>
                                        <button class="next bg-white border-0"><i class="fa-solid fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($hasExtraSkills)
                            <h6 class="mb-0 mt-3">Additional Skills</h6>
                            <div class="carousel-container">
                                <div class="inner-carousel">
                                    <div class="track">
                                        @foreach ($service->extraSkills as $skill)
                                            <div class="card-container">
                                                <div class="card-c">{{ $skill?->skill ? $skill?->skill?->name : ($skill?->name ?? '--') }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="navc">
                                    <button class="prev bg-white border-0"><i class="fa-solid fa-chevron-left"></i></button>
                                    <button class="next bg-white border-0"><i class="fa-solid fa-chevron-right"></i></button>
                                </div>
                            </div>
                        @endif

                        @if (!$hasSkills && !$hasExtraSkills)
                            <p class="text-muted mt-3">No skills listed</p>
                        @endif
                    </div>
            </div>

            {{-- Compare Plans --}}
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
                        @if(Auth::check() && Auth::id() != $service->user_id)
                            <p class="ps-2 py-2 m-0 ps-md-3 border-top border-bottom">Order</p>
                        @endif
                    </div>

                    @if ($service->service_type == 0)
                        <!-- Single Package -->
                        @php $detail = $serviceDetails->first(); @endphp
                        <div class="w-50 text-center border-top border-bottom border-end fs12 bg-light">
                            <h5 class="bg-lgreen text-dark fw-bold rounded m-md-2 p-3 p-md-4 mb-3">{{ $detail->title }}
                            </h5>
                            <p class="fw-bold border-top border-light">
                                ${{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
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
                            @if(Auth::check() && Auth::id() != $service->user_id)
                                <p class="border-top py-2 m-0">
                                    <button class="btn btn-success bg-dgreen2 fs-6 px-1 px-md-3"
                                        onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';">
                                        Order
                                    </button>
                                </p>
                            @endif
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
                                    ${{ number_format($helpers->convert_to_currency(Session::get('currency'), $detail->price), 2) }}
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
                                @if(Auth::check() && Auth::id() != $service->user_id)
                                    <p class="border-top py-2 m-0">
                                        <button class="btn btn-success bg-dgreen2 fs-6 px-1 px-md-3"
                                            onclick="location.href='{{ route('user.service.booking', encrypt($detail->id)) }}';">
                                            Order
                                        </button>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="w-100 text-center p-4">
                            <p class="text-danger">Invalid service type.</p>
                        </div>
                    @endif
                </div>
            </div>


            {{-- Other Services --}}
            @if($otherServices->isNotEmpty())
                <div class="px-md-3 py-3 mt-3 shadow-sm border rounded-2 bg-white">
                    <p class=" fs-5 ms-3 ms-md-0">Other services by {{ $service?->user?->name }}.</p>

                    <div class="row gx-3 gy-3">
                        @foreach ($otherServices as $index => $oservice)
                            @php
                                $progress = ($oservice->rating / 5) * 100;
                            @endphp

                            <div class="col-md-4">
                                <div class="card shadow-sm border h-100">
                                    <img src="{{ getImage('assets/images/service/' . $oservice->image, imagePath()['service']['size']) }}"
                                        class="card-img-top p-2 pb-0" alt="{{ $oservice->title }}">

                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-lg-8 mb-2">
                                                <div class="d-flex flex-row gap-2 align-items-center fs-10">
                                                    {{-- <img class="rounded-circle"
                                                        src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $oservice->user->image, null) }}"
                                                        width="40" height="40" alt="{{ $oservice->user->name }}"> --}}
                                                        @if (!is_null($oservice->user->image))
                                                            <div class="d-flex justify-content-center">
                                                                <img class="rounded-circle border border-secondary img-fluid"
                                                                    alt="{{ $oservice->user->username ?? $oservice->user->name ?? 'Seller' }}"
                                                                    src="{{ $oservice->user->image ? Storage::url($oservice->user->image) : asset('assets/images/default-profile.png') }}"
                                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                                            </div>
                                                        @else
                                                            <div class="d-flex justify-content-center">
                                                                <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                                    style="height: 40px; width: 40px; font-size: 20px">
                                                                    {{ strtoupper(substr($oservice->user->firstname ?? 'A', 0, 1)) }}
                                                                    {{ strtoupper(substr($oservice->user->lastname ?? 'A', 0, 1)) }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 fs-12 fw-semibold">
                                                            {{ $oservice->user->name }}
                                                        </p>
                                                        <p class="mb-0 text-secondary fs-10">
                                                            {{ $oservice->user->designation ?? 'Service Provider' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

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
                                                        {{ number_format($oservice->rating, 1) }}
                                                        ({{ $oservice->reviews_count ?? 0 }})
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="fs-12 fw-normal mt-2">
                                            {{ Str::limit($oservice->title, 80) }}
                                        </h6>

                                        <div class="w-100 mt-2">
                                            <p class="mb-0 fs-10 fw-semibold float-end">
                                                Starting From:
                                                <span class="text-green">
                                                    ${{ number_format($oservice?->latestDetail->price, 2) }}
                                                </span>
                                            </p>
                                            <p class="mb-0 fs-10 fw-semibold">
                                                Delivery:
                                                <span class="text-green">
                                                    {{ $oservice?->latestDetail->delivery_time }} Days
                                                </span>
                                            </p>
                                        </div>

                                        <p class="mt-3 mb-0">
                                            <a href="{{route('service.details', encrypt($oservice->id))}}" class="btn btn-sm btn-success bg-dgreen border-0 float-end">
                                                Order Now
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif



            <div class="px-3 py-2 mt-3 shadow-sm border border-1 rounded-2 bg-white">
                <h5 class="mt-2">{{ $totalReviews ?? 0 }} @lang('reviews')</h5>
                <div class="row mb-3 ps-3">
                    @php
                        // Calculate overall rating for current service
                        $rate1 = $allcount > 0 ? App\Models\ReviewRating::where('service_id', $service->id)->avg('rating') : 0;
                        $overallRating = round($rate1, 1);
                        $filledStars = floor($overallRating);
                        $halfStar = $overallRating - $filledStars >= 0.5;
                        $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                        // Calculate total reviews for progress bars
                        $totalCurrentReviews = $five + $four + $three + $two + $one;
                        $totalCurrentReviews = $totalCurrentReviews > 0 ? $totalCurrentReviews : 1;

                        $totalOtherReviews = $otherfive + $otherfour + $otherthree + $othertwo + $otherone;
                        $totalOtherReviews = $totalOtherReviews > 0 ? $totalOtherReviews : 1;
                    @endphp

                    <!-- Left - Rating Summary -->
                    <div class="col-md-6 col-xl-6 col-lg-6 text-start">
                        <div class="col mb-3">
                            <div class="text-start fw-semibold">Overall Rating
                                <span class="text-start ms-2 fw-bold align-items-center">
                                    {{ number_format($overallRating, 1) }}
                                    @for ($i = 0; $i < $filledStars; $i++)
                                        <i class="fa fa-star star fs-10 text-warning"></i>
                                    @endfor
                                    @if ($halfStar)
                                        <i class="fa fa-star-half-alt star fs-10 text-warning"></i>
                                    @endif
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <i class="fa fa-star star fs-10 text-secondary"></i>
                                    @endfor
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">5<i class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($five, $totalCurrentReviews) }}%;"></div>
                            </div>
                            <span class="rating-text">({{ $five }})</span>
                        </div>

                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">4<i class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($four, $totalCurrentReviews) }}%;"></div>
                            </div>
                            <span class="rating-text">({{ $four }})</span>
                        </div>

                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">3<i class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($three, $totalCurrentReviews) }}%;"></div>
                            </div>
                            <span class="rating-text">({{ $three }})</span>
                        </div>

                        <div class="d-flex align-items-center mb-2 fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">2<i class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($two, $totalCurrentReviews) }}%;"></div>
                            </div>
                            <span class="rating-text">({{ $two }})</span>
                        </div>

                        <div class="d-flex align-items-center fw-semibold">
                            <span class="d-flex align-items-center me-2 rating-text">1<i class="fa fa-star star fs-12"></i></span>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ getPercent($one, $totalCurrentReviews) }}%;"></div>
                            </div>
                            <span class="rating-text">({{ $one }})</span>
                        </div>
                    </div>

                    <div class="col-md-5 col-xl-5 col-lg-5 pt-4 pt-md-5 ps-5 text-start">
                        <p class="fs-5">Rating Breakdown</p>
                        <p class="fs-14 mb-1">
                            Skills
                            <span class="float-end">
                                {{ number_format($skills ?: 0, 1) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>
                        <p class="fs-14 mb-1">
                            Seller Communication level
                            <span class="float-end">
                                {{ number_format($communication ?: 0, 1) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>
                        <p class="fs-14 mb-1">
                            Recommended to a friend
                            <span class="float-end">
                                {{ number_format($recommend ?: 0, 1) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>
                        <p class="fs-14 mb-1">
                            Service & described
                            <span class="float-end">
                                {{ number_format($described ?: 0, 1) }}
                                <i class="fa fa-star star fs-12"></i>
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Review Tabs -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tabs-this" role="tab">This service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tabs-other" role="tab">All Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tabs-jobs" role="tab">All Jobs</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- This Service Reviews -->
                    <div class="tab-pane fade show active" id="tabs-this" role="tabpanel">
                        @forelse($all as $index => $rev)
                            @php
                                $reviewUser = $rev->user;
                                $reviewUserImage = $reviewUser && $reviewUser->image ? Storage::url($reviewUser->image) : 'https://ionicframework.com/docs/demos/api/avatar/avatar.svg';
                            @endphp
                            <div class="review-item-wrapper {{ $index >= 2 ? 'd-none more-review-this' : '' }}">
                                <div class="d-flex mt-4">
                                    <img class="rounded-circle border border-secondary"
                                        src="{{ $reviewUserImage }}"
                                        width="55" height="55"
                                        alt="{{ $reviewUser->username ?? 'User' }}"
                                        style="object-fit: cover;">
                                    <div style="margin-left:10px;" class="w-100">
                                        <p class="float-end text-c8 fs-12 mb-1">{{ diffforhumans($rev->created_at) }}</p>
                                        <h5 class="mb-0 pt-1">{{ $reviewUser->firstname ?? '' }} {{ $reviewUser->lastname ?? '' }}
                                            @if($reviewUser && $reviewUser->status == 1)
                                                <i class="fa-solid fa-circle-check fs-12 text-green"></i>
                                            @endif
                                        </h5>
                                        <p class="fs-12 text-secondary mb-2">
                                            {{ $reviewUser->country ?? '' }}
                                            <i class="fa-solid fa-star text-warning ps-sm-2"></i>
                                            {{ number_format($rev->rating, 1) }}
                                        </p>
                                        <p class="fs-14 mb-2">{{ $rev->review }}</p>

                                        @if($rev->reply && $rev->reply != '')
                                            <div class="d-flex mt-3 ms-4">
                                                <img class="rounded-circle border border-secondary"
                                                    src="{{ $service->user->image ? Storage::url($service->user->image) : 'https://ionicframework.com/docs/demos/api/avatar/avatar.svg' }}"
                                                    width="40" height="40"
                                                    alt="{{ $service->user->username ?? 'Seller' }}"
                                                    style="object-fit: cover;">
                                                <div style="margin-left:10px;">
                                                    <h6 class="mb-0">{{ $service->user->firstname ?? '' }} {{ $service->user->lastname ?? '' }}</h6>
                                                    <p class="fs-14 mt-1 mb-0">{{ $rev->reply }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted">No reviews found for this service.</p>
                            </div>
                        @endforelse

                        @if($all->count() > 2)
                            <button class="toggle-review-btn w-100 fs-6 bg-lgreen3 btn btn-light rounded text-dgreen2 mt-3" data-target="this">
                                Show More ▼
                            </button>
                        @endif
                    </div>

                    <!-- All Services Reviews -->
                    <div class="tab-pane fade" id="tabs-other" role="tabpanel">
                        @forelse($otherall as $index => $rev)
                            @php
                                $reviewUser = $rev->user;
                                $reviewUserImage = $reviewUser && $reviewUser->image ? Storage::url($reviewUser->image) : 'https://ionicframework.com/docs/demos/api/avatar/avatar.svg';
                            @endphp
                            <div class="review-item-wrapper {{ $index >= 2 ? 'd-none more-review-other' : '' }}">
                                <div class="d-flex mt-4">
                                    <img class="rounded-circle border border-secondary"
                                        src="{{ $reviewUserImage }}"
                                        width="55" height="55"
                                        alt="{{ $reviewUser->username ?? 'User' }}"
                                        style="object-fit: cover;">
                                    <div style="margin-left:10px;" class="w-100">
                                        <p class="float-end text-c8 fs-12 mb-1">{{ diffforhumans($rev->created_at) }}</p>
                                        <h5 class="mb-0 pt-1">{{ $reviewUser->firstname ?? '' }} {{ $reviewUser->lastname ?? '' }}
                                            @if($reviewUser && $reviewUser->status == 1)
                                                <i class="fa-solid fa-circle-check fs-12 text-green"></i>
                                            @endif
                                        </h5>
                                        <p class="fs-12 text-secondary mb-2">
                                            {{ $reviewUser->country ?? 'Location not specified' }}
                                            <i class="fa-solid fa-star text-warning ps-sm-2"></i>
                                            {{ number_format($rev->rating, 1) }}
                                        </p>
                                        <p class="fs-14 mb-2">{{ $rev->review }}</p>

                                        @if($rev->reply && $rev->reply != '')
                                            <div class="d-flex mt-3 ms-4">
                                                <img class="rounded-circle border border-secondary"
                                                    src="{{ $service->user->image ? Storage::url($service->user->image) : 'https://ionicframework.com/docs/demos/api/avatar/avatar.svg' }}"
                                                    width="40" height="40"
                                                    alt="{{ $service->user->username ?? 'Seller' }}"
                                                    style="object-fit: cover;">
                                                <div style="margin-left:10px;">
                                                    <h6 class="mb-0">{{ $service->user->firstname ?? '' }} {{ $service->user->lastname ?? '' }}</h6>
                                                    <p class="fs-14 mt-1 mb-0">{{ $rev->reply }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted">No reviews found for other services.</p>
                            </div>
                        @endforelse

                        @if($otherall->count() > 2)
                            <button class="toggle-review-btn w-100 fs-6 bg-lgreen3 btn btn-light rounded text-dgreen2 mt-3" data-target="other">
                                Show More ▼
                            </button>
                        @endif
                    </div>

                    <!-- All Jobs Reviews -->
                    <div class="tab-pane fade" id="tabs-jobs" role="tabpanel">
                        @forelse($joball as $index => $rev)
                            @php
                                $reviewUser = $rev->user;
                                $reviewUserImage = $reviewUser && $reviewUser->image ? Storage::url($reviewUser->image) : 'https://ionicframework.com/docs/demos/api/avatar/avatar.svg';
                            @endphp
                            <div class="review-item-wrapper {{ $index >= 2 ? 'd-none more-review-job' : '' }}">
                                <div class="d-flex mt-4">
                                    <img class="rounded-circle border border-secondary"
                                        src="{{ $reviewUserImage }}"
                                        width="55" height="55"
                                        alt="{{ $reviewUser->username ?? 'User' }}"
                                        style="object-fit: cover;">
                                    <div style="margin-left:10px;" class="w-100">
                                        <p class="float-end text-c8 fs-12 mb-1">{{ diffforhumans($rev->created_at) }}</p>
                                        <h5 class="mb-0 pt-1">{{ $reviewUser->firstname ?? '' }} {{ $reviewUser->lastname ?? '' }}
                                            @if($reviewUser && $reviewUser->status == 1)
                                                <i class="fa-solid fa-circle-check fs-12 text-green"></i>
                                            @endif
                                        </h5>
                                        <p class="fs-12 text-secondary mb-2">
                                            {{ $reviewUser->country ?? 'Location not specified' }}
                                            <i class="fa-solid fa-star text-warning ps-sm-2"></i>
                                            {{ number_format($rev->rating, 1) }}
                                        </p>
                                        <p class="fs-14 mb-2">{{ $rev->review }}</p>
                                        @if($rev->reply && $rev->reply != '')
                                            <div class="d-flex mt-3 ms-4">
                                                <img class="rounded-circle border border-secondary"
                                                    src="{{ $service->user->image ? Storage::url($service->user->image) : 'https://ionicframework.com/docs/demos/api/avatar/avatar.svg' }}"
                                                    width="40" height="40"
                                                    alt="{{ $service->user->username ?? 'Seller' }}"
                                                    style="object-fit: cover;">
                                                <div style="margin-left:10px;">
                                                    <h6 class="mb-0">{{ $service->user->firstname ?? '' }} {{ $service->user->lastname ?? '' }}</h6>
                                                    <p class="fs-14 mt-1 mb-0">{{ $rev->reply }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted">No reviews found for jobs.</p>
                            </div>
                        @endforelse

                        @if($joball->count() > 2)
                            <button class="toggle-review-btn w-100 fs-6 bg-lgreen3 btn btn-light rounded text-dgreen2 mt-3" data-target="job">
                                Show More ▼
                            </button>
                        @endif
                    </div>
                </div>
            </div>

@push('script')
<script>
$(document).ready(function() {
    $('.toggle-review-btn').click(function() {
        var target = $(this).data('target');
        var hiddenReviews = $('.more-review-' + target);

        if (hiddenReviews.hasClass('d-none')) {
            hiddenReviews.removeClass('d-none');
            $(this).html('Show Less ▲');
        } else {
            hiddenReviews.addClass('d-none');
            $(this).html('Show More ▼');
        }
    });
});
</script>
@endpush
        </div>
    </div>

    <!-- Related Services -->
    @if (count($releatedServices) > 0)
        <div class="mb-5">
            <div class="px-3 px-md-0">
                <h4 class="mb-0">Related Services</h4>
                <div class="row">
                    @foreach ($releatedServices as $index => $service)
                        @php
                            $progress = ($service->rating / 5) * 100;
                        @endphp

                        <div class="col-md-3 py-2">
                            <div class="card shadow-sm border h-100">
                                <img src="{{ getImage('assets/images/service/' . $service->image, imagePath()['service']['size']) }}"
                                    class="card-img-top p-2 pb-0" alt="{{ $service->title }}">

                                <div class="card-body">
                                    <div class="row align-items-center">
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

                                        <div class="col-6 col-lg-4 text-end">
                                            <div class="d-flex flex-column align-items-end justify-content-center">
                                                <p class="mb-0 fs-10 fw-semibold">
                                                    Rank {{ $index + 1 }}
                                                </p>
                                                <div class="progress" style="width: 80%; height: 3px;">
                                                    <div class="progress-bar bg-green" role="progressbar"
                                                        style="width: {{ $progress }}%;"></div>
                                                </div>
                                                <p class="mb-0 fs-10 fw-semibold">
                                                    <i class="fa fa-star star"></i>
                                                    {{ number_format($service->rating, 1) }}
                                                    ({{ $service->reviews_count ?? 0 }})
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <h6 class="fs-12 fw-normal mt-2">
                                        {{ Str::limit($service->title, 80) }}
                                    </h6>

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

                                    <p class="mt-3 mb-0">
                                        <a href="{{ route('service.details', encrypt($service->id)) }}" class="btn btn-sm btn-success bg-dgreen border-0 float-end">
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

            // Initialize Bootstrap tabs
            $('.nav-tabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });

        document.addEventListener('shown.bs.tab', function (event) {
            let targetPaneId = event.target.getAttribute('data-bs-target');
            let activePane = document.querySelector(targetPaneId);
            if (activePane) {
                let detailId = activePane.getAttribute('data-detail_id');
                console.log('Selected detail ID:', detailId);
            }
        });

        // Toggle functionality for review sections
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-review-btn');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    let hiddenItems;
                    let isExpanded = this.getAttribute('data-expanded') === 'true';

                    switch(target) {
                        case 'this':
                            hiddenItems = document.querySelectorAll('#tabs-this .more-review-this');
                            break;
                        case 'other':
                            hiddenItems = document.querySelectorAll('#tabs-other .more-review-other');
                            break;
                        case 'job':
                            hiddenItems = document.querySelectorAll('#tabs-jobs .more-review-job');
                            break;
                        default:
                            return;
                    }

                    hiddenItems.forEach(item => {
                        if (isExpanded) {
                            item.classList.add('d-none');
                        } else {
                            item.classList.remove('d-none');
                        }
                    });

                    this.textContent = isExpanded ? 'Show More ▼' : 'Show Less ▲';
                    this.setAttribute('data-expanded', !isExpanded);
                });
                button.setAttribute('data-expanded', 'false');
            });
        });

        // Skills & Expertise carousels — same scroll behaviour as the job
        // listing cards (see resources/views/templates/basic/job.blade.php).
        document.addEventListener("DOMContentLoaded", function() {
            const carousels = document.querySelectorAll(".carousel-container");

            carousels.forEach((carousel) => {
                const prev = carousel.querySelector(".prev");
                const next = carousel.querySelector(".next");
                const track = carousel.querySelector(".track");
                const nav = carousel.querySelector(".navc");
                // The arrows sit BESIDE the track (not on top of it), so the
                // actual visible/clipped viewport is .inner-carousel, which
                // is narrower than the whole .carousel-container once the
                // arrow column's width is subtracted. Using the wider
                // carousel.clientWidth here under-scrolled — the last card(s)
                // stayed cut off on the right, and "next" hid itself before
                // everything had actually scrolled into view.
                const viewport = carousel.querySelector(".inner-carousel") || carousel;
                // .inner-carousel has left/right padding (breathing room
                // between the scrolling cards and the flush-edge arrows).
                // clientWidth includes that padding, but overflow:hidden
                // clips at the padding's OUTER edge, not the content edge —
                // so scrollWidth - clientWidth alone scrolls exactly
                // (paddingLeft + paddingRight) too far, pushing the last
                // card's edge out. Subtract the padding back out.
                const viewportStyle = getComputedStyle(viewport);
                const viewportPaddingX = parseFloat(viewportStyle.paddingLeft || 0) + parseFloat(viewportStyle.paddingRight || 0);

                function visibleWidth() {
                    return viewport.clientWidth - viewportPaddingX;
                }

                if (!prev || !next || !track) return;

                let index = 0;
                let width = visibleWidth();

                function currentMaxScroll() {
                    return track.scrollWidth - visibleWidth();
                }

                // The arrow is always shown now, even with only 1-2 skills
                // that don't overflow — clicking it then is just a no-op
                // (see the maxScroll <= 0 guards below), never broken.
                if (nav) nav.style.display = "";

                window.addEventListener("resize", function() {
                    width = visibleWidth();
                    index = 0;
                    track.style.transform = "translateX(0px)";
                    prev.classList.remove("show");
                    next.classList.remove("hide");
                });

                // "next" (right) hides once you've reached the true end;
                // "prev" (left) shows as soon as you've scrolled off the
                // start. Both can be visible together in the middle.
                next.addEventListener("click", function(e) {
                    e.preventDefault();

                    const maxScroll = currentMaxScroll();
                    if (maxScroll <= 0) return;

                    const nextPosition = Math.min((index + 1) * width, maxScroll);

                    if (nextPosition === index * width) {
                        return;
                    }

                    index++;
                    track.style.transform = `translateX(-${nextPosition}px)`;
                    prev.classList.add("show");

                    if (nextPosition >= maxScroll) {
                        next.classList.add("hide");
                    }
                });

                prev.addEventListener("click", function(e) {
                    e.preventDefault();

                    index--;
                    if (index < 0) {
                        index = 0;
                    }

                    const maxScroll = currentMaxScroll();
                    const position = Math.min(index * width, Math.max(maxScroll, 0));
                    track.style.transform = `translateX(-${position}px)`;

                    if (position < maxScroll) {
                        next.classList.remove("hide");
                    }

                    if (index === 0) {
                        prev.classList.remove("show");
                    }
                });
            });
        });
    </script>
@endsection
