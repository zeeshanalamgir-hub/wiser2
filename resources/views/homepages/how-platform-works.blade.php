@extends('homepages.layout.app')

@section('title', 'How Platform Works')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
@endsection

@section('content')
<div class="container-fluid px-md-5 bg-lgreen3 border-top">
    <div class="container">
    <div class="row pt-5">
        <div class="col-12 ">
            <center>
            <h1 class="text-center fw-bold mb-2 text-dgreen "> How Wiserlance Professional Platform works?</h1>
            <p class="mb-5 fs-18 text-dgreen  w-md-75">Use our safe, flexible, and cost-effective platform to
                find and hire verified, talented and professional freelancers all around the world.</p>
            <center>
        </center></center></div> <!--colmn closed-->
    </div><!--Row closed-->
</div>  </div>

<div class="container-fluid">
    <div class="row"><h2 class="text-center fw-bold textdul p-5 bg-dgreen2 text-white">For Employers</h2> </div>
    <div class="container">

    <div class="row pb-5">
                <div class="col-12">
                    <div class="pb-3 mt-4">
                        <h4 class="text-center fs-3 fw-bold"> Sign Up </h4>
                        <p class="fs-16 text-center px-5">Simply sign up for a free account, submit a job, and begin receiving quotes.
                            Wiserlance's updated search feature will help you identify talented and professional freelancers in the country you choose,
                            and you will only receive quotations from verified freelancers.</p>
                            <!--<p class="text-center"><span class="btn btn-success btn-sm bg-green fs-5 bg-green text-white  shadow-sm ">Sign Up</span> </p> -->
                    </div>
                </div>

                <div class="col-md-4 col-12 border-end border-secondary">
                    <div class="">
                        <center>
                        <p class="rounded-5 fs-5 text-white fw-bold bg-green text-center pt-1 shadow" style="width:40px;height:40px"> 1 </p>
                        <h6 class="fs-18 fw-bold text-dgreen2">   Join Wiserlance for FREE   </h6>
                        <p class="fs-16 ">Our platform verifies buyers to maintain a secure marketplace, fostering safe and reliable transactions.</p>
                        </center>
                    </div>
                </div>
                 <div class="col-md-4 col-12 border-end border-secondary">
                    <div class="">
                        <center>
                        <p class="rounded-5 fs-5 fw-bold bg-green text-white text-center pt-1 shadow" style="width:40px;height:40px"> 2 </p>
                        <h6 class="text-dgreen2 fs-18 fw-bold"> Escrow Secure Payment </h6>
                        <p class="fs-16 ">Protect your funds with our escrow system, releasing payments only after reviewing completed work.</p>
                        </center>
                    </div>
                </div>
                 <div class="col-md-4 col-12">
                    <div class="">
                        <center>
                        <p class="rounded-5 fs-5 fw-bold bg-green text-white text-center pt-1 shadow" style="width:40px;height:40px"> 3 </p>
                        <h6 class="text-dgreen2 fs-18 fw-bold"> Advanced Job Skills Match </h6>
                        <p class="fs-16">Our cutting-edge algorithms match your job with the perfect talent, ensuring exceptional project outcomes.</p>
                        </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid fs-14 mt-4">
    <div class="container">
        <div class="row gx-3">
         <div class="col-md-4 h-100">
            <div class="p-3 border-dgreen1 rounded shadow-sm">
            <div class="d-flex flex-row border-dgreen1 bg-dgreen2 rounded-3 p-3 h-130">
                <i class="fa-solid fa-user-tag rounded-5 border-dgreen1 p-2 text-dgreen2 mt-1 fs-3 me-3" style="height:50px; background-color:#fff"></i>
                <div>
                <h5 class="fw-bold text-light"> HIRING</h5>
                <p class="text-light">Examine, compare, and choose the finest freelancers for your project</p>
                </div>
            </div>

            <div class="d-flex flex-row mt-4 ">
                <p class="rounded-5 mb-0 bg-dgreen2 fs-6 fw-bold text-white text-center me-2" style="width:25px;height:25px"> 1 </p>
                <h6 class="text-dgreen2 fw-bold">Analyse bids</h6>
            </div>
            <p style="margin-left:35px"> Examine the Freelancers' profiles for performance, comments, portfolio, and earnings information as you go through the Quotes you've received.</p>

            <div class="d-flex flex-row mt-4">
                <p class="rounded-5 mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center me-2" style="width:25px;height:25px"> 2 </p>
                <h6 class="text-dgreen2 fw-bold">Optimised Hiring with Advanced Skill-Matching Technology</h6>
            </div>
            <p style="margin-left:25px"> We have streamlined the hiring process by developing advanced algorithms that accurately match candidate skills to your job requirements. This technology enables you to efficiently shortlist candidates and extend offers to those who best align with your needs. </p>

                <div class="d-flex flex-row mt-3">
                <p class="rounded-5 mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center me-2" style="width:25px;height:25px"> 3 </p>
                <h6 class="text-dgreen2 fw-bold">Complete the contract</h6>
            </div>
            <p style="margin-left:35px"> Choose the Professional you wish to employ and agree on the scope of work, payment conditions, and timetable. Remember that Wiserlance will only cover you if you have a contract in place and all communication took place through the <strong>Wiserlance</strong> platform.</p>

            <div class="d-flex flex-row mt-3">
                <p class="rounded-5 mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center me-2" style="width:25px;height:25px"> 4 </p>
                <h6 class="text-dgreen2 fw-bold">Hire a professional </h6>
            </div>
            <p style="margin-left:35px"> Examine the Freelancers' profiles for performance, comments, portfolio, and earnings information as you go through the Quotes you've received.</p>
         </div>
        </div>

         <!------Management------>

         <div class="col-md-4 ">
            <div class="h-100 p-3 border-dgreen1 rounded shadow-sm">
            <div class="d-flex flex-row  bg-dgreen2 rounded-3 p-3 h-130">
                <i class="fa-solid fa-user-gear shadow-sm rounded-5 border  p-2 text-dgreen2 mt-1 fs-3 me-3" style="height:50px; background:#fff;"></i>
                <div>
                    <h5 class="fw-bold text-light"> MANAGEMENT</h5>
                    <p class="text-light"> Get work done without stress</p>
                </div>
            </div>

            <div class="d-flex flex-row mt-4">
                <p class="rounded-5 mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center  me-2" style="width:25px;height:25px"> 1 </p>
                <h6 class="text-dgreen2 fw-bold">Track progress of jobs</h6>
            </div>
            <p style="margin-left:35px;"> Hourly freelancers can use status updates to keep track of their job progress and time.</p>

            <div class="d-flex flex-row mt-4">
                <p class="rounded-5 mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center me-2" style="width:25px;height:25px"> 2 </p>
                <h6 class="text-dgreen2 fw-bold">Boost productivity</h6>
            </div>
            <p style="margin-left:35px;">In one location, you can manage files, freelancers, communication, and payments.</p>
         </div>
        </div>

         <!------Payment------>

         <div class="col-md-4">
            <div class="h-100 p-3 border-dgreen1 rounded shadow-sm ">
            <div class="d-flex flex-row bg-dgreen2 border-dgreen1 shadow-sm rounded-3 p-3 h-130">
                <i class="fa-solid fa-hand-holding-dollar shadow-sm rounded-5 border border-dgreen1 p-2 text-dgreen2 mt-1 fs-3 me-3" style="height:50px; background:#fff;"></i>
                <div>
                    <h5 class="text-light fw-bold"> PAYMENT</h5>
                    <p class="text-light"> Take charge of your engagements by gaining access to a dedicated dashboard where you can keep track your transactions.</p>
                </div>
            </div>

            <div class="d-flex flex-row mt-4">
                <p class="rounded-circle mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center  me-2" style="width:25px;height:25px"> 1 </p>
                <h6 class="text-dgreen2 fw-bold">Secure Payment Process with Escrow Protection</h6>
            </div>
            <p style="margin-left:35px"> Deposit funds with confidence through our secure, third-party escrow account tailored specifically for your needs. This system allows you to demonstrate your commitment to completing your project while ensuring funds are safely held and released only upon fulfillment of contractual terms.</p>

            <div class="d-flex flex-row mt-4">
                <p class="rounded-circle mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center  me-2" style="width:25px;height:25px"> 2 </p>
                <h6 class="text-dgreen2 fw-bold"> Make a deposit using your preferred mode of payment.</h6>
            </div>
            <p style="margin-left:35px"> We provide a variety of easy payment methods; choose the one that best suits your needs. </p>

            <div class="d-flex flex-row mt-3">
                <p class="rounded-5 mb-0 fs-6 fw-bold bg-dgreen2 text-white text-center  me-2" style="width:25px;height:25px"> 3 </p>
                <h6 class="text-dgreen2 fw-bold"> Authourisaton of payment</h6>
            </div>
            <p style="margin-left:35px"> Payment authorization is at your fingertips; authorize it just once you're completely happy.</p>
         </div>
         </div>
        </div>
    </div>
</div>

<div class="container-fluid bg-linear bg-white mt-5">
    <div class="container">
    <div class="row py-5" style="row-gap:10px">
        <div class="col-md-3 col-6 text-dgreen text-center ">
            <div class="p-3 rounded shadow border bg-white  h-100"><h6>Post projects for free.</h6></div>
        </div>
        <div class="col-md-3 col-6 text-dgreen text-center ">
            <div class="p-3 rounded shadow border bg-white h-100"><h6>Browse profiles, reviews, then hire your favorite and started your project.</h6></div>
        </div>
            <div class="col-md-3 col-6 text-dgreen text-center ">
            <div class="p-3 rounded shadow border bg-white  h-100"><h6>Chat and share files using the BizlanceGuru platform.</h6></div>
        </div>
            <div class="col-md-3 col-6 text-dgreen text-center ">
            <div class="p-3 rounded shadow border bg-white h-100"><h6>We protect your money by only paying for work you authorise</h6></div>
        </div>
    </div>
    <h4 class="text-white text-center mb-0 pb-4">We guarantee the lowest charges in the freelancing market
        @auth
            @if(Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1')
                <a href="{{ route('user.job.create') }}" class="btn btn-warning ms-3">Post A Job Free</a>
            @else
                <a href="{{ url('/login') }}" class="btn btn-warning ms-3">Post A Job Free</a>
            @endif
        @else
            <a href="{{ url('/login') }}" class="btn btn-warning ms-3">Post A Job Free</a>
        @endauth
    </h4>
    </div>
    </div>

    <div class="container-fluid bg-lgreen3 mt-5 ">
    <div class="row"><h2 class="text-center fw-bold textdul p-5 bg-dgreen text-white"> For Freelancers</h2> </div>
    <div class="container">
    <div class="row px-2 px-sm-5 ">
        <div class="col-12 ">
            <div class="p-4 border-success">

                <h4 class="text-dgreen fw-bold fs-3">  Sign Up </h4>
                <p class="text-dgreen">Join the fastest-growing freelance marketplace by creating a Freelancer profile.</p>

                <p class="text-dgreen"><b>1- Join Wiserlance for FEE</b>
                 <span class="ms-2">Sign up with Wiserlance for free and validate your identity and credentials.</span>
                </p>
                <p class="text-dgreen"><b>2- Send in your bid</b>
                 <span class="ms-2"> Accept the terms and conditions, and your created professional profile will take care of the rest, demonstrating your credentials and abilities to effectively execute the work.</span>
                </p>

            </div>
        </div>

        <div class="col-lg-12">
            <div class="p-4 card rounded-4 shadow-sm border-dgreen1">
                <h4 class="text-dgreen2  fw-bold my-3">
                <i class="fa-solid fa-magnifying-glass fa-xl text-green me-3 "></i> Find the job you like</h4>
                <p class="text-dgreen fs-6">Wiserlance makes it simple to find and apply for jobs that are a good fit for your abilities and experience</p>

                <p class="text-dgreen"><b>1- Check out the latest job openings.</b>
                 <span class="ms-2">Browse the online job listings and apply for the position that best suits you.</span>
                </p>
                <p class="text-dgreen"><b>2- Create a professional profile</b>
                 <span class="ms-2">Explain the services you provide, demonstrate&nbsp;your portfolios, and add any other facts that
                    may help your profile stand out. Remember to Create a good profile, get your ID verified to attract potential
                     employers and increase your chances of being hired.</span>
                </p>
                <p class="text-dgreen"><b>3- Be Hired</b>
                 <span class="ms-2"> Review the Proposal with the employer in details before finalising the Contract to begin work.
                    Note that, it is imperative that you establish a clear understanding of the engagement and provide a precise quotation to the employer to avoid and issues down the line.</span>
                </p>
            </div>
        </div>
        <div class="col-lg-12  mt-4">
            <div class="p-4 card rounded-4 shadow-sm border-dgreen1">
                <h4 class="text-dgreen2  fw-bold my-3">
                <i class="fa-solid fa-gear fa-xl text-green me-3 "></i> Management</h4>
                <p class="text-dgreen fs-6">Get work done without stress</p>

                <p class="text-dgreen"><b>1- Track progress of jobs</b>
                 <span class="ms-2"> If you are hired for hourly rate work, make sure you report promptly to the potential employer. If will help both parties to avoid any misunderstanding.</span>
                </p>
                <p class="text-dgreen"><b>2- Boost productivity </b>
                 <span class="ms-2"> In one location, you can manage files, employers, communication, and payments.</span>
                </p>
            </div>
        </div>
        <div class="col-lg-12  mt-4">
            <div class="p-4 card rounded-4 shadow-sm border-dgreen1">
                <h4 class="text-dgreen2  fw-bold my-3">
                <i class="fa-solid fa-hand-holding-dollar fa-xl text-green me-3 "></i> Payment</h4>
                <p class="text-dgreen fs-6">Take charge of your engagements by gaining access to a dedicated dashboard where you can keep track your transactions.</p>

                <p class="text-dgreen"><b>1- Secure Payment Process with Escrow Protection</b>
                 <span class="ms-2">Receive payments with confidence through our secure, third-party escrow account designed to meet your needs. This system ensures that
                    funds are deposited by the buyer to demonstrate their commitment to the project, securely held, and released to you upon successful completion of the contractual terms.</span>
                </p>
                <p class="text-dgreen"><b>2- Job funding</b>
                 <span class="ms-2"> Wiserlance requires buyers to deposit funds into a secure escrow account prior to responding to your submitted bids. This process enhances your confidence and
                    ensures the employer's commitment to hiring you for their posted jobs.</span>
                </p>
                <p class="text-dgreen"><b>3- Withdraw money using your preferred mode of payment.</b>
                 <span class="ms-2"> We provide a variety of easy payment methods; choose the one that best suits your needs.</span>
                </p>
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
            <a class="btn  rounded-5 bg-dgreen2 btn-success" href="/job">Find a Job</a>
            @auth
                @if(Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1')
                    <a href="{{ route('user.job.create') }}" class="btn border-0 rounded-5 bg-green btn-success ms-3">Post a Job</a>
                @endif
            @else
                <a href="{{ url('/login') }}" class="btn border-0 rounded-5 bg-green btn-success ms-3">Post a Job</a>
            @endauth
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
<script src="{{ asset('js/new_footer.js') }}"></script>
@endsection
