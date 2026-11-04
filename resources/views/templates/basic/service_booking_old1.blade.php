@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
<!-- ============================================================== -->
<!-- Top header  -->
<!-- ============================================================== -->
<!-- ======================= Home Banner ======================== -->
<!-- <div class="home-banner1 margin-bottom-0" style="background:#28b661 url(assets/img/banner-3.jpg) no-repeat; " data-overlay="5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12">
                <div class="banner_caption text-center mb-2">
                    <h1 class="ft-bold mb-4">Lorem, ipsum dolor sit amet consectetur, adipisicing elit. Delectus, sit eligendi minus numquam expedita, sit eligendi </h1>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- ======================= Home Banner ======================== -->
<!-- ============================ Service Details Start ================================== -->
<section class="bg-light py-5 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                @if($service->optionalImage->count() > '0')
                <div id="servicedetailcarousel" class="carousel slide mb-4" data-ride="carousel">
                    @if($service->featured == 1)
                    <div class="feature text-uppercase">featured</div>
                    @endif
                    <ol class="carousel-indicators">
                        @foreach($service->optionalImage as $index => $value)
                        <li data-target="#servicedetailcarousel" data-slide-to="{{$index}}" class="<?php echo ($index == '0') ? 'active' : ''; ?>"></li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @foreach($service->optionalImage as $key => $value)
                        <div class="carousel-item <?php echo ($index == '0') ? 'active' : ''; ?>">
                            <img class="d-block w-100 service-detail-carousel" src="{{getImage('assets/images/optionalService/'.$value->image, imagePath()['optionalService']['size']) }}" alt="@lang('item-banner')">
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <img class="d-block w-100 service-detail-carousel mb-4" src="{{getImage('assets/images/service/'.$service->image,imagePath()['service']['size'])}}" alt="@lang('Service Image')">
                @endif
                <!-- <div class="bg-white rounded px-3 py-4 mb-4">
                    <div class="jbd-01 d-flex align-items-center justify-content-between">
                        <div class="jbd-flex d-flex align-items-center justify-content-start">
                            <div class="jbd-01-thumb">
                                <img src="assets/img/t-8.png" class="img-fluid" width="90" alt="" />
                            </div>
                            <div class="jbd-01-caption pl-3">
                                <div class="tbd-title"><h4 class="mb-0 ft-medium fs-md">John K. Surber</h4></div>
                                <div class="jbl_location mb-3"><span><i class="lni lni-map-marker mr-1"></i>Faisalabad, Pakistan</span></div>
                                <div class="jbl_info01">
                                    <div class="jbl_location mb-3"><span ><i class="fa fa-star mr-1"></i>4.2</span><span class="ml-3"><i class="fa fa-user mr-1"></i>Member Since 17 Jan 2022</span><span class="medium ft-medium text-success ml-3"><i class="fa fa-check mr-1"></i>Verified</span></div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="jbd-01-right text-right ">
                            <div class="jbl_button mb-2"><a href="freelance-singls.html" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium" style="width:150px;">View Profile</a></div>
                            <div class="jbl_button"><a style="width:150px;" href="freelance-singls.html" class="btn rounded bg-white border fs-sm ft-medium">Hire Me!</a></div>
                        </div>
                    </div>
                </div> -->
                <div class="bg-white rounded mb-4">
                    <div class="jbd-01 px-3 py-4">
                        <div class="jbd-details mb-4">
                            <h5 class="ft-medium fs-md">{{__($service->title)}}</h5>
                            <p> @php echo $service->description @endphp</p>
                        </div>
                        
                        <div class="jbd-details mb-3">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h5 class="pt-3" >{{$general->cur_sym}} <span id="servicePrice"> {{showAmount($n)}}</span></h5>
                                </div>
                                <div class="col-lg-6">
                                    
                                    <select class="select me-3 selectQty form-control" id="qty">
                                        @for($i=1; $i<=50; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                    
                                </div>
                                <div class="col-lg-12">
                                    <div class="card custom-card p-0 mb-3">
                                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                            <h4 class="card-title mb-0">
                                            @lang('Extra Service')
                                            </h4>
                                            <div class="card-btn">
                                                <button type="button" class="btn--base addExtra"><i class="las la-plus"></i> @lang('Add New')</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row justify-content-center addExtraService">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <select class="select form-control me-3 selectQty" id="qty">
                                        <option value="">Select Quantity</option>
                                        @for($i=1; $i<=50; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div> -->
                            </div>
                            <!-- <div class="position-relative row">
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="mb-2 mr-4 ml-lg-0 mr-lg-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light-success theme-cl p-1 small d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check small"></i>
                                            </div>
                                            <h6 class="mb-0 ml-3 text-muted fs-sm">Strong core PHP Hands on experience.</h6>
                                        </div>
                                    </div>
                                    <div class="mb-2 mr-4 ml-lg-0 mr-lg-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light-success theme-cl p-1 small d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check small"></i>
                                            </div>
                                            <h6 class="mb-0 ml-3 text-muted fs-sm">Strong Expertise in CodeIgniter Framework .</h6>
                                        </div>
                                    </div>
                                    <div class="mb-2 mr-4 ml-lg-0 mr-lg-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light-success theme-cl p-1 small d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check small"></i>
                                            </div>
                                            <h6 class="mb-0 ml-3 text-muted fs-sm">Understanding of MVC design pattern.</h6>
                                        </div>
                                    </div>
                                    <div class="mb-2 mr-4 ml-lg-0 mr-lg-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light-success theme-cl p-1 small d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check small"></i>
                                            </div>
                                            <h6 class="mb-0 ml-3 text-muted fs-sm">Expertise in PHP, MVC Frameworks and good technology exposure of Codeigniter .</h6>
                                        </div>
                                    </div>
                                    <div class="mb-2 mr-4 ml-lg-0 mr-lg-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light-success theme-cl p-1 small d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check small"></i>
                                            </div>
                                            <h6 class="mb-0 ml-3 text-muted fs-sm">Basic understanding of front-end technologies, such as JavaScript, HTML5, and CSS3</h6>
                                        </div>
                                    </div>
                                    <div class="mb-2 mr-4 ml-lg-0 mr-lg-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light-success theme-cl p-1 small d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check small"></i>
                                            </div>
                                            <h6 class="mb-0 ml-3 text-muted fs-sm">Good knowledge of relational databases, version control tools and of developing web services.</h6>
                                        </div>
                                    </div>
                                    <div class="mb-2 mr-4 ml-lg-0 mr-lg-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light-success theme-cl p-1 small d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check small"></i>
                                            </div>
                                            <h6 class="mb-0 ml-3 text-muted fs-sm">Proficient understanding of code versioning tools, such as Git.</h6>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            
                        </div>
                        
                        
                        
                        <!--  <div class="jbd-details mb-1">
                            <h5 class="ft-medium fs-md">Tags</h5>
                            <ul class="p-0 skills_tag text-left">
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Joomla</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">WordPress</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Javascript</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">PHP</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">HTML5</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">MS SQL</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">SQL Development</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Dynamod</span></li>
                                <li><span class="px-2 py-1 medium skill-bg rounded text-dark">Database</span></li>
                            </ul>
                        </div> -->
                        <!-- Blog Comment -->
                        <!--   <div class="article_detail_wrapss single_article_wrap format-standard">
                            
                            <div class="comment-area">
                                <div class="all-comments">
                                    <h3 class="comments-title">05 Comments</h3>
                                    <div class="comment-list">
                                        <ul>
                                            <li class="article_comments_wrap">
                                                <article>
                                                    <div class="article_comments_thumb">
                                                        <img src="assets/img/user-1.jpg" alt="">
                                                    </div>
                                                    <div class="comment-details">
                                                        <div class="comment-meta">
                                                            <div class="comment-left-meta">
                                                                <h4 class="author-name">Rosalina Kelian <span class="selected"><i class="fas fa-bookmark"></i></span></h4>
                                                                <div class="comment-date">19th May 2018</div>
                                                            </div>
                                                            <div class="comment-reply">
                                                                <a href="#" class="reply"><span class="icona"><i class="ti-back-left"></i></span> Reply</a>
                                                            </div>
                                                        </div>
                                                        <div class="comment-text">
                                                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim laborumab.
                                                            perspiciatis unde omnis iste natus error.</p>
                                                        </div>
                                                    </div>
                                                </article>
                                                <ul class="children">
                                                    <li class="article_comments_wrap">
                                                        <article>
                                                            <div class="article_comments_thumb">
                                                                <img src="assets/img/user-2.jpg" alt="">
                                                            </div>
                                                            <div class="comment-details">
                                                                <div class="comment-meta">
                                                                    <div class="comment-left-meta">
                                                                        <h4 class="author-name">Rosalina Kelian</h4>
                                                                        <div class="comment-date">19th May 2018</div>
                                                                    </div>
                                                                    <div class="comment-reply">
                                                                        <a href="#" class="reply"><span class="icons"><i class="ti-back-left"></i></span> Reply</a>
                                                                    </div>
                                                                </div>
                                                                <div class="comment-text">
                                                                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim laborumab.
                                                                    perspiciatis unde omnis iste natus error.</p>
                                                                </div>
                                                            </div>
                                                        </article>
                                                        
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="article_comments_wrap">
                                                <article>
                                                    <div class="article_comments_thumb">
                                                        <img src="assets/img/user-5.jpg" alt="">
                                                    </div>
                                                    <div class="comment-details">
                                                        <div class="comment-meta">
                                                            <div class="comment-left-meta">
                                                                <h4 class="author-name">Rosalina Kelian</h4>
                                                                <div class="comment-date">19th May 2018</div>
                                                            </div>
                                                            <div class="comment-reply">
                                                                <a href="#" class="reply"><span class="icons"><i class="ti-back-left"></i></span> Reply</a>
                                                            </div>
                                                        </div>
                                                        <div class="comment-text">
                                                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim laborumab.
                                                            perspiciatis unde omnis iste natus error.</p>
                                                        </div>
                                                    </div>
                                                </article>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="comment-box submit-form">
                                    <h3 class="reply-title">Post Comment</h3>
                                    <div class="comment-form">
                                        <form action="#">
                                            <div class="row">
                                                
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <textarea name="comment" class="form-control" cols="30" rows="6" placeholder="Type your comments...."></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <a href="#" class="btn theme-bg text-white">Submit</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                        </div> -->
                    </div>
                    <!-- <div class="jbd-02 px-3 py-3 br-top">
                        <div class="jbd-02-flex d-flex align-items-center justify-content-between">
                            <div class="jbd-02-social">
                                <ul class="jbd-social">
                                    <li><i class="ti-sharethis"></i></li>
                                    <li><a href="javascript:void(0);"><i class="ti-facebook"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="ti-twitter"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="ti-linkedin"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="ti-instagram"></i></a></li>
                                </ul>
                            </div>
                            
                        </div>
                    </div> -->
                </div>
            </div>
            <!-- Sidebar -->
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <!-- Categories -->
                <div class="box-static" style="z-index: 1;">
                    <!-- Tab panes -->
                    <div class="single_widgets1 widget_category1  ">
                        <h4 class="title  py-2 px-2">@lang('Your Order')</h4>
                        <ul>
                            
                            <li><a href="#">@lang('Service Price') <span  class="newdesign">{{$general->cur_sym}}{{getAmount($n)}}</span></a></li>
                            <li><a href="#">@lang('Quantity') <span  class="newdesign" id="qunatity">1</span></a></li>
                            <li><a href="#">@lang('Additional Service Cost') <span  class="newdesign">{{$general->cur_sym}} <span id="additionalservicetotal">0</span></span></a></li>
                        <li><a href="#">@lang('Subtotal') <span id="" class="newdesign">{{$general->cur_sym}}{{getAmount($n)}}</span></a></li>
                        <li style="display:none;"><span>@lang('Extras Price') :</span>
                        <div class="order-price-tags">
                            <span>{{$general->cur_sym}}</span>
                            <span id="extraPrice">0.00</span>
                        </div>
                    </li>
                    
                    <div id="discount">
                        
                    </div>
                    <li><a href="#">@lang('Total')<span id="" class="newdesign">{{$general->cur_sym}} <span id="totalPrice"> {{getAmount($n)}}</span></span></a></li>
                    
                </ul>
                <a href="javascript:void(0)"  data-toggle="modal" data-target="#serviceModal" class="mt-2 btn rounded theme-bg text-light fs-sm ft-medium full-width">@lang('Confirm & Pay')</a>
            </div>
        </div>
    </div>
</div>
</section>
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
aria-hidden="true">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="ModalLabel">@lang('Payment You Order')</h4>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <form method="POST" action="{{route('user.service.booked')}}">
            @csrf
            <div class="modal-body">

                <input type="hidden" name="revision" value="{{$revision}}">
                <input type="hidden" name="time" value="{{$time}}">
                <input type="hidden" name="package" value="{{$package}}">
                <input type="hidden" name="serviceId" value="{{$service->id}}">
                <input type="hidden" name="extraservice" value="" id="extraId">
                <input type="hidden" name="qty" value="1" id="serviceQuantity">
                <input type="hidden" name="price" value="{{$n}}" >
                <input type="hidden" name="custommizeprice" value="" id="custommizeprice">
                <input type="hidden" name="additionalextraprice" value="" id="additionalextraprice">
                <div class="row justify-content-center addExtraService">
                                                
                                            </div>
                <div class="form-group">
                    <h5>@lang('How you want to pay')</h5>
                    <select class="form-control" name="payment">
                        <option value="wallet">{{__($general->sitename)}} @lang('wallet')</option>
                        <option value="checkout">@lang('Checkout')</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-rounded text-white" data-dismiss="modal">@lang('Close')</button>
                <button type="submit" class="btn theme-bg text-light apply-btn rounded full-width">@lang('Confirm')</button>
            </div>
        </form>
    </div>
</div>
</div>
<!-- ============================ Service Details End ================================== -->
<!-- ============================ Compare End ================================== -->
<!-- ============================ Compare End ================================== -->
<!-- ======================= Newsletter Start ============================ -->
<section class="space bg-cover" style="background:#28b661 url(assets/img/b-2.jpg) no-repeat top; " <!-- style="background:#28b661 url(https://fiverr-res.cloudinary.com/q_auto,f_auto,w_1400,dpr_1.0/v1/attachments/generic_asset/asset/50218c41d277f7d85feeaf3efb4549bd-1599072608122/bg-signup-1400-x1.png) no-repeat;" -->>
<div class="container py-5">
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="sec_title position-relative mb-5">
            <h6 class="text-muted mb-0">Subscribr Now</h6>
            <!-- <h2 class="ft-bold text-light">Get All New Job Notification</h2> -->
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
</section>
<!-- ======================= Newsletter Start ============================ -->
@endsection
@section('script')
<script type="text/javascript">
$("#qty").change(function() {

$("#qty").attr('disabled',false);
var servicePrice = $("#servicePrice").text();
// alert(servicePrice);
var qty = $("#qty").val();
var extraPrice = $("#extraPrice").text();
var total = (parseFloat(servicePrice * qty) + parseFloat(extraPrice));
$("#totalPrice").text(`${parseFloat(total.toFixed(8))}`);
$("#paymentPrice").text(`${parseFloat(total.toFixed(8))}`);
$("#qunatity").text(`${qty}`);
$("#serviceQuantity").val(qty);

});
</script>
<script type="text/javascript">
$('.addExtra').on('click', function () {
var html = `
<div class="col-lg-12 extraServiceRemove">
<div class="row">
<div class="col-xl-9 col-lg-12 form-group">
    <input type="text" name="extra_title[]" class="form-control" placeholder="@lang("Enter title")" required>
</div>
<div class="col-xl-2 col-lg-12 form-group">
    <div class="input-group mb-3"> 
        <input type="text" class="form-control extraamountmulti" name="extra_price[]" placeholder="@lang('Enter Price')" required="">
        <span class="input-group-text" id="basic-addon2">{{__($general->cur_text)}}</span>
    </div>
</div>
<div class="col-xl-1">
    <button type="button" class="btn btn--danger text-white border--rounded btn-sm mt-1 removeBtn">
    <i class="fa fa-times"></i>
    </button>
</div>
</div>
</div>`;
$('.addExtraService').append(html);
});
$(document).on('click', '.removeBtn', function () {
$(this).closest('.extraServiceRemove').remove();
});
</script>
 <script type="text/javascript">

    $(document).on('keyup','.extraamountmulti',function(){
    var abcd = 0;
   
        $('.extraamountmulti').each(function(key, value){
            // console.log(key);
            // console.log(value);
            var $this = $(this);
            var thisval = $this.val();
            console.log(thisval);
            abcd += parseFloat(thisval);
        });
    var extratotal = (parseFloat(abcd));
    $("#additionalextraprice").val(parseFloat(abcd));
    var alltotalPrice = $("#totalPrice").text();
    console.log(extratotal);
    $('#additionalservicetotal').text(extratotal);
    var totalll = (parseFloat(alltotalPrice) + parseFloat(extratotal));
$("#totalPrice").text(`${parseFloat(totalll.toFixed(8))}`);
    });

        </script>
@endsection