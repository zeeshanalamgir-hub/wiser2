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
                        <i class="fa-solid fa-chevron-right fs-14"></i> Policies
                    </p>
                    <!--Breadcrumbs End-->
                <div class="col-lg-12 mb-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item shadow-sm">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed text-dgreen fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Delete my WL account
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                 <div class="accordion-body">
                    <p>Wiserlance is a platform that puts professionals through a rigorous test while they create their profiles, ensuring that only genuine professionals would join Wiserlance and assist buyers.</p>
                    <p>However, in order to prevent any unnecessary surprises, here is your basic starter guide:</p>
                    <ol>
                        <li>	Browse services, buy any services you need in an easy term means and pre-packaged form for speedy delivery, and you can get started right away and maybe even test out a professional.</li>
                        <li>	Post a Job for larger pieces of work and you will instantly begin receiving custom proposals from top professionals; alternatively, browse professional profiles and directly contact specific professionals.</li>
                        <li> Check out a professional's profile before hiring them to learn more about their background, experience and rating on WL.
                        </li><li>	You will be requested to pay money into the Escrow account so that the professional may start working once you decide to accept a professional's bid or after you buy a service. In the case of custom jobs, this will be the agreed-upon deposit amount specified in the bid; in the case of services, the sum you put into the Escrow account serves as the whole purchase price.</li>
                        <li>	Follow the Engagement Guidelines while working with a professional, and always keep a record of conversations in the WorkStream to remember what has been discussed and decided.</li>
                        <li> When the work has been completed to your satisfaction, release all escrow account money and make the full payment to the professional by paying their payment request. After that, you may provide your expert opinion on how they performed.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Engagement guidelines
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
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingThree">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                Policies For Profile
            </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>Although most tasks go smoothly and according to plan, we acknowledge that occasionally things might change and it could be necessary to cancel a job. You may ask for a cancellation after you accept a bid, but please make sure to:</p>
                    <ul>
                        <li> You must inform the professional of the cancellation and provide reasoning in the WorkStream.</li>
                        <li> If there are money in the escrow account and you think you are entitled to a refund (see Section 6.2 of our Terms and Conditions)</li>
                    </ul>
                    <h6>You may ask for a refund if:</h6>
                    <ol>
                        <li> The freelancer failed to finish the job by the predetermined deadline.</li>
                        <li> You have made an effort to contact the professional through WorkStream since they haven't answered to a query in the WorkStream after one working day.</li>
                        <li> The freelancer can be notified of the cancellation and a dispute ticket raised so our dispute resolution team can assist you.</li>
                        <li> You (the Buyer) are obligated to pay for the service provided in line with our terms and conditions if you (the Buyer) fail to inform the Professional in the WorkStream that you desire to terminate the job and it is subsequently completed.</li>
                        <li> For any work completed on purchased service, milestone and fixed price in case of a fixed price job or time spent, if it is a per-hour project, you should agree with the Freelancer in the WorkStream what payment is required.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingFour">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                Policies For Services
            </button>
            </h2>
            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>Although most tasks go smoothly and according to plan, we acknowledge that occasionally things might change and it could be necessary to cancel a job. You may ask for a cancellation after you accept a bid, but please make sure to:</p>
                    <ul>
                        <li> You must inform the professional of the cancellation and provide reasoning in the WorkStream.</li>
                        <li> If there are money in the escrow account and you think you are entitled to a refund (see Section 6.2 of our Terms and Conditions)</li>
                    </ul>
                    <h6>You may ask for a refund if:</h6>
                    <ol>
                        <li> The freelancer failed to finish the job by the predetermined deadline.</li>
                        <li> You have made an effort to contact the professional through WorkStream since they haven't answered to a query in the WorkStream after one working day.</li>
                        <li> The freelancer can be notified of the cancellation and a dispute ticket raised so our dispute resolution team can assist you.</li>
                        <li> You (the Buyer) are obligated to pay for the service provided in line with our terms and conditions if you (the Buyer) fail to inform the Professional in the WorkStream that you desire to terminate the job and it is subsequently completed.</li>
                        <li> For any work completed on purchased service, milestone and fixed price in case of a fixed price job or time spent, if it is a per-hour project, you should agree with the Freelancer in the WorkStream what payment is required.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingFive">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                WL Fee Calculator
            </button>
            </h2>
            <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>Although most tasks go smoothly and according to plan, we acknowledge that occasionally things might change and it could be necessary to cancel a job. You may ask for a cancellation after you accept a bid, but please make sure to:</p>
                    <ul>
                        <li> You must inform the professional of the cancellation and provide reasoning in the WorkStream.</li>
                        <li> If there are money in the escrow account and you think you are entitled to a refund (see Section 6.2 of our Terms and Conditions)</li>
                    </ul>
                    <h6>You may ask for a refund if:</h6>
                    <ol>
                        <li> The freelancer failed to finish the job by the predetermined deadline.</li>
                        <li> You have made an effort to contact the professional through WorkStream since they haven't answered to a query in the WorkStream after one working day.</li>
                        <li> The freelancer can be notified of the cancellation and a dispute ticket raised so our dispute resolution team can assist you.</li>
                        <li> You (the Buyer) are obligated to pay for the service provided in line with our terms and conditions if you (the Buyer) fail to inform the Professional in the WorkStream that you desire to terminate the job and it is subsequently completed.</li>
                        <li> For any work completed on purchased service, milestone and fixed price in case of a fixed price job or time spent, if it is a per-hour project, you should agree with the Freelancer in the WorkStream what payment is required.</li>
                    </ol>
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
