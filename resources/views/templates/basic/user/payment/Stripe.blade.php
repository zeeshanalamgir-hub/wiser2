@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="row">
    <div class="col-12">
        <h5 class="text-dgreen2"> Checkout </h5>
        <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
            <div class="card-wrapper"></div>
            <form role="form" id="payment-form" method="{{$data->method}}" action="{{$data->url}}">
                @csrf
                <input type="hidden" value="{{$data->track}}" name="track">
                <!-- Hidden Input for Stripe Token -->
                <input type="hidden" name="stripeToken" id="stripeToken">

                <!-- Card Fields -->
                <div id="cardFields">
                    <!-- Prefilled User Information -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="name" class="form-label">Name on Card</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required value="{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}">
                        </div>
                        {{-- <div class="col-md-4">
                            <label for="cardNumber" class="form-label">Card Number</label>
                            <input type="text" id="cardNumber" name="cardNumber" class="form-control" placeholder="1234 5678 9012 3456" required>
                        </div> --}}
                        <!-- Supported Card Information -->
                        {{-- <div class="col-md-4">
                            <label class="form-label">Supported Cards</label>
                            <div class="d-flex gap-2">
                                <img src="https://img.icons8.com/color/48/000000/visa.png" alt="Visa" width="40">
                                <img src="https://img.icons8.com/color/48/000000/mastercard.png" alt="Mastercard" width="40">
                                <img src="https://img.icons8.com/color/48/000000/amex.png" alt="Amex" width="40">
                                <img src="https://img.icons8.com/color/48/000000/discover.png" alt="Discover" width="40">
                            </div>
                        </div> --}}
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Card Details</label>
                            <div id="card-element" class="form-control" style="padding: 10px;"></div>
                            <div id="card-errors" class="text-danger mt-2"></div>
                        </div>

                        {{-- <div class="col-md-4">
                            <label for="cardExpiry" class="form-label">Expiry Date</label>
                            <input type="text" id="cardExpiry" name="cardExpiry" class="form-control" placeholder="MM/YY" autocomplete="off" required value="{{ old('cardExpiry') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="cardCVC" class="form-label">CVV</label>
                            <input type="text" id="cardCVC" name="cardCVC" class="form-control" placeholder="123" required value="{{ old('cardCVC') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="cardHolderName" class="form-label">Name on Card</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required value="{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}">
                        </div> --}}
                    </div>
                </div>
                <button type="submit" class="btn btn-success bg-green border-0">@lang('PAY NOW')</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/global/js/card.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    (function ($) {
        "use strict";
        const stripe = Stripe('{{ $data->stripeAcc->publishable_key }}');
        const elements = stripe.elements();

         // Stripe Card Element
         const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    '::placeholder': { color: '#aab7c4' }
                }
            }
        });

        cardElement.mount('#card-element');

        $('#payment-form').on('submit', async function (e) {
            e.preventDefault();

            // Disable button to prevent multiple clicks
            $('button[type="submit"]').prop('disabled', true);

            // Create Stripe Token
            const { token, error } = await stripe.createToken(cardElement);

            if (error) {
                $('#card-errors').text(error.message);
                $('button[type="submit"]').prop('disabled', false);
            } else {
                $('#stripeToken').val(token.id);
                this.submit(); // Submit the form
            }
        });

    })(jQuery);
</script>
@endsection