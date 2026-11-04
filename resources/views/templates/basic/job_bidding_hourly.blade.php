@extends($activeTemplate . 'layouts.wiserlance.layout')
<style>
    .read-more-content {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        overflow: hidden;
    }

    .read-more-content.expanded {
        display: block;
    }

    .read-more-btn {
        border: none;
        background: none;
        padding: 5px 0;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }

    .links-c {
        color: #007bff;
    }

    .read-more-btn:hover {
        text-decoration: underline;
    }
</style>
@section('content')
    <p class="mb-0 mt-3">
        <!-- Breadcrumbs -->
        <a href="{{ route('home') }}" style="text-decoration:none;"><i class="fa-solid fa-house fs-14" style="color:#9c9c9c"></i></a>
        <i class="fa-solid fa-chevron-right fs-12" style="color:#9c9c9c"></i>
        <a href="/job" style="text-decoration:none;color:#9c9c9c">Jobs</a>
        <i class="fa-solid fa-chevron-right fs-12" style="color:#9c9c9c"></i>
        <a href='{{ route('job.details', [encrypt($job->id)]) }}' style="text-decoration:none;color:#9c9c9c">Jobs Details</a>
        <i class="fa-solid fa-chevron-right fs-12" style="color:#9c9c9c"></i>
        <a style="color:#9c9c9c;text-decoration:none;cursor: default;">
            Bid Now
        </a>
    </p>

    <div class="row py-4">
        <div class="col-lg-12 mt-3 mt-lg-0" id="contents">
            {{-- <div class="bg-white px-3 py-4 shadow-sm border fs-14 rounded-2">
                <h2>Job Details</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <strong>Title:</strong>
                            <div>{!! $job->title !!}</div>
                        </div>

                        <div class="job-text">
                            <strong>Description</strong>
                            <div class="read-more-content">
                                {!! $job->description !!}
                            </div>
                            <button type="button" class="read-more-btn links-c">Read More</button>
                        </div>

                        <div class="job-text">
                            <strong>Requirements</strong>
                            <div class="read-more-content">
                                {!! $job->requirements !!}
                            </div>
                            <button type="button" class="read-more-btn links-c">Read More</button>
                        </div>
                    </div>
                    

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/briefcase-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>Level - <span>{{ $job->exp_type_text }}</span></div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/budget-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>Budget Type - <span>{{ $job->job_type }}</span></div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/ref-time-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>Posted - <span>{{ diffforhumans($job->created_at) }}</span></div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/ref-time-g-gray.svg') }}" style="margin-right: 8px;" />
                            <div>Max Weekly Required Hours - <span>{{ $weeklyHours }} @lang('Hours')</span></div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/budget-f-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>Budget - <span> ${{ $job->amount }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/location-pin-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>Location - {{ $job->user->country }}</div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row g-3">

                <div class="col-md-8">
                    <div class="bg-white px-4 py-4 shadow-sm border rounded-2 fs-14 h-100">
                        <h2>Job Details</h2>
                        <div>
                            <strong>Title:</strong>
                            <div>{!! $job->title !!}</div>
                        </div>
                        <div class="job-text mt-3">
                            <strong>Description</strong>
                            <div class="read-more-content">
                                {!! $job->description !!}
                            </div>
                            <button type="button" class="read-more-btn links-c">
                                Read More
                            </button>
                        </div>
                        <div class="job-text mt-3">
                            <strong>Requirements</strong>
                            <div class="read-more-content">
                                {!! $job->requirements !!}
                            </div>
                            <button type="button" class="read-more-btn links-c">
                                Read More
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-white px-4 py-4 shadow-sm border rounded-2 fs-14 h-100">
                        <h2>Job Information</h2>
                        <div class="d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/briefcase-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>
                                Level - <span>{{ $job->exp_type_text }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/budget-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>
                                Budget Type - <span>{{ $job->job_type }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/ref-time-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>
                                Posted - <span>{{ diffforhumans($job->created_at) }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/ref-time-g-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>
                                Max Weekly Required Hours -
                                <span>{{ $weeklyHours }} @lang('Hours')</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/budget-f-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>
                                Budget - <span>${{ $job->amount }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <img src="{{ asset('assets/wiserlance/assets/img/icons/location-pin-gray.svg') }}"
                                style="margin-right: 8px;" />
                            <div>
                                Location - <span>{{ $job->user->country }}</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="bg-white px-4 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                <h5>Skills & Expertise</h5>
                <div class="mt-3">
                    <p style="line-height:40px;">
                        @if (isset($job->skills) && count($job->skills) > 0 && $job->extraSkills && count($job->extraSkills) > 0)
                            @foreach ($job->skills as $skill)
                                @if ($matchingSkills->contains( $skill->name ))
                                    <span class="{{ !$loop->first ? 'ms-2' : '' }} matching-skill">{{ __($skill->name) }}</span>
                                @else
                                    <span class="tags {{ !$loop->first ? 'ms-2' : '' }}">{{ __($skill->name) }}</span>
                                @endif
                            @endforeach
                            @foreach($job->extraSkills as $extraSkill)
                                @if ($matchingSkills->contains( $extraSkill->name))
                                    <span class=" ms-2 matching-skill">{{ __($extraSkill->name) }}</span>
                                @else
                                    <span class="tags ms-2">{{ __($extraSkill->name) }}</span>
                                @endif
                            @endforeach
                        @else
                            <span class="ms-2 text-muted">No skills available.</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="bg-white px-3 py-2 mt-3 shadow-sm border fs-14 rounded-2">
                <form enctype="multipart/form-data" method="post" action="{{ url('/job/addbidinghourly/' . encrypt($job->id)) }}">
                    @csrf
                    <input name="cutfix" type="hidden" value="{{ showAmount($membershipFee) }}">
                    <div>
                        <h6>@lang('Hourly Bidding Information')</h6>
                        <div class="mt-3">
                            <div>
                                <label>Hourly Bid Amount</label>
                                <div class="input-group">
                                    <input id="hourlyBidAmount" type="number" class="form-control" min="1"
                                        max="{{ $job->maxBudget }}" value="{{ old('bid_price') }}"
                                        oninput="validity.valid||(value='');">
                                    <input type="hidden" name="bid_price" id="calculatedValue" min="1">
                                    <span class="input-group-text">$/hr</span>
                                </div>
                                <div id="error-message" class="text-danger mt-2"></div>
                            </div>
                            <div class="mt-3">
                                <label>Platform Fee: {{ showAmount($membershipFee) }}%</label>
                            </div>
                            <div class="mt-2">
                                <label>You'll be paid hourly: $<span id="milestone_pay_amt">0</span></label>
                            </div>
                            <div class="mt-3">
                                <label>Attachment</label> <small>(file type: doc, docx, gif, jpeg, jpg, pdf, png, ppt, word,
                                    xls, xlsx, zip)</small>
                                <input type="file" name="file" class="form-control">
                            </div>
                            <div class="mt-3">
                                <label>Cover Letter</label>
                                <textarea name="proposal" id="description" class="form-control with-light" rows="10">{!! old('proposal') !!}</textarea>
                            </div>
                            <div class="mt-3">
                                <button type="submit" id="submitBidButton"
                                    class="btn btn-success bg-green">@lang('Submit Bid')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
<script>
    $(document).ready(function() {
        const maxbudget = parseFloat('{{ $job->amount }}');
        const platformFee = parseFloat('{{ showAmount($membershipFee) }}');

        // Handle bid amount input
        $('#hourlyBidAmount').on('input', function() {
            const amount = parseFloat($(this).val()); // Get the input value as a float
            const errorMessageElement = $('#error-message'); // Error message div

            // Check for errors: Invalid amount, negative values, or exceeding max budget
            if (isNaN(amount) || amount <= 0) {
                errorMessageElement.text('Error: The bid should be a valid positive number.');
            } else if (amount > maxbudget) {
                errorMessageElement.text('Error: Amount exceeds maximum budget.');
            } else {
                errorMessageElement.text(''); // Clear the error message if the input is valid
            }

            // Update the calculated value based on platform fee
            if (!isNaN(amount) && amount > 0 && amount <= maxbudget) {
                const feeAmount = amount * (platformFee / 100);
                const amountAfterDeduction = amount - feeAmount;
                $('#milestone_pay_amt').text(amountAfterDeduction.toFixed(2)); // Update the display
                $('#calculatedValue').val(amount.toFixed(2)); // Update the hidden input
            } else {
                $('#milestone_pay_amt').text('0.00'); // Reset value if invalid input
                $('#calculatedValue').val(''); // Reset hidden input if invalid
            }
        });

        // Handle form submission
        $('#submitBidButton').on('click', function(event) {
            const amount = parseFloat($('#hourlyBidAmount').val()); // Get bid amount
            const errorMessageElement = $('#error-message');

            // Prevent form submission if there's an error
            if (isNaN(amount) || amount <= 0 || amount > maxbudget) {
                event.preventDefault(); // Stop form submission
                errorMessageElement.text('Error: Please correct the bid amount before submitting.');
                return false;
            }

            // Ensure textarea content is properly handled by nicEdit
            const editorInstance = nicEditors.findEditor('description');
            if (editorInstance) {
                editorInstance.saveContent();
            }

            // Disable button to prevent multiple submissions
            $(this).prop('disabled', true);

            // Allow form submission
            $(this).closest('form').submit();
        });

        // Show/hide bid modal
        $('#Bidbtn').click(function() {
            $('#Bidmodel').show();
        });

    });
    const customNicEditors = {};
     bkLib.onDomLoaded(function() {
            const elementId = 'description';
            const description = document.getElementById(elementId);

            if (description) {
                if (!customNicEditors[elementId]) {
                    customNicEditors[elementId] = new nicEditor().panelInstance(elementId);
                }
            }
        });

        // Read More Script
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.read-more-btn').forEach(function (button) {
            const content = button.previousElementSibling;

            // Hide button if content doesn't need expanding
            if (content.scrollHeight <= 100) {
                button.style.display = 'none';
                return;
            }

            button.addEventListener('click', function () {
                content.classList.toggle('expanded');

                if (content.classList.contains('expanded')) {
                    button.textContent = 'Read Less';
                } else {
                    button.textContent = 'Read More';
                }
            });
        });
    });
</script>
@endsection

