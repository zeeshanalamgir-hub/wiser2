@extends('homepages.layout.app')

@section('title', $pageTitle)

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
    <style>
        .card-h { min-height:190px; }
        :root {
            --primary: #0f5256;
            --secondary: #69ba6b;
            --accent: #4a8b50;
            --dark: #1a2e33;
            --light: #f8f9fa;
            --white: #ffffff;
            --gray: #6c757d;
            --shadow: rgba(15, 82, 86, 0.1);
            --free: #6c757d;
            --basic: #17a2b8;
            --pro: #28a745;
            --business: #ffc107;
            --enterprise: #dc3545;
            --employer-standard: #6f42c1;
            --employer-premium: #e83e8c;
        }

        /* Role Toggle */
        .role-toggle-container {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            margin-top: 1rem;
            width: 100%;
        }
        .role-toggle {
            display: flex;
            background: var(--dark);
            border: 2px solid #000;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            width: 100%;
        }
        .role-option {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--white);
            text-align: center;
            flex: 1;
        }
        .role-option.active {
            background: #058f8cff;
            box-shadow: 0 2px 10px rgba(15, 82, 86, 0.3);
        }

        /* Plan Section */
        .plan-section {
            display: none;
            animation: fadeIn 0.5s ease;
            width: 100%;
        }
        .plan-section.active {
            display: block;
        }

        /* Billing Toggle Buttons */
        .billing-toggle-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .billing-btn {
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .billing-btn.active {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }
        .billing-btn:not(.active) {
            background: transparent;
            color: var(--dark);
            border-color: #dee2e6;
        }
        .billing-btn:not(.active):hover {
            background: #f8f9fa;
        }

        .plan-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        }


        .bg-free { background: linear-gradient(135deg, #6c757d, #8fa1a7); }
        .bg-basic { background: linear-gradient(135deg, #17a2b8, #5bc0de); }
        .bg-pro { background: linear-gradient(135deg, #28a745, #5cb85c); }
        .bg-advance { background: linear-gradient(135deg, #0f5256, #1a6b6f); }
        .bg-employer-standard { background: #fbc106; }
        .bg-employer-premium { background: #368754; }

        .btn-free { background: #6c757d !important; border-color: #6c757d; }
        .btn-basic { background: #17a2b8 !important; border-color: #17a2b8; }
        .btn-pro { background: #28a745 !important; border-color: #28a745; }
        .btn-advance { background: #0f5256 !important; border-color: #0f5256; }

        .popular-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ffc107;
            color: #000;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            z-index: 10;
        }

        .best-value-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #28a745;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            z-index: 10;
        }

        .feature-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #dee2e6;
        }
        .feature-item:last-child {
            border-bottom: none;
        }
        .feature-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .feature-value {
            font-weight: 600;
            color: var(--dark);
        }

        .membership-container {
            display: block;
        }
        .monthly-plans, .yearly-plans {
            display: none;
        }
        .monthly-plans.active, .yearly-plans.active {
            display: block;
        }

        .btn:hover {
            background-color: inherit;
            border-color: inherit;
            color: inherit;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .role-option {
                padding: 0.5rem 1rem;
                font-size: 14px;
            }
            .billing-toggle-buttons {
                flex-direction: column;
                align-items: center;
            }
            .billing-btn {
                width: 200px;
            }
        }
    </style>
@endsection

@php
    $user = Auth::user();
@endphp

@section('content')
<div class="container-fluid px-md-5 bg-lgreen3">
    <div class="container">
        <div class="row pt-5">
            <div class="col-12">
                <center>
                    <h1 class="text-center fw-bold mb-2 text-dgreen">{{ $pageTitle }}</h1>
                    <p class="text-center mb-3 fs-5 text-secondary w-md-75">Find the option that works best for you</p>

                    <div class="role-toggle-container">
                        <div class="role-toggle">
                            <div class="role-option active" data-role="freelancer">Freelancer</div>
                            <div class="role-option" data-role="employer">Employer</div>
                        </div>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>

<!-- Freelancer Section -->
<section id="freelancerSection" class="plan-section active">
    <div class="container-fluid bg-dgreen2">
        <div class="container">
            <div class="row py-4">
                <div class="col-12">
                    <h2 class="text-white fw-bold text-center mb-3">Freelancer Plans</h2>

                    <div class="billing-toggle-buttons">
                        <button id="monthlyBtn" class="billing-btn active">Monthly</button>
                        <button id="yearlyBtn" class="billing-btn">Yearly</button>
                    </div>
                </div>
            </div>

            <div id="monthlyPlans" class="monthly-plans active">
                @if($freelancerMonthly->count() > 0)
                <div class="row pb-5 g-4">
                    @foreach($freelancerMonthly as $index => $membership)
                        @php
                            if($membership->price == 0) {
                                $headerClass = 'bg-free';
                                $btnClass = 'btn-free';
                            } elseif(strpos(strtolower($membership->title), 'basic') !== false) {
                                $headerClass = 'bg-basic';
                                $btnClass = 'btn-basic';
                            } elseif(strpos(strtolower($membership->title), 'standard') !== false) {
                                $headerClass = 'bg-pro';
                                $btnClass = 'btn-pro';
                            } else {
                                $headerClass = 'bg-advance';
                                $btnClass = 'btn-advance';
                            }
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow rounded-4 plan-card position-relative">
                                <div class="card-header {{ $headerClass }} text-white text-center py-4 rounded-top-4 border-0">
                                    <h3 class="mb-1">{{ $membership->title }}</h3>
                                    @if($membership->short_description)
                                        <p class="mb-0 fs-14 opacity-75">{{ $membership->short_description }}</p>
                                    @else
                                        <p class="mb-0 fs-14 opacity-75">
                                            @if($membership->price == 0)
                                                Perfect for getting started
                                            @elseif($index == 1)
                                                For growing your freelance business
                                            @else
                                                For serious freelancers
                                            @endif
                                        </p>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <!-- Price Section -->
                                    <div class="text-center mb-4">
                                        <div class="d-flex justify-content-center align-items-baseline">
                                            <span class="fs-1 fw-bold text-dgreen">
                                                ${{ number_format($membership->price, 0) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="features-list">
                                        <div class="feature-item">
                                            <span class="feature-label">Service Cost</span>
                                            <span class="feature-value">{{ $membership->features['service_fee'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Job Cost</span>
                                            <span class="feature-value">{{ $membership->features['job_cost'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Monthly Bids</span>
                                            <span class="feature-value">{{ $membership->features['bids'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Featured Jobs Bids</span>
                                            <span class="feature-value">{{ $membership->features['featured_bids'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Annual Bids Rollover</span>
                                            <span class="feature-value">{{ $membership->features['annual_bids_rollover'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Purchase Additional Bids</span>
                                            <span class="feature-value">{{ $membership->features['purchase_additional_bids'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Regular Services</span>
                                            <span class="feature-value">{{ $membership->features['normal_services'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Featured Services</span>
                                            <span class="feature-value">{{ $membership->features['featured_services'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Annual Services Rollover</span>
                                            <span class="feature-value">{{ $membership->features['annual_services_rollover'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Portfolio Slots</span>
                                            <span class="feature-value">{{ $membership->features['portfolio'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Verified Account</span>
                                            <span class="feature-value">{{ $membership->features['verified'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Priority Support</span>
                                            <span class="feature-value">{{ $membership->features['support'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Quick Withdrawals</span>
                                            <span class="feature-value">{{ $membership->features['safe_payment'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                                    @if($membership->price == 0)
                                        @auth
                                            @if($user?->is_freelancer == 1)
                                                <a href="{{url('/user/seller/freelancermembership')}}" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                    Try It Now
                                                </a>
                                            @else
                                                <a href="#" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                    Try It Now
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{url('/login')}}" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                Try It Now
                                            </a>
                                        @endif
                                    @else
                                        @auth
                                            @if($user?->is_freelancer == 1)
                                                <a href="{{url('/user/seller/freelancermembership')}}" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                    Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                                </a>
                                            @else
                                                <a href="#" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                    Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{url('/login')}}" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info text-center my-5">
                    No monthly freelancer membership plans available
                </div>
                @endif
            </div>

            <div id="yearlyPlans" class="yearly-plans">
                @if($freelancerYearly->count() > 0)
                <div class="row pb-5 g-4">
                    @foreach($freelancerYearly as $index => $membership)
                        @php
                            if($membership->price == 0) {
                                $headerClass = 'bg-free';
                                $btnClass = 'btn-free';
                            } elseif(strpos(strtolower($membership->title), 'basic') !== false) {
                                $headerClass = 'bg-basic';
                                $btnClass = 'btn-basic';
                            } elseif(strpos(strtolower($membership->title), 'standard') !== false) {
                                $headerClass = 'bg-pro';
                                $btnClass = 'btn-pro';
                            } else {
                                $headerClass = 'bg-advance';
                                $btnClass = 'btn-advance';
                            }
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow rounded-4 plan-card position-relative">
                                {{-- <span class="best-value-badge">SAVE 20%</span> --}}

                                <div class="card-header {{ $headerClass }} text-white text-center py-4 rounded-top-4 border-0">
                                    <h3 class="mb-1">{{ $membership->title }}</h3>
                                    @if($membership->short_description)
                                        <p class="mb-0 fs-14 opacity-75">{{ $membership->short_description }}</p>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="d-flex justify-content-center align-items-baseline">
                                            <span class="fs-1 fw-bold text-dgreen">
                                                ${{ number_format($membership->price, 0) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="features-list">
                                        <div class="feature-item">
                                            <span class="feature-label">Service Cost</span>
                                            <span class="feature-value">{{ $membership->features['service_fee'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Job Cost</span>
                                            <span class="feature-value">{{ $membership->features['job_cost'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Monthly Bids</span>
                                            <span class="feature-value">{{ $membership->features['bids'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Featured Jobs Bids</span>
                                            <span class="feature-value">{{ $membership->features['featured_bids'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Annual Bids Rollover</span>
                                            <span class="feature-value">{{ $membership->features['annual_bids_rollover'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Purchase Additional Bids</span>
                                            <span class="feature-value">{{ $membership->features['purchase_additional_bids'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Regular Services</span>
                                            <span class="feature-value">{{ $membership->features['normal_services'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Featured Services</span>
                                            <span class="feature-value">{{ $membership->features['featured_services'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Annual Services Rollover</span>
                                            <span class="feature-value">{{ $membership->features['annual_services_rollover'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Portfolio Slots</span>
                                            <span class="feature-value">{{ $membership->features['portfolio'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Verified Account</span>
                                            <span class="feature-value">{{ $membership->features['verified'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Priority Support</span>
                                            <span class="feature-value">{{ $membership->features['support'] }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <span class="feature-label">Quick Withdrawals</span>
                                            <span class="feature-value">{{ $membership->features['safe_payment'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                                    @auth
                                        @if($user?->is_freelancer == 1)
                                            <a href="{{url('/user/seller/freelancermembership')}}" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                            </a>
                                        @else
                                            <a href="#" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                                Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{url('/login')}}" class="btn w-100 {{ $btnClass }} text-white py-2 rounded-pill">
                                            Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info text-center my-5">
                    No yearly freelancer membership plans available
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="container-fluid bg-lgreen3">
        <div class="container">
            <div class="row p-5 px-2 px-sm-5">
                <h2 class="text-dgreen text-center pb-2 fs-1">Benefits of Paid Membership</h2>
                <p class="text-secondary text-center">Supercharge your freelancing journey with exclusive perks that help you win more projects and boost your income.</p>

                <div class="col-lg-6 col-xxl-4 mt-4">
                    <div class="card card-h rounded-4 p-3 ps-4 shadow">
                        <div class="d-flex">
                            <i class="fas fa-money-bill-wave rounded-5 fs-5 bg-green text-white text-center shadow pt-2 mt-3" style="width:40px; height:40px"></i>
                            <div class="w-75 ms-3">
                                <h6 class="text-dgreen2 fs-20 fw-bold my-3">Keep More of What You Earn</h6>
                                <p class="fs-16">Enjoy lower platform fees and boost your take-home income.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xxl-4 mt-4">
                    <div class="card card-h rounded-4 p-3 ps-4 shadow">
                        <div class="d-flex">
                            <i class="fas fa-star rounded-5 fs-5 bg-green text-white text-center shadow pt-2 mt-3" style="width:40px; height:40px"></i>
                            <div class="w-75 ms-3">
                                <h6 class="text-dgreen2 fs-20 fw-bold my-3">Access Exclusive Opportunities</h6>
                                <p class="fs-16">Featured jobs/projects available only to members.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xxl-4 mt-4">
                    <div class="card card-h rounded-4 p-3 ps-4 shadow">
                        <div class="d-flex">
                            <i class="fas fa-business-time rounded-5 fs-5 bg-green text-white text-center shadow pt-2 mt-3" style="width:40px; height:40px"></i>
                            <div class="w-75 ms-3">
                                <h6 class="text-dgreen2 fs-20 fw-bold my-3">Bid on More Projects</h6>
                                <p class="fs-16">Receive extra bids every month, with unused bids rolling over annually.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="employerSection" class="plan-section">
    <div class="container-fluid bg-dgreen2">
        <div class="container">
            <div class="row py-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h2 class="text-white fw-bold">Employer Plans</h2>
                        <span class="badge bg-warning text-dark p-2 rounded-5 px-3">Monthly Billing Only</span>
                    </div>
                </div>
            </div>

            @if($employerMemberships->count() > 0)
            <div class="row pb-5 g-4">
                @foreach($employerMemberships as $index => $membership)
                    @php
                        $headerClass = $index == 0 ? 'bg-employer-standard' : 'bg-employer-premium';
                        $btnClass = $index == 0 ? 'btn-warning' : 'btn-success';
                    @endphp

                    <div class="col-md-4">
                        <div class="card border-0 shadow rounded-4 plan-card">
                            <div class="card-header {{ $headerClass }} text-white text-center py-4 rounded-top-4 border-0">
                                <h3 class="mb-1">{{ $membership->title }}</h3>
                                @if($membership->short_description)
                                    <p class="mb-0 fs-14 opacity-75">{{ $membership->short_description }}</p>
                                @else
                                    <p class="mb-0 fs-14 opacity-75">
                                        {{ $index == 0 ? 'For occasional hiring needs' : 'For regular hiring needs' }}
                                    </p>
                                @endif
                            </div>

                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="d-flex justify-content-center align-items-baseline">
                                        <span class="fs-1 fw-bold text-dgreen">
                                            ${{ number_format($membership->price, 0) }}
                                        </span>
                                        <span class="text-muted ms-2">per month</span>
                                    </div>
                                </div>

                                <div class="features-list">
                                    <div class="feature-item">
                                        <span class="feature-label">Service Fee</span>
                                        <span class="feature-value">{{ $membership->features['service_fee'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Regular Job Posting</span>
                                        <span class="feature-value">{{ $membership->features['regular_jobs'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Featured Job Posting</span>
                                        <span class="feature-value">{{ $membership->features['featured_jobs'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Job Invites/Month</span>
                                        <span class="feature-value">{{ $membership->features['job_invites'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Featured Job Tags</span>
                                        <span class="feature-value">{{ $membership->features['featured_tags'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Assistance</span>
                                        <span class="feature-value">{{ $membership->features['assistance'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Safe Payment</span>
                                        <span class="feature-value">{{ $membership->features['safe_payment'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Customer Support</span>
                                        <span class="feature-value">{{ $membership->features['support'] }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-label">Verification</span>
                                        <span class="feature-value">{{ $membership->features['verification'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                                @auth
                                    @if($user->is_employer == 1)
                                        <a href="{{url('/user/buyer/employermembership')}}" class="btn w-100 {{ $btnClass }} fw-bold py-2 rounded-pill">
                                            Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    @else
                                        <a href="#" class="btn w-100 {{ $btnClass }} fw-bold py-2 rounded-pill">
                                            Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{url('/login')}}" class="btn w-100 {{ $btnClass }} fw-bold py-2 rounded-pill">
                                        Buy Now <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-info text-center my-5">
                No employer membership plans available
            </div>
            @endif
        </div>
    </div>

    <div class="container-fluid bg-lgreen3">
        <div class="container">
            <div class="row p-5 px-2 px-sm-5">
                <h2 class="text-dgreen text-center pb-2 fs-1">Smart Hiring, No Cost</h2>
                <p class="text-secondary text-center">Discover and hire the right freelancers while keeping your budget intact.</p>

                <div class="col-lg-6 col-xxl-4 mt-4">
                    <div class="card card-h rounded-4 p-3 ps-4 shadow">
                        <div class="d-flex">
                            <i class="fas fa-user-check rounded-5 fs-5 bg-green text-white text-center shadow pt-2 mt-3" style="width:40px; height:40px"></i>
                            <div class="w-75 ms-3">
                                <h6 class="text-dgreen2 fs-20 fw-bold my-3">Access to Top Freelancers</h6>
                                <p class="fs-16">Connect with highly skilled, vetted professionals quickly.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xxl-4 mt-4">
                    <div class="card card-h rounded-4 p-3 ps-4 shadow">
                        <div class="d-flex">
                            <i class="fas fa-bolt rounded-5 fs-5 bg-green text-white text-center shadow pt-2 mt-3" style="width:40px; height:40px"></i>
                            <div class="w-75 ms-3">
                                <h6 class="text-dgreen2 fs-20 fw-bold my-3">Faster Hiring</h6>
                                <p class="fs-16">Priority job listings and invitations speed up finding the right talent.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xxl-4 mt-4">
                    <div class="card card-h rounded-4 p-3 ps-4 shadow">
                        <div class="d-flex">
                            <i class="fas fa-tags rounded-5 fs-5 bg-green text-white text-center shadow pt-2 mt-3" style="width:40px; height:40px"></i>
                            <div class="w-75 ms-3">
                                <h6 class="text-dgreen2 fs-20 fw-bold my-3">Cost Savings</h6>
                                <p class="fs-16">Discounts on fees and bundled services reduce hiring expenses.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="mt-5 text-center fs-14">We charge a nominal 2.9% processing fee per invoice to provide seamless payments, reliable support, and a smooth hiring experience for everyone.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
         showLoader();

        setTimeout(hideLoader, 5000);
        const roleOptions = document.querySelectorAll('.role-option');
        const freelancerSection = document.getElementById('freelancerSection');
        const employerSection = document.getElementById('employerSection');

        const monthlyBtn = document.getElementById('monthlyBtn');
        const yearlyBtn = document.getElementById('yearlyBtn');
        const monthlyPlans = document.getElementById('monthlyPlans');
        const yearlyPlans = document.getElementById('yearlyPlans');

        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                roleOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');

                const role = this.getAttribute('data-role');
                if (role === 'freelancer') {
                    freelancerSection.classList.add('active');
                    employerSection.classList.remove('active');
                } else {
                    freelancerSection.classList.remove('active');
                    employerSection.classList.add('active');
                }
            });
        });

        if (monthlyBtn && yearlyBtn) {
            monthlyBtn.addEventListener('click', function() {
                monthlyBtn.classList.add('active');
                yearlyBtn.classList.remove('active');

                monthlyPlans.classList.add('active');
                yearlyPlans.classList.remove('active');
            });

            yearlyBtn.addEventListener('click', function() {
                yearlyBtn.classList.add('active');
                monthlyBtn.classList.remove('active');

                yearlyPlans.classList.add('active');
                monthlyPlans.classList.remove('active');
            });
        }
    });

</script>
@endsection
