@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                 @if(auth()->user()->is_freelancer==1)
                 @include($activeTemplate . 'partials.seller_sidebar')
                @else
                @include($activeTemplate . 'partials.buyer_sidebar')
                @endif
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="table-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="table-area">
                                    <table class="custom-table">
                                        <thead>
                                             <tr>
                                                <th>@lang('Initiated')</th>
                                                <th>@lang('Transaction ID')</th>
                                                <th>@lang('Gateway')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Status')</th>
                                                <th> @lang('Details')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($logs as $k=>$data)
                                                <tr>
                                                    <td data-label="@lang('Time')">
                                                        {{showDateTime($data->created_at)}}
                                                        <br>
                                                         {{diffforhumans($data->updated_at)}}
                                                    </td>
                                                    <td data-label="#@lang('Trx')">{{$data->trx}}</td>
                                                    <td data-label="@lang('Gateway')">{{ __(@$data->gateway->name)  }}</td>
                                                    <td data-label="@lang('Amount')">
                                                        <strong>{{getAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                                                    </td>
                                                    <td>
                                                        @if($data->status == 1)
                                                            <span class="badge badge--success">@lang('Complete')</span>
                                                            <br>
                                                        {{diffforhumans($data->updated_at)}}
                                                        @elseif($data->status == 2)
                                                            <span class="badge badge--warning">@lang('Pending')</span>
                                                        @elseif($data->status == 3)
                                                            <span class="badge badge--danger">@lang('Cancel')</span>
                                                        @endif

                                                        @if($data->admin_feedback != null)
                                                            <button class="btn-info btn-rounded text-white badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                                                        @endif
                                                    </td>

                                                    @php
                                                        $details = ($data->detail != null) ? json_encode($data->detail) : null;
                                                    @endphp

                                                    <td data-label="@lang('Details')">
                                                        <a href="javascript:void(0)" class="btn btn--primary text-white btn-sm approveBtn"
                                                           data-info="{{$details}}"
                                                           data-id="{{ $data->id }}"
                                                           data-amount="{{ getAmount($data->amount)}} {{ __($general->cur_text) }}"
                                                           data-charge="{{ getAmount($data->charge)}} {{ __($general->cur_text) }}"
                                                           data-after_charge="{{ getAmount($data->amount + $data->charge)}} {{ __($general->cur_text) }}"
                                                           data-rate="{{ getAmount($data->rate)}} {{ __($data->method_currency) }}"
                                                           data-payable="{{ getAmount($data->final_amo)}} {{ __($data->method_currency) }}">
                                                            <i class="fa fa-desktop"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$logs->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Details')</h4>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group text-center">
                        <li class="list-group-item dark-bg">@lang('Amount') : <span class="withdraw-amount "></span></li>
                        <li class="list-group-item dark-bg">@lang('Charge') : <span class="withdraw-charge "></span></li>
                        <li class="list-group-item dark-bg">@lang('After Charge') : <span class="withdraw-after_charge"></span></li>
                        <li class="list-group-item dark-bg">@lang('Conversion Rate') : <span class="withdraw-rate"></span></li>
                        <li class="list-group-item dark-bg">@lang('Payable Amount') : <span class="withdraw-payable"></span></li>
                    </ul>
                    <ul class="list-group withdraw-detail mt-1">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Details')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="modal-body">
                    <div class="withdraw-detail"></div>
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
        (function ($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-charge').text($(this).data('charge'));
                modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                modal.find('.withdraw-rate').text($(this).data('rate'));
                modal.find('.withdraw-payable').text($(this).data('payable'));
                var list = [];
                var details =  Object.entries($(this).data('info'));

                var ImgPath = "{{asset(imagePath()['verify']['deposit']['path'])}}/";
                var singleInfo = '';
                for (var i = 0; i < details.length; i++) {
                    if (details[i][1].type == 'file') {
                        singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <img src="${ImgPath}/${details[i][1].field_name}" alt="@lang('Image')" class="w-100">
                                        </li>`;
                    }else{
                        singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${details[i][1].field_name}</span>
                                        </li>`;
                    }
                }

                if (singleInfo)
                {
                    modal.find('.withdraw-detail').html(`<br><strong class="my-3">@lang('Payment Information')</strong>  ${singleInfo}`);
                }else{
                    modal.find('.withdraw-detail').html(`${singleInfo}`);
                }
                modal.modal('show');
            });

            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

