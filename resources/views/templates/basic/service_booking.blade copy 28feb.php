@extends($activeTemplate . 'layouts.wiserlance.layout')
@php
    $helpers = new \App\Classes\Helpers();
    $currency_rates = $helpers->get_currency_rates();
    $currencies = $helpers->get_all_currencies();
    $rates = DB::table('currency_rates')->orderBy('id', 'DESC')->first();

    if ($rates) {
        $currencies = json_decode($rates->rates);
        $currency = strtoupper(Session::get('currency', 'USD'));
        $rates = $currencies->$currency ?? 1;
    } else {
        $currencies = null;
        $rates = 1;
    }

    // Convert service price
    $servicePriceUSD = number_format((float)$serviceDetails->price, 2, '.', '');
    $convertedPrice = $helpers->convert_to_currency(Session::get('currency', 'USD'), $servicePriceUSD);
    $displayPrice = number_format($convertedPrice, 2);
    $symbol = Session::get('currency_symbol', '$');
    
    // Platform fee
    $platformFee = (float)($platformFee ?? 4.00);
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
            
            <form method="POST" action="{{ route('user.service.booking.confirm') }}" id="bookingForm">
                @csrf
                <input type="hidden" name="discount" id="formDiscount">
                <input type="hidden" name="coupan" id="formCoupan">
                <input type="hidden" name="addon_ids[]" id="addon_ids">
                <input type="hidden" id="serviceQuantity" value="1">
                <input type="hidden" id="servicePriceInput" value="{{ $servicePriceUSD }}">
                <input type="hidden" id="convertedServicePrice" value="{{ $convertedPrice }}">
                <input type="hidden" name="service_detail_id" value="{{ encrypt($serviceDetails->id) }}">
                <input type="hidden" name="converted_amount" id="convertedAmount" value="0">
                <input type="hidden" name="extra_services_data" id="extra_services_data" value="">

                <div class="bg-white p-4 shadow-sm border fs-14 rounded mb-3">
                    <!-- Title and Price -->
                    <div class="w-100 fw-semibold fs-5 text-dgreen2">
                        {{ $serviceDetails->title }}
                        <span class="float-end">
                            {{ $symbol }}{{ $displayPrice }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="w-100 mt-1">
                        {{ Str::limit($serviceDetails->desc, 100) }}
                    </div>

                    <!-- Delivery & Revisions -->
                    <div class="w-100 fw-semibold mt-1">
                        <i class="fas fa-clock fs-10"></i> Delivery
                        <span class="float-end">{{ $serviceDetails->delivery_time }} Days</span>
                    </div>
                    <div class="w-100 fw-semibold mt-1">
                        <i class="fas fa-sync-alt fs-10"></i> Revisions
                        <span class="float-end">{{ $serviceDetails->revision }}</span>
                    </div>

                    <!-- Dynamic Feature Tiers -->
                    <h6 class="mt-3 fw-bold text-dgreen">Features</h6>
                    @foreach ($availableFeatures as $feature)
                        @php
                            $selected = null;
                            if(isset($userFeatures) && count($userFeatures) > 0) {
                                $selected = $userFeatures[$serviceDetails?->id]->firstWhere('service_tier_id', $feature->id) ?? null;
                            }
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
                        <span class="float-end" id="displayServicePrice">
                            {{ $symbol }}{{ $displayPrice }}
                        </span>
                    </div>

                    <!-- Select Quantity -->
                    <div class="w-100 fw-semibold mt-1">
                        Select Quantity
                        <div class="input-group float-end" style="width: 100px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary quantity-minus">-</button>
                            <input type="text" class="form-control text-center quantity-input fw-bold fs-14" 
                                   id="quantity" value="1" name="quantity" style="padding: 0;">
                            <button type="button" class="btn btn-sm btn-outline-secondary quantity-plus">+</button>
                        </div>
                    </div>

                    <!-- Quantity Display -->
                    <div class="w-100 fw-semibold mt-3">
                        Quantity
                        <span class="float-end" id="quantityDisplay">1</span>
                    </div>

                    <!-- Subtotal -->
                    <div class="w-100 fw-semibold mt-1">
                        Subtotal 
                        <span class="float-end" id="subtotalAmount">
                            {{ $symbol }}0.00
                        </span>
                    </div>
                    <input type="hidden" name="subtotal" id="subtotalHidden" value="0">

                    {{-- Extra Services --}}
                    <div class="w-100 mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dgreen">Extra Services</h6>
                        <ul class="list-unstyled" id="extra-services-list">
                            <li class="text-muted">No extra services added</li>
                        </ul>
                        <div class="w-100 fw-semibold mt-2 d-none" id="extra-services-total-row">
                            Extra Services Total 
                            <span class="float-end" id="extra-services-total-amount">{{ $symbol }}0.00</span>
                        </div>
                    </div>

                    <div class="w-100 mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dgreen">Selected Add-ons</h6>
                        <ul class="list-unstyled" id="selected-addons-list">
                            <li class="text-muted">No add-ons selected</li>
                        </ul>
                        <div class="w-100 fw-semibold mt-2 d-none" id="addons-total-row">
                            Add-ons Total 
                            <span class="float-end" id="addons-total-amount">{{ $symbol }}0.00</span>
                        </div>
                    </div>

                    <div class="w-100 fw-semibold mt-1">
                        Service Fee 
                        <span class="float-end">
                            {{ $platformFee }}% 
                            (<span id="platformFeeAmount">{{ $symbol }}0.00</span>)
                        </span>
                        <input type="hidden" name="serviceFee" value="{{ $platformFee }}">
                        <input type="hidden" name="serviceFeeAmount" id="serviceFeeAmount" value="0">
                    </div>

                    <div class="w-100 fw-semibold mt-1">
                        Total 
                        <span class="float-end" id="totalPrice">{{ $symbol }}0.00</span>
                    </div>
                    <input type="hidden" name="total" id="totalHidden" value="0">

                    <!-- Payment Fee Calculation -->
                    <div class="w-100 mt-3 pt-3 border-top">
                        {{-- Uncomment if you have payment method fees --}}
                        {{-- <h6 class="fw-bold text-dgreen">Payment Method Fee</h6>
                        <div class="w-100 fw-semibold mt-1">Payment Processing Fee
                            <span class="float-end" id="payment-fee-amount">{{ $symbol }}0.00</span>
                        </div> --}}
                        <div class="w-100 fw-bold mt-2 fs-5">Total to Pay
                            <span class="float-end" id="total-with-fee">{{ $symbol }}0.00</span>
                        </div>
                    </div>

                    <!-- Coupon Input -->
                    <div class="w-100 mt-3" id="discount">
                        <label for="couponCode" class="form-label">Coupon Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control fs-14" name="coupon" id="couponCode" 
                                   placeholder="Enter coupon code">
                            <button type="button" class="btn btn-outline-secondary" id="applyCoupon">Apply</button>
                        </div>
                        <div class="invalid-feedback" id="couponError"></div>
                        <div class="valid-feedback" style="color:#00C535" id="couponSuccess"></div>
                    </div>

                    <!-- Confirm Button -->
                    <div class="w-100 mt-3">
                        <button type="submit" class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold" 
                                id="confirm-btn">
                            Confirm & Pay
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Left Section -->
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
                                        <span class="text-danger ms-2">UnVerified <i class="fas fa-times fs-12"></i></span>
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

            <!-- Main Box 2 - Add-ons -->
            <div class="px-3 py-2 mt-3 shadow-sm border border-2 rounded-2 bg-white">
                <h5 class="fw-bold fs-18 p-2 text-dgreen">Upgrade your order with extras</h5>
                @if ($mergedAddons->isNotEmpty())
                    @foreach ($mergedAddons as $addon)
                        @php
                            $addonPrice = $helpers->convert_to_currency(Session::get('currency', 'USD'), $addon->price);
                            $addonDisplayPrice = number_format($addonPrice, 2);
                        @endphp
                        <div class="px-4 py-3 mb-3 bg-lgreen3 rounded-2">
                            <input type="checkbox" name="selected_addons[]" value="{{ $addon->id }}"
                                class="form-check-input float-end addon-checkbox" 
                                data-price="{{ $addonPrice }}"
                                data-display-price="{{ $addonDisplayPrice }}"
                                data-days="{{ $addon->delivery_time }}">
                            <p class="mb-1 fw-bold fs-16 text-dgreen2">
                                {{ $addon->addon_name }}
                                @if ($addon->delivery_time)
                                    <span class="fw-normal">(+{{ $addon->delivery_time }}
                                        day{{ $addon->delivery_time > 1 ? 's' : '' }})</span>
                                @endif
                            </p>
                            <p class="m-0 fw-semibold fs-18">
                                {{ $symbol }}{{ $addonDisplayPrice }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <div class="px-4 py-3 mb-3 bg-light rounded-2 text-center text-muted">
                        No add-ons available for this service.
                    </div>
                @endif
            </div>

            <!-- Extra Services Box -->
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
                                placeholder="Enter title" required>
                        </div>
                        <div class="col-8 col-md-4 mt-1">
                            <input type="number" class="form-control fs-14 extra-service-price" 
                                   placeholder="Price" min="0" step="0.01" required>
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
<script>
$(document).ready(function() {
    // Get currency symbol and conversion data
    const currencySymbol = '{{ $symbol }}';
    const platformFee = parseFloat('{{ $platformFee }}') || 4.00;
    const servicePrice = parseFloat('{{ $convertedPrice }}') || 0;
    
    // Initialize
    updateAllCalculations();
    updateExtraServicesList();
    updateAddonsList();

    // Quantity controls
    $('.quantity-minus').click(function() {
        const $input = $(this).siblings('.quantity-input');
        let count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count).trigger('change');
    });

    $('.quantity-plus').click(function() {
        const $input = $(this).siblings('.quantity-input');
        $input.val(parseInt($input.val()) + 1).trigger('change');
    });

    // Quantity input change
    $('.quantity-input').on('input change', function() {
        const quantity = parseInt($(this).val()) || 1;
        $('#quantityDisplay').text(quantity);
        updateAllCalculations();
    });

    // Add-on checkbox change
    $('.addon-checkbox').change(function() {
        updateAddonsList();
        updateAllCalculations();
    });

    // Add More button for extra services
    $('#addMoreBtn').on('click', function() {
        const wrapper = $('#extraServiceWrapper');
        const row = $(`
            <div class="row py-3 bg-white extra-service-row">
                <div class="col-12 col-md-7 mt-1">
                    <input type="text" class="form-control fs-14 extra-service-title" placeholder="Enter title" required>
                </div>
                <div class="col-8 col-md-4 mt-1">
                    <input type="number" class="form-control fs-14 extra-service-price" 
                           placeholder="Price" min="0" step="0.01" required>
                </div>
                <div class="col-4 col-md-1 mt-1">
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fas fa-multiply fs-12"></i>
                    </button>
                </div>
            </div>
        `);
        wrapper.append(row);
    });

    // Remove extra service row
    $(document).on('click', '.remove-row', function() {
        if ($('.extra-service-row').length > 1) {
            $(this).closest('.extra-service-row').remove();
            updateExtraServicesList();
            updateAllCalculations();
        }
    });

    // Update extra service inputs
    $(document).on('input', '.extra-service-title, .extra-service-price', function() {
        updateExtraServicesList();
        updateAllCalculations();
    });

    // Apply coupon
    $('#applyCoupon').on('click', function() {
        const couponCode = $('#couponCode').val();
        if (couponCode.trim()) {
            $.ajax({
                url: "{{ route('service.apply.coupon') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    serviceId: '{{ $service->id }}',
                    coupon: couponCode
                },
                success: function(response) {
                    console.log("response =>",response)
                    if (response.status) {
                        $('#formCoupan').val(couponCode);
                        $('#couponError').text('');
                        $('#couponSuccess').text(response.message).show();
                        updateAllCalculations();
                        
                    } else {
                        $('#couponSuccess').text('').hide();
                        $('#formDiscount').val(0);
                        $('#formCoupan').val('');
                        $('#couponError').text(response.message).show();
                        updateAllCalculations();
                    }
                },
                error: function(msg) {
                    $('#couponSuccess').text('').hide();
                    $('#formDiscount').val(0);
                    $('#formCoupan').val('');
                    $('#couponError').text('An error occurred while applying the coupon. Please try again.').show();
                    updateAllCalculations();
                }
            });
        }
    });

    // Form submission
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        
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

        $('#extra_services_data').val(JSON.stringify(extraServices));

        // Submit form
        this.submit();
    });

    // Update add-ons list
    function updateAddonsList() {
        const list = $('#selected-addons-list');
        const totalRow = $('#addons-total-row');
        const totalAmount = $('#addons-total-amount');
        
        list.empty();
        
        let total = 0;
        let hasAddons = false;
        
        $('.addon-checkbox:checked').each(function() {
            hasAddons = true;
            const name = $(this).closest('.rounded-2').find('p.fw-bold').text().trim();
            const price = parseFloat($(this).data('price')) || 0;
            const days = $(this).data('days') || 0;
            const displayPrice = $(this).data('display-price') || price.toFixed(2);
            
            total += price;
            
            list.append(`
                <li>
                    <span class="fas fa-check text-green fw-bold float-end"></span> 
                    ${name} 
                    <small class="text-muted">
                        (${days > 0 ? `+${days} day${days > 1 ? 's' : ''}, ` : ''}${currencySymbol}${displayPrice})
                    </small>
                </li>
            `);
        });
        
        if (hasAddons) {
            totalRow.removeClass('d-none');
            totalAmount.text(currencySymbol + total.toFixed(2));
        } else {
            totalRow.addClass('d-none');
            list.append('<li class="text-muted">No add-ons selected</li>');
        }
        
        return total;
    }

    // Update extra services list
    function updateExtraServicesList() {
        const list = $('#extra-services-list');
        const totalRow = $('#extra-services-total-row');
        const totalAmount = $('#extra-services-total-amount');
        
        list.empty();
        
        let total = 0;
        let hasServices = false;
        
        $('.extra-service-row').each(function() {
            const title = $(this).find('.extra-service-title').val().trim();
            const price = $(this).find('.extra-service-price').val().trim();
            const priceNum = parseFloat(price) || 0;
            
            if (title && priceNum > 0) {
                hasServices = true;
                total += priceNum;
                
                list.append(`
                    <li>
                        <span class="fas fa-check text-green fw-bold float-end"></span> 
                        ${title}
                        <small class="text-muted">(${currencySymbol}${priceNum.toFixed(2)})</small>
                    </li>
                `);
            }
        });
        
        if (hasServices && total > 0) {
            totalRow.removeClass('d-none');
            totalAmount.text(currencySymbol + total.toFixed(2));
        } else {
            totalRow.addClass('d-none');
            list.append('<li class="text-muted">No extra services added</li>');
        }
        
        return total;
    }

    // Update all calculations
    function updateAllCalculations() {
        const quantity = parseInt($('#quantity').val()) || 1;
        const serviceSubtotal = servicePrice * quantity;
        const addonsTotal = calculateAddonsTotal();
        const extraServicesTotal = calculateExtraServicesTotal();
        
        // Update subtotal
        $('#subtotalAmount').text(currencySymbol + serviceSubtotal.toFixed(2));
        $('#subtotalHidden').val(serviceSubtotal);
        
        // Calculate service fee
        const serviceFeeAmount = (serviceSubtotal + addonsTotal + extraServicesTotal) * (platformFee / 100);
        $('#platformFeeAmount').text(currencySymbol + serviceFeeAmount.toFixed(2));
        $('#serviceFeeAmount').val(serviceFeeAmount);
        
        // Calculate total
        const total = serviceSubtotal + addonsTotal + extraServicesTotal + serviceFeeAmount;
        $('#totalPrice').text(currencySymbol + total.toFixed(2));
        $('#totalHidden').val(total);
        
        // Update total to pay (with potential payment fees)
        $('#total-with-fee').text(currencySymbol + total.toFixed(2));
        $('#convertedAmount').val(total.toFixed(2));
        
        // Update service quantity for form
        $('#serviceQuantity').val(quantity);
    }

    // Calculate addons total
    function calculateAddonsTotal() {
        let total = 0;
        $('.addon-checkbox:checked').each(function() {
            total += parseFloat($(this).data('price')) || 0;
        });
        return total;
    }

    // Calculate extra services total
    function calculateExtraServicesTotal() {
        let total = 0;
        $('.extra-service-price').each(function() {
            const price = parseFloat($(this).val()) || 0;
            total += price;
        });
        return total;
    }
});
</script>
@endsection