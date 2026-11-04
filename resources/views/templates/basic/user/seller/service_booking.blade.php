@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')

    <div class="row">
        <h5 class="text-dgreen2 mt-2">Service Booking</h5>
        <div class="col-12">
            @include('templates.basic.user.seller.service_nav')
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
                        <a href="{{ route('user.booking-service-details', encrypt($booking->id)) }}"
                            class="links-c2">
                            {{ __($booking->service->title) }}
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
                                    {{ $booking?->buyer?->username ?? 'N/A' }}</div>
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
                                @if ($booking->status == 0 || $booking->status == 8)
                                    <a href="{{ route('user.booking-service-details', encrypt($booking->id)) }}"
                                        class="links-c2 border p-1 rounded"><i class="fa-regular fa-eye text-green"></i> View</a>
                                    {{-- <a href="#" class="mx-2 links-c2 border p-1 rounded approvedBtn" data-bs-toggle="modal"
                                        data-order-number="{{ $booking->order_number }}"
                                        data-bs-target="#acceptModal_{{ $booking->id }}"><i
                                            class="fa-solid fa-check text-green"></i> Accept</a>
                                    <a href="#" class="links-c2 border p-1 rounded cancelBtn" data-bs-toggle="modal"
                                        data-order-number="{{ $booking->order_number }}"
                                        data-bs-target="#declineModal_{{ $booking->id }}"><i
                                            class="fa-solid fa-xmark text-danger"></i> Decline</a> --}}
                                @elseif ($booking->status == 3 || $booking->status == 8)
                                    <a href="{{ route('user.booking-service-details', encrypt($booking->id)) }}"
                                        class="links-c2 border p-1 rounded"><i class="fa-regular fa-eye text-green"></i> View</a>
                                    <a href="{{ route('user.booking-service-details-work-progress', encrypt($booking->id)) }}"
                                        class="border mx-2 links-c2 p-1 rounded text-success"><i
                                            class="fa-solid fa-building text-primary"></i> Workroom</a>

                                @elseif ($booking->status == 6 || $booking->status == 9 || $booking->status == 10)
                                    <a href="{{ route('user.booking-service-details', encrypt($booking->id)) }}"
                                        class="border links-c2 p-1 rounded text-success"><i
                                            class="fa-regular fa-eye text-green"></i> View</a>
                                @elseif ($booking->status == 5 || $booking->status == 8)
                                    <a href="{{ route('user.booking-service-details', encrypt($booking->id)) }}"
                                        class="border links-c2 p-1 rounded text-success"><i
                                            class="fa-regular fa-eye text-green"></i> View</a>

                                    <a href="{{ route('user.booking-service-details-work-progress', encrypt($booking->id)) }}"
                                        class="border links-c2 p-1 rounded text-success"><i
                                            class="fa-solid fa-building text-primary"></i> Workroom</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Accept Modal --}}
                    <div class="modal fade" id="acceptModal_{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="max-width:350px">
                                <div class="modal-header bg-green text-white py-2 px-4">
                                    <h5 class="modal-title fs-5">@lang('Approval Booking Confirmation')</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('user.service-booking-confirm') }}">
                                    @csrf
                                    <input type="hidden" name="order_number" value="{{ $booking->order_number }}">
                                    <input type="hidden" name="confirm" value="approved">
                                    <div class="modal-body">
                                        <p class="fs-6">@lang('Are you sure to approve this booking?')</p>
                                    </div>
                                    <div class="modal-footer clear-both">
                                        <button type="submit" class="btn btn-sm p-1 btn-green p-1">@lang('Yes')</button>
                                        <button type="button" class="btn btn-sm btn-light bg-lsgreen"
                                            data-bs-dismiss="modal">@lang('No')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Decline Modal --}}
                    <div class="modal fade" id="declineModal_{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="max-width:350px">
                                <div class="modal-header bg-danger text-white py-2 px-4">
                                    <h5 class="modal-title fs-5">@lang('Cancel Booking Confirmation')</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('user.booking.confirm') }}">
                                    @csrf
                                    <input type="hidden" name="order_number" value="{{ $booking->order_number }}">
                                    <input type="hidden" name="confirm" value="cancel">
                                    <div class="modal-body">
                                        <p class="fs-6">@lang('Are you sure to cancel this booking?')</p>
                                    </div>
                                    <div class="modal-footer clear-both">
                                        <button type="submit" class="btn btn-sm btn-green p-1">@lang('Yes')</button>
                                        <button type="button" class="btn btn-sm btn-light bg-lsgreen"
                                            data-bs-dismiss="modal">@lang('No')</button>
                                    </div>
                                </form>
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
    <script>
        'use strict';
        $('.approvedBtn').on('click', function () {
            const modalSelector = $(this).data('bs-target'); // e.g. "#acceptModal_12"
            const modal = $(modalSelector); // now it's a jQuery object
            modal.find('input[name=order_number]').val($(this).data('order-number'));
        });


        $('.cancelBtn').on('click', function () {
            const modalSelector = $(this).data('bs-target'); // e.g. "#acceptModal_12"
            const modal = $(modalSelector); // now it's a jQuery object
            modal.find('input[name=order_number]').val($(this).data('order-number'));

            // var modal = $('#cancelModal');
            // modal.find('input[name=order_number]').val($(this).data('order_number'))
            // modal.modal('show');
        });

        $('.workBtn').on('click', function () {
            var modal = $('#workModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.disputeShow').on('click', function () {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>

@endsection
