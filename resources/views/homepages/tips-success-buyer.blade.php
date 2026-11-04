@extends('homepages.layout.app')

@section('title', 'Tips for Success Buyer')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/wiserlance/assets/css/home-pages.css')}}">
@endsection

@section('content')
<div style="">
    <div class="container">
        <div class="row px-2 p-lg-3">

                <!--Breadcrumbs End-->
            <div class="col-lg-12 mb-4 mt-2">
                <h1 class="fs-3 text-dgreen2 mb-4 fw-bold "> Tips for Success as a Buyer</h1>

        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item ">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button text-dgreen bg-lgreen3 fw-semibold fs-5 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Before You Hire
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                 <div class="accordion-body pt-4">
                    <div class="d-flex">
                        <div><i class="fa-solid fa-clipboard-list me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i></div>
                        <p> <b class="text-dgreen2"> Define your project:</b><br> Clearly outline your goals, deliverables, and budget. The more specific you are, the easier it is to attract freelancers who truly fit your needs.</p>
                    </div>

                    <div class="d-flex">
                        <div> <i class="fas fa-clipboard-check me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i> </div>
                        <p> <b class="text-dgreen2"> Review freelancer profiles carefully:</b><br> Take time to explore portfolios, client reviews, and skillsets before making your choice.</p>
                    </div>

                    <div class="d-flex">
                        <div> <i class="fas fa-file-export me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i></div>
                        <p> <b class="text-dgreen2"> Post a clear and detailed job:</b><br> Include background about your project, expectations, and budget so freelancers can tailor their proposals effectively.</p>
                    </div>

                    <div class="d-flex">
                        <div> <i class="fas fa-calendar-week me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i></div>
                        <p><b class="text-dgreen2">Set expectations early:</b> <br> Discuss communication frequency, deadlines, and milestones to ensure everyone’s aligned from the start.</p>
                    </div>
            </div>
        </div>

        <div class="accordion-item mt-1 ">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed text-dgreen bg-lgreen3 fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                During and After the Project
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body pt-4">
                    <div class="d-flex">
                        <div><i class="fa-solid fa-comment-dots me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i></div>
                        <p> <b class="text-dgreen2"> Keep communication open:</b><br> Regular updates and quick responses help projects run smoothly and build mutual trust.</p>
                    </div>

                    <div class="d-flex">
                        <div> <i class="fas fa-credit-card me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i> </div>
                        <p> <b class="text-dgreen2"> Pay promptly and fairly:</b><br> Timely payments motivate freelancers and reinforce a positive working relationship.</p>
                    </div>

                    <div class="d-flex">
                        <div> <i class="fa fa-star me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i></div>
                        <p> <b class="text-dgreen2"> Share thoughtful feedback: </b><br> When the project wraps up, provide honest and constructive feedback to help freelancers improve and build credibility.</p>
                    </div>

                    <div class="d-flex">
                        <div> <i class="fas fa-handshake me-3 bg-dgreen2 rounded-5 text-light  text-center pt-4 fa-xl" style="width:50px; Height:50px;"></i></div>
                        <p><b class="text-dgreen2">Build long-term relationships:</b> <br> If a freelancer delivers great results, keep in touch for future work—strong partnerships create lasting success.</p>
                    </div>
            </div>
    </div>


</div></div></div> </div>     </div></div>
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
