@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="modal fade" id="milestoneModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body py-4 py-1">
                    <div class="text-center mb-4">
                        <h6 class="m-0 ft-regular">@lang('Proposal detail')</h6>
                        <input type="" name="order_number">
                    </div>

                    <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                        <tbody>



                            <tr>
                                <td style="width:33%;" scope="row">Milestone 1</td>
                                <td style="width:33%;" scope="row">04 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 2</td>
                                <td style="width:33%;" scope="row">14 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 3</td>
                                <td style="width:33%;" scope="row">24 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <p><span class="font-weight-bold">Cover Letter:</span>Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum
                                    dui nisl, vestibulum in lectus a, lobortis elementum velit. <a href=""><i
                                            class="fa fa-paperclip"></i></a> </p>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
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
        <!-- <div class="dashboard-widg-bar d-block">
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

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <style type="text/css">
            .btn {
                padding: 7px;
            }
        </style>
        <div class="dashboard-widg-bar d-block ">
            <div class="row">
                <div class="col-lg-12 pb-3">

                    <!-- <a href="{{ route('user.buyer.hire.employ') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium " >Open jobs</a> -->
                    <a href="{{ route('user.buyer.hire.employ.bids', encrypt($singlejob->id)) }}"
                        class="btn rounded theme-bg text-light fs-sm ft-medium ">Bids</a>
                    <a href="{{ route('user.buyer.hire.employ.offers', encrypt($singlejob->id)) }}"
                        class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium ml-2">Offers</a>
                    <a href="{{ route('user.buyer.hire.employ.workspace') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Workspace</a>
                    <!-- <a href="{{ route('user.buyer.hire.employ.hourlytimesheet') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Timesheet(Hourly)</a> -->
                    <!-- <a href="{{ route('user.buyer.hire.employ.completed') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Completed</a>
                    <a href="{{ route('user.buyer.hire.employ.closed') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Closed</a> -->
                    <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Chat with
                        seller</a>
                </div>

            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center">@lang('Title')</th>
                                        <th scope="col" class="text-center">@lang('Type')</th>
                                        <th scope="col" class="text-center">@lang('Seller')</th>
                                        <th scope="col" class="text-center">@lang('Due Date')</th>
                                        <th scope="col" class="text-center">@lang('Submitted on')</th>
                                        <th scope="col" class="text-center">@lang('Budget')</th>
                                        <th scope="col" class="text-center">@lang('Bid amount')</th>
                                        <th scope="col" class="text-center">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($singlejobbids as $bid)
                                        @php
                                            $seller = DB::table('users')
                                                ->where('id', $bid->user_id)
                                                ->first();
                                        @endphp
                                        <tr>

                                            <td>{{ $bid->job->title }}</td>
                                            @if ($bid->job->budgettype == 'fixed')
                                                <td>Fixed</td>
                                            @elseif($bid->job->budgettype == 'per_hour')
                                                <td>Per Hour</td>
                                            @endif

                                            <td>{{ $seller->username }} <br>
                                                <a
                                                    href="{{ route('profile', [slug($seller->username), encrypt($seller->id)]) }}">@
                                                    {{ $seller->username }}</a>
                                            </td>
                                            <td>{{ showDateTime($bid->due_date, 'd M Y') }}</td>
                                            <td>
                                                {{ showDateTime($bid->created_at, 'd M Y') }}
                                            </td>
                                            @if ($bid->job->budgettype == 'fixed')
                                                <td>{{ $general->cur_sym }}{{ showAmount($bid->job->amount) }}</td>
                                            @elseif($bid->job->budgettype == 'per_hour')
                                                <td>{{ $general->cur_sym }}{{ showAmount($bid->job->amount) }}/hour</td>
                                            @endif
                                            @if ($bid->job->budgettype == 'fixed')
                                                <td>${{ $bid->bid_price }}</td>
                                            @elseif($bid->job->budgettype == 'per_hour')
                                                <td>${{ $bid->bid_price }}/hour</td>
                                            @endif
                                            <td>
                                                <div class="dash-action-98563">
                                                    <a href="javascript:void(0);" data-order_number="{{ $bid->id }}"
                                                        data-toggle="modal" data-target="#milestoneModal"
                                                        class="milestoneBtn p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                            class="lni lni-eye"></i></a>
                                                    <a data-toggle="modal" data-target="#workModal"
                                                        href="javascript:void(0);"
                                                        class="workBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center mr-1"><i
                                                            class="fa fa-gift"></i></a>
                                                    <a href="javascript:void(0);"
                                                        class=" p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i
                                                            class="lni lni-trash-can"></i></a>


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
            <!-- <div class="workboxmain">
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
            </div> -->
            <!-- <style type="text/css">
            .table-bordered td, .table-bordered th {
            border: 1px solid rgba(0,0,0,.125) !important;
            }
            .table tr th, .table tr td {
            border-color: #eaeff5;
            padding: 12px 15px;
            vertical-align: top;
            }
            #downbtn{
            padding: 1px 13px !important;
            }
            </style>
            <div class="row" >
                <div class="col-lg-12">

                    <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                        <tbody>
                            <tr>

                                <th colspan="3" >Seller Responce <span style="float:right;"><a href="#" id="downbtn" class="toggle btn rounded  fs-sm ft-medium mr-2" ><i class="fa fa-arrow-down"></i> </a></span></th>


                            </tr>


                            <tr>
                                <td style="width:33%;" scope="row">Milestone 1</td>
                                <td style="width:33%;" scope="row">04 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 2</td>
                                <td style="width:33%;" scope="row">14 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 3</td>
                                <td style="width:33%;" scope="row">24 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 100.00</td>

                            </tr>
                            <tr class="showresponce " style="display:none;" id="target">
                                <td colspan="3">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <div class=" rounded px-3 py-4 mb-4">
                                                <div class="jbd-01 d-flex align-items-center justify-content-between">
                                                    <div class="jbd-flex d-flex align-items-center justify-content-start">
                                                        <div class="jbd-01-thumb">

                                                            <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg" class="img-fluid" style="width:80px;height: 80px;border-radius: 50%;" alt="" />

                                                        </div>
                                                        <div class="jbd-01-caption pl-3">
                                                            <div class="tbd-title">
                                                                <a href="#"><h4 class="mb-0 ft-medium fs-md text-capitalize">Zohaib Mustafa </h4></a>
                                                            </div>
                                                            <div class="jbl_location text-capitalize">Web Developer</span> <span class="ml-3">$200/Hour</span></div>
                                                            <div class="jbl_location my-1"><span><i class="lni lni-map-marker mr-1"></i>Pakistan</span></div>
                                                            <div class="jbl_info01">
                                                                <div class="jbl_location "><span><i style="color:gold;" class="fa fa-star mr-1"></i>5</span><span class="ml-3"><i class="fa fa-user mr-1"></i>@lang('Member Since') 02 Jan 2023</span>

                                                                <span class="medium ft-medium text-success ml-3"><i class="fa fa-times mr-1"></i>Verified</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="jbd-01-right text-right ">
                                                    <div class="jbl_button mb-2"><a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium" style="width:150px;">View Profile</a></div>
                                                    <div class="jbl_button mt-3"><span class="ml-3"><i class="fa fa-money mr-1"></i>@lang('Submitted on') 02 Jan 2023</span></div>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                            <p><span class="font-weight-bold">Cover Letter:</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl, vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt tristique risus ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est. Quisque mattis venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac nec magna. Nulla nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse consequat, dui non congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit amet mauris. Aenean consectetur sem eu lectus egestas tincidunt. Proin augue tellus, venenatis ac tempus nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida sit amet, condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales pellentesque risus. Nullam sit amet nisi eu neque feugiat finibus.</p>
                                            <a  data-toggle="modal" data-target="#workModal" href="javascript:void(0);" class="workBtn btn btn-sm rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-gift"></i> &nbsp Send Offer</a>
                                            <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12">

                    <table class="table table-bordered" style="border: 1px solid rgba(0,0,0,.125) !important;">

                        <tbody>
                            <tr>

                                <th colspan="3" >Buyer Offer <span style="float:right;"><a href="#" id="downbtn" class="toggle2 btn rounded  fs-sm ft-medium mr-2" ><i class="fa fa-arrow-down"></i> </a></span></th>


                            </tr>


                            <tr>
                                <td style="width:33%;" scope="row">Milestone 1</td>
                                <td style="width:33%;" scope="row">04 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 80.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 2</td>
                                <td style="width:33%;" scope="row">14 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 80.00</td>

                            </tr>
                            <tr>
                                <td style="width:33%;" scope="row">Milestone 3</td>
                                <td style="width:33%;" scope="row">24 January 2023</td>
                                <td style="width:34%;text-align: right;">$ 80.00</td>

                            </tr>
                            <tr class="showresponce " style="display:none;" id="target2">
                                <td colspan="3">
                                    <div class="row ">



                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                            <p><span class="font-weight-bold">Cover Letter:</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl, vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt tristique risus ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est. Quisque mattis venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac nec magna. Nulla nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse consequat, dui non congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit amet mauris. Aenean consectetur sem eu lectus egestas tincidunt. Proin augue tellus, venenatis ac tempus nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida sit amet, condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales pellentesque risus. Nullam sit amet nisi eu neque feugiat finibus.</p>

                                            <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->
        </div>
    @endsection
    @section('script')
        <div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="ModalLabel">@lang('Send Offer')</h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('user.work.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="order_number">
                            <input type="hidden" name="work_type" value="service">
                            <div class="form-group">
                                <div>
                                    <label for="formFileLg" class="form-label font-weight-bold">@lang('Attachment')</label>
                                    <input class="form-control form-control-lg" name="file" id="formFileLg"
                                        type="file" required="">
                                    <small>@lang('Supported files:zip and max size:25 Mb')</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" name="" class="form-control" name="title"
                                    placeholder="Title " required>
                            </div>
                            <div class="form-group">
                                <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Details ...." required></textarea>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="formFileLg" class="form-label font-weight-bold">@lang('Milestones')</label>
                                    <div class="row">
                                        <div class="col-md-4 pt-3">
                                            Milestone1
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="number" name="" class="form-control" name="title"
                                                    placeholder="$ 32" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="date" name="" class="form-control" name="title"
                                                    placeholder="" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 pt-3">
                                            Milestone2
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="number" name="" class="form-control" name="title"
                                                    placeholder="$ 32" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="date" name="" class="form-control" name="title"
                                                    placeholder="" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 pt-3">
                                            Milestone3
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="number" name="" class="form-control" name="title"
                                                    placeholder="$ 32" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="date" name="" class="form-control" name="title"
                                                    placeholder="" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-rounded text-white"
                                data-dismiss="modal">@lang('Close')</button>
                            <button type="submit"
                                class="btn btn-success btn-rounded text-white">@lang('Send')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            "use strict";
            $('.approvedBtn').on('click', function() {
                var modal = $('#approvedModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
            $('.milestoneBtn').on('click', function() {
                var modal = $('#milestoneModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
            $('.cancelBtn').on('click', function() {
                var modal = $('#cancelModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
            $('.workBtn').on('click', function() {
                var modal = $('#workModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
            $('.toggle').click(function() {
                $('#target').toggle();
            });
            $('.toggle2').click(function() {
                $('#target2').toggle();
            });
        </script>
    @endsection
