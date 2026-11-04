@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Work Delivery Approval Confirmation')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.work.delivery.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="service">
                        <p>@lang('Are you sure to approved this work delivery')</p>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disputeModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Are you sure to dispute this booking')</h2>
                    </div>
                    <form method="POST" action="">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="service">
                        <p>@lang('Are you sure to cancel this Proposal')</p>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="dispute" placeholder="Why dispute ...." required></textarea>
                            <small>@lang('Minimum 50 Characters Required')</small>
                        </div>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Dispute Report')</h2>
                    </div>
                    <div class="dispute-detail">
                    </div>
                    <div class="form-group">
                    </div>
                    <div class="form-group text-center mb-0">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <h5 class="text-dgreen2 mt-3">Booking Services</h5>
        <div class="col-12">
            @include('templates.basic.user.buyer.service_nav')
            <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                @forelse($serviceBookings as $booking)
                    @php
                        $statuses = [
                            0 => ['label' => 'Unpaid'],
                            1 => ['label' => 'Running'],
                            2 => ['label' => 'Payable Both'],
                            3 => ['label' => 'Paid'],
                            4 => ['label' => 'Refund'],
                            5 => ['label' => 'Accepted'],
                            6 => ['label' => 'Completed'],
                            7 => ['label' => 'Admin'],
                            8 => ['label' => 'In Dispute'],
                            9 => ['label' => 'Pending'],
                            10 => ['label' => 'Cancel'],
                        ];
                        $status = $statuses[$booking->status] ?? ['label' => 'Unknown'];
                    @endphp

                    <h6 class="mb-1 mt-{{ !$loop->first ? '3' : '2' }}">
                        <a href="{{ route('user.service-booked-details', encrypt($booking->id)) }}" class="links-c2">
                            {{ __($booking?->service?->title) }}
                        </a>
                    </h6>
                    <div class="border-bottom row">
                        <div class="pb-3 fs-14 col-xl-9">
                            <ul class="nav">
                                <div class="nav-item me-3"><i class="fa-solid fa-calendar-days text-dgreen2"></i>
                                    {{ showDateTime($booking->created_at, 'd/m/Y') }}</div>
                                <div class="nav-item me-3"><i class="fa-solid fa-box text-dgreen2"></i>
                                    {{ $booking->order_number }}</div>
                                <div class="nav-item me-3"><i class="fa-solid fa-user-check text-dgreen2"></i>
                                    {{ $booking?->service?->user?->username ?? 'N/A' }}</div>
                                <div class="nav-item me-3"><i class="fa-solid fa-circle-dollar-to-slot text-dgreen2"></i>
                                    {{ showAmount($booking->amount) }} {{ $general->cur_text }}</div>
                                <div class="nav-item me-3"><i class="fa-solid fa-calendar-check text-dgreen2"></i>
                                    {{ showDateTime($booking->due_date, 'd/m/Y') }}</div>
                                <div class="nav-item me-3 me-md-0"><i class="fa-solid fa-clipboard-list text-dgreen2"></i>
                                    {{ $status['label'] }}</div>
                            </ul>
                        </div>
                        <div class="fs-14 mb-3 col-xl-3">
                            <div class="float-xl-end">
                                @if ($booking->status == 0 || $booking->status == 8 || $booking->status == 10)
                                    {{-- Awaiting --}}
                                    <a href="{{ route('user.service-booked-details', encrypt($booking->id)) }}"
                                        class="links-c2 border p-1 rounded">
                                        <i class="fa-regular fa-eye text-green"></i> View
                                    </a>
                                    {{-- <a href="#" class="mx-2 links-c2 border p-1 rounded approvedBtn"
                                        data-bs-toggle="modal" data-order-number="{{ $booking->order_number }}"
                                        data-bs-target="#acceptModal_{{ $booking->id }}">
                                        <i class="fa-solid fa-check text-green"></i> Accept
                                    </a>
                                    <a href="#" class="links-c2 border p-1 rounded cancelBtn"
                                        data-bs-toggle="modal" data-order-number="{{ $booking->order_number }}"
                                        data-bs-target="#declineModal_{{ $booking->id }}">
                                        <i class="fa-solid fa-xmark text-danger"></i> Decline
                                    </a> --}}
                                @elseif ($booking->status == 3 || $booking->status == 8)
                                    {{-- Accepted --}}
                                    <a href="{{ route('user.service-booked-details', encrypt($booking->id)) }}"
                                        class="links-c2 border p-1 rounded">
                                        <i class="fa-regular fa-eye text-green"></i> View
                                    </a>
                                    <a href="{{ route('user.service-booked-details-workprogress', encrypt($booking->id)) }}"
                                        class="border mx-2 links-c2 p-1 rounded text-success">
                                        <i class="fa-solid fa-building text-primary"></i> Workroom
                                    </a>
                                @elseif ($booking->status == 6 || $booking->status == 9)
                                    {{-- Completed --}}
                                    <a href="{{ route('user.service-booked-details', encrypt($booking->id)) }}"
                                        class="border links-c2 p-1 rounded text-success">
                                        <i class="fa-regular fa-eye text-green"></i> View
                                    </a>
                                    {{-- <a href="{{ route('user.service-booked-details-workprogress', encrypt($booking->id)) }}"
                                        class="border mx-2 links-c2 p-1 rounded text-success">
                                        <i class="fa-solid fa-building text-primary"></i> Workroom
                                    </a> --}}
                                @elseif ($booking->status == 5 || $booking->status == 8)
                                    {{-- Cancelled --}}
                                    <a href="{{ route('user.service-booked-details', encrypt($booking->id)) }}"
                                        class="border links-c2 p-1 rounded text-success">
                                        <i class="fa-regular fa-eye text-green"></i> View
                                    </a>

                                    <a href="{{ route('user.service-booked-details-workprogress', encrypt($booking->id)) }}"
                                        class="border mx-2 links-c2 p-1 rounded text-success">
                                        <i class="fa-solid fa-building text-primary"></i> Workroom
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No service bookings available.</p>
                @endforelse
            </div>

            @if ($serviceBookings->hasPages())
                <div class="mt-4">
                    {{ $serviceBookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        'use strict';
        $('.approvedBtn').on('click', function() {
            var modal = $('#approvedModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.disputeBtn').on('click', function() {
            var modal = $('#disputeModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.disputeShow').on('click', function() {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                searching: false,
                lengthChange: false,
                // paging: false
            });
        });
    </script>
@endsection
