@extends('homepages.layout.app')

@section('title', 'Help Support Center')

@section('custom-css')
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
@endsection

@section('content')
<div class="bg-dgreen2">
    <div class="container">
    <div class="row py-5">
        <div class="col-lg-6 d-flex ps-lg-5">
            <h1 class="fs-3 text-white me-3 " style="display: flex;align-items:center">
                <img src="{{ asset('assets/images/home-page/Customer-Support.png') }}" width="60" class="rounded-circle bg-white p-1 me-3">
                Authentication and Registration</h1>
        </div> <!--colmn closed-->
        <div class="col-lg-6 px-lg-5">
            <form class="d-flex mt-2" role="search">
                <input class="form-control bg-white shadow-sm float-end bg-lgreen " type="search" placeholder="Search for help" aria-label="Search">
                <button class="btn btn-outline-dark btn-sm ms-1 bg-green border-0" type="submit"><img src="{{ asset('assets/images/home-page/icons8-search.svg') }}" width="30"></button>
            </form>
        </div> <!--colmn closed-->
    </div>
    </div>
</div>

<div class="bg-light">
        <div class="container">
            <div class="row px-2 p-lg-3">
                    <!--Breadcrumbs-->
                    <p class=" mb-0 text-bc mb-4 fs-14">
                        <a class="text-3gray" href="/"> <i class="fa-solid fa-house"></i></a>
                        <i class="fa-solid fa-chevron-right fs-14"></i> <a class="text-3gray links-c1" href="{{ route('help.support.center') }}"> Help &amp; Support</a>
                        <i class="fa-solid fa-chevron-right fs-14"></i> Profile and Settings
                    </p>
                    <!--Breadcrumbs End-->
                <div class="col-lg-12 mb-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item shadow-sm">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed text-dgreen fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Renaming your profile
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                 <div class="accordion-body">
                    <p> Our Buyers and Professionals at Wiserlance are trustworthy. We encourage all of our users to always utilise their actual profile picture and information when
                        verifying their accounts with us. All of our users are verified through our platform, and our Payment Team makes sure that there are no bypasses or breaches
                        of our Terms and Conditions. We really hope that you would comprehend that when carrying out these checks, we keep our clients' best interests as well as Know
                        Your Client (KYC) compliance requirements in mind.</p>

                    <p> However, in order to prevent any unnecessary surprises, here is your basic starter guide:</p>

                    <ol>
                        <li> Professionals can access their dashboard (seller dashboard) by logging in with their verified login information.</li>
                        <li> Professionals can complete the following verification steps under "Withdraw Money" section:</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                The professional application was rejected
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>Our Buyers and Professionals at Wiserlance are trustworthy. When creating a job or service, we encourage our users to use their genuine profile photo, their real information, and most importantly, to be authentic to themselves.</p>
                    <p>We strongly advise all users to read the Wiserlance Terms &amp; Conditions and any related documents. Your access may be permanently restricted if you violate Wiserlance rules or violate our terms and conditions by creating fictitious accounts or providing incorrect payment information.</p>
                    <h6>-	Signing up on Wiserlance</h6>
                    <ul>
                        <li> You may sign in as a new user using either Facebook or Google. You can register a different account if you don't want to do that. You must verify your account before continuing.</li>
                        <li> You will get the following choice to choose and continue your registration as a new user:
                                <ul>
                                    <li>Buyer</li>
                                    <li>Seller</li>
                                    <li>Both</li>
                                </ul>
                        </li>
                    </ul>
                    <p>It won't take long to create your account! Once your profile has been completely verified, you will be notified and given the choice to move forward and browse for services. Create a job posting and locate professionals that fulfil your criteria!</p>
                    <p>The Dashboard will allow you to see any information regarding your account, including job creation, job management, employee list, booked services, deposits etc.</p>

                </div>
            </div>
        </div>
        </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-lgreen2 p-3">
    <div class="containaer text-center">
        <h5 class=""> <i>Do you have a question? please send us your query, and we'll be happy to assist you?</i>
            <a href="{{ route('contact.us') }}" class="btn btn-success bg-dgreen ms-3 fs-5 mt-2 mt-lg-0"> <i class="fa-solid fa-chevron-right text-green"></i> Ask Now</a>
        </h5>
    </div>
</div>
@endsection

@section('custom-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
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
        const cardWidth = cards[0]?.offsetWidth + parseInt(getComputedStyle(track).gap || 0);
        const visibleCards = Math.floor(carousel?.offsetWidth / cardWidth);
        const maxIndex = cards.length - visibleCards;

        // Initialize button states
        updateButtonStates();

        // Next button event listener
        if (nextButton) {
            nextButton.addEventListener('click', function() {
                if (index < maxIndex) {
                    index++;
                    updateCarousel();
                }
            });
        }

        // Previous button event listener
        if (prevButton) {
            prevButton.addEventListener('click', function() {
                if (index > 0) {
                    index--;
                    updateCarousel();
                }
            });
        }

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
                cards.forEach(c => c.querySelector('.card-c')?.classList.remove('active'));
                this.querySelector('.card-c')?.classList.add('active');
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const newVisibleCards = Math.floor(carousel?.offsetWidth / cardWidth);
            const newMaxIndex = cards.length - newVisibleCards;

            if (index > newMaxIndex) {
                index = newMaxIndex;
            }

            updateCarousel();
        });

        // Update carousel position and button states
        function updateCarousel() {
            if (track) {
                track.style.transform = `translateX(${-index * cardWidth}px)`;
            }
            updateButtonStates();
            updateIndicators();
        }

        // Update button disabled states
        function updateButtonStates() {
            if (prevButton) {
                prevButton.classList.toggle('disabled', index === 0);
            }
            if (nextButton) {
                nextButton.classList.toggle('disabled', index >= maxIndex);
            }
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
