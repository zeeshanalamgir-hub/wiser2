@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
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

        <style type="text/css">
            .btn {
                padding: 7px;
            }
        </style>
        <div class="dashboard-widg-bar d-block ">
            <div class="row">
                <div class="col-lg-12 pb-3">

                    <a href="{{ route('user.buyer.hire.employ') }}"
                        class="btn rounded theme-bg text-light fs-sm ft-medium ">Open jobs</a>
                    <a href="{{ route('user.buyer.hire.employ.offers') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Offers</a>
                    <a href="{{ route('user.buyer.hire.employ.workspace') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Workspace</a>
                    <a href="{{ route('user.buyer.hire.employ.completed') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Completed</a>
                    <a href="{{ route('user.buyer.hire.employ.closed') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Closed</a>
                    <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Chat with
                        seller</a>
                </div>

            </div>


            <div class="workboxmain">
                <div class="row">
                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                <h5>Title:Admin servicers required from an expert</h5>
                                <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp
                                    &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals:
                                        1</span> &nbsp &nbsp <span class="font-weight-normal"><i
                                            class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span
                                        style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp
                                    &nbsp <span style="font-size: 10px;"
                                        class="badge badge-warning">@lang('Running')</span></p>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">


                                <a href="#"
                                    class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2"><i
                                        class="fa fa-eye"></i> </a>
                                <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2"><i
                                        class="fa fa-edit"></i> </a>
                                <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2"><i
                                        class="fa fa-ban"></i> </a>


                            </div>
                        </div>


                    </div>


                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                <h5>Title:Admin servicers required from an expert</h5>
                                <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp
                                    &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals:
                                        1</span> &nbsp &nbsp <span class="font-weight-normal"><i
                                            class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span
                                        style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp
                                    &nbsp <span style="font-size: 10px;"
                                        class="badge badge-warning">@lang('Running')</span></p>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">


                                <a href="#"
                                    class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2"><i
                                        class="fa fa-eye"></i> </a>
                                <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2"><i
                                        class="fa fa-edit"></i> </a>
                                <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2"><i
                                        class="fa fa-ban"></i> </a>


                            </div>
                        </div>


                    </div>


                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                <h5>Title:Admin servicers required from an expert</h5>
                                <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp
                                    &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals:
                                        1</span> &nbsp &nbsp <span class="font-weight-normal"><i
                                            class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span
                                        style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp
                                    &nbsp <span style="font-size: 10px;"
                                        class="badge badge-warning">@lang('Running')</span></p>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">


                                <a href="#"
                                    class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2"><i
                                        class="fa fa-eye"></i> </a>
                                <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2"><i
                                        class="fa fa-edit"></i> </a>
                                <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2"><i
                                        class="fa fa-ban"></i> </a>


                            </div>
                        </div>


                    </div>




                </div>
            </div>
        </div>
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
    @endsection
