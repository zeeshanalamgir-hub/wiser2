@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')

    <p class=" text-bc fs-14 pt-2"><a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
        <i class="fa-solid fa-house fs-14"></i></a>
    <i class="fa-solid fa-chevron-right fs-12"></i> Deposit Money
    </p> <!--Breadcrumbs End-->
    <div class="row">
        <div class="col-12">
            <h5 class="text-dgreen2 ms-2"> Deposit Money </h5>
            <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-3">Select the deposit money method</h6>
                        @foreach ($gatewayCurrency as $index => $data)
                            <div class="form-check">
                                <!-- Stripe selected by default -->
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="flexRadioDefault"
                                    id="gateway{{ $data->id }}"
                                    data-id="{{ $data->id }}"
                                    data-name="{{ $data->name }}"
                                    data-currency="{{ $data->currency }}"
                                    data-method_code="{{ $data->method_code }}"
                                    data-min_amount="{{ getAmount($data->min_amount) }}"
                                    data-max_amount="{{ getAmount($data->max_amount) }}"
                                    data-base_symbol="{{ $data->baseSymbol() }}"
                                    data-fix_charge="{{ getAmount($data->fixed_charge) }}"
                                    data-percent_charge="{{ getAmount($data->percent_charge) }}"
                                    @if($index === 0) checked @endif>
                                <label class="form-check-label" for="gateway{{ $data->id }}">
                                    <i class="fa-brands fa-cc-stripe"></i> {{ __($data->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-sm-6">
                        <form method="POST" action="{{ route('user.deposit.inserttwo') }}">
                            @csrf
                            <input type="hidden" name="currency" class="edit-currency">
                            <input type="hidden" name="method_code" class="edit-method-code">
                            <div class="row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Amount</label>
                                <div class="col-sm-8">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="amount" min="0" name="amount" class="form-control depositAmount" aria-label="Amount (to the nearest dollar)">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-1">
                            {{--  <div class="mt-1">
                                <p class="float-end pb-0 depositLimit"></p>
                                <p>Limit</p>
                            </div>  --}}

                            <div class="mt-1">
                                <p class="float-end pb-0 depositCharge">$0.00</p>
                                <p>Processing charges</p>
                            </div>

                            <hr class="mt-1">
                            <div class="fw-bold mt-1">
                                <p class="float-end pb-0 totalAmount">$0.00</p>
                                <p>Total</p>
                            </div>
                            <p class="fw-normal text-secondary fs-14"> Conversion with USD and final value will show on next step</p>
                            <span><button class="btn btn-success bg-green border-0"> Deposit Money</button></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">
    (function($) {
        "use strict";

        function updateDepositInfo(selectedInput) {
    // Retrieve data attributes from the selected radio input
    const name = selectedInput.data('name');
    const currency = selectedInput.data('currency');
    const method_code = selectedInput.data('method_code');
    const minAmount = parseFloat(selectedInput.data('min_amount'));
    const maxAmount = parseFloat(selectedInput.data('max_amount'));
    const fixCharge = parseFloat(selectedInput.data('fix_charge'));
    const percentCharge = parseFloat(selectedInput.data('percent_charge'));
    const baseSymbol = selectedInput.data('base_symbol');

    // Update limit and charges display
    const depositLimit = `Limit: ${minAmount} - ${maxAmount} ${baseSymbol}`;
    $('.depositLimit').text(depositLimit);

    // Display percent first, then fixed
    const depositCharge = `${percentCharge > 0 ? percentCharge + '%' : ''}`;
    $('.depositCharge').text(depositCharge);

    // Update total calculation: percent first, then fixed
    const amountInput = parseFloat($('.depositAmount').val()) || 0;
    const percentFee = (amountInput * percentCharge) / 100;
    const total = amountInput + percentFee; // percentFee pehle add, phir fixCharge

    $('.totalAmount').text(`${total.toFixed(2)} ${baseSymbol}`);

    // Update hidden input fields
    $('.edit-currency').val(currency);
    $('.edit-method-code').val(method_code);
}

        // Trigger change event when radio button changes
        $('input[name="flexRadioDefault"]').on('change', function() {
            const selectedInput = $(this);
            updateDepositInfo(selectedInput);
        });

        // Trigger calculation when amount changes
        $('.depositAmount').on('input', function() {
            const selectedInput = $('input[name="flexRadioDefault"]:checked');
            updateDepositInfo(selectedInput);
        });

        // Initialize with the default selected option (Stripe by default)
        const defaultSelectedInput = $('input[name="flexRadioDefault"]:checked');
        updateDepositInfo(defaultSelectedInput);

    })(jQuery);
</script>
@endsection

