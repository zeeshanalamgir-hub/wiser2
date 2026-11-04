@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="all-sections ptb-60">
        <div class="container-fluid">
            <div class="section-wrapper">
                <div class="row justify-content-center mb-30-none">
                    @include($activeTemplate . 'partials.buyer_sidebar')
                    <div class="col-xl-9 col-lg-12 mb-30">
                        <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                        <div class="card custom--card">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                <h4 class="card-title mb-0">
                                    {{ __($pageTitle) }}
                                </h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        @lang('Amount')
                                        <span>{{ __($general->cur_sym) }}{{ showAmount($booking->bid_price) }}</span>
                                    </li>



                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        @lang('Delivery Date')
                                        <span>{{ showDateTime($booking->created_at->addDays($booking->job->delivery_time), 'd M Y') }}</span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        @lang('Working Status')
                                        @if ($booking->working_status == 0)
                                            <span class="badge badge--primary">@lang('Pending')</span>
                                        @elseif($booking->working_status == 1)
                                            <span class="badge badge--success">@lang('Completed')</span>
                                        @elseif($booking->working_status == 2)
                                            <span class="badge badge--secondary">@lang('Delivered')</span>
                                        @elseif($booking->working_status == 3)
                                            <span class="badge badge--danger">@lang('Cancel')</span>
                                        @elseif($booking->working_status == 4)
                                            <span class="badge badge--info">@lang('In Progress')</span>
                                        @elseif($booking->working_status == 5)
                                            <span class="badge badge--danger">@lang('Delivery Expired')</span>
                                        @elseif($booking->working_status == 6)
                                            <button class="btn-info btn-rounded text-white  badge disputeShow"
                                                data-dispute="{{ $booking->dispute_report }}"><i
                                                    class="fa fa-info"></i></button>
                                            <span class="badge badge--warning">@lang('Dispute')</span>
                                        @endif
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        @lang('Status')
                                        @if ($booking->status == 1)
                                            <span class="badge badge--primary">@lang('Running')</span>
                                        @elseif($booking->status == 2)
                                            <span class="badge badge--warning">@lang('Payable Both')</span>
                                        @elseif($booking->status == 3)
                                            <span class="badge badge--success">@lang('Paid')</span>
                                        @elseif($booking->status == 4)
                                            <span class="badge badge--info">@lang('Refund')</span>
                                        @endif
                                    </li>
                                </ul>


                            </div>
                            <div class="card-body">
                                {{ __($booking->proposal) }}
                            </div>
                            @if ($booking->JobworkFile->isNotEmpty())
                                <div class="mt-3">
                                    <h4 class="text-center">@lang('Work Delivery')</h4>
                                    @foreach ($booking->JobworkFile as $file)
                                        <div class="text-center mt-2">
                                            <h5>@lang('Delivery Details')</h5>
                                            <p>{{ __($file->details) }}</p>
                                            <a href="{{ route('user.jobwork.delivery.download', encrypt($file->id)) }}"
                                                class="btn btn--primary btn-rounded text-white">@lang('File Download')</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection


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
