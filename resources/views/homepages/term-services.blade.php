@extends('homepages.layout.app')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/wiserlance/assets/css/home-pages.css') }}">

    <style>
        /* .list-group-item {
            background-color: #037e89 !important;
        } */

        .list-group-item.active {
            background-color: #037e89 !important;
            border: none;
        }
    </style>
@endsection



@section('content')
    <div class="">
        <div class="container">
            <div class="row px-2 p-lg-3">
                <p class="mb-0 text-bc mb-4 fs-14">
                    <a class="text-3gray" href="/">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <i class="fa-solid fa-chevron-right fs-14"></i>
                    <a class="text-3gray links-c1" href="{{ route('terms') }}">
                        Terms of Service
                    </a>
                </p>

                <div class="col-lg-12 mb-4">

                    @if (isset($helpCenter) && count($helpCenter) > 0)
                        <div class="row">

                            <!-- Left Sidebar -->
                            <div class="col-lg-4 mb-4">
                                <div class="list-group shadow-sm">

                                    @foreach ($helpCenter as $index => $term)
                                        <button
                                            class="list-group-item list-group-item-action {{ $index == 0 ? 'active' : '' }}"
                                            data-bs-toggle="pill" data-bs-target="#term-tab-{{ $term->id }}"
                                            type="button">
                                            {{ $term->title }}
                                        </button>
                                    @endforeach

                                </div>
                            </div>

                            <!-- Right Content -->
                            <div class="col-lg-8">

                                <div class="tab-content">

                                    @foreach ($helpCenter as $index => $term)
                                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                            id="term-tab-{{ $term->id }}">

                                            <div class="card border-0 shadow-sm">

                                                <div class="card-header bg-dgreen2 text-white">
                                                    <h4 class="mb-0">
                                                        {{ $term->title }}
                                                    </h4>
                                                </div>

                                                <div class="card-body">

                                                    @if ($term->details && count($term->details) > 0)
                                                        <div class="accordion accordion-flush"
                                                            id="accordion-{{ $term->id }}">

                                                            @foreach ($term->details as $detailIndex => $detail)
                                                                <div class="accordion-item mt-1">

                                                                    <h2 class="accordion-header"
                                                                        id="heading-{{ $detail->id }}">

                                                                        <button
                                                                            class="accordion-button text-dgreen bg-lgreen3 fs-5 fw-semibold {{ $detailIndex != 0 ? 'collapsed' : '' }}"
                                                                            type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#collapse-{{ $detail->id }}"
                                                                            aria-expanded="{{ $detailIndex == 0 ? 'true' : 'false' }}">

                                                                            {{ $detail->sub_title }}
                                                                        </button>

                                                                    </h2>

                                                                    <div id="collapse-{{ $detail->id }}"
                                                                        class="accordion-collapse collapse {{ $detailIndex == 0 ? 'show' : '' }}"
                                                                        data-bs-parent="#accordion-{{ $term->id }}">

                                                                        <div class="accordion-body">
                                                                            {!! $detail->desc !!}
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    @else
                                                        <div class="alert alert-info mb-0">
                                                            No details available.
                                                        </div>
                                                    @endif

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No payment terms found.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="bg-lgreen2 p-3">
        <div class="container">
            <div class="col-12 ps-3">
                <h5>
                    <i>Do you have a question? Please send us your query, and we'll be happy to assist you.</i>

                    <a href="{{ route('contact.us') }}" class="btn btn-success bg-dgreen ms-3 fs-5 mt-2 mt-lg-0">

                        <i class="fa-solid fa-chevron-right text-green"></i>
                        Ask Now
                    </a>
                </h5>
            </div>
        </div>
    </div>
@endsection

@push('custom-css')
    <style>
        .accordion-item {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .accordion-button:not(.collapsed) {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, .125);
        }

        .table {
            font-size: 14px;
        }

        .table th,
        .table td {
            padding: 10px;
            vertical-align: middle;
        }

        .bg-dgreen2 {
            background-color: #1b5e20;
        }

        .text-dgreen2 {
            color: #1b5e20;
        }

        .bg-lgreen3 {
            background-color: #c8e6c9;
        }

        .bg-lgreen2 {
            background-color: #a5d6a7;
        }

        .bg-dgreen {
            background-color: #2e7d32;
        }

        .text-green {
            color: #4caf50;
        }

        .btn-success.bg-dgreen {
            border-color: #1b5e20;
        }

        .btn-success.bg-dgreen:hover {
            background-color: #1b5e20;
            border-color: #1b5e20;
        }

        .fs-5 {
            font-size: 1.1rem;
        }

        .fs-3 {
            font-size: 1.8rem;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .accordion-body {
            padding: 1.5rem;
            line-height: 1.6;
        }

        .accordion-body h1,
        .accordion-body h2,
        .accordion-body h3,
        .accordion-body h4,
        .accordion-body h5,
        .accordion-body h6 {
            margin-top: 1rem;
            margin-bottom: 0.75rem;
            color: #1b5e20;
        }

        .accordion-body p {
            margin-bottom: 1rem;
        }

        .accordion-body ol,
        .accordion-body ul {
            padding-left: 1.8rem;
            margin-bottom: 1rem;
        }

        .accordion-body li {
            margin-bottom: 0.5rem;
        }

        .accordion-body table {
            width: 100%;
            margin-bottom: 1rem;
        }

        .accordion-body img {
            max-width: 100%;
            height: auto;
        }

        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .accordion-body {
                padding: 1rem;
            }

            .table {
                font-size: 12px;
            }

            .fs-3 {
                font-size: 1.5rem;
            }
        }
    </style>
@endpush

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showLoader();

            setTimeout(hideLoader, 5000);
            // Initialize any carousel functionality if needed
            const prevButton = document.querySelector('.nav-button.prev');
            const nextButton = document.querySelector('.nav-button.next');
            const track = document.querySelector('.track');
            const carousel = document.querySelector('.carousel-container');
            const cards = document.querySelectorAll('.card-container');
            const indicators = document.querySelectorAll('.indicator');

            if (cards.length > 0 && track && carousel) {
                let index = 0;
                const cardWidth = cards[0]?.offsetWidth + parseInt(getComputedStyle(track).gap || 0);
                const visibleCards = Math.floor(carousel?.offsetWidth / cardWidth);
                const maxIndex = cards.length - visibleCards;

                function updateCarousel() {
                    if (track) {
                        track.style.transform = `translateX(${-index * cardWidth}px)`;
                    }
                    updateButtonStates();
                    updateIndicators();
                }

                function updateButtonStates() {
                    if (prevButton) {
                        prevButton.classList.toggle('disabled', index === 0);
                    }
                    if (nextButton) {
                        nextButton.classList.toggle('disabled', index >= maxIndex);
                    }
                }

                function updateIndicators() {
                    const activeIndicator = Math.floor(index / Math.ceil(maxIndex / indicators.length));
                    indicators.forEach((indicator, i) => {
                        indicator.classList.toggle('active', i === activeIndicator);
                    });
                }

                if (nextButton) {
                    nextButton.addEventListener('click', function() {
                        if (index < maxIndex) {
                            index++;
                            updateCarousel();
                        }
                    });
                }

                if (prevButton) {
                    prevButton.addEventListener('click', function() {
                        if (index > 0) {
                            index--;
                            updateCarousel();
                        }
                    });
                }

                indicators.forEach((indicator, i) => {
                    indicator.addEventListener('click', function() {
                        index = i * Math.ceil(maxIndex / indicators.length);
                        updateCarousel();
                    });
                });

                cards.forEach(card => {
                    card.addEventListener('click', function() {
                        cards.forEach(c => c.querySelector('.card-c')?.classList.remove('active'));
                        this.querySelector('.card-c')?.classList.add('active');
                    });
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
