@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
<style>
    .loading-spinner {
        text-align: center;
        padding: 40px;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #28a745;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .nicEdit-panelContain,
    .nicEdit-main,
    .nicEdit-container,
    .nicEdit-panel {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box;
    }

    .nicEdit-main {
        min-height: 200px !important;
        /* match your textarea height */
        width: 100% !important;
    }
</style>
@section('content')
    <div class="card bg-white p-md-2 p-md-3 ms-md-1 mt-md-2 shadow-sm border rounded">
        <!-- Stepper -->
        <div class="stepper-wrapper mt-2 fs-14 bg-light shadow-sm p-3 rounded border mx-4">
            @php
                $completedStep = auth()->user()->completed_step;
            @endphp

            <div class="stepper-item {{ $completedStep >= 1 ? 'completed' : '' }} " data-step="1"
                @if ($completedStep >= 1) onclick="navigateToStep(1)" @endif>
                <div class="step-counter">1</div>
                <div class="step-name">Personal</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 2 ? 'completed' : '' }} " data-step="2"
                @if ($completedStep >= 2) onclick="navigateToStep(2)" @endif>
                <div class="step-counter">2</div>
                <div class="step-name">Identity Verification</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 3 ? 'completed' : '' }} " data-step="3"
                @if ($completedStep >= 3) onclick="navigateToStep(3)" @endif>
                <div class="step-counter">3</div>
                <div class="step-name">Education</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 4 ? 'completed' : '' }} " data-step="4"
                @if ($completedStep >= 4) onclick="navigateToStep(4)" @endif>
                <div class="step-counter">4</div>
                <div class="step-name">Certification</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 5 ? 'completed' : '' }} " data-step="5"
                @if ($completedStep >= 5) onclick="navigateToStep(5)" @endif>
                <div class="step-counter">5</div>
                <div class="step-name">Language</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 6 ? 'completed' : '' }} " data-step="6"
                @if ($completedStep >= 6) onclick="navigateToStep(6)" @endif>
                <div class="step-counter">6</div>
                <div class="step-name">Occupation</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 7 ? 'completed' : '' }} " data-step="7"
                @if ($completedStep >= 7) onclick="navigateToStep(7)" @endif>
                <div class="step-counter">7</div>
                <div class="step-name">Skills</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 8 ? 'completed' : '' }} " data-step="8"
                @if ($completedStep >= 8) onclick="navigateToStep(8)" @endif>
                <div class="step-counter">8</div>
                <div class="step-name">Portfolio</div>
            </div>
            <div class="stepper-item {{ $completedStep >= 9 ? 'completed' : '' }} " data-step="9"
                @if ($completedStep >= 9) onclick="navigateToStep(9)" @endif>
                <div class="step-counter">9</div>
                <div class="step-name">Notifications</div>
            </div>
        </div>
        <!-- Step Content -->
        <div id="step-content">
            <!-- Step 1 Content -->
            <div id="step-1" class="step-pane active">
                <!-- Profile Image Upload Section -->
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">Providing real personal information on your
                        profile is essential for building trust,
                        verifying identity, increasing credibility, and avoiding potential legal issues.
                        You should always be transparent and honest about your personal information to maximise
                        your chances of getting hired and building a successful career. </p>
                </center>
                <!-- Profile Image Upload Section -->
                <form action="{{ route('profile.personal_info') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="profile-upload position-relative">
                            <label for="profilePicInput" class="position-relative">
                                <!-- Profile Picture Preview -->
                                <div class="profile-picture-container rounded-circle overflow-hidden"
                                    style="width: 160px; height: 160px;">
                                    <img id="profilePicPreview"
                                        src="{{ $user->image && Storage::disk('public')->exists($user->image) ? Storage::url($user->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}"
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
                                <input type="text" id="designation" name="designation"
                                    value="{{ __($user->designation) }}" class="form-control"
                                    placeholder="e.g.Graphic Designer | Web Developer" required />
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" id="address" name="address"
                                    value="{{ __($user->address->address) }}" placeholder="Address" class="form-control"
                                    required />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 col-md-6 mb-3">
                                <select id="country" name="country" class="form-select" required>
                                    <option value="" disabled {{ !$user->address->country ? 'selected' : '' }}>
                                        @lang('Select Country')</option>

                                    @foreach ($countries as $code => $country)
                                        <option value="{{ $country->country }}"
                                            {{ $user->address->country == $country->country ? 'selected' : '' }}>
                                            {{ $country->country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <select id="state" name="state" class="form-select" required>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3">
                                <select id="city" name="city" class="form-select" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" id="zip" name="zip" class="form-control"
                                    value="{{ __($user->address->zip) }}" placeholder="Zip Code" required />
                            </div>
                        </div>
                        <!-- Save and Next Buttons -->
                        <div class="d-flex justify-content-end align-items-center gap-2 mt-4">
                            <p class="mb-0">Please Save before proceeding >></p>
                            <button type="submit" class="btn  btn-outline-dark"> Save </button>
                            <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(2)"
                                {{ $completedStep < 1 ? 'disabled' : '' }}>Next</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="step-2" class="step-pane">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary"></p>
                    <div class="mt-4">
                        @if ($user->persona == 0)
                            <p>Click "Start Verification" to begin the identity verification process.</p>
                            <div id="persona_verification">
                                <button id="start-verification-btn" class="btn btn-success"
                                    onclick="startPersonaVerification()">
                                    Start Verification
                                </button>
                            </div>
                        @else
                            <div style="text-align: center; padding: 40px; color: #28a745;">
                                <h4>✅ Verification Completed.</h4>
                                <p>You can now proceed to the next step.</p>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <button type="button" class="btn btn-success bg-green border-0"
                                    onclick="showStep(1)">
                                    Back
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <p class="mb-0">Please Save before proceeding >></p>
                                <button type="submit" class="btn btn-outline-success">
                                    Save
                                </button>
                                <button type="button" class="btn btn-success bg-green border-0"
                                    onclick="showStep(3)">
                                    Next
                                </button>
                            </div>

                        </div>
                    </div>
                </center>
            </div>
            <!-- Step 3 Content -->
            <div id="step-3" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        Including educational information on your profile can help you demonstrate your qualifications,
                        achievements, and commitment to learning, and can give you a competitive edge in the job market.
                    </p>
                </center>
                <div class="card-body">
                    <form id="educationForm" action="{{ route('profile.education') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="education-container">
                            @forelse ($user->educations as $index => $education)
                                <div class="form-section">
                                    <input type="hidden" name="education_ids[]" value="{{ $education->id }}">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="main-color">EDUCATION</h6>
                                        <a type="button" class="delete-btn text-danger"
                                            onclick="removeEducationSection(this)">
                                            <i class="fa-regular fa-circle-xmark fs-5"></i>
                                        </a>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="namedegree[]" class="form-control"
                                                placeholder="Degree Name"
                                                value="{{ old('namedegree.' . $index, $education->degree) }}" required>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <input type="number" class="form-control" name="yeardegree[]"
                                                placeholder="Year of Degree" min="1900" max="{{ date('Y') }}"
                                                step="1"
                                                value="{{ old('yeardegree.' . $index, $education->yeardegree) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="namecollege[]" class="form-control"
                                                placeholder="Name of College/University"
                                                value="{{ old('namecollege.' . $index, $education->institue) }}" required>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <input type="text" name="countrycollege[]" class="form-control"
                                                placeholder="Country of College/University"
                                                value="{{ old('countrycollege.' . $index, $education->countryinstitue) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-4" id="transBox">
                                        <h6 class="main-color">DEGREE TRANSCRIPT</h6>
                                        <div id="transcript_error"></div>
                                        <input type="file" name="degrecertificate[]" class="form-control"
                                            accept=".jpeg, .jpg, .png, .pdf" onchange="check_file_size_anmb(this)">
                                        @if ($education->degrecertificate)
                                            <p class="mt-2">
                                                Uploaded File:
                                                <a href="{{ Storage::url($education->degrecertificate) }}"
                                                    target="_blank">
                                                    View Transcript
                                                </a>
                                            </p>
                                        @endif
                                    </div>

                                    <label>
                                        <input type="checkbox" name="showdegree[]" value="1"
                                            {{ old('showdegree.' . $index, $education->showdegree) ? 'checked' : '' }}>
                                        Show Degree on Profile
                                    </label>
                                    <hr>
                                </div>
                            @empty
                                {{-- Default empty form when no education exists --}}
                                <div class="form-section">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="main-color">EDUCATION</h6>
                                        <a type="button" class="delete-btn text-danger"
                                            onclick="removeEducationSection(this)">
                                            <i class="fa-regular fa-circle-xmark fs-5"></i>
                                        </a>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="namedegree[]" class="form-control"
                                                placeholder="Degree Name" required>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <input type="number" class="form-control" name="yeardegree[]"
                                                placeholder="Year of Degree" min="1900" max="{{ date('Y') }}"
                                                step="1" value="{{ date('Y') }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="namecollege[]" class="form-control"
                                                placeholder="Name of College/University" required>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <input type="text" name="countrycollege[]" class="form-control"
                                                placeholder="Country of College/University" required>
                                        </div>
                                    </div>

                                    <div class="mb-4" id="transBox">
                                        <h6 class="main-color">DEGREE TRANSCRIPT</h6>
                                        <div id="transcript_error"></div>
                                        <input type="file" name="degrecertificate[]" class="form-control"
                                            accept=".jpeg, .jpg, .png, .pdf" onchange="check_file_size_anmb(this)">
                                    </div>

                                    <label>
                                        <input type="checkbox" name="showdegree[]" value="1">
                                        Show Degree on Profile
                                    </label>
                                    <hr>
                                </div>
                            @endforelse
                        </div>

                        <template id="education-template">
                            <div class="form-section">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <h6 class="main-color">EDUCATION</h6>
                                    <a type="button" class="delete-btn text-danger" onclick="removeEducationSection(this)">
                                        <i class="fa-regular fa-circle-xmark fs-5"></i>
                                    </a>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 mb-3">
                                        <input type="text" name="namedegree[]" class="form-control"
                                            placeholder="Degree Name" required>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2">
                                        <input type="number" class="form-control" name="yeardegree[]"
                                            placeholder="Year of Degree" min="1900" max="{{ date('Y') }}"
                                            step="1" value="{{ date('Y') }}" required>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 mb-3">
                                        <input type="text" name="namecollege[]" class="form-control"
                                            placeholder="Name of College/University" required>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2">
                                        <input type="text" name="countrycollege[]" class="form-control"
                                            placeholder="Country of College/University" required>
                                    </div>
                                </div>

                                <div class="mb-4" id="transBox">
                                    <h6 class="main-color">DEGREE TRANSCRIPT</h6>
                                    <div id="transcript_error"></div>
                                    <input type="file" name="degrecertificate[]" class="form-control"
                                        accept=".jpeg, .jpg, .png, .pdf" onchange="check_file_size_anmb(this)">
                                </div>

                                <label>
                                    <input type="checkbox" name="showdegree[]" value="1">
                                    Show Degree on Profile
                                </label>
                                <hr>
                            </div>
                        </template>


                        <!-- Add More Button -->
                        <div class="btn btn-light w-100 mt-2 text-dgreen2 border text-start"
                            onclick="addMoreEducationForm()">
                            <i class="fas fa-plus-circle"></i> Add More Section
                        </div>

                        <!-- Save and Navigation Buttons -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-end align-items-center float-end gap-2">
                                <p class="mb-0">Please Save before proceeding >></p>
                                <button type="submit" class="btn btn-outline-success">Save</button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(4)"
                                    {{ $completedStep < 2 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(2)">Back</button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Step 4 Content -->
            <div id="step-4" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        Including certification information on your profile can help you demonstrate your expertise,
                        commitment to professional development, and knowledge of industry standards, and can give
                        you a competitive edge in the job market.
                    </p>
                </center>
                <div class="card-body">
                    <form id="certificationForm" action="{{ route('profile.certifications') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div id="certificate-container">
                            @forelse ($user->certifications as $index => $certification)
                                <div class="form-section">
                                    <input type="hidden" name="certificate_ids[]" value="{{ $certification->id }}">

                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="main-color">CERTIFICATION</h6>
                                        <a type="button" class="delete-btn text-danger" onclick="removeCertificateSection(this)">
                                            <i class="fa-regular fa-circle-xmark fs-5"></i>
                                        </a>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="namecertificate[]" class="form-control"
                                                placeholder="Certificate Name"
                                                value="{{ old('namecertificate.' . $index, $certification->namecertificate) }}" required>
                                        </div>

                                        <div class="col-12 col-md-6 mb-2">
                                            <input type="number" name="yearcertificate[]" class="form-control"
                                                placeholder="Year of Certification" min="1900" max="{{ date('Y') }}"
                                                step="1"
                                                value="{{ old('yearcertificate.' . $index, $certification->yearcertificate) }}" required />
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="nameinstitue[]" class="form-control"
                                                placeholder="Name of Institute"
                                                value="{{ old('nameinstitue.' . $index, $certification->nameinstitue) }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-4" id="transBox">
                                        <h6 class="main-color">Certificate Transcript</h6>
                                        <input type="file" name="certificate[]" class="form-control"
                                            accept=".jpeg, .jpg, .png, .pdf" onchange="check_file_size_anmb(this)">
                                        @if ($certification->certificate)
                                            <p class="mt-2">
                                                Uploaded File:
                                                <a href="{{ asset('storage/' . $certification->certificate) }}" target="_blank">
                                                    View Certificate
                                                </a>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="checkbox" name="showcertificate[{{ $index }}]" value="1"
                                                {{ old('showcertificate.' . $index, $certification->showcertificate) ? 'checked' : '' }}>
                                            Show Certificate on Profile
                                        </div>
                                    </div>

                                    <hr>
                                </div>
                            @empty
                                {{-- Default form when no certifications exist --}}
                                <div class="form-section">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="main-color">CERTIFICATION</h6>
                                        <a type="button" class="delete-btn text-danger" onclick="removeCertificateSection(this)">
                                            <i class="fa-regular fa-circle-xmark fs-5"></i>
                                        </a>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="namecertificate[]" class="form-control"
                                                placeholder="Certificate Name" required>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <input type="number" name="yearcertificate[]" class="form-control"
                                                placeholder="Year of Certification" min="1900" max="{{ date('Y') }}"
                                                step="1" value="{{ date('Y') }}" required />
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="text" name="nameinstitue[]" class="form-control"
                                                placeholder="Name of Institute" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="main-color">Certificate Transcript</h6>
                                        <input type="file" name="certificate[]" class="form-control"
                                            accept=".jpeg, .jpg, .png, .pdf" onchange="check_file_size_anmb(this)">
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <input type="checkbox" name="showcertificate[]" value="1">
                                            Show Certificate on Profile
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            @endforelse
                        </div>

                        <!-- Template for new sections -->
                        <template id="certificate-template">
                            <div class="form-section">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <h6 class="main-color">CERTIFICATION</h6>
                                    <a type="button" class="delete-btn text-danger" onclick="removeCertificateSection(this)">
                                        <i class="fa-regular fa-circle-xmark fs-5"></i>
                                    </a>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 mb-3">
                                        <input type="text" name="namecertificate[]" class="form-control"
                                            placeholder="Certificate Name" required>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2">
                                        <input type="number" name="yearcertificate[]" class="form-control"
                                            placeholder="Year of Certification" min="1900" max="{{ date('Y') }}"
                                            step="1" value="{{ date('Y') }}" required />
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 mb-3">
                                        <input type="text" name="nameinstitue[]" class="form-control"
                                            placeholder="Name of Institute" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <h6 class="main-color">Certificate Transcript</h6>
                                    <input type="file" name="certificate[]" class="form-control"
                                        accept=".jpeg, .jpg, .png, .pdf" onchange="check_file_size_anmb(this)">
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 mb-3">
                                        <input type="checkbox" name="showcertificate[]" value="1">
                                        Show Certificate on Profile
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </template>

                        <!-- Add More Button -->
                        <div class="btn btn-light w-100 mt-2 text-dgreen2 border text-start"
                            onclick="addMoreCertificateForm()">
                            <i class="fas fa-plus-circle"></i> Add More Section
                        </div>

                        <!-- Save and Navigation Buttons -->
                        <div class="mt-4">
                            <div class="d-flex float-end gap-2 justify-content-end align-items-center">
                                <p class="mb-0">Please Save before proceeding >></p>
                                <button type="submit" class="btn btn-outline-success">Save</button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(5)"
                                    {{ $completedStep < 3 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(3)">Back</button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Step 4 Content -->
            <div id="step-5" class="step-pane" style="display: none;">
                @php
                    $lang = DB::table('profile_languages')->get();
                    $degree = DB::table('profile_degrees')->get();
                @endphp
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        Including your language skills on your profile can help you demonstrate your strengths and
                        suitability f
                        or a particular job, and give you a competitive edge in the job market.
                    </p>
                </center>

                <div class="card-body">
                    <form id="languageForm" action="{{ route('profile.languages') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div id="language-container">
                            {{-- If user already has saved languages --}}
                            @if ($user->languages->count())
                                @foreach ($user->languages as $index => $userLang)
                                    <div class="form-section">
                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                            <h6 class="main-color fs-5">LANGUAGE</h6>
                                            <a type="button" class="delete-btn text-danger" onclick="removeLanguageSection(this)">
                                                <i class="fa-regular fa-circle-xmark fs-5"></i>
                                            </a>
                                        </div>

                                        <input type="hidden" name="language_ids[]" value="{{ $userLang->id }}">

                                        <div class="row mb-2">
                                            <div class="col-12 col-md-6 mb-3">
                                                <select class="form-select" name="langname[]" required>
                                                    <option value="">Select Language</option>
                                                    @foreach ($lang as $langs)
                                                        <option value="{{ $langs->name }}"
                                                            {{ old('langname.' . $index, $userLang->name) == $langs->name ? 'selected' : '' }}>
                                                            {{ $langs->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-6 mb-2">
                                                <select class="form-select" name="langvalue[]" required>
                                                    <option value="">Select Language Level</option>
                                                    @foreach (['Basic', 'Conversational', 'Fluent', 'Native'] as $level)
                                                        <option value="{{ $level }}"
                                                            {{ old('langvalue.' . $index, $userLang->type) == $level ? 'selected' : '' }}>
                                                            {{ $level }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            {{-- If user has no languages and no old input → show 1 empty form --}}
                            @elseif (!old('langname'))
                                <div class="form-section">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="main-color fs-5">LANGUAGE</h6>
                                        <a type="button" class="delete-btn text-danger" style="display:none;"
                                            onclick="removeLanguageSection(this)">
                                            <i class="fa-regular fa-circle-xmark fs-5"></i>
                                        </a>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 mb-3">
                                            <select class="form-select" name="langname[]" required>
                                                <option value="">Select Language</option>
                                                @foreach ($lang as $langs)
                                                    <option value="{{ $langs->name }}">{{ $langs->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6 mb-2">
                                            <select class="form-select" name="langvalue[]" required>
                                                <option value="">Select Language Level</option>
                                                <option value="Basic">Basic</option>
                                                <option value="Conversational">Conversational</option>
                                                <option value="Fluent">Fluent</option>
                                                <option value="Native">Native</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- If there are old input values (after validation error) --}}
                            @if (old('langname'))
                                @foreach (old('langname') as $i => $oldLang)
                                    @if (!isset($user->languages[$i]))
                                        <div class="form-section">
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <h6 class="main-color fs-5">LANGUAGE</h6>
                                                <a type="button" class="delete-btn text-danger" onclick="removeLanguageSection(this)">
                                                    <i class="fa-regular fa-circle-xmark fs-5"></i>
                                                </a>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-12 col-md-6 mb-3">
                                                    <select class="form-select" name="langname[]" required>
                                                        <option value="">Select Language</option>
                                                        @foreach ($lang as $langs)
                                                            <option value="{{ $langs->name }}"
                                                                {{ $oldLang == $langs->name ? 'selected' : '' }}>
                                                                {{ $langs->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12 col-md-6 mb-2">
                                                    <select class="form-select" name="langvalue[]" required>
                                                        <option value="">Select Language Level</option>
                                                        @foreach (['Basic', 'Conversational', 'Fluent', 'Native'] as $level)
                                                            <option value="{{ $level }}"
                                                                {{ old('langvalue.' . $i) == $level ? 'selected' : '' }}>
                                                                {{ $level }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <template id="language-template">
                            <div class="form-section">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <h6 class="main-color fs-5">LANGUAGE</h6>
                                    <a type="button" class="delete-btn text-danger" style="display: none;"
                                        onclick="removeLanguageSection(this)">
                                        <i class="fa-regular fa-circle-xmark fs-5"></i>
                                    </a>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 mb-3">
                                        <select class="form-select" name="langname[]" required>
                                            <option value="">Select Language</option>
                                            @foreach ($lang as $langs)
                                                <option value="{{ $langs->name }}">{{ $langs->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6 mb-2">
                                        <select class="form-select" name="langvalue[]" required>
                                            <option value="">Select Language Level</option>
                                            <option value="Basic">Basic</option>
                                            <option value="Conversational">Conversational</option>
                                            <option value="Fluent">Fluent</option>
                                            <option value="Native">Native</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="btn btn-light w-100 text-dgreen2 border text-start" onclick="addMoreLanguageForm()">
                            <i class="fas fa-plus-circle"></i> Add More Section
                        </div>

                        <div class="mt-4">
                            <div class="d-flex float-end gap-2 justify-content-end align-items-center">
                                <p class="mb-0">Please Save before proceeding >></p>
                                <button type="submit" class="btn btn-outline-success">Save</button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(6)"
                                    {{ $completedStep < 4 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(4)">Back</button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Step 5 Content -->
            <div id="step-6" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        Create a professional profile on Wiserlance to offer services or bid on projects.
                        Set your hourly rate and choose the option that suits you. If you opt for the service option,
                        your profile won't appear in the professional listing, allowing you to create services
                        for buyers without disclosing your professional background.
                    </p>
                </center>
                <div class="card-body">
                    <form id="occupationForm" action="{{ route('profile.occupation') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div id="form-container">
                            <div class="form-section">
                                <h5>Availability</h5>
                                <h6 class="fw-semibold mt-3 mb-1 text-green">Hours Per Week</h6>
                                <p class="fs-14">Knowing how much you can work helps Wiserlance find the right jobs for
                                    you.</p>
                                <p>I can currently work</p>

                                <div class="d-xl-flex">
                                    <div class="form-check fs-14 ms-3">
                                        <input class="form-check-input" type="radio" name="weekly_hours"
                                            value="40" id="40"
                                            {{ old('weekly_hours', $user->weekly_hours) == 40 ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="40">
                                            More than 30 hrs/week
                                        </label>
                                    </div>

                                    <div class="form-check fs-14 ms-3">
                                        <input class="form-check-input" type="radio" name="weekly_hours"
                                            value="30" id="30"
                                            {{ old('weekly_hours', $user->weekly_hours) == 30 ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="30">
                                            Less than 30 hrs/week
                                        </label>
                                    </div>

                                    <div class="form-check fs-14 ms-3">
                                        <input class="form-check-input" type="radio" name="weekly_hours"
                                            value="open_offer" id="open_offer"
                                            {{ old('weekly_hours', $user->weekly_hours) == 'open_offer' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="open_offer">
                                            As needed - open to offers
                                        </label>
                                    </div>

                                    <div class="form-check fs-14 ms-3 ps-xl-5">
                                        <input class="form-check-input" type="radio" name="weekly_hours"
                                            value="none" id="none"
                                            {{ old('weekly_hours', $user->weekly_hours) == 'none' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="none">
                                            None
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-between align-items-center mt-2">
                                    <h6 class="text-green">OCCUPATION</h6>
                                    <a type="button" class="delete-btn text-danger" style="display: none;">
                                        <i class="fa-regular fa-circle-xmark fs-5"></i>
                                    </a>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12 col-md-4 mb-3">
                                        <div class="form-group">
                                            <input type="checkbox" name="professional" id="professional" value="1"
                                                {{ old('professional', $user->professional) ? 'checked' : '' }}>
                                            <label for="professional">Professional</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <div class="form-group">
                                            <input type="checkbox" name="serviceprovider" id="sp" value="1"
                                                {{ old('serviceprovider', $user->serviceprovider) ? 'checked' : '' }}>
                                            <span>&nbsp;Service Provider</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3"
                                        style="{{ old('serviceprovider', $user->serviceprovider) ? '' : 'display: none;' }}"
                                        id="hourly-rate">
                                        <div class="form-group">
                                            <input type="number" name="rate" class="form-control" min="0"
                                                id="rate-value" placeholder="@lang('Per Hour Rate')"
                                                value="{{ old('rate', $user->per_hour_rate) }}">
                                            <span>hourly rate</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6 mb-3">
                                        <select class="form-select" id="expertLevel" name="expertLevel">
                                            <option value="Beginner"
                                                {{ old('expertLevel', $user->level) == 'Beginner' ? 'selected' : '' }}>
                                                Beginner</option>
                                            <option value="Intermedite"
                                                {{ old('expertLevel', $user->level) == 'Intermedite' ? 'selected' : '' }}>
                                                Intermedite</option>
                                            <option value="Expert"
                                                {{ old('expertLevel', $user->level) == 'Expert' ? 'selected' : '' }}>Expert
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <textarea class="form-control with-light" style="height: 200px !important;width: 100% !important;"
                                        id="description_50" name="about_me" rows="5" cols="10">{{ old('about_me', $user->about_me) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex float-end gap-2 justify-content-end align-items-center">
                                <p class="mb-0">Please Save before proceeding >></p>
                                <button type="submit" class="btn btn-outline-success">Save</button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(7)"
                                    {{ $completedStep < 5 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(5)">Back</button>
                        </div>
                    </form>
                </div>

            </div>
            <!-- Step 6 Content -->
            <div id="step-7" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        Showcasing your portfolio samples on your profile can help you demonstrate your skills,
                        style, vision, and versatility, and can significantly increase your chances of getting
                        hired or winning new clients.
                    </p>
                </center>
                @php
                    // Get old or current selected values
                    $selectedCategory = old('category', $user->cat_id);
                    $selectedSubcategory = old('subcategory', $user->subcat_id);
                    $selectedChildCategory = old('childcategory', $user->childcat_id);

                    // Fetch dependent subcategories & childcategories using selected (old or user) IDs
                    $subs = $selectedCategory
                        ? DB::table('sub_categories')->where('category_id', $selectedCategory)->get()
                        : collect();
                    $childs = $selectedSubcategory
                        ? DB::table('child_categories')->where('subcat_id', $selectedSubcategory)->get()
                        : collect();
                @endphp
                <div class="card-body">
                    <form action="{{ route('profile.skills') }}" id="skillsandexpertise" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="form-container">
                            <div class="form-section">
                                <h6 class="main-color">SERVICE CATEGORIZATION</h6>

                                <div class="row mb-2">
                                    {{-- Category --}}
                                    <div class="col-md-4 mb-3">
                                        <div>
                                            <select class="form-select" name="category" id="category" required>
                                                <option disabled {{ !$selectedCategory ? 'selected' : '' }}>
                                                    @lang('Select Category Level 1')
                                                </option>
                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                                        {{ __($category->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Subcategory --}}
                                    <div class="col-md-4 mb-3">
                                        <div>
                                            <select name="subcategory" class="form-select" id="subCategorys" required>
                                                <option disabled {{ !$selectedSubcategory ? 'selected' : '' }}>
                                                    @lang('Select Category Level 2')
                                                </option>
                                                @foreach ($subs as $subCategory)
                                                    <option value="{{ $subCategory->id }}"
                                                        {{ $selectedSubcategory == $subCategory->id ? 'selected' : '' }}>
                                                        {{ __($subCategory->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Child Category --}}
                                    <div class="col-md-4 mb-3">
                                        <div class="myChildCatgry">
                                            @if ($childs->count() > 0)
                                                <select name="childcategory" class="form-select tierchildcat"
                                                    id="childCategorys" required>
                                                    <option disabled {{ !$selectedChildCategory ? 'selected' : '' }}>
                                                        @lang('Select Category Level 3')
                                                    </option>
                                                    @foreach ($childs as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ $selectedChildCategory == $category->id ? 'selected' : '' }}>
                                                            {{ __($category->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Skills section --}}
                                <div class="row">
                                    <div class="col-lg-12">Skills</div>
                                    <div id="skills_result" class="col-xl-12 col-lg-12 form-group"></div>
                                    <div class="col-xl-12 col-lg-12">
                                        <ul class="nav nav-tabs" role="tablist" id="result"></ul>
                                        <div class="tab-content" id="demos"></div>
                                    </div>
                                </div>

                                {{-- Extra Skills --}}
                                <div class="row mt-3">
                                    <div class="col-lg-12">Extra Skills</div>
                                    <div id="skills_result_extra" class="col-xl-12 col-lg-12 form-group mb-1"></div>
                                    <div class="col-xl-12 col-lg-12">
                                        <div class="form-group">
                                            <input onkeyup="getSkillbuname(this.value)" id="search_skill"
                                                class="form-control rounded" type="text" autocomplete="off"
                                                placeholder="Type to Search ...">
                                        </div>
                                    </div>
                                    <div id="skil_result" class="col-xl-12 col-lg-12 form-group"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Save and navigation --}}
                        <div class="mt-4">
                            <div class="d-flex float-end gap-2 justify-content-end align-items-center">
                                <p class="mb-0">Please Save before proceeding >></p>
                                <button type="submit" class="btn btn-outline-success">Save</button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(8)"
                                    {{ $completedStep < 6 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(6)">Back</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="step-8" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        Showcasing your portfolio samples on your profile can help you demonstrate your skills,
                        style, vision, and versatility, and can significantly increase your chances of getting
                        hired or winning new clients.
                    </p>
                </center>

                <div class="card-body">
                    <form id="portfolioForm" action="{{ route('profile.portfolio') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- If user has no portfolio and no old input, show one empty form section by default --}}
                        @if ($user->portfolioprofile->isEmpty() && !old('ptitle'))
                            <div class="form-section">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <h6 class="main-color">PORTFOLIO</h6>
                                    <a type="button" class="delete-btn text-danger" onclick="removePortfolioSection(this)">
                                        <i class="fa-regular fa-circle-xmark fs-5"></i>
                                    </a>
                                </div>

                                <div class="mb-4">
                                    <input type="text" class="form-control" name="ptitle[]" placeholder="Title*" required>
                                </div>

                                <div class="mb-4">
                                    <div class="">
                                        <div class="editable">Add Description*</div>
                                        <div class="btn-toolbar mt-4 style-box" role="toolbar">
                                            <textarea class="form-control with-light nicEdit" style="height: 200px !important;width: 100%;" name="pdesc[]" rows="5" id="description_200"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <h1 class="main-color fs-5">PROJECTS</h1>
                                <p>You must add at least one file to your project*</p>

                                <div class="d-flex flex-wrap align-items-center gap-5 image-box">
                                    <img style="width: 288px; height: 164px; object-fit: cover; border-radius: 5px; display: none;">
                                    <label class="w-100" style="cursor: pointer;">
                                        <input type="file" accept=".jpeg, .jpg, .png" name="pimage[]" style="display: none;">
                                        <div id="img-view">
                                            <p class="fw-medium pt-5">Drag & Drop Image Or <span>Browse</span> file</p>
                                        </div>
                                    </label>
                                </div>

                                <p class="fs-14 text-secondary mt-2">Images (.jpg, .jpeg, .png) will be resized into 920x470.</p>
                            </div>
                        @endif

                        <div id="portfolio-container">
                            @foreach ($user->portfolioprofile as $index => $as)
                                <div class="form-section">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="main-color">PORTFOLIO</h6>
                                        <a type="button" class="delete-btn text-danger"
                                            onclick="removePortfolioSection(this)">
                                            <i class="fa-regular fa-circle-xmark fs-5"></i>
                                        </a>
                                    </div>
                                    <div class="mb-4">
                                        <input type="hidden" name="portfolio_ids[]"
                                            value="{{ old('portfolio_ids.' . $index, $as->id) }}">
                                        <input type="text" value="{{ old('ptitle.' . $index, $as->title) }}"
                                            name="ptitle[]" class="form-control" placeholder="Title*">
                                        @error('ptitle.' . $index)
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <div class="">
                                            <div id="coverLetter" contenteditable="true" class="editable">Add
                                                Description* </div>
                                            <div class="btn-toolbar mt-4 style-box" role="toolbar">
                                                <textarea class="form-control with-light nicEdit" style="height: 200px !important;width: 100%;"
                                                    id="description_{{ $as->id }}" name="pdesc[]" rows="5">{!! old('pdesc.' . $index, $as->description) !!}</textarea>
                                            </div>
                                        </div>
                                        @error('pdesc.' . $index)
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <h1 class="main-color fs-5">PROJECTS</h1>
                                    <p>You must add at least one file to your project*</p>
                                    <div class="d-flex flex-wrap align-items-center gap-5" id="image-container">
                                        <div class="image-box position-relative">
                                            <img id="image-{{ $as->id }}" src="{{ Storage::url($as->image) }}"
                                                style="width: 288px; height: 164px; object-fit: cover; border-radius: 5px;">
                                            <button type="button"
                                                class="edit-icon btn btn-sm btn-light position-absolute top-0 end-0 mt-1 me-1"
                                                onclick="document.getElementById('file-input-{{ $as->id }}').click()">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <input type="file" id="file-input-{{ $as->id }}"
                                                accept=".jpeg, .jpg, .png" name="pimage[]" hidden
                                                onchange="updateImage(event, '{{ $as->id }}')">
                                        </div>
                                    </div>
                                    @error('pimage.' . $index)
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <p class="fs-14 text-secondary mt-2">Images (.jpg, .jpeg, .png) image will resized into
                                        920x470.</p>
                                </div>
                            @endforeach

                            {{-- Add new portfolio sections if old input exists --}}
                            @if (old('ptitle'))
                                @foreach (old('ptitle') as $oldIndex => $oldTitle)
                                    @if ($oldIndex >= count($user->portfolioprofile))
                                        <div class="form-section">
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <h6 class="main-color">PORTFOLIO</h6>
                                                <a type="button" class="delete-btn text-danger"
                                                    onclick="removePortfolioSection(this)">
                                                    <i class="fa-regular fa-circle-xmark fs-5"></i>
                                                </a>
                                            </div>
                                            <div class="mb-4">
                                                <input type="text" class="form-control" name="ptitle[]"
                                                    value="{{ old('ptitle.' . $oldIndex) }}" placeholder="Title*"
                                                    required>
                                                @error('ptitle.' . $oldIndex)
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <div class="">
                                                    <div class="editable">Add Description*</div>
                                                    <div class="btn-toolbar mt-4 style-box" role="toolbar">
                                                        <textarea class="form-control with-light nicEdit" id="description_old_{{ $oldIndex }}"
                                                            style="height: 200px !important;width: 100%;" name="pdesc[]" rows="5">{{ old('pdesc.' . $oldIndex) }}</textarea>
                                                    </div>
                                                </div>
                                                @error('pdesc.' . $oldIndex)
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <h1 class="main-color fs-5">PROJECTS</h1>
                                            <p>You must add at least one file to your project*</p>
                                            <div class="d-flex flex-wrap align-items-center gap-5 image-box"
                                                id="old-image-container-{{ $oldIndex }}">
                                                <img id="old-image-preview-{{ $oldIndex }}"
                                                    style="width: 288px; height: 164px; object-fit: cover; border-radius: 5px; display: none;">
                                                <label class="w-100" id="old-upload-label-{{ $oldIndex }}"
                                                    style="cursor: pointer;">
                                                    <input type="file" accept=".jpeg, .jpg, .png"
                                                        name="pimage[]" style="display: none;"
                                                        onchange="previewOldImage(event, {{ $oldIndex }})">
                                                    <div id="img-view">
                                                        <p class="fw-medium pt-5">Drag & Drop Image Or <span>Browse</span>
                                                            file</p>
                                                    </div>
                                                </label>
                                            </div>
                                            @error('pimage.' . $oldIndex)
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <p class="fs-14 text-secondary mt-2">Images (.jpg, .jpeg, .png) will be resized
                                                into
                                                920x470.</p>
                                        </div>
                                    @endif
                                @endforeach
                            @endif

                            <template id="portfolio-template">
                                <div class="form-section">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="main-color">PORTFOLIO</h6>
                                        <a type="button" class="delete-btn text-danger"
                                            onclick="removePortfolioSection(this)">
                                            <i class="fa-regular fa-circle-xmark fs-5"></i>
                                        </a>
                                    </div>
                                    <div class="mb-4">
                                        <input type="text" class="form-control" name="ptitle[]" placeholder="Title*"
                                            required>
                                    </div>
                                    <div class="mb-4">
                                        <div class="">
                                            <div class="editable">Add Description*</div>
                                            <div class="btn-toolbar mt-4 style-box" role="toolbar">
                                                <textarea class="form-control with-light nicEdit" style="height: 200px !important;width: 100%;" name="pdesc[]"
                                                    rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h1 class="main-color fs-5">PROJECTS</h1>
                                    <p>You must add at least one file to your project*</p>
                                    <div class="d-flex flex-wrap align-items-center gap-5 image-box">
                                        <img
                                            style="width: 288px; height: 164px; object-fit: cover; border-radius: 5px; display: none;">
                                        <label class="w-100" style="cursor: pointer;">
                                            <input type="file" accept=".jpeg, .jpg, .png" name="pimage[]"
                                                style="display: none;">
                                            <div id="img-view">
                                                <p class="fw-medium pt-5">Drag & Drop Image Or <span>Browse</span> file</p>
                                            </div>
                                        </label>
                                    </div>
                                    <p class="fs-14 text-secondary mt-2">Images (.jpg, .jpeg, .png) will be resized into
                                        920x470.</p>
                                </div>
                            </template>
                        </div>

                        <div class="btn btn-light w-100 text-dgreen2 border text-start" onclick="addMorePortfolioForm()">
                            <span><i class="fas fa-plus-circle"></i> Add More Section</span>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex float-end gap-2 justify-content-end align-items-center">
                                <p class="mb-0">Please Save before proceeding >></p>
                                <button type="submit" class="btn btn-outline-success"> Save </button>
                                <button type="button" class="btn btn-success bg-green border-0" onclick="showStep(9)"
                                    {{ $completedStep < 7 ? 'disabled' : '' }}>Next</button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(7)">Back</button>
                        </div>
                    </form>
                </div>

            </div>
            <div id="step-9" class="step-pane" style="display: none;">
                <center>
                    <p class="fs-14 text-center w-75 mt-2 text-secondary">
                        The way we get information and remain connected has been revolutionised by notification alerts.
                        They are essential tools that increase output, keep us informed, and improve our quality of work
                        in general. Customize your notification settings to suit your needs and preferences, and enjoy the
                        benefits of this powerful communication tool.
                    </p>
                </center>
                <div class="card-body">
                    <form action="{{ route('profile.notifications') }}" id="notifications" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="form-container">
                            <!-- The first form group -->
                            <div class="form-section">
                                <h6 class="main-color mb-3">Notification Settings</h6>
                                {{-- <div class="row mb-2">
                                    <div class="col-md-6 form-check form-switch ms-2">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Specified Notification</label>
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
                                            onchange="loadNotifications(this.checked)" {{ $userAlertsCount > 0 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-md-5  form-check form-switch float-end ps-5 ms-4">
                                        <label class="form-check-label " for="flexSwitchCheckChecked">All Notification</label>
                                        <input class="form-check-input " type="checkbox" role="switch" id="flexSwitchCheckChecked"
                                            onchange="toggleAllNotifications(this.checked)"
                                            {{ $userAlertsCount == 0 ? 'checked' : '' }}>
                                    </div>
                                </div> --}}
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

                                                <input type="hidden" name="template_ids[]"
                                                    value="{{ $alert->id }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex float-end gap-2">
                                <a target="_blank" href="{{ route('profile', ['username' => $user->username, 'id' => encrypt($user->id)]) }}"
                                    class="btn btn-outline-success">
                                    Public profile view
                                </a>
                                <button type="submit" class="btn btn-success bg-green border-0"> Finish Profile </button>
                            </div>
                            <button type="button" class="btn btn-success bg-green border-0"
                                onclick="showStep(7)">Back</button>
                        </div>
                    </form>
                </div>
            </div>
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
                    <p>We really hope that this guide will help you understand what to anticipate throughout the
                        application process and provide you with the knowledge you need to begin your professional
                        career on the platform.
                        Step one: Registered email verification
                        Wiserlance will email you once you input your contact information to ask you to confirm your
                        email address. It is crucial that you finish this step-in order to go on to the next since you
                        won't be allowed to begin the application form until you have.
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
    <script>
        let verificationStarted = false;

        function startPersonaVerification() {
            if (verificationStarted) return;
            const container = document.getElementById('persona_verification');

            // Show loading
            container.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner"></div>
                    <p>Initializing verification...</p>
                </div>
            `;

            try {
                // Personna JS
                const personaClient = new Persona.Client({
                    templateId: 'itmpl_2fubiYToteN9XLgjtkHZ3gdqAnUq', // 🔑 your Inquiry Template ID
                    environmentId: 'env_sbQENueYfk9i7tWASYjvdbvU8CdC', // 🔑 Sandbox Environment ID
                    onLoad: () => {
                        console.log("📦 Persona client loaded");
                    },
                    onReady: () => {
                        console.log("✅ Persona client ready");
                        personaClient.open({
                            camera: true
                        });
                    },
                    onError: (err) => {
                        console.error("❌ Persona client error:", err);
                        showPersonaError(err);
                    },
                    onComplete: ({
                        inquiryId,
                        status,
                        fields
                    }) => {
                        console.log(`✅ Inquiry ${inquiryId} completed with status ${status}`);
                        showPersonaSuccess(status);
                        changePersonaStatus(status, inquiryId);

                    },
                    onCancel: ({
                        inquiryId,
                        sessionToken
                    }) => {
                        console.log("❌ Verification cancelled");
                        showPersonaCancel();
                    }
                });

                verificationStarted = true;
            } catch (error) {
                console.error("Failed to initialize Persona:", error);
                showPersonaError(error);
            }
        }

        function showPersonaSuccess(status) {
            const container = document.getElementById('persona_verification');
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #28a745;">
                    <h4>✅ Verification Completed</h4>
                    <p>Status: ${status}</p>
                    <p>You can now proceed to the next step.</p>
                </div>
            `;
        }

        function showPersonaError(error) {
            const container = document.getElementById('persona_verification');
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #dc3545;">
                    <h4>❌ Verification Error</h4>
                    <p>There was an error loading the verification process.</p>
                    <button class="btn btn-outline-success" onclick="location.reload()">Try Again</button>
                </div>
            `;
        }

        function showPersonaCancel() {
            const container = document.getElementById('persona_verification');
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #ffc107;">
                    <h4>⚠️ Verification Cancelled</h4>
                    <p>The verification process was cancelled.</p>
                    <button class="btn btn-success" onclick="location.reload()">Start Over</button>
                </div>
            `;
        }
        const customNicEditors = {};

        function initializeExistingEditors() {
            // Find all existing textareas with IDs that start with 'description_'
            const existingTextareas = document.querySelectorAll('[id^="description_"]:not(.nicEdit-main)');

            existingTextareas.forEach(textarea => {
                const elementId = textarea.id;

                // Check if an instance already exists for this element
                if (!customNicEditors[elementId] && textarea.offsetParent !== null) {
                    // Only initialize if the textarea is visible
                    try {
                        // Create a new nicEditor instance and store it
                        customNicEditors[elementId] = new nicEditor({
                            fullPanel: true,
                            xhtml: true
                        }).panelInstance(elementId);

                        // Force width styling after initialization
                        setTimeout(() => {
                            const editorInstance = customNicEditors[elementId];
                            if (editorInstance && editorInstance.elm) {
                                // Apply width to the editor container
                                const editorContainer = editorInstance.elm.parentNode;
                                if (editorContainer) {
                                    editorContainer.style.width = '100% !important';
                                    editorContainer.style.minWidth = '100% !important';
                                }

                                // Apply width to the iframe
                                const iframe = editorContainer.querySelector('iframe');
                                if (iframe) {
                                    iframe.style.width = '100% !important';
                                    iframe.style.minWidth = '100% !important';
                                }

                                // Apply width to the textarea itself
                                editorInstance.elm.style.width = '100% !important';
                                editorInstance.elm.style.minWidth = '100% !important';
                            }
                        }, 100);

                    } catch (error) {
                        console.error('Error initializing nicEditor for', elementId, error);
                    }
                }
            });
        }

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


        function changePersonaStatus(status, inquiry_id) {
            $.ajax({
                url: '{{ route('persona') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    inquiry_id: inquiry_id
                },
                success: function(response) {},
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        }


        function updateImage(event, imageId) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;

                img.onload = function() {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");

                    // Set the canvas size to 920x470
                    const targetWidth = 920;
                    const targetHeight = 470;
                    canvas.width = targetWidth;
                    canvas.height = targetHeight;

                    // Resize the image on the canvas
                    ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

                    // Convert the resized image to a data URL
                    const resizedImage = canvas.toDataURL("image/jpeg", 0.9);

                    // Display the resized image in the UI
                    document.getElementById(`image-${imageId}`).src = resizedImage;
                };
            };
            reader.readAsDataURL(file);
        }

        function handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;

                img.onload = function() {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");

                    // Set the fixed size for the canvas
                    const targetWidth = 920;
                    const targetHeight = 470;
                    canvas.width = targetWidth;
                    canvas.height = targetHeight;

                    // Resize image to fit canvas
                    ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

                    // Convert the resized image to a data URL
                    const resizedImage = canvas.toDataURL("image/jpeg", 0.9);

                    const portfolioImage = document.getElementById('portfolio-image');
                    portfolioImage.src = resizedImage;
                    portfolioImage.style.display = 'block';

                    // Create an edit icon
                    const editButton = document.createElement("button");
                    editButton.type = "button";
                    editButton.classList.add("edit-icon", "btn", "btn-sm", "btn-light", "position-absolute",
                        "top-0", "end-0", "mt-1", "me-1");
                    editButton.innerHTML = `<i class="fa fa-pencil-alt"></i>`;
                    editButton.onclick = function() {
                        document.getElementById("input-file").click();
                    };
                    const imageBox = document.getElementById('upload-box');
                    imageBox.appendChild(editButton);

                    // Hide the placeholder text
                    const imgView = document.getElementById('img-view');
                    imgView.querySelector('p').style.display = 'none'; // Hide the <p> tag
                };
            };
            reader.readAsDataURL(file);
        }
        document.getElementById('portfolioForm').addEventListener('submit', function(e) {
            const portfolioImage = document.getElementById('portfolio-image');
            if (portfolioImage) {
                if (!portfolioImage.src || portfolioImage.src.includes("data:image") === false) {
                    e.preventDefault(); // Prevent form submission
                    alert('Please upload an image for the portfolio.'); // Custom error message
                }
            }
        });

        function showStep(stepNumber) {

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

            initializeEditorsForStep(stepNumber)
            initializeExistingEditors();

        }

        function navigateToStep(stepNumber) {
            const completedStep = {{ session('activeStep', auth()->user()->completed_step ?? 0) }};
            if (stepNumber > completedStep) {
                alert('Please complete the previous steps first.');
                return;
            }
            showStep(stepNumber);
            initializeExistingEditors();
        }

        document.getElementById('profilePicInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                // When the file is loaded, display it in the preview image
                reader.onload = function(e) {
                    document.getElementById('profilePicPreview').src = e.target.result;
                };

                // Read the uploaded file as a Data URL
                reader.readAsDataURL(file);
            }
        });

        $(document).ready(function() {
            const modal = $('#myModal');
            // Show the modal if terms are not accepted
            const termsAccepted = '{{ auth()->user()->terms_accepted ?? false }}';
            if (termsAccepted == 0) {
                modal.modal('show');
            }

            $('#acceptBtn').click(function() {
                $.ajax({
                    url: '{{ route('user.accept.terms') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        modal.modal('hide'); // Hide the modal
                    },
                    error: function(xhr) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            });

            $('#rejectBtn').click(function() {
                window.location = '{{ url('/user/seller/dashboard') }}';
            });
        });


        function addMoreEducationForm() {
            const template = document.getElementById('education-template');
            const clone = template.content.cloneNode(true); // Clone the template
            document.getElementById('education-container').appendChild(clone);
        }
        // Usage for different sections
        function addMoreCertificateForm() {
            const template = document.getElementById('certificate-template');
            const clone = template.content.cloneNode(true); // Clone the template
            document.getElementById('certificate-container').appendChild(clone);
        }

        function addMoreLanguageForm() {
            const template = document.getElementById('language-template');
            const clone = template.content.cloneNode(true); // Clone the template
            document.getElementById('language-container').appendChild(clone);

        }


        let portfolioCounter = {{ count($user->portfolioprofile) }}; // initialize based on existing count

        function addMorePortfolioForm() {
            const template = document.getElementById('portfolio-template');
            const clone = template.content.cloneNode(true);

            // Generate unique ID for the new textarea
            const newId = `description_${portfolioCounter}`;
            const textarea = clone.querySelector('textarea[name="pdesc[]"]');
            textarea.id = newId;

            // Set ID for image preview and input for JS targeting
            const image = clone.querySelector('img');
            const fileInput = clone.querySelector('input[type="file"]');
            const imageId = `image-new-${portfolioCounter}`;
            const fileInputId = `file-input-new-${portfolioCounter}`;
            image.id = imageId;
            fileInput.id = fileInputId;

            // Bind onchange event to the file input for preview
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && image) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        image.src = e.target.result;
                        image.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Append the cloned section
            document.getElementById('portfolio-container').appendChild(clone);

            // Initialize nicEditor for the new textarea
            initializeEditorsForStep(portfolioCounter);

            // Increment the counter for next time
            portfolioCounter++;
        }


        function removePortfolioSection(element) {
            const section = element.closest('.form-section');
            if (section) {
                section.remove();
            }
        }

        function updateImage(event, id) {
            const input = event.target;
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`image-${id}`).src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function handleImageUpload(event) {
            const file = event.target.files[0];
            const previewImg = event.target.closest('.image-box').querySelector('img');
            if (file && previewImg) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's an active step passed from the session
            const storedStep = localStorage.getItem('activeStep');

            // Use the stored step if available, otherwise default to the session step
            const activeStep = storedStep ? parseInt(storedStep) : @json(session('activeStep', 1));
            // Show the active step
            showStep(activeStep);

            // Clear the stored step from localStorage
            localStorage.removeItem('activeStep');
            initializeExistingEditors();

        });

        function removeEducationSection(element) {
            const section = element.closest('.form-section');
            const educationIdInput = section.querySelector('input[name="education_ids[]"]');

            if (educationIdInput && educationIdInput.value) {
                const educationId = educationIdInput.value;

                // Send AJAX request to delete the education record
                fetch(`/profile/education/delete/${educationId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the section from the DOM
                            section.remove();
                            alert('Record deleted successfully.');
                            localStorage.setItem('activeStep', data.activeStep);
                            // Reload the page after successful deletion
                            window.location.reload();
                        } else {
                            alert('Failed to delete record. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the education record.');
                    });
            } else {
                // If no education ID, just remove the section
                section.remove();
            }
        }

        function removeCertificateSection(element) {
            const section = element.closest('.form-section');
            const certificateIdInput = section.querySelector('input[name="certificate_ids[]"]');

            if (certificateIdInput && certificateIdInput.value) {
                const certificateId = certificateIdInput.value;

                // Store the current step in localStorage
                const currentStep = document.querySelector('.stepper-item').getAttribute('data-step');
                localStorage.setItem('currentStep', currentStep);

                // Send AJAX request to delete the education record
                fetch(`/profile/certificate/delete/${certificateId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the section from the DOM
                            section.remove();
                            alert('Record deleted successfully.');
                            // Reload the page after successful deletion
                            localStorage.setItem('activeStep', data.activeStep);
                            window.location.reload();
                        } else {
                            alert('Failed to delete record. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the education record.');
                    });
            } else {
                // If no education ID, just remove the section
                section.remove();
            }
        }

        function removeLanguageSection(element) {
            const section = element.closest('.form-section');
            const languageIdInput = section.querySelector('input[name="language_ids[]"]');

            if (languageIdInput && languageIdInput.value) {
                const languageId = languageIdInput.value;

                // Send AJAX request to delete the education record
                fetch(`/profile/language/delete/${languageId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the section from the DOM
                            section.remove();
                            alert('Record deleted successfully.');
                            // Reload the page after successful deletion
                            localStorage.setItem('activeStep', data.activeStep);
                            window.location.reload();
                        } else {
                            alert('Failed to delete record. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the education record.');
                    });
            } else {
                // If no education ID, just remove the section
                section.remove();
            }
        }

        function removePortfolioSection(element) {
            const section = element.closest('.form-section');
            const portfolioIdInput = section.querySelector('input[name="portfolio_ids[]"]');

            if (portfolioIdInput && portfolioIdInput.value) {
                const portfolioId = portfolioIdInput.value;

                // Send AJAX request to delete the education record
                fetch(`/profile/portfolio/delete/${portfolioId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the section from the DOM
                            section.remove();
                            alert('Record deleted successfully.');
                            localStorage.setItem('activeStep', data.activeStep);
                            // Reload the page after successful deletion
                            window.location.reload();
                        } else {
                            alert('Failed to delete record. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the education record.');
                    });
            } else {
                // If no education ID, just remove the section
                section.remove();
            }
        }

        $(document).ready(function() {
            // Check if freelancer checkbox is checked on page load
            if ($('#professional').is(':checked')) {
                $('#hourly-rate').show(); // Show hourly-rate field if freelancer is checked
            }

            // Toggle hourly-rate field visibility based on freelancer checkbox
            $('#professional').change(function() {
                if (this.checked) {
                    $('#hourly-rate').show(); // Show the field
                    $('#professional').val('1'); // Set value to 1 if checked
                } else {
                    $('#hourly-rate').hide(); // Hide the field
                    $('#professional').val('0'); // Set value to 0 if unchecked
                }
            });
        });

        function loadSubcategories(categoryId) {
            if (!categoryId) return;

            $.ajax({
                type: "GET",
                url: "{{ route('user.category') }}",
                data: {
                    category: categoryId
                },
                success: function(data) {
                    var html = '<option value="" selected disabled>Select Subcategory</option>';
                    $.each(data, function(index, item) {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $("#subCategorys").html(html);

                    // Trigger change event for subcategory to load child categories
                    $("#subCategorys").trigger('change');
                },
                error: function() {
                    alert('Failed to fetch subcategories.');
                }
            });
        }

        function loadChildCategories(subcategoryId) {
            if (!subcategoryId) return;
            $.ajax({
                type: "GET",
                url: "{{ route('user.subcategory') }}",
                data: {
                    subcategory: subcategoryId
                },
                success: function(data) {
                    var html = '<option value="" selected disabled>Select Child Category</option>';
                    $.each(data, function(index, item) {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $("#childCategorys").html(html);
                },
                error: function() {
                    alert('Failed to fetch child categories.');
                }
            });
        }

        function loadSkillCategories(subcategoryId) {
            if (!subcategoryId) return;
            $.ajax({
                type: "GET",
                url: "{{ route('user.skillcategory') }}",
                data: {
                    subcategory: subcategoryId
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {
                        html += 'No Skill Category Available';
                    } else {
                        $.each(data, function(name, id) {
                            html += `<li class="nav-item">
                                <a onclick="getskills(${id})" type="button" class="nav-link addcl" id="nav-link" data-toggle="tab" href="#tab-${id}" role="tab">
                                    ${name}
                                </a>
                            </li>`;
                        });
                    }
                    $('#result').html(html);

                    // If a skill category is already selected, trigger the click event to load skills
                    if ($('#result .nav-link').length > 0) {
                        $('#result .nav-link').first().trigger('click');
                    }
                },
                error: function() {
                    alert('Failed to fetch skill categories.');
                }
            });
        }
        // On page load, check if category is already selected and load subcategories
        // var defaultCategoryId = $("#category").val();
        // if (defaultCategoryId) {
        //     loadSubcategories(defaultCategoryId);
        // }

        // On page load, check if subcategory is already selected and load child categories
        var defaultSubcategoryId = $("#subCategorys").val();
        if (defaultSubcategoryId) {
            loadSkillCategories(defaultSubcategoryId);
        }
        $('#category').on('change', function() {
            var categoryId = $(this).val();
            loadSubcategories(categoryId);
        });
        // On change event for subcategory dropdown
        $("#subCategorys").on('change', function() {
            var subcategoryId = $(this).val();
            loadSkillCategories(subcategoryId);
        });
        const checkload = [];
        const skills_arr = [];
        const skills_name = [];
        const newskills_arr = [];
        const newskills_name = [];
        const exskills_arr = [];
        const exskills_name = [];
        const newexskills_arr = [];
        const newexskills_name = [];
        let newskillcount = 0;
        let newexskillcount = 0;
        const selectedSkills = @json($selectedSkills);
        //let button_html = '';

        function getskills(c_id) {
            if (checkload.indexOf(c_id) !== -1) {
                return
            }
            $.ajax({
                type: "GET",
                url: "{{ route('user.getskills') }}",
                data: {
                    c_id: c_id
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {

                        html += 'No Skill  Availble';
                    } else {

                        $.each(data, function(name, id, skill_category_id) {
                            //alert(category_id);
                            html +=
                                `<div class="tab-pane nbv " style="display:inline;" id="tab-'+c_id+'" role="tabpanel"><button type="button" onclick="selectskills(${id},'${name}',${c_id},false)" value="${id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${name}<i class="fa fa-plus ml-2"></i></button>&nbsp</div>`;
                        });
                    }
                    $('#demos').html(html);
                    //checkload.push(c_id);
                }
            });
        }
        $(".addcl").click(function() {
            $(".nbv").addClass("active");
            $(this).addClass("active");
        });

        function selectskills(s_id, name, c_id, check) {
            // Check if the skill is already selected
            if (skills_arr.indexOf(s_id) !== -1) {
                return; // Skill already selected, do nothing
            }

            // Add the skill to the arrays
            skills_arr.push(s_id);
            skills_name.push(name);

            // Generate HTML for the selected skills
            let button_html = '';
            for (let i = 0; i < skills_arr.length; i++) {
                button_html += `
                    <button type="button" onclick="unselectskill(${skills_arr[i]})" id="skill_btn${skills_arr[i]}" value="${skills_arr[i]}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                        ${skills_name[i]} <i class="fa fa-times ml-2"></i>
                    </button>
                    <input type="hidden" name="skills[]" id="job_skills${skills_arr[i]}" value="${skills_arr[i]}"/>
                `;
            }

            // Render the HTML in the #skills_result div
            $('#skills_result').html(button_html);

            // Clear the search input if needed
            if (check === true) {
                $('#skil_result').html('');
                $('#search_skill').val('');
            }
        }

        function gettiers(subcategory, childcategory) {
            $.ajax({
                url: "{{ url('/Search_tiers') }}",
                method: "POST",
                //dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'subcategory': subcategory,
                    'childcategory': childcategory
                },
                success: function(response) {
                    if (response != 0) {
                        var result = jQuery.parseJSON(response);
                        if (result.status == 'true') {
                            // $('.strains-current-count').html('12');

                            $('.filtered-tiers').html(result.content_data);

                            // $("#loading").hide();
                        } else {

                        }
                    }
                },
                error: function(data) {
                    alert('failure');
                }
            });
        }

        function childcattier(subcategory) {
            $('.tierchildcat').on('change', function() {
                var childcategory = $('#childCategorys').val();
                gettiers(subcategory, childcategory);
            });
        }

        function unselectskill(s_id) {
            var index = skills_arr.indexOf(s_id);
            if (index !== -1) {
                skills_arr.splice(index, 1);
                skills_name.splice(index, 1);
            }
            $('#skill_btn' + s_id).remove();
            $('#job_skills' + s_id).remove();
            renderExtraSkillsResult();
        }

        function getSkillbuname(search) {
            let html = '';
            if (search == "") {
                $('#skil_result').html('');
                $('#skil_result').css('display', 'none');
                return;
            }
            $('#skil_result').css('display', 'block');
            $.ajax({
                type: "GET",
                url: "{{ route('user.getskillsbyname') }}",
                data: {
                    search: search
                },
                success: function(data) {
                    var html = '';
                    if (data.error) {

                        //html +=
                        //    `<b><a onclick="selectnewskill('${search}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${search}</a></b><hr>`;
                        html +=
                            `<b><a onclick="selectnewskill('${search}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${search}</a></b><hr>`;
                    } else {
                        html += "<div  style='height:100px;overflow:scroll; overflow-x: hidden;'>";
                        $.each(data, function(name, id) {
                            html +=
                                `<p><b><a onclick="selectextraskills(${id},'${name}',true)" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;

                        });
                        html += '</div>';
                    }

                    $('#skil_result').html(html);
                    // checkload.push(c_id);
                }
            });
        }

        function selectextraskills(s_id, name, check) {
            if (check) {
                $('#skil_result').css('display', 'none');
            }
            let button_html = '';
            if (exskills_arr.indexOf(s_id) !== -1) {
                return
            }
            exskills_arr.push(s_id);
            exskills_name.push(name);
            for (let i = 0; i < exskills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectextraskill(' + exskills_arr[i] + ')" id="skill_btn' +
                    exskills_arr[i] + '" value="' + exskills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1" style="background:rgb(207, 217, 218)">' +
                    exskills_name[i] +
                    ' <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
                    exskills_arr[i] + '" value="' + exskills_arr[i] + '"/>';

            }
            for (let i = 0; i < newexskills_arr.length; i++) {
                button_html += '<button type="button" onclick="unselectnewskill(' + newexskills_arr[i] +
                    ')" id="newskill_btn' + newexskills_arr[i] + '" value="' + exskills_arr[i] +
                    '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' + newexskills_name[i] +
                    ' <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
                    newexskills_arr[i] + '" value="' + newskills_name[i] + '"/>';

            }
            console.log('exskill =>',exskills_arr,'exskillname =>',exskills_name);

            $('#skills_result_extra').html(button_html);
            renderExtraSkillsResult();
            if (check == true) {
                $('#skil_result').html('');
                $('#search_skill').val('');
            }
        }

        function selectnewskill(name) {
            if (newskills_name.indexOf(name) !== -1) return;
            newskillcount++;
            newskills_arr.push(newskillcount);
            newskills_name.push(name);
            renderExtraSkillsResult();
            $('#skil_result').html('');
            $('#search_skill').val('');
            $('#skil_result').css('display', 'none');
        }

        function unselectextraskill(s_id) {
            s_id = parseInt(s_id, 10);
            var index = exskills_arr.indexOf(s_id);
            if (index !== -1) {
                exskills_arr.splice(index, 1);
                exskills_name.splice(index, 1);
            }
            renderExtraSkillsResult();
        }

        function unselectnewskill(s_id) {
            const index = newskills_arr.indexOf(s_id);
            if (index !== -1) {
                newskills_arr.splice(index, 1);
                newskills_name.splice(index, 1);
            }
            renderExtraSkillsResult();
            $('#skil_result').css('display', 'none');
        }

        function selectextraskills(s_id, name, check) {
            if (check) {
                $('#skil_result').css('display', 'none');
            }
            // Avoid duplicates
            if (exskills_arr.indexOf(s_id) !== -1) {
                return;
            }

            exskills_arr.push(s_id);
            exskills_name.push(name);

            renderExtraSkillsResult();

            if (check === true) {
                $('#skil_result').html('');
                $('#search_skill').val('');
            }
        }

        function unselectextraskill(s_id) {
            s_id = parseInt(s_id, 10);
            const index = exskills_arr.indexOf(s_id);
            if (index !== -1) {
                exskills_arr.splice(index, 1);
                exskills_name.splice(index, 1);
            }
            renderExtraSkillsResult();
        }

        function unselectnewskill(s_id) {
            const index = newskills_arr.indexOf(s_id);
            if (index !== -1) {
                newskills_arr.splice(index, 1);
                newskills_name.splice(index, 1);
            }
            renderExtraSkillsResult();
            $('#skil_result').css('display', 'none');
        }

    function renderExtraSkillsResult() {
        let button_html = '';

        // Existing skills
        for (let i = 0; i < exskills_arr.length; i++) {
            button_html += `
                <button type="button"
                    onclick="unselectextraskill(${exskills_arr[i]})"
                    id="skill_btn${exskills_arr[i]}"
                    value="${exskills_arr[i]}"
                    class="btn gray ft-small apply-btn fs-sm rounded mt-1"
                    style="background:rgb(207, 217, 218)">
                    ${exskills_name[i]} <i class="fa fa-times ml-2"></i>
                </button>
                <input type="hidden" name="skills[]" value="${exskills_arr[i]}">
            `;
        }

        // New skills
        for (let i = 0; i < newskills_arr.length; i++) {
            button_html += `
                <button type="button"
                    onclick="unselectnewskill(${newskills_arr[i]})"
                    id="newskill_btn${newskills_arr[i]}"
                    value="${newskills_arr[i]}"
                    class="btn gray ft-small apply-btn fs-sm rounded mt-1">
                    ${newskills_name[i]} <i class="fa fa-times"></i>
                </button>
                <input type="hidden" name="newskills[]" value="${newskills_arr[i]}">
            `;
        }

        $('#skills_result_extra').html(button_html);
    }


        function renderSelectedSkills() {
            if (selectedSkills && selectedSkills.length > 0) {
                selectedSkills.forEach(skill => {
                    // Call the selectskills function for each skill
                    selectskills(skill.id, skill.name, skill.category_id, false);
                });
            }
        }



        // Call the function when the page loads
        $(document).ready(function() {
            function getStates(country_id, selectedState = '') {
                $.ajax({
                    url: '{{ route('get.country.states') }}',
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: country_id
                    },
                    success: function(response) {
                        let $stateContainer = $('#state').closest('.col-md-6'); // parent wrapper
                        let states = response.states || [];

                        if (states.length > 0) {
                            if (!$stateContainer.find('select#state').length) {
                                $stateContainer.html(`
                                    <select id="state" name="state" class="form-select" required>
                                        <option value="">Select State</option>
                                    </select>
                                `);
                            }

                            let $state = $stateContainer.find('#state');
                            $state.empty().append('<option value="">Select State</option>');

                            $.each(states, function(_, value) {
                                $state.append(`
                                    <option data-code="${value?.code ?? ''}" value="${value?.name ?? ''}">
                                        ${value?.name ?? ''}
                                    </option>
                                `);
                            });

                            if (selectedState) {
                                $state.val(selectedState);
                            }
                        } else {
                            let inputHTML = `
                                <input type="text" id="state" name="state" class="form-control"
                                    placeholder="Enter State" ${selectedState ? `value="${selectedState}"` : ''} required>
                            `;

                            if (!$stateContainer.find('input#state').length) {
                                $stateContainer.html(inputHTML);
                            } else {
                                $stateContainer.find('input#state').val(selectedState || '');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            }

            function getCities(country_id, selectedCity = '') {
                $.ajax({
                    url: '{{ route('get.country.cities') }}',
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: country_id
                    },
                    success: function(response) {
                        let $cityContainer = $('#city').closest('.col-md-6'); // parent wrapper
                        let cities = response.cities || [];

                        if (cities.length > 0) {
                            // Show dropdown
                            if (!$cityContainer.find('select#city').length) {
                                $cityContainer.html(`
                                    <select id="city" name="city" class="form-select" required>
                                        <option value="">Select City</option>
                                    </select>
                                `);
                            }

                            let $city = $cityContainer.find('#city');
                            $city.empty().append('<option value="">Select City</option>');

                            $.each(cities, function(_, value) {
                                $city.append(`
                                    <option value="${value}">${value}</option>
                                `);
                            });

                            if (selectedCity) {
                                $city.val(selectedCity);
                            }
                        } else {
                            // Show text input
                            let inputHTML = `
                                <input type="text" id="city" name="city" class="form-control"
                                    placeholder="Enter City" ${selectedCity ? `value="${selectedCity}"` : ''} required>
                            `;

                            if (!$cityContainer.find('input#city').length) {
                                $cityContainer.html(inputHTML);
                            } else {
                                $cityContainer.find('input#city').val(selectedCity || '');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            }

            renderSelectedSkills();
            let country_id = $('#country').val();
            const selected_state = @json($user?->address?->state);
            getStates(country_id, selected_state);

            const selected_city = @json($user?->address?->city);
            getCities(country_id, selected_city);

            $(document).on('change', '#country', function() {
                let country_id = $(this).val();
                getStates(country_id);
                getCities(country_id);
            });
            $('#skil_result').css('display', 'none');

        });

        function previewOldImage(event, oldIndex) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgPreview = document.getElementById('old-image-preview-' + oldIndex);
                    const uploadLabel = document.getElementById('old-upload-label-' + oldIndex);

                    if (imgPreview && uploadLabel) {
                        imgPreview.src = e.target.result;
                        imgPreview.style.display = 'block';
                        uploadLabel.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        }

document.addEventListener('DOMContentLoaded', function() {
    // Attach event listener to any existing portfolio image inputs
    document.querySelectorAll('input[name="pimage[]"]').forEach(function(input) {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Find the nearest image tag within this section
                    const img = event.target.closest('.image-box').querySelector('img');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });
});

function previewImageSafe(event, input) {
    const file = event.target.files && event.target.files[0];
    if (!file) return; // stop if no file selected
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = input.closest('.image-box').querySelector('img');
        if (img) {
            img.src = e.target.result;
            img.style.display = 'block';
        }
    };
    reader.readAsDataURL(file);
}

    </script>
@endsection
