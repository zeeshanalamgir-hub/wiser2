<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Wiserlance - Home </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/templates/basic/frontend/css/style2.css') }}" rel="stylesheet">

    <!-- Fonts Awesom -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .form-control:focus {
            color:#495057 !important;
        }
        .carousel-wrapper {
            position: relative;
            margin: 0px 0;
        }

        .carousel-container {
            width: 100%;
            position: relative;
            padding: 0 0px;
            overflow: hidden;
        }

        .inner-carousel {
            width: 1350px;
            position: relative;
            overflow: visible;
        }

        .track {
            width: 100%;
            padding-top: 12px;
            display: flex;
            transition: transform 0.4s ease;
            gap: 2px;

        }

        .card-container {
            flex: 0 0 auto;
        }

        .card-c {
            background: #eaf5ed;
            /*background: linear-gradient(145deg, #f0f4f8, #e3e8ef);*/
            padding: 3px 10px;
            border-radius: 10px;
            text-align: center;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #a7d6cbff;
            font-weight: 500;
            color: #495057;
        }

        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            border: none;
            /*z-index: 10;*/
            cursor: pointer;
        }

        .nav-button:hover {
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-button:active {
            transform: translateY(-50%) scale(0.95);
        }

        .nav-button.prev {
            left: 0;
        }

        .nav-button.next {
            right: 0;
        }

        .nav-button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .nav-button.disabled:hover {
            transform: translateY(-50%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu a:hover {
            background: white;
        }

        a.nav-link {
            padding: auto 0 !important;
        }

        .solution-container {
            position: relative;
            background-color: #000;
            padding: 30px;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("{{ asset('assets/img/home/bg-header.jpg') }}");

            background-size: cover;
            background-position: center;
            opacity: 0.5;
            z-index: 1;

        }

        .content {
            position: relative;
            z-index: 2;
        }

        .nested-box {
            width: 50%;
            height: 230px;
            background-color: rgba(255, 255, 255, 0.9);
            /*display: flex;*/
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        button.active {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .img-carousel-wrapper {
            position: relative;
            margin: 20px 0;
        }

        .img-carousel-container {
            width: 94%;
            overflow: hidden;
            position: relative;
        }

        .img-inner-carousel {
            position: relative;
            overflow: visible;
        }

        .img-track {
            display: flex;
            gap: 10px;
            transition: transform 0.4s ease;
        }

        .img-card-container {
            flex: 0 0 auto;
            width: 270px;
            /* fixed width for each image */
            padding: 10px;
        }

        .img-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            border: 5px solid #dee2e6;
        }

        .img-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .img-nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #037e89;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            border: none;
            cursor: pointer;
            z-index: 10;
        }

        .img-nav-button:hover {
            transform: translateY(-50%) scale(1.1);
        }

        .img-nav-button.prev {
            left: -10px;
        }

        .img-nav-button.next {
            right: -10px;
        }

        .img-nav-button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <!--navBar ------------------------------>
    <div class="border border-bottom" style="position:sticky; top:0; z-index:999;">
        @include('templates.basic.header_new')

        <!------------------------------ Carousel Start here------------------------------------>

        <div class="container-fluid bg-white">
            <div class="container">
                <div id="cat-bar" class="row bg-white ">
                    <div class="col">
                        <div class="carousel-wrapper">
                            <div class="carousel-container">
                                <div class="inner-carousel">
                                    <div class="track">
                                        @if(count ($cat) > 0)
                                            @foreach($cat as $category)
                                                <div class="card-container">
                                                    <div data-name="{{ $category?->name }}" class="card-c ms-4">
                                                        <a style="text-decoration: none;color:#495057" href="{{ route('job',['cat_id'=>$category->name]) }}">
                                                            {{ $category?->name }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <button class="nav-button prev">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button class="nav-button next">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="solution-container" style="height:550px;">
        <div class="background-overlay"></div>
        <div class="content">
            <div class="row">
                <div class="col-sm-1 col-md-2 col-xl-3 "></div>
                <div class="col-sm-10 col-md-8 col-xl-6 pt-5 ">
                    <div class="container bg-light flex-row rounded-3 mx-auto p-5">

                        <div id="pro">
                            <p class="text-center fs-1 fw-bold text-dgreen2">
                                PROFESSIONALS
                            </p>
                            <p class="fs-5 text-center text-dgreen">Find skilled experts ready to bring your projects
                                to life with unmatched expertise.</p>
                        </div>

                        <div id="job" style="display:none">
                            <p class="text-center fs-1 fw-bold text-dgreen2">
                                JOBS
                            </p>
                            <p class="fs-5 text-center text-dgreen">Find skilled experts ready to bring your projects
                                to life with unmatched expertise.</p>
                        </div>

                        <div id="serv" style="display:none">
                            <p class="text-center fs-1 fw-bold text-dgreen2">
                                Services
                            </p>
                            <p class="fs-5 text-center text-dgreen">Find skilled experts ready to bring your projects
                                to life with unmatched expertise.</p>
                        </div>

                    </div>

                    <div class="mx-auto mt-3 d-flex">
                        <button id="btn-pro" onclick="showDiv('pro', this, 'professionals')"
                            class="btn btn-md bg-green3 text-white w-100">
                            PROFESSIONALS
                        </button>
                        <button id="btn-job" onclick="showDiv('job', this, 'jobs')"
                            class="btn btn-md border border-2 btn-outline-light w-100 mx-2">
                            JOBS
                        </button>
                        <button id="btn-serv" onclick="showDiv('serv', this, 'services')"
                            class="btn btn-md border border-2 btn-outline-light w-100">
                            SERVICES
                        </button>
                    </div>

                    <div class="mx-auto mt-3 bg-light rounded p-2">
                        <form class="d-flex" role="search">
                            <input type="hidden" name="type" id="searchType" value="professionals">
                            <input class="form-control me-2" name="search" type="search"
                                placeholder="Search for Professionals, Jobs or Services" aria-label="Search">
                            <button class="btn btn-success bg-green3" type="submit">Search</button>
                        </form>
                    </div>

                </div>
                <div class="col-sm-1 col-md-2 col-xl-3"></div>
            </div>

        </div>
    </div>


    <!-- Contents area ----------------------->
    <div class="container-fluid px-md-5 bg-lgreen3 py-4">
        <div class="container">
            <div class="row p-lg-3 pb-lg-5">
                <div class="col-12 p-lg-4">
                    <center>
                        <h1 class="text-center fw-bold mb-3 mt-3 text-dgreen "> Comprehensive Services, Expertly
                            Delivered </h1>
                        <p class="mb-5 fs-18 w-sm-75">Wiserlancer offers a versatile, end-to-end portfolio of services
                            crafted to solve your business challenges and support your strategic vision.</p>

                        @if (count($cat) > 0)
                            <div class="container-fluid">
                                <div class="container">
                                    <div class="img-carousel-wrapper">
                                        <div class="img-carousel-container">
                                            <div class="img-inner-carousel">
                                                <div class="img-track">

                                                    <!-- Images -->
                                                    @foreach ($cat as $category)
                                                        <div class="img-card-container">
                                                            <div id="cat"
                                                                class="bg-white py-3 rounded-1 border-success shadow-sm">
                                                                <img src="{{ asset('assets/images/category/' . $category->image) }}"
                                                                    class="img-fluid" width="60">
                                                                <p class="mt-2 mb-1 fw-bold text-dgreen2">
                                                                    {{ $category->name }}</p>
                                                                <p class="badge bg-green p-2 mt-2 fs-14">
                                                                    {{ $category->jobs_count }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Prev/Next buttons -->
                                        <button class="img-nav-button prev "><i
                                                class="fa-solid fa-chevron-left"></i></button>
                                        <button class="img-nav-button next"><i
                                                class="fa-solid fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const prevButton = document.querySelector('.img-nav-button.prev');
                                const nextButton = document.querySelector('.img-nav-button.next');
                                const track = document.querySelector('.img-track');
                                const carousel = document.querySelector('.img-carousel-container');
                                const cards = document.querySelectorAll('.img-card-container');

                                let index = 0;
                                const cardWidth = cards[0].offsetWidth + parseInt(getComputedStyle(track).gap);
                                const visibleCards = Math.floor(carousel.offsetWidth / cardWidth);
                                const maxIndex = cards.length - visibleCards;

                                updateButtonStates();

                                nextButton.addEventListener('click', function() {
                                    if (index < maxIndex) {
                                        index++;
                                        updateCarousel();
                                    }
                                });

                                prevButton.addEventListener('click', function() {
                                    if (index > 0) {
                                        index--;
                                        updateCarousel();
                                    }
                                });

                                window.addEventListener('resize', function() {
                                    const newVisibleCards = Math.floor(carousel.offsetWidth / cardWidth);
                                    const newMaxIndex = cards.length - newVisibleCards;
                                    if (index > newMaxIndex) index = newMaxIndex;
                                    updateCarousel();
                                });

                                function updateCarousel() {
                                    track.style.transform = `translateX(${-index * cardWidth}px)`;
                                    updateButtonStates();
                                }

                                function updateButtonStates() {
                                    prevButton.classList.toggle('disabled', index === 0);
                                    nextButton.classList.toggle('disabled', index >= maxIndex);
                                }
                            });
                        </script>
                        <center>
                </div> <!--colmn closed-->
            </div><!--Row closed-->
        </div>
    </div>

    <div class="container-fluid bg-white pt-5">
        <div class="container">
            <div class="row p-3 px-lg-5">
                <div class="col-5 d-none  d-lg-block pt-5 ">
                    <img class="img-fluid rounded-4 border-green"
                        src="{{ asset('assets/img/home/for-buyers.avif') }}">
                </div>

                <div class="col-lg-7 ps-4">
                    <h1 class="text-dgreen fw-bold textdul">For Buyers</h1>
                    <h3 class="mt-4 text-dgreen">Expertise, On Demand</h3>
                    <p>Connect with the ideal professional on Wiserlance—seamlessly, and with certainty.</p>
                    <p>Find the verified professional for your project. Choose a ready-to-go service or post a custom
                        job to receive competitive proposals from our global network of experts—all within your budget.
                    </p>

                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <div class="card py-4 rounded-4 shadow" style="height:195px;">
                                <center>
                                    <i class="fas fa-search rounded-5 fs-5 bg-dgreen2 text-white text-center pt-2 shadow"
                                        style="width:40px;height:40px"></i>
                                    <h6 class="text-dgreen fs-18 fw-bold my-3">Search & Buy Service</h6>
                                    <a href="{{ route('service') }}"
                                        class="btn btn-sm rounded-5 bg-green shadow btn-success ">Browse
                                        Services</a>
                                </center>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card p-4 rounded-4 mt-3 mt-sm-0 shadow " style="height:195px;">
                                <center>
                                    <i class="fas fa-user-check rounded-5 fs-5 bg-dgreen2 text-white text-center shadow pt-2"
                                        style="width:40px;height:40px"></i>
                                    <h6 class="text-dgreen fs-18 fw-bold my-3">Post a Job & Hire a Professional</h6>
                                    @if(Auth::check() && (Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1'))
                                        <a href="{{ route('user.job.create') }}" class="btn rounded-5 bg-green btn-success ms-3">Post a
                                            Job</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn rounded-5 bg-green btn-success ms-3">Post a
                                            Job</a>
                                    @endif
                                </center>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid bg-lgreen3 mt-5">
        <div class="container">
            <div class="row p-5 px-2 px-sm-5 ">
                <h1 class="text-center text-dgreen fw-bold"> How Wiserlance Works</h1>
                <p class="text-center">Our simple four-step process makes it easy to find the perfect talent or the
                    ideal project</p>

                <div class="col-lg-4 col-xl-3 mt-4">
                    <div class="card rounded-4 p-4 shadow" style="height:265px;">
                        <center>
                            <i class="fas fa-edit rounded-5 fs-5 bg-green text-white text-center shadow pt-2"
                                style="width:40px;height:40px"></i>
                            <h6 class="text-dgreen2 fs-18 fw-bold my-3">Post a Job or Service</h6>
                            <p class="fs-14">Create a detailed job or service listing outlining your requirements and
                                budget. Our intuitive form makes it quick and easy.</p>
                        </center>
                    </div>
                </div>

                <div class="col-lg-4 col-xl-3 mt-4">
                    <div class="card rounded-4 p-4 shadow" style="height:265px;">
                        <center>
                            <i class="fas fa-search rounded-5 fs-5 bg-green text-white text-center shadow pt-2"
                                style="width:40px;height:40px"></i>
                            <h6 class="text-dgreen2 fs-18 fw-bold my-3">Find Perfect Match</h6>
                            <p class="fs-14">Review job postings, service offerings, or profiles to find the perfect
                                match. Our algorithm helps you discover the best talent.</p>
                        </center>
                    </div>

                </div>

                <div class="col-lg-4 col-xl-3 mt-4">
                    <div class="card rounded-4 p-4 shadow" style="height:265px;">
                        <center>
                            <i class="fas fa-sliders-h rounded-5 fs-5 bg-green text-white text-center shadow pt-2"
                                style="width:40px;height:40px"></i>
                            <h6 class="text-dgreen2 fs-18 fw-bold my-3">Get It Done, Your Way</h6>
                            <p class="fs-14">Set the pace, decide how payments work, and keep everything flowing in
                                WorkRooms — where collaboration, communication, and progress come together.</p>
                        </center>
                    </div>

                </div>

                <div class="col-lg-4 col-xl-3 mt-4">
                    <div class="card rounded-4 p-4 shadow" style="height:265px;">
                        <center>
                            <i class="fas fa-handshake rounded-5 fs-5 bg-green text-white text-center shadow pt-2"
                                style="width:40px;height:40px"></i>
                            <h6 class="text-dgreen2 fs-18 fw-bold my-3">Collaborate & Pay Securely</h6>
                            <p class="fs-14">Work together securely and release payment when milestones are completed.
                                Our platform ensures safe transactions for everyone.</p>
                        </center>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white py-5">
        <div class="container">
            <div class="row p-3 px-lg-5">
                <div class="col-5 d-none  d-lg-block pt-5 ">

                    <img class="img-fluid rounded-4 border-green"
                        src="{{ asset('assets/img/home/for-buyers.avif') }}">
                </div>

                <div class="col-lg-7 ps-4">
                    <h1 class="text-dgreen fw-bold textdul">For Sellers</h1>
                    <h3 class="text-dgreen mt-4">Discover Outstanding Opportunities</h3>
                    <p>Command your career as a freelancer. Enjoy the independence to choose your projects and the
                        flexibility to work from anywhere, on your terms.</p>
                    <h5 class="text-dgreen">So why wait? Start exploring Wiserlance for freelancing today!</h5>
                    <p>Reach a global clientele and work on your terms. Whether you prefer the structure of fixed-price
                        projects or the flexibility of hourly work, Wiserlance provides the tools to make it happen.</p>

                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <div class="card rounded-4 p-4 shadow ">
                                <center>
                                    <i class="fas fa-business-time rounded-5 fs-5 bg-dgreen2 text-white text-center pt-2 shadow"
                                        style="width:40px;height:40px"></i>
                                    <h6 class="text-dgreen fs-18 fw-bold my-3">Find Projects</h6>
                                    <a href="{{ route('job') }}"
                                        class="btn btn-sm rounded-5 bg-green shadow btn-success 0">Browse
                                        Jobs</a>
                                </center>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card rounded-4 p-4 mt-3 mt-sm-0 shadow">
                                <center>
                                    <i class="fas fa-tools rounded-5 fs-5 bg-dgreen2 text-white text-center shadow pt-2"
                                        style="width:40px;height:40px"></i>
                                    <h6 class="text-dgreen fs-18 fw-bold my-3">Offer Your Services</h6>
                                    @if(Auth::check() && (Auth::user()->is_freelancer == 1 || Auth::user()->is_freelancer == '1'))
                                        <a href="{{ route('user.service.create') }}"
                                            class="btn btn-sm rounded-5 bg-green btn-success shadow">Create a
                                            Service</a>
                                    @else
                                        <a href="javascript:void(0)"
                                            class="btn btn-sm rounded-5 bg-green btn-success shadow">Create a
                                            Service</a>
                                    @endif
                                </center>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid bg-lgreen3 ">
        <div class="container">
            <div class="row py-5 p-sm-5">
                <h2 class="text-dgreen text-center fs-1 fw-bold">Ready to get started?</h2>
                <p class="text-center mt-2">Join thousands of employers and freelancers already thriving with jobs and
                    services on our platform.</p>
                <p class="text-center mt-3">
                    <a href="{{ route('service') }}" class="btn  rounded-5 bg-dgreen2 btn-success ">Find Services</a>
                    @if(Auth::check() && (Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1'))
                        <a href="{{ route('user.job.create') }}" class="btn rounded-5 bg-green btn-success ms-3">Post a
                            Job</a>
                    @else
                        <a href="javascript:void(0)" class="btn rounded-5 bg-green btn-success ms-3">Post a
                            Job</a>
                    @endif
                </p>
            </div>
        </div>
    </div>
    </div>

    @include('templates.basic.footer_new')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prevButton = document.querySelector('.nav-button.prev');
        const nextButton = document.querySelector('.nav-button.next');
        const track = document.querySelector('.track');
        const carousel = document.querySelector('.carousel-container');
        const cards = document.querySelectorAll('.card-container');
        const indicators = document.querySelectorAll('.indicator');

        let index = 0;
        const cardWidth = cards[0].offsetWidth + parseInt(getComputedStyle(track).gap);
        const visibleCards = Math.floor(carousel.offsetWidth / cardWidth);
        const maxIndex = cards.length - visibleCards;

        // Initialize button states
        updateButtonStates();

        // Next button event listener
        nextButton.addEventListener('click', function() {
            if (index < maxIndex) {
                index++;
                updateCarousel();
            }
        });

        // Previous button event listener
        prevButton.addEventListener('click', function() {
            if (index > 0) {
                index--;
                updateCarousel();
            }
        });

        // Indicator click event listeners
        indicators.forEach((indicator, i) => {
            indicator.addEventListener('click', function() {
                index = i * Math.ceil(maxIndex / indicators.length);
                updateCarousel();
            });
        });

        // Card click event listeners
        cards.forEach(card => {
            card.addEventListener('click', function() {
                cards.forEach(c => c.querySelector('.card-c').classList.remove('active'));
                this.querySelector('.card-c').classList.add('active');
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const newVisibleCards = Math.floor(carousel.offsetWidth / cardWidth);
            const newMaxIndex = cards.length - newVisibleCards;

            if (index > newMaxIndex) {
                index = newMaxIndex;
            }

            updateCarousel();
        });

        // Update carousel position and button states
        function updateCarousel() {
            track.style.transform = `translateX(${-index * cardWidth}px)`;
            updateButtonStates();
            updateIndicators();
        }

        // Update button disabled states
        function updateButtonStates() {
            prevButton.classList.toggle('disabled', index === 0);
            nextButton.classList.toggle('disabled', index >= maxIndex);
        }

        // Update indicator states
        function updateIndicators() {
            const activeIndicator = Math.floor(index / Math.ceil(maxIndex / indicators.length));
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === activeIndicator);
            });
        }
    });

    function showDiv(divId, button, type) {
        // Hide all sections
        document.getElementById('pro').style.display = 'none';
        document.getElementById('job').style.display = 'none';
        document.getElementById('serv').style.display = 'none';

        // Show the selected section
        document.getElementById(divId).style.display = 'block';

        // Update hidden input type value
        document.getElementById('searchType').value = type;

        // Reset button styles
        const buttons = document.querySelectorAll('.mx-auto .btn');
        buttons.forEach(btn => {
            btn.classList.remove('bg-green3', 'text-white');
            btn.classList.add('btn-outline-light');
        });

        // Activate selected button
        button.classList.add('bg-green3', 'text-white');
        button.classList.remove('btn-outline-light');
    }

    // Optional: Set default active section and type on page load
    window.addEventListener('DOMContentLoaded', () => {
        showDiv('pro', document.getElementById('btn-pro'), 'professionals');
    });
</script>

</html>
</div>
