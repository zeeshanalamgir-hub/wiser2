@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.buyer_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                        <div class="card custom--card">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                <h4 class="card-title mb-0">
                                   @lang('Payment Preview')
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                                    </div>
                                    <div class="col-md-8">
                                        <form action="{{$data->url}}" method="{{$data->method}}">
                                            <ul class="list-group mt-5">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <h4>@lang('Final Amount')</h4>
                                                    <h4>{{getAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</h4>
                                                </li>

                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                  <h4>@lang('To Get')</h4>
                                                    <h4>{{getAmount($deposit->amount)}}  {{__($general->cur_text)}}</h4>
                                                </li>
                                            </ul>
                                            <script src="{{$data->src}}"
                                                class="stripe-button"
                                                @foreach($data->val as $key=> $value)
                                                data-{{$key}}="{{$value}}"
                                                @endforeach
                                            >
                                            </script>
                                        </form>
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
@push('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').addClass("mt-5 btn--success btn-round custom-success text-center btn-lg");
        })(jQuery);
    </script>
@endpush
