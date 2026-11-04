@extends('homepages.layout.app')

@section('title', 'Privacy Policy')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/wiserlance/assets/css/home-pages.css') }}">
@endsection
@section('content')
    <div class="" style="">
        <div class="container">
            <div class="row px-2 p-lg-3">
                <p class=" mb-0 text-bc mb-4 fs-14">
                    <a class="text-3gray" href="/"> <i class="fa-solid fa-house"></i></a>
                    <i class="fa-solid fa-chevron-right fs-14"></i> <a class="text-3gray links-c1"
                        href="{{ route('privacy') }}"> Project Rules</a>
                </p>
                <!--Breadcrumbs End-->
                <div class="card">
                    <div class="card-body">
                        @if ($policy?->details && count($policy?->details) > 0)
                            <div class="accordion accordion-flush" id="privacyAccordion">
                                @foreach ($policy?->details as $index => $detail)
                                    <div class="accordion-item mt-1">
                                        <h2 class="accordion-header" id="flush-heading-{{ $detail->id ?? $index }}">
                                            <button
                                                class="accordion-button text-dgreen bg-lgreen3 fs-5 fw-semibold {{ $index != 0 ? 'collapsed' : '' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse-{{ $detail->id ?? $index }}"
                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                aria-controls="flush-collapse-{{ $detail->id ?? $index }}">
                                                {{ $detail->sub_title ?? 'Detail ' . ($index + 1) }}
                                            </button>
                                        </h2>
                                        <div id="flush-collapse-{{ $detail->id ?? $index }}"
                                            class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                            aria-labelledby="flush-heading-{{ $detail->id ?? $index }}"
                                            data-bs-parent="#privacyAccordion">
                                            <div class="accordion-body pt-4">
                                                {!! $detail->desc ?? '' !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="accordion-item mt-1">
                                <div class="accordion-body">
                                    <p>No content available.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>


    <div class="bg-lgreen2 p-3">
        <div class="container">
            <div class="col-12 ps-3">
                <h5><i>Do you have a question? please send us your query, and we'll be happy to assist you?</i>
                    <a href="{{ route('contact.us') }}" class="btn btn-success bg-dgreen ms-3 fs-5 mt-2 mt-lg-0"><i
                            class="fa-solid fa-chevron-right text-green" aria-hidden="true"></i> Ask Now</a>
                </h5>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
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
