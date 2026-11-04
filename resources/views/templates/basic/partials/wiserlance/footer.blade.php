<style>
    .ntn-green-footer {
        background-color: var(--primary-color);
    }

    .ntn-white-border-top-footer-6587567 {
        border-top: 1px solid var(--white-color);
        margin-bottom: 16px;
    }

    .ntn-footer-middle {}

    .ntn-footer-sections-outer {
        display: flex;
        align-items: flex-start;
    }
</style>
<footer class="text-white ntn-green-footer">
    <div class="footer-middle ntn-footer-middle">
        <div class="container">
            <div class="row ntn-footer-sections-outer">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                    <div class="footer_widget">
                        <img src="{{ asset('assets/wiserlance/assets/img/logos/wiserlance-logo-footer.svg') }}"
                            class="img-footer small mb-2" alt="" /><br>
                        <p class="mt-3">Kohinoor Plaza, D-Ground <br>
                            Faisalabad 38000</p>
                        <p class="mt-3">1-234-567-8901 <br>
                            info@mail.com
                        </p>
                        <ul class="list-inline">
                            <li class="list-inline-item"><a href="#" class="theme-cl"><i
                                        class="lni lni-facebook-filled"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="theme-cl"><i
                                        class="lni lni-twitter-filled"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="theme-cl"><i
                                        class="lni lni-youtube"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="theme-cl"><i
                                        class="lni lni-instagram-filled"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="theme-cl"><i
                                        class="lni lni-linkedin-original"></i></a></li>
                        </ul>



                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                    <div class="footer_widget">
                        <h3 class="widget_title text-white ntn-poppins-bold">Categories</h3>
                        <ul class="footer-menu">
                            @php
                                $cat = DB::table('categories')->where('status', 1)->where('type', 'service')->get();
                            @endphp
                            @foreach ($cat as $cate)
                                <li><a class="text-white ntn-poppins"
                                        href="{{ route('category.services', [encrypt($cate->id)]) }}">{{ $cate->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                    <div class="footer_widget">
                        <h3 class="widget_title text-white ntn-poppins-bold">Support</h3>
                        <ul class="footer-menu">
                            <li><a class="text-white ntn-poppins" href="{{ route('service') }}">Help & Support</a></li>
                            <li><a class="text-white ntn-poppins" href="{{ route('job') }}">Trust & safety</a></li>
                            <li><a class="text-white ntn-poppins" href="{{ route('professionals') }}">Selling on
                                    Wiserlnce</a>
                            </li>
                            <li><a class="text-white ntn-poppins" href="{{ route('blog') }}">Buying on Wiserlnce</a>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                    <div class="footer_widget">
                        <h3 class="widget_title text-white ntn-poppins-bold">About</h3>
                        <ul class="footer-menu">
                            <li><a class="text-white ntn-poppins" href="{{ route('service') }}">Careers</a></li>
                            <li><a class="text-white ntn-poppins" href="{{ route('job') }}">Press & News</a></li>
                            <li><a class="text-white ntn-poppins" href="{{ route('professionals') }}">Partnerships</a>
                            </li>
                            <li><a class="text-white ntn-poppins" href="{{ route('blog') }}">Privacy Policy</a></li>
                            <li><a class="text-white ntn-poppins" href="{{ route('blog') }}">Terms of Services</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ntn-footer-bottom-cn {
            padding-left: 0;
            padding-right: 0;
            padding-top: 16px;
        }

        .ntn-footer-bottom-cn2 {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .ntn-select-currency-footer {}

        .ntn-footer-bottom-cn3 {
            display: flex;
            flex-direction: row;
            gap: 16px;
            width: 100%;
            font-size: 14px;
            align-items: center
        }
    </style>

    <div class="footer-bottom">
        <div class="container ntn-footer-bottom-cn">
            <div class="ntn-footer-bottom-cn2">
                <div class="ntn-footer-bottom-cn3"><img
                        src="{{ asset('assets/wiserlance/assets/img/icons/copywrite.svg') }}" style="width:16px" />
                    <p class="mb-0 ntn-poppins">Wiserlance 2023, all rights are reserved </p>
                </div>
                <div
                    style="display: flex;
                width: 100%;
                align-items: center;
                justify-content: end;
                gap: 35px;">
                    <div>
                        @include('templates.basic.partials.wiserlance.components.currency-selection')
                    </div>
                    <div>
                        @include('templates.basic.partials.wiserlance.components.language-selection')
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
