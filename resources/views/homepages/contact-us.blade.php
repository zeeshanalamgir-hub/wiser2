@extends('homepages.layout.app')

@section('title', 'Contact Us')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
    <style>
        /* Additional responsive styles */
        @media (max-width: 768px) {
            .contact-form-section,
            .contact-info-section {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .accordion-button {
                font-size: 14px;
                padding: 12px;
            }

            .accordion-body {
                font-size: 14px;
                padding: 12px;
            }

            .fs-16 {
                font-size: 14px !important;
            }

            h1.fs-3 {
                font-size: 1.5rem !important;
            }

            h6 {
                font-size: 0.9rem;
            }

            .btn-success {
                width: 100%;
                margin-bottom: 10px;
            }

            .contact-info-item {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 576px) {
            .card {
                padding: 1rem !important;
            }

            h4 {
                font-size: 1.2rem;
            }

            .fa-xl {
                font-size: 1.5rem !important;
            }

            .ask-now-section h5 {
                font-size: 1rem;
                text-align: center;
            }

            .ask-now-section .btn {
                display: block;
                margin: 10px auto 0 !important;
                width: fit-content;
            }
        }

        /* Fix for form inputs on mobile */
        .form-control {
            font-size: 16px !important; /* Prevents zoom on iOS */
        }

        /* Improve touch targets on mobile */
        .accordion-button,
        .btn,
        .links-dg {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
        }

        /* Better spacing for contact info */
        .contact-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .contact-info-item i {
            min-width: 24px;
            margin-top: 3px;
        }

        /* Responsive accordion */
        .accordion-item {
            margin-bottom: 8px;
        }

        /* Fix for breadcrumbs on mobile */
        .breadcrumb {
            flex-wrap: wrap;
        }

        /* Responsive grid fixes */
        .row {
            margin-left: 0;
            margin-right: 0;
        }

        [class*="col-"] {
            padding-left: 12px;
            padding-right: 12px;
        }

        /* Better card shadows on mobile */
        .card {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        }

        /* Responsive typography */
        @media (max-width: 480px) {
            h1.fs-3 {
                font-size: 1.3rem !important;
            }

            .text-dgreen2 {
                font-size: 1.1rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="container">
        <div class="row px-2 p-lg-3">
            <div class="col-12 mb-4">
                <h1 class="fs-3 fw-bold text-dgreen2 me-3">Contact Us</h1>
                <h6 class="text-muted">Have a question, need help, or want to talk business? Send us a message and our team will respond shortly.</h6>
            </div>

            <div class="row g-4">
                <!-- Contact Form Column -->
                <div class="col-lg-7 col-md-12 contact-form-section">
                    <div class="card h-100 p-3 p-md-4 shadow border-dgreen1">
                        <h4 class="text-dgreen2 mb-4">Contact Form</h4>
                        <form method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fullname" class="form-label fs-16 fw-medium">Full name</label>
                                    <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Your Name" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fs-16 fw-medium">Email address</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="company" class="form-label fs-16 fw-medium">Company (optional)</label>
                                    <input type="text" name="company" class="form-control" id="company" placeholder="Company">
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label fs-16 fw-medium">Subject</label>
                                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Short Summary" required>
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label fs-16 fw-medium">Message</label>
                                    <textarea name="message" class="form-control" id="message" rows="4" placeholder="Tell us what's on your mind..." required></textarea>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-success bg-green px-4 py-2">Send Message</button>
                                </div>

                                @if(session('success'))
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12 mt-3">
                                    <p class="mb-0">Prefer email? Write to <a href="mailto:support@wiserlance.com" class="links-dg">support@wiserlance.com</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Info Column -->
                <div class="col-lg-5 col-md-12 contact-info-section">
                    <!-- Get in Touch Card -->
                    <div class="card mb-4 p-3 p-md-4 shadow border-dgreen1">
                        <h4 class="text-dgreen2 mb-3">Get in touch</h4>
                        <p class="text-muted small">Our support team is available Monday — Friday, 9:00 — 18:00 (UTC).</p>

                        <div class="contact-info-item">
                            <i class="far fa-envelope text-green fa-xl"></i>
                            <a class="links-dg" href="mailto:support@wiserlance.com">support@wiserlance.com</a>
                        </div>

                        <div class="contact-info-item">
                            <i class="fas fa-phone-volume text-green fa-xl"></i>
                            <a class="links-dg" href="tel:+353863783948">+353 86 3783 948</a>
                        </div>

                        <div class="contact-info-item">
                            <i class="fas fa-map-marker-alt text-green fa-xl"></i>
                            <div>
                                D24 NRA0<br>
                                Dublin, Ireland
                            </div>
                        </div>
                    </div>

                    <!-- Quick Answers Card -->
                    <div class="card p-3 p-md-4 shadow border-dgreen1">
                        <h4 class="text-dgreen2 mb-3">Quick answers</h4>

                        <div class="accordion accordion-flush" id="quickAnswersAccordion">
                            <!-- FAQ Item 1 -->
                            <div class="accordion-item border mb-2 rounded">
                                <h6 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed bg-lgreen3 text-dgreen" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        How long until I get a reply?
                                    </button>
                                </h6>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#quickAnswersAccordion">
                                    <div class="accordion-body">
                                        <p class="mb-0">We typically respond within 24 hours on business days.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ Item 2 -->
                            <div class="accordion-item border mb-2 rounded">
                                <h6 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed bg-lgreen3 text-dgreen" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Where can I report a payment issue?
                                    </button>
                                </h6>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#quickAnswersAccordion">
                                    <div class="accordion-body">
                                        <p class="mb-0">Email our billing team at <a class="links-dg fw-bold" href="mailto:support@wiserlance.com">support@wiserlance.com</a></p>
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ Item 3 -->
                            <div class="accordion-item border rounded">
                                <h6 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed bg-lgreen3 text-dgreen" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Can I request a demo?
                                    </button>
                                </h6>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#quickAnswersAccordion">
                                    <div class="accordion-body">
                                        <p class="mb-0">Yes — contact sales at <a class="links-dg fw-bold" href="mailto:support@wiserlance.com">support@wiserlance.com</a> and we'll schedule a walkthrough.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="bg-lgreen2 py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-md-start">
                <h5 class="mb-3 mb-md-0 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start">
                    <i class="me-md-2">Do you have a question? Please send us your query, and we'll be happy to assist you.</i>
                    <a href="{{route('contact.us')}}" class="btn btn-success bg-dgreen ms-md-3 fs-5 mt-2 mt-md-0 px-4 py-2">
                        <i class="fa-solid fa-chevron-right text-green me-2" aria-hidden="true"></i>Ask Now
                    </a>
                </h5>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
         showLoader();

        setTimeout(hideLoader, 5000);
        // Handle alert dismissal
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }, 5000); // Auto dismiss after 5 seconds
        });

        // Smooth scroll to contact form if there are validation errors
        if (document.querySelector('.alert-danger')) {
            document.querySelector('.contact-form-section').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // Your existing carousel code (if needed)
        const prevButton = document.querySelector('.nav-button.prev');
        const nextButton = document.querySelector('.nav-button.next');
        const track = document.querySelector('.track');
        const carousel = document.querySelector('.carousel-container');
        const cards = document.querySelectorAll('.card-container');
        const indicators = document.querySelectorAll('.indicator');

        if (prevButton && nextButton && track && carousel && cards.length) {
            let index = 0;
            const cardWidth = cards[0]?.offsetWidth + parseInt(getComputedStyle(track).gap || 0);
            const visibleCards = Math.floor(carousel?.offsetWidth / cardWidth);
            const maxIndex = cards.length - visibleCards;

            function updateButtonStates() {
                prevButton.classList.toggle('disabled', index === 0);
                nextButton.classList.toggle('disabled', index >= maxIndex);
            }

            function updateCarousel() {
                track.style.transform = `translateX(${-index * cardWidth}px)`;
                updateButtonStates();
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

            window.addEventListener('resize', function() {
                const newVisibleCards = Math.floor(carousel?.offsetWidth / cardWidth);
                const newMaxIndex = cards.length - newVisibleCards;

                if (index > newMaxIndex) {
                    index = newMaxIndex;
                }

                updateCarousel();
            });

            updateButtonStates();
        }
    });
</script>
@endsection
