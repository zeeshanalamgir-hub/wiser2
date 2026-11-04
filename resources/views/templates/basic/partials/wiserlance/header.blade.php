@php
    $helpers = new \App\Classes\Helpers();
    $currency_rates = $helpers->get_currency_rates();
    $currencies = $helpers->get_all_currencies();
@endphp

<style>
    .btn-outline-light:hover {
        color: #000 !important;
        transition: 0.5s;
    }
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        width: 100%;
        transition: all 0.3s ease;
    }

    /* Optional: Add a shadow when sticky */
    .sticky-header.sticky {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Optional: Add a background color when sticky (if needed) */
    .sticky-header.sticky .container-fluid {
        background-color: #1e4a3b; /* Slightly darker shade when sticky */
    }

    /* Ensure the header content remains visible */
    .sticky-header .container-fluid {
        transition: background-color 0.3s ease;
    }
</style>

<div class="container-fluid bg-dgreen sticky-header" id="mainHeader">
    <div class="container">
        <div class="row pt-3 pb-md-2">
            <div class="col-md-4 col-lg-2 ">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.svg') }}"
                        style="width: 200px;">
                </a>
            </div>
            <div class="col-lg-6 col-md-4">
                <nav class="header navbar navbar-expand-lg navbar-dark d-none d-lg-block">
                    <ul class="navbar-nav me-auto ms-5 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        @if (Auth::check())
                            @if (Auth::user()->is_employer == '1')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('professionals') }}">Find Professionals</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('service') }}">Find Services</a>
                                </li>
                            @elseif (Auth::user()->is_freelancer == '1')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('job') }}">Find Jobs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Corporate</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('professionals') }}">Professionals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('service') }}">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('job') }}">Jobs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Corporate</a>
                            </li>
                        @endif
                        <!-- Hidden input mein route URL store karein -->
                        <input type="hidden" id="currency-change-url"
                            value="{{ route('currency.change', ['currency' => ':currency']) }}">
                        <!-- Currency Switcher -->
                        <li class="nav-item dropdown ms-2 fs-14" id="currency-dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('assets/wiserlance/assets/img/icons/dollar.svg') }}"
                                    width="25" />
                                {{ Session::get('currency', 'PKR') }}
                            </a>
                            <ul class="dropdown-menu fs-14">
                                @foreach ($currencies as $key => $item)
                                    <li>
                                        <a class="dropdown-item currency-option {{ Session::get('currency') == $item->code ? 'active' : '' }}"
                                            href="#" data-currency="{{ $item->code }}">
                                            {{ $item->code }} - {{ $item->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <!-- Language Switcher -->
                        <li class="nav-item dropdown fs-14">
                            <a class="nav-link dropdown-toggle pt-1" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('assets/wiserlance/assets/img/icons/lang.svg') }}" width="30" />
                                <span id="anu">ENG</span>
                            </a>
                            <ul class="dropdown-menu fs-14">
                                <li><a class="dropdown-item lang-select" href="#" data-lang="english"
                                        data-res="ENG"><span class="flag-icon flag-icon-us"></span> ENG</a></li>
                                <li><a class="dropdown-item lang-select" href="#" data-lang="russian"
                                        data-res="RUS"><span class="flag-icon flag-icon-ru"></span> RUS</a></li>
                                <li><a class="dropdown-item lang-select" href="#" data-lang="arabic"
                                        data-res="ARAB"><span class="flag-icon flag-icon-sa"></span> ARAB</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-4 mt-lg-2 flex-column">
                <div class="float-end d-flex w-100">
                    @guest
                        <button class="btn btn-outline-light me-3" onclick="location.href='{{ url('/login') }}';">Log
                            in</button>
                        <button class="btn btn-success" onclick="location.href='{{ url('/signup') }}';">Sign up</button>
                    @endguest
                    @if (Auth::check())
                        <form class="d-flex w-100" role="search">
                            <input class="form-control bg-dsgreen border-0 fs-14" id="myInput"
                                style="height:30px; margin-top:7px;" type="search" placeholder="Search..."
                                aria-label="Search">
                            <button class="btn btn-sm bg-dgreen pt-2" type="submit"><img
                                    src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}"
                                    width="28"></button>
                        </form>
                        <a class="px-1" style="padding-top:10px" href="/chatify"><img
                                src="{{ asset('assets/wiserlance/assets/img/icons/message-green-circle-white.svg') }}"
                                width="25">
                        </a>
                        @php
                            $notifications = DB::table('notifications')
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
                        <span class="dropdown">
                            <button class="px-1 mt-1 mx-2 btn btn-sm position-relative " data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('assets/wiserlance/assets/img/icons/notification-green-circle-white.svg') }}"
                                    width="23">
                                @if ($unreadCount > 0)
                                    <span
                                        class="notification-unread-count position-absolute start-100 translate-middle fs-10 rounded-circle vw-25 bg-danger text-white"
                                        style="width:17px; height:17px; margin:5px 0 0 -5px">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu p-0 notification-list" style="max-width:350px">
                                @forelse ($notifications as $notification)
                                    <li class="border-bottom {{ $loop->first ? 'bg-lsgreen rounded-top' : '' }}">
                                        <a class="dropdown-item p-3 pb-1 notification-link"
                                            data-id="{{ $notification->id }}"
                                            data-click-url="{{ json_decode($notification->data, true)['click_url'] ?? '#' }}">
                                            <div class="d-flex py-1">
                                                <p class="circle text-green bg-light border me-2">
                                                    <i class="far fa-bell fs-5 mt-2"></i>
                                                </p>
                                                <div class="d-flex flex-column text-truncate" style="flex-grow: 1;">
                                                    <p class="mb-0 text-secondary fs-12 w-100">
                                                        {{ Str::limit(json_decode($notification->data, true)['message'] ?? '', 40) }}
                                                    </p>
                                                    <p class="mb-0 text-secondary fs-12 w-100">
                                                        {{ Str::limit(substr(json_decode($notification->data, true)['message'] ?? '', 40), 40) }}
                                                    </p>
                                                    <p class="mb-0 fs-12 text-secondary">
                                                        {{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->diffForHumans() : 'N/A' }}
                                                    </p>
                                                </div>
                                                <i
                                                    class="ms-2 align-self-center {{ $notification->read_at ? 'far fa-envelope-open' : 'far fa-envelope' }} text-secondary"></i>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="border-bottom text-muted text-center">
                                        <p class="mb-0 text-secondary fs-14">No new notifications</p>
                                    </li>
                                @endforelse
                                <li class="bg-light rounded">
                                    <a class="dropdown-item fs-14 text-green links-c"
                                        href="{{ url('/all-notifications') }}">
                                        <i class="fas fa-angle-double-right ms-3 me-2 py-2"></i> View All
                                        Notifications</a>
                                </li>
                            </ul>
                        </span>

                        @if (Auth::check())
                            <a class="img-fluid circle" href="#" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}"
                                    style="width: 35px; height: 35px;border-radius: 50%;">
                            </a>
                        @endif
                        <ul class="dropdown-menu fs-14 dropstart">
                            @if (Auth::check())
                                @if (Auth::user()->is_employer == '1')
                                    <li><a href="{{ route('user.buyer.dashboard') }}"
                                            class="dropdown-item">Dashboard</a></li>
                                    <li><a href="{{ route('user.buyer.profile') }}" class="dropdown-item">Profile
                                            Settings</a></li>
                                    @if (Auth::user()->status == 1)
                                        <li>
                                            <a href="{{ route('buyerprofile', ['username' => Auth::user()->username, 'id' => encrypt(Auth::user()->id)]) }}"
                                                class="dropdown-item">View Profile</a>
                                        </li>
                                    @endif
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.buyer.change.password') }}">Change
                                            Password</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a></li>
                                @else(Auth::user()->is_freelancer == '1')
                                    <li><a href="{{ route('user.home') }}" class="dropdown-item">Dashboard</a></li>
                                    <li><a href="{{ route('user.home1') }}" class="dropdown-item">Profile
                                            Settings</a>
                                    </li>
                                    @if (Auth::user()->status == 1)
                                        <li>
                                            <a href="{{ route('profile', ['username' => Auth::user()->username, 'id' => encrypt(Auth::user()->id)]) }}"
                                                class="dropdown-item">View Profile</a>
                                        </li>
                                    @endif
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.buyer.change.password') }}">Change
                                            Password</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a></li>
                                @endif
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div> <!--Row closed---->
    </div> <!--container closed-->
</div>

<style>
.sticky-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    width: 100%;
    transition: all 0.3s ease;
}

.sticky-header.sticky {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Optional: Add a smooth transition for the background */
.sticky-header .container-fluid {
    transition: background-color 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const header = document.getElementById('mainHeader');
    
    window.addEventListener('scroll', function() {
        // Add shadow when scrolled
        if (window.scrollY > 50) {
            header.classList.add('sticky');
        } else {
            header.classList.remove('sticky');
        }
    });
});
</script>

<div class="container-fluid bg-dgreen py-1 d-lg-none">
    <!--<nav class="navbar navbar-expand-lg navbar-dark navbar-nav">-->
    <div class="container">
        <div class="row">
            <div class="col-7">
                <a class="navbar-brand" href="index.php"> <img
                        src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.svg') }}"
                        style="width: 200px;"></a>
            </div>
            <!--offCanvas start-->

            <div class="col-3 col-md-4">
                <button class="btn float-end pt-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                    <span class=" ps-2 navbar-dark navbar-toggler-icon"></button>

                <div class="offcanvas offcanvas-start" data-bs-backdrop="static" style="width:300px;" tabindex="-1"
                    id="staticBackdrop" aria-labelledby="staticBackdropLabel">

                    <div class="offcanvas-header bg-dgreen text-white">
                        <h5 class="offcanvas-title " id="staticBackdropLabel">Dashboard</h5>
                        <button type="button" class="btn-close bg-green fs-10 me-2" data-bs-dismiss="offcanvas"
                            aria-label="Close"> </button>
                    </div>

                    <div class="offcanvas-body p-0 bg-light">

                        <ul class="nav bg-sgreen float-end p-2 flex-column">
                            <li class="nav-item"><a class="nav-link py-1 text-success" href="#"><i
                                        class="fa-solid fa-caret-right"></i> Jobs</a></li>
                            <li class="nav-item"><a class="nav-link py-1 text-success" href="#"> <i
                                        class="fa-solid fa-caret-right"></i> Corporate</a></li>
                        </ul>

                        <ul class="nav bg-sgreen flex-column p-2">
                            <li class="nav-item"><a class="nav-link py-1 text-success" href="#"> <i
                                        class="fa-solid fa-caret-right disabled"></i> Professionals</a></li>
                            <li class="nav-item float-right"><a class="nav-link py-1 text-success" href="#"><i
                                        class="fa-solid fa-caret-right disable"></i> Services</a></li>
                        </ul>

                        <div class="">
                            <h6 class="py-2 ps-3  mb-0 bg-dgreen text-green">Buyer Account<h6>
                                    <ul
                                        class="nav flex-column bg-light fs-14 fw-nornmal bg-lgrey p-2 mt-2 text-secondary">
                                        <li class="nav-item"><a class="nav-link py-1 text-secondary" href="#">
                                                <i class="fa-solid fa-table-list"></i> Manage Jobs</a></li>
                                        <li class="nav-item"><a class="nav-link py-1 text-secondary"
                                                href="#"><i class="fa-regular fa-square-plus"></i> Create
                                                Jobs</a></li>
                                        <li class="nav-item"><a class="nav-link py-1 text-secondary" href="#">
                                                <i class="fa-regular fa-comment-dots"></i> Dispute</a></li>
                                    </ul>

                                    <hr>

                                    <h6 class="mt-3 ps-3 bg-light">Purchase</h6>
                                    <ul class="nav flex-column ms-2 ps-3 " style="font-size:14px;">
                                        <li class="nav-item">
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fa-regular fa-hand-pointer"></i> Booked Services</a>
                                        </li>
                                        <li class="">
                                            <a class="nav-link p-0 py-1 text-success" href="../hiring/openJobs.php">
                                                <i class="fa-solid fa-list-ul"></i> Hiring List</a>
                                        </li>
                                    </ul>

                                    <hr>

                                    <h6 class="mt-3 ps-3">Basics</h6>
                                    <ul class="nav flex-column ms-2 ps-3" style="font-size:14px;">
                                        <li class="nav-item">
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-inbox"></i> Inbox</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="far fa-list-alt"></i> Reports Log</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="far fa-money-bill-alt"></i> Deposit Money</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-history"></i> Deposit History</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-hand-holding-usd"></i> Withdraw Money</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-history"></i> Withdraw History</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="far fa-plus-square"></i> New Ticket</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fa-solid fa-ticket"></i> My Ticket</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-history"></i> Transaction History</a>
                                        </li>
                                    </ul>
                                    <hr>
                                    <h6 class="mt-3 ps-3">Upgrade</h6>
                                    <ul class="nav flex-column ms-2 ps-3" style="font-size:14px;">
                                        <li class="nav-item">
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-id-card"></i> Upgrade Membership</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="far fa-folder-open"></i> Job Packages</a>
                                        </li>
                                    </ul>
                                    <hr>
                                    <ul class="nav flex-column ms-2 mt-3 ps-3" style="font-size:14px;">
                                        <li class="nav-item">
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-cog"></i> Settings</a>
                                        </li>
                                        <li>
                                            <a class="nav-link text-dark p-0 py-1" href="#"> <i
                                                    class="fas fa-question-circle"></i> Help Center</a>
                                        </li>
                                    </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--offCanvas end-->


            <div class="col-2 col-md-1">
                <ul class="nav float-end">
                    <li class="dropstart">
                        @if (Auth::check())
                            <a class=" nav-link img-fluid circle" href="#" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}"
                                    style="width: 35px; height:35px; border-radius: 50%;">
                            </a>
                            <ul class="dropdown-menu fs-12">
                                <li><a class="dropdown-item" href="#"">Dashboard</a></li>
                                <li><a class="dropdown-item" href="#">Update Profile</a></li>
                                <li style="width:100px;"><a class="dropdown-item" href="#">Change Password</a>
                                </li>
                                {{--  <li><a class="dropdown-item" href="#">2FA Security</a></li>  --}}
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        @endif
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
