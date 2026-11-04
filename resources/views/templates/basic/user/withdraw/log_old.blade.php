@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                {{-- Sidebar Start Dashboard --}}
                    @include($activeTemplate . 'partials.seller_sidebar')
                {{-- Sidebar End Dashboard --}}
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="table-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="table-area">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Transaction ID')</th>
                                                <th>@lang('Gateway')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Charge')</th>
                                                <th>@lang('After Charge')</th>
                                                <th>@lang('Rate')</th>
                                                <th>@lang('Receivable')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Time')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($withdraws as $k=>$data)
                                                <tr>
                                                    <td data-label="#@lang('Trx')">{{$data->trx}}</td>
                                                    <td data-label="@lang('Gateway')">{{ __($data->method->name) }}</td>
                                                    <td data-label="@lang('Amount')">
                                                        <strong>{{getAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                                                    </td>
                                                    <td data-label="@lang('Charge')" class="text-danger">
                                                        {{getAmount($data->charge)}} {{__($general->cur_text)}}
                                                    </td>
                                                    <td data-label="@lang('After Charge')">
                                                        {{getAmount($data->after_charge)}} {{__($general->cur_text)}}
                                                    </td>
                                                    <td data-label="@lang('Rate')">
                                                        {{getAmount($data->rate)}} {{__($data->currency)}}
                                                    </td>
                                                    <td data-label="@lang('Receivable')" class="text-success">
                                                        <strong>{{getAmount($data->final_amount)}} {{__($data->currency)}}</strong>
                                                    </td>
                                                    <td data-label="@lang('Status')">
                                                        @if($data->status == 2)
                                                            <span class="badge badge--primary">@lang('Pending')</span>
                                                        @elseif($data->status == 1)
                                                            <span class="badge badge--success">@lang('Completed')</span>
                                                            <button class="btn-info btn-rounded text-white  badge approveBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                                                        @elseif($data->status == 3)
                                                            <span class="badge badge--danger">@lang('Rejected')</span>
                                                            <button class="btn-info btn-rounded badge approveBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                                                        @endif

                                                    </td>
                                                    <td data-label="@lang('Time')">
                                                        {{showDateTime($data->created_at)}}
                                                        <br>
                                                        {{diffforhumans($data->updated_at)}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$withdraws->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Details')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
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
        (function($){
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery);

    </script>
@endpush
