@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    {{--  <p class=" mb-0 text-bc"><a class="text-3gray" href="{{ route('user.home') }}">
        <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i> <span id="current-breadcrumb">Withdraw History</span>
    </p>  --}}

    <h5 class="text-dgreen2 mt-2">Withdraw History</h5>


    <div class="dashboard-content">
        <div class="dashboard-widg-bar d-block mt-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive bg-white" id="tra"
                        style="border-radius: 6px; margin-bottom: 40px">
                        <table id="example" class="table row-border" style="width:100%">
                            <thead class="thead-dark" style="rgba(240, 240, 240, 1)">
                                <tr>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Transaction ID')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Gateway')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Amount')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Charge')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('After Charge')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Rate')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Receivable')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Status')</th>
                                    <th scope="col"
                                        style="color: rgba(0, 63, 69, 1); font-size: 14px; font-weight: 700 ">
                                        @lang('Time')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdraws as $k=>$data)
                                    <tr>
                                        <td data-label="#@lang('Trx')">{{ $data->trx }}</td>
                                        <td data-label="@lang('Gateway')">{{ __($data->method->name) }}</td>
                                        <td data-label="@lang('Amount')">
                                            <strong>${{ getAmount($data->amount) }}</strong>
                                        </td>
                                        <td data-label="@lang('Charge')" class="text-danger">
                                            ${{ getAmount($data->charge) }}
                                        </td>
                                        <td data-label="@lang('After Charge')">
                                            ${{ getAmount($data->after_charge) }}
                                        </td>
                                        <td data-label="@lang('Rate')">
                                            ${{ getAmount($data->rate) }}
                                        </td>
                                        <td data-label="@lang('Receivable')" class="text-success">
                                            <strong>${{ getAmount($data->final_amount) }}</strong>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            <span class="badge bg-success">@lang('Completed')</span>

                                            {{-- @if ($data->status == 2)
                                                <span class="badge bg-warning">@lang('Pending')</span>
                                            @elseif($data->status == 1)
                                                <span class="badge bg-success">@lang('Completed')</span>
                                                <button class="btn btn-info btn-sm rounded-pill text-white approveBtn"
                                                    data-admin_feedback="{{ $data->admin_feedback }}">
                                                    <i class="fa fa-info"></i>
                                                </button>
                                            @elseif($data->status == 3)
                                                <span class="badge bg-danger">@lang('Rejected')</span>
                                                <button class="btn btn-info btn-sm rounded-pill approveBtn"
                                                    data-admin_feedback="{{ $data->admin_feedback }}">
                                                    <i class="fa fa-info"></i>
                                                </button>
                                            @endif --}}
                                        </td>
                                        <td data-label="@lang('Time')">
                                            {{ showDateTime($data->created_at) }}
                                            <br>
                                            {{ diffforhumans($data->updated_at) }}
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
            </div>
        </div>
    </div>

    <!-- Admin Feedback Modal -->
    <div class="modal fade" id="adminFeedbackModal" tabindex="-1" aria-labelledby="adminFeedbackLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminFeedbackLabel">@lang('Admin Feedback')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="adminFeedbackContent">
                    {{-- Feedback text will appear here --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            $('.withdraw').on('click', function() {
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');
                var withdrawLimit =
                    `@lang('Withdraw Limit'): ${minAmount} - ${maxAmount}  {{ __($general->cur_text) }}`;
                $('.withdrawLimit').text(withdrawLimit);
                var withdrawCharge =
                    `@lang('Charge'): ${fixCharge} {{ __($general->cur_text) }} ${(0 < percentCharge) ? ' + ' + percentCharge + ' %' : ''}`
                $('.withdrawCharge').text(withdrawCharge);
                $('.method-name').text(`@lang('Withdraw Via') ${result.name}`);
                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.id);
            });
        })(jQuery);


            $(document).ready(function () {
                $(document).on('click', '.approveBtn', function () {
                    let feedback = $(this).data('admin_feedback') || 'No feedback provided';
                    $('#adminFeedbackContent').text(feedback);
                    $('#adminFeedbackModal').modal('show');
                });
            });

    </script>
@endsection
