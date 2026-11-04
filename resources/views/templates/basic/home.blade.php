@extends($activeTemplate . 'layouts.wiserlance.website-layout')
@section('content')
    @php
        $content = getContent('contact_us.content', true);
    @endphp
    @php
        $helpers = new \App\Classes\Helpers();
        $currency_rates = $helpers->get_currency_rates();
        $currencies = $helpers->get_all_currencies();
        // echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
    @endphp

    <section style="padding-top:0;">
        @include('templates.basic.components.home.header')
        @include('templates.basic.components.home.for-buyer')
        {{-- @include('templates.basic.components.home.featured-services') --}}
        @include('templates.basic.components.home.why-chosse-us')
        @include('templates.basic.components.home.popular-catagory')
        @include('templates.basic.components.home.for-sellers')
        @include('templates.basic.components.home.corporate-business')
    </section>
@endsection
@push('script')
    <script type="text/javascript">
        // item Slide
        $('.service-slide').slick({
            slidesToShow: 4,
            arrows: true,
            dots: false,
            infinite: true,
            speed: 500,
            cssEase: 'linear',
            autoplaySpeed: 2000,
            autoplay: true,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        arrows: true,
                        dots: false,
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        arrows: true,
                        dots: false,
                        slidesToShow: 1
                    }
                }
            ]
        });
    </script>
    <script type="text/javascript">
        $('.autoadjust').change(function() {
            $(".autoadjust").removeClass("autowidth");
            var adg = $(".autoadjust").val();
            if (adg == 'professionals') {
                $(".autoadjust").addClass("pros");
                $(".autoadjust").removeClass("ser");
                $(".autoadjust").removeClass("cor");
                $(".autoadjust").removeClass("job");
            } else if (adg == 'services') {
                $(".autoadjust").addClass("ser");
                $(".autoadjust").removeClass("pros");
                $(".autoadjust").removeClass("cor");
                $(".autoadjust").removeClass("job");
            } else if (adg == 'jobs') {
                $(".autoadjust").addClass("job");
                $(".autoadjust").removeClass("pros");
                $(".autoadjust").removeClass("cor");
                $(".autoadjust").removeClass("ser");
            } else if (adg == 'corporate') {
                $(".autoadjust").addClass("cor");
                $(".autoadjust").removeClass("pros");
                $(".autoadjust").removeClass("ser");
                $(".autoadjust").removeClass("job");
            }
            //   var text = $(this).find('option:selected').text()
            //   var $aux = $('<select/>').append($('<option/>').text(text))
            //   $(this).after($aux)
            //   $(this).width($aux.width())
            //   $aux.remove()
            // }).change()
        });
    </script>
    <script type="text/javascript">
        $('.newright-paddle').on('click', function() {
            var $this = $(this);
            var id = $this.data('id');
            var inner = $('#newparentscrolss');
            $('#newparentscrolss').animate({
                scrollLeft: inner.width()
            }, 1000);
            $('.newright-paddless').hide();
            $('.newleft-paddless').show();
        });
        $('.newleft-paddle').on('click', function() {
            var $this = $(this);
            var id = $this.data('id');
            var inner = $('#newparentscrolss');
            $('#newparentscrolss').animate({
                scrollLeft: 0
            }, 1000);
            $('.newleft-paddless').hide();
            $('.newright-paddless').show();
        });
    </script>
@endpush
