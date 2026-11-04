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
                                                    <img src="{{getImage('assets/images/job/'.$jobBiding->job->image, imagePath()['job']['size']) }}" alt="@lang('item-banner')">
                                                </div>
                                                <div class="item-card-content">
                                                    <div class="item-card-content-top">
                                                        <div class="left">
                                                            <div class="author-thumb">
                                                                  <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $jobBiding->user->image,'profile_image') }}" alt="{{__($jobBiding->user->username)}}">
                                                            </div>
                                                            <div class="author-content">
                                                                <h5 class="name"><a href="{{route('profile', $jobBiding->user->username)}}">{{__($jobBiding->user->username)}}</a> <span class="level-text"> {{__(@$jobBiding->user->rank->level)}}</span></h5>
                                                            </div>
                                                        </div>
                                                        <div class="right d-flex flex-wrap align-items-center">
                                                            <div class="item-amount">{{$general->cur_sym}}{{showAmount($jobBiding->price)}}</div>
                                                        </div>
                                                    </div>
                                                    <h3 class="item-card-title"><a href="javascript:void(0)">{{__($jobBiding->title)}}</a></h3>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="product-desc mt-80">
                                            <div class="product-desc-content pt-0">
                                                @php echo $jobBiding->description @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 mb-30">
                                    <div class="sidebar">
                                        <div class="widget custom-widget mb-30">
                                            <h3 class="widget-title">@lang('Hire Employees')</h3>
                                            <ul class="details-list">
                                                <li><span>@lang('Biding Price') :</span> <span>{{$general->cur_sym}}{{getAmount($jobBiding->price)}}</span>
                                                </li>
                                               
                                                <li><span>@lang('Subtotal') :</span> <span>{{$general->cur_sym}}{{getAmount($jobBiding->price)}}</span>
                                                </li>
                                            </ul>
                                            <div class="total-price-area d-flex flex-wrap align-items-center justify-content-between">
                                                <div class="left">
                                                    <h4 class="title">@lang('Total') :</h4>
                                                </div>
                                                <div class="right">
                                                    <h4 class="title">{{$general->cur_sym}}<span id="paymentPrice">{{getAmount($jobBiding->price)}}</span></h4>
                                                </div>
                                            </div>
                                            <div class="widget-btn mt-20">
                                                 <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#serviceModal" class="btn--base w-100"><i class="las la-sign-in-alt"></i> @lang('Checkout')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($otherServices->isNotEmpty())
                                <div class="item-bottom-area  item-details-section pt-100">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-12">
                                            <div class="section-header">
                                                <h2 class="section-title">@lang('Other services by') {{__($jobBiding->user->username)}}</h2>
                                            </div>
                                            <div class="item-card-wrapper border-0 p-0 grid-view">
                                            @foreach($otherServices as $other)
                                                <div class="item-card">
                                                    <div class="item-card-thumb">
                                                        <img src="{{getImage('assets/images/service/'.$other->image, imagePath()['service']['size']) }}" alt="@lang('service-banner')">
                                                        @if($other->featured == 1)
                                                            <div class="item-level">@lang('Featured')</div>
                                                        @endif
                                                    </div>
                                                    <div class="item-card-content">
                                                        <div class="item-card-content-top">
                                                            <div class="left">
                                                                <div class="author-thumb">
                                                                    <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. $other->user->image,'profile_image') }}" alt="@lang('author')">
                                                                </div>
                                                                <div class="author-content">
                                                                    <h5 class="name"><a href="{{route('profile', $other->user->username)}}">{{__($other->user->username)}}</a> <span
                                                                            class="level-text">{{__(@$other->user->rank->level)}}</span></h5>
                                                                    <div class="ratings">
                                                                        <i class="fas fa-star"></i>
                                                                        <span class="rating me-2">{{__(getAmount($other->rating, 2))}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="right">
                                                                <div class="item-amount">{{__($general->cur_sym)}}{{getAmount($other->price)}}</div>
                                                            </div>
                                                        </div>
                                                        <h3 class="item-card-title"><a href="{{route('service.details', [slug($other->title), encrypt($other->id)])}}">{{__($other->title)}}</a></h3>
                                                    </div>
                                                    <div class="item-card-footer">
                                                        <div class="left">
                                                            <a href="javascript:void(0)" class="item-love me-2 loveHeartAction"
                                                             data-serviceid="{{$service->id}}"><i class="fas fa-heart"></i> <span
                                                                    class="give-love-amount">({{__($other->favorite)}})</span></a>
                                                            <a href="javascript:void(0)" class="item-like"><i class="las la-thumbs-up"></i> ({{__($other->likes)}})</a>
                                                        </div>
                                                        <div class="right">
                                                            <div class="order-btn">
                                                                <a href="{{route('user.service.booking', [slug($other->title), encrypt($other->id)])}}" class="btn--base"><i class="las la-shopping-cart"></i> @lang('Order
                                                                    Now')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                <h4 class="modal-title" id="ModalLabel">@lang('Payment for hire employees')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('user.hire.employ')}}">
                    @csrf
                        <input type="hidden" name="job_biding_id" value="{{$jobBiding->id}}">
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
