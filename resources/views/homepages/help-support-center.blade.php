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
                <input class="form-control bg-white shadow-sm float-end bg-lgreen"  id="searchInput" type="search" placeholder="Search for help" aria-label="Search">
                <button class="btn btn-outline-dark btn-sm ms-1 bg-green border-0" type="submit"><img src="{{ asset('assets/images/home-page/icons8-search.svg')}}" width="30"></button>
            </form>
        </div> <!--colmn closed-->
    </div>
    </div>
</div>

<div class="container">
    <div class="row py-4 px-2 p-lg-5">

        <div class="col-lg-6 pe-lg-4">
            <h3 class="text-dgreen2 fw-semibold"  style="display: flex;align-items:center"><img src="{{ asset('assets/images/home-page/help.png')}}" width="30" class="me-2"> Buyer Support</h3>
            <h6 class="text-secondary mt-3">If you are a buyer seeking assistance to register as a seller on Wiserlance, follow the links below.</h6>
            @if(count($buyers)>0)
                <ul class="list-unstyled">
                    @foreach($buyers as $byr)
                        <li class="mt-3 help-item">
                            <i class="fa-solid fa-arrow-right border-dgreen2 p-1 rounded-circle fs-5 text-green me-2"></i>
                            <a class="links-c2" href="{{ route('help.details',['slug'=>$byr?->slug]) }}"> {{ $byr?->title }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="col-lg-6 ps-lg-5 mt-4 mt-lg-0">
            <h3 class="text-dgreen2 fw-semibold"  style="display: flex;align-items:center"> <img src="{{ asset('assets/images/home-page/help_1.png')}}" width="30" class="me-2"> Professional Support</h3>
            <h6 class="text-secondary mt-3">If you are a professional seeking assistance to register as a professional on Wiserlance, follow the links below.</h6>

            @if(count($sellers)>0)
                <ul class="list-unstyled">
                    @foreach($sellers as $slr)
                        <li class="mt-3 help-item">
                            <i class="fa-solid fa-arrow-right border-dgreen2 p-1 rounded-circle fs-5 text-green me-2"></i>
                            <a class="links-c2" href="{{ route('help.details',['slug'=>$slr?->slug]) }}"> {{ $slr?->title }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>


</div>

<div class="bg-lgreen2 p-3 ps-lg-4 p-xl-3">
    <div class="containaer ps-xl-5">
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
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let query = this.value.toLowerCase();

        let items = document.querySelectorAll('.help-item');

        items.forEach(item => {
            let text = item.innerText.toLowerCase();

            if (text.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endsection
