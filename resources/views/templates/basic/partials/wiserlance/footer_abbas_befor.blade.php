
<!-- <section class="space bg-cover" style="background:#28b661 url({{asset('assets/wiserlance/assets/img/zub/jb.jpg')}}) no-repeat; ">
<div class="container py-5">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative mb-5">
<h6 class="text-muted mb-0">Subscribr Now</h6>
<h2 class="ft-bold">Get All New Job <span class="theme-cl">Notification</span></h2>
</div>
</div>
</div>
<div class="row ">
<div class="col-xl-7 col-lg-10 col-md-12 col-sm-12 col-12">
<form class="bg-white rounded p-1">
<div class="row no-gutters">
<div class="col-xl-9 col-lg-9 col-md-8 col-sm-8 col-8">
    <div class="form-group mb-0 position-relative">
        <input type="text" class="form-control lg left-ico" placeholder="Enter Your Email Address">
        <i class="bnc-ico lni lni-envelope"></i>
    </div>
</div>
<div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-4">
    <div class="form-group mb-0 position-relative">
        <button class="btn full-width custom-height-lg bg-dark text-white fs-md" type="button">Subscribe</button>
    </div>
</div>
</div>
</form>
</div>
</div>
</div>
</section> -->
<!-- ======================= Newsletter Start ============================ -->

<!-- ============================ Footer Start ================================== -->
<!-- <footer class="light-footer skin-light-footer style-3">
<div class="footer-middle">
<div class="container">
<div class="row">
<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
<div class="footer_widget">
<img src="{{asset('assets/wiserlance/assets/img/logos/1.JPG')}}" class="img-footer small mb-2" alt="" /><br>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco.</p>
<img src="{{asset('assets/wiserlance/assets/img/app1 (1).png')}}" class="img-footer small mb-2" alt="" />
<img src="{{asset('assets/wiserlance/assets/img/app1 (2).png')}}" class="img-footer small mb-2" alt="" />



</div>
</div>
<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
<div class="footer_widget">
<h4 class="widget_title">Categories</h4>
<ul class="footer-menu">
    @php
    $cat=DB::table('categories')->where('status',1)->where('type','service')->get();
    @endphp
    @foreach($cat as $cate)
    <li><a href="{{route('category.services', [encrypt($cate->id)])}}">{{$cate->name}}</a></li>
    @endforeach
</ul>
</div>
</div>
<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
<div class="footer_widget">
<h4 class="widget_title">Links</h4>
<ul class="footer-menu">
    <li><a href="{{route('service')}}">Services</a></li>
    <li><a href="{{route('job')}}">Jobs</a></li>
    <li><a href="{{route('professionals')}}">Professionals</a></li>
    <li><a href="{{route('blog')}}">Blogs</a></li>
</ul>
</div>
</div>

<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
<div class="footer_widget">
<h4 class="widget_title">Company</h4>
<ul class="footer-menu">
    <li><a href="#"><i class="fa fa-envelope"></i> &nbsp Info@gmail.com</a></li>
    <li><a href="#"><i class="fa fa-phone"></i> &nbsp +92345678912</a></li>
    <li><a href="#">Become a Seller</a></li>
    <li><a href="#">About us</a></li>
 </ul>
</div>
</div>
<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
<div class="footer_widget">
<h4 class="widget_title">Support</h4>
<ul class="footer-menu">
    <li><a href="#">Terms & Conditions</a></li>
    <li><a href="#">User Guide</a></li>
    <li><a href="{{route('contact')}}">Contact Us</a></li>
    <li>
        <div class="address ">
    <ul class="list-inline">
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-facebook-filled"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-twitter-filled"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-youtube"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-instagram-filled"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-linkedin-original"></i></a></li>
    </ul>
</div>
    </li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="footer-bottom br-top">
<div class="container">
<div class="row align-items-center">
<div class="col-lg-6 col-md-6 text-left">
<p class="mb-0">Wiserlance © 2023 All rights Reserved </p>
</div>
<div class="col-lg-6 col-md-6 text-right">

                        <img alt="payment" src="https://1000logos.net/wp-content/uploads/2021/06/Stripe-Logo-2009.png" style="width:10%;">
                        <img alt="payment" src="https://www.citypng.com/public/uploads/preview/hd-official-blue-paypal-logo-trademark-png-216352711957dpeuc7foy.png" style="width:10%;">
                        <img alt="payment" src="https://tukuz.com/wp-content/uploads/2021/05/mangopay-logo-vector.png" style="width:15%;">
                        
</div>
</div>
</div>
</div>
</footer> -->

<footer class=" theme-bg text-white" >
<div class="footer-middle">
<div class="container">
<div class="row">
<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
<div class="footer_widget">
<img src="{{asset('assets/wiserlance/assets/img/logos/1.JPG')}}" class="img-footer small mb-2" alt="" /><br>
<p class="ml-3 mt-3">Kohinoor Plaza, D-Ground <br>
Faisalabad 38000</p>
<p class="ml-3 mt-3">1-234-567-8901 <br>
    info@mail.com
</p>
 <ul class="list-inline">
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-facebook-filled"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-twitter-filled"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-youtube"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-instagram-filled"></i></a></li>
        <li class="list-inline-item"><a href="#" class="theme-cl"><i class="lni lni-linkedin-original"></i></a></li>
    </ul>



</div>
</div>
<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
<div class="footer_widget">
<h2 class="widget_title text-white">Categories</h2>
<ul class="footer-menu" >
    @php
    <!-- $cat=DB::table('categories')->where('status',1)->where('type','service')->get(); -->
    $cat=DB::table('categories')->where('status',1)->where('type','job')->get();
    @endphp
    @foreach($cat as $cate)
    <li ><a class="text-white" href="{{route('category.services', [encrypt($cate->id)])}}"><i style="font-size:8px;" class="fa fa-circle"></i> &nbsp {{$cate->name}}</a></li>
    @endforeach
</ul>
</div>
</div>

<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
<div class="footer_widget">
<h2 class="widget_title text-white">Support</h2>
<ul class="footer-menu" >
    <li><a class="text-white" href="{{route('service')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Help & Support</a></li>
    <li><a class="text-white" href="{{route('job')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Trust & safety</a></li>
    <li><a class="text-white" href="{{route('professionals')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Selling on Wiserlnce</a></li>
    <li><a class="text-white" href="{{route('blog')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Buying on Wiserlnce</a></li>
</ul>
</div>
</div>


<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
<div class="footer_widget">
<h2 class="widget_title text-white">About</h2>
<ul class="footer-menu" >
    <li><a class="text-white" href="{{route('service')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Careers</a></li>
    <li><a class="text-white" href="{{route('job')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Press & News</a></li>
    <li><a class="text-white" href="{{route('professionals')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Partnerships</a></li>
    <li><a class="text-white" href="{{route('blog')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Privacy Policy</a></li>
    <li><a class="text-white" href="{{route('blog')}}"> <i style="font-size:8px;" class="fa fa-circle"></i> &nbsp Terms of Services</a></li>
</ul>
</div>
</div>




</div>
</div>
</div>
<div class="footer-bottom br-top bg-dark">
<div class="container">
<div class="row align-items-center">
<div class="col-lg-12 text-left">
<p class="mb-0" style="color: #28b661;">Wiserlance © 2023 All rights Reserved </p>
</div>

</div>
</div>
</div>
</footer>
<!-- ============================ Footer End ==================================