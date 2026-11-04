@extends($activeTemplate.'layouts.frontend')
@section('content')
<style>
.cust{
    display:flex;
} 


</style>
<section class="all-sections pt-60">
    <div class="container-fluid p-max-sm-0">
        <div class="sections-wrapper d-flex flex-wrap justify-content-center">
            <article class="main-section">
                <div class="section-inner">
                    <div class="item-section">
                        <div class="container">
                            <div class="row justify-content-center mb-30-none">
                                <div class="col-xl-9 col-lg-9 mb-30">
                                    <div class="item-details-area">
                                        <div class="item-card-wrapper border-0 p-0 list-view">
                                            <div class="item-card">
                                                <div class="item-card-thumb">
                                                    <img src="{{getImage('assets/images/service/'.$service->image, imagePath()['service']['size']) }}" alt="@lang('item-banner')">
                                                    @if($service->featured == 1)
                                                        <div class="item-level">@lang('Featured')</div>
                                                    @endif
                                                </div>
                                                <div class="item-card-content">
                                                    <div class="item-card-content-top">
                                                        <div class="left">
                                                            <div class="author-thumb">
                                                                  <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $service->user->image,'profile_image') }}" alt="{{__($service->user->username)}}">
                                                            </div>
                                                            <div class="author-content">
                                                                <h5 class="name"><a href="{{route('profile', $service->user->username)}}">{{__($service->user->username)}}</a> <span class="level-text"> {{__(@$service->user->rank->level)}}</span></h5>
                                                                <div class="ratings">
                                                                    <i class="fas fa-star"></i>
                                                                    <span class="rating me-2">{{__($service->rating)}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="right d-flex flex-wrap align-items-center">
                                                            <select class="select me-3 selectQty" id="qty">
                                                                @for($i=1; $i<=50; $i++)
                                                                    <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                            <div class="item-amount">{{$general->cur_sym}}{{showAmount($service->price)}}</div>
                                                        </div>
                                                    </div>
                                                    <h3 class="item-card-title"><a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}">{{__($service->title)}}</a></h3>
                                                </div>
                                                <div class="item-card-footer">
                                                    <div class="left">
                                                        <a href="javascript:void(0)" class="item-love me-2 loveHeartAction" data-serviceid="{{$service->id}}"><i class="fas fa-heart"></i> <span
                                                                class="give-love-amount">({{$service->favorite}})</span></a>
                                                        <a href="javascript:void(0)" class="item-like"><i class="las la-thumbs-up"></i> ({{$service->likes}})</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    @if($service->extraService->isNotEmpty())
                                        <div class="service-card mt-60">
                                            <div class="service-card-header bg--gray text-center">
                                                <h4 class="title">@lang('Extra Service')</h4>
                                            </div>
                                            <div class="service-card-body">
                                                <div class="service-card-form">
                                                    @foreach($service->extraService as $key => $extra)
                                                        <div class="form-row">
                                                            <div class="left">
                                                                <div class="form-group custom-check-group">
                                                                    <input type="checkbox" id="{{$key}}" data-key="{{$key}}" data-id="{{$extra->id}}" value="{{getAmount($extra->price)}}" class="extraService">
                                                                    <label for="{{$key}}">{{__($extra->title)}}</label>
                                                                </div>
                                                            </div>
                                                            <div class="right">
                                                                <span class="value">{{$general->cur_sym}}{{getAmount($extra->price)}}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($service->customization==1)
                                        <div class="service-card mt-60">
                                            <div class="service-card-header bg--gray text-center">
                                                <h4 class="title">Customization</h4>
                                            </div>
                                            <div class="service-card-body">
                                                <div class="service-card-form">
                                                    
                                                        <div class="form-row">
                                                            <div class="left">
                                                                <div class="form-group ">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                          <input type="checkbox" class="customization">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                             <label for="">Customization</label>
                                                                        </div>
                                                                          <div class="col-md-6">
                                                                              <div class="showinput"></div>
                                                                        </div>
                                                                    </div>
                                                                   
                                                                   
                                                                   
                                                                </div>
                                                                
                                                            </div>
                                                           
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="product-desc mt-40">-->
                                        <!--    <div class="section-header">-->
                                        <!--        <h2 class="section-title">Customization</h2>-->
                                        <!--    </div>-->
                                        <!--    <div class="product-desc-content pt-0">-->
                                        <!--     <input type="radio" class="customization">-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                    @endif
                                    
                                        <div class="product-desc mt-80">
                                            <div class="section-header">
                                                <h2 class="section-title">@lang('Service Description')</h2>
                                            </div>
                                            <div class="product-desc-content pt-0">
                                                @php echo $service->description @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 mb-30">
                                    <div class="sidebar">
                                        <div class="widget custom-widget mb-30">
                                            <h3 class="widget-title">@lang('Your Order')</h3>
                                            <ul class="details-list">
                                                <li><span>@lang('Service Price') :</span> 
                                                    <div class="order-price-tags">
                                                        <span>{{$general->cur_sym}}</span>
                                                        <span id="servicePrice">{{getAmount($service->price)}}</span>
                                                    </div>
                                                </li>
                                                <li><span>@lang('Extras Price') :</span>
                                                    <div class="order-price-tags">
                                                        <span>{{$general->cur_sym}}</span>
                                                        <span id="extraPrice">0.00</span>
                                                    </div>
                                                </li>
                                                <li><span>@lang('Quantity') :</span> 
                                                    <span id="qunatity">1</span>
                                                </li>
                                                
                                                 <li class="showfield" ><span>@lang('Customization') :</span> 
                                                   <span id="customizationvalue"></span>
                                                </li>
                                                <li><span>@lang('Subtotal') :</span>
                                                    <div class="order-price-tags">
                                                        <span>{{$general->cur_sym}}</span>
                                                        <span id="totalPrice">{{getAmount($service->price)}}</span>
                                                    </div>
                                                </li>

                                                <div id="discount">
                                                    
                                                </div>

                                            </ul>
                                            <div class="total-price-area d-flex flex-wrap align-items-center justify-content-between">
                                                <div class="left">
                                                    <h4 class="title">@lang('Total') :</h4>
                                                </div>
                                                <div class="right">
                                                    <h4 class="title">{{$general->cur_sym}}<span id="paymentPrice">{{getAmount($service->price)}}</span></h4>
                                                </div>
                                            </div>
                                            @if($coupon)
                                                <form class="coupon-form mt-20">
                                                    <input type="text" class="form-control" id="couponCode" placeholder="@lang('Coupon Code')">
                                                    <button type="button" class="coupon-form-btn" id="couponApply"><i class="las la-angle-right"></i></button>
                                                </form>
                                            @endif
                                            <div class="widget-btn mt-20">
                                                 <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#serviceModal" class="btn--base w-100"><i class="las la-sign-in-alt"></i> @lang('Checkout')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>
@include($activeTemplate.'partials.end_ad')
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Payment You Order')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" action="{{route('user.service.booked')}}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="serviceId" value="{{$service->id}}">
                    <input type="hidden" name="extraservice" value="" id="extraId">
                    <input type="hidden" name="qty" value="1" id="serviceQuantity">
                    <input type="hidden" name="custommizeprice" value="" id="custommizeprice">
                    <div class="form-group">
                        <h5>@lang('How you want to pay')</h5>
                        <select class="form-control" name="payment">
                            <option value="wallet">{{__($general->sitename)}} @lang('wallet')</option>
                            <option value="checkout">@lang('Checkout')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--base btn-rounded text-white">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>


    'use strict';
    (function($){
        var extraTotal = 0;
        var extraId = [];
        var discount = 0;
        
$('.customization').on('click',function(){
   
    $('.showinput').empty();
   $('.showinput').append(`
   <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="getamount" placeholder="Customize Amount" name="customizeamount">
                                              <button class="input-group-text add" id="basic-addon2">Add</button>
                                    </div>`);
       var serviceSubtotal = $("#totalPrice").text();
                var serviceTotal = $("#paymentPrice").text();
        $(".add").on("click", function(){
            var amount=$('#getamount').val();
            $('#customizationvalue').empty();
            $('#custommizeprice').val(amount);
            $('#customizationvalue').text(`$`+amount);
            
                var subTotal =  parseFloat(serviceSubtotal) + parseFloat(amount);
                var total =  parseFloat(serviceTotal) + parseFloat(amount);
                $("#totalPrice").text(`${parseFloat(subTotal.toFixed(8))}`);
                $("#paymentPrice").text(`${parseFloat(total.toFixed(8))}`);
        });
});

        $("#couponApply").on("click", function(){
            var couponCode = $("#couponCode").val();
            var serviceId = {{$service->id}};
            var qty = $("#serviceQuantity").val();
            if(couponCode)
            {
                $.ajax({
                    type:"GET",
                    url:"{{route('user.service.coupon.apply')}}",
                    data : {couponCode : couponCode, serviceId: serviceId, qty : qty}, 
                    success:function(response){
                        if(response.error){
                            notify('error', response.error)
                        }else{
                            notify('success',response.success);
                            $("#discount").html(`
                                <li>
                                  <span>Discount (${response.code})</span>
                                  <span>{{$general->cur_sym}}${parseFloat(response.amount.toFixed(8))}</span>
                                </li>
                                `)
                            discount = 1;
                            $("#qty").attr("disabled", true);
                            var discountAmount = response.amount;
                            var totalPrice = $("#paymentPrice").text();
                            var final = parseFloat(totalPrice) - parseFloat(discountAmount);
                            $("#paymentPrice").text(`${parseFloat(final.toFixed(8))}`);
                        }
                    }
                });
            }
            else{
                notify('error', "Please Enter Coupon Code");
            }
        });


        $("#qty").change(function() {
            if(discount != 1)
            {
                $("#qty").attr('disabled',false);
                var servicePrice = $("#servicePrice").text();
                var qty = $("#qty").val();
                var extraPrice = $("#extraPrice").text();
                var total = (parseFloat(servicePrice * qty) + parseFloat(extraPrice));
                $("#totalPrice").text(`${parseFloat(total.toFixed(8))}`);
                $("#paymentPrice").text(`${parseFloat(total.toFixed(8))}`);
                $("#qunatity").text(`${qty}`);
                $("#serviceQuantity").val(qty);
            }else
            {
                notify('error', 'The coupon has already been applied');
            }
        });
        
        $(".extraService").change(function() {
            $('.extraService').is(":checked");
            var key = $(this).data("key");
            
            var extraPrice = $(this).val();
            if ($(`#${key}`).is(":checked"))
            {       
                extraTotal = parseFloat(extraTotal) + parseFloat(extraPrice);
                var serviceSubtotal = $("#totalPrice").text();
                var serviceTotal = $("#paymentPrice").text();
                var subTotal =  parseFloat(serviceSubtotal) + parseFloat(extraPrice);
                var total =  parseFloat(serviceTotal) + parseFloat(extraPrice);
                $("#totalPrice").text(`${parseFloat(subTotal.toFixed(8))}`);
                $("#paymentPrice").text(`${parseFloat(total.toFixed(8))}`);
                var eId = $(this).data("id");
                extraId.push(eId);
            }
            else
            {
                extraTotal = parseFloat(extraTotal) - parseFloat(extraPrice);
                var serviceSubtotal = $("#totalPrice").text();
                var serviceTotal = $("#paymentPrice").text();
                var subTotal = parseFloat(serviceSubtotal) - parseFloat(extraPrice);
                var total = parseFloat(serviceTotal) - parseFloat(extraPrice);
                $("#totalPrice").text(`${parseFloat(subTotal.toFixed(8))}`);
                $("#paymentPrice").text(`${parseFloat(total.toFixed(8))}`);

                var eId = $(this).data("id");
                extraId.splice($.inArray(eId, extraId), 1);
            }
            $("#extraPrice").text(`${extraTotal.toFixed(2)}`)
            $("#extraId").val(extraId);
        });
    })(jQuery)
</script>
@endpush