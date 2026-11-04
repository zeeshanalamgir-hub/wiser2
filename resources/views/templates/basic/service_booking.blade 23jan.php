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
            <form method="POST" action="{{ route('user.pay.confirm') }}">
                @csrf
                <!-- <input type="hidden" name="coupon" id="formCoupon" value=""> -->
                <input type="hidden" name="discount" id="formDiscount" value="0">
                <input type="hidden" name="addon_ids[]" id="addon_ids">
                <input type="hidden" id="serviceQuantity" value="1">
                <input type="hidden" id="servicePrice-input"
                    value="{{ number_format($helpers->convert_to_currency('USD', @$serviceDetails->price), 2, '.', '') }}">
                <input type="hidden" name="service_detail_id" value="{{ encrypt($serviceDetails->id) }}">
                <input type="hidden" name="converted_amount" id="convertedAmount" value="">

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
                                value="1" style="padding: 0;">
                            <button type="button" class="btn btn-sm btn-outline-secondary plus">+</button>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="w-100 fw-semibold mt-3">
                        Quantity
                        <span class="float-end" id="qunatity">1</span>
                    </div>



                    <!-- Subtotal and Cost -->
                    <div class="w-100 fw-semibold mt-1">Subtotal <span class="float-end" id="Subtotal">0</span></div>

                    <div class="w-100 mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dgreen">Selected Add-ons</h6>
                        <ul class="list-unstyled" id="selected-addons-list">
                            <li class="text-muted">No add-ons selected</li>
                        </ul>
                    </div>

                    <div class="w-100 fw-semibold mt-1">Service Fee <span class="float-end">{{ $platformFee }}%</span>
                    </div>
                    <div class="w-100 fw-semibold mt-1">Total <span class="float-end" id="totalPrice">0</span></div>

                    <!-- Payment Fee Calculation -->
                    <div class="w-100 mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dgreen">Payment Method Fee</h6>
                        <div class="w-100 fw-semibold mt-1">Payment Processing Fee
                            <span class="float-end" id="payment-fee-amount">$0.00</span>
                        </div>
                        <div class="w-100 fw-bold mt-2 fs-5">Total to Pay
                            <span class="float-end" id="total-with-fee">$0.00</span>
                        </div>
                    </div>

                    <!-- Coupon Input -->
                    <div class="w-100" id="discount">
                        <label for="couponCode" class="form-label"></label>
                        <input type="text" class="form-control is-valid fs-14" id="couponCode" value="Coupon code"
                            required>
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
                                <!-- <img class="rounded-circle float-md-end"
                                                        src="{{ getImage('assets/images/user/profile/' . @$service->user->image, '70x70') }}"
                                                        alt="{{ $service->user->username }}" width="70"> -->

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
                            <input type="text" class="form-control fs-14" placeholder="Enter title">
                        </div>
                        <div class="col-8 col-md-4 mt-1">
                            <input type="text" class="form-control fs-14" placeholder="Price">
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
        $("#qty").change(function() {
            // alert($("#servicePrice").text() * $("#qty").val());
            var extra_services = 0;
            $("#qty").attr('disabled', false);
            var servicePrice = $("#servicePrice").text();

            var qty = $("#qty").val();
            $('.extraamountmulti').each(function(key, value) {
                var $this = $(this);
                var thisval = $this.val();
                extra_services += parseFloat(thisval);
            });
            var subtotal1 = (parseFloat(servicePrice) * parseFloat(qty));
            var fee = {{ $platformFee }};
            var per = (subtotal1 * fee) / 100;
            var subtotal = subtotal1 + per + extra_services;

            var total = parseFloat(subtotal) + parseFloat(extra_services);
            $("#Subtotal").html(parseFloat(subtotal1.toFixed(2)));
            $("#totalPrice").html(parseFloat(total.toFixed(2)));
            $("#qunatity").text(`${qty}`);
            $("#serviceQuantity").val(qty);
        });
    </script>

    <script type="text/javascript">
        var c_no2 = 100001;
        $('.addExtra').on('click', function() {
            var html = '<div class="col-lg-12 extraServiceRemove" id="port1-' + c_no2 + '">\
                        <div class="row">\
                            <div class="col-lg-7 col-md-8 col-sm-12 col-12 form-group">\
                                <input type="text" class="form-control extraservicetitlemulti" placeholder="@lang('Enter title')" required>\
                            </div>\
                            <div class="col-lg-3 col-md-2 col-sm-12 col-12 form-group">\
                                <div class="input-group mb-3">\
                                    <input type="number" class="form-control extraamountmulti" placeholder="@lang('Price')" required="">\
                                </div>\
                            </div>\
                            <div class="col-lg-2 col-md-2 col-sm-12 col-12">\
                                <button data-id="' + c_no2 + '" type="button" class="btn btn-danger text-white border-rounded btn-sm removeBtn">\
                                <i class="fa fa-times"></i>\
                                </button>\
                            </div>\
                        </div>\
                        </div>';
            $('.addExtraService').append(html);
        });

        $(document).on('click', '.removeBtn', function() {
            $(this).closest('.extraServiceRemove').remove();
            var extra_services = 0;
            $("#qty").attr('disabled', false);
            var servicePrice = $("#servicePrice").text();

            var qty = $("#qty").val();
            $('.extraamountmulti').each(function(key, value) {
                var $this = $(this);
                var thisval = $this.val();
                extra_services += parseFloat(thisval);
            });
            var subtotal1 = (parseFloat(servicePrice) * parseFloat(qty));
            var fee = {{ $platformFee }};
            var per = (subtotal1 * fee) / 100;
            var subtotal = subtotal1 + per + extra_services;

            var total = parseFloat(subtotal) + parseFloat(extra_services);
            $("#Subtotal").html(parseFloat(subtotal1.toFixed(2)));
            $("#totalPrice").html(parseFloat(total.toFixed(2)));
            $("#qunatity").text(`${qty}`);
            $("#serviceQuantity").val(qty);
            $("#additionalservicetotal").text(extra_services);
        });
    </script>
    <script type="text/javascript">
        $(document).on('keyup', '.extraamountmulti', function() {
            var servicePrice = $('#servicePrice-input').val();
            var qty = $('#qty').val();
            var extra_services = 0;
            $('.extraamountmulti').each(function(key, value) {
                var $this = $(this);
                var thisval = $this.val();
                extra_services += parseFloat(thisval);
            });
            $('#additionalservicetotal').html(extra_services);
            var total1 = (parseFloat(servicePrice) * parseFloat(qty)) + parseFloat(extra_services);
            var fee = {{ $platformFee }};
            var per = (total1 * fee) / 100;
            var total = total1 + per;
            var total2 = (parseFloat(servicePrice) * parseFloat(qty)) + parseFloat(extra_services);
            $("#totalPrice").html(total.toFixed(2));
            // $("#final").html( (parseFloat(servicePrice) * parseFloat(qty)) + parseFloat(extra_services));
        });
    </script>

    <script type="text/javascript">
        $("#couponApply").on("click", function() {
            var couponCode = $("#couponCode").val();
            var serviceId = {{ $service->id }};
            var serviceprice =
                {{ number_format($helpers->convert_to_currency(Session::get('currency'), @$n), 2) }};
            var qty = $("#serviceQuantity").val();
            if (couponCode) {
                $.ajax({

                    type: "GET",
                    url: "{{ route('user.service.coupon.apply') }}",
                    data: {
                        couponCode: couponCode,
                        serviceId: serviceId,
                        serviceprice: serviceprice,
                        qty: qty
                    },
                    success: function(response) {
                        if (response.error) {
                            notify('error', response.error)
                        } else {
                            notify('success', response.success);
                            $("#discount").html(`
                    <li><a href="#">@lang('Discount')<span id="" class="newdesign"><span id="totalPrice"> (${response.code}) {{ $general->cur_sym }}${parseFloat(response.amount.toFixed(8))}</span></span></a></li>
                    <li><a href="#">@lang('Grand Total')<span id="" class="newdesign">{{ $general->cur_sym }} <span id="final"> </span></span></a></li>
                    `)
                            discount = 1;
                            $("#hideextra").attr("disabled", true);
                            $("#qty").attr("disabled", true);
                            var discountAmount = response.amount;
                            var totalPrice = $("#totalPrice").text();
                            var final = parseFloat(totalPrice) - parseFloat(discountAmount);
                            $("#paymentPrice").text(`${parseFloat(final.toFixed(8))}`);
                            $("#final").text(final);
                        }
                    }
                });
            } else {
                notify('error', "Please Enter Coupon Code");
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#show-reply-area').click(function() {
                $('.reply-form-area').slideToggle("slow");
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize prices on page load
            updatePrices();

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
                //alert(selectedAddons.length);
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

            $('#confirm-btn').on('click', function(e) {
                let ids = [];
                let titles = [];
                let prices = [];

                $('.addon-checkbox:checked').each(function() {
                    let addonId = $(this).val();
                    let addonName = $(this).closest('.rounded-2').find('p.fw-bold').text().trim();
                    let addonPrice = $(this).data('price');

                    ids.push(addonId);
                    titles.push(addonName);
                    prices.push(addonPrice);
                });

                $('#addon_ids').val(JSON.stringify(ids));

                // You can also calculate and set converted_amount dynamically if needed
                $('#convertedAmount').val($('#total-with-fee').text().replace('$', ''));

                // Optionally pass coupon value and discount
                $('#formCoupon').val($('#couponCode').val());
                $('#formDiscount').val();
            });


            // Payment method change handler (from the left section)
            $('.payment-method').change(function() {
                updatePrices();
            });

            // Function to update all prices
            function updatePrices() {
                var qty = parseInt($('#qty').val());
                var servicePrice = parseFloat($('#servicePrice-input').val());
                var addonsTotal = 0;

                // Calculate addons total from checked checkboxes
                $('.addon-checkbox:checked').each(function() {
                    addonsTotal += parseFloat($(this).data('price')) || 0;
                });

                // Calculate subtotal (service price * quantity)
                var subtotal = servicePrice * qty;

                // Calculate service fee (5% of subtotal)
                var percentageValue = parseFloat('{{ $platformFee }}') || 0;
                //alert(percentageValue);
                var serviceFee = (subtotal + addonsTotal) * (percentageValue / 100);

                // Calculate total (subtotal + extra services + service fee)
                var total = subtotal + addonsTotal + serviceFee;

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
                $('#Subtotal').text(subtotal.toFixed(2));
                $('#additionalservicetotal').text((addonsTotal).toFixed(2));

                $('#totalPrice').text(total.toFixed(2));

                // Update payment fee display
                $('#payment-fee-amount').text(paymentFee.toFixed(2));
                $('#total-with-fee').text(totalWithFee.toFixed(2));
                $('#amount').val(totalWithFee.toFixed(2));

                $('#formQuantity').val(qty);
                $('#formPrice').val(servicePrice.toFixed(2));
            }

            // Remove extra service
            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.extraServiceRemove').remove();
                updatePrices();
            });

            // Initialize prices on page load
            updatePrices();

        });

        document.getElementById('addMoreBtn').addEventListener('click', function() {
            const wrapper = document.getElementById('extraServiceWrapper');

            const row = document.createElement('div');
            row.classList.add('row', 'py-3', 'bg-white', 'extra-service-row');

            row.innerHTML = `
            <div class="col-12 col-md-7 mt-1">
                <input type="text" class="form-control fs-14" placeholder="Enter title">
            </div>
            <div class="col-8 col-md-4 mt-1">
                <input type="text" class="form-control fs-14" placeholder="Price">
            </div>
            <div class="col-4 col-md-1 mt-1">
                <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="fas fa-multiply fs-12"></i>
                </button>
            </div>
        `;

            wrapper.appendChild(row);
        });

        // Remove row (event delegation)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('.extra-service-row').remove();
            }
        });
    </script>
@endsection
