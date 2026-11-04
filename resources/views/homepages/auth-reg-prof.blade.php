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
                        <i class="fa-solid fa-chevron-right fs-14"></i> Authentication &amp; Registration
                    </p>
                    <!--Breadcrumbs End-->
                <div class="col-lg-12 mb-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item shadow-sm">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed text-dgreen fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Authenticate your Account
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                 <div class="accordion-body">
                    <p> Our Buyers and Professionals at Wiserlance are trustworthy. We encourage all of our users to always utilise their actual profile picture and information when
                        verifying their accounts with us. All of our users are verified through our platform, and our Payment Team makes sure that there are no bypasses or breaches
                        of our Terms and Conditions. We really hope that you would comprehend that when carrying out these checks, we keep our clients' best interests as well as Know
                        Your Client (KYC) compliance requirements in mind.</p>

                    <p> However, in order to prevent any unnecessary surprises, here is your basic starter guide:</p>

                    <ol style="list-style:lower-alpha">
                        <li> Professionals can access their dashboard (seller dashboard) by logging in with their verified login information.</li>
                        <li> Professionals can complete the following verification steps under "Withdraw Money" section:</li>
                    </ol>
                    <h5>Identification documents</h5>
                    <p>Please upload requested identification documents and click Submit.</p>
                    <p>Please attach the following papers to assist us confirm your identity and ensure the absolute protection of your information (scanned copy, photo or screenshot).</p>
                        <ol>
                            <li> a copy of a government-issued ID showing all of the information clearly
                                <ol style="list-style:lower-roman">
                                    <li> a photocopy of your current driver's license, passport, or national identity card showing your name exactly as it appears on your WL account.</li>
                                    <li> Please only submit a single file with a clear colour copy of the front and back of your identification card.</li>
                                </ol>
                            </li>
                            <li> a copy of your most recent utility bill or statement, with your name and address on it (less than 3 months old)
                                <ol>
                                    <li>i.	a copy of a most recent statement or bill (not older than three months) showing the name and address specified on your WL account Utility bill
                                        photocopy required: Electricity, gas, council tax, landline phone bill, credit card statement, or bank statement.</li>
                                    <li>ii.	Please only submit a single file with a clear colour copy of the front and back of the document.</li>
                                </ol>
                            </li>
                            <li> Please be patient while our payment team reviews your credentials. Be informed that the verification of documents may take up to 7 working days.</li>
                        </ol>

                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Professional application procedure
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>We really hope that this guide will help you understand what to anticipate throughout the application process and provide you with the knowledge you need to begin your professional career on the platform.</p>
                    <h6>Step one: Registered email verification</h6>
                    <p>Wiserlance will email you once you input your contact information to ask you to confirm your email address. It is crucial that you finish this step-in order to go on to the next since you won't be allowed to begin the application form until you have.</p>
                    <h6>Step 2: Application form </h6>
                    <p>You will be required to complete a brief application form and to provide the following information after your email has been validated.</p>
                    <ol style="list-style:lower-alpha">
                        <li> Profile image <br> It's crucial that you upload a high-quality, authentic profile photo of yourself at this phase. Illustrations, logos, cartoons, and fictitious photos of oneself are not presently accepted.<br><br>
                            <i>Note: Please be aware that images can only be uploaded up to 5MB and 4200x2500 pixels in size.</i><br><br>
                        </li>
                        <li> Professional’s Information <br>
                            In this part, you must verify or change your full name, title, residence, state, zip code, city, and country. To leave a lasting impression on the platform's buyers, it is the professional's duty to submit correct information in this part.
                        </li><br>
                        <li> Occupational information <br>
                            Professionals have the option to build their profiles on Wiserlance whether they want to provide services, work on an hourly basis, or both. If you wish to work on an hourly basis or submit a bid on a job or project that the buyers have posted, you may enter your hourly rate here. You can skip the box that asks for your per-hour rate if you simply want to offer services on Wiserlance and do not want to charge on an hourly basis.
                        </li><br>
                        <li> Languages <br>
                            What is your mother tongue? Or, if you speak more than one language, mention it here to demonstrate your proficiency!
                        </li><br>
                        <li> Educational information <br>
                            Education details can be entered in this section, along with clear pictures of transcripts, to make the profile more appealing to buyers.
                        </li><br>
                        <li> Certification information <br>
                            Certification details can be entered in this section, along with clear pictures of degree/ transcripts, to make the profile more appealing to buyers. Professionals have the option to hide it on their public profile.
                        </li><br>
                        <li> Skills and expertise  <br>
                            It's critical to list your most pertinent qualifications, which demonstrate your professionalism. Exactly what do you excel at? Which of your talents do you believe best embodies you in your position?
                        </li><br>
                        <li> Portfolio samples  <br>
                            You are permitted to post images of your current portfolio so that potential buyers may see the kind of work you have done in the past. Depending on the membership plan you have chosen to register with, the system will direct you through performing this activity.
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingThree">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
            Professional Guide
            </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                <p>Our Buyers and Professionals at Wiserlance are trustworthy. When creating a Job/Project or a Service, we encourage our users to utilize their own profile image, their genuine information, and most importantly, to be honest to themselves.</p>
                <p>Your short strategy to finding work and making money on Wiserlance as a professional is provided below!</p>
                    <ol style="list-style:upper-alpha">
                        <li> Register on Wiserlance <br> To be eligible to win employment, you must be a Wiserlance member.<br>
                        </li><br>
                        <li> Make an excellent professional profile <br>
                            It is worth putting some time on your profile so that it advertises your skills and expertise because it serves as your shopfront and is crucial to establishing the appropriate first impression with potential Buyers. Particularly crucial is your portfolio, which should include excellent examples of your prior work. By receiving endorsements from your reliable contacts, you may boost your reputation on Wiserlance.
                        </li><br>
                        <li> Post a Service <br>
                            It presents an encapsulated version of your services to draw in new Customers. To distinguish out from the competition, try to be distinctive in the services you give. Make your Service unique by conducting your research.
                        </li><br>
                        <li> Find new jobs/projects <br>
                            Visitors to Wiserlance may browse jobs and projects on the platform, but only registered and verified professionals can submit bids. When submitting a bid for a job, be professional and provide persuasive arguments to convince the potential employer to accept your bid.
                        </li><br>
                        <li>Deliver on schedule, communicate often via the WorkStream, and abide by the other Rules of Engagement while dealing with Buyers. A satisfied customer may not only refer more business, but their positive reviews will also help you rise in the ranks on WL and attract new customers
                        </li><br>
                        <li> When you have completed a specific task for a Buyer and they are happy with the work you have performed, they will raise your payment request to pay you, which includes releasing* any escrow money. Your Buyer will be able to provide you with the vital feedback once they have paid for your service.</li>
                    </ol>
                    <p>Note that only after the clearing period (as described in the Terms and Conditions)—which might take up to 14 working days to process—will money be available for withdrawal. Please be advised that national bank holidays could affect the amount of time it takes to complete orders.</p>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-1 shadow-sm">
            <h2 class="accordion-header" id="flush-headingFour">
            <button class="accordion-button collapsed text-dgreen fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                Why was my application refused
            </button>
            </h2>
            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <p>Our administration staff evaluates each application, and there are a number of requirements that must be satisfied in order to become a member of the professional’s network on Wiserlance.</p>

                    <ol style="list-style:lower-alpha">
                        <li> Unacceptable profile photo <br> Your profile photo must be authentic and of high quality for Wiserlance. As a result, prospective Buyers will perceive your profile as friendly and more welcoming. This implies that at this time, we do not accept any drawings, logos, cartoons, or fictitious images of oneself. <br>
                        </li><br>
                        <li> The application includes spam <br>
                            Wiserlance forbids any material that can be construed as "spam content." This includes any advertising or marketing from affiliates.
                        </li><br>
                        <li> Profiles of users from different freelancing markets <br>
                            You are not permitted to advertise or promote any other freelancer marketplace on your Wiserlance profile.
                        </li><br>
                        <li> Exchange of contact information <br>
                            Your profile on Wiserlance cannot include any contact information. This applies to all of your contact information, such as your phone number, home address, and email.
                        </li><br>
                        <li> Mismatched locations <br> Your application will be rejected if the location you chose doesn't match what our technology discovered or if a VPN is detected. To make sure that customers are informed of any potential time zone delays before they start working with you, do this. It's crucial that your location be accurate at all times.
                        </li><br>
                    </ol>
                    <h6>Other things to take into account</h6>
                    <p>There are occasionally several candidates with the same abilities. This indicates that there are too many freelancers with these talents on the site, outnumbering the need from clients. As a result, we might need to put a hold on applying for these talents in order to safeguard the future employment of our current professional.</p>
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
