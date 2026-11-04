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
                        <i class="fa-solid fa-chevron-right fs-14"></i> Billing and Payments
                    </p>
                    <!--Breadcrumbs End-->
                <div class="col-lg-12 mb-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item shadow-sm">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed text-dgreen fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Bringing up a dispute
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                 <div class="accordion-body">
                    <p>In order to attempt and settle any disputes, we urge Professionals and buyers to cooperate. The majority of disputes are settled without the involvement of the WL Dispute Team in most situations, and this
                        may be achieved with some excellent communication. In order to ensure that you are behaving in accordance with the Terms and Conditions on WL and that you are aware of what is expected of your Buyer or
                        Professional as well, we first advise that you check over our Engagement Guidelines, which are a component of the terms and conditions for Job Delivery.</p>
                    <ol style="list-style:lower-alpha">
                        <li><strong> Buyer</strong>
                            <p>When buyers are unhappy with the job, we encourage them to offer comments. Never forget the time and effort your professional put in to making sure the project was finished to your satisfaction.</p>
                            <p>The buyer can offer revisions and properly communicate the requirements via workstream in order to address any concerns with the ongoing jobs/projects.</p>
                            </li>
                        <li> <strong> Professional</strong>
                            <p>All professionals are urged to provide excellent customer service to their buyers, which includes taking reasonable steps to resolve any concerns. Before launching a Dispute, a Professional must make sure they have offered revisions.</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Requesting payments
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>You just need to submit a payment request to the buyer to have the buyer release the deposit money once you have finished the agreed-upon work on the project or service.</p>
                    <ol style="list-style:lower-alpha">
                        <li> Service payment: The buyer will make an advance payment equal to the amount of the service being purchased, and when the agreed-upon work has been finished, the buyer will submit a request to release the money to the professional.</li><br>
                        <li> Milestone payment: Once the buyer is satisfied with the quality of the work, the professional can request to release money for each milestone reached via the workspace area after completing the agreed-upon task. </li><br>
                        <li> Fixed contract: Once the buyer is satisfied with the quality of the work, the professional can request to release money for the fixed contract completed via the workspace area after completing the agreed-upon task. </li><br>
                        <li> Per hour job: Once the buyer is satisfied with the quality of the job, the professional may submit a timesheet through workspace and ask the buyer to release payment. </li><br>
                    </ol>
                    <p>Your buyer has seven days to pay your invoice in full.</p>
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
