@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout')
@section('content')
    <div class="dashboard-content">
        <div class="dashboard-tlbar d-block mb-5">
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
        </div>

        <div class="dashboard-widg-bar d-block">

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">@lang('Job')</th>
                                        <th scope="col">@lang('Buyer')</th>
                                        <th scope="col">@lang('Amount')</th>
                                        <th scope="col">@lang('Working Status')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jobVacancys as $booking)
                                        <tr>
                                            <td>
                                                <img src="{{ getImage('assets/images/job/' . $booking->job->image, '590x300') }}"
                                                    alt="@lang('Job Image')" class="img-fluid circle" width="50" />
                                            </td>
                                            <td>{{ __(str_limit($booking->job->title, 20)) }}</td>
                                            <td>{{ __($booking->user->fullname) }} <br> <a
                                                    href="{{ route('profile', [slug($booking->user->username), encrypt($booking->user->id)]) }}"><span>@</span>{{ $booking->user->username }}</a>
                                            </td>
                                            <td>{{ showAmount($booking->bid_price) }} {{ __($general->cur_text) }}</td>
                                            <td>
                                                @if ($booking->working_status == 0)
                                                    <span class="badge badge-primary">@lang('Pending')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->updated_at) }}
                                                @elseif($booking->working_status == 1)
                                                    <span class="badge badge-success">@lang('Completed')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->updated_at) }}
                                                @elseif($booking->working_status == 2)
                                                    <span class="badge badge-secondary">@lang('Delivered')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->updated_at) }}
                                                @elseif($booking->working_status == 3)
                                                    <span class="badge badge-danger">@lang('Cancel')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->updated_at) }}
                                                @elseif($booking->working_status == 4)
                                                    <span class="badge badge-info">@lang('In Progress')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->updated_at) }}
                                                @elseif($booking->working_status == 5)
                                                    <span class="badge badge-danger">@lang('Delivery Expired')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->updated_at) }}
                                                @elseif($booking->working_status == 6)
                                                    <span class="badge badge-warning">@lang('Dispute')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->updated_at) }}
                                                @endif
                                            </td>
                                            <td style="width:10%;">
                                                @if ($booking->status == 1)
                                                    <span class="badge badge-success">@lang('Running')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->status_updated_at) }}
                                                @elseif($booking->status == 2)
                                                    <span class="badge badge-warning">@lang('Payable Both')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->status_updated_at) }}
                                                @elseif($booking->status == 3)
                                                    <span class="badge badge-success">@lang('Paid')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->status_updated_at) }}
                                                @elseif($booking->status == 4)
                                                    <span class="badge badge-info">@lang('Refund')</span>
                                                    <br>
                                                    {{ diffforhumans($booking->status_updated_at) }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dash-action-98563">
                                                    <a href="{{ route('user.seller.job.list.details', encrypt($booking->id)) }}"
                                                        class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                            class="lni lni-eye"></i></a>
                                                    @if ($booking->working_status == 0)
                                                        <a data-toggle="modal" data-target="#approvedModal"
                                                            href="javascript:void(0);"
                                                            data-order_number="{{ $booking->id }}"
                                                            class="approvedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i
                                                                class="fa fa-check"></i></a>
                                                        <a data-toggle="modal" data-target="#cancelModal"
                                                            href="javascript:void(0);"
                                                            data-order_number="{{ $booking->id }}"
                                                            class="cancelBtn p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center"><i
                                                                class="lni lni-trash"></i></a>
                                                    @elseif($booking->working_status == 4 || $booking->working_status == 2)
                                                        <a data-toggle="modal" data-target="#workModal"
                                                            href="javascript:void(0);"
                                                            data-order_number="{{ $booking->id }}"
                                                            class="workBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i
                                                                class="fa fa-truck-loading"></i></a>
                                                    @endif
                                                </div>
                                            </td>
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
        <script>
            "use strict";
            $('.workBtn').on('click', function() {
                var modal = $('#workModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
        </script>
    @endsection
    <div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Work Delivery')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('user.jobwork.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="jobfile">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Upload Work File')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file" required="">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>


                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Delivery Details ...."
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success"
                                style="width:100%;">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-rounded text-white"
                        data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="mb-4 tbl-lg rounded overflow-hidden">
                <div class="table-responsive bg-white">
                    <table class="table text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-left">@lang('Job Title')</th>
                                <th scope="col" class="text-left">@lang('Job Type')</th>

                                <th scope="col" class="text-left">@lang('Date')</th>
                                <th scope="col" class="text-center">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($jobVacancys as $booking)
<tr>
                                <td class="text-left"><a style="color:blue;text-decoration: underline;" href="{{ route('job.details', [slug(@$booking->job->title), encrypt(@$booking->job->id)]) }}">{{ __(str_limit(@$booking->job->title, 20)) }} </a></td>
                                @if (@$booking->job->budgettype == 'fixed')
<td class="text-left">Fixed</td>
@elseif(@$booking->job->budgettype == 'per_hour')
<td class="text-left">Per Hour</td>
@endif

                                <td class="text-left">
                                    {{ showDateTime(@$booking->job->created_at, 'd M Y') }}
                                </td>
                                <td >
                                    <div class="dash-action-98563">
                                        <a title="Bidding Detail" href="{{ route('user.job.vacancy.bids', encrypt(@$booking->job->id)) }}" class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="lni lni-eye"></i></a>
                                        @if ($booking->job->show_completed == '1')
<a title="Add to completed" href="javascript:void(0);" data-id="{{ $booking->id }}" data-toggle="modal" data-target="#completedModal" class="completedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center ml-1"><i class="fa fa-check"></i></a>
@endif

                                    </div>
                                </td>
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
    </div> -->
