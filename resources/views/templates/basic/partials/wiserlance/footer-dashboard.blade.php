
<footer class="container-fluid bg-dgreen text-white mt-3">
    <div class="container">
        <div class="row py-5">
            <div class="col-md-3">
                <img src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.svg') }}" width="200">

            <p class="mt-4 ps-3"> Kohinoor Plaza, D-Ground <br>
                Faisalabad 38000</br>
            </p>

            <p class="ps-3"> 1-234-567-8901 <br>
                info@mail.com
            </p>
            <i class="fa-brands fa-facebook-f ps-3"></i> <i class="fab fa-twitter ms-2"></i> <i class="fa-brands fa-youtube ms-2"></i>
            <i class="fa-brands fa-instagram ms-2"></i> <i class="fa-brands fa-linkedin ms-2"></i>
        </div>

            <div class=" col-md-3 mt-3 mt-md-0">
                <h5 class="ps-md-3">Categories</h5>
                <ul class="nav flex-column" style="font-size: 14px; line-height: 10px;">
                    @if(count($service_categories)>0)
                        @foreach($service_categories as $service_category)
                            <li class="nav-item">
                                <a href="{{ route('home', ['type' => 'services', 'category' => $service_category->id]) }}" class="nav-link text-white">{{$service_category?->name}}</a>
                            </li>
                        @endforeach
                    @endif
                    
                </u>
            </div>
            <div class="col-md-3 mt-3 mt-md-0">
                <h5 class="ps-md-3">Support</h5>
                <ul class="nav flex-column" style="font-size: 14px; line-height: 10px;">
                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="{{ route('help.support.center') }}">Help & Support</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="#">Trust & safety</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="#">Selling on Wiserlnce</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="#">Buying on Wiserlnce</a>
                    </li>
                </u>
            </div>
            <div class="col-md-3 mt-3 mt-md-0">
                <h5 class="ps-md-3">About</h5>
                <ul class="nav flex-column ps-0" style="font-size: 14px; line-height: 10px;">
                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="#">Careers</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="#">Press & News</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="#">Partnerships</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="{{ route('privacy') }}">Privacy Policy</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="{{ route('terms') }}">Terms of Services</a>
                    </li>
                </u>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="{{ url('/') }}" class="nav-link text-white" style="text-align: center">© {{ date('Y') }} Wiserlance. All rights reserved.</a>
            </div>
        </div>
    </div>
</footer>
