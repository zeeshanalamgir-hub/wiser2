@extends('homepages.layout.app')

@section('title', 'Why Choose Us')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
@endsection

@section('content')
<div class="container-fluid px-md-5 bg-lgreen3">
    <div class="container">
    <div class="row pt-5">
        <div class="col-12 ">
            <center>
            <h1 class="text-center fw-bold mb-2 text-dgreen "> Why Choose Us?</h1>
            <p class="mb-5 fs-18 text-dgreen  w-md-75">Discover why millions of Employers and Freelancers trust our platform to connect, collaborate, and succeed.</p>

            <center>
        </center></center></div> <!--colmn closed-->
    </div><!--Row closed-->
</div>  </div>

<div class="container-fluid bg-dgreen2">
    <div class="container">
    <div class="row py-5">
        <h2 class="text-white text-center fw-bold textdul pb-4">For Employers</h2>
                <div class="col-12">
                    <div class="card p-3 pb-2 rounded-3 shadow my-2">
                        <div class="d-sm-flex">
                        <p><i class="fa-solid fa-user-tag rounded-5 fs-5 bg-green text-white text-center pt-2 shadow " style="width:40px;height:40px"></i></p>
                         <h6 class="text-dgreen fs-18 fw-bold mt-xl-2 ms-2"> Verified Seller </h6>
                        <p class="fs-16 fw-normal mt-xl-2 ms-3 ms-2 ms-sm-3">Browse verified freelancers with transparent transaction data, ensuring trust and quality for your projects.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card p-3 pb-2 rounded-3 shadow my-2">
                        <div class="d-sm-flex">
                        <p><i class="fa-solid fa-user-check rounded-5 fs-5 bg-green text-white text-center pt-2 shadow " style="width:40px;height:40px"></i></p>
                        <h6 class="text-dgreen fs-18 fw-bold mt-xl-2 ms-2">   Verified Buyer   </h6>
                        <p class="fs-16 fw-normal mt-2 ms-xl-3 ms-2 ms-sm-3">Our platform verifies buyers to maintain a secure marketplace, fostering safe and reliable transactions.</p>
                        </div>
                    </div>
                </div>
                 <div class="col-12">
                    <div class="card p-3 pb-2 rounded-3 shadow my-2">
                        <div class="d-sm-flex">
                            <p><i class="fa-solid fa-lock rounded-5 fs-5 bg-green text-white text-center pt-2 shadow  " style="width:40px;height:40px"></i></p>
                        <h6 class="text-dgreen fs-18 fw-bold mt-xl-2 ms-2"> Escrow Secure Payment </h6>
                        <p class="fs-16 fw-normal mt-xl-2 ms-2 ms-sm-3">Protect your funds with our escrow system, releasing payments only after reviewing completed work.</p>
                        </div>
                    </div>
                </div>
                 <div class="col-12">
                    <div class="card p-3 pb-2 rounded-3 shadow my-2">
                        <div class="d-sm-flex">
                        <p><i class="fa-solid fa-user-graduate rounded-5 fs-5 bg-green text-white text-center pt-2 shadow " style="width:40px;height:40px"></i></p>
                        <h6 class="text-dgreen fs-18 fw-bold mt-xl-2 ms-2 "> Advanced Job Skills Match </h6>
                        <p class="fs-16 fw-normal mt-xl-2 ms-2 ms-sm-3">Our cutting-edge algorithms match your job with the perfect talent, ensuring exceptional project outcomes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     </div>

     <div class="container-fluid bg-lgreen3 mt-5 ">
    <div class="container">
    <div class="row px-sm-5 g-5">
        <h2 class="text-dgreen text-center fw-bold textdul pb-4">For Freelancers</h2>

        <div class="col-lg-6 ">
            <div class="card rounded-4 border-dgreen3 p-4 shadow">
                <center>
                <i class="fas fa-edit rounded-5 fs-5 bg-green text-white text-center" style="width:50px;height:50px; padding-top:13px;"></i>
                <h6 class="text-dgreen2 fs-5 fw-bold my-3">Find and Apply for Jobs</h6>
                <p class="">Search for jobs that match your skills and stand out with premium bids to secure your next project.</p>
                </center>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card rounded-4 border-dgreen3 p-4 shadow">
                <center>
                <i class="fas fa-search rounded-5 fs-4 bg-green text-white text-center" style="width:50px;height:50px; padding-top:13px;"></i>
                <h6 class="text-dgreen2 fs-5 fw-bold my-3">Unbeatable Price Plans</h6>
                <p>Choose from flexible pricing plans designed to maximize value and meet your freelancing needs.</p>
                </center>
            </div>

        </div>

        <div class="col-lg-6  ">
            <div class="card rounded-4 border-dgreen3 p-4 shadow">
                <center>
                <i class="fas fa-sliders-h rounded-5 fs-4 bg-green text-white text-center" style="width:50px;height:50px; padding-top:13px;"></i>
                <h6 class="text-dgreen2 fs-5 fw-bold my-3">Financial Benefits for Sellers</h6>
                <p class="">Enjoy low fees as little as 5% and fast payouts, making our platform the top choice for freelancers.</p>
                </center>
            </div>

        </div>

        <div class="col-lg-6 ">
            <div class="card rounded-4 border-dgreen3 p-4 shadow">
                <center>
                <i class="fas fa-handshake rounded-5 fs-4 bg-green text-white text-center" style="width:50px;height:50px; padding-top:13px;"></i>
                <h6 class="text-dgreen2 fs-5 fw-bold my-3">Flexible Work Environment</h6>
                <p class="">Use WorkRooms to manage multiple jobs, collaborate with teams, and create agreements that suit you.</p>
                </center>
            </div>

        </div>
    </div>
</div>
</div>

<div class="container-fluid bg-lgreen3 ">
    <div class="container">
        <div class="row py-5 p-sm-5">
            <h2 class="text-dgreen text-center fs-1 fw-bold">Ready to get started?</h2>
            <p class="text-center mt-2">Join thousands of employers and freelancers already thriving with jobs and services on our platform.</p>
            <p class="text-center mt-3">
            @guest
                <a class="btn rounded-5 bg-dgreen2 btn-success" href="{{ url('/job') }}">
                    Find a Job
                </a>

            @else

                @if(Auth::user()->is_employer == 1)

                    <a class="btn rounded-5 bg-dgreen2 btn-success" href="{{ url('/service') }}">
                        Find a Service
                    </a>

                    <a href="{{ route('user.job.create') }}" class="btn border-0 rounded-5 bg-green btn-success ms-3">
                        Post a Job
                    </a>

                @else

                    <a class="btn rounded-5 bg-dgreen2 btn-success" href="{{ url('/job') }}">
                        Find a Job
                    </a>

                    <a href="{{ route('user.service.create') }}" class="btn border-0 rounded-5 bg-green btn-success ms-3">
                        Create a Service
                    </a>

                @endif

            @endguest

        </p>
        </div>
    </div>
</div>
@endsection

@section('custom-js')

<script>
        document.addEventListener('DOMContentLoaded', function() {
             showLoader();

            setTimeout(hideLoader, 5000);
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
    </script>
@endsection
