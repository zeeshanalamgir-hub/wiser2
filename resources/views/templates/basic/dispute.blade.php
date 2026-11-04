@extends($activeTemplate.'layouts.frontend')
@section('content')
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
                                {{-- <th>@lang('Service Price')</th> --}}
                                <th>@lang('Quantity')</th>
                                <th>@lang('Discount')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Working Status')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($bookings as $booking)
                            <tr @if($loop->odd) class="table-light" @endif>
                                <td data-label="@lang('Buyer')">
                                    <span class="font-weight-bold">{{$booking->user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $booking->user_id) }}"><span>@</span>{{ $booking->user->username }}</a>
                                    </span>
                                </td>

                                <td data-label="@lang('Seller')">
                                    <span class="font-weight-bold">{{$booking->service->user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $booking->service->user_id) }}"><span>@</span>{{ $booking->service->user->username }}</a>
                                    </span>
                                </td>

                                <td data-label="@lang('Order Number')"><span class="font-weight-bold">{{$booking->order_number}}</span></td>
                                <td data-label="@lang('Delivery Date')">
                                    <span class="font-weight-bold">{{showDateTime($booking->created_at->addDays($booking->service->delivery_time), ('d M Y'))}}</span>
                                    <br>
                                    {{diffforhumans($booking->created_at->addDays($booking->service->delivery_time))}}
                                </td>

                                <td data-label="@lang('Service Price')">
                                    <span class="font-weight-bold">{{$general->cur_sym}}{{getAmount($booking->service->price)}}</span>
                                </td>

                                <td data-label="@lang('Quantity')">
                                    <span class="font-weight-bold">{{getAmount($booking->qty)}}</span>
                                </td>

                                <td data-label="@lang('Discount')">
                                    @if($booking->discount !=0)
                                        <span class="font-weight-bold">{{$general->cur_sym}}{{getAmount($booking->discount)}}</span>
                                    @else
                                        <span class="font-weight-bold">@lang('N/A')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Amount')">
                                    <span class="font-weight-bold">{{$general->cur_sym}}{{getAmount($booking->amount)}}</span>
                                </td>

                                <td data-label="@lang("Working Status")">
                                    @if($booking->working_status == 0)
                                        <span class="badge badge--primary">@lang('Pending')</span>
                                        <br>
                                        {{diffforhumans($booking->updated_at)}}
                                    @elseif($booking->working_status == 1)
                                        <span class="badge badge--success">@lang('Completed')</span>
                                         <br>
                                         {{diffforhumans($booking->updated_at)}}
                                    @elseif($booking->working_status == 2)
                                        <span class="badge badge--dark">@lang('Delivered')</span>
                                         <br>
                                         {{diffforhumans($booking->updated_at)}}
                                    @elseif($booking->working_status == 3)
                                        <span class="badge badge--danger">@lang('Cancel')</span>
                                        <br>
                                        {{diffforhumans($booking->updated_at)}}
                                    @elseif($booking->working_status == 4)
                                        <span class="badge badge--success">@lang('In Progress')</span>
                                        <br>
                                        {{diffforhumans($booking->updated_at)}}
                                    @elseif($booking->working_status == 5)
                                        <span class="badge badge--danger">@lang('Expired')</span>
                                        <br>
                                        {{diffforhumans($booking->updated_at)}}
                                    @elseif($booking->working_status == 6)
                                        <span class="badge badge--warning">@lang('Dispute')</span>
                                         <button class="btn-info btn-rounded text-white  badge disputeShow" data-dispute="{{$booking->dispute_report}}"><i class="fa fa-info"></i></button>
                                        <br>
                                        {{diffforhumans($booking->updated_at)}}
                                    @endif
                                </td>

                                <td data-label="@lang("Status")">
                                    @if($booking->status == 1)
                                        <span class="badge badge--success">@lang('Running')</span>
                                        <br>
                                        {{diffforhumans($booking->status_updated_at)}}
                                    @elseif($booking->status == 2)
                                        <span class="badge badge--warning">@lang('Payable Both')</span>
                                        <br>
                                        {{diffforhumans($booking->status_updated_at)}}
                                    @elseif($booking->status == 3)
                                        <span class="badge badge--success">@lang('Paid')</span>
                                        <br>
                                        {{diffforhumans($booking->status_updated_at)}}
                                    @elseif($booking->status == 4)
                                        <span class="badge badge--success">@lang('Refund')</span>
                                        <br>
                                        {{diffforhumans($booking->status_updated_at)}}
                                    @endif
                                </td>


                                <td data-label="@lang('Action')">
                                    <a href="{{route('admin.booking.service.details', $booking->id)}}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
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


<div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    <form action="{{ route('admin.booking.service.search', $scope ?? str_replace('admin.booking.service.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Buyer / Seller')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush


@push('script')
<script>
    'use strict';
    $('.disputeShow').on('click', function () {
        var modal = $('#disputeModalShow');
        var feedback = $(this).data('dispute');
        modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
        modal.modal('show');
    });
</script>
@endpush
