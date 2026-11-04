@extends($activeTemplate . 'layouts.wiserlance.layout')
@php
    $helpers = new \App\Classes\Helpers();
    $currency_rates = $helpers->get_currency_rates();
    $currencies = $helpers->get_all_currencies();
    // echo "<pre>";print_r($currency_rates);echo "</pre>";exit;
    $rates = DB::table('currency_rates')->orderBy('id', 'DESC')->first();

    $currencies = json_decode($rates->rates);

    $currency = strtoupper(Session::get('currency'));
    $currency = 'USD';
    $rates = $currencies->$currency;
@endphp

@section('content')
    <div class="row p-3 p-md-0 pb-5">
        <div class="col-12 p-md-4 pb-md-2">
            <!--Breadcrumbs-->
            <p class="mb-0 mt-3 text-3gray">
                @if ($user->is_employer)
                    <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                @else
                    <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                @endif
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a href="/service" class="text-3gray" style="text-decoration:none;">Services</a>
                <i class="fa-solid fa-chevron-right fs-12"></i> Order Now
            </p>
            <!--Breadcrumbs End-->
        </div>
        
        <!-- Right Section-->
        <div class="col-lg-4 order-second order-lg-last">
            <form method="POST" action="{{ route('user.service.booking.confirm') }}">
                @csrf
                <!-- <input type="hidden" name="coupon" id="formCoupon" value=""> -->
                <input type="hidden" name="discount" id="formDiscount" value="0">
                <input type="hidden" name="addon_ids[]" id="addon_ids">
                <input type="hidden" id="serviceQuantity" value="1">
                <input type="hidden" id="servicePriceInput"
                    value="{{ number_format($helpers->convert_to_currency('USD', @$serviceDetails->price), 2, '.', '') }}">
                <input type="hidden" name="service_detail_id" value="{{ encrypt($serviceDetails->id) }}">
                <input type="hidden" name="converted_amount" id="convertedAmount" value="">
                <input type="hidden" name="extra_services_data" id="extra_services_data" value="">

                <div class="bg-white p-4 shadow-sm border fs-14 rounded mb-3">
                    <!-- Title and Price -->
                    <div class="w-100 fw-semibold fs-5 text-dgreen2">
                        {{ $serviceDetails->title }}
                        <span class="float-end">
                            {{ Session::get('currency_symbol') }}
                            {{ number_format($helpers->convert_to_currency(Session::get('currency'), @$serviceDetails->price), 2) }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="w-100 mt-1">
                        {{ $serviceDetails->desc }}
                    </div>

                    <!-- Delivery & Revisions -->
                    <div class="w-100 fw-semibold mt-1"><i class="fas fa-clock fs-10"></i> Delivery
                        <span class="float-end">{{ $serviceDetails->delivery_time }} Days</span>
                    </div>
                    <div class="w-100 fw-semibold mt-1"><i class="fas fa-sync-alt fs-10"></i> Revisions
                        <span class="float-end">{{ $serviceDetails->revision }}</span>
                    </div>

                    <!-- Dynamic Feature Tiers -->
                    <h6 class="mt-3 fw-bold text-dgreen">Features</h6>
                    @foreach ($availableFeatures as $feature)
                        @php
                            $selected =
                                $userFeatures[$serviceDetails->id]->firstWhere('service_tier_id', $feature->id) ?? null;
                        @endphp

                        @if ($feature->type === 'select' || $feature->type === 'input')
                            <div class="w-100 mt-1">
                                {{ $feature->name }}
                                <span class="float-end">{{ $selected->service_value_tier ?? '-' }}</span>
                            </div>
                        @elseif($feature->type === 'checkbox')
                            @if ($selected && $selected->service_value_tier === 'yes')
                                <div class="w-100 mt-1">
                                    {{ $feature->name }}
                                    <i class="fas fa-check text-green fw-bold float-end"></i>
                                </div>
                            @endif
                        @endif
                    @endforeach

                    <!-- Price Section -->
                    <div class="w-100 fw-semibold mt-1">Service Price
                        <span class="float-end">{{ Session::get('currency_symbol') }}
                            {{ number_format($helpers->convert_to_currency(Session::get('currency'), @$serviceDetails->price), 2) }}</span>
                    </div>

                    <!--Select Quantity -->
                    <div class="w-100 fw-semibold mt-1">
                        Select Quantity
                        <div class="input-group float-end" style="width: 100px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary minus">-</button>
                            <input type="text" class="form-control text-center qtyinput fw-bold fs-14" id="qty"
                                value="1" name="quantity" style="padding: 0;">
                            <button type="button" class="btn btn-sm btn-outline-secondary plus">+</button>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="w-100 fw-semibold mt-3">
                        Quantity
                        <span class="float-end" id="qunatity">1</span>
                    </div>

                    <!-- Subtotal and Cost -->
                    <div class="w-100 fw-semibold mt-1">Subtotal <span class="float-end Subtotal">0</span></div>
                    <input type="hidden" name="Subtotal" class="SubtotalHidden">
                    {{-- Extra Services --}}
                    <div class="w-100 mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dgreen">Extra Services</h6>
                        <ul class="list-unstyled" id="extra-services-list">
                            <li class="text-muted">No extra services added</li>
                        </ul>
                        <div class="w-100 fw-semibold mt-2 d-none" id="extra-services-total-row">
                            Extra Services Total <span class="float-end" id="extra-services-total-amount">$0.00</span>
                        </div>
                    </div>

                    <div class="w-100 mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dgreen">Selected Add-ons</h6>
                        <ul class="list-unstyled" id="selected-addons-list">
                            <li class="text-muted">No add-ons selected</li>
                        </ul>
                    </div>

                    <div class="w-100 fw-semibold mt-1">Service Fee <span class="float-end">{{ $platformFee }}% (<span id="platformFeeAmount">$0.00</span>)</span>
                        <input type="hidden" name="serviceFee" value="{{ $platformFee }}">
                    </div>
                    <div class="w-100 fw-semibold mt-1">Total <span class="float-end" id="totalPrice">0</span></div>

                    <!-- Payment Fee Calculation -->
                    <div class="w-100 mt-3 pt-3 border-top">
                        {{--  <h6 class="fw-bold text-dgreen">Payment Method Fee</h6>
                        <div class="w-100 fw-semibold mt-1">Payment Processing Fee
                            <span class="float-end" id="payment-fee-amount">$0.00</span>
                        </div>  --}}
                        <div class="w-100 fw-bold mt-2 fs-5">Total to Pay
                            <span class="float-end" id="total-with-fee">$0.00</span>
                        </div>
                    </div>

                    <!-- Coupon Input -->
                    <div class="w-100" id="discount">
                        <label for="couponCode" class="form-label"></label>
                        <input type="text" class="form-control is-valid fs-14" name="coupon" id="couponCode"
                            value="Coupon code" required>
                    </div>

                    <!-- Confirm Button -->
                    <div class="w-100 mt-3">
                        <button class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold" id="confirm-btn">
                            Confirm & Pay
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!--Left Section---xxxxxxxxxxxxxxx-->
        <div class="col-lg-8 mt-3 mt-lg-0" id="contents">
            <!-- Main Box 1 -->
            <div class="px-3 py-2 shadow-sm border rounded-2 bg-white">
                <div class="row d-flex py-2">
                    <div class="col-md-9">
                        <!-- Service Title -->
                        <p class="mb-2 fw-bold ps-md-4 ms-md-5">
                            {{ $service->title }}
                        </p>

                        <div class="d-flex">
                            <!-- User Avatar -->
                            <div>
                                <img src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, null) }}"
                                    alt="@lang('Profile Image')" class="rounded-circle me-2" width="50" height="50"
                                    style="border-radius:50% !important;object-fit:cover;">
                            </div>

                            <!-- User Info -->
                            <div class="ms-3 ms-md-2">
                                <p class="m-0 fs-18 fw-bold text-capitalize">{{ $service->user->username }}</p>

                                @php
                                    $rate = DB::table('review_ratings')
                                        ->where('pro_id', $service->user->id)
                                        ->avg('rating');
                                    $rate = round($rate, 0);
                                @endphp

                                <p class="m-0 text-secondary fs-14">
                                    <i class="fas fa-star fs-12 text-warning"></i> {{ $rate }}
                                    <i class="fas fa-map-marker-alt ms-2"></i> {{ @$service->user->address->country }}
                                    @if ($service->user->status == 1)
                                        <span class="text-green ms-2">Verified <i class="fas fa-check fs-12"></i></span>
                                    @else
                                        <span class="text-danger ms-2">UnVerified <i
                                                class="fas fa-times fs-12"></i></span>
                                    @endif
                                </p>

                                <span class="me-2 text-secondary fs-14">
                                    <i class="fas fa-user fs-12"></i>
                                    Member Since {{ showDateTime($service->user->created_at, 'd M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Buttons -->
                    <div class="col-md-3 col d-flex flex-column pt-3">
                        <a href="{{ route('profile', [slug($service->user->username), encrypt($service->user->id)]) }}"
                            class="btn btn-sm btn-success bg-dgreen2 mb-2 fw-semibold">View Profile</a>
                        <a href="{{ route('user', ['id' => $service->user_id]) }}"
                            class="btn btn-sm btn-dark fw-semibold">Contact Me</a>
                    </div>
                </div>
            </div>

            <!--Main Box 2-->
            <div class="px-3 py-2 mt-3 shadow-sm border border-2 rounded-2 bg-white">
                <h5 class="fw-bold fs-18 p-2 text-dgreen">Upgrade your order with extras</h5>
                @if ($mergedAddons->isNotEmpty())
                    @foreach ($mergedAddons as $addon)
                        <div class="px-4 py-3 mb-3 bg-lgreen3 rounded-2">
                            <input type="checkbox" name="selected_addons[]" value="{{ $addon->id }}"
                                class="form-check-input float-end addon-checkbox" data-price="{{ $addon->price }}"
                                data-days="{{ $addon->delivery_time }}">
                            <p class="mb-1 fw-bold fs-16 text-dgreen2">
                                {{ $addon->addon_name }}
                                @if ($addon->delivery_time)
                                    <span class="fw-normal">(+{{ $addon->delivery_time }}
                                        day{{ $addon->delivery_time > 1 ? 's' : '' }})</span>
                                @endif
                            </p>
                            <p class="m-0 fw-semibold fs-18">
                                {{ Session::get('currency_symbol') }}{{ number_format($addon->price, 2) }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <div class="px-4 py-3 mb-3 bg-light rounded-2 text-center text-muted">
                        No add-ons available for this service.
                    </div>
                @endif
            </div>

            <!-- Deposit Box -->
            <div class="px-3 py-3 mt-3 shadow-sm border rounded-2 bg-white">
                <button id="addMoreBtn"
                    class="btn btn-sm btn-success bg-dgreen2 border-0 text-white fw-semibold float-end">
                    Add More
                </button>

                <h5 class="fw-bold fs-18 text-dgreen">Extra Service</h5>

                <!-- Wrapper -->
                <div id="extraServiceWrapper">
                    <!-- Single Row -->
                    <div class="row py-3 bg-white extra-service-row">
                        <div class="col-12 col-md-7 mt-1">
                            <input type="text" class="form-control fs-14 extra-service-title"
                                placeholder="Enter title">
                        </div>
                        <div class="col-8 col-md-4 mt-1">
                            <input type="text" class="form-control fs-14 extra-service-price" placeholder="Price">
                        </div>
                        <div class="col-4 col-md-1 mt-1">
                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                <i class="fas fa-multiply fs-12"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize prices on page load
            updatePrices();
            updateExtraServicesList();

            $('.minus').click(function() {
                var $input = $(this).parent().find('.qtyinput');
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                $input.val(count);
                $input.change();
                return false;
            });

            $('.plus').click(function() {
                var $input = $(this).parent().find('.qtyinput');
                $input.val(parseInt($input.val()) + 1);
                $input.change();
                return false;
            });

            // Manual quantity input change
            $('.qtyinput').change(function() {
                updatePrices();
            });

            // Add-on checkbox change handler
            $('.addon-checkbox').change(function() {
                // Update selected add-ons list
                let selectedAddonsList = $('#selected-addons-list');
                selectedAddonsList.empty();

                let selectedAddons = $('.addon-checkbox:checked');
                if (selectedAddons.length > 0) {
                    selectedAddons.each(function() {
                        const addonName = $(this).closest('.rounded-2').find('p.fw-bold').text()
                            .trim();
                        const addonPrice = parseFloat($(this).data('price')).toFixed(2);
                        const addonDays = $(this).data('days');
                        selectedAddonsList.append(`
                        <li>
                            <span class="fas fa-check text-green fw-bold float-end"></span> ${addonName} 
                            <small class="text-muted">(+${addonDays} day${addonDays > 1 ? 's' : ''}, $${addonPrice})</small>
                        </li>
                    `);
                    });
                } else {
                    selectedAddonsList.append('<li class="text-muted">No add-ons selected</li>');
                }

                updatePrices();
            });

            // Add More button for extra services
            $('#addMoreBtn').on('click', function() {
                const wrapper = $('#extraServiceWrapper');
                const row = $(`
                    <div class="row py-3 bg-white extra-service-row">
                        <div class="col-12 col-md-7 mt-1">
                            <input type="text" class="form-control fs-14 extra-service-title" placeholder="Enter title">
                        </div>
                        <div class="col-8 col-md-4 mt-1">
                            <input type="text" class="form-control fs-14 extra-service-price" placeholder="Price">
                        </div>
                        <div class="col-4 col-md-1 mt-1">
                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                <i class="fas fa-multiply fs-12"></i>
                            </button>
                        </div>
                    </div>
                `);
                wrapper.append(row);
                updateExtraServicesList();
            });

            // Remove extra service row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.extra-service-row').remove();
                updateExtraServicesList();
                updatePrices();
            });

            // Update extra service list when price or title changes
            $(document).on('input', '.extra-service-title, .extra-service-price', function() {
                updateExtraServicesList();
                updatePrices();
            });

            // Update extra services list in sidebar
            function updateExtraServicesList() {
                const extraServicesList = $('#extra-services-list');
                const extraServicesTotalRow = $('#extra-services-total-row');
                const extraServicesTotalAmount = $('#extra-services-total-amount');

                extraServicesList.empty();

                let hasValidServices = false;
                let extraServicesTotal = 0;

                $('.extra-service-row').each(function() {
                    const title = $(this).find('.extra-service-title').val().trim();
                    const price = $(this).find('.extra-service-price').val().trim();

                    if (title && price && !isNaN(parseFloat(price)) && parseFloat(price) > 0) {
                        hasValidServices = true;
                        const priceValue = parseFloat(price);
                        extraServicesTotal += priceValue;

                        extraServicesList.append(`
                            <li>
                                <span class="fas fa-check text-green fw-bold float-end"></span> ${title}
                                <small class="text-muted">($${priceValue.toFixed(2)})</small>
                            </li>
                        `);
                    }
                });

                if (hasValidServices && extraServicesTotal > 0) {
                    extraServicesTotalRow.removeClass('d-none');
                    extraServicesTotalAmount.text('$' + extraServicesTotal.toFixed(2));
                } else {
                    extraServicesTotalRow.addClass('d-none');
                    extraServicesList.append('<li class="text-muted">No extra services added</li>');
                }
            }

            // Function to update all prices
            function updatePrices() {
                var qty = parseInt($('#qty').val());
                var servicePrice = parseFloat($('#servicePrice-input').val());
                var addonsTotal = 0;
                var extraServicesTotal = 0;

                // Calculate addons total from checked checkboxes
                $('.addon-checkbox:checked').each(function() {
                    addonsTotal += parseFloat($(this).data('price')) || 0;
                });

                // Calculate extra services total
                $('.extra-service-price').each(function() {
                    const price = $(this).val().trim();
                    if (price && !isNaN(parseFloat(price)) && parseFloat(price) > 0) {
                        extraServicesTotal += parseFloat(price);
                    }
                });

                // Calculate subtotal (service price * quantity)
                var subtotal = servicePrice * qty;

                // Calculate service fee (platform fee % of subtotal + addons + extra services)
                var percentageValue = parseFloat('{{ $platformFee }}') || 0;
                var serviceFee = (subtotal + addonsTotal + extraServicesTotal) * (percentageValue / 100);

                $('#platformFeeAmount').text('$' + serviceFee.toFixed(2));

                // Calculate total (subtotal + addons + extra services + service fee)
                var total = subtotal + addonsTotal + extraServicesTotal + serviceFee;

                // Get payment method fees
                const selectedMethod = $('input[name="payment_method"]:checked');
                const fixedCharge = parseFloat(selectedMethod?.data('fix_charge')) || 0;
                const percentCharge = parseFloat(selectedMethod?.data('percent_charge')) || 0;

                // Calculate payment fees
                const paymentFee = fixedCharge + (total * percentCharge / 100);
                const totalWithFee = total + paymentFee;

                // Update displayed values
                $('#qunatity').text(qty);
                $('#serviceQuantity').val(qty);
                $('.Subtotal').text(subtotal.toFixed(2));
                $('.SubtotalHidden').val(subtotal.toFixed(2));
                $('#totalPrice').text(total.toFixed(2));

                // Update payment fee display
                $('#payment-fee-amount').text('$' + paymentFee.toFixed(2));
                $('#total-with-fee').text('$' + totalWithFee.toFixed(2));

                // Store converted amount for form submission
                $('#convertedAmount').val(totalWithFee.toFixed(2));
            }

            $('#confirm-btn').on('click', function(e) {
                // Collect addon IDs
                let addonIds = [];
                $('.addon-checkbox:checked').each(function() {
                    addonIds.push($(this).val());
                });
                $('#addon_ids').val(JSON.stringify(addonIds));

                // Collect extra services data
                let extraServices = [];
                $('.extra-service-row').each(function() {
                    const title = $(this).find('.extra-service-title').val().trim();
                    const price = $(this).find('.extra-service-price').val().trim();

                    if (title && price && !isNaN(parseFloat(price)) && parseFloat(price) > 0) {
                        extraServices.push({
                            title: title,
                            price: parseFloat(price).toFixed(2)
                        });
                    }
                });

                // Store extra services data in hidden input
                $('#extra_services_data').val(JSON.stringify(extraServices));

                // Optionally pass coupon value and discount
                $('#formCoupon').val($('#couponCode').val());


            });

            // Payment method change handler (if exists in your layout)
            $('.payment-method').change(function() {
                updatePrices();
            });

            // Initialize
            updatePrices();
            updateExtraServicesList();
        });
    </script>
@endsection