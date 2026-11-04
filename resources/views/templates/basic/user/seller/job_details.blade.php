@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.seller_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="card custom--card">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                {{__($pageTitle)}}
                            </h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">

                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Amount')
                                <span>{{__($general->cur_sym)}}{{showAmount($jobListDetails->amount)}}</span>
                              </li>

                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Order Number')
                                <span>{{__($jobListDetails->order_number)}}</span>
                              </li>

                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Delivery Date')
                                <span>{{showDateTime($jobListDetails->created_at->addDays($jobListDetails->biding->job->delivery_time), ('d M Y'))}}</span>
                              </li>

                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Working Status')
                                @if($jobListDetails->working_status == 0)
                                    <span class="badge badge--primary">@lang('Pending')</span>
                                @elseif($jobListDetails->working_status == 1)
                                    <span class="badge badge--success">@lang('Completed')</span>
                                @elseif($jobListDetails->working_status == 2)
                                    <span class="badge badge--secondary">@lang('Delivered')</span>
                                @elseif($jobListDetails->working_status == 3)
                                    <span class="badge badge--danger">@lang('Cancel')</span>
                                @elseif($jobListDetails->working_status == 4)
                                    <span class="badge badge--info">@lang('In Progress')</span>
                                @elseif($jobListDetails->working_status == 5)
                                    <span class="badge badge--danger">@lang('Delivery Expired')</span>
                                @elseif($jobListDetails->working_status == 6)
                                    <button class="btn-info btn-rounded text-white  badge disputeShow" data-dispute="{{$jobListDetails->dispute_report}}"><i class="fa fa-info"></i></button>
                                    <span class="badge badge--warning">@lang('Dispute')</span>
                                @endif
                              </li>

                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Status')
                                    @if($jobListDetails->status == 1)
                                        <span class="badge badge--primary">@lang('Running')</span>
                                    @elseif($jobListDetails->status == 2)
                                        <span class="badge badge--warning">@lang('Payable Both')</span>
                                    @elseif($jobListDetails->status == 3)
                                        <span class="badge badge--success">@lang('Paid')</span>
                                    @elseif($jobListDetails->status == 4)
                                        <span class="badge badge--info">@lang('Refund')</span>
                                    @endif
                              </li>
                            </ul>

                            @if($jobListDetails->workFile->isNotEmpty())
                                <div class="mt-3">
                                <h4 class="text-center">@lang('Work Delivery')</h4>
                                    @foreach($jobListDetails->workFile as $file)
                                    <div class="text-center mt-2">
                                        <h5>@lang('Delivery Details')</h5>
                                        <p>{{__($file->details)}}</p>
                                        <a href="{{route('user.work.file.download', encrypt($file->id))}}" class="btn btn--primary btn-rounded text-white">@lang('File Download')</a>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



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
    $('.disputeShow').on('click', function () {
        var modal = $('#disputeModalShow');
        var feedback = $(this).data('dispute');
        modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
        modal.modal('show');
    });
</script>
@endpush
