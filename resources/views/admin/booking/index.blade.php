@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Seller')</th>
                                    <th>@lang('Order Number')</th>
                                    <th>@lang('Delivery Date')</th>
                                    <th>@lang('Service Price')</th>
                                    <th>@lang('Quantity')</th>
                                    <th>@lang('Discount')</th>
                                    <th>@lang('Extra Service')</th>
                                    <th>@lang('Agreed Dispute Amount')</th>
                                    <th>@lang('Amount')</th>
                                    <!-- <th>@lang('Working Status')</th> -->
                                    <th>@lang('Status')</th>
                                    <th>@lang('Chats')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($bookings as $booking)
                                    @php
                                        $add_ser = DB::table('additional_services')
                                            ->where('booking_id', $booking->id)
                                            ->get();
                                        $add_ser_count = DB::table('additional_services')
                                            ->where('booking_id', $booking?->id)
                                            ->count();
                                    @endphp
                                    <tr @if ($loop->odd) class="table-light" @endif>
                                        <td data-label="@lang('Buyer')">
                                            <span class="font-weight-bold">{{ @$booking?->user?->fullname }}</span>
                                            <br>

                                            {{-- <span class="small">
                                                <a href="{{ route('admin.users.detail', ['id'=>$booking?->service?->id]) }}"><span>@</span>{{ $booking?->buyer?->fullname }}</a>
                                            </span> --}}

                                            <a href="{{ $booking?->service?->id ? route('admin.users.detail', $booking->service->id) : '#' }}">
                                                <span>@</span>{{ $booking?->buyer?->fullname }}
                                            </a>
                                        </td>

                                        <td data-label="@lang('Seller')">
                                            <span
                                                class="font-weight-bold">{{ @$booking?->service?->user?->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                @if (@$booking?->service?->buyer_id == '')
                                                @else
                                                    <a
                                                        href="{{ route('admin.users.detail', ['id' => @$booking?->service?->user?->id]) }}"><span>@</span>{{ @$booking?->service?->user?->username }}</a>
                                                @endif

                                            </span>
                                        </td>

                                        <td data-label="@lang('Order Number')"><span
                                                class="font-weight-bold">{{ @$booking?->order_number }}</span></td>
                                        <td data-label="@lang('Delivery Date')">
                                            <span
                                                class="font-weight-bold">{{ showDateTime(@$booking?->created_at->addDays(@$booking?->service?->delivery_time), 'd M Y') }}</span>
                                            <br>
                                            {{ diffforhumans(@$booking?->created_at->addDays($booking?->service?->delivery_time)) }}
                                        </td>

                                        <td data-label="@lang('Service Price')">
                                            <span
                                                class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount(@$booking?->service_price) }}</span>
                                        </td>

                                        <td data-label="@lang('Quantity')">
                                            <span class="font-weight-bold">{{ getAmount($booking->qty) }}</span>
                                        </td>

                                        <td data-label="@lang('Discount')">
                                            @if ($booking->discount != 0)
                                                <span
                                                    class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount($booking->discount) }}</span>
                                            @else
                                                <span class="font-weight-bold">@lang('N/A')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Discount')">
                                            @if ($add_ser_count > 0)
                                                <span
                                                    class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount(@$add_ser->sum('amount')) }}</span>
                                            @else
                                                <span class="font-weight-bold">$0</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Discount')">
                                            @if ($booking->agreed_amount > 0)
                                                <span
                                                    class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount(@$booking->agreed_amount) }}</span>
                                            @else
                                                <span class="font-weight-bold">$0</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Amount')">
                                            <span
                                                class="font-weight-bold">{{ $general->cur_sym }}{{ @$booking->service_price * @$booking->qty + @$add_ser->sum('amount') - @$booking->agreed_amount }}</span>
                                        </td>

                                        <!-- <td data-label="@lang('Working Status')">
                                            @if ($booking->working_status == 0)
    <span class="badge badge--primary">@lang('Pending')</span>
                                                <br>
                                                {{ diffforhumans($booking->updated_at) }}
@elseif($booking->working_status == 1)
    <span class="badge badge--success">@lang('Completed')</span>
                                                 <br>
                                                 {{ diffforhumans($booking->updated_at) }}
@elseif($booking->working_status == 2)
    <span class="badge badge--dark">@lang('Delivered')</span>
                                                 <br>
                                                 {{ diffforhumans($booking->updated_at) }}
@elseif($booking->working_status == 3)
    <span class="badge badge--danger">@lang('Cancel')</span>
                                                <br>
                                                {{ diffforhumans($booking->updated_at) }}
@elseif($booking->working_status == 4)
    <span class="badge badge--success">@lang('In Progress')</span>
                                                <br>
                                                {{ diffforhumans($booking->updated_at) }}
@elseif($booking->working_status == 5)
    <span class="badge badge--danger">@lang('Expired')</span>
                                                <br>
                                                {{ diffforhumans($booking->updated_at) }}
@elseif($booking->working_status == 6)
    <span class="badge badge--warning">@lang('Dispute')</span>
                                                 <button class="btn-info btn-rounded text-white  badge disputeShow" data-dispute="{{ $booking->dispute_report }}"><i class="fa fa-info"></i></button>
                                                <br>
                                                {{ diffforhumans($booking->updated_at) }}
    @endif
                                        </td> -->

                                        <td data-label="@lang('Status')">
                                            <!-- @if ($booking->status == 1)
    <span class="badge badge--success">@lang('Running')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
@elseif($booking->status == 2)
    <span class="badge badge--warning">@lang('Payable Both')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
@elseif($booking->status == 3)
    <span class="badge badge--success">@lang('Paid')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
@elseif($booking->status == 4)
    <span class="badge badge--success">@lang('Refund')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
    @endif -->
                                            @if ($booking->status == 1)
                                                <span class="badge badge--success">@lang('In Pregress')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 2)
                                                <span class="badge badge--warning">@lang('Payable Both')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 3)
                                                <span class="badge badge--success">@lang('Paid & Delivered')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 4)
                                                <span class="badge badge--success">@lang('Refund')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 5)
                                                <span class="badge badge--primary">@lang('pending')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 6)
                                                <span class="badge badge--success">@lang('completed')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 0)
                                                <span class="badge badge--warning">@lang('unpaid')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 8)
                                                <span class="badge badge--danger">@lang('in Dispute')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @elseif($booking->status == 7)
                                                <span class="badge badge--danger">@lang('dispute send to admin')</span>
                                                <br>
                                                {{ diffforhumans($booking->status_updated_at) }}
                                            @endif
                                        </td>

                                        <td data-label="@lang('chats')">
                                            <a href="{{ route('admin.user.chat', [$booking->buyer->id, $booking->buyer->id]) }}"
                                                class="icon-btn mr-2" data-toggle="tooltip" title=""
                                                data-original-title="@lang('Edit')">
                                                <i class="las la-edit text--shadow"></i>
                                            </a>
                                        </td>
                                        <td data-label="@lang('Action')">

                                            <a href="{{ route('admin.booking.service.details', $booking->id) }}"
                                                class="icon-btn" data-toggle="tooltip" title=""
                                                data-original-title="@lang('Details')">
                                                <i class="las la-desktop text--shadow"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($bookings) }}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Dispute Report')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="dispute-detail">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <form
        action="{{ route('admin.booking.service.search', $scope ?? str_replace('admin.booking.service.', '', request()->route()->getName())) }}"
        method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Buyer / Seller')"
                value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush


@push('script')
    <script>
        'use strict';
        $('.disputeShow').on('click', function() {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>
@endpush
