<div class="col-12 mt-4"> <!-- Timesheet section start -->
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen">
            <div class="row">
                <div class="col-xl-6">
                    <span class="fw-bold">Timesheets</span>
                    <button class="btn btn-sm dropdown-toggle ms-4" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Filter by:
                    </button>
                    <ul class="dropdown-menu fs-14">
                        <li><a class="dropdown-item" href="#">All</a></li>
                        <li><a class="dropdown-item" href="#">Manual</a></li>
                        <li><a class="dropdown-item" href="#">Auto-tracked</a></li>
                    </ul>
                </div>
                <div class="col-xl-6">
                    <ul class="nav">
                        @if ($isAllowedManualTime == 1)
                            <li>
                                <button class="btn btn-sm btn-outline-success text-green border-green"
                                    data-bs-toggle="modal" data-bs-target="#modalManual" title="Manual">
                                    <i class="fa-solid fa-hand-pointer"></i> Manual Timesheet Entry
                                </button>
                            </li>
                        @endif
                        <li>
                            <button class="btn btn-sm btn-outline-dark ms-3" onclick="checkAndLaunchApp()"
                                id="auto-tracker" title="Auto Tracker">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> Auto Tracker
                            </button>
                        </li>
                        <li>
                            @if ($allowManualTime == 0)
                                <button class="btn btn-sm btn-outline-dark ms-3" data-bs-toggle="modal"
                                    data-bs-target="#manualLogModal" title="Request Manual Log Permission">
                                    <i class="fa-solid"></i> Allow Manual Time Log
                                </button>
                            @elseif($allowManualTime == 1 && !$isAllowedManualTime)
                                <button class="btn btn-sm btn-outline-secondary ms-3" disabled>
                                    Manual Log Requested
                                </button>
                            @elseif($allowManualTime == 1 && $isAllowedManualTime == 2)
                                <button class="btn btn-sm btn-outline-danger ms-3" disabled>
                                    Manual Log Rejected
                                </button>
                            @endif
                        </li>
                        <li>
                            <span class="text-end fs-14 ms-3">
                                <!-- Input for date selection restricted to the current week -->
                                <input type="date" class="bg-lsgreen border-0" id="start" name="trip-start"
                                    value="{{ $date ?? now()->utc()->format('Y-m-d') }}" {{-- min="{{ now()->startOfWeek()->format('Y-m-d') }}" --}}
                                    max="{{ now()->endOfWeek()->format('Y-m-d') }}"
                                    data-url="{{ route('user.seller.workspace.timesheet', ['encryptedId' => $encJobId]) }}">
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xl-12">
                    <span class="fw-bold">Total Time Logged: </span>{{ $totalTime['hours'] }} hrs
                    {{ $totalTime['minutes'] }} mins
                    <span class="fw-bold">Cost: </span>${{ $totalTime['cost'] }}
                    <span class="fw-bold">Manual time: </span>{{ $manualTime['hours'] }} hrs
                    {{ $manualTime['minutes'] }} mins
                    <span class="fw-bold">Auto-tracked time: </span>{{ $autoTrackedTime['hours'] }} hrs
                    {{ $autoTrackedTime['minutes'] }} mins
                </div>
            </div>
        </div>
        @php
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY); // Monday 12:00 AM
            $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY); // Sunday 11:59:59 PM
        @endphp

        <div class="card-body">
            @if ($jobWorkBids->isEmpty())
                <div>No record found.</div>
            @else
                <?php $rowCounter = 0; ?>
                @foreach ($jobWorkBids as $key => $bid)
                    <div class="row">
                        <div class="col-xl-8 pe-0">
                            {{-- <u class="text-secondary fw-bold">{{ $bid->title }}</u><br> --}}
                            <ul class="nav">
                                <li class="ms-0">
                                    <i class="fa-solid fa-circle-dollar-to-slot fs-5 text-3gray"></i>
                                    <b>${{ $bid->per_hour_rate }}/hr</b>
                                </li>
                                <li>
                                    <span class="ms-2">
                                        <i class="fa-regular fa-clock text-secondary"></i>
                                        <b>{{ $bid->time }} hrs {{ $bid->minutes }} mins</b>
                                    </span>
                                </li>
                                <li>
                                    <span class="ms-0 ms-md-2">
                                        Cost: <b>${{ number_format($bid->total_cost, 2) }}</b>
                                    </span>
                                </li>
                                @if (isset($bid->file) && !empty($bid->file))
                                    <li class="ms-0 ms-md-2">
                                        <a href="{{ asset('storage/' . $bid->file) }}" class="text-decoration-none"
                                            target="_blank">
                                            <i class="fa-solid fa-image"></i> View Attachment
                                        </a>
                                    </li>
                                @endif
                            </ul>
                            <ul class="nav mt-1">
                                <li class="ms-0">
                                    <div id="memo-container-{{ $bid->id }}"
                                        class="d-flex align-items-center w-100">
                                        <span id="memo-text-{{ $bid->id }}"
                                            class="me-2">{{ $bid->memo }}</span>
                                        <textarea class="form-control d-none" id="memo-input-{{ $bid->id }}" style="width:600px;height:0px;"
                                            oninput="validateWordCount({{ $bid->id }})">{{ $bid->memo }}</textarea>
                                        <button class="btn btn-sm btn-outline-success ms-2 d-none"
                                            id="update-memo-btn-{{ $bid->id }}"
                                            onclick="updateMemo({{ $bid->id }})">Update
                                        </button>
                                    </div>
                                    <small id="word-count-{{ $bid->id }}" class="text-muted d-none mt-1">Words: 0
                                        (15-30 words required)
                                    </small>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xl-4 py-2 pt-xl-4">
                            <ul class="nav float-xl-end">
                                @if ($bid->status == 6)
                                    <li>
                                        <a href="{{ route('user.seller_dispute') }}"
                                            class="btn btn-sm btn-outline-danger ms-3" title="View Dispute">View
                                            Dispute</a>
                                    </li>
                                @endif
                                @if ($bid->intervals->whereNotIn('status', [3, 6])->isNotEmpty())
                                    @if ($bid->memo && $bid->created_at >= $startOfWeek && $bid->created_at <= $endOfWeek)
                                        <li>
                                            <button class="btn btn-sm btn-outline-success ms-3"
                                                id="edit-memo-btn-{{ $bid->id }}"
                                                onclick="enableMemoEdit({{ $bid->id }})">Edit Memo</button>
                                        </li>
                                    @endif

                                    @if ($bid->created_at >= $startOfWeek && $bid->created_at <= $endOfWeek)
                                        <li>
                                            <button class="btn btn-sm btn-outline-danger ms-3"
                                                title="Delete Selected Intervals"
                                                id="deleteSelectedIntervals">Delete</button>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>
                    @if (isset($bid->hourlyBlocks) && !empty($bid->hourlyBlocks))
                        @foreach ($bid->hourlyBlocks as $block)
                            <div class="row overflow-scroll pt-4">
                                <div class="d-flex">
                                    <?php $rowCounter++; ?>
                                    <div style="width:25px">
                                        {{-- <div class="fs-10 lh-1 mt-3 me-1 text-secondary">{{ $block['hour'] }}</div> --}}
                                        <div class="fs-10 lh-1 mt-3 me-1 text-secondary"
                                            id="hourlyBlock-{{ $block['hour'] }}">{{ $block['hour'] }} <input
                                                class="form-check-input select-all-checkbox" type="checkbox"
                                                data-row-id="{{ $rowCounter }}"
                                                data-delivery-hour-id="{{ $bid->id }}"></div>
                                    </div>
                                    {{-- @php
                                        dd($block['intervals']);
                                    @endphp --}}
                                    @foreach ($block['intervals'] as $interval)
                                        <div style="width:200px"
                                            class="{{ $interval['has_data'] ? ($interval['status'] == 1 ? '' : ($interval['status'] == 6 ? 'bg-danger' : ($interval['status'] == 3 ? 'bg-success' : ''))) : '' }}">
                                            @if ($interval['has_data'])
                                                @if (!empty($interval['screen_shot']))
                                                    <a href="{{ asset('storage/' . $interval['screen_shot']) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('storage/' . $interval['screen_shot']) }}"
                                                            width="155" height="100" alt="Screenshot">
                                                    </a>
                                                @else
                                                    <p class="text-secondary fs-10 text-center">No Screenshot</p>
                                                @endif
                                                <button type="button"
                                                    class="d-flex w-100 border-0 p-1 text-white border-end border-white mt-1 {{ $interval['status'] == 5 ? 'bg-3gray' : 'bg-dgreen2' }}"
                                                    id="liveToastBtn-{{ $interval['id'] }}">
                                                    <div style="width:100px; font-size:10px">
                                                        <input class="form-check-input select-checkbox"
                                                            name="interval_ids[]" data-row-id="{{ $rowCounter }}"
                                                            type="checkbox" value="{{ $interval['id'] }}"
                                                            data-delivery-hour-id="{{ $bid->id }}">
                                                        {{ $interval['time'] }}
                                                    </div>
                                                    <div class="progress w-100 ms-1 mt-1" style="height:12px">
                                                        <div class="progress-bar fs-10" role="progressbar"
                                                            aria-label="Activity Progress"
                                                            style="width: {{ $interval['activity_level'] * 10 }}%; height:12px"
                                                            aria-valuenow="{{ round($interval['activity_level']) }}"
                                                            aria-valuemin="1" aria-valuemax="10">
                                                            {{ $interval['activity_level'] }}
                                                        </div>
                                                    </div>
                                                </button>
                                                {{-- @elseif ($interval['is_manual'] == 1)
                                                    <div class="bg-lsgreen border-end border-white p-4 text-white"
                                                        style="width:155px; height:100px;">Manual</div>
                                                    <div style="width:100px; font-size:10px"> --}}
                                                {{-- @if ($interval['is_manual'] == 0) --}}
                                                {{-- <input class="form-check-input select-checkbox"
                                                                name="interval_ids[]"
                                                                data-row-id="{{ $rowCounter }}" type="checkbox"
                                                                value="{{ $interval['id'] }}"
                                                                data-delivery-hour-id="{{ $block->id }}"> --}}
                                                {{-- @endif --}}

                                                {{-- {{ $interval['time'] }}
                                                    </div>
                                                @endif --}}

                                                {{-- <div class="text-end fs-14 ps-1 text-secondary">

                                                </div> --}}
                                                <div class="toast-container p-1" style="margin-left: -160px;">
                                                    <div id="liveToast-{{ $interval['id'] }}" class="toast bg-white"
                                                        role="alert" aria-live="assertive" aria-atomic="true">
                                                        <div class="toast-body p-3">
                                                            <button type="button"
                                                                class="text-danger border-0 btn-close float-end"
                                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                                            <p class="fs-12"><b>Memo</b><br>{{ $bid->memo }}</p>
                                                            <p class="mt-1 fs-12">
                                                                <b>Activity Level:
                                                                    {{ $interval['activity_level'] }}</b>
                                                                <i class="fa fa-info-circle ms-1 text-muted"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="This time window will be included in the hourly payment slot if the activity level is 6 or above.">
                                                                </i>
                                                            </p>
                                                            <div class="d-flex">
                                                                <div class="fs-12">
                                                                    <b>Activity Details</b><br>
                                                                    Active
                                                                    {{ $interval['countActivityLogs'] }} of 10
                                                                    min<br>
                                                                    <span class="text-success">What is
                                                                        activity level</span>
                                                                </div>
                                                                <div class="progress w-25 ms-5 mt-3"
                                                                    style="height:12px">
                                                                    <div class="progress-bar fs-10" role="progressbar"
                                                                        aria-label="Activity Progress"
                                                                        style="width: {{ $interval['countActivityLogs'] * 10 }}%; height:12px"
                                                                        aria-valuenow="{{ $interval['countActivityLogs'] }}"
                                                                        aria-valuemin="1" aria-valuemax="100">
                                                                        {{ $interval['countActivityLogs'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <table class="fs-12 table mt-2">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Time</th>
                                                                        <th>Mouse</th>
                                                                        <th>Keyboard</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($interval['activities'] as $activity)
                                                                        <tr>
                                                                            <td>{{ $activity['time'] }}
                                                                            </td>
                                                                            <td>{{ $activity['mouse_clicks'] }}
                                                                            </td>
                                                                            <td>{{ $activity['keystrokes'] }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <hr>
                @endforeach
            @endif
        </div>
    </div>
</div>
<!-- Manual Timesheet Modal -->
<div class="modal fade" id="modalManual" tabindex="-1" aria-labelledby="modalAcceptLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light py-2 px-4">
                <h5 class="modal-title">Send Your Work Duration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-14">
                <div id="manualError"></div>
                <form method="POST" action="{{ url('user/seller/job/send/timesheet/work/bid') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $encBuyerCounterOfferDataId }}" name="buyerCounterOfferDataId">

                    <div class="mb-3 row">
                        <label for="title" class="col-sm-3 col-form-label">Memo</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control fs-14" id="memo" name="memo" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date" class="col-sm-3 col-form-label">Date</label>
                        <div class="col-sm-9">
                            <!-- Read-only date input with restrictions -->
                            <input type="date" class="form-control fs-14" id="date" name="date"
                                value="{{ $date ?? now()->utc()->format('Y-m-d') }}"
                                min="{{ \Carbon\Carbon::now()->startOfYear()->format('Y-m-d') }}"
                                max="{{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="timezone" class="col-sm-3 col-form-label">Timezone</label>
                        <div class="col-sm-9">
                            <select class="form-select fs-14" id="timezone" name="timezone" required>
                                <option value="UTC" {{ $timezone == 'UTC' ? 'selected' : '' }}>UTC (Coordinated
                                    Universal Time)</option>
                                <option value="local" {{ $timezone == 'local' ? 'selected' : '' }}>Local Time
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="start_time" class="col-sm-3 col-form-label">Start Time</label>
                        <div class="col-sm-9">
                            <select class="form-control fs-14" id="start_time" name="start_time" required>
                                @foreach ($timeIntervals as $time)
                                    <option value="{{ $time['value'] }}" {{ $time['disabled'] ? 'disabled' : '' }}
                                        {{ !$time['disabled'] && $time['value'] == $startTime ? 'selected' : '' }}>
                                        {{ $time['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="end_time" class="col-sm-3 col-form-label">End Time</label>
                        <div class="col-sm-9">
                            <select class="form-control fs-14" id="end_time" name="end_time" required>
                                @foreach ($timeIntervals as $time)
                                    <option value="{{ $time['value'] }}" {{ $time['disabled'] ? 'disabled' : '' }}
                                        {{ !$time['disabled'] && $time['value'] == $endTime ? 'selected' : '' }}>
                                        {{ $time['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="total_hours" class="col-sm-3 col-form-label">Total Duration</label>
                        <div class="col-sm-4">
                            <input type="number" required class="form-control fs-14" id="total_hours"
                                name="total_hours" value="{{ old('total_hours') }}" placeholder="Hours" required
                                readonly>
                            <div class="text-danger" id="total_hours-error"></div>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" required class="form-control fs-14" id="total_minutes"
                                name="total_minutes" value="{{ old('total_minutes') }}" placeholder="Minutes"
                                required readonly>
                            <div class="text-danger" id="total_minutes-error"></div>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="attachment" class="col-sm-3 col-form-label">Attachment</label>
                        <div class="col-sm-9">
                            <input class="form-control fs-14" type="file" id="attachment" name="file"
                                accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png">
                            <span class="text-muted fs-12">
                                Allowed: PDF, DOC, DOCX, ZIP, JPG, JPEG, PNG
                            </span>
                            <div class="text-danger" id="file-error"></div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="manualLogModal" tabindex="-1" aria-labelledby="manualLogModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="manualLogModalLabel">Request Manual Time Log Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="manualLogRequestForm" method="POST"
                    action="{{ route('user.seller.manual.log.request') }}">
                    @csrf
                    <input type="hidden" name="encBuyerCounterOfferDataId"
                        value="{{ $encBuyerCounterOfferDataId }}">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Manual Time Log</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required
                            placeholder="Explain why you need permission for manual time logging."></textarea>
                        <div class="invalid-feedback">Please provide a valid reason.</div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-sm btn-success">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="downloadAppModal" tabindex="-1" aria-labelledby="downloadAppModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadAppModalLabel">Download App</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Seems like there is no app installed Would you like to download the app?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Download</button>
            </div>
        </div>
    </div>
</div>
