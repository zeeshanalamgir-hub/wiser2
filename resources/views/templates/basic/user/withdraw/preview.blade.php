@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')

@section('content')
    <section class="all-sections ptb-60">
        <div id="main-content">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-dgreen2"> @lang('Checkout') </h5>
                    <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                        <div class="row">
                            <!-- Left side: Method -->
                            <div class="col-sm-6">
                                <h6 class="mb-3">@lang('Your selected withdrawal method')</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" checked>
                                    <label class="form-check-label">
                                        {{ __($data->method->name) }}
                                    </label>
                                </div>
                            </div>

                            <!-- Right side: Preview details -->
                            <div class="col-sm-6">
                                <div class="mt-1">
                                    <p class="float-end">${{ showAmount($data->final_amount) }} </p>
                                    <p>@lang('Amount')</p>
                                </div>

                                <hr class="mt-1">
                                <div class="mt-3">
                                    <p class="float-end pb-0">
                                        ${{ showAmount($data->charge) }}
                                    </p>
                                    <p>@lang('Platform charges')</p>
                                </div>

                                <hr class="mt-1">
                                <div class="mt-1">
                                    <p class="float-end pb-0">
                                        ${{ showAmount($data->amount) }}
                                    </p>
                                    <p>@lang('Payable')</p>
                                </div>

                                <hr class="mt-1">
                                <div class="w-100 clearfix">
                                    <p class="fw-normal text-secondary fs-12 mb-0 float-end">
                                        @lang('Conversion rate'):
                                        1 {{ __($data->currency) }} = 1 $
                                    </p>
                                </div>

                                <div class="fw-bold mt-1">
                                    <p class="float-end pb-0">
                                        ${{ showAmount($data->amount) }}
                                    </p>
                                    <p class="mt-0">@lang('Total')</p>
                                </div>
                                @if ($data->method_id == 1 || $data->method_id == '1')
                                    <form method="POST" action="{{ route('user.withdraw.submit') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success bg-green border-0">
                                            @lang('Withdraw Now')
                                        </button>
                                    </form>
                                @elseif($data->method_id == 2 || $data->method_id == '2')
                                    <form method="GET" action="{{ route('user.seller.paypal.payment') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success bg-green border-0">
                                            @lang('Withdraw Now')
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
