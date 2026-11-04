@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                 @if(auth()->user()->is_freelancer==1)
                 @include($activeTemplate . 'partials.seller_sidebar')
                @else
                @include($activeTemplate . 'partials.buyer_sidebar')
                @endif
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="deposit-area deposit-preview-area">
                        <div class="row justify-content-center">
                            <div class="col-xl-8 col-lg-8">
                                <div class="deposit-item">
                                    <div class="deposit-item-header section--bg text-white text-center">
                                        <span class="title"><i class="lab la-paypal"></i> {{ $data->gatewayCurrency()->name }}</span>
                                    </div>
                                    <div class="deposit-item-body">
                                        <div class="deposit-thumb-area">
                                            <div class="deposit-thumb">
                                                <img src="{{ $data->gatewayCurrency()->methodImage() }}" alt="@lang('payment')">
                                            </div>
                                        </div>
                                        <div class="deposit-content text-center">
                                            <ul class="deposit-list">
                                                <li>@lang('Amount'): <span>{{getAmount($data->amount)}}</span> {{__($general->cur_text)}}</li>
                                                <li>@lang('Charge'): <span>{{getAmount($data->charge)}}</span> {{__($general->cur_text)}}</li>
                                                <li>@lang('Payable'): <span>{{getAmount($data->amount + $data->charge)}}
                                                </span> {{$general->cur_text}}</li>
                                                <li> @lang('Conversion Rate'): <strong>1 {{__($general->cur_text)}} = {{getAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong>
                                                </li>
                                                <li>@lang('In') {{$data->baseCurrency()}}:
                                                    <strong>{{getAmount($data->final_amo)}}</strong>
                                                </li>
                                                @if($data->gateway->crypto==1)
                                                    <li>
                                                        @lang('Conversion with')
                                                        <b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="deposit-item-footer bg--base">
                                        <div class="deposit-btn text-center">
                                            @if( 1000 >$data->method_code)
                                                <a href="{{route('user.deposit.confirm')}}" class="btn btn--base text-white btn-block btn-icon icon-left"><i class="las la-money-bill"></i>@lang('Pay Now')</a>
                                            @else
                                                <a href="{{route('user.deposit.manual.confirm')}}" class="btn btn--base text-white btn-block btn-icon icon-left">@lang('Pay Now')</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


