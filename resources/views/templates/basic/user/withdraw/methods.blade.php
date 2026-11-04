@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="dashboard-content">
        <div class="row">
            <div class="col-12 mt-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="text-dgreen2 ms-2">@lang('Withdraw Money')</h5>
                    </div>
                    <div>
                        @if ($onBoarding?->response['url'] && !$isOnboardingComplete)
                            <a href="javascript:void(0)" class="text-green" id="completeOnboardingBtn">
                                @lang('Complete Onboarding')
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm mt-1 p-4" style="border: solid 1px #d2d2d2">
                    <div class="row">
                        <!-- Left side: withdrawal method -->
                        <div class="col-sm-6">
                            <h6 class="mb-3">@lang('Select Withdrawal Method')</h6>
                            @if ($withdrawMethod->isEmpty())
                                <p class="text-danger">@lang('No withdrawal methods available')</p>
                            @else
                                @foreach ($withdrawMethod as $index => $data)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input withdrawMethod" type="radio" name="withdraw_method"
                                               id="withdraw{{ $data->id }}" data-id="{{ $data->id }}"
                                               data-name="{{ $data->name }}" data-currency="$"
                                               data-method_code="{{ $data->id }}"
                                               data-min_amount="{{ getAmount($data->min_limit) }}"
                                               data-max_amount="{{ getAmount($data->max_limit) }}"
                                               data-base_symbol="$"
                                               data-fix_charge="{{ getAmount($data->fixed_charge) }}"
                                               data-percent_charge="{{ getAmount($data->percent_charge) }}"
                                               @if ($index === 0) checked @endif>
                                        <label class="form-check-label" for="withdraw{{ $data->id }}">
                                           @if($data?->name == 'Stripe' || $data?->name == 'stripe')
                                                <i class="fa-brands fa-cc-stripe"></i>
                                            @else($data?->name == 'Paypal' || $data?->name == 'paypal')
                                                <i class="fa-brands fa-paypal"></i>
                                            @endif
                                            {{ $data->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Right side: withdrawal form -->
                        <div class="col-sm-6">
                            <form method="POST" action="{{ route('user.withdraw.money') }}" id="withdrawForm">
                                @csrf
                                <input type="hidden" name="currency" class="edit-currency">
                                <input type="hidden" name="method_code" class="edit-method-code">

                                <div class="row align-items-center mb-2">
                                    <label class="col-sm-4 col-form-label">@lang('Amount')</label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-1">
                                            <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                            <input type="number" id="amount" name="amount"
                                                   class="form-control withdrawAmount" placeholder="@lang('Enter amount')"
                                                   min="0" step="0.01" value="">
                                        </div>
                                        <small class="text-danger d-none error-amount"></small>
                                    </div>
                                </div>

                                <hr class="mb-1">

                                <div class="mt-1">
                                    <p class="float-end pb-0 withdrawCharge"></p>
                                    <p>@lang('Platform charges')</p>
                                </div>

                                <hr class="mt-1">
                                <div class="fw-bold mt-1">
                                    <p class="float-end pb-0 totalAmount">0</p>
                                    <p>@lang('Total')</p>
                                </div>
                                {{-- @if($onBoarding?->response['url'] && !$isOnboardingComplete) --}}
                                @if($isOnboardingComplete)
                                    <button type="submit" class="btn btn-success bg-green border-0" id="withdrawButton">
                                        @lang('Confirm Withdraw')
                                    </button>
                                @endif
                            </form>
                        </div>
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

            function updateWithdrawInfo(selectedInput) {
                const currency = selectedInput.data('currency');
                const method_code = selectedInput.data('method_code');
                const minAmount = parseFloat(selectedInput.data('min_amount')) || 0;
                const maxAmount = parseFloat(selectedInput.data('max_amount')) || Infinity;
                const fixCharge = parseFloat(selectedInput.data('fix_charge')) || 0;
                const percentCharge = parseFloat(selectedInput.data('percent_charge')) || 0;
                const baseSymbol = selectedInput.data('base_symbol');

                $('.withdrawLimit').text(`${minAmount.toFixed(2)} - ${maxAmount.toFixed(2)} ${baseSymbol}`);
                const withdrawCharge = `${fixCharge.toFixed(2)} ${baseSymbol}${percentCharge > 0 ? ` + ${percentCharge}%` : ''}`;
                $('.withdrawCharge').text(withdrawCharge);

                const amountInput = parseFloat($('.withdrawAmount').val());
                const errorElement = $('.error-amount');
                const withdrawButton = $('#withdrawButton');

                if (!isNaN(amountInput)) {
                    if (amountInput <= 0) {
                        errorElement.text('Amount must be greater than 0');
                        errorElement.removeClass('d-none');
                        withdrawButton.prop('disabled', true);
                    } else if (amountInput < minAmount) {
                        errorElement.text(`Minimum withdrawal amount is ${minAmount.toFixed(2)} ${baseSymbol}`);
                        errorElement.removeClass('d-none');
                        withdrawButton.prop('disabled', true);
                    } else if (amountInput > maxAmount) {
                        errorElement.text(`Maximum withdrawal amount is ${maxAmount.toFixed(2)} ${baseSymbol}`);
                        errorElement.removeClass('d-none');
                        withdrawButton.prop('disabled', true);
                    } else {
                        errorElement.addClass('d-none');
                        withdrawButton.prop('disabled', false);
                    }

                    const percentFee = (amountInput * percentCharge) / 100;
                    let total = amountInput - (fixCharge + percentFee);
                    total = Math.max(0, total);
                    $('.totalAmount').text(`${total.toFixed(2)} ${baseSymbol}`);
                } else {
                    errorElement.addClass('d-none');
                    withdrawButton.prop('disabled', true);
                    $('.totalAmount').text(`0.00 ${baseSymbol}`);
                }

                $('.edit-currency').val(currency);
                $('.edit-method-code').val(method_code);
            }

            $('input[name="withdraw_method"]').on('change', function() {
                updateWithdrawInfo($(this));
            });

            $('.withdrawAmount').on('input', function() {
                updateWithdrawInfo($('input[name="withdraw_method"]:checked'));
            });

            $('#withdrawForm').on('submit', function(e) {
                const selectedMethod = $('input[name="withdraw_method"]:checked');
                const amountInput = parseFloat($('.withdrawAmount').val());
                const minAmount = parseFloat(selectedMethod.data('min_amount')) || 0;
                const maxAmount = parseFloat(selectedMethod.data('max_amount')) || Infinity;
                const baseSymbol = selectedMethod.data('base_symbol');

                if (isNaN(amountInput)) {
                    e.preventDefault();
                    $('.error-amount').text('Please enter a valid amount');
                    $('.error-amount').removeClass('d-none');
                } else if (amountInput <= 0) {
                    e.preventDefault();
                    $('.error-amount').text('Amount must be greater than 0');
                    $('.error-amount').removeClass('d-none');
                } else if (amountInput < minAmount) {
                    e.preventDefault();
                    $('.error-amount').text(`Minimum withdrawal amount is ${minAmount.toFixed(2)} ${baseSymbol}`);
                    $('.error-amount').removeClass('d-none');
                } else if (amountInput > maxAmount) {
                    e.preventDefault();
                    $('.error-amount').text(`Maximum withdrawal amount is ${maxAmount.toFixed(2)} ${baseSymbol}`);
                    $('.error-amount').removeClass('d-none');
                }
            });

            if ($('input[name="withdraw_method"]:checked').length) {
                updateWithdrawInfo($('input[name="withdraw_method"]:checked'));
            }

            $('input[name="withdraw_method"]').first().data({
                fix_charge: "{{ $withdrawMethod[0]->fixed_charge ?? 20 }}",
                percent_charge: "{{ $withdrawMethod[0]->percent_charge ?? 10 }}",
                min_amount: "{{ $withdrawMethod[0]->min_limit ?? 50 }}",
                max_amount: "{{ $withdrawMethod[0]->max_limit ?? 1000 }}"
            });

            // Handle onboarding button click
            $('#completeOnboardingBtn')?.click(function(e) {
                e.preventDefault();
                // Show loading state
                const btn = $(this);
                const originalText = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> Generating new link...');
                btn.prop('disabled', true);

                // Get fresh onboarding link
                $.ajax({
                    url: '{{ route("user.get.fresh.onboarding.link") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.url) {
                            // Redirect to onboarding URL in the same tab
                            window.location.href = response.url;
                        } else {
                            alert('Failed to generate new onboarding link. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON?.message || 'Failed to generate new link'));
                    },
                    complete: function() {
                        btn.html(originalText);
                        btn.prop('disabled', false);
                    }
                });
            });

        })(jQuery);
    </script>

    <style>
        #completeOnboardingBtn {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #completeOnboardingBtn:hover {
            text-decoration: underline;
        }
    </style>
@endsection
