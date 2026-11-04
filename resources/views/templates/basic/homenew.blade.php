@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
 
<!-- Top header  -->  
<!-- ============================================================== -->
<!-- ======================= Home Banner ======================== -->
<div class="home-banner margin-bottom-0" style="background:#00ab46 url({{asset('assets/wiserlance/assets/img/banner-3.jpg')}}) no-repeat;" data-overlay="5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-5 col-lg-7 col-md-7 col-sm-12 col-12">
                <form action="{{url('/search')}}" method="get" enctype="multipart/form-data" class="bg-white rounded p-4">
                    @csrf 
                    <div class="row">  

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <h2 class="mb-0 text-muted">Best Service, Right Time & <br> Right People</h2>
                            </div>

                            <div class="form-group position-relative">
                                <input type="text" name="keyword" class="form-control lg form-ico border" placeholder="I'm Looking For" />
                                <i class="bnc-ico lni lni-search-alt"></i>
                            </div> 

                            <div class="form-group position-relative">
                                <select name="type" class="custom-select lg border">
                                    <option value="jobs">Jobs</option>
                                    <option value="services">Services</option>
                                    <option value="professionals">Professionals</option>
                                    <option value="corporate">Corporate</option>
                                </select>
                            </div>
                            <div class="form-group position-relative">
                                <input type="text" name="location" class="form-control lg form-ico" placeholder="Location" />
                                 
                                <i class="bnc-ico lni lni-target"></i>
                            </div>
                            <div class="form-group position-relative">
                                <button class="btn full-width custom-height-lg theme-bg text-white fs-md" type="submit">Search</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
<div class="imployer-explore">
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/microsoft-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/paypal-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/serv-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/mothercare-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/xerox-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/yahoo-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/serv-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/mothercare-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/xerox-home.png')}}" class="" alt="" />
    </div>
    <div class="impl-thumb">
        <img src="{{asset('assets/wiserlance/assets/img/yahoo-home.png')}}" class="" alt="" />
    </div>
</div>
<!-- ======================= Home Banner ======================== -->

<!-- ======================= Job List ======================== -->
<section class="middle bg-light" >
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="text-muted mb-0">Latest Jobs</h6>
<h2 class="ft-bold">Latest jobs List</h2>
</div>
</div>
</div>
<div class="row align-items-center">
   <!-- Single -->
                    @foreach($jobs as $job)
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
                        <div class="row px-3 py-3  bg-white">
                            <div class="col-xl-9 col-lg-9 text-left">
                                <div class="d-block mb-2 position-relative">
                                    <span class="text-muted medium"><i class="fa fa-user mr-1"></i>{{$job->user->username}}</span>
                                    @if(isset($user->status) && $user->status == 1)
                                    <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
                                    @else
                                    <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
                                    @endif
                                </div>
                                <h6 class="font-weight-bold">{{__($job->title)}}</h6>
                                <p>{{__($job->description)}}</p>
                                <div class="jbd-details mb-1">

                                    <ul class="p-0 skills_tag text-left">
                                        @if($job->skill > 0)
                                        @foreach($job->skill as $skill)
                                        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($skill)}}</span></li>
                                        @endforeach
                                        @else

                                        @endif

                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 ">
                                <p><i class="fa fa-award    mr-2"></i>{{__(@$job->user->level)}}</p>
                                <p><i class="fa fa-clock mr-2"></i>{{$job->delivery_time}} @lang('Days')</p>
                                 @if($job->budgettype == 'per_hour')
                                <p><i class="fa fa-dollar-sign mr-2"></i>{{$general->cur_sym}}{{showAmount($job->amount)}}/hour</p>
                                @else
                                <p><i class="fa fa-dollar-sign mr-2"></i>{{$general->cur_sym}}{{showAmount($job->amount)}}</p>
                                @endif 
                                <p><i class="fa fa-map mr-2"></i>{{@$job->user->address->country}}</p>
                                <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">@lang('Bid Now')<i class="lni lni-arrow-right-circle ml-1"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
</div> 
<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="position-relative text-center">
<a href="{{route('job')}}" class="btn btn-md theme-bg rounded text-light hover-theme">Explore More Jobs<i class="lni lni-arrow-right-circle ml-2"></i></a>
</div>
</div>
</div>
</div>
</section> 

<!-- <section class="space bg-light">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="m-spaced">
                    <div class="position-relative">
                        <div class="mb-1"><span class="theme-bg-light theme-cl px-2 py-1 rounded">New</span></div>
                        <h2 class="ft-bold mb-3">Latest Jobs</h2>
                        <p class="mb-2">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                        <p class="mb-4">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. </p>
                    </div>
                    <div class="position-relative row">

                        <div class="col-lg-12 col-md-12 col-12 mt-3">
                            <a href="javascript:void(0);" class="btn btn-md theme-bg-light rounded theme-cl hover-theme">See Details<i class="lni lni-arrow-right-circle ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
                <div class="position-relative">
                    <img src="https://totaljobshub.in/wp-content/uploads/ad9c6f8dfe70da68095d85084aeeabed_content_img_1552110390657-1.jpg" class="img-fluid" width="100%" alt="" />
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- ======================= Job List ======================== -->

<!-- ======================= Related Jobs ======================== -->
<section class="space min " style="background:#0AB68B !important ;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="sec_title position-relative text-center mb-5">
                    <h6 class="text-white mb-0">Featured</h6>
                    <h2 class="text-white ft-bold">Featured Projects</h2>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row align-items-center ">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 ">
                <div class="bg-image-feature" style="background:url(https://themebing.com/wp/prolancer/wp-content/uploads/2021/09/img-10-400x400.jpg);">
                    <a href="">
                        <h1 class="featurenew-text text-white">Laravel Project</h1>
                    </a>
                    <div class="box">
                        <span class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam delectus ullam impedit quam modi vero quibusdam sapiente qui, eveniet sequi architecto porro excepturi laborum nemo !</span>
                        <div class="job_grid_thumbmb-2 mt-2">
                            <a href="landing-page.html" class="d-inline-flex text-center m-auto circle border p-2"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle" width="70" alt="" /></a>
                        </div>
                        <div class="job_grid_caption text-left pb-3 px-3">
                            <h4 class="mb-0 ft-medium medium"><a href="landing-page.html" class="text-white fs-md">John K. Surber</a></h4>
                            <div class="jbl_location"><i class="lni lni-map-marker c mr-1"></i><span>San Francisco</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 ">
                <div class="bg-image-feature" style="background:url(https://themebing.com/wp/prolancer/wp-content/uploads/2021/09/img-3-400x400.jpg);">
                    <a href="">
                        <h1 class="featurenew-text text-white">Laravel Project</h1>
                    </a>
                    <div class="box">
                        <span class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam delectus ullam impedit quam modi vero quibusdam sapiente qui, eveniet sequi architecto porro excepturi laborum nemo !</span>
                        <div class="job_grid_thumbmb-2 mt-2">
                            <a href="landing-page.html" class="d-inline-flex text-center m-auto circle border p-2"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle" width="70" alt="" /></a>
                        </div>
                        <div class="job_grid_caption text-left pb-3 px-3">
                            <h4 class="mb-0 ft-medium medium"><a href="landing-page.html" class="text-white fs-md">John K. Surber</a></h4>
                            <div class="jbl_location"><i class="lni lni-map-marker c mr-1"></i><span>San Francisco</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 ">
                <div class="bg-image-feature" style="background:url(https://themebing.com/wp/prolancer/wp-content/uploads/2021/09/img-10-400x400.jpg);">
                    <a href="">
                        <h1 class="featurenew-text text-white">Laravel Project</h1>
                    </a>
                    <div class="box">
                        <span class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam delectus ullam impedit quam modi vero quibusdam sapiente qui, eveniet sequi architecto porro excepturi laborum nemo !</span>
                        <div class="job_grid_thumbmb-2 mt-2">
                            <a href="landing-page.html" class="d-inline-flex text-center m-auto circle border p-2"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle" width="70" alt="" /></a>
                        </div>
                        <div class="job_grid_caption text-left pb-3 px-3">
                            <h4 class="mb-0 ft-medium medium"><a href="landing-page.html" class="text-white fs-md">John K. Surber</a></h4>
                            <div class="jbl_location"><i class="lni lni-map-marker c mr-1"></i><span>San Francisco</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 ">
                <div class="bg-image-feature" style="background:url(https://themebing.com/wp/prolancer/wp-content/uploads/2021/09/img-3-400x400.jpg);">
                    <a href="">
                        <h1 class="featurenew-text text-white">Laravel Project</h1>
                    </a>
                    <div class="box">
                        <span class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam delectus ullam impedit quam modi vero quibusdam sapiente qui, eveniet sequi architecto porro excepturi laborum nemo !</span>
                        <div class="job_grid_thumbmb-2 mt-2">
                            <a href="landing-page.html" class="d-inline-flex text-center m-auto circle border p-2"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle" width="70" alt="" /></a>
                        </div>
                        <div class="job_grid_caption text-left pb-3 px-3">
                            <h4 class="mb-0 ft-medium medium"><a href="landing-page.html" class="text-white fs-md">John K. Surber</a></h4>
                            <div class="jbl_location"><i class="lni lni-map-marker c mr-1"></i><span>San Francisco</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="job_grid border rounded ">
<div class="position-absolute ab-left"><button type="button" class="p-3 border circle d-flex align-items-center justify-content-center bg-white text-gray"><i class="lni lni-heart-filled position-absolute snackbar-wishlist"></i></button></div>
<div class="position-absolute ab-right"><span class="medium theme-cl theme-bg-light px-2 py-1 rounded">Completed</span></div>
<div class="job_grid_thumb mb-3 pt-5 px-3">
    <a href="job-detail.html" class="d-block text-center m-auto"><img src="{{asset('assets/wiserlance/assets/img/team-1.jpg')}}" class="img-fluid" width="70" alt="" /></a>
</div>
<div class="job_grid_caption text-center pb-5 px-3">
    <h6 class="mb-0 lh-1 ft-medium medium"><a href="employer-detail.html" class="text-muted medium">Joe Smith</a></h6>
    <h4 class="mb-0 ft-medium medium"><a href="job-detail.html" class="text-dark fs-md">Php Dashboard</a></h4>
    <div class="jbl_location"><i class="lni lni-map-marker mr-1"></i><span>San Francisco</span></div>
</div>
<div class="job_grid_footer pb-4 px-3 d-flex align-items-center justify-content-between">
    <div class="df-1 text-muted"><i class="lni lni-wallet mr-1"></i>$50k - $80k PA.</div>
    <div class="df-1 text-muted"><i class="lni lni-star mr-1"></i>4.6 Rating</div>
</div>
</div>
</div> -->
        </div>
        <!-- row -->
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="position-relative text-center">
                    <a href="all-projects.html" class="btn btn-md theme-bg text-light rounded  hover-theme">Browse All Projects<i class="lni lni-arrow-right-circle ml-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= Related Jobs ======================== -->
<!-- ======================= About Start ============================ -->
<section class="middle pt-5  bg-light">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
                <div class="position-relative">
                    <img src="{{asset('assets/wiserlance/assets/img/bn-2.jpg')}}" class="img-fluid" alt="" />
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="m-spaced">
                    <div class="position-relative">
                       <!--  <div class="mb-1"><span class="theme-bg-light theme-cl px-2 py-1 rounded">About Us</span></div> -->
                        <h2 class="ft-bold mb-3">Why Choose Wiserlance?</h2>
                        <p class="mb-3">Talented and professionals from around the world at your fingertips now & why should businesses trust Wiserlance?</p>
                    </div>
                    <div class="position-relative row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="mb-3 mr-4 ml-lg-0 mr-lg-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle theme-bg theme-cl p-2 small d-flex align-items-center justify-content-center">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <h6 class="mb-0 ml-3">Pay and reward the right people, based on your budget and requirements</h6>
                                </div>
                            </div>
                            <div class="mb-3 mr-4 ml-lg-0 mr-lg-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle theme-bg theme-cl p-2 small d-flex align-items-center justify-content-center">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <h6 class="mb-0 ml-3">Registration on Wiserlance will be limited to only professional and talented individuals with identity verified, as our goal is to offer quality services.</h6>
                                </div>
                            </div>
                            <div class="mb-3 mr-4 ml-lg-0 mr-lg-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle theme-bg theme-cl p-2 small d-flex align-items-center justify-content-center">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <h6 class="mb-0 ml-3">Work and quality is at the top of your priority list, while protection of your data, privacy and money is at ours</h6>
                                </div>
                            </div>
                            <div class="mb-3 mr-4 ml-lg-0 mr-lg-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle theme-bg theme-cl p-2 small d-flex align-items-center justify-content-center">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <h6 class="mb-0 ml-3">Do you have a question? please send us your question, and we'll be happy to assist you?</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-12 mt-4">
                            <a href="contact.html" class="btn btn-md theme-bg rounded text-white hover-theme">Start Up For Free<i class="lni lni-arrow-right-circle ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= About Start ============================ -->
<!-- ======================= All category ======================== -->
<section class="space" style="background: #FFE3B3 !important;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="sec_title position-relative text-center mb-5">
                    <h6 class="text-muted mb-0">Popular Categories</h6>
                    <h2 class="ft-bold">Browse Top Categories</h2>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row align-items-center">
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-laptop-phone fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Software Company</h4>
                            <span class="text-muted">607 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-cloud fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Cloud Computing</h4>
                            <span class="text-muted">960 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-shopify fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Logistics/Shipping</h4>
                            <span class="text-muted">438 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-construction fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Engineering Services</h4>
                            <span class="text-muted">644 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-phone-set fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Telecom/ Internet</h4>
                            <span class="text-muted">380 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-sthethoscope fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Healthcare/Pharma</h4>
                            <span class="text-muted">472 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-money-protection fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Finance/Insurance</h4>
                            <span class="text-muted">654 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-diamond fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Product Software</h4>
                            <span class="text-muted">732 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-briefcase fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Diversified/Retail</h4>
                            <span class="text-muted">610 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-graduation fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Education</h4>
                            <span class="text-muted">960 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-mastercard fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Banking/BPO</h4>
                            <span class="text-muted">740 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="cats-wrap text-center">
                    <a href="landing-page.html" class="cats-box d-block rounded bg-white px-2 py-4">
                        <div class="text-center mb-2 mx-auto position-relative d-inline-flex align-items-center justify-content-center p-3 theme-bg-light circle"><i class="lni lni-gallery fs-lg theme-cl"></i></div>
                        <div class="cats-box-caption">
                            <h4 class="fs-md mb-0 ft-medium m-catrio">Printing & Packaging</h4>
                            <span class="text-muted">425 Jobs</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- /row -->
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="position-relative text-center">
                    <a href="allcategories.html" class="btn btn-md theme-bg-light theme-cl rounded  hover-theme">Browse All Categories<i class="lni lni-arrow-right-circle ml-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= All category ======================== -->
<section class="space min bg-light" >
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="text-muted mb-0">Top Professionals</h6>
<h2 class="ft-bold">Hire Top Professionals</h2>
</div>
</div>
</div>

<!-- row -->
<div class="row align-items-center">

                                @foreach($professionals as $pro)
                                <!-- Single -->
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
                                    <div class="row px-3 py-3  bg-white">
                                        <div class="col-xl-9 col-lg-9 text-left">
                                            <div class="d-block mb-2 position-relative">
                                                        <span class="text-muted medium"><img src="assets/img/t-8.png" class="img-fluid circle mr-2" width="40" alt="" /></i>{{$pro->firstname}} {{$pro->lastname}}</span>
                                                        <!-- <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
                                                        <span class="muted medium ml-2 theme-cl"><i class="fa fa-dot-circle  mr-1"></i>Online</span> -->
                                                    </div>
                                            <h6 class="font-weight-bold">{{$pro->designation}}</h6>
                                            <p>{{$pro->about_me}}</p>
                                            <div class="jbd-details mb-1">
                                                
                                                <ul class="p-0 skills_tag text-left">
                                                    
                                                     @foreach(@$pro->skillsprofilenew as $as)
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($as->name)}}</span></li>
                                @endforeach
                                
                                   @foreach(@$pro->skillsprofile as $as)
                                    <li><span class="px-2 py-1 medium skill-bg rounded text-dark">{{__($as->skill_id)}}</span></li>
                                @endforeach
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 ">
                                            <p ><i class="fa fa-award    mr-2"></i>{{$pro->level}}</p>
                                            
                                            <p ><i class="fa fa-dollar-sign mr-2"></i>${{$pro->per_hour_rate}}/hour</p>
                                            <p ><i class="fa fa-map mr-2"></i>{{$pro->address->city}}, {{$pro->address->country}}</p>
                                            <a href="{{route('profile', [slug($pro->username), encrypt($pro->id)])}}" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach 
                                

<!-- Single -->
<!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="job_grid border rounded ">
<div class="position-absolute ab-left"><button type="button" class="p-3 border circle d-flex align-items-center justify-content-center bg-white text-gray"><i class="lni lni-heart-filled position-absolute snackbar-wishlist"></i></button></div>
<div class="job_grid_thumbmb-2 text-center pt-4 px-3 mb-2">
    <a href="landing-page.html" class="d-inline-flex text-center m-auto circle border p-2"><img src="assets/img/t-1.png" class="img-fluid circle" width="70" alt="" /></a>
</div>
<div class="job_grid_caption text-center pb-3 px-3">
    <h4 class="mb-0 ft-medium medium"><a href="landing-page.html" class="text-dark fs-md">Patricia B. North</a></h4>
    <div class="jbl_location"><i class="lni lni-map-marker mr-1"></i><span>San Francisco</span></div>
</div>
<div class="job_grid_footer px-3">
    <ul class="p-0 skills_tag text-center">
        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
        <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
        <li><span class="px-2 py-1 medium theme-bg rounded text-light">+3 More</span></li>
    </ul>
</div>
<div class="job_grid_footer pb-4 px-3">
    <div class="df-1 text-dark ft-medium col-12 mt-3"><a href="landing-page.html" class="btn gray apply-btn rounded full-width">View Candidate<i class="lni lni-arrow-right-circle ml-1"></i></a></div>
</div>
</div>
</div> -->

</div>
<!-- row -->

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="position-relative text-center">
<a href="{{route('professionals')}}" class="btn btn-md theme-bg rounded text-light hover-theme">Browse All Professionals<i class="lni lni-arrow-right-circle ml-2"></i></a>
</div>
</div>
</div>
</div>
</section>
<!-- ======================= Candidates List ======================== -->


<!-- ============================ Our Partner Start ================================== -->
<section class="bg-cover" style="background:#0AB68B !important ;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-7 col-md-9 col-sm-12">
                <div class="sec_title position-relative text-center mb-5">
                    <h6 class="text-light mb-0">Current Openings</h6>
                    <h2 class="ft-bold text-light">We Have Worked with 10,000+ Trusted Companies</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11 col-md-12 col-sm-12">
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="cats-wrap text-left">
                            <a href="job-search-v1.html" class="cats-box rounded bg-white d-flex align-items-center px-2 py-3">
                                <div class="text-center"><img src="{{asset('assets/wiserlance/assets/img/c-9.png')}}" class="img-fluid" width="55" alt=""></div>
                                <div class="cats-box-caption px-2">
                                    <h4 class="fs-md mb-0 ft-medium">Web Designing</h4>
                                    <span class="text-muted">302 Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="cats-wrap text-left">
                            <a href="job-search-v1.html" class="cats-box rounded bg-white d-flex align-items-center px-2 py-3">
                                <div class="text-center"><img src="{{asset('assets/wiserlance/assets/img/c-10.png')}}" class="img-fluid" width="55" alt=""></div>
                                <div class="cats-box-caption px-2">
                                    <h4 class="fs-md mb-0 ft-medium">Web Designing</h4>
                                    <span class="text-muted">302 Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="cats-wrap text-left">
                            <a href="job-search-v1.html" class="cats-box rounded bg-white d-flex align-items-center px-2 py-3">
                                <div class="text-center"><img src="{{asset('assets/wiserlance/assets/img/c-11.png')}}" class="img-fluid" width="55" alt=""></div>
                                <div class="cats-box-caption px-2">
                                    <h4 class="fs-md mb-0 ft-medium">Web Designing</h4>
                                    <span class="text-muted">302 Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="cats-wrap text-left">
                            <a href="job-search-v1.html" class="cats-box rounded bg-white d-flex align-items-center px-2 py-3">
                                <div class="text-center"><img src="{{asset('assets/wiserlance/assets/img/c-2.png')}}" class="img-fluid" width="55" alt=""></div>
                                <div class="cats-box-caption px-2">
                                    <h4 class="fs-md mb-0 ft-medium">Web Designing</h4>
                                    <span class="text-muted">302 Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="cats-wrap text-left">
                            <a href="job-search-v1.html" class="cats-box rounded bg-white d-flex align-items-center px-2 py-3">
                                <div class="text-center"><img src="{{asset('assets/wiserlance/assets/img/c-16.png')}}" class="img-fluid" width="55" alt=""></div>
                                <div class="cats-box-caption px-2">
                                    <h4 class="fs-md mb-0 ft-medium">Web Designing</h4>
                                    <span class="text-muted">302 Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="cats-wrap text-left">
                            <a href="job-search-v1.html" class="cats-box rounded bg-white d-flex align-items-center px-2 py-3">
                                <div class="text-center"><img src="{{asset('assets/wiserlance/assets/img/c-4.png')}}" class="img-fluid" width="55" alt=""></div>
                                <div class="cats-box-caption px-2">
                                    <h4 class="fs-md mb-0 ft-medium">Web Designing</h4>
                                    <span class="text-muted">302 Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="cats-wrap text-left">
                            <a href="job-search-v1.html" class="cats-box rounded bg-white d-flex align-items-center px-2 py-3">
                                <div class="text-center"><img src="{{asset('assets/wiserlance/assets/img/c-3.png')}}" class="img-fluid" width="55" alt=""></div>
                                <div class="cats-box-caption px-2">
                                    <h4 class="fs-md mb-0 ft-medium">Web Designing</h4>
                                    <span class="text-muted">302 Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ht-50"></div>
</section>
<!-- ============================ Our Partner End ================================== -->
<!-- ======================= About Start ============================ -->
<section class="space bg-light">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="m-spaced">
                    <div class="position-relative">
                        <!-- <div class="mb-1"><span class="theme-bg-light theme-cl px-2 py-1 rounded">New</span></div> -->
                        <h2 class="ft-bold mb-3">Corporate Business</h2>
                        <p class="mb-2">Wiserlance facilitates business-to-business connections by registering and onboarding service providers with highly qualified personnel who have a strong commercial awareness and who give not only professional services but also a pragmatic perspective in reaching targeted business goals. 
Juggling everything internally when your company starts to expand can be challenging. By outsourcing to the right business partners, </p>
                        <p class="mb-4">you can free up time and money to concentrate on other areas of your business while still giving clients the high-caliber service they've come to expect from their favorite brands.
Every professional organisation registered on Wiserlance undergoes a thorough due diligence process before being allowed to provide services. This brings businesses peace of mind when they procure or outsource services.
 </p>
                    </div>
                    <div class="position-relative row">

                        <div class="col-lg-12 col-md-12 col-12 mt-3">
                            <a href="javascript:void(0);" class="btn btn-md theme-bg-light rounded theme-cl hover-theme">See Details<i class="lni lni-arrow-right-circle ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
                <div class="position-relative">
                    <img src="{{asset('assets/wiserlance/assets/img/b-6.jpg')}}" class="img-fluid" alt="" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= About Start ============================ -->
<!-- ======================= Candidates List ======================== -->
<!-- <section class="space min bg-light" >
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="text-muted mb-0">Top Professionals</h6>
<h2 class="ft-bold">Hire Top <span class="theme-cl">Professionals</span></h2>
</div>
</div>
</div>

<div class="row align-items-center">

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-dot-circle  mr-1"></i>Online</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-dot-circle  mr-1"></i>Offline</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-dot-circle  mr-1"></i>Online</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-dot-circle  mr-1"></i>Offline</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-dot-circle  mr-1"></i>Online</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-dot-circle  mr-1"></i>Offline</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-check mr-1"></i>Verified</span>
        <span class="muted medium ml-2 theme-cl"><i class="fa fa-dot-circle  mr-1"></i>Online</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4 service-hov">
<div class="row px-3 py-3  bg-white">
<div class="col-xl-9 col-lg-9 text-left">
    <div class="d-block mb-2 position-relative">
        <span class="text-muted medium"><img src="{{asset('assets/wiserlance/assets/img/t-8.png')}}" class="img-fluid circle mr-2" width="40" alt="" /></i>Becky Lynch</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-times mr-1"></i>UnVerified</span>
        <span class="muted medium ml-2 text-danger"><i class="fa fa-dot-circle  mr-1"></i>Offline</span>
    </div>
    <h6 class="font-weight-bold">Fresher UI/UX Designer (13 Completed Jobs)</h6>
    <p>Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Repellat eaque et laudantium aspernatur neque ducimus earum unde totam accusantium eum facilis molestias laboriosam, tempora, dolorem sapiente dicta nobis fugiat recusandae!</p>
    <div class="jbd-details mb-1">
        
        <ul class="p-0 skills_tag text-left">
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
            <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
        </ul>
    </div>
</div>
<div class="col-xl-3 col-lg-3 ">
    <p ><i class="fa fa-award    mr-2"></i>Expert</p>
    <p ><i class="fa fa-check mr-2"></i>98% Job Success</p>
    <p ><i class="fa fa-dollar-sign mr-2"></i>$200/hour</p>
    <p ><i class="fa fa-map mr-2"></i>Liverpool, London</p>
    <a href="single-professional.html" class="btn theme-bg text-light ft-medium apply-btn fs-sm rounded full-width">Hire Now<i class="lni lni-arrow-right-circle ml-1"></i></a>
</div>
</div>
</div>

</div>

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="position-relative text-center">
<a href="all-freelancers.html" class="btn btn-md bg-dark rounded text-light hover-theme">Browse All Professionals<i class="lni lni-arrow-right-circle ml-2"></i></a>
</div>
</div>
</div>
</div>
</section> -->
<!-- ======================= Candidates List ======================== -->
<!-- ======================= Customer Review ======================== -->
<section class="middle " style="background: #FFE3B3 !important;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="sec_title position-relative text-center mb-5">
                    <h6 class="text-muted mb-0">Our Reviews</h6>
                    <h2 class="ft-bold">What Our Customer Saying</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="review-slide px-3">
                    <!-- single review -->
                    <div class="single_review px-2">
                        <div class="reviews_wrap position-relative bg-white rounded py-4 px-4">
                            <div class="rw-header d-flex align-items-center justify-content-start">
                                <div class="rv-110-thumb position-relative verified-author"><img src="{{asset('assets/wiserlance/assets/img/team-3.jpg')}}" class="img-fluid circle" width="70" alt="" /></div>
                                <div class="rv-110-caption pl-3">
                                    <h4 class="ft-medium fs-md mb-0 lh-1">Alvin B. Washington</h4>
                                    <p class="p-0 m-0">Co Founder</p>
                                </div>
                            </div>
                            <div class="rw-header d-flex mt-3">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                        </div>
                    </div>
                    <!-- single review -->
                    <div class="single_review px-2">
                        <div class="reviews_wrap position-relative bg-white rounded py-4 px-4">
                            <div class="rw-header d-flex align-items-center justify-content-start">
                                <div class="rv-110-thumb"><img src="{{asset('assets/wiserlance/assets/img/team-4.jpg')}}" class="img-fluid circle" width="70" alt="" /></div>
                                <div class="rv-110-caption pl-3">
                                    <h4 class="ft-medium fs-md mb-0 lh-1">Lavera C. Clifford</h4>
                                    <p class="p-0 m-0">Team Manager</p>
                                </div>
                            </div>
                            <div class="rw-header d-flex mt-3">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                        </div>
                    </div>
                    <!-- single review -->
                    <div class="single_review px-2">
                        <div class="reviews_wrap position-relative bg-white rounded py-4 px-4">
                            <div class="rw-header d-flex align-items-center justify-content-start">
                                <div class="rv-110-thumb position-relative verified-author"><img src="{{asset('assets/wiserlance/assets/img/team-2.jpg')}}" class="img-fluid circle" width="70" alt="" /></div>
                                <div class="rv-110-caption pl-3">
                                    <h4 class="ft-medium fs-md mb-0 lh-1">Linda S. Riggs</h4>
                                    <p class="p-0 m-0">Project Manager</p>
                                </div>
                            </div>
                            <div class="rw-header d-flex mt-3">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                        </div>
                    </div>
                    <!-- single review -->
                    <div class="single_review px-2">
                        <div class="reviews_wrap position-relative bg-white rounded py-4 px-4">
                            <div class="rw-header d-flex align-items-center justify-content-start">
                                <div class="rv-110-thumb"><img src="{{asset('assets/wiserlance/assets/img/team-5.jpg')}}" class="img-fluid circle" width="70" alt="" /></div>
                                <div class="rv-110-caption pl-3">
                                    <h4 class="ft-medium fs-md mb-0 lh-1">Chris L. Hazel</h4>
                                    <p class="p-0 m-0">Web Designer</p>
                                </div>
                            </div> 
                            <div class="rw-header d-flex mt-3">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                        </div>
                    </div>
                    <!-- single review -->
                    <div class="single_review px-2">
                        <div class="reviews_wrap position-relative bg-white rounded py-4 px-4">
                            <div class="rw-header d-flex align-items-center justify-content-start">
                                <div class="rv-110-thumb position-relative verified-author"><img src="{{asset('assets/wiserlance/assets/img/team-1.jpg')}}" class="img-fluid circle" width="70" alt="" /></div>
                                <div class="rv-110-caption pl-3">
                                    <h4 class="ft-medium fs-md mb-0 lh-1">Mark Jukerberg</h4>
                                    <p class="p-0 m-0">PHP Developer</p>
                                </div>
                            </div>
                            <div class="rw-header d-flex mt-3">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= Customer Review ======================== -->

@endsection