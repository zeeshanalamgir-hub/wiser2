@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
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
                    <form method="POST" action="{{ route('user.job.completed') }}">
                        @csrf
                        <input type="hidden" name="id">
                        <input type="hidden" value="offer" name="type">
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
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Accept Proposal')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.proposal.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to approved this work delivery')
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
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Cancel Proposal')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.proposal.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="confirm" value="cancel">
                        <p>@lang('Are you sure to cancel this Proposal')</p>
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
    <div class="dashboard-content">
        <style type="text/css">
            .btn {
                padding: 7px;
            }
        </style>
        <div class="dashboard-content">
            <div class="container-header-table-100095">
                @include('templates.basic.user.buyer.components.buyer-hire-employees-top-buttons', [
                    'activeTab' => 'offeredJobs',
                ])
                <div class="table-container-985632">
                    <div class="table-heading-10002">
                        <h3>Offered Jobs</h3>
                    </div>
                    <table class="table row-border">
                        <thead class="table-head-10004">
                            <tr>
                                <th>@lang('Job Title')</th>
                                <th>@lang('Job Type')</th>
                                <th>@lang('Offers')</th>
                                <th>@lang('Date')</th>
                                <th class="text-left">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hireEmploys as $hireEmploy)
                                <tr>
                                    <td><a style="color:blue;text-decoration: underline;"
                                            href="{{ route('job.details', [slug($hireEmploy->job->title), encrypt($hireEmploy->job->id)]) }}">{{ __(str_limit($hireEmploy->job->title, 20)) }}
                                        </a></td>
                                    @if ($hireEmploy->job->budgettype == 'fixed')
                                        <td>Fixed</td>
                                    @elseif($hireEmploy->job->budgettype == 'per_hour')
                                        <td>Per Hour</td>
                                    @endif
                                    @php
                                        $bids = DB::table('user_biding')
                                            ->where('job_id', $hireEmploy->job->id)
                                            ->count();
                                        $offers = DB::table('user_offers')
                                            ->where('job_id', $hireEmploy->job->id)
                                            ->count();

                                    @endphp
                                    <td><a style="color:blue;text-decoration: underline;"
                                            href="{{ route('user.buyer.hire.employ.offers', encrypt($hireEmploy->job_id)) }}">{{ $offers }}</a>
                                    </td>

                                    <td>
                                        {{ showDateTime($hireEmploy->job->created_at, 'd M Y') }}
                                    </td>
                                    <td class="text-left">

                                        <div class="dash-action-98563">
                                            <a title="View Offer Detail"
                                                href="{{ route('user.buyer.hire.employ.offers', encrypt($hireEmploy->job_id)) }}"
                                                class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                    class="lni lni-eye"></i></a>
                                            @if ($hireEmploy->job->show_completed == '1')
                                                <a title="Add to completed" href="javascript:void(0);"
                                                    data-id="{{ $hireEmploy->job->id }}" data-toggle="modal"
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

        <!--  <div class="dashboard-widg-bar d-block">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="mb-4 tbl-lg rounded overflow-hidden">
                                            <div class="table-responsive bg-white">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col"></th>
                                                            <th scope="col">@lang('Job')</th>
                                                            <th scope="col">@lang('Employ')</th>
                                                            <th scope="col">@lang('Amount')</th>
                                                            <th scope="col">@lang('Working Status')</th>
                                                            <th scope="col">@lang('Status')</th>
                                                            <th scope="col">@lang('Action')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($hireEmploys as $hireEmploy)
    <tr>
                                                            <td>
                                                                <img src="{{ getImage('assets/images/job/' . $hireEmploy->job->image, '590x300') }}" alt="@lang('Job Image')" class="img-fluid circle" width="50" />
                                                            </td>
                                                            <td>{{ __(str_limit($hireEmploy->job->title, 20)) }}</td>
                                                            <td>{{ __(@$hireEmploy->user->fullname) }} <a href="{{ route('profile', [slug(@$hireEmploy->job->user->username), encrypt(@$hireEmploy->job->user->id)]) }}" >{{ $hireEmploy->job->user->username }}</a></td>
                                                            <td>{{ showAmount($hireEmploy->bid_price) }} {{ __($general->cur_text) }}</td>
                                                            <td>
                                                                @if ($hireEmploy->working_status == 0)
    <span class="badge badge-primary">@lang('Pending')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 1)
    <span class="badge badge-success">@lang('Completed')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 2)
    <span class="badge badge-secondary">@lang('Delivered')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 3)
    <span class="badge badge-danger">@lang('Cancel')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 4)
    <span class="badge badge-info">@lang('In Progress')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 5)
    <span class="badge badge-danger">@lang('Delivery Expired')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 6)
    <span class="badge badge-warning">@lang('Dispute')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->updated_at) }}
    @endif
                                                            </td>
                                                            <td style="width:10%;">
                                                                @if ($hireEmploy->status == 1)
    <span class="badge badge-success">@lang('Running')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 2)
    <span class="badge badge-warning">@lang('Payable Both')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 3)
    <span class="badge badge-success">@lang('Paid')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 4)
    <span class="badge badge-info">@lang('Refund')</span>
                                                                <br>
                                                                {{ diffforhumans($hireEmploy->status_updated_at) }}
    @endif
                                                            </td>
                                                            <td>
                                                                <div class="dash-action-98563">
                                                                    <a href="{{ route('user.buyer.hire.employ.details', encrypt($hireEmploy->id)) }}" class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="lni lni-eye"></i></a>
                                                                    @if ($hireEmploy->working_status == 0)
    <a href="javascript:void(0);" data-order_number="{{ $hireEmploy->id }}" data-order_number="{{ $hireEmploy->id }}" data-toggle="modal" data-target="#approvedModal" class="approvedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i class="lni lni-pencil"></i></a>
                                                                    <a data-toggle="modal" data-target="#cancelModal" href="javascript:void(0);" data-order_number="{{ $hireEmploy->id }}" class="  p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="lni lni-trash-can"></i></a>
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
                            </div> -->
        <!--  <style type="text/css">
                            .btn{
                            padding: 7px;
                            }
                            </style>
                            <div class="dashboard-widg-bar d-block ">
                                <div class="row">
                                    <div class="col-lg-12 pb-3" >


                                    </div>

                                </div>

                                <div class="workboxmain">
                                    <div class="row">
                                        <div class="col-lg-12 innerbox">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                                    <h5>Title:Admin servicers required from an expert</h5>
                                                    <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals: 1</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-warning">@lang('Running')</span></p>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">

                                                    <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a>
                                                    <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2" ><i class="fa fa-edit"></i> </a>
                                                    <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-ban"></i> </a>


                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-12 innerbox">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                                    <h5>Title:Admin servicers required from an expert</h5>
                                                    <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals: 1</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-warning">@lang('Running')</span></p>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">

                                                    <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a>
                                                    <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2" ><i class="fa fa-edit"></i> </a>
                                                    <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-ban"></i> </a>


                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-lg-12 innerbox">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                                    <h5>Title:Admin servicers required from an expert</h5>
                                                    <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals: 1</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-warning">@lang('Running')</span></p>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">

                                                    <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a>
                                                    <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2" ><i class="fa fa-edit"></i> </a>
                                                    <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-ban"></i> </a>


                                                </div>
                                            </div>


                                        </div>



                                    </div>
                                </div>
                            </div> -->
    @endsection
    @section('script')
        <script type="text/javascript">
            "use strict";
            $('.approvedBtn').on('click', function() {
                var modal = $('#approvedModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
            $('.cancelBtn').on('click', function() {
                var modal = $('#cancelModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
        </script>
        <script type="text/javascript">
            'use strict';
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
