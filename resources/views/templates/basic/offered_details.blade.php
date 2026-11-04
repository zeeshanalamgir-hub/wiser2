@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')

@section('content')
    <style>
        .nicEdit-container,
        .nicEdit-panelContain,
        .nicEdit-main {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }

        .nicEdit-main {
            min-height: 200px;
            /* optional: increase height */
            padding: 10px;
            /* optional: padding inside editor */
            word-wrap: break-word;
        }
    </style>
    {{-- @if ($isCancel == 1)
    <div class="col-12 shadow mt-3 alert alert-danger" role="alert">
        <h5>The buyer cancel his offer</h5>
        <p><b>Reason: </b> {{ $data->reason }}</p>
    </div> --}}
    @if ($status == 2)
        <div class="col-12 shadow mt-3 alert alert-danger" role="alert">
            <h5>Offer rejected by {{ $jobPosterName }}</h5>
            <p><b>Reason: </b> {!! $data->rejected_reason !!}</p>
        </div>
    @endif

    <div class="card shadow mt-2">
        <div class="card-header pt-3">
            <p class="float-end me-3">
                <span class="text-secondary">Status:</span>
                <span class="fw-bold text-{{ $status == 1 ? 'green' : ($status == 2 ? 'danger' : 'warning') }}">
                    @if ($status == 1)
                        <i class="fa-regular fa-circle-check"></i> {{ $data->getStatusLabel() }}
                    @elseif($status == 2)
                        <i class="fa-regular fa-circle-xmark"></i> {{ $data->getStatusLabel() }}
                    @elseif($status == 3)
                        <i class="fa-regular fa-circle-check"></i> {{ $data->getStatusLabel() }}
                    @else
                        <i class="fa-regular fa-clock me-1 bg-light"></i>{{ $data->getStatusLabel() }}
                    @endif
                </span>
            </p>
            <h5>Offered Details</h5>
        </div>

        <div class="card-body p-4">
            <div class="row">
                <div class="col-12">
                    <a class="links" href="{{route('job.details', ['id' => encrypt($data->job->id)])}}" target="_blank">
                        <h5>{{ $data->job->title }}</h5>
                    </a>
                    <p><b>Job Type:</b> {{ ucfirst($data->job->job_type) }}</p>
                    @if($data->invite_professional != 1)
                    <p><b>Description:</b> {!! $data->description ?? 'No description available' !!}</p>
                    @endif
                </div>
                {{-- <div class="col-4">
                <div class="card text-center">
                    <div class="card-header bg-lsgreen">Total Amount</div>
                    <div class="card-body">
                        <h4 class="text-green">
                            ${{ number_format($expected_total_price, 2) }}{{ $data->job->job_type === 'hourly' ? ' /hr' : '' }}
                        </h4>
                    </div>
                </div>
            </div> --}}
            </div>

            @if ($data->type == 'bymilestone' && !empty($milestones))
                <div class="row">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="4">Milestone Title</th>
                                    <th scope="col" colspan="4">Due Date</th>
                                    <th scope="col" colspan="4" class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($milestones as $milestone)
                                    <tr>
                                        <td colspan="4">{{ $milestone->title }}</td>
                                        <td colspan="4">
                                            @if (is_numeric($milestone->due_date))
                                                {{ $milestone->due_date }}
                                            @elseif (\Carbon\Carbon::hasFormat($milestone->due_date, 'Y-m-d') || strtotime($milestone->due_date))
                                                {{ \Carbon\Carbon::parse($milestone->due_date)->format('d-M-Y') }}
                                            @else
                                                No Due Date
                                            @endif
                                        </td>
                                        <td colspan="4" class="text-end">${{ number_format($milestone->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif($data->type == 'byproject')
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="4">Milestone Title</th>
                                    <th scope="col" colspan="4">Due Date</th>
                                    <th scope="col" colspan="4" class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($milestones as $milestone)
                                    <tr>
                                        <td colspan="4">Project Payment</td>
                                        <td colspan="4">
                                            {{ \Carbon\Carbon::parse($milestone->due_date)->format('d-M-Y') }}
                                        </td>
                                        <td colspan="4" class="text-end">${{ number_format($milestone->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tbody>
                            <tr>
                                <td colspan="6">Project Payment</td>
                                <td>
                                    @if (is_numeric($milestone->due_date))
                                        {{ $milestone->due_date }}
                                    @elseif (\Carbon\Carbon::hasFormat($milestone->due_date, 'Y-m-d') || strtotime($milestone->due_date))
                                        {{ \Carbon\Carbon::parse($milestone->due_date)->format('d-M-Y') }}
                                    @else
                                        No Due Date
                                    @endif
                                </td>
                                <td  class="text-end">${{ number_format($expected_total_price, 2) }}</td>
                            </tr>
                        </tbody> --}}
                        </table>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><b>Total</b></td>
                                <td class="text-end">${{ number_format($expected_total_price, 2) }}</td>
                            </tr>
                            <tr>
                                <td>{{ $platformFeePercentage }}% Seller Service Fee</td>
                                <td class="text-end">(${{ number_format($platform_fee, 2) }})</td>
                            </tr>
                            {{-- <tr>
                                <td>You’ll Receive -After service fees, your estimated payment</td>
                                <td class="text-end"><b>${{ number_format($total_after_fee, 2) }}</b></td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- @if($data->job->job_status == 0) --}}
            <div class="row mt-4">
                <div class="col-12">
                    @if ($status == 1)
                        <!-- Workroom Button -->
                        <a href="{{ route('user.seller.workroom', ['encryptedId' => $encryptedJobId]) }}"
                            class="btn btn-primary me-2" title="Go to Workroom">
                            <i class="fas fa-briefcase"></i> Workroom
                        </a>
                    @elseif ($status == 0)
                        <!-- Accept Offer and Reject Offer Buttons -->
                        <a href="{{ route('user.offers.accept', ['encUserBidId' => $encryptedBuyerCounterOfferId]) }}"
                            class="btn btn-success me-2" title="Accept Offer">
                            <i class="fas fa-check"></i> Accept Offer
                        </a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalSendBid" class="btn btn-danger"
                            title="Reject Offer">
                            <i class="fas fa-times"></i> Reject Offer
                        </a>
                    @endif
                    <!-- Offer List Button -->
                    <a href="{{ url('user/seller/job/list/ajax/offers') }}" class="btn btn-secondary me-2"
                        title="View Offer List">
                        <i class="fas fa-list"></i> Offer List
                    </a>
                </div>
            </div>
            {{-- @else
            <div class="col-12 shadow mt-3 alert alert-danger" role="alert">
                <h5>The Job has been expired</h5>
            </div>
            @endif --}}
        </div>
    </div>
    <div class="modal fade" id="modalSendBid" tabindex="-1" aria-labelledby="modalSendBidLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSendBidLabel">Decline Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="offerDeclineForm"
                        action="{{ route('user.offers.invite.decline', ['encUserBidId' => $encryptedBuyerCounterOfferId]) }}"
                        method="POST">
                        @csrf
                        <div class="cover-letter-details-outer-76755">
                            <div class="letter-section-7895">
                                <div class="poppins-semibold ffs-12 decline_reason_title">Decline Reason</div>
                                <textarea rows="7" id="description" name="decline_reason"
                                    class="afacad-regular ffs-12 p-2 form-control with-light" placeholder="Enter your decline reason here..."
                                    style="width: 100% !important;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Decline Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset($activeTemplateTrue . 'frontend/js/nicEdit.js') }}"></script>
    <script>
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

        $(document).ready(function() {
            $('.decline_reason_title').nextAll('div').slice(0, 2).css('width', '100%');
        })
        $('#offerDeclineForm').on('submit', function(e) {
            const editor = nicEditors.findEditor('description');
            if (editor) editor.saveContent();

            const val = $('#description').val().trim();
            if (val === '') {
                e.preventDefault();
                alert('Decline reason is required');
                return false;
            }
        });
    </script>
@endsection
