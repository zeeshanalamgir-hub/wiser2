@extends($activeTemplate . 'layouts.wiserlance.layout')
@php
    $hasErrors = false;
    if ($errors->any()) {
        $hasErrors = true;
    }
    if (session('notify') && session('notify')[0][0] == 'error') {
        $hasErrors = true;
    }
@endphp

@push('style')
    <style>
        .country-code .input-group-prepend .input-group-text {
            background: #fff !important;
        }

        .country-code select {
            border: none;
        }

        .country-code select:focus {
            border: none;
            outline: none;
        }

        .hover-input-popup {
            position: relative;
        }

        .hover-input-popup:hover .input-popup {
            opacity: 1;
            visibility: visible;
        }

        .input-popup {
            position: absolute;
            bottom: 70%;
            left: 50%;
            width: 280px;
            background-color: #003f45;
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            opacity: 0;
            visibility: hidden;
            -webkit-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }

        .input-popup::after {
            position: absolute;
            content: '';
            bottom: -19px;
            left: 50%;
            margin-left: -5px;
            border-width: 10px 10px 10px 10px;
            border-style: solid;
            border-color: transparent transparent #1a1a1a transparent;
            -webkit-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        .input-popup p {
            padding-left: 20px;
            position: relative;
        }

        .input-popup p::before {
            position: absolute;
            content: '';
            font-family: 'Line Awesome Free';
            font-weight: 900;
            left: 0;
            top: 4px;
            line-height: 1;
            font-size: 18px;
        }

        .input-popup p.error {
            text-decoration: line-through;
        }

        .input-popup p.error::before {
            content: "\f057";
            color: #ea5455;
        }

        .input-popup p.success::before {
            content: "\f058";
            color: #28c76f;
        }
    </style>
@endpush

@section('content')
    <div class="row p-3 p-md-3">
        <div class="col-12">
            <!--Breadcrumbs-->
            <p class=" text-bc fs-14"> <a class="text-3gray" href="{{ route('home') }}">
                    <i class="fa-solid fa-house fs-14"></i></a>
                <i class="fa-solid fa-chevron-right fs-12"></i>Sign Up
            </p> <!--Breadcrumbs End-->
            <center>
                <div class="card bg-white shadow p-4 px-lg-5" style="max-width:600px;">
                    <form method="POST" action="{{ route('user.register') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="ftype" name="ftype" value="{{ $type }}" />
                        <div id="registrationForm">
                            <h3 class="mt-4 fw-bold text-dgreen"> Registration</h3>
                            <p class="fs-14"> Please fill in the registration form </p>
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control fs-14 " id="firstname" name="firstname"
                                        required="" placeholder="@lang('First name')*" value="{{ old('firstname') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control fs-14" id="lastname" name="lastname"
                                        required="" placeholder="@lang('Last name')*" value="{{ old('lastname') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control fs-14 checkUser" required="" id="email"
                                        name="email" placeholder="@lang('Email address')*" value="{{ old('email') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control fs-14 checkUser" required=""
                                        name="username" value="{{ old('username') }}" placeholder="@lang('username')" />
                                    <small class="text-danger usernameExist"></small>
                                </div>
                                @php
                                    $oldCountry = old('country_code')
                                        ? old('country_code')
                                        : (isset($defaultCountryCode)
                                            ? $defaultCountryCode
                                            : '');
                                    $oldCity = old('city') ? old('city') : (isset($defaultCity) ? $defaultCity : '');
                                    $oldMobileCode = old('mobile_code')
                                        ? old('mobile_code')
                                        : (isset($mobile_code)
                                            ? $mobile_code
                                            : '');
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <select name="country_code" id="country_code" class="form-select fs-14 text-secondary"
                                        aria-label="country_code" required>
                                        <option value="" disabled {{ !$oldCountry ? 'selected' : '' }}>Select Country
                                        </option>
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}"
                                                data-timezone="{{ $country->timezone }}" value="{{ $key }}"
                                                data-dial_code="{{ $country->dial_code }}"
                                                {{ $oldCountry == $key ? 'selected' : '' }}>
                                                {{ __($country->country) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Hidden Timezone Input -->
                                <input type="hidden" id="timezone" name="timezone" value="">
                                <input type="hidden" id="city" name="city" value="{{ $oldCity }}">


                                <div class="col-md-6 mb-3">
                                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                        class="form-control checkUser" placeholder="@lang('Phone Number')">
                                    <input type="hidden" name="mobile_code" id="mobile_code">
                                    <small class="text-danger mobileExist"></small>
                                </div>


                                <div class="col-md-6 mb-3 hover-input-popup">
                                    <input type="password" class="form-control fs-14" id="password" name="password"
                                        required placeholder="@lang(' Enter password')" />
                                    @if ($general->secure_password)
                                        <div class="input-popup">
                                            <p class="error lower">@lang('1 small letter minimum')</p>
                                            <p class="error capital">@lang('1 capital letter minimum')</p>
                                            <p class="error number">@lang('1 number minimum')</p>
                                            <p class="error special">@lang('1 special character minimum')</p>
                                            <p class="error minimum">@lang('6 character password')</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="password" class="form-control fs-14" name="password_confirmation" required
                                        placeholder="@lang(' Enter confirm password')" />
                                </div>
                                @if ($general->agree)
                                    <p class="fs-12">
                                        <input type="checkbox" id="agree" name="agree" value="yes">
                                        @lang('By registering, You are agree to the ')<a href="#0" class="text--base"><span
                                                class="text-green">@lang('Terms and Conditions')</span></a>
                                        @lang('and take note of the data protection declaration. Further information on registration and ')<a href="#0" class="text--base"><span
                                                class="text-green">@lang('data processing')</span></a>
                                        @lang('can be found here.')
                                    </p>
                                @endif
                            </div>

                            <button type="submit"
                                class="btn btn-success bg-green border-0 rounded-1 register-button w-100">
                                @lang('Register Now')</button>

                        </div>
                    </form>
                    <p class="mt-3 text-end fs-12">You already have an account?<a
                            class="links-c ps-2 "href="{{ route('user.login') }}">
                            Log In Now</a></p>
                </div>
            </center>
        </div>
    </div>
    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">@lang('You are with us')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h6>@lang('You already have an account please Sign in ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded "
                        data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="" class="btn btn--primary btn-rounded ">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@section('script')
    <script>
        "use strict";

        (function() {
            const countrySelect = document.getElementById('country_code');
            const contactInput = document.getElementById('mobile');

            // Init intl-tel-input on your existing contact input
            // Hide its own dropdown since you have your own country select
            const iti = window.intlTelInput(contactInput, {
                allowDropdown: false, // disable its flag dropdown — you control country via #bus_country
                nationalMode: true, // accept national format e.g. 03148084045 for PK
                strictMode: true, // enforces max length per country automatically
                autoPlaceholder: 'aggressive', // shows e.g. "0301 2345678" as placeholder for PK
                validationNumberType: null, // validate all types (mobile + fixed)
            });

            function syncCountry() {
                const opt = countrySelect.options[countrySelect.selectedIndex];
                const code = opt?.value;
                if (code) {
                    iti.setCountry(code.toLowerCase());
                }
            }

            countrySelect.addEventListener('change', function() {
                syncCountry();
                contactInput.value = '';
                contactInput.classList.remove('is-valid', 'is-invalid');
            });

            // Validate using intl-tel-input's built-in isValidNumber()
            contactInput.addEventListener('input', function() {
                if (!contactInput.value.trim()) {
                    contactInput.classList.remove('is-valid', 'is-invalid');
                    return;
                }
                if (iti.isValidNumber()) {
                    contactInput.classList.add('is-valid');
                    contactInput.classList.remove('is-invalid');
                } else {
                    contactInput.classList.add('is-invalid');
                    contactInput.classList.remove('is-valid');
                }
            });

            // Block form submit if invalid
            contactInput.closest('form')?.addEventListener('submit', function(e) {
                if (!iti.isValidNumber()) {
                    e.preventDefault();
                    contactInput.classList.add('is-invalid');
                    contactInput.focus();
                }
            });

            // Init on page load for edit mode
            syncCountry();
            if (contactInput.value.trim()) {
                contactInput.classList.toggle('is-valid', iti.isValidNumber());
                contactInput.classList.toggle('is-invalid', !iti.isValidNumber());
            }

        })();

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang('Captcha field is required.')</span>';
                return false;
            }
            return true;
        }

        // Function to update dial code dropdown options based on selected country
        function updateDialCodeOptions(selectedCountry) {
            var dialCodeDropdown = document.getElementById('mobile_code');
            dialCodeDropdown.innerHTML = ''; // Clear existing options

            // Get the selected country's dial code
            var selectedCountryOption = document.querySelector('#country option[value="' + selectedCountry + '"]');
            var mobileCode = selectedCountryOption.getAttribute('data-mobile_code');

            // Create a new option for the dial code dropdown
            var option = document.createElement('option');
            option.value = mobileCode;
            option.text = '+' + mobileCode;
            dialCodeDropdown.add(option);
        }

        (function($) {
            @if ($mobile_code)
                $(`option[data-code={{ $mobile_code }}]`).attr('selected', '');
            @endif
            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country_code] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country_code] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country_code] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country_code] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country_code] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country_code] :selected').data('mobile_code'));
            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response['data'] && response['type'] == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response['data'] != null) {
                        $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                    } else {
                        $(`.${response['type']}Exist`).text('');
                    }
                });
            });

        })(jQuery);

        document.addEventListener("DOMContentLoaded", function() {
            let countryDropdown = document.getElementById('country_code');
            let timezoneInput = document.getElementById('timezone');
            let mobileCodeInput = document.getElementById('mobile_code');

            // Function to update timezone and mobile code when country is selected
            function updateFields() {
                let selectedOption = countryDropdown.options[countryDropdown.selectedIndex];
                let timezone = selectedOption.getAttribute('data-timezone');
                let mobileCode = selectedOption.getAttribute('data-mobile_code');
                if (timezone) {
                    timezoneInput.value = timezone;
                }
                if (mobileCode) {
                    mobileCodeInput.value = mobileCode;
                    $('.mobile-code').text('+' + $('select[name=country_code] :selected').data('mobile_code'));
                }
            }

            // Update fields on page load based on preselected country
            updateFields();

            // Update fields when country selection changes
            countryDropdown.addEventListener('change', function() {
                updateFields();
            });
        });
    </script>
@endsection
