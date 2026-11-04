@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')

@section('content')
    <div class="card bg-white p-md-2 p-md-3 ms-md-1 mt-md-2 shadow-sm border rounded">
        <!-- Stepper -->
        <div class="stepper-wrapper mt-2 fs-14 bg-light shadow-sm p-3 rounded border mx-4">
            @php
                $completedStep = auth()->user()->completed_step ?? 0; // Get the completed step from the database
            @endphp

            <div class="stepper-item {{ $completedStep >= 1 ? 'completed active' : '' }} " data-step="1"
                @if ($completedStep >= 1) onclick="navigateToStep(1)" @endif>
                <div class="step-counter">1</div>
                <div class="step-name">Personal</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 2 ? 'completed active' : '' }} " data-step="2"
                @if ($completedStep >= 2) onclick="navigateToStep(2)" @endif>
                <div class="step-counter">2</div>
                <div class="step-name">Identity Verification</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 3 ? 'completed active' : '' }} " data-step="3"
                @if ($completedStep >= 3) onclick="navigateToStep(3)" @endif>
                <div class="step-counter">3</div>
                <div class="step-name">Business Details</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 4 ? 'completed active' : '' }} " data-step="4"
                @if ($completedStep >= 4) onclick="navigateToStep(4)" @endif>
                <div class="step-counter">4</div>
                <div class="step-name">Business Contact</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 5 ? 'completed active' : '' }} " data-step="5"
                @if ($completedStep >= 5) onclick="navigateToStep(5)" @endif>
                <div class="step-counter">5</div>
                <div class="step-name">Notification</div>
            </div>
        </div>
        <!-- Step Content -->
        <div id="step-content">
            <!-- Step 1 Content -->
            <div id="step-1" class="step-pane active">
                <!-- Profile Image Upload Section -->
                <div class="alert alert-danger mx-4 term_condition_alert d-none">You must accept the terms and conditions to
                    proceed.</div>
                <form action="{{ url('/buyer-personal-info/' . $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-center align-items-center mt-4">

                        <div class="profile-upload position-relative">
                            <label for="profilePicInput" class="position-relative">
                                <!-- Profile Picture Preview -->
                                <div class="profile-picture-container rounded-circle overflow-hidden"
                                    style="width: 160px; height: 160px;">
                                    <img id="profilePicPreview"
                                        src="{{ $user->image ? Storage::url($user->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}"
                                        alt="User Profile" class="img-fluid w-100 h-100" style="object-fit: cover;" />
                                </div>

                                <!-- Camera Icon -->
                                <i
                                    class="fas fa-camera position-absolute bottom-0 end-0 bg-white p-2 rounded-circle border"></i>
                            </label>
                            <!-- File Input -->
                            <input type="file" id="profilePicInput" name="profile_picture" accept="image/*"
                                class="d-none" />
                        </div>
                    </div>

                    <!-- Username Input -->
                    <div class="mb-3 mt-2 text-center">
                        <p class="text-center fw-bold text-orange">{{ __($user->username) }}</p>
                    </div>
                    <!-- Personal Information Form -->
                    <div class="card-body">
                        <h1 class="main-color fs-5 pb-2">Personal Information</h1>
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3 ">
                                <input type="text" id="firstname" name="firstname" class="form-control"
                                    value="{{ __($user->firstname) }}" placeholder="First Name" required />
                            </div>
                            <div class="col-12 col-md-6 mb-2 ">
                                <input type="text" class="form-control " id="lastName" name="lastname"
                                    value="{{ __($user->lastname) }}" placeholder="Last Name" required />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3">
                                <input type="email" readonly class="form-control " id="email" name="email"
                                    value="{{ __($user->email) }}" placeholder="E-Mail (example@gmail.com)" required />
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <select id="country" name="country" class="form-control" required>
                                    <!-- Default placeholder option -->
                                    <option value="" disabled {{ !$user->country_code ? 'selected' : '' }}>
                                        @lang('Select Country')</option>

                                    <!-- Loop through countries and preselect based on DB value -->
                                    @foreach ($countries as $code => $country)
                                        <option value="{{ $country['country'] }}"
                                            {{ $user->country_code == $country['country'] ? 'selected' : '' }}>
                                            {{ $country['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <!-- Description -->
                            <div class="col-12 col-md-12 mb-3">
                                <textarea class="form-control with-light" style="height: 200px !important;" name="about_me" id="description_1"
                                    placeholder="Description">{!! $user->about_me ?? '' !!}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2" type="checkbox" name="terms" id="terms"
                                        required @if ($user->terms_accepted) checked @endif />

                                    <label class="form-check-label" for="terms" style="margin:0;">
                                        You are required to accept the
                                        <a href="javascript:void(0)" id="terms_condition"
                                            style="color:#00C535;font-weight:bold;text-decoration:none;">
                                            Terms & Conditions
                                        </a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Save and Next Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn  btn-outline-dark"> Save </button>
                            <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(2)"
                                {{ $completedStep < 1 ? 'disabled' : '' }}>Next</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="step-2" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                    <div class="mt-4">
                        <div class="d-flex float-end gap-2">
                            <h2>Persona Embedded Flow</h2>
                            <button id="start-verification">Start Verification</button>

                            {{--  @include('templates.basic.user.buyer.persona')  --}}
                            <button type="submit" class="btn btn-outline-success"> Save </button>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(3)">Next</button>
                        </div>
                        <button type="button" class="btn btn-success bg-green border-0"
                            onclick="showStep(1)">Back</button>
                    </div>
                    </p>
                </center>

            </div>
            <!-- Step 3 Content -->
            <div id="step-3" class="step-pane" style="display: none;">
                <form action="{{ url('/buyer-business-info') }}" method="POST" enctype="multipart/form-data"
                    onsubmit="completeStep(3); return false;">
                    @csrf
                    <div class="card-body">
                        <h1 class="main-color fs-5 pb-2">Business Details</h1>

                        <div class="row mb-2">
                            <!-- Business Name -->
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" id="companyname" name="companyname"
                                    value="{{ old('companyname', $ucinfo->companyname ?? '') }}" class="form-control"
                                    placeholder="Business Name" required />
                            </div>
                            <!-- Business Website -->
                            <div class="col-12 col-md-6 mb-2">
                                <input type="text" id="companyweb" name="companyweb"
                                    value="{{ old('companyweb', $ucinfo->companyweb ?? '') }}" class="form-control"
                                    placeholder="Business Website Address" required />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <!-- Business Industry -->
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" id="companyind" name="companyind"
                                    value="{{ old('companyind', $ucinfo->companyind ?? '') }}" class="form-control"
                                    placeholder="Business Industry" required />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 col-md-12 mb-3">
                                <textarea class="form-control with-light" cols="60" name="description" id="description_3"
                                    placeholder="Description" style="height: 200px !important;">
                                    {!! old('description', $ucinfo->description ?? '') !!}
                                </textarea>
                            </div>
                        </div>
                        <!-- Save and Next Buttons -->
                        <div class="mt-4">
                            <div class="d-flex float-end gap-2">
                                <button type="submit" class="btn btn-outline-success"> Save </button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(4)"
                                    {{ $completedStep < 2 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(2)">Back</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Step 3 Content -->
            {{--  @dd($ucinfo?->contact)  --}}
            <div id="step-4" class="step-pane" style="display: none;">
                <form action="{{ url('/buyer-business-contact') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="country_code" id="country_code" value="{{ $country_code ?? null }}">
                    <div class="card-body">
                        <h1 class="main-color fs-5 pb-2">Business Contact</h1>

                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" id="director" name="director"
                                    value="{{ old('director', $ucinfo->director ?? '') }}" class="form-control"
                                    placeholder="Director/Proprietor" required />
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <input type="number" id="contact" name="contact"
                                    value="{{ old('contact') ?? ($ucinfo?->contact ?? '') }}" class="form-control"
                                    placeholder="Contact Number" required />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3">
                                <select id="bus_country" name="bus_country" class="form-select" required>
                                    <!-- Default placeholder option -->
                                    <option value="" disabled {{ !optional($ucinfo)->country ? 'selected' : '' }}>
                                        @lang('Select Country')</option>

                                    <!-- Loop through countries and preselect based on DB value -->
                                    @foreach ($countries as $code => $country)
                                        <option value="{{ $country['country'] }}"
                                            data-country_code = "{{ $code }}"
                                            {{ optional($ucinfo)->country == $country['country'] ? 'selected' : '' }}>
                                            {{ $country['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <select id="timezone" name="timezone" class="form-select">
                                    @foreach ($countries as $code => $details)
                                        @if (isset($details['timezone']))
                                            <option value="{{ $details['timezone'] }}"
                                                {{ old('timezone', $ucinfo->timezone ?? '') == $details['timezone'] ? 'selected' : '' }}>
                                                {{ $details['timezone'] }} ({{ $details['country'] }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3">
                                <select id="state" name="state" class="form-select">
                                </select>

                                {{--  <input type="text" id="state" name="state"
                                       value="{{ old('state', $ucinfo->state ?? '') }}"
                                       class="form-control" placeholder="State" required/>  --}}
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" id="address" name="address"
                                    value="{{ old('address', $ucinfo->address ?? '') }}" class="form-control"
                                    placeholder="Address" required />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" id="zip" name="zip"
                                    value="{{ old('zip', $ucinfo->zip ?? '') }}" class="form-control"
                                    placeholder="Zip Code" required />
                            </div>
                        </div>
                        <!-- Save and Next Buttons -->
                        <div class="mt-4">
                            <div class="d-flex float-end gap-2">
                                <button type="submit" class="btn btn-outline-success"> Save </button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(5)"
                                    {{ $completedStep < 3 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(3)">Back</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="step-5" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        The way we get information and remain connected has been revolutionised by notification alerts.
                        They are essential tools that increase output, keep us informed, and improve our quality of work
                        in general. Customize your notification settings to suit your needs and preferences, and enjoy the
                        benefits of this powerful communication tool.
                    </p>
                </center>
                <div class="card-body">
                    <form action="{{ route('buyer.notification.settings') }}" id="notifications" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="form-container">
                            <!-- The first form group -->
                            <div class="form-section">
                                <h6 class="main-color mb-3">Notification Settings</h6>
                                <hr>
                                <div id="notificationList">
                                    @foreach ($noti as $alert)
                                        <div class="row mb-3 align-items-center">
                                            <!-- Alert Name -->
                                            <div class="col-8">
                                                <span>{{ $alert->name ?? 'No Name' }}</span>
                                            </div>
                                            <!-- Notification Toggle -->
                                            <div class="col-4 text-end">
                                                <input type="checkbox" class="form-check-input notification-toggle"
                                                    name="notifications[{{ $alert->id }}]"
                                                    {{ $alert->user_status === 1 || is_null($alert->user_status) ? 'checked' : '' }}>

                                                <input type="hidden" name="template_ids[]" value="{{ $alert->id }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex float-end gap-2">
                                <a href="{{ route('buyerprofile', ['username' => $user->username, 'id' => encrypt($user->id)]) }}"
                                    class="btn btn-outline-success">
                                    Public profile view
                                </a>
                                <button type="submit" @if ($user->completed_step == 5) disabled @endif
                                    class="btn btn-success bg-green border-0"> Finish Profile </button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(4)">Back</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Add more steps as needed -->
        </div>
    </div>

    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal content -->
                <div class="modal-header">
                    <h5 class="modal-title">Terms & Conditions</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>
                <div class="modal-body">
                    <p style="text-align: justify">We really hope that this guide will help you understand what to
                        anticipate throughout the
                        application process and provide you with the knowledge you need to begin your professional
                        career on the platform.
                        Step one: Registered email verification
                        Wiserlance will email you once you input your contact information to ask you to confirm your
                        email address. It is crucial that you finish this step-in order to go on to the next since you
                        won't be allowed to begin the application form until you have.
                    </p>
                    <p style="text-align: justify">
                        <b>🔒 Escrow Setup</b> <br />
                        To ensure your payment is protected, we use a secured escrow service. Your funds will be held safely
                        and only released to the freelancer once you approve the completed work.
                        To initiate this process, we will share the relevant information our escrow partner.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="acceptBtn">Accept</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="rejectBtn">Reject</button>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
    <script>
        
        // Global object to store nicEditor instances
        const customNicEditors = {};

        function initializeEditorsForStep(stepNumber) {
            const elementId = `description_${stepNumber}`;
            const description = document.getElementById(elementId);

            if (description) {
                // Check if an instance already exists for this element
                if (!customNicEditors[elementId]) {
                    // If no instance exists, create a new one and store it
                    customNicEditors[elementId] = new nicEditor().panelInstance(elementId);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sessionStep = @json(session('activeStep', 1));
            const activeStep = parseInt(sessionStep) || 1;
            showStep(activeStep);
        });

        $(document).on('click', '#terms_condition', function() {
            {{--  $(document).ready(function() {  --}}
            const modal = $('#myModal');
            // Show the modal if terms are not accepted
            {{--  const termsAccepted = '{{ auth()->user()->terms_accepted ?? false }}';
    if (termsAccepted == 0) {
        }  --}}
            modal.modal('show');

            $('#acceptBtn').click(function() {
                $.ajax({
                    url: '{{ route('user.buyer.accept.terms') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        modal.modal('hide'); // Hide the modal
                        {{--  alert(response.message);   --}}
                    },
                    error: function(xhr) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            });

            $('#rejectBtn').click(function() {
                const modal = $('#myModal');
                modal.modal('hide');
                //window.location = '{{ url('/user/buyer/dashboard') }}';
            });
        });

        $(document).on('change', '#terms', function() {
            if ($(this).is(':checked')) {
                $.ajax({
                    url: '{{ route('user.buyer.accept.terms') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        modal.modal('hide');
                    },
                    error: function(xhr) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            }
        });

        function showStep(stepNumber) {
            if (stepNumber == 2) {
                const checkbox = document.getElementById("terms");
                if (!checkbox.checked) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    $('.term_condition_alert').removeClass('d-none');
                    setTimeout(() => {
                        $('.term_condition_alert').addClass('d-none');
                    }, 3000);
                    return;
                }
            }
            // Hide all step panes
            document.querySelectorAll('.step-pane').forEach(pane => {
                pane.style.display = 'none';
            });

            // Show the selected step pane
            document.getElementById(`step-${stepNumber}`).style.display = 'block';

            // Update active stepper
            document.querySelectorAll('.stepper-item').forEach(item => {
                const step = parseInt(item.getAttribute('data-step'));
                item.classList.remove('active');
                if (step < stepNumber) {
                    item.classList.add('completed');
                }
                if (step === stepNumber) {
                    item.classList.add('active');
                }
            });
            // Initialize nicEditor for the current step
            initializeEditorsForStep(stepNumber);
        }

        function navigateToStep(stepNumber) {
            const completedStep = {{ auth()->user()->completed_step ?? 0 }};

            if (stepNumber > completedStep) {
                alert('Please complete the previous steps first.');
                return;
            }
            showStep(stepNumber);
        }

        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePicPreview');
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        document.getElementById('profilePicInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePicPreview');
                    preview.src = e.target.result; // Update the image source
                };
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        });

        function setGMT(country_id) {
            $('#timezone option').each(function() {
                let optionText = $(this).text();
                let match = optionText.match(/\(([^)]+)\)/);
                if (match && match[1] === country_id) {
                    $(this).prop('selected', true);
                    $('#timezone').trigger('change');
                    return false;
                }
            });
        }
        $(document).on('change', '#bus_country', function() {
            let country_code = $(this).find(':selected').data('country_code');
            $('#country_code').val(country_code);
            console.log("country_code", country_code);
            let country_id = $(this).val();
            setGMT(country_id);
            getStates(country_id);
        });

        function getStates(country_id, selectedState = '') {
            $.ajax({
                url: '{{ route('get.country.states') }}',
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: country_id
                },
                success: function(response) {
                    $('#state').empty().append('<option value="">Select State</option>');
                    $.each(response.states, function(key, value) {
                        $('#state').append(
                            '<option data-code="' + value?.code + '" value="' + value?.name + '">' +
                            value?.name + '</option>'
                        );
                    });

                    if (selectedState) {
                        $('#state').val(selectedState);
                    }
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            let country_id = $('#bus_country').val();
            const selected_state = @json($ucinfo?->state);

            setGMT(country_id);
            getStates(country_id, selected_state);
        });
    </script>
@endsection
