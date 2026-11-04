@extends('homepages.layout.app')

@section('title', 'About Us')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
    <style>
        /* Responsive About Page Styles */
        @media (max-width: 1200px) {
            .container-fluid.px-md-5 {
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }

        @media (max-width: 992px) {
            /* Mission/Vision/Promise cards */
            .bg-dgreen2 .row {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .bg-dgreen2 .col-4 {
                width: 100%;
                flex: 0 0 auto;
            }

            /* Core Values grid */
            .bg-lgreen3 .col-lg-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            /* How We Help sections */
            .d-flex.ms-5.ps-5 {
                margin-left: 2rem !important;
                padding-left: 2rem !important;
            }

            .d-flex.ms-5.ps-5 p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            /* Header section */
            .container .row.pt-5 h1 {
                font-size: 2rem;
            }

            .container .row.pt-5 h3 {
                font-size: 1.5rem;
                width: 100% !important;
            }

            .container .row.pt-5 p {
                width: 100% !important;
                padding: 0 1rem;
            }

            .container .row.pt-5 .btn {
                margin-bottom: 0.5rem;
            }

            .container .row.pt-5 .ms-3 {
                margin-left: 0 !important;
            }

            /* Mission/Vision/Promise section */
            .bg-dgreen2 .row {
                grid-template-columns: 1fr;
                padding: 2rem 1rem !important;
            }

            .bg-dgreen2 .card {
                margin: 0.5rem 0;
            }

            .bg-dgreen2 .card h4 {
                font-size: 1.25rem;
            }

            /* Core Values section */
            .bg-lgreen3 .row.p-5 {
                padding: 2rem 1rem !important;
            }

            .bg-lgreen3 .col-lg-3 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-top: 1rem;
            }

            .bg-lgreen3 .card {
                padding: 1.5rem !important;
            }

            .bg-lgreen3 .card h6 {
                font-size: 1.1rem;
            }

            /* How We Help section */
            .container .row.py-5.ps-5 {
                padding: 2rem 1rem !important;
                margin-left: 0 !important;
            }

            .container .row.py-5.ps-5 h5 {
                padding: 0 1rem !important;
                font-size: 1rem;
            }

            .d-flex.ms-5.ps-5 {
                flex-direction: column;
                align-items: center !important;
                text-align: center;
                margin-left: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .d-flex.ms-5.ps-5 i {
                margin-bottom: 0.5rem;
            }

            .d-flex.ms-5.ps-5 h6 {
                margin: 0.5rem 0 !important;
            }

            .d-flex.ms-5.ps-5 p {
                margin: 0.5rem 0 0 0 !important;
            }

            /* Get Started section */
            .bg-lgreen3 .row.py-5 {
                padding: 2rem 1rem !important;
            }

            .bg-lgreen3 .row.py-5 h2 {
                font-size: 1.75rem;
            }

            .bg-lgreen3 .row.py-5 p {
                padding: 0 1rem;
            }

            .bg-lgreen3 .row.py-5 .btn {
                display: block;
                width: 80%;
                margin: 0.5rem auto !important;
            }

            .bg-lgreen3 .row.py-5 .ms-3 {
                margin-left: auto !important;
            }
        }

        @media (max-width: 576px) {
            /* Extra small devices */
            .container-fluid.px-md-5 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .container .row.pt-5 h1 {
                font-size: 1.75rem;
            }

            .container .row.pt-5 h3 {
                font-size: 1.25rem;
            }

            .container .row.pt-5 p {
                font-size: 0.9rem;
                padding: 0 0.5rem;
            }

            .container .row.pt-5 button {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .bg-dgreen2 .card {
                padding: 1rem !important;
            }

            .bg-dgreen2 .card h4 {
                font-size: 1.1rem;
                margin-left: 0 !important;
            }

            .bg-dgreen2 .card p {
                font-size: 0.9rem;
                margin-left: 0 !important;
            }

            .bg-lgreen3 .card {
                padding: 1rem !important;
            }

            .bg-lgreen3 .card i {
                width: 50px;
                height: 50px;
                font-size: 1.5rem !important;
                padding-top: 0.6rem !important;
            }

            .bg-lgreen3 .card h6 {
                font-size: 1rem;
            }

            .bg-lgreen3 .card p {
                font-size: 0.85rem;
            }

            .d-flex.ms-5.ps-5 i {
                width: 50px;
                height: 50px;
                font-size: 1.5rem !important;
                padding-top: 0.6rem !important;
            }

            .d-flex.ms-5.ps-5 h6 {
                font-size: 1rem;
            }

            .d-flex.ms-5.ps-5 p {
                font-size: 0.85rem;
            }

            .bg-lgreen3 .row.py-5 h2 {
                font-size: 1.5rem;
            }

            .bg-lgreen3 .row.py-5 .btn {
                width: 90%;
                padding: 0.6rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 375px) {
            /* Very small phones */
            .container .row.pt-5 h1 {
                font-size: 1.5rem;
            }

            .container .row.pt-5 h3 {
                font-size: 1.1rem;
            }

            .bg-dgreen2 .card h4 {
                font-size: 1rem;
            }

            .bg-dgreen2 .card h4 i {
                font-size: 1.1rem !important;
            }

            .bg-lgreen3 .card i {
                width: 45px;
                height: 45px;
                font-size: 1.25rem !important;
                padding-top: 0.5rem !important;
            }

            .bg-lgreen3 .card h6 {
                font-size: 0.95rem;
            }

            .btn {
                font-size: 0.85rem;
            }
        }

        /* Fix for center alignment issues */
        @media (max-width: 768px) {
            center {
                width: 100%;
            }

            .text-center.w-md-75 {
                width: 100% !important;
            }

            .container .row.pt-5 center {
                padding: 0 0.5rem;
            }
        }

        /* Improve card spacing on mobile */
        @media (max-width: 992px) {
            .bg-lgreen3 .row {
                margin: 0;
            }

            .bg-lgreen3 .col-lg-3 {
                padding: 0 0.5rem;
            }
        }

        /* Fix button groups on mobile */
        @media (max-width: 576px) {
            .container .row.pt-5 center .btn {
                display: block;
                width: 200px;
                margin: 0.5rem auto !important;
            }

            .container .row.pt-5 center .ms-3 {
                margin-left: auto !important;
            }
        }

        /* Improve readability */
        @media (max-width: 768px) {
            p {
                line-height: 1.6;
            }

            .card p {
                line-height: 1.5;
            }
        }

        /* Fix icon alignment on mobile */
        @media (max-width: 576px) {
            .bg-lgreen3 .card center i,
            .d-flex.ms-5.ps-5 i {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Ensure cards don't overflow */
        .card {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Smooth transitions */
        .card, .btn, i {
            transition: all 0.3s ease;
        }

        /* Better touch targets for mobile */
        @media (max-width: 768px) {
            .btn {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .card {
                cursor: pointer;
            }
        }

        /* Landscape mode optimization */
        @media (max-width: 896px) and (orientation: landscape) {
            .bg-dgreen2 .row {
                grid-template-columns: repeat(3, 1fr);
            }

            .container .row.pt-5 {
                padding-top: 2rem !important;
            }

            .container .row.pt-5 p {
                max-height: 80px;
                overflow-y: auto;
            }
        }

        /* Fix for Bootstrap grid on mobile */
        @media (max-width: 768px) {
            .row {
                --bs-gutter-x: 1rem;
            }

            .col-4, .col-lg-3 {
                padding-right: calc(var(--bs-gutter-x) * .5);
                padding-left: calc(var(--bs-gutter-x) * .5);
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid pb-4 px-md-5 bg-lgreen3">
    <div class="container">
        <div class="row pt-5">
            <div class="col-12">
                <div class="text-center text-md-start text-lg-center">
                    <h1 class="fw-bold mb-2 text-dgreen display-4 display-md-3">About Us</h1>
                    <h3 class="mb-3 text-dgreen2 mx-auto" style="max-width: 800px;">We're redefining how the world works</h3>

                    <div class="mx-auto" style="max-width: 1000px;">
                        <p class="px-2 px-md-4" style="text-align:justify">WiserLance.com isn't just a platform—it's our vision in action. We don't just bring freelancers and employers together; we craft a space where collaboration sparks innovation, ideas flourish, and work becomes more than a task—it becomes an experience. Here, connections are meaningful, opportunities are boundless, and everything we do reflects our passion for doing things differently.</p>
                        <p class="px-2 px-md-4" style="text-align:justify">We aim to be the leading platform where professionals come together to connect, collaborate, and achieve great results. We believe the best work happens in an environment that is both flexible and secure. Built on transparency and merit, our community empowers people to look beyond geography, politics, and beliefs—focusing instead on sharing ideas, seizing opportunities, and succeeding together.</p>
                        <p>Join <strong>wiserlance.com</strong> today—where your skills meet the world, and your ideas find their stage.</p>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 gap-sm-3 my-4">
                        <a href="{{ url('/signup') }}" class="btn btn-success bg-green px-4 py-2">Join Us</a>
                        {{-- <button class="btn btn-outline-dark px-4 py-2">Learn More</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid bg-dgreen2">
    <div class="container">
        <div class="row py-5 g-3 g-md-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 p-3 pb-2 rounded-4 shadow border-top border-dark border-4">
                    <h4 class="text-dgreen mt-2 ms-2">
                        <i class="fa-regular me-2 fa-lg text-green fa-font-awesome"></i> Our Mission
                    </h4>
                    <p class="fw-normal mt-2 ms-2 ms-sm-3">A borderless world where skills, ideas, and ambition drive meaningful collaboration and shared success.</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card h-100 p-3 pb-2 rounded-4 shadow border-top border-dark border-4">
                    <h4 class="text-dgreen mt-2 ms-2">
                        <i class="fa-regular me-2 fa-lg text-warning fa-eye"></i> Our Vision
                    </h4>
                    <p class="fw-normal mt-2 ms-2 ms-sm-3">Security, transparency, and growth — we're committed to making freelancing a sustainable career choice for all.</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card h-100 p-3 pb-2 rounded-4 shadow border-top border-dark border-4">
                    <h4 class="text-dgreen mt-2 ms-2">
                        <i class="fa-regular me-2 fa-lg text-info fa-handshake"></i> Our Promise
                    </h4>
                    <p class="fw-normal mt-2 ms-2 ms-sm-3">Our platform verifies buyers to maintain a secure marketplace, fostering safe and reliable transactions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid bg-lgreen3">
    <div class="container">
        <div class="row py-5 px-2 px-sm-4 px-md-5 g-4">
            <h2 class="text-dgreen fs-1 text-center fw-bold pb-4">Our Core Values</h2>

            <div class="col-lg-3 col-md-6">
                <div class="card border-dgreen2 rounded-4 p-3 p-md-4 shadow h-100">
                    <div class="text-center">
                        <i class="fa-regular fa-handshake bg-warning bg-opacity-50 border border-secondary border-2 text-dark rounded-5 fs-3 text-center shadow d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px"></i>
                        <h6 class="text-dgreen2 fs-18 fw-bold my-3">Trust &amp; Transparency</h6>
                        <p class="fs-14 mb-0">We ensure clear terms, secure payments, and fair recognition for every professional.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-dgreen2 rounded-4 p-3 p-md-4 shadow h-100">
                    <div class="text-center">
                        <i class="fa-solid fa-draw-polygon bg-info bg-opacity-50 rounded-5 border border-secondary border-2 fs-3 text-center shadow d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px"></i>
                        <h6 class="text-dgreen2 fs-18 fw-bold my-3">Flexibility</h6>
                        <p class="fs-14 mb-0">Work how, when, and where you want — full‑time, part‑time, or project‑based.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-dgreen2 rounded-4 p-3 p-md-4 shadow h-100">
                    <div class="text-center">
                        <i class="fa-solid fa-award bg-primary bg-opacity-50 rounded-5 border border-secondary border-2 fs-3 text-center shadow d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px"></i>
                        <h6 class="text-dgreen2 fs-18 fw-bold my-3">Quality &amp; Skill</h6>
                        <p class="fs-14 mb-0">We celebrate craftsmanship, consistent results, and continuous learning.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-dgreen2 rounded-4 p-3 p-md-4 shadow h-100">
                    <div class="text-center">
                        <i class="fa-solid fa-globe bg-danger bg-opacity-50 rounded-5 border border-secondary border-2 fs-3 text-center shadow d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px"></i>
                        <h6 class="text-dgreen2 fs-18 fw-bold my-3">Global Opportunity</h6>
                        <p class="fs-14 mb-0">Our network transcends borders, connecting creators and companies worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="container">
        <div class="row py-5 px-3 px-sm-4 px-md-5">
            <div class="col-12 text-center">
                <h2 class="text-dgreen2 fs-1 fw-bold">How We Help You Thrive</h2>
                <h5 class="text-center text-secondary px-2 px-md-5 mt-3">From discovery to delivery, we simplify every step — empowering freelancers and businesses with intelligent tools, seamless payments, and collaboration that drives results.</h5>

                <div class="d-flex flex-column flex-md-row align-items-start bg-light border-dgreen2 p-3 rounded-4 mt-4">
                    <div class="d-flex align-items-center justify-content-center mb-3 mb-md-0">
                        <i class="fa-regular fa-lightbulb rounded-5 border text-warning border-success border-2 fs-3 text-center shadow d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px; flex-shrink:0;"></i>
                    </div>
                    <div class="ms-0 ms-md-3 text-center text-md-start">
                        <h6 class="text-dgreen2 fs-18 fw-bold mb-2">Discover</h6>
                        <p class="mb-0">AI‑powered matching, curated talent pools, and advanced search to find the right fit every time.</p>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-start bg-light border-dgreen2 p-3 rounded-4 mt-3">
                    <div class="d-flex align-items-center justify-content-center mb-3 mb-md-0">
                        <i class="fa-regular fa-credit-card rounded-5 border text-dgreen2 border-success border-2 fs-3 text-center shadow d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px; flex-shrink:0;"></i>
                    </div>
                    <div class="ms-0 ms-md-3 text-center text-md-start">
                        <h6 class="text-dgreen2 fs-18 fw-bold mb-2">Secure Payments</h6>
                        <p class="mb-0">Escrow protection, milestone payouts, and instant withdrawals ensure peace of mind for everyone.</p>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-start bg-light border-dgreen2 p-3 rounded-4 mt-3">
                    <div class="d-flex align-items-center justify-content-center mb-3 mb-md-0">
                        <i class="fa-regular fa-comment-dots rounded-5 border text-green border-success border-2 fs-3 text-center shadow d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px; flex-shrink:0;"></i>
                    </div>
                    <div class="ms-0 ms-md-3 text-center text-md-start">
                        <h6 class="text-dgreen2 fs-18 fw-bold mb-2">Collaborate</h6>
                        <p class="mb-0">Built‑in chat, task tracking, and real‑time feedback keep your projects on time and on point.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid bg-lgreen3">
    <div class="container">
        <div class="row py-5 px-3 px-sm-4">
            <div class="col-12 text-center">
                <h2 class="text-dgreen fs-1 fw-bold">Ready to get started?</h2>
                <p class="text-center mt-2">Join thousands of employers and freelancers already thriving with jobs and services on our platform.</p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 gap-sm-3 mt-4">
                    <a class="btn rounded-5 bg-dgreen2 btn-success px-4 py-2" href="/job">Find a Job</a>

                    @auth
                        @if(Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1')
                            <a href="{{ route('user.job.create') }}" class="btn rounded-5 bg-green btn-success px-4 py-2">Post a Job</a>
                        @else
                            <a href='/login' class="btn rounded-5 bg-green btn-success px-4 py-2">Post a Job</a>
                        @endif
                    @else
                        <a href="{{ url('/login') }}" class="btn rounded-5 bg-green btn-success px-4 py-2">Post a Job</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showLoader();

        setTimeout(hideLoader, 5000); // Hide loader after 5 seconds

        // Carousel functionality (if exists on the page)
        const prevButton = document.querySelector('.nav-button.prev');
        const nextButton = document.querySelector('.nav-button.next');
        const track = document.querySelector('.track');
        const carousel = document.querySelector('.carousel-container');

        if (prevButton && nextButton && track && carousel) {
            const cards = document.querySelectorAll('.card-container');
            const indicators = document.querySelectorAll('.indicator');

            if (cards.length > 0) {
                let index = 0;
                const cardWidth = cards[0].offsetWidth + parseInt(getComputedStyle(track).gap || 20);
                const visibleCards = Math.floor(carousel.offsetWidth / cardWidth);
                const maxIndex = cards.length - visibleCards;

                function updateButtonStates() {
                    prevButton.classList.toggle('disabled', index === 0);
                    nextButton.classList.toggle('disabled', index >= maxIndex);
                }

                function updateCarousel() {
                    track.style.transform = `translateX(${-index * cardWidth}px)`;
                    updateButtonStates();

                    if (indicators.length > 0) {
                        const activeIndicator = Math.floor(index / Math.ceil(maxIndex / indicators.length));
                        indicators.forEach((indicator, i) => {
                            indicator.classList.toggle('active', i === activeIndicator);
                        });
                    }
                }

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

                indicators.forEach((indicator, i) => {
                    indicator.addEventListener('click', function() {
                        index = i * Math.ceil(maxIndex / indicators.length);
                        updateCarousel();
                    });
                });

                cards.forEach(card => {
                    card.addEventListener('click', function() {
                        cards.forEach(c => {
                            const cardC = c.querySelector('.card-c');
                            if (cardC) cardC.classList.remove('active');
                        });
                        const cardC = this.querySelector('.card-c');
                        if (cardC) cardC.classList.add('active');
                    });
                });

                window.addEventListener('resize', function() {
                    const newVisibleCards = Math.floor(carousel.offsetWidth / cardWidth);
                    const newMaxIndex = cards.length - newVisibleCards;

                    if (index > newMaxIndex) {
                        index = newMaxIndex;
                    }

                    updateCarousel();
                });

                updateButtonStates();
            }
        }

        // Add smooth scrolling to all links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll for cards
        const cards = document.querySelectorAll('.card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    });
</script>
@endsection
