@extends('homepages.layout.app')

@section('title', 'Tips for Success Seller')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
@endsection

@section('content')
<div style="">
    <div class="container">
        <div class="row px-2 p-lg-3">
            <div class="col-lg-12 mb-4 mt-2">
                <h1 class="fs-3 text-dgreen2 mb-4 fw-bold">Tips for Success as a Seller</h1>

                <div class="accordion accordion-flush" id="mainAccordion">
                    <!-- Profile and Portfolio -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed text-dgreen bg-lgreen3 fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Profile and Portfolio
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#mainAccordion">
                            <div class="accordion-body pt-4">
                                <div class="d-flex">
                                    <div><i class="fa-regular fa-address-card me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Create a professional profile:</b><br>Use a professional photo and write a compelling bio that highlights your experience and what makes you unique.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-desktop me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Showcase your work:</b><br>Include a portfolio of your best work to demonstrate your abilities visually.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-list-ul me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">List specific skills:</b><br>Be detailed and specific about your areas of expertise to help clients find you.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-regular fa-star me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Gather testimonials:</b><br>Ask satisfied clients for reviews or testimonials to boost credibility.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bidding and Proposals -->
                    <div class="accordion-item mt-1">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed text-dgreen bg-lgreen3 fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Bidding and Proposals
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#mainAccordion">
                            <div class="accordion-body pt-4">
                                <div class="d-flex">
                                    <div><i class="fa-solid fa-circle-play me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Start with smaller projects:</b><br>Begin with manageable tasks to build experience and collect positive reviews.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-regular fa-money-bill-1 me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Set a reasonable rate:</b><br>Start competitively and adjust your rate as your reputation grows.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-pen me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Write tailored proposals:</b><br>Customize every proposal for each job and explain how you can solve the client's problem.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-trophy me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Focus on quality:</b><br>Deliver excellent work to gain repeat clients and positive feedback.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client Communication and Relationship Building -->
                    <div class="accordion-item mt-1">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed text-dgreen bg-lgreen3 fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Client Communication and Relationship Building
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#mainAccordion">
                            <div class="accordion-body pt-4">
                                <div class="d-flex">
                                    <div><i class="fa-solid fa-comment-dots me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Communicate clearly:</b><br>Be transparent about your skills, availability, and project expectations.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-rocket me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Go above and beyond:</b><br>Exceed client expectations to earn long-term partnerships and referrals.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-bolt me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Be responsive:</b><br>Reply quickly and keep clients updated throughout the project.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Long-Term Strategy -->
                    <div class="accordion-item mt-1">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed text-dgreen bg-lgreen3 fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Long-Term Strategy
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#mainAccordion">
                            <div class="accordion-body pt-4">
                                <div class="d-flex">
                                    <div><i class="fa-solid fa-comment-dots me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Continuously improve:</b><br>Invest in your skills to stay competitive and expand your service range.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-clock me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Be patient:</b><br>Success takes time — stay consistent and don't get discouraged by early setbacks.</p>
                                </div>

                                <div class="d-flex">
                                    <div><i class="fa-solid fa-paper-plane me-3 bg-dgreen2 opacity-75 rounded-5 text-light text-center pt-4 fa-xl" style="width:50px; Height:50px;" aria-hidden="true"></i></div>
                                    <p><b class="text-dgreen2">Build a brand outside platforms:</b><br>Reply quickly and keep clients updated throughout the project.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-lgreen2 p-3">
    <div class="container">
        <div class="col-12 ps-3">
            <h5><i>Do you have a question? please send us your query, and we'll be happy to assist you?</i>
                <a href="{{route('contact.us')}}" class="btn btn-success bg-dgreen ms-3 fs-5 mt-2 mt-lg-0"><i class="fa-solid fa-chevron-right text-green" aria-hidden="true"></i> Ask Now</a>
            </h5>
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
