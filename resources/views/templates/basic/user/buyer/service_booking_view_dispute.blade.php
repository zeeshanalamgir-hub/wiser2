@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <style type="text/css">
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            font-size: 1.5em;
            justify-content: left;
            text-align: center;
            width: 5em;
            margin-top: -10px;
        }

        .star-rating .stars {
            display: none !important;
            c
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }

        .star-rating :checked~label {
            color: #f90;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #fc0;
        }
    </style>
    <div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Send Dispute to Admin')</h2>
                    </div>
                    <form method="POST" action="{{route('user.buyer.dispute.offer.servie.sendadmin')}}">
                        @csrf
                        <input type="hidden" name="dispute_id">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to send dispute to admin?')
                        <div class="form-group">
                            <label>Reason* <small class="text-danger">(max 100 characters)</small></label>
                            <textarea class="form-control mb-2" maxlength="100" placeholder="Reason" name="reason"
                                required></textarea>
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
    <div class="modal fade" id="reqmsacceptModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Accept Offer')</h2>
                    </div>
                    <form method="POST" action="{{route('user.buyer.dispute.offer.servie.accept')}}">
                        @csrf
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to accept this offer?')
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
    <div class="modal fade" id="reqmsrejectModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Decline offer')</h2>
                    </div>
                    <form method="POST" action="{{route('user.buyer.dispute.offer.servie.decline')}}">
                        @csrf
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to decline this offer?')
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
    <div class="modal fade" id="offerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Send Offer')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('user.buyer.dispute.offer.servie.new')}}">
                    @csrf
                    <input type="hidden" name="dispute_id">


                    <div class="modal-body">


                        <div class="row ">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Employer amount</label>
                                <input type="number" id="totalmulti" name="buyer_amount" class="form-control" required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Freelancer amount</label>
                                <input type="number" id="totalmulti" name="seller_amount" class="form-control" required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Seller Message</label>
                                <textarea class="form-control" required="" name="seller_message" rows="5"></textarea>
                            </div>

                        </div>


                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn theme-bg text-light apply-btn rounded ">@lang('Send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="dashboard-content">
        <div class="dashboard-tlbar d-block mb-3">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="{{route('user.buyer.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item text-muted"><a href="{{route('user.buyer.service.booked')}}">Service
                                    Booked</a></li>
                            <li class="breadcrumb-item text-muted"><a
                                    href="{{route('user.buyer.service.booked.details.workprogress', encrypt($booking->id))}}">Work
                                    in progress</a></li>
                            <li class="breadcrumb-item">View Dispute</li>
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
                <div class="col-lg-12 pb-3" style="border-bottom: 1px dashed #eceff3;">
                    <a href="{{route('user.buyer.service.booked.details', encrypt($booking->id))}}"
                        class="btn rounded theme-bg theme-cl fs-sm ft-medium">Service Detail</a>
                    @if($booking->status == '5' || $booking->status == '4')
                    @else
                        <a href="{{route('user.buyer.service.booked.details.contract', encrypt($booking->id))}}"
                            class="btn rounded theme-bg theme-cl fs-sm ft-medium ml-2">Contract</a>
                        <a href="{{route('user.buyer.service.booked.details.workprogress', encrypt($booking->id))}}"
                            class="btn rounded theme-bg theme-cl fs-sm ft-medium ml-2">In progress</a>
                        <a href="{{route('user.buyer.service.booked.details.workcompleted', encrypt($booking->id))}}"
                            class="btn rounded theme-bg theme-cl fs-sm ft-medium ml-2">Invoice</a>
                        <a href="{{route('user.buyer.service.booked.details.workcompleted1', encrypt($booking->id))}}"
                            class="btn rounded theme-bg theme-cl fs-sm ft-medium ml-2">Completed</a>
                        <a href="#" class="btn rounded theme-bg theme-cl fs-sm ft-medium ml-2">Chat with seller</a>
                    @endif
                </div>

            </div>
            {{-- <div class="workboxmain">
                <div class="row">
                    <div class="col-lg-12 topboxwork">
                        <a data-toggle="modal" data-target="#workModal" href="javascript:void(0);"
                            data-order_number="{{$booking->order_number}}"
                            class="workBtn btn rounded bg-white border fs-sm ft-medium disputeBtn"
                            data-order_number="{{$booking->order_number}}" data-toggle="modal"
                            data-target="#disputeModal">@lang('Dispute')</a>
                    </div>
                    <!-- work one -->
                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <h5>Delivery One <span style="font-size: 10px;"
                                        class="badge badge-danger">@lang('Canceled')</span></h5>
                                <p><span class="font-weight-bold">Submission Date:</span>5 January 2022</p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right pt-1">

                                <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2"><i
                                        class="fa fa-arrow-down"></i> &nbsp Responce Detail</a>
                                <a href="#" class="btn btn-sm rounded theme-bg text-light ft-medium "><i
                                        class="fa fa-file"></i> &nbsp Attachment</a>


                            </div>
                        </div>
                        <div class="row showresponce " style="display:none;" id="target">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <p><span class="font-weight-bold"> Title:</span>Rejecting Delivery One</p>
                                <p><span class="font-weight-bold"> Date:</span>10 January 2022</p>
                                <p><span class="font-weight-bold">Reason:</span>Lorem ipsum dolor sit amet, consectetur
                                    adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl,
                                    vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt tristique risus
                                    ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est. Quisque mattis
                                    venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac nec magna. Nulla
                                    nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse consequat, dui non
                                    congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit amet mauris. Aenean
                                    consectetur sem eu lectus egestas tincidunt. Proin augue tellus, venenatis ac tempus
                                    nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida sit amet,
                                    condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales pellentesque
                                    risus. Nullam sit amet nisi eu neque feugiat finibus.</p>
                                <a href="#" class="btn btn-sm rounded theme-bg text-light ft-medium "><i
                                        class="fa fa-file"></i> &nbsp Attachment</a>
                            </div>


                        </div>
                    </div>
                    <!-- work two -->
                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <h5>Delivery Two <span style="font-size: 10px;"
                                        class="badge badge-success">@lang('Approved')</span></h5>
                                <p><span class="font-weight-bold">Submission Date:</span>15 January 2022</p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right pt-1">
                                <a href="#" class="toggle2 btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2"><i
                                        class="fa fa-arrow-down"></i> &nbsp Responce Detail</a>
                                <a href="#" class="btn btn-sm rounded theme-bg text-light ft-medium "><i
                                        class="fa fa-file"></i> &nbsp Attachment</a>
                            </div>
                        </div>
                        <div class="row showresponce " style="display:none;" id="target2">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <p><span class="font-weight-bold"> Title:</span>Accepting Delivery Two</p>
                                <p><span class="font-weight-bold"> Date:</span>20 January 2022</p>
                                <p><span class="font-weight-bold">Reason:</span>Lorem ipsum dolor sit amet, consectetur
                                    adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl,
                                    vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt tristique risus
                                    ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est. Quisque mattis
                                    venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac nec magna. Nulla
                                    nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse consequat, dui non
                                    congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit amet mauris. Aenean
                                    consectetur sem eu lectus egestas tincidunt. Proin augue tellus, venenatis ac tempus
                                    nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida sit amet,
                                    condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales pellentesque
                                    risus. Nullam sit amet nisi eu neque feugiat finibus.</p>
                                <a href="#" class="btn btn-sm rounded theme-bg text-light ft-medium "><i
                                        class="fa fa-file"></i> &nbsp Attachment</a>
                            </div>


                        </div>
                    </div>
                    <!-- work two -->
                    <!-- work two -->
                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <h5>Delivery two</h5>
                                <p><span class="font-weight-bold">Submission Date:</span>25 January 2022</p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right pt-1">
                                <a href="#" class="btn btn-sm rounded theme-bg text-light ft-medium mr-2"><i
                                        class="fa fa-file"></i> &nbsp Attachment</a>
                                <a data-order_number="{{$booking->order_number}}" data-toggle="modal"
                                    data-target="#approvedModal"
                                    class="approvedBtn btn btn-sm rounded theme-bg-light theme-cl ft-medium mr-2"><i
                                        class="fa fa-check"></i> </a>
                                <a data-order_number="{{$booking->order_number}}" data-toggle="modal"
                                    data-target="#cancelModal"
                                    class="cancelBtn btn btn-sm rounded bg-danger text-white ft-medium "><i
                                        class="fa fa-times"></i> </a>
                            </div>
                        </div>
                        <div class="row showresponce " style="display:none;" id="target2">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <p><span class="font-weight-bold">Responce Title:</span>Accepting Delivery Two</p>
                                <p><span class="font-weight-bold">Response Date:</span>20 January 2022</p>
                                <p><span class="font-weight-bold">Response Detail:</span>Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum
                                    dui nisl, vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt
                                    tristique risus ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est.
                                    Quisque mattis venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac
                                    nec magna. Nulla nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse
                                    consequat, dui non congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit
                                    amet mauris. Aenean consectetur sem eu lectus egestas tincidunt. Proin augue tellus,
                                    venenatis ac tempus nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida
                                    sit amet, condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales
                                    pellentesque risus. Nullam sit amet nisi eu neque feugiat finibus.</p>
                                <a href="#" class="btn btn-sm rounded theme-bg text-light ft-medium "><i
                                        class="fa fa-file"></i> &nbsp Attachment</a>
                            </div>


                        </div>
                    </div>
                    <!-- work two -->

                </div>
            </div>--}}
            <div class="row mb-2">
                @if($dispute_data->status == 1 || $dispute_data->status == 2)
                    @if($dispute_data->status == 1)
                        <!--  <span style="font-size: 10px;" class="badge badge-success">@lang('dispute closed')</span> -->
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="alert alert-success">Dispute closed.</div>
                        </div>
                    @elseif($dispute_data->status == 2)
                        <!-- <span style="font-size: 10px;" class="badge badge-primary">@lang('dispute submitted to admin')</span> -->
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="alert alert-danger">Dispute submitted to admin.</div>
                        </div>
                    @endif
                @endif
            </div>
            <div class="workboxmain">
                <div class="row">
                    <div class="col-lg-12 topboxwork">


                        <a href="javascript:void(0);" title="dispute info"
                            class="px-2 py-1 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center mr-1 disputeinfoBtn"
                            data-toggle="modal" data-target="#disputeinfoModal"><i class="fa fa-info"></i></a>


                    </div>
                    @if($dispute_offers->count() > 0)
                        @foreach($dispute_offers as $data)
                            <div class="col-lg-12 innerbox">

                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-12 col-12">
                                        @if(@$data->send_by == 'buyer')
                                            <p><span class="font-weight-bold">Buyer Offer:</span> {{@$data->message}}</p>

                                        @elseif(@$data->send_by == 'seller')
                                            <p><span class="font-weight-bold">Seller Offer:</span> {{@$data->message}}</p>

                                        @endif
                                        <p><span class="font-weight-normal"> Freelancer: <span
                                                    class="font-weight-bold"><span>$</span>{{@$data->seller_amount}}</span></span>
                                            &nbsp &nbsp <span class="font-weight-normal"> Employer: <span
                                                    class="font-weight-bold"><span>$</span>{{@$data->buyer_amount}}</span></span>
                                        </p>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right" style="padding-top: 12px;">
                                        @if($data->send_by == 'buyer')


                                            @if($data->status == 0)
                                                <span style="font-size: 10px;"
                                                    class="badge badge-warning">@lang('offer sent by buyer')</span>
                                            @elseif($data->status == 1)
                                                <span style="font-size: 10px;"
                                                    class="badge badge-success">@lang('offer accepted by seller')</span>
                                            @elseif($data->status == 3)
                                                <span style="font-size: 10px;"
                                                    class="badge badge-danger">@lang('Dispute offer declined by seller')</span>

                                            @endif
                                        @elseif($data->send_by == 'seller')
                                            @if($data->status == 0)
                                                @if($dispute_data->status == 1 || $dispute_data->status == 2)
                                                    @if($dispute_data->status == 1)
                                                        <span style="font-size: 10px;" class="badge badge-success">@lang('dispute closed')</span>
                                                    @elseif($dispute_data->status == 2)
                                                        <span style="font-size: 10px;"
                                                            class="badge badge-primary">@lang('dispute submitted to admin')</span>
                                                    @endif
                                                @else
                                                    <a data-target="#reqmsacceptModal" data-work_id="{{@$data->id}}" href="javascript:void(0);"
                                                        class="reqmsacceptBtn btn rounded theme-bg text-light  fs-sm ft-medium mr-2">Accept </a>
                                                    <a data-target="#reqmsrejectModal" data-work_id="{{@$data->id}}" href="javascript:void(0);"
                                                        class="reqmsrejectBtn btn rounded bg-danger text-light theme-cl fs-sm ft-medium mr-2">Deny
                                                    </a>
                                                @endif
                                            @elseif($data->status == 1)
                                                <span style="font-size: 10px;"
                                                    class="badge badge-success">@lang('offer accepted by buyer')</span>
                                            @elseif($data->status == 3)
                                                <span style="font-size: 10px;"
                                                    class="badge badge-danger">@lang('offer rejected by buyer')</span>
                                            @endif
                                        @endif


                                    </div>
                                </div>


                            </div>
                        @endforeach
                    @else

                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6>No data found</h6>
                        </div>

                    @endif
                </div>
            </div>
            @if($dispute_data->status == 1 || $dispute_data->status == 2 || $show_send > 0)
            @else
                <div class="row mt-3">
                    <div class="col-lg-12 col-md-12">
                        <a href="javascript:void(0);" data-dispute_id="{{@$dispute_data->id}}" data-toggle="modal"
                            data-target="#offerModal"
                            class="offerBtn btn btn-md ft-medium text-light rounded theme-bg">@lang('SEND OFFER')</a>
                        <a href="javascript:void(0);" data-dispute_id="{{@$dispute_data->id}}" data-toggle="modal"
                            data-target="#adminModal"
                            class="adminBtn btn btn-md ft-medium text-light rounded theme-bg">@lang('SEND TO ADMIN')</a>

                    </div>
                </div>
            @endif
        </div>
@endsection
    @section('script')
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="ModalLabel">@lang('Reject Work Delivery')</h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{route('user.work.delivery.rejected')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="order_number">
                            <input type="hidden" name="work_id">
                            <input type="hidden" name="work_type" value="service">
                            <div class="form-group">
                                <div>
                                    <label for="formFileLg" class="form-label font-weight-bold">@lang('Upload File')</label>
                                    <input class="form-control form-control-lg" name="file" id="formFileLg" type="file"
                                        required="">
                                    <small>@lang('Supported files:zip and max size:25 Mb')</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea rows="8" class="form-control" name="details"
                                    placeholder="Describe Your Rejection Details ...." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-rounded text-white"
                                data-dismiss="modal">@lang('Close')</button>
                            <button type="submit"
                                class="disabled btn btn-success btn-rounded text-white">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @php
            $add_ser = DB::table('additional_services')->where('booking_id', $booking->id)->get();
        @endphp
        @php
            $sub = ($booking->qty * $booking->service_price);
            $total = ($sub + $add_ser->sum('amount'));
        @endphp
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
                            <h2 class="m-0 ft-regular">@lang('Work Delivery Approval Confirmation')</h2>
                        </div>
                        <form method="POST" action="{{route('user.work.delivery.approved')}}">
                            @csrf
                            <input type="hidden" name="order_number">
                            <input type="hidden" name="work_id">
                            <input type="hidden" name="totalss" value="{{$total}}">
                            <input type="hidden" name="work_type" value="service">
                            <p>@lang('Are you sure to approved this work delivery')</p>
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
        <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl login-pop-form" role="document">
                <div class="modal-content" id="loginmodal">
                    <div class="modal-headers">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ti-close"></span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="m-0 ft-regular">@lang('Payment Confirmation')</h2>
                        </div>
                        <form method="POST" action="{{route('user.work.delivery.approved.payed')}}">
                            @csrf
                            <input type="hidden" name="order_number">
                            <input type="hidden" name="work_id">
                            <input type="hidden" name="totalss" value="{{$total}}">
                            <input type="hidden" name="work_type" value="service">
                            <p>@lang('Are you sure to pay')</p>
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
      
       
        <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                            <h2 class="m-0 ft-regular">@lang('Dispute Report')</h2>
                        </div>
                        <div class="dispute-detail">
                        </div>
                        <div class="form-group">
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
        <div class="modal fade" id="disputeinfoModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                            <h6 class="m-0 ft-regular">@lang('Dispute info')</h6>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
        <script type="text/javascript">
            'use strict';
            $('.offerBtn').on('click', function () {
                var modal = $('#offerModal');
                modal.find('input[name=dispute_id]').val($(this).data('dispute_id'))
                modal.modal('show');
            });
            $('.disputeinfoBtn').on('click', function () {
                var modal = $('#disputeinfoModal');
                modal.modal('show');
            });
            $('.adminBtn').on('click', function () {
                var modal = $('#adminModal');
                modal.find('input[name=dispute_id]').val($(this).data('dispute_id'))
                modal.modal('show');
            });
            $('.reqmsacceptBtn').on('click', function () {
                var modal = $('#reqmsacceptModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.modal('show');
            });
            $('.reqmsrejectBtn').on('click', function () {
                var modal = $('#reqmsrejectModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.modal('show');
            });
        </script>
    @endsection