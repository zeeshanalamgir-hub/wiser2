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
                                <th>@lang('Discount')</th>
                                <th>@lang('Software')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Document')</th>
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
                                    <span class="font-weight-bold">{{$booking->software->user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $booking->software->user_id) }}"><span>@</span>{{ $booking->software->user->username }}</a>
                                    </span>
                                </td>

                                <td data-label="@lang('Order Number')">
                                    <span class="font-weight-bold">{{$booking->order_number}}</span>
                                </td>


                                <td data-label="@lang('Discount')">
                                    @if($booking->discount != 0)
                                        <span class="font-weight-bold">{{$general->cur_sym}}{{getAmount($booking->amount)}}</span>
                                    @else
                                         <span class="font-weight-bold">@lang('N/A')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Software')">
                                    <a href="{{route('admin.software.file.download', encrypt($booking->software->id))}}" class="icon-btn"><i class="las la-arrow-down"></i></a>
                                </td>

                                 <td data-label="@lang('Amount')">
                                    <span class="font-weight-bold">{{$general->cur_sym}}{{getAmount($booking->amount)}}</span>
                                </td>

                                <td data-label="@lang('Document')">
                                    <a href="{{route('admin.software.document.download', encrypt($booking->software->id))}}" class="icon-btn"><i class="las la-arrow-down"></i></a>
                                </td>

                                <td data-label="@lang("Status")">
                                    @if($booking->status == 3)
                                        <span class="badge badge--success">@lang('Paid')</span>
                                        <br>
                                        <span>{{diffforhumans($booking->updated_at)}}</span>
                                    @else
                                        <span class="badge badge--danger">@lang('N/A')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Action')">
                                    <a href="{{route('admin.software.details', $booking->software->id)}}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
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
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($bookings) }}
                </div>
            </div>
        </div>
    </div>
@endsection



@push('breadcrumb-plugins')
    <form action="{{ route('admin.sales.software.search')}}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Buyer / Seller')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
