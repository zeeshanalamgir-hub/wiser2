@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.seller_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="deposit-area">
                        <div class="row justify-content-center mb-30-none">
                            @foreach($gatewayCurrency as $currency)
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                                    <div class="deposit-item">
                                        <div class="deposit-item-header section--bg text-white text-center">
                                            <span class="title"><i class="lab la-paypal"></i> {{__($currency->name)}}</span>
                                        </div>
                                        <div class="deposit-item-body">
                                            <div class="deposit-thumb">
                                                <img src="{{$currency->methodImage()}}" alt="{{__($currency->name)}}">
                                            </div>
                                        </div>
                                        <div class="deposit-item-footer bg--base">
                                            <div class="deposit-btn text-center">
                                                <form action="{{route('user.payment.insert')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="booking_number" value="{{session()->get('booking')}}">
                                                    <input type="hidden" name="currency" value="{{$currency->currency}}">
                                                    <input type="hidden" name="method_code" value="{{$currency->method_code}}">
                                                    <button type="submit" class="btn btn--base text-white btn-block btn-icon icon-left"><i class="las la-money-bill"></i> @lang('PayNow')</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection