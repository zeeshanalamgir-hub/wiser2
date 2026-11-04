@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="row">
        <div class="col-12">
            <h5 class="text-dgreen2 mt-3">Checkout</h5>
            <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                <div class="row">
                    <!-- Left: Payment Method -->
                    <div class="col-sm-6">
                        <h6 class="mb-3">Your selected deposit money method</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" checked>
                            <label class="form-check-label">
                                {{ $data?->gatewayCurrency()?->name }}
                            </label>
                        </div>
                    </div>

                    <!-- Right: Payment Details -->
                    <div class="col-sm-6">
                        <div class="mt-1">
                            <p class="float-end">{{ __($general?->cur_sym) }}{{ getAmount($data?->amount) }}</p>
                            <p>Amount</p>
                        </div>

                        <hr class="mt-1">
                        <div class="mt-3">
                            <p class="float-end pb-0">{{ __($general?->cur_sym) }}{{ getAmount($data?->charge) }}</p>
                            <p>Processing charges</p>
                        </div>

                        <hr class="mt-1">
                        <div class="mt-1">
                            <p class="float-end pb-0">{{ __($general?->cur_sym) }}{{ getAmount($data?->amount + $data?->charge) }}</p>
                            <p>Payable</p>
                        </div>

                        <hr class="mt-1">
                        <div class="w-100 clearfix">
                            <p class="fw-normal text-secondary fs-12 mb-0 float-end">
                                Conversion rate: 1 {{ __($general?->cur_sym) }} = {{ getAmount($data?->rate) }} {{ __($data?->baseCurrency()) }}
                            </p>
                        </div>

                        <div class="fw-bold mt-1">
                            <p class="float-end pb-0">@lang('In') {{ $data?->baseCurrency() }}:</span>
                                {{ getAmount($data?->final_amo) }}
                            </p>
                            <p class="mt-0">Total</p>
                        </div>

                        @if($data?->gateway?->crypto == 1)
                            <p class="fw-normal text-secondary fs-14 mt-2">
                                Conversion with {{ __($data?->method_currency) }} and final value will show on next step
                            </p>
                        @endif
                        <span>
                            @if($data?->method_code < 1000)
                                <a href="{{ route('user.deposit.confirm') }}" class="btn btn-success bg-green border-0">
                                    @lang('Pay Now')
                                </a>
                            @else
                                <a href="{{ route('user.deposit.manual.confirm') }}" class="btn btn-success bg-green border-0">
                                    @lang('Pay Now')
                                </a>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
