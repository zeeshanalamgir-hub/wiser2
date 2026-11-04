@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout')
@section('content')
    <div class="modal fade" id="completedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Job Completed Confirmation')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.jobtwo.completed.seller') }}">
                        @csrf
                        <input type="hidden" name="id">
                        <p>@lang('Are you sure to add this job to completed')</p>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
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
                                <input class="form-control form-control-lg" name="file" id="formFileLg" type="file"
                                    required="">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Delivery Details ...." required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" style="width:100%;">@lang('Submit')</button>
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
    <div class="dashboard-content">
        <div class="container-header-table-100095">
            @include('templates.basic.user.seller.components.buyer-hire-employees-top-buttons', [
                'activeTab' => 'offerRecived',
            ])
            <div class="table-container-985632">
                <div class="table-heading-10002">
                    <div class="table-heding-top-78563">Offer Recived</div>
                </div>
                <table class="table row-border">
                    <thead class="table-head-10004">
                        <tr>
                            <th class="table-head-col-10005">
                                @lang('Job Title')</th>
                            <th class="table-head-col-10005">
                                @lang('Job Type')</th>
                            <th class="table-head-col-10005">
                                @lang('Date')</th>
                            <th class="table-head-col-10005">
                                @lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobVacancys as $booking)
                            <tr>
                                <td class="text-left"><a style="color:blue;text-decoration: underline;"
                                        href="{{ route('job.details', [slug(@$booking->job->title), encrypt(@$booking->job->id)]) }}">{{ __(str_limit(@$booking->job->title, 20)) }}
                                    </a></td>
                                @if (@$booking->job->budgettype == 'fixed')
                                    <td class="text-left">Fixed</td>
                                @elseif(@$booking->job->budgettype == 'per_hour')
                                    <td class="text-left">Per Hour</td>
                                @endif

                                <td class="text-left">
                                    {{ showDateTime(@$booking->job->created_at, 'd M Y') }}
                                </td>
                                <td class="text-left">
                                    <div class="dash-action-98563">
                                        <a title="Offer Detail"
                                            href="{{ route('user.job.vacancy.offers.detail', encrypt($booking->job->id)) }}"
                                            class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                class="lni lni-eye"></i></a>
                                        @if ($booking->job->show_completed == '1')
                                            <a title="Add to completed" href="javascript:void(0);"
                                                data-id="{{ $booking->id }}" data-toggle="modal"
                                                data-target="#completedModal"
                                                class="completedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center ml-1"><i
                                                    class="fa fa-check"></i></a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
        $('.completedBtn').on('click', function() {
            var modal = $('#completedModal');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection
