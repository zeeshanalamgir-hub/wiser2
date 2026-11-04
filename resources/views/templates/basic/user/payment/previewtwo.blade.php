@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="row">
        <div class="col-12">
            <h5 class="text-dgreen2 mt-3"> Checkout </h5>
            <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-3">Your selected deposit money method</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"
                                checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                {{ $data->gatewayCurrency()->name }}
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="mt-1">
                            <p class="float-end"> {{ __($general->cur_sym) }}{{ getAmount($data->amount) }}</p>
                            <p>Amount</p>
                        </div>

                        <hr class="mt-1">
                        <div class="mt-3">
                            <p class="float-end pb-0">{{ __($general->cur_sym) }}{{ getAmount($data->charge) }}</p>
                            <p>Processing charges</p>
                        </div>
                        <hr class="mt-1">
                        <div class="mt-1">
                            <p class="float-end pb-0">{{ $general->cur_sym }}{{ getAmount($data->amount + $data->charge) }}
                            </p>
                            <p>Payable</p>
                        </div>

                        <hr class="mt-1">
                        <div class="w-100 clearfix">
                            <p class="fw-normal text-secondary fs-12 mb-0 float-end"> Conversion rate: 1
                                {{ __($general->cur_sym) }} = {{ getAmount($data->rate) }} {{ __($data->baseCurrency()) }}
                            </p>
                        </div>
                        <div class="fw-bold mt-1">
                            <p class="float-end pb-0">@lang('In') {{ $data->baseCurrency() }}:</span>
                                {{ getAmount($data->final_amo) }}</p>
                            <p class="mt-0">Total</p>
                        </div>
                        <span>
                            {{--  <button type="button" id="payNow" class="btn btn-success bg-green border-0">
                                @lang('Pay Now')</button>  --}}
                            <a type="button" href="{{route('user.deposit.confirm')}}" class="btn btn-success bg-green border-0"> @lang('Pay Now')</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#payNow').on('click', function() {
                $('#payNow').attr('disabled', true);
                $('#payNow').text('Processing...');
                $.ajax({
                    url: '{{ route('user.deposit.confirm') }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.url) {
                            openStripePopup(response.url);
                            $('#payNow').removeAttr('disabled');
                            $('#payNow').text('Pay Now');
                        } else {
                            $('#payNow').removeAttr('disabled');
                            $('#payNow').text('Pay Now');
                        }
                    }
                })
            });
        });

        function openStripePopup(url) {
            var width = 500;
            var height = 700;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;

            window.open(
                url,
                "StripeCheckout",
                `width=${width},height=${height},top=${top},left=${left}`
            );
        }
        window.addEventListener("message", function(event) {
            if (event.origin !== window.location.origin) return;

            if (event.data.status === "success") {
                $.ajax({
                    url: '{{ route('user.buyer.savetransaction') }}',
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    data: {
                        data: event.data
                    },
                    success: function(response) {
                        if(response.status) {
                            window.location.href = "{{ route('user.buyer.dashboard') }}";
                        }
                    }
                });
            }
            if (event.data.status === "failed") {
                console.log("Payment failed: " + event.data.message);
            }
            if (event.data.status === "cancelled") {
                console.log("Payment cancelled");
            }
        });
    </script>
@endsection
