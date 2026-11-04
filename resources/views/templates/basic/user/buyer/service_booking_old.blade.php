@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.buyer_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="table-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="table-area">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Service')</th>
                                                <th>@lang('Order Number')</th>
                                                <th>@lang('Seller')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Working Status')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse($serviceBookings as $booking)
                                                <tr>
                                                    <td data-label="@lang('Service')" class="text-start">
                                                        <div class="author-info">
                                                            <div class="thumb">
                                                                <img src="{{getImage('assets/images/service/'.$booking->service->image,'590x300') }}" alt="@lang('Service Image')">
                                                            </div>
                                                            <div class="content"><a href="{{route('service.details', [slug($booking->service->title), $booking->service->id])}}">{{__(str_limit($booking->service->title, 20))}}</a></div>
                                                        </div>
                                                    </td>
                                                    <td data-label="@lang('Order Number')">{{__($booking->order_number)}}</td>
                                                    <td data-label="@lang('Seller')">
                                                    	 <span class="font-weight-bold">{{__(@$booking->service->user->fullname)}}</span>
					                                    <br>
					                                    <span class="text--info">
					                                    <a href="{{route('profile',@$booking->service->user->username)}}"><span>@</span>{{ $booking->service->user->username }}</a>
					                                    </span>
                                                    </td>
                                                    <td data-label="@lang('Amount')">{{showAmount($booking->amount)}} {{__($general->cur_text)}}</td>
                                                    <td data-label="@lang('Working Status')">
                                                    	@if($booking->working_status == 0)
                                                    		<span class="badge badge--primary">@lang('Pending')</span>
                                                            <br>
                                                            {{diffforhumans($booking->updated_at)}}
                                                    	@elseif($booking->working_status == 1)
                                                    		<span class="badge badge--success">@lang('Completed')</span>
                                                            <br>
                                                            {{diffforhumans($booking->updated_at)}}
                                                    	@elseif($booking->working_status == 2)
                                                    		<span class="badge badge--secondary">@lang('Delivered')</span>
                                                            <br>
                                                            {{diffforhumans($booking->updated_at)}}
                                                    	@elseif($booking->working_status == 3)
                                                    		<span class="badge badge--danger">@lang('Cancel')</span>
                                                            <br>
                                                            {{diffforhumans($booking->updated_at)}}
                                                    	@elseif($booking->working_status == 4)
                                                    		<span class="badge badge--info">@lang('In Progress')</span>
                                                            <br>
                                                            {{diffforhumans($booking->updated_at)}}
                                                    	@elseif($booking->working_status == 5)
                                                    		<span class="badge badge--danger">@lang('Delivery Expired')</span>
                                                            <br>
                                                            {{diffforhumans($booking->updated_at)}}
                                                    	@elseif($booking->working_status == 6)

                                                    		<span class="badge badge--warning">@lang('Dispute')</span>
                                                            <button class="btn-info btn-rounded text-white  badge disputeShow" data-dispute="{{$booking->dispute_report}}"><i class="fa fa-info"></i></button>
                                                            <br>
                                                            {{diffforhumans($booking->updated_at)}}
                                                    	@endif
                                                    </td>

                                                     <td data-label="@lang('Status')">
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
			                                                <span class="badge badge--info">@lang('Refund')</span>
                                                            <br>
                                                            {{diffforhumans($booking->status_updated_at)}}
			                                            @endif
                                                    </td>

                                                    <td data-label="Action">
                                                        <a href="{{route('user.buyer.service.booked.details', encrypt($booking->id))}}" class="btn btn--primary text-white ms-1"><i class="las la-desktop"></i></a>
                                                        @if($booking->working_status==2)
                                                            <a href="javascript:void(0)" class="btn btn--success text-white approvedBtn ms-1" data-order_number="{{$booking->order_number}}" data-bs-toggle="modal" data-bs-target="#approvedModal"><i class="las la-check"></i></a>

                                                            <a href="javascript:void(0)" class="btn btn--danger text-white disputeBtn ms-1" data-order_number="{{$booking->order_number}}" data-bs-toggle="modal" data-bs-target="#disputeModal"><i class="las la-times"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$serviceBookings->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Work Delivery Approval Confirmation')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
                <form method="POST" action="{{route('user.work.delivery.approved')}}">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="work_type" value="service">
                    <div class="modal-body">
                        <p>@lang('Are you sure to approved this work delivery')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                         <button type="submit" class="btn btn--success btn-rounded text-white">@lang('Approved')</button>
                    </div>
                </form>
        </div>
    </div>
</div>



<div class="modal fade" id="disputeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Are you sure to dispute this booking')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('user.work.dispute')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_number">
                     <input type="hidden" name="work_type" value="service">
                    <div class="form-group">
                         <textarea rows="8" class="form-control" name="dispute" placeholder="Why dispute ...." required></textarea>
                         <small>@lang('Minimum 50 Characters Required')</small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--base" style="width:100%;">@lang('Submit')</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Dispute Report')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="dispute-detail">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
<script>
    'use strict';
    $('.approvedBtn').on('click', function () {
        var modal = $('#approvedModal');
        modal.find('input[name=order_number]').val($(this).data('order_number'))
        modal.modal('show');
    });

    $('.disputeBtn').on('click', function () {
        var modal = $('#disputeModal');
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
@endpush


