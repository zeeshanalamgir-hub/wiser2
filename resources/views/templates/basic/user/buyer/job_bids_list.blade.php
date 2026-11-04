
@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
<style>
    .nicEdit-container,
.nicEdit-panelContain,
.nicEdit-main {
    width: 100% !important;
    box-sizing: border-box;
}

.nicEdit-main {
    min-height: 150px !important;
}
</style>
@section('content')
    <!-- Displaying Bids -->
    @foreach ($bids as $index => $bid)
        <div class="card shadow-sm d-flex mt-2" style="border: solid 1px #d2d2d2">
            <div class="card-header bg-lsgreen float-end pt-3">
                <div class="dropdown float-end">
                    <button class="btn btn-sm bg-white border dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="pe-5 me-5">Sort By</span>
                    </button>
                    <ul class="dropdown-menu fs-14">
                        <li><a class="dropdown-item" href="#">Default</a></li>
                        <li><a class="dropdown-item" href="#">Bid Amount</a></li>
                        <li><a class="dropdown-item" href="#">Recent</a></li>
                        <li><a class="dropdown-item" href="#">Rating</a></li>
                    </ul>
                </div>
                <div class="float-end pe-3">
                    <button class="btn btn-success bg-green border-0 btn-sm" onclick="location.href='{{ url('/job/details/'.encrypt($bid->job->id)) }}';">
                        <i class="fa-solid fa-eye"></i> View Job
                    </button>
                </div>
                <h4 class="text-secondary">Bids</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-sm-7 col-md-6 pe-0 d-flex">

                        <a href="{{ route('profile', ['username' => slug($bid->user->username), 'id' => encrypt($bid->user->id)]) }}" class="text-decoration-none">
                            @if(!is_null($bid?->user?->image))

                            {{--  <img src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . $bid->user->image, null) }}"  --}}
                            <img src="{{ Storage::url($bid?->user?->image) }}"
                                    alt="@lang('Profile Image')" class="rounded-circle border border-secondary" width="70" height="70">
                            @else
                                <div class="d-flex justify-content-center">
                                    <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                        style="height:45px;width:45px;font-size:20px;margin-top:10px">
                                        {{-- Display initials if no image --}}
                                        {{ strtoupper(substr($bid?->user?->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($bid?->$user?->lastname ?? 'A', 0, 1)) }}
                                    </div>
                                </div>
                            @endif
                        </a>
                        <div style="margin-left:10px; padding-top:10px">
                            <a href="{{ route('profile', ['username' => slug($bid->user->username), 'id' => encrypt($bid->user->id)]) }}"
                                class="text-decoration-none text-dark">
                                 <h5 class="mb-0">{{ $bid->user->fullname }}</h5>
                             </a>
                            <p class="fs-14 text-secondary mb-1">{{ $bid->user->position }}</p>
                            <p class="pt-0 mb-0">
                                <span class="bg-warning p-1 fs-14 fw-bold">{{ $bid->userRating }}</span>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($bid->userRating))
                                        <i class="fa-solid fa-star text-warning"></i> <!-- Full star in yellow -->
                                    @elseif($i == ceil($bid->userRating) && $bid->userRating - floor($bid->userRating) >= 0.5)
                                        <i class="fa-solid fa-star-half-alt text-warning"></i> <!-- Half star in yellow -->
                                    @else
                                        <i class="fa-solid fa-star text-3gray"></i> <!-- Empty star in gray -->
                                    @endif
                                @endfor
                            </p>
                            <p class="pt-1"><i class="fas fa-map-marker-alt"></i> {{ $bid->user->country }}</p>
                        </div>
                    </div>
                    <div class="col-sm-5 col-md-6 pt-3">
                        <div class="float-md-end">
                            {{-- <button class="btn btn-sm btn-success bg-green border-0" title="View Contract" onclick="location.href='../hiring/workSpaceOverview.php';"> <i class="fa-solid fa-eye"></i> Contract </button>
                            <button class="btn btn-sm btn-danger border-0" title="Cancel Bid" onclick=" "> <i class="fa-regular fa-circle-xmark"></i> Cancel Bid </button> --}}
                            @if ($bid->is_buyer_offer_sent)
                                <!-- If an offer has been sent -->
                                <button class="btn btn-sm btn-success bg-green border-0" title="View Offer"
                                    onclick="window.location.href='{{ url('user/buyer/hire/offered-jobs') }}'">
                                    <i class="fas fa-eye"></i> View Offer
                                </button>
                                <a href="javascript:void(0);" data-cancelbid_id="{{$bid->buyerCounterOffer->id}}" title="Cancel Bid" data-bs-toggle="modal" data-bs-target="#cancelbidModal" class="cancelbidBtn btn btn-sm btn-danger border-0">
                                    <i class="fa-regular fa-circle-xmark"></i> Cancel Bid
                                </a>
                            @elseif($is_redirect_to_deposit_money)
                                <!-- If the user needs to deposit money -->
                                <button class="btn btn-sm btn-secondary border-0" title="Deposit Money"
                                    onclick="location.href='{{ route('user.deposittwo') }}'">
                                    <i class="fas fa-money-bill-wave"></i> Deposit Money
                                </button>
                            @else
                                <!-- If an offer has not been sent -->
                                <button class="btn btn-sm btn-success bg-green border-0" data-bs-toggle="modal"
                                    data-bs-target="#modalSendBid-{{ $index }}" title="Send Bid">
                                    <i class="fas fa-paper-plane"></i> Send Offer
                                </button>
                            @endif
                            <!--send bid pop up-->
                            <div class="modal fade" id="modalSendBid-{{ $index }}" tabindex="-1"
                                aria-labelledby="modalSendBidLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-green text-white py-2 px-4">
                                            <h1 class="modal-title fs-5" id="modalSendBidLabel-{{ $index }}">Send
                                                Bid!</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('user.buyer.submit.bid.offer') }}" method="POST"
                                                id="offerForm-{{ $index }}" enctype="multipart/form-data">
                                                @csrf
                                                <!-- Hidden Inputs (Add necessary hidden inputs here) -->
                                                <input type="hidden" name="job_id" value="{{ $singlejob->id }}">
                                                <input type="hidden" name="bid_id" value="{{ $bid ? $bid->id : '' }}">
                                                <input type="hidden" name="sender" value="{{ $user->id }}">
                                                <input type="hidden" name="reciever" value="{{ $bid->user_id }}"
                                                    value="{{ $bid ? $bid->user_id : '' }}"> <!-- Adjust as needed -->
                                                <input type="hidden" name="job_type"
                                                    value="{{ $bid->job_type ?? 'default' }}">
                                                <!-- Example value, adjust accordingly -->

                                                <h6 class="mb-0">
                                                    @if (!is_null($bid->user->image))
                                                        <img src="{{ Storage::url($bid->user->image) }}"
                                                            alt="@lang('Profile Image')"
                                                            class="rounded-circle border border-secondary" width="70"
                                                            height="70">
                                                        {{ $bid->user->username ?? 'User Name' }}
                                                    @else
                                                        <div class="d-flex justify-content-center">
                                                            <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                                style="height:70px;width:70px;font-size:20px">
                                                                {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </h6>
                                                {{-- <p class="mt-2">Job Title: <a href="#"
                                                        class="links-c2 fs-6 fw-bold"></a></p> --}}

                                                <div class="mb-3 mt-2">
                                                    <label for="title" class="form-label">Job Title:</label>
                                                    <input type="text" class="form-control" id="title"
                                                        name="title" value="{{ $singlejob->title ?? 'Job Title' }}"
                                                        style="width: 100%;">
                                                </div>
                                                <div class="mb-3" style="width: 100% !important;">
                                                    <label for="description" class="form-label">Description:</label>
                                                    <textarea class="form-control w-100" id="description" name="description" rows="2" required>{!! $singlejob->description !!}</textarea>
                                                </div>

                                                <div class="input-group bg-lsgreen">
                                                    <input type="file" class="form-control" id="inputGroupFile02"
                                                        name="file">
                                                    <label class="input-group-text bg-lsgreen"
                                                        for="inputGroupFile02">Upload</label>
                                                </div>
                                                <p class="form-text fs-12 text-secondary mb-3">Allowed files types: pdf,
                                                    xls, ppt, word, doc, docx, gif, jpg, png, jpeg</p>
                                                <p class="form-text fs-12 text-secondary mb-3"> <b>Note:</b> <i>You must
                                                        deposit fund to start the project</i></p>
                                                @if ($bid->job_type == 'hourly')
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <div class="input-group float-end w-50">
                                                            <span class="input-group-text">$</span>
                                                           <input
                                                                type="text"
                                                                class="form-control"
                                                                name="hourly_amount"
                                                                placeholder="5"
                                                                value="{{ $bid->bid_price }}"
                                                                inputmode="numeric"
                                                                pattern="[0-9]*"
                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                            >
                                                            <span class="input-group-text">/Hr</span>
                                                        </div>
                                                        <p class="my-2">Hourly Rate</p>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="input-group float-end w-50">
                                                            <input type="text" class="form-control"
                                                            min="0"
                                                            step="1"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                            name="weekly_hours" value="{{ $singlejob->weekly_hours }}" aria-describedby="basic-addon2">
                                                            <span class="input-group-text" id="basic-addon2">Hr</span>
                                                        </div>
                                                        <p class="my-2">Minimum hr/week</p>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="input-group float-end w-50">
                                                            <span class="input-group-text">$</span>
                                                            <input type="text" class="form-control" name="deposit_fund" value="{{ isset($bid) ? $singlejob->weekly_hours * $bid->bid_price : '' }}" readonly>
                                                        </div>
                                                        <p class="my-2">Deposit Fund</p>
                                                    </li>
                                                </ul>
                                                @elseif($bid->job_type == 'byproject')
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <div class="input-group float-end w-50">
                                                                <span class="input-group-text">$</span>
                                                               <input
                                                                    type="text"
                                                                    class="form-control"
                                                                    name="project_amount"
                                                                    value="{{ $bid->bid_price }}"
                                                                    placeholder="5"
                                                                    inputmode="numeric"
                                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                                >
                                                                <span class="input-group-text"></span>
                                                            </div>
                                                            <p class="my-2"> Amount </p>
                                                        </li>
                                                        @php
                                                        // Assuming $days is the number of days fetched from the database
                                                        $days = old('due_date.' . $index, $bid->due_date); // Fetch days from the database
                                                        $dueDate = date('Y-m-d', strtotime("+$days days")); // Calculate the due date
                                                        @endphp
                                                        <li class="list-group-item">
                                                            <div class="input-group float-end w-50">
                                                                <span class="input-group-text"></span>
                                                                <input type="date" id="due_date_input" name="due_date" value="{{ $dueDate }}"
                                                                    class="form-control">
                                                                <div id="due_date_error" class="text-danger"
                                                                    style="display:none;">Due date cannot be in the past.
                                                                </div>
                                                                <span class="input-group-text"></span>
                                                            </div>
                                                            <p class="my-2"> Due Date </p>
                                                        </li>
                                                        <input type="hidden" id="days_from_today" name="project_days" value="{{ old('project_days',$days) }}">
                                                    </ul>
                                                @elseif($bid->job_type == 'bymilestone')
                                                    @foreach ($bid->milestones as $index => $md)
                                                        <ul class="list-group">
                                                            <li class="list-group-item">
                                                                <div class="input-group float-end w-50">
                                                                    <input type="text" name="milestone_title[]"
                                                                        maxlength="255" value="{{ $md->title }}"
                                                                        class="form-control">
                                                                    <span class="input-group-text"></span>
                                                                </div>
                                                                <p class="my-2"> @lang('Title') </p>
                                                            </li>

                                                            <li class="list-group-item">
                                                                <div class="input-group float-end w-50">
                                                                    <span class="input-group-text">$</span>
                                                                    <input
                                                                        type="number"
                                                                        name="milestone_amount[]"
                                                                        value="{{ $md->amount }}"
                                                                        class="form-control"
                                                                        min="0"
                                                                        step="1"
                                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                                    >
                                                                    <span class="input-group-text"></span>
                                                                </div>
                                                                <p class="my-2"> @lang('Amount') </p>
                                                            </li>
                                                            @php
                                                                // Assuming $days is the number of days fetched from the database
                                                                $days = old('due_date.' . $index, $md->due_date); // Fetch days from the database
                                                                $dueDate = date('Y-m-d', strtotime("+$days days")); // Calculate the due date
                                                            @endphp
                                                            <li class="list-group-item">
                                                                <div class="input-group float-end w-50">
                                                                    <span class="input-group-text"></span>
                                                                    <!-- Due date input with unique ID -->
                                                                    <input type="date"
                                                                        id="due_date_input_{{ $index }}"
                                                                        name="due_date[]" value="{{ $dueDate }}"
                                                                        class="form-control">

                                                                    <div id="due_date_error_{{ $index }}"
                                                                        class="text-danger" style="display:none;">Due date
                                                                        cannot be in the past.</div>
                                                                    <span class="input-group-text"></span>
                                                                </div>
                                                                <p class="my-2">Due Date</p>
                                                            </li>
                                                            <input type="hidden" id="days_from_today_{{ $index }}" name="project_days[]">

                                                        </ul>
                                                    @endforeach
                                                @endif
                                                @if ($bid->job_type == 'hourly')
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="manual_time_logging" id="manualTimeLogging"
                                                            {{ $bid->allow_manual_time ? 'checked' : '' }}>
                                                        <label class="form-check-label fs-14" for="manualTimeLogging">
                                                            Manual Time Logging <span class="fw-bold"></span>
                                                        </label>
                                                    </div>
                                                @endif
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="close_project" id="flexRadioDefault2">
                                                    <label class="form-check-label fs-14" for="flexRadioDefault2"> Close this project after accepting this offer by
                                                        <span class="fw-bold">{{ $bid->user->username ?? 'User Name' }}</span>
                                                    </label>
                                                </div>

                                                <div class="modal-footer clear-both">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success bg-green border-0">Send</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--send bid pop up end-->
                            <a href="{{ route('user', $bid->user->id) }}" class="btn btn-sm ms-2 btn-secondary bg-dgreen">
                                <i class="fas fa-comment-dots"></i> Chat
                            </a>

                            {{-- @if ($bid->job_type === 'hourly')
                                <p class="text-center p-2 shadow-sm border mt-3 fw-bold float-end"
                                    style="max-width:175px">Bid
                                    Amount ${{ $bid->bid_price }}/hr</p>
                            @endif --}}
                        </div>
                    </div>
                    <div class="col-12">
                        <ul class="nav">
                            <li class="nav-item"><span class="text-secondary">Job success:</span> <span
                                    class="me-3 fw-bold">{{ $bid->jss }}%</span></li>
                            @if($bid->job_type == 'hourly')
                                <li class="nav-item"><span class="text-secondary">Hourly Rate:</span> <span
                                    class="me-3 fw-bold">${{ $bid->user->per_hour_rate }}/hr</span></li>
                            @else
                                <li class="nav-item"><span class="text-secondary">Amount:</span> <span
                                    class="me-3 fw-bold">${{ $bid->bid_price }}</span></li>
                            @endif
                            <li class="nav-item"><span class="text-secondary">Completed Jobs:</span> <span
                                    class="me-3 fw-bold">{{ $bid->completedProjects }}</span></li>
                            <li class="nav-item"><span class="text-secondary">Amount Earned:</span> <span
                                    class="me-3 fw-bold">${{ round($bid->user->balance, 2) }}</span></li>
                        </ul>
                        <ul class="nav border-top border-bottom py-2 mt-3">
                            <li class="nav-item"><span class="text-secondary">Submit on:</span> <span
                                    class="me-3 fw-bold">{{ $bid->created_at->format('d M, Y') }}</span></li>
                            @if ($bid->job_type === 'hourly')
                                <li class="nav-item">
                                    <span class="text-secondary">Bid Duration:</span>
                                    <span class="me-3 fw-bold">
                                        {{ $bid->job->weekly_hours }} Hours Weekly
                                    </span>
                                </li>
                            @else
                                <li class="nav-item">
                                    <span class="text-secondary">Bid Duration:</span>
                                    <span class="me-3 fw-bold">{{ $bid->due_date }} Days</span>
                                </li>
                            @endif
                            <li class="nav-item"><span class="text-secondary">Bid Amount:</span> <span
                                class="me-3 fw-bold">${{ $bid->bid_price }}</span></li>
                            <li class="nav-item">
                                <span class="text-secondary">Attachment:</span>
                                <span class="fw-bold">
                                    @if ($bid->attachments->isNotEmpty()) <!-- Use the relationship -->
                                        @foreach ($bid->attachments as $attachment)
                                            <a href="{{ Storage::url($attachment->image_url) }}"
                                                class="text-decoration-none"
                                                download>
                                                <i class="fa-solid fa-download text-green"></i>
                                            </a>
                                        @endforeach

                                    @elseif($bid->file != '')
                                            <a href="{{ Storage::url($bid->file) }}"
                                                class="text-decoration-none"
                                                download>
                                                <i class="fa-solid fa-download text-green"></i>
                                            </a>
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </li>
                        </ul>

                        <!-- Tags (Skills) -->
                        <ul class="nav py-2 mt-3" style="display: flex;gap:5px">
                            @if ($bid->allSkills && count($bid->allSkills) > 0)
                                @foreach ($bid->allSkills as $skill)
                                    @if ($skill['isMatched'])
                                        <li class="tagsFeatured fs-14 ms-0 ms-sm-2 mt-2 mt-sm-0">{{ $skill['name'] }}</li>
                                    @else
                                        <li
                                            class="tags fs-14 ms-0 ms-sm-2 mt-2 mt-sm-0 ">{{ $skill['name'] }}</li>
                                    @endif
                                @endforeach
                            @else
                                <span class="fs-12 text-muted">No skills available for this job.</span>
                            @endif
                        </ul>

                        <!-- If bid was rejected -->
                        @if($bid->is_cancel_bid == 1)
                            <div class="col-12 mt-5 ps-3 text-danger border-start border-4">
                                <h6>The buyer has cancelled offer</h6>
                                <p class="fs-14"><b>Reason:
                                    </b>{{ $bid->reason ?? 'No reason provided' }}</p>
                            </div>
                        @elseif ($bid->seller_offer_responce == 1)
                            <div class="col-12 mt-5 ps-3 text-danger border-start border-4">
                                <h6>Offer rejected by {{ $bid->user->username }}</h6>
                                <p class="fs-14"><b>Reason:
                                    </b>{!! $bid->seller_offer_responce_reason ?? 'No reason provided' !!}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Milestones Section -->
                @if ($bid->job_type === 'bymilestone' && $bid->milestones && count($bid->milestones) > 0)
                    <ul class="list-group">
                        <li class="list-group-item fw-bold">
                            <span>Milestone Name</span> 
                            <span class="float-end">Amount</span>
                        </li>

                        @foreach ($bid->milestones as $milestone)
                            
                            <li class="list-group-item">
                                <div class="mb-1">
                                    <span>{{ $milestone->title }}</span>
                                    <span class="float-end">${{ number_format($milestone->amount, 2) }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="accordion rounded" id="accordionExample"> <!--accordion-->
                    <div class="accordion-item mt-3">
                        <p class="accordion-header border rounded" id="headingOne">

                            <button class="accordion-button bg-light collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <span class="me-2 fw-bold">Cover Letter </span>
                            </button>
                        </p>

                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body border rounded-bottom">
                                {!! $bid->proposal !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
<!-- Bid Cancel Modal -->
<div class="modal fade" id="cancelbidModal" tabindex="-1" aria-labelledby="cancelBidModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h1 class="modal-title fs-5">@lang('Cancel Bid')</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="mb-3">
                    <p class="fs-sm text-muted">@lang('Are you sure you want to cancel this bid?')</p>
                </div>
                <form method="POST" action="{{ route('user.buyer.cancel.bid') }}">
                    @csrf
                    <input type="hidden" name="cancelbid_id">
                    <input type="hidden" name="confirm" value="cancel">

                    <!-- Cancel Reason Textarea -->
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">@lang('Cancel Reason')</label>
                        <textarea name="cancenreason" id="cancelReason" class="form-control" rows="3" placeholder="@lang('Enter your reason')" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger text-white ft-medium">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Bid Cancel Modal -->


@endsection
@section('script')
<script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
    <script>

        const customNicEditors = {};

        function applyNicEditorFullWidth(container) {
            const root = container || document;
            root.querySelectorAll('.nicEdit-main, .nicEdit-panelContain, .nicEdit-container, .nicEdit-panel').forEach(el => {
                el.style.setProperty('width', '100%', 'important');
                el.style.setProperty('box-sizing', 'border-box', 'important');
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[id^="modalSendBid-"]').forEach(function (modal) {
                modal.addEventListener('shown.bs.modal', function () {
                    const textarea = modal.querySelector('textarea[name="description"]');
                    if (textarea && !textarea.dataset.nicInitialized) {
                        new nicEditor({ fullPanel: true }).panelInstance(textarea.id);
                        textarea.dataset.nicInitialized = 'true';
                    }
                    setTimeout(function() {
                        applyNicEditorFullWidth(modal);
                    }, 50);
                });
            });
        });

        function calculateDaysFromToday() {
            var dueDateInput = document.getElementById('due_date_input').value;
            var dueDate = new Date(dueDateInput);
            var today = new Date();

            // Set both dates to midnight for accurate day-only comparison
            today.setHours(0, 0, 0, 0);
            dueDate.setHours(0, 0, 0, 0);

            if (dueDate < today) {
                document.getElementById('due_date_error').style.display = 'block';
                document.getElementById('days_from_today').value = '';
            } else {
                document.getElementById('due_date_error').style.display = 'none';
                var timeDiff = dueDate.getTime() - today.getTime();
                var daysDiff = timeDiff / (1000 * 3600 * 24); // Direct difference in days
                console.log(daysDiff);
                document.getElementById('days_from_today').value = daysDiff;
            }
        }

        const dueDateInput = document.getElementById('due_date_input');
        if (dueDateInput) {
            dueDateInput.addEventListener('change', calculateDaysFromToday);
        } else {
            console.warn('Due date input element not found.');
        }

        function updateProjectDays(index) {
            const dueDateInput = document.getElementById('due_date_input_' + index);
            const daysFromTodayInput = document.getElementById('days_from_today_' + index);
            const dueDateError = document.getElementById('due_date_error_' + index);

            const dueDate = new Date(dueDateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set time to midnight for comparison
            dueDate.setHours(0, 0, 0, 0);
            if (dueDate < today) {
                dueDateError.style.display = 'block'; // Show error if the due date is in the past
                daysFromTodayInput.value = ''; // Clear the days value
            } else {
                dueDateError.style.display = 'none';
                const timeDiff = dueDate.getTime() - today.getTime();
                const daysDiff = timeDiff / (1000 * 3600 * 24); // Difference in days
                daysFromTodayInput.value = daysDiff;
                console.log("days diff:", daysDiff);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            @if(isset($bids) && count($bids)>0)
                @foreach ($bid?->milestones as $index => $md)
                    console.log("updateProjectDays");
                    // Call the function on page load to set the initial value
                    updateProjectDays('{{ $index }}');

                    // Attach change event listener to each due date input
                    document.getElementById('due_date_input_{{ $index }}').addEventListener('change',
                function() {
                        updateProjectDays('{{ $index }}'); // Pass the current index to the function
                    });
                @endforeach
            @endif
        });

        document.addEventListener('DOMContentLoaded', function() {
            const hourlyRateInput = document.querySelector('input[name="hourly_amount"]');
            const weeklyHoursInput = document.querySelector('input[name="weekly_hours"]');
            const depositFundInput = document.querySelector('input[name="deposit_fund"]');

             // Check if elements exist before proceeding
            if (hourlyRateInput && weeklyHoursInput && depositFundInput) {
                function calculateDepositFund() {
                    const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
                    const weeklyHours = parseFloat(weeklyHoursInput.value) || 0;
                    const depositFund = hourlyRate * weeklyHours;

                    // Set the value of the Deposit Fund input
                    depositFundInput.value = depositFund.toFixed(2); // Limit to two decimal places
                }

                // Add event listeners to update Deposit Fund whenever Hourly Rate or Weekly Hours changes
                hourlyRateInput.addEventListener('input', calculateDepositFund);
                weeklyHoursInput.addEventListener('input', calculateDepositFund);
            }
        });
        $('.cancelbidBtn').on('click', function() {
            var modal = $('#cancelbidModal');
            modal.find('input[name=cancelbid_id]').val($(this).data('cancelbid_id'))
            modal.modal('show');
        });



    </script>
@endsection

