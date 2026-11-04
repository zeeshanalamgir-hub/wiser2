@extends($activeTemplate.'layouts.frontend')
@section('content')
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
                                                    <img src="{{getImage('assets/images/software/'.$software->image, imagePath()['software']['size']) }}" alt="@lang('Software Image')">
                                                </div>
                                                <div class="item-card-content">
                                                    <div class="item-card-content-top">
                                                        <div class="left">
                                                            <div class="author-thumb">
                                                                  <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $software->user->image,'profile_image') }}" alt="{{__($software->user->username)}}">
                                                            </div>
                                                            <div class="author-content">
                                                                <h5 class="name"><a href="{{route('profile', $software->user->username)}}">{{__($software->user->username)}}</a> <span class="level-text"> {{__(@$software->user->rank->label)}}</span></h5>
                                                                <div class="ratings">
                                                                    <i class="fas fa-star"></i>
                                                                    <span class="rating me-2">{{__($software->rating)}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="right d-flex flex-wrap align-items-center">
                                                            <div class="item-amount">{{$general->cur_sym}}{{showAmount($software->amount)}}</div>
                                                        </div>
                                                    </div>
                                                    <h3 class="item-card-title"><a href="{{route('software.details', [slug($software->title), encrypt($software->id)])}}">{{__($software->title)}}</a></h3>
                                                </div>
                                                <div class="item-card-footer">
                                                    <div class="left">
                                                        <a href="javascript:void(0)" class="item-love me-2 loveHeartActionSoftware" data-softwareid="{{$software->id}}"><i class="fas fa-heart"></i> <span
                                                                class="give-love-amount">({{$software->favorite}})</span></a>
                                                        <a href="javascript:void(0)" class="item-like"><i class="las la-thumbs-up"></i> ({{$software->likes}})</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="product-desc mt-80">
                                            <div class="product-desc-content pt-0">
                                                @php echo $software->description @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 mb-30">
                                    <div class="sidebar">
                                        <div class="widget custom-widget mb-30">
                                            <h3 class="widget-title">@lang('Order Summary')</h3>
                                            <ul class="details-list">
                                                <li><span>@lang('Software Price') :</span> <span>{{$general->cur_sym}}{{getAmount($software->amount)}}</span>
                                                </li>
                                               
                                                <li><span>@lang('Subtotal') :</span> <span>{{$general->cur_sym}}{{getAmount($software->amount)}}</span>
                                                </li>

                                                <div id="discount">
                                                    
                                                </div>

                                            </ul>
                                            <div class="total-price-area d-flex flex-wrap align-items-center justify-content-between">
                                                <div class="left">
                                                    <h4 class="title">@lang('Total') :</h4>
                                                </div>
                                                <div class="right">
                                                    <h4 class="title">{{$general->cur_sym}}<span id="paymentPrice">{{getAmount($software->amount)}}</span></h4>
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
                <div class="modal-body">
                    <form method="POST" action="{{route('user.software.buy.store')}}">
                        @csrf
                            <input type="hidden" name="software_id" value="{{$software->id}}">
                            <div class="form-group">
                                <h5>@lang('How you want to pay')</h5>
                                <select class="form-control" name="payment">
                                    <option value="wallet">{{__($general->sitename)}} @lang('wallet')</option>
                                    <option value="checkout">@lang('Checkout')</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn--base" style="width:100%;">@lang('Confirm')</button>
                            </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    'use strict';
    $("#couponApply").on("click", function(){
        var couponCode = $("#couponCode").val();
        var softwareId = {{$software->id}};
        if(couponCode)
        {
            $.ajax({
                type:"GET",
                url:"{{route('user.software.coupon.apply')}}",
                data : {couponCode : couponCode, softwareId: softwareId}, 
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
</script>
@endpush