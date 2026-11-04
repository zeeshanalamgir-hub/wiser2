<div class="container-fluid bg-dgreen d-none d-lg-block">

    <div class="container pb-1">
        <div class="row pt-3 pb-md-2">

            <div class="col-md-4 col-lg-3 col-xl-2 ">
                <a href="{{ route('home') }}"><img
                        src="{{ asset('assets/wiserlance/assets/img/home/logo-wiserlance.svg') }}"
                        style="width: 200px;"></a>
            </div>

            <div class="col-md-3 ps-xl-5">
                <nav class="navbar navbar-expand-lg navbar-dark d-none pt-1 d-lg-block fs-16 ps-0">
                    <ul class="navbar-nav me-auto my-lg-0">
                        <li class="nav-item pt-2">
                            <a class="links-c3 px-1 px-xl-2 px-xxl-3"
                                href="{{ route('professionals') }}">Professionals</a>
                        </li>
                        <li class="nav-item pt-2 ">
                            <a class="links-c3 px-1 px-xl-2 px-xxl-3" href="{{ route('service') }}">Services</a>
                        </li>
                        <li class="nav-item pt-2">
                            <a class="links-c3 px-1 px-xl-2 px-xxl-3" href="{{ route('job') }}">Jobs</a>
                        </li>
                        <!---
                  <li class="nav-item pt-2">
                    <a class="links-c3 px-1 px-xl-2 px-xxl-3" href="whyChooseUs.php">WhyUs</a>
                  </li>

                   <li class="nav-item pt-2">
                    <a class="links-c3 px-1 px-xl-2 px-xxl-3" href="membershipPricing.php">Pricing</a>
                  </li>-->
                    </ul>
                </nav>
            </div>

            @if(Auth::check())
                <div class="col-md-4 col-xl-5 ps-4 pe-0 pt-2">
            @else
                <div class="col-md-4 col-xl-4 ps-4 pe-0 pt-2">
            @endif
                <form class="d-flex w-100 me-3 rounded-3 p-1 bg-dgreen2 text-dark" method="GET" role="search">
                    <input class="form-control bg-dgreen text-light border-0 fs-12" style="color:#495057" id="myInput"
                        style="height:25px; margin-top:2px;" type="search" value="{{ request()->input('search') }}" name="search" placeholder="Search..." aria-label="Search">

                    <select name="type" class="form-select border-0 bg-dgreen2 pe-0 text-light py-1 fs-12"
                        aria-label="Default select example" style="width:170px; height:25px; margin-top:2px">
                        <option value="jobs" {{ request()->type == 'jobs' ? 'selected' : '' }}>Jobs</option>
                        <option value="professionals" {{ request()->type == 'professionals' ? 'selected' : '' }}>Professionals</option>
                        <option value="services" {{ request()->type == 'services' ? 'selected' : '' }}>Services</option>
                    </select>
                </form>
            </div>
            @if(Auth::check())
                <div class="col-md-2 px-0 fs-12 mt-lg-2">
            @else
                <div class="col-md-3 px-0 fs-12 mt-lg-2">
            @endif
                @if(Auth::check())
                    <div class="float-end d-flex">
                @else
                    <div class="float-end">
                @endif
                    <img src= "{{ asset('assets/wiserlance/assets/img/home/lang.svg') }}" width="30" />
                    <select class="p-0 bg-dgreen border-0 text-white">
                        <option selected>English</option>
                        <option value="1">German</option>
                        <option value="2">Spanish</option>
                        <option value="3">Arabic</option>
                    </select>

                    {{--  <a href="{{ route('user.login') }}" class="btn btn-outline-light mx-0 mx-xl-2">Log
                        in</a>
                    <a href="{{ route('user.signupform') }}" class="btn btn-success bg-green px-lg-1 px-xl-2">Sign
                        up</a>  --}}
                        @auth
                            @php
                                $user = auth()->user();
                                $dashboardRoute = ($user->is_freelancer === '1' || $user->is_freelancer == 1)
                                    ? route('user.home') 
                                    : route('user.buyer.dashboard');
                            @endphp

                            <a href="{{ $dashboardRoute }}" class="d-flex align-items-center ms-2 text-decoration-none">
                               @if(!is_null($user?->image))
                                    <img src="{{ Storage::url($user->image) ??  asset('assets/images/default-profile.png')}}" alt="@lang('Profile Image')" class="rounded-circle me-2" width="35" height="35" style="object-fit:cover">
                                @else
                                    <div class="d-flex justify-content-center">
                                        <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                            style="height:45px;width:45px;font-size:20px">
                                            <span style="font-size: 18px">
                                                {{ strtoupper(substr($user?->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($user?->lastname ?? 'A', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('user.login') }}" class="btn btn-outline-light mx-0 mx-xl-2">
                                Log in
                            </a>
                            <a href="{{ route('user.signupform') }}" class="btn btn-success bg-green px-lg-1 px-xl-2">
                                Sign up
                            </a>
                        @endauth
                </div>
            </div>
        </div> <!--Row closed---->
    </div>
</div>

<div class="container-fluid bg-dgreen py-1 d-lg-none">
    <!--<nav class="navbar navbar-expand-lg navbar-dark navbar-nav">-->
    <div class="container">
        <div class="row">

            <div class="col-8 col-sm-5 col-md-4 ">
                <a class="navbar-brand" href="{{ route('home') }}"><img
                        src="{{ asset('assets/wiserlance/assets/img/home/logo-wiserlance.svg') }}"
                        style="width:200px"></a>
            </div>

            <!----This searchbar only visible on mobile screens--->
            <div class="col-2 pt-3 d-sm-none">
                <a type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop"
                    aria-controls="offcanvasTop"><i class="fa-solid fa-magnifying-glass text-white fs-5"></i></a>
                <div class="offcanvas offcanvas-top p-3" tabindex="-1" id="offcanvasTop"
                    aria-labelledby="offcanvasTopLabel" style="height:110px;">
                    <button type="button" class="btn-close py-2 mb-2 text-green" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                    <form class="d-flex w-100 me-3 rounded-3 p-1 bg-dgreen2 text-dark" role="search">
                        <input class="form-control text-light border-0 fs-12" id="myInput"
                            style="height:30px; margin-top:2px;" name="search" type="search" placeholder="Search..."
                            aria-label="Search">
                        <select name="type" class="form-select border-0 bg-dgreen2 pe-0 text-light py-1 fs-12"
                            aria-label="Default select example" style="width:170px; height:25px; margin-top:2px">
                            <option value="jobs" {{ request()->type == 'jobs' ? 'selected' : '' }}>Jobs</option>
                            <option value="professionals" {{ request()->type == 'professionals' ? 'selected' : '' }}>Professionals</option>
                            <option value="services" {{ request()->type == 'services' ? 'selected' : '' }}>Services</option>
                        </select>
                    </form>
                </div>
            </div>

            <!----This searchbar only visible on samll and medium screens--->
            <div class="col-6 col-md-7 mt-2 d-none d-sm-block">
                <form class="d-flex w-100 me-3 rounded-3 p-1 bg-dgreen2 text-dark" role="search">
                    <input class="form-control bg-dgreen text-light border-0 fs-14" id="myInput" name="search"
                        style="height:25px; margin-top:2px;" type="search" placeholder="Search..." aria-label="Search">
                    <select name="type" class="form-select border-0 bg-dgreen2 pe-0 text-light py-1 fs-12"
                        aria-label="Default select example" style="width:170px; height:25px; margin-top:2px">
                        <option value="jobs" {{ request()->type == 'jobs' ? 'selected' : '' }}>Jobs</option>
                        <option value="professionals" {{ request()->type == 'professionals' ? 'selected' : '' }}>Professionals</option>
                        <option value="services" {{ request()->type == 'services' ? 'selected' : '' }}>Services</option>
                    </select>
                </form>
            </div>

            <div class="col-1 p-2">
                <div class="dropdown float-end">
                    <a class="" class="btn btn-secondary dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class=" text-green fs-3"> <i class="fa-solid fa-bars"></i></span>
                    </a>
                    <ul class="dropdown-menu fs-14">
                        <li><a class="dropdown-item" href="logIn.php"><i
                                    class="fa-solid text-dgreen2 fa-arrow-right-to-bracket text-3gray"></i> Log
                                in</a></li>
                        <li><a class="dropdown-item" href="signUp.php"><i
                                    class="fa-solid text-dgreen2 fa-user-plus text-3gray"></i> Sign up</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('professionals') }}"><i
                                    class="fa-solid fa-caret-right text-dgreen2"></i> Professionals</a></li>
                        <li><a class="dropdown-item" href="{{ route('service') }}"><i
                                    class="fa-solid fa-caret-right text-dgreen2"></i> Services</a></li>
                        <li><a class="dropdown-item" href="{{ route('job') }}"><i
                                    class="fa-solid fa-caret-right text-dgreen2"></i> Jobs</a></li>
                        {{--  <li><a class="dropdown-item" href="#"><i
                                    class="fa-solid fa-caret-right text-dgreen2"></i> Why us</a></li>
                        <li><a class="dropdown-item" href="#"><i
                                    class="fa-solid fa-caret-right text-dgreen2"></i> Pricing</a></li>  --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
