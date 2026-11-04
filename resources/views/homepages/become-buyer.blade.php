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
                <img src="{{ asset('assets/images/home-page/Customer-Support.png') }}" width="60" class="rounded-circle bg-white p-1 me-3 ">
                 Help and Support Center</h1>
        </div> <!--colmn closed-->
        <div class="col-lg-6 px-lg-5">
            <form class="d-flex mt-2" role="search">
                <input class="form-control bg-white shadow-sm float-end bg-lgreen " type="search" placeholder="Search for help" aria-label="Search">
                <button class="btn btn-outline-dark btn-sm ms-1 bg-green border-0" type="submit"><img src="{{ asset('assets/images/home-page/icons8-search.svg')}}" width="30"></button>
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
                        <i class="fa-solid fa-chevron-right fs-14"></i> Becoming a Buyer on WL
                    </p>
                    <!--Breadcrumbs End-->
                <div class="col-lg-12 mb-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item shadow-sm">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed text-dgreen fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
            Approving a bid
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                 <div class="accordion-body">
                    <p>At Wiserlance, we aim to ensure that you collaborate with the greatest professionals and are happy with the final result. Be assured that at Wiserlance we carefully review each applicant's profile before approving it, allowing them to post services, and making them live. But how can you track for the professional who most closely matches your needs?</p>
                    <h6 class="fw-semibold"> - Assessing a bid</h6>
                    <p class="ms-4">An ideal bid doesn't exist. But while assessing a bid, we advise you to keep the following in mind:</p>
                    <ol class="ms-3" style="list-style-type: lower-roman;">
                        <li> is the Proposal detailed enough? Always ask questions to make sure you completely grasp anything that seems unclear.</li>
                        <li> are the deliverables listed and divided into clearly defined milestones or targets?</li>
                        <li> have they given any timeframes or commitments? Are the timeframes reasonable?</li>
                        <li> Prior to submitting a bid for the open position, did they actively contact with you?</li>
                    </ol>
                    <h6 class="fw-semibold"> - Assessing professional</h6>

                    <ol class="ms-3" style="list-style-type: lower-roman;">
                        <li> to view samples of their work, visit the portfolio link on their profile page. If there aren't enough examples, ask them to add more (or have them give you additional as attachments in the WorkStream, if appropriate), and make sure they have the necessary training or qualifications.</li>
                        <li> ii.	View their profile to see the Wiserlance projects they have done and the ratings and reviews they have earned.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
            Job cancelling
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>You have two choices if you no longer wish to receive bids for your job:</p>

                    <h6 class="fw-semibold ms-3"> a-	Pausing a job </h6>
                    <p class="ms-5">This will prevent professionals from submitting more bids for your job. You may still review and respond to previous bids while your job is on pause. To get more bids, you can choose to unpause (continue) your job at any moment.</p>

                    <h6 class="fw-semibold ms-3">b-	Delete a job </h6>
                    <p class="ms-5">This will remove your job from the listings and notify all professionals who have submitted bids for this job that you have cancelled it.</p>
                    <p>You may pause or delete a job that you have posted by going to your dashboard, clicking manage job, and then performing the necessary actions.</p>
                    <p>Be aware that, only while a project is still accepting bids may it be suspended or cancelled in this manner. Look at the Payment cancellations and refunds section in the Terms and Conditions document if you have accepted a proposal on your project and you want to end the project with your freelancer.</p>

                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingThree">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
            Job posting
            </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>Would you like to post a job on Wiserlance? Post a project if you need a professional to do a specific piece of work for you and would like to receive bids from professionals.</p>
                        <h6 class="fw-semibold">Posting a job/project:</h6>
                    <p class="ms-3">Registered and verified buyers will have total control over their tasks via their dashboard, where they may create jobs and manage them as needed.</p>
                    <p class="ms-3">Enter the details of your job/project in the form. Following are some hints for filling up each field:</p>

                    <ol style="list-style:lower-latin;">
                        <li> give a thorough but clear description of the job/project, ideally outlining the precise skill set you want. You should make sure that this information stands out and draws the top freelancers with the talents you want because it will be shown in the project listings.</li>
                        <li> select the category that best describes your project so that it may be found.</li>
                        <li> the subcategory is connected to the category you'll add for your project.</li>
                        <li> dthe more information you can give professionals here about exactly what you need, timeframes, and other specifics, the better. This will help you obtain plenty of good and realistic bids and reduce the number of inquiries you receive. More specifics are always preferable! You may think of it as a project briefing paper.</li>
                    </ol>
                    <p>Your job/project is automatically reviewed once it is uploaded to ensure that it complies with Wiserlance rules before being advertised to all professionals in the job listings.</p>
                    <p>You should start receiving freelancer proposals very soon. You will get an email notice, so you can check in to your WL mailbox, read their proposals, and hopefully select your ideal professional!</p>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-collapseFour">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
            Non-communicative professional
            </button>
            </h2>
            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>In order to maintain good communication and ensure that everyone is satisfied with the way the work is going, we at WL highly advise buyers and professionals to collaborate closely via the WorkStream. What may be anticipated while working with Professionals is outlined in our Engagement Guidelines. Every professional on WL gives you, the Buyer, daily updates on their work via the WorkStream and replies to your inquiries within one business day. Sometimes a professional's lack of response is just justified, such as when they are unwell. You won't need to worry since your money will be kept secure in Escrow until you decide to give it to the professional.</p>
                    <p>Here are a few recommendations to keep everything under control:</p>

                    <ol style="list-style: lower-latin">
                        <li> Ask your professional why they aren't replying and how the project is going if you haven't already by putting a message on the WorkStream. Make it known to them that you require more frequent updates.</li>
                        <li> You could wish to discontinue the work with them and locate another professional to complete the task if they continue to be uncommunicative and you become dissatisfied. Post a message to the professional asking them to cease working in the WorkStream to inform them of the cancellation.</li>
                        <li> You could choose to ask for a refund if the agreed-upon delivery deadline has gone and you haven't gotten the job from your professional. When professionals fail to deliver (or are unresponsive), jobs are reimbursed, and they are penalized on WL.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-collapseFive">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
            Professional selection
            </button>
            </h2>
            <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-collapseFive" data-bs-parent="#accordionFiveExample">
                <div class="accordion-body">
                    <p>Our goal at Wiserlance is to establish a healthy relationship between you and your professional.</p>
                    <h6 class="fw-semibold ms-5"> -	Services</h6>
                    <p class="ms-5">If you want to get in touch with a professional while looking for services, all you have to do is click Order after choosing the service you want and viewing all of the service's details, along with view professional’s profile and contact. Before placing an order, you may contact the professional by clicking the "contact now" option if you have any questions about a service they have listed.</p>

                    <h6 class="fw-semibold ms-5"> -	Professional</h6>
                    <p class="ms-5">Visit the professional's listing page by clicking on the professional's name in our header to get in touch with them. To view a professional's profile, browse, search, and click through.</p>
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
