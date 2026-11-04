@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Employees')</th>
                                    <th>@lang('Order Number')</th>


                                    <th>@lang('Job Type')</th>
                                    <th>@lang('Job Title')</th>
                                    <th>@lang('Delivery Date')</th>
                                    <th>@lang('Milestone Title')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Milestone Status')</th>
                                    {{-- <th>@lang('Working Status')</th> --}}
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    @php
                                        // Filter intervals with status 6
                                        $jobWorkIntervals =
                                            $booking->job->jobWorkDeliveryHourlyBids->flatMap(function ($hourlyBid) {
                                                return $hourlyBid->intervals->where('status', 6);
                                            }) ?? collect();
                                    @endphp
                                    {{-- Check for project-based or hourly data --}}
                                    @if (
                                        ($booking->buyerCounterOffers && $booking->buyerCounterOffers->buyerCounterOfferData->isNotEmpty()) ||
                                            $jobWorkIntervals->isNotEmpty())
                                        {{-- Display project-based milestones --}}
                                        @if ($booking->buyerCounterOffers && $booking->buyerCounterOffers->buyerCounterOfferData->isNotEmpty())
                                            @foreach ($booking->buyerCounterOffers->buyerCounterOfferData as $offerData)
                                                <tr @if ($loop->parent->odd) class="table-light" @endif>
                                                    <!-- Common Columns -->
                                                    <td data-label="@lang('Buyer')">
                                                        <span
                                                            class="font-weight-bold">{{ $booking?->buyer?->username }}</span><br>
                                                        <span class="small">
                                                            <a href="{{ route('admin.users.detail', $booking->buyer_id) }}">
                                                                <span>@</span>{{ $booking?->buyer?->username }}
                                                            </a>
                                                        </span>
                                                    </td>

                                                    <td data-label="@lang('Employees')">
                                                        <span
                                                            class="font-weight-bold">{{ $booking?->seller?->username }}</span><br>
                                                        <span class="small">
                                                            <a
                                                                href="{{ route('admin.users.detail', $booking->job->user_id) }}">
                                                                <span>@</span>{{ $booking?->seller?->username }}
                                                            </a>
                                                        </span>
                                                    </td>
                                                    <td data-label="@lang('Order Number')">
                                                        <span class="font-weight-bold">{{ $booking->order_number }}</span>
                                                    </td>

                                                    <td data-label="@lang('Job Type')">
                                                        <span class="font-weight-bold">
                                                            {{ $booking->booking_type }}
                                                        </span><br>
                                                    </td>
                                                    <td data-label="@lang('Job Title')">{{ $booking->job->title }}</td>
                                                    <td data-label="@lang('Delivery Date')">
                                                        <span class="font-weight-bold">
                                                            {{ showDateTime($offerData->due_date, 'd M Y') }}
                                                        </span><br>
                                                        {{ diffforhumans($offerData->due_date) }}
                                                    </td>
                                                    <!-- Display project milestones -->

                                                    <td data-label="@lang('Milestone Title')">{{ $offerData->title ?? "Project Payment" }}</td>
                                                    <td data-label="@lang('Amount')">
                                                        <span
                                                            class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount($offerData->amount) }}</span>
                                                    </td>
                                                    <td data-label="@lang('Milestone Status')">
                                                        @if ($offerData->milestone_status == 0)
                                                            <span class="badge badge--primary">@lang('Not Requested')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                        @elseif($offerData->milestone_status == 1)
                                                            <span class="badge badge--success">@lang('Revision Requested')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                        @elseif($offerData->milestone_status == 2)
                                                            <span
                                                                class="badge badge--secondary">@lang('Requested')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                        @elseif($offerData->milestone_status == 3)
                                                            <span class="badge badge--danger">@lang('Paid')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                            {{-- @elseif($offerData->milestone_status == 4)
                                                            <span class="badge badge--dark">@lang('In Progress')</span>
                                                        @elseif($offerData->milestone_status == 5)
                                                            <span class="badge badge--danger">@lang('Expired')</span> --}}
                                                        @elseif($offerData->milestone_status == 6)
                                                            <span class="badge badge--warning">@lang('Disputed')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                            <a href="{{route('admin.hire.details', $offerData->id)}}"
                                                                class="btn-info btn-rounded text-white  badge"
                                                                data-dispute="{{ $booking->dispute_report }}"><i
                                                                    class="fa fa-info"></i></a>
                                                        @elseif($offerData->milestone_status == 7)
                                                            <span class="badge badge--warning">@lang('Refund')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                        @elseif($offerData->milestone_status == 8)
                                                            <span class="badge badge--warning">@lang('Milestone Requested')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                        @elseif($offerData->milestone_status == 9)
                                                            <span class="badge badge--warning">@lang('Milestone Request Rejected')</span><br>
                                                            {{ diffforhumans($booking->updated_at) }}
                                                        @endif
                                                    </td>
                                                    <td data-label="@lang('Status')">
                                                        @if ($booking->status == 1)
                                                            <span class="badge badge--success">@lang('Running')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @elseif ($booking->status == 2)
                                                            <span class="badge badge--warning">@lang('Payable Both')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @elseif ($booking->status == 3)
                                                            <span class="badge badge--success">@lang('Paid')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @elseif ($booking->status == 4)
                                                            <span class="badge badge--success">@lang('Refund')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @endif
                                                    </td>
                                                    <td data-label="@lang('Action')">
                                                        <a href="{{ route('admin.hire.details', $offerData->id) }}"
                                                            class="icon-btn" title="@lang('Details')">
                                                            <i class="las la-desktop text--shadow"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        {{-- Display hourly jobs --}}
                                        @if ($jobWorkIntervals->isNotEmpty())
                                            @foreach ($jobWorkIntervals as $jobWorkInterval)
                                                <tr @if ($loop->odd) class="table-light" @endif>
                                                    <!-- Buyer Column -->
                                                    <td data-label="@lang('Buyer')">
                                                        <span
                                                            class="font-weight-bold">{{ $booking->buyer->username }}</span><br>
                                                        <span class="small">
                                                            <a
                                                                href="{{ route('admin.users.detail', $booking->buyer_id) }}">
                                                                <span>@</span>{{ $booking->buyer->username }}
                                                            </a>
                                                        </span>
                                                    </td>

                                                    <!-- Employees Column -->
                                                    <td data-label="@lang('Employees')">
                                                        <span
                                                            class="font-weight-bold">{{ $booking->seller->username }}</span><br>
                                                        <span class="small">
                                                            <a
                                                                href="{{ route('admin.users.detail', $booking->job->user_id) }}">
                                                                <span>@</span>{{ $booking->seller->username }}
                                                            </a>
                                                        </span>
                                                    </td>

                                                    <!-- Order Number Column -->
                                                    <td data-label="@lang('Order Number')">
                                                        <span class="font-weight-bold">{{ $booking->order_number }}</span>
                                                    </td>

                                                    <!-- Job Type Column -->
                                                    <td data-label="@lang('Job Type')">
                                                        <span
                                                            class="font-weight-bold">{{ $booking->booking_type }}</span><br>
                                                    </td>

                                                    <!-- Job Title Column -->
                                                    <td data-label="@lang('Job Title')">
                                                        <span class="font-weight-bold">{{ $booking->job->title }}</span>
                                                    </td>

                                                    <!-- Delivery Date Column -->
                                                    <td data-label="@lang('Delivery Date')">
                                                        <span class="font-weight-bold">
                                                            {{ showDateTime($jobWorkInterval->start_time, 'd M Y') }}
                                                        </span><br>
                                                        {{ diffforhumans($jobWorkInterval->start_time) }}
                                                    </td>

                                                    <!-- Memo Column -->
                                                    <td data-label="@lang('Memo')">
                                                        {{ $jobWorkInterval->memo }}
                                                    </td>

                                                    <!-- Total Cost Column -->
                                                    <td data-label="@lang('Total Cost')">
                                                        {{ $general->cur_sym }}{{ getAmount($jobWorkInterval->cost) }}
                                                    </td>

                                                    <!-- Interval Status Column -->
                                                    <td data-label="@lang('Status')">
                                                        @if ($jobWorkInterval->status == 6)
                                                            <span class="badge badge--warning">@lang('Disputed')</span>
                                                        @elseif ($jobWorkInterval->status == 3)
                                                            <span class="badge badge--success">@lang('Completed')</span>
                                                        @else
                                                            <span class="badge badge--primary">@lang('In Progress')</span>
                                                        @endif
                                                    </td>
                                                    <td data-label="@lang('Status')">
                                                        @if ($booking->status == 1)
                                                            <span class="badge badge--success">@lang('Running')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @elseif ($booking->status == 2)
                                                            <span class="badge badge--warning">@lang('Payable Both')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @elseif ($booking->status == 3)
                                                            <span class="badge badge--success">@lang('Paid')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @elseif ($booking->status == 4)
                                                            <span class="badge badge--success">@lang('Refund')</span><br>
                                                            {{ diffforhumans($booking->status_updated_at) }}
                                                        @endif
                                                    </td>
                                                    <!-- Action Column -->
                                                    <td data-label="@lang('Action')">
                                                        <a href="{{ route('admin.hire.details', $jobWorkInterval->hourlyBid->data_id) }}"
                                                            class="icon-btn" title="@lang('Details')">
                                                            <i class="las la-desktop text--shadow"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-muted text-center">
                                                {{ __('No milestone or hourly data found.') }}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-muted text-center">{{ $emptyMessage }}</td>
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
        action="{{ route('admin.hire.search', $scope ?? str_replace('admin.hire.', '', request()->route()->getName())) }}"
        method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Employees / Buyer')"
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
