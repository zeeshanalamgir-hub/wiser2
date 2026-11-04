@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Details')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <p class="text-black">@lang('Amount') : <span class="withdraw-amount "></span></p>
                            <p class="text-black">@lang('Charge') : <span class="withdraw-charge "></span></p>
                            <p class="text-black">@lang('After Charge') : <span class="withdraw-after_charge"></span></p>
                            <p class="text-black">@lang('Conversion Rate') : <span class="withdraw-rate"></span></p>
                            <p class="text-black">@lang('Payable Amount') : <span class="withdraw-payable"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="">
                                <div class="form-group">
                                    <label class="text-dark ft-medium">Enter Deposit Amount</label>
                                    <input type="text" class="form-control rounded" placeholder="$200">
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Details')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="withdraw-detail"></div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        {{--  <div class="dashboard-tlbar d-block mb-5">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                            <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard-manage-service.html" class="theme-cl">Manage
                                    Services</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>  --}}
        <div class="row">
            <h5 class="text-dgreen2 mt-2">@lang('Deposit History')</h5>
        </div>
        <div class="dashboard-widg-bar d-block mt-4">

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table w-100">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">@lang('Initiated')</th>
                                        <th scope="col">@lang('Transaction ID')</th>
                                        <th scope="col">@lang('Gateway')</th>
                                        <th scope="col">@lang('Amount')</th>
                                        <th scope="col">@lang('Status')</th>
                                        {{--  <th scope="col"> @lang('Details')</th>  --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $k=>$data)
                                        <tr>

                                            <td> {{ showDateTime($data->created_at) }}
                                                <br>
                                                {{ diffforhumans($data->updated_at) }}
                                            </td>
                                            <td>{{ $data->trx }}</td>
                                            <td>{{ __(@$data->gateway->name) }}</td>
                                            <td>${{ getAmount($data->amount) }}</td>
                                            <td>
                                                @if ($data->status == 1)
                                                    <span class="badge bg-success">@lang('Complete')</span>
                                                    <br>
                                                    {{ diffforhumans($data->updated_at) }}
                                                @elseif ($data->status == 2)
                                                    <span class="badge bg-warning text-dark">@lang('Pending')</span>
                                                @elseif ($data->status == 3)
                                                    <span class="badge bg-danger">@lang('Cancel')</span>
                                                @endif

                                                @if (!empty($data->admin_feedback))
                                                    <button type="button"
                                                            class="badge bg-info text-white border-0 detailBtn"
                                                            data-admin_feedback="{{ $data->admin_feedback }}">
                                                        <i class="fa fa-info"></i>
                                                    </button>
                                                @endif
                                            </td>

                                            @php
                                                $details = $data->detail != null ? json_encode($data->detail) : null;
                                            @endphp
                                            {{--  <td>

                                                <div class="dash-action-98563">
                                                    <a data-info="{{ $details }}" data-id="{{ $data->id }}"
                                                        data-amount="{{ getAmount($data->amount) }} {{ __($general->cur_text) }}"
                                                        data-charge="{{ getAmount($data->charge) }} {{ __($general->cur_text) }}"
                                                        data-after_charge="{{ getAmount($data->amount + $data->charge) }} {{ __($general->cur_text) }}"
                                                        data-rate="{{ getAmount($data->rate) }} {{ __($data->method_currency) }}"
                                                        data-payable="{{ getAmount($data->final_amo) }} {{ __($data->method_currency) }}"
                                                        href="javascript:void(0);" data-toggle="modal"
                                                        data-target="#approveModal"
                                                        class="approveBtn"><i class="far fa-eye"></i></a>
                                                    <!-- <a href="javascript:void(0);" class="p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i class="lni lni-pencil"></i></a>
                   <a href="javascript:void(0);" class="p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="lni lni-trash-can"></i></a> -->
                                                </div>
                                            </td>  --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endsection
    @section('script')
        <script type="text/javascript">
            (function($) {
                "use strict";
                $('.approveBtn').on('click', function() {
                    var modal = $('#approveModal');
                    modal.find('.withdraw-amount').text($(this).data('amount'));
                    modal.find('.withdraw-charge').text($(this).data('charge'));
                    modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                    modal.find('.withdraw-rate').text($(this).data('rate'));
                    modal.find('.withdraw-payable').text($(this).data('payable'));
                    var list = [];
                    var details = Object.entries($(this).data('info'));

                    var ImgPath = "{{ asset(imagePath()['verify']['deposit']['path']) }}/";
                    var singleInfo = '';
                    for (var i = 0; i < details.length; i++) {
                        if (details[i][1].type == 'file') {
                            singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <img src="${ImgPath}/${details[i][1].field_name}" alt="@lang('Image')" class="w-100">
                                        </li>`;
                        } else {
                            singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${details[i][1].field_name}</span>
                                        </li>`;
                        }
                    }

                    if (singleInfo) {
                        modal.find('.withdraw-detail').html(
                            `<br><strong class="my-3">@lang('Payment Information')</strong>  ${singleInfo}`);
                    } else {
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
    @endsection
