<div class="col-12"> <!-- Timesheet section start-->
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen">
            <div class="row">
                <div class="col-xl-7">
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
                <div class="col-xl-5">
                    <ul class="nav">
                        <li>
                            <span class="text-end fs-14 ms-3">
                                <!-- Input for date selection restricted to the current week -->
                                <input type="date" class="bg-lsgreen border-0" id="start" name="trip-start"
                                    value="{{ $date ?? now()->utc()->format('Y-m-d') }}"
                                    data-url="{{ route('user.workspace.timesheet', ['encryptedId' => $encJobId]) }}">
                            </span>
                        </li>
                        <li>
                            @if ($allowManualTime == 1 && !$isManulaLogAccepted)
                                <!-- If a request is already present -->
                                <button class="btn btn-sm btn-outline-success ms-3 text-black" data-bs-toggle="modal"
                                    data-bs-target="#manualLogModal">
                                    Manual Log Request
                                </button>
                            @elseif($isManulaLogAccepted == 1)
                                <button class="btn btn-sm btn-outline-secondary ms-3">
                                    Manual Log Accepted
                                </button>
                            @elseif($isManulaLogAccepted == 2)
                                <button class="btn btn-sm btn-outline-danger ms-3">
                                    Manual Log Rejected
                                </button>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($jobWorkBids->isEmpty())
                <div>No record found.</div>
            @else
                <?php $rowCounter = 0; ?>
                @foreach ($jobWorkBids as $key => $bid)
                    <div class="row">
                        <div class="col-xl-8 pe-0">
                            <u class="text-secondary fw-bold">{{ $bid->title }}</u><br>
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
                                    {{ $bid->memo }}
                                </li>
                            </ul>
                            @if ($bid->status == 4)
                                <ul class="nav mt-1">
                                    <li class="ms-0">
                                        Disputed Hours:
                                    </li>
                                    <li>
                                        <span class="ms-2">
                                            <i class="fa-regular fa-clock text-secondary"></i>
                                            <b>{{ $bid->disputed_hours }} hrs {{ $bid->disputed_minutes }} mins</b>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="ms-0 ms-md-2">
                                            Disputed Cost: <b>${{ $bid->disputed_amount }}</b>
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
                                    @if ($bid->status == 6)
                                        <li> <span
                                                class="text-success ms-2 bg-danger text-white px-2 py-1 fs-12 rounded-1">Disputed</span>
                                        </li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                        <div class="col-xl-4 py-2 pt-xl-4">
                            @php
                                // Log the values being checked
                                Log::info('Bid Status:', ['status' => $bid->status]);
                                Log::info('Date Start:', ['date_start' => $bid->date_start]);

                                $startOfDisputeWindow = \Carbon\Carbon::parse($bid->date_start)->next(\Carbon\Carbon::MONDAY);
                                $endOfDisputeWindow = $startOfDisputeWindow->copy()->addDays(4);

                                Log::info('Dispute Window:', [
                                    'start' => $startOfDisputeWindow->toDateTimeString(),
                                    'end' => $endOfDisputeWindow->toDateTimeString(),
                                    'current_time' => now()->toDateTimeString(),
                                ]);
                            @endphp

                            <ul class="nav float-xl-end">
                                {{-- Debugging output --}}
                                {{-- @php
                                    echo "Bid Status: " . $bid->status . "<br>";
                                    echo "Date Start: " . $bid->date_start . "<br>";
                                    echo "Current Time: " . now()->toDateTimeString() . "<br>";
                                    echo "Start of Dispute Window: " . $startOfDisputeWindow->toDateTimeString() . "<br>";
                                    echo "End of Dispute Window: " . $endOfDisputeWindow->toDateTimeString() . "<br>";
                                @endphp --}}

                                {{-- Conditional logic --}}
                                @if ($bid->status < 4 && now()->between($startOfDisputeWindow, $endOfDisputeWindow))
                                <li>
                                        <button id="disputeSelectedIntervals" class="btn btn-sm btn-outline-danger ps-2" title="Dispute">Dispute</button>
                                    </li>
                                @elseif ($bid->status == 6)
                                    <li>
                                        <a href="{{ route('user.buyer.dispute') }}" class="btn btn-sm btn-outline-danger ms-3" title="View Dispute">View Dispute</a>
                                    </li>
                                @endif
                            </ul>

                        </div>
                    </div>

                    <!-- Hourly Blocks Section -->
                    @if (isset($bid->hourlyBlocks) && !empty($bid->hourlyBlocks))
    @foreach ($bid->hourlyBlocks as $block)

        @php
            $isManual = $bid->intervals->every(function ($interval) {
                return $interval->is_manual == 1;
            });
        @endphp

        <div class="row pt-4">
            <div class="d-flex" style="overflow-x:auto">
                <?php $rowCounter++; ?>

                <div style="width:25px">
                    <div class="fs-10 lh-1 mt-3 me-1 text-secondary"
                        id="hourlyBlock-{{ $block['hour'] }}">
                        {{ $block['hour'] }}

                        <input class="form-check-input select-all-checkbox"
                            type="checkbox"
                            data-row-id="{{ $rowCounter }}"
                            data-delivery-hour-id="{{ $bid->id }}"
                            @if ($isManual) data-block-cost="{{ $bid->total_cost }}" @endif>
                    </div>
                </div>

                @foreach ($block['intervals'] as $interval)
                    <div style="width:200px"
                        class="{{ $interval['has_data']
                            ? ($interval['status'] == 6
                                ? 'bg-danger'
                                : ($interval['status'] == 3
                                    ? 'bg-success'
                                    : ''))
                            : '' }}">

                        @if ($interval['has_data'])

                            {{-- Screenshot --}}
                            @if (!empty($interval['screen_shot']))
                                <a href="{{ asset('storage/' . $interval['screen_shot']) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $interval['screen_shot']) }}"
                                        width="155" height="100">
                                </a>
                            @elseif ($interval['is_manual'] == 1)
                                <div class="bg-lsgreen border-end border-white p-4 text-white"
                                    style="width:170px; height:100px;">
                                    Manual
                                </div>
                            @else
                                <p class="text-secondary text-center fs-10">No Screenshot</p>
                            @endif

                            {{-- Button + Progress --}}
                            <button type="button"
                                class="d-flex w-100 border-0 p-1 text-white border-end border-white mt-1 {{ $interval['status'] == 5 ? 'bg-3gray' : 'bg-dgreen2' }}"
                                id="liveToastBtn-{{ $interval['id'] }}">

                                <div style="width:100px; font-size:10px">

                                    {{-- Checkbox only if status = 1 --}}
                                    @if ($interval['status'] == 1)
                                        <input class="form-check-input select-checkbox"
                                            name="interval_ids[]"
                                            data-row-id="{{ $rowCounter }}"
                                            type="checkbox"
                                            value="{{ $interval['id'] }}"
                                            id="interval-{{ $interval['id'] }}"
                                            data-cost="{{ $interval['cost'] }}">
                                    @endif

                                    {{ $interval['time'] }}
                                </div>
                                <div class="progress w-100 ms-1 mt-1" style="height:12px">
                                    <div class="progress-bar fs-10"
                                        role="progressbar"
                                        style="width: {{ $interval['activity_level'] * 10 }}%; height:12px"
                                        aria-valuenow="{{ round($interval['activity_level']) }}"
                                        aria-valuemin="1"
                                        aria-valuemax="10">
                                        {{ $interval['activity_level'] }}
                                    </div>
                                </div>
                            </button>

                            {{-- Toast --}}
                            <div class="toast-container p-1">
                                <div id="liveToast-{{ $interval['id'] }}" class="toast bg-white">
                                    <div class="toast-body p-3">

                                        <button type="button"
                                            class="text-danger border-0 btn-close float-end"
                                            data-bs-dismiss="toast"></button>

                                        <p class="fs-12">
                                            <b>Memo</b><br>
                                            Working on creating 4 new pages
                                        </p>

                                        <p class="mt-1 fs-12">
                                            <b>Activity</b><br>
                                            Mouse {{ $interval['mouse_clicks'] }}
                                            Keyboard {{ $interval['keystrokes'] }}
                                        </p>

                                        <div class="d-flex">
                                            <div class="fs-12">
                                                <b>Activity Level</b><br>
                                                Active {{ $interval['countActivityLogs'] }} of 10 min
                                            </div>

                                            <div class="progress w-25 ms-5 mt-3" style="height:12px">
                                                <div class="progress-bar fs-10"
                                                    style="width: {{ $interval['countActivityLogs'] * 10 }}%">
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
                                                        <td>{{ $activity['time'] }}</td>
                                                        <td>{{ $activity['mouse_clicks'] }}</td>
                                                        <td>{{ $activity['keystrokes'] }}</td>
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
    </div> <!-- Timesheet section end-->
</div>

<div class="modal fade" id="modalHourlyDispute" tabindex="-1" aria-labelledby="modalHourlyDisputeLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="modalHourlyDispute">Dispute Hourly Work!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="hourlyDisputeForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="selectedIntervalsContainer"></div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Please describe the reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="2" required></textarea>
                        <div class="invalid-feedback" id="reason-error"></div>

                    </div>

                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input class="form-control" type="file" id="attachment" name="file">
                        <div class="invalid-feedback" id="file-error"></div>
                    </div>

                    <h5>Dispute Offer</h5>
                    <p>Amount to dispute: <span id="disputeAmount"></span></p>

                    <div class="mb-3">
                        <label for="employerAmount" class="form-label">Employer Amount</label>
                        <input class="form-control" id="buyer_amount" name="buyer_amount" rows="1" required>
                        <div class="invalid-feedback" id="seller_amount-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="freelancerAmount" class="form-label">Freelancer Amount</label>
                        <input class="form-control" id="seller_amount" name="seller_amount" rows="1" required>
                        <div class="invalid-feedback" id="buyer_amount-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="messageToFreelancer" class="form-label">Message to freelancer</label>
                        <textarea class="form-control" id="messageToFreelancer" name="message" rows="2"></textarea>
                        <div class="invalid-feedback" id="message-error"></div>
                    </div>
                    <!-- Hidden fields -->
                    <input type="hidden" id="total_cost" name="total_cost">

                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-success" id="sendHourlyDispute">Send</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="manualLogModal" tabindex="-1" aria-labelledby="manualLogModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="manualLogModalLabel">Manual Log Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <p><strong>Request Description:</strong> <span>{{ $requestDescription }}</span>  </p>
                <h6>Do you want to allow manual time logging for this request?</h6>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <form id="manualLogAcceptForm" method="POST"
                    action="{{ route('user.buyer.manual.log.response') }}">
                    @csrf
                    <input type="hidden" name="encBuyerCounterOfferDataId" value="{{ $encBuyerCounterOfferDataId }}">
                    <button type="submit" name="response" value="accept"
                        class="btn btn-sm btn-success">Accept</button>
                    <button type="submit" name="response" value="reject"
                        class="btn btn-sm btn-danger">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>
