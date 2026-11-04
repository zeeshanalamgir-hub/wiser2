@extends($activeTemplate . 'layouts.wiserlance.layout')

@section('content')
    <style>
        .modal-header,
        #saveChanges {
            background: #003f45 !important;
            color: white !important;
        }
    </style>
    <div class="row">

        <div class="col-12 p-md-4 pb-md-2">
            <!--Breadcrumbs-->
            <p class="mb-0 mt-3 text-3gray">
                @if ($user?->is_employer)
                    <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                @else
                    <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                @endif
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a href="/service" class="text-3gray" style="text-decoration:none;">Services</a>
                <i class="fa-solid fa-chevron-right fs-12"></i> Payment
            </p>
            <!--Breadcrumbs End-->
        </div>

        {{-- Payment & Billing Section (Left) --}}
        <div class="col-lg-8 mt-3 mt-lg-0" id="contents">
            <form method="POST" action="{{ route('user.service.pay.confirm') }}">
                @csrf
                <input type="hidden" name="revision" value="{{ $revision }}">
                <input type="hidden" name="time" value="{{ $time }}">
                <input type="hidden" name="package" value="{{ $package }}">
                <input type="hidden" name="serviceId" value="{{ $service->id }}">
                <input type="hidden" name="serviceDetailId" value="{{ $serviceDetails->id }}">
                <input type="hidden" name="extraservice" value="{{json_encode($extra_services)}}" id="extraId">
                <input type="hidden" name="qty" value="{{$quantity}}" id="serviceQuantity">
                <input type="hidden" name="price" value="{{ $n }}">
                <input type="hidden" name="currentrate" value="{{ $rates }}">
                <input type="hidden" name="othersymbol" value="{{ Session::get('currency_symbol') }}">
                <input type="hidden" value="{{ showAmount(@$usermembership[0]->fee) }}" name="seller_platform_fee">
                <input type="hidden" value="{{ showAmount(@$usermembership1[0]->fee) }}" name="buyer_service_fee"
                    id="buyer_service_fee">
                <input type="hidden" name="otherprice"
                    value="{{ $helpers->convert_to_currency(Session::get('currency'), @$n), 2 }}">
                <input type="hidden" name="custommizeprice" value="0" id="custommizeprice">
                <input type="hidden" name="additionalextraprice" value="0" id="additionalextraprice">

                {{-- Billing Info --}}
                <div class="p-4 shadow-sm border rounded-2 bg-white">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="text-dgreen fw-bold">Billing Information</h5>
                            <p class="fs-14 text-secondary ps-2">Your invoice will be issued according to the details listed
                                here.</p>
                            <p class="fs-14 text-secondary mb-0 ps-2">
                                <span><b>Name : </b></span> <span id="yourName"></span><br>
                                <span>Address : </span> <span id="yourAddress"></span><br>
                                <span>Tel : </span> <span id="yourTel"></span><br>
                                <span>Email : </span> <span id="yourEmail"></span><br>
                            </p>
                        </div>
                        <div class="col-md-3 d-flex flex-column pt-3">
                            <button type="button" id="addDetails"
                                class="btn btn-sm btn-success bg-dgreen2 mb-2 fw-semibold">Add
                                Details</button>
                        </div>
                    </div>
                </div>

                {{-- Payment Section --}}
                <div class="p-4 mt-3 shadow-sm border rounded bg-white">
                    <h5 class="fw-bold text-dgreen">Payment Options</h5>
                    <label class="d-flex gap-2 p-2 align-items-center">
                        <input type="radio" class="mr-2" name="payment" value="card"> Select Payment Method
                        <img src="https://help.zazzle.com/hc/article_attachments/360010513393" style="width: 130px;">
                    </label>

                    {{-- Card Fields --}}
                    <div class="ps-3 mt-2">
                        <p class="m-0">Card number</p>
                        <div class="input-group">
                            <input type="text" class="form-control fs-14" placeholder="5864 5943 9523">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <small class="text-danger"><i class="fas fa-exclamation-circle fs-12"></i> Please enter a valid
                            card
                            number.</small>
                    </div>

                    <div class="row ps-3 mt-2">
                        <div class="col">
                            <p class="m-0">Expiration Date</p>
                            <input type="text" class="form-control fs-14" placeholder="MM/YY">
                        </div>
                        <div class="col">
                            <p class="m-0">Security Code</p>
                            <input type="text" class="form-control fs-14" placeholder="123">
                        </div>
                    </div>

                    <div class="ps-3 mt-3">
                        <p class="m-0">Cardholder's name</p>
                        <input type="text" class="form-control fs-14" placeholder="Cardholder's name">
                        <small class="text-secondary">*As written on the card.</small>
                    </div>

                    <div class="ps-3 mt-2">
                        <label class="d-flex gap-1 align-items-center fs-14">
                            <input type="checkbox"> Save this card for future payments.
                        </label>
                    </div>

                    {{-- PayPal Option --}}
                    <div class="py-3">
                        <label class="d-flex gap-2 align-items-center">
                            <input type="radio" name="payment" value="paypal">
                            <i class="fa-brands fa-cc-paypal fs-4 text-primary"></i>
                            Paypal
                        </label>
                    </div>
                </div>

                {{-- Confirm & Pay --}}
                <div class="text-end mt-3">
                    <button type="submit" class="btn theme-bg text-light apply-btn rounded">Confirm & Pay</button>
                </div>
            </form>
        </div>

        {{-- Summary Section (Right) --}}
        <div class="col-lg-4 order-second order-lg-last">
            <form method="POST" action="{{ route('user.service.pay.confirm') }}">
                @csrf
                <!-- <input type="hidden" name="coupon" id="formCoupon" value=""> -->
                <input type="hidden" name="discount" id="formDiscount" value="{{ number_format($coupan_amount,2) }}">
                <input type="hidden" name="addon_ids[]" id="addon_ids" value="{{ $addon_ids[0] }}">
                <input type="hidden" name="serviceQuantity" id="serviceQuantity" value="{{ $quantity }}">
                <input type="hidden" id="servicePrice-input"
                    value="{{ number_format($helpers->convert_to_currency('USD', @$serviceDetails->price), 2, '.', '') }}">
                <input type="hidden" name="service_detail_id" value="{{ encrypt($serviceDetails->id) }}">
                <input type="hidden" name="converted_amount" id="convertedAmount"
                    value="{{ number_format( $basePrice -$coupan_amount, 2) }}">
                <input type="hidden" name="service_fee_amount" id="service_fee_amount"
                    value="{{ number_format($serviceFee, 2) }}">
                <input type="hidden" name="extra_services_data" id="extra_services_data" value="{{ $extra_services_data }}">

                <div class="bg-white p-4 shadow-sm border fs-14 rounded mb-3">

                    <div class="d-flex border-bottom pb-2">
                        <div>
                            <img src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $service->user->image, null) }}"
                                alt="@lang('Profile Image')" class="rounded-circle me-2" width="50" height="50"
                                style="border-radius:50% !important;object-fit:cover;">
                        </div>
                        <p class="mb-2 fw-bold ms-3 mt-1">{!! $service?->user?->about_me ?? '--' !!}</p>
                    </div>
                    <!-- Title and Price -->
                    <div class="w-100 fw-semibold fs-5 text-dgreen2">
                        {{ $serviceDetails->title }}
                        <span class="float-end">
                            {{--  {{ Session::get('currency_symbol') }}  --}}
                            ${{ number_format($helpers->convert_to_currency(Session::get('currency'), @$serviceDetails->price), 2) }}
                        </span>
                    </div>

                    <!-- Description -->
                    {{--  <div class="w-100 mt-1">
                        {{ $serviceDetails->desc }}
                    </div>  --}}

                    <!-- Delivery & Revisions -->
                    {{--  <div class="w-100 fw-semibold mt-1"><i class="fas fa-clock fs-10"></i> Delivery
                        <span class="float-end">{{ $serviceDetails->delivery_time }} Days</span>
                    </div>
                    <div class="w-100 fw-semibold mt-1"><i class="fas fa-sync-alt fs-10"></i> Revisions
                        <span class="float-end">{{ $serviceDetails->revision }}</span>
                    </div>  --}}
                    @if (count($availableFeatures) > 0)
                        <!-- Dynamic Feature Tiers -->
                        <h6 class="mt-3 fw-bold text-dgreen">Features</h6>
                        @foreach ($availableFeatures as $feature)
                            @php
                                $selected =
                                    $userFeatures[$serviceDetails->id]->firstWhere('service_tier_id', $feature->id) ??
                                    null;
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
                    @endif
                    <!-- Price Section -->
                    {{--  <div class="w-100 fw-semibold mt-1">Service Price
                        <span class="float-end">{{ Session::get('currency_symbol') }}
                            {{ number_format($helpers->convert_to_currency(Session::get('currency'), @$serviceDetails->price), 2) }}</span>
                    </div>  --}}

                    <!--Select Quantity -->
                    {{--  <div class="w-100 fw-semibold mt-1">
                        Select Quantity
                        <div class="input-group float-end" style="width: 100px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary minus">-</button>
                            <input type="text" readonly class="form-control text-center qtyinput fw-bold fs-14" name="quantity"
                                id="qty" value="{{ $quantity }}" style="padding: 0;">
                            <button type="button" class="btn btn-sm btn-outline-secondary plus">+</button>
                        </div>
                    </div>  --}}

                    <!-- Quantity -->
                    {{--  <div class="w-100 fw-semibold mt-3">
                        Quantity
                        <span class="float-end" id="qunatity">{{ $quantity }}</span>
                    </div>  --}}

                    <!-- Subtotal and Cost -->
                    {{--  <div class="w-100 fw-semibold mt-1">Subtotal <span class="float-end"
                            id="Subtotal">{{ $Subtotal }}</span></div>  --}}
                    {{-- Extra Services --}}
                    @if (count($extra_services) > 0)
                        <div class="w-100 mt-3 pt-3 border-top">
                            <h6 class="fw-bold text-dgreen">Extra Services</h6>
                            <ul class="list-unstyled" id="extra-services-list">
                                @foreach ($extra_services as $extra_service)
                                    <li>
                                        <span class="fas fa-check text-green fw-bold float-end"></span>
                                        {{ $extra_service?->title }}
                                        <small class="text-muted">(${{ $extra_service?->price }})</small>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="w-100 fw-semibold mt-2 d-none" id="extra-services-total-row">
                                Extra Services Total <span class="float-end" id="extra-services-total-amount">$0.00</span>
                            </div>
                        </div>
                    @endif

                    {{--  <div class="w-100 mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dgreen">Selected Add-ons</h6>
                        <ul class="list-unstyled" id="selected-addons-list">
                            <li class="text-muted">No add-ons selected</li>
                        </ul>
                    </div>  --}}

                    {{--  <div class="w-100 fw-semibold mt-1">Service Fee <span class="float-end">{{ $service_fee }}%
                        </span>
                    </div>  --}}
                    {{--  <div class="w-100 fw-semibold mt-1">Total <span class="float-end"
                            id="totalPrice">{{ $basePrice }}</span></div>  --}}
                    @if ($coupan_amount != 0)
                        <div class="w-100 fw-semibold py-1 mt-2 fs-16 border-top">
                            Promo Code <span class="fw-normal text-green">*{{ $coupon }}*
                            </span> <span class="float-end">-{{ number_format($coupan_amount,2) }}</span>
                            <p class="fs-12 mb-0 text-secondary fw-normal"><i>Avail discount per order</i></p>
                        </div>
                    @endif

                    <div class="w-100 fw-semibold mt-1 border-top fs-16 py-1">
                        Service Fee
                        <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-html="true"
                            data-bs-title="&lt;span class='fs-12'&gt; Add information &lt;/span&gt;"><i
                                class="fa-regular fa-circle-question"></i></button>
                        <span class="float-end">${{ number_format($serviceFee, 2) }}</span>
                    </div>
                    <!-- Payment Fee Calculation -->
                    <div class="w-100 mt-1 pt-1 border-top">
                        {{--  <h6 class="fw-bold text-dgreen">Payment Method Fee</h6>
                        <div class="w-100 fw-semibold mt-1">Payment Processing Fee
                            <span class="float-end" id="payment-fee-amount">$0.00</span>
                        </div>  --}}

                        <div class="w-100 fw-semibold mt-2 fs-6">Total
                            <span class="float-end"
                                id="total-with-fee">${{ number_format($basePrice - $coupan_amount, 2) }}</span>
                        </div>
                        <div class="w-100 mt-1">Delivery Time
                            <span class="float-end">{{ $serviceDetails->delivery_time }} Days</span>
                        </div>
                    </div>

                    <!-- Coupon Input -->
                    {{--  <div class="w-100" id="discount">
                        <label for="couponCode" class="form-label"></label>
                        <input type="text" class="form-control is-valid fs-14" id="couponCode" value="Coupon code"
                            required>
                    </div>  --}}

                    <!-- Confirm Button -->
                    <div class="w-100 mt-3">
                        <button class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold" id="confirm-btn">
                            Confirm & Pay
                        </button>
                    </div>
                    <p class="text-gray mb-0 text-center mt-2"><i class="fas fa-lock fs-10"></i> Secure payment</p>
                    <p class="text-start mb-0 fs-14 text-center mt-2">You will be charged
                        <b>${{ number_format($basePrice - $coupan_amount, 2) }}</b> Total amount <br>
                        includes currency conversion fees.
                    </p>
                </div>
            </form>
        </div>
    </div>

    {{--  Modal  --}}
    <div class="modal fade" id="billingInforamation" tabindex="-1" aria-labelledby="billingInforamationLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="billingInforamationLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="user_name" placeholder="Name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Address</label>
                            <input type="text" class="form-control" id="user_address" placeholder="Address" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Telephone</label>
                            <input type="number" class="form-control" id="user_tel" placeholder="Telephone" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Email</label>
                            <input type="email" class="form-control" id="user_email" placeholder="Email" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '#addDetails', function() {
            $('#billingInforamation').modal('show');
        })

        $(document).on('click', '#saveChanges', function(e) {
            e.preventDefault();
            let uname = $('#user_name').val();
            let uaddress = $('#user_address').val();
            let utel = $('#user_tel').val();
            let uemail = $('#user_email').val();

            $('#yourName').text(uname);
            $('#yourAddress').text(uaddress);
            $('#yourTel').text(utel);
            $('#yourEmail').text(uemail);

            if (uname != '' && uaddress != '' && utel != '' && uemail != '') {
                $('#billingInforamation').modal('hide');
            }

        })
    </script>
@endsection
