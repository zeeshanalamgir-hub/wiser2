
@extends($activeTemplate.'layouts.wiserlance.dashboard-layout')
@section('content')
    <style type="text/css">
        .btn{
        padding: 7px;
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
                    <h2 class="m-0 ft-regular">@lang('Dispute submitted to Admin')</h2>
                </div>
                <form method="POST" action="{{route('user.service.sendadmin.dispute.offer')}}">
                    @csrf
                    <input type="hidden" name="dispute_id">
                    <input type="hidden" name="confirm" value="approved">
                    @lang('Are you sure to send dispute  to admin?')
                    <div class="form-group">
                        <label>Reason* <small class="text-danger">(max 100 characters)</small></label>
                        <textarea class="form-control mb-2" maxlength="100" placeholder="Reason" name="reason" required></textarea>
                        <button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                    </div>
                    <div class="form-group text-center mb-0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reqmsacceptModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
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
                <form method="POST" action="{{route('user.service.accept.dispute.offer')}}">
                    @csrf
                    <input type="hidden" name="work_id">
                    <input type="hidden" name="confirm" value="approved">
                    @lang('Are you sure to accept this offer?')
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                    </div>
                    <div class="form-group text-center mb-0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reqmsrejectModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
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
                <form method="POST" action="{{route('user.service.decline.dispute.offer')}}">
                    @csrf
                    <input type="hidden" name="work_id">
                    <input type="hidden" name="confirm" value="approved">
                    @lang('Are you sure to decline this offer?')
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
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
            <form method="POST" action="{{route('user.service.new.dispute.offer')}}">
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
                            <input type="number" id="totalmulti" name="seller_amount"  class="form-control" required>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                            <label> Message</label>
                            <textarea class="form-control" required="" name="message" rows="5"></textarea>
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
                        <li class="breadcrumb-item text-muted"><a href="{{route('user.home')}}">Dashboard</a></li>
                        <li class="breadcrumb-item text-muted"><a href="{{route('user.booking.service')}}">Service Booking</a></li>
                        <li class="breadcrumb-item text-muted"><a href="{{route('user.booking.service.details.workprogress', encrypt($booking->id))}}">Work in progress</a></li>
                        <li class="breadcrumb-item">View Dispute</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="dashboard-widg-bar d-block ">
        <div class="row">
            <div class="col-lg-12 pb-3" style="border-bottom: 1px dashed #eceff3;">
                <a href="{{route('user.booking.service.details', encrypt($booking->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ">Service Detail</a>
                @if($booking->status == '5' || $booking->status == '4' )
                @else
                <a href="{{route('user.booking.service.details.contract', encrypt($booking->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Contract</a>
                <a href="{{route('user.booking.service.details.workprogress', encrypt($booking->id))}}"  class="btn rounded theme-bg text-light  fs-sm ft-medium ml-2"  >In progress</a>
                <a href="{{route('user.booking.service.details.workcompleted', encrypt($booking->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Invoice</a>
                <a href="{{route('user.booking.service.details.workcompleted1', encrypt($booking->id))}}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Completed</a>
                <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Chat with {{$booking?->service?->user?->firstname}} {{$booking?->service?->user?->lastname}}</a>
                @endif
            </div>
        </div>
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


                    <a href="javascript:void(0);" title="dispute info" class="px-2 py-1 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center mr-1 disputeinfoBtn"  data-toggle="modal" data-target="#disputeinfoModal"><i class="fa fa-info"></i></a>


                </div>
                @if($dispute_offers->count() > 0)
                @foreach($dispute_offers as $data)
                <div class="col-lg-12 innerbox">

                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12 col-12">
                            @if(@$data->send_by == 'seller')
                            <p><span class="font-weight-bold">Seller Offer:</span> {{@$data->message}}</p>

                            @elseif(@$data->send_by == 'buyer')
                            <p><span class="font-weight-bold">Buyer Offer:</span> {{@$data->buyer_message}}</p>

                            @endif
                            <p><span class="font-weight-normal"> Freelancer: <span class="font-weight-bold"><span>$</span>{{@$data->seller_amount}}</span></span> &nbsp &nbsp <span class="font-weight-normal"> Employer: <span class="font-weight-bold"><span>$</span>{{@$data->buyer_amount}}</span></span>  </p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right" style="padding-top: 12px;">
                            @if($data->send_by == 'seller')

                            @if($data->status == 0)
                            <span style="font-size: 10px;" class="badge badge-warning">@lang('offer sent by seller')</span>
                            @elseif($data->status == 1)
                            <span style="font-size: 10px;" class="badge badge-success">@lang('offer accepted by buyer')</span>
                            @elseif($data->status == 3)
                            <span style="font-size: 10px;" class="badge badge-danger">@lang('Dispute offer declined by buyer')</span>

                            @endif
                            @elseif($data->send_by == 'buyer')
                            @if($data->status == 0)
                            @if($dispute_data->status == 1 || $dispute_data->status == 2)
                            @if($dispute_data->status == 1)
                            <span style="font-size: 10px;" class="badge badge-success">@lang('dispute closed')</span>
                            @elseif($dispute_data->status == 2)
                            <span style="font-size: 10px;" class="badge badge-primary">@lang('dispute submitted to admin')</span>
                            @endif
                            @else
                            <a data-target="#reqmsacceptModal" data-work_id="{{@$data->id}}" href="javascript:void(0);" class="reqmsacceptBtn btn rounded theme-bg text-light  fs-sm ft-medium mr-2" >Accept </a>
                            <a data-target="#reqmsrejectModal" data-work_id="{{@$data->id}}" href="javascript:void(0);" class="reqmsrejectBtn btn rounded bg-danger text-light theme-cl fs-sm ft-medium mr-2" >Deny </a>
                            @endif
                            @elseif($data->status == 1)
                            <span style="font-size: 10px;" class="badge badge-success">@lang('offer accepted by seller')</span>
                            @elseif($data->status == 3)
                            <span style="font-size: 10px;" class="badge badge-danger">@lang('offer rejected by seller')</span>
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
        @if($dispute_data->status == 1 || $dispute_data->status == 2 ||  $show_send > 0)
        @else
        <div class="row mt-3">
            <div class="col-lg-12 col-md-12">
                <a href="javascript:void(0);" data-dispute_id="{{@$dispute_data->id}}" data-toggle="modal" data-target="#offerModal"  class="offerBtn btn btn-md ft-medium text-light rounded theme-bg">@lang('SEND OFFER')</a>
                <a href="javascript:void(0);" data-dispute_id="{{@$dispute_data->id}}" data-toggle="modal" data-target="#adminModal"  class="adminBtn btn btn-md ft-medium text-light rounded theme-bg">@lang('SEND TO ADMIN')</a>

            </div>
        </div>
        @endif
    </div>

    @endsection
    @section('script')
    <div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Booking Work Delivery')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('user.work.upload')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="service">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Upload Work File')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg" type="file" required="">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text"  class="form-control" name="title" placeholder="Title for delivery" required>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Delivery Details ...." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-white" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success btn-rounded text-white">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="disputeinfoModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
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
    <script>
    'use strict';

    $('.offerBtn').on('click', function() {
    var modal = $('#offerModal');
    modal.find('input[name=dispute_id]').val($(this).data('dispute_id'))
    modal.modal('show');
    });
    $('.disputeinfoBtn').on('click', function() {
    var modal = $('#disputeinfoModal');
    modal.modal('show');
    });
    $('.reqmsacceptBtn').on('click', function() {
    var modal = $('#reqmsacceptModal');
    modal.find('input[name=work_id]').val($(this).data('work_id'))
    modal.modal('show');
    });
    $('.reqmsrejectBtn').on('click', function() {
    var modal = $('#reqmsrejectModal');
    modal.find('input[name=work_id]').val($(this).data('work_id'))
    modal.modal('show');
    });
    $('.adminBtn').on('click', function() {
    var modal = $('#adminModal');
    modal.find('input[name=dispute_id]').val($(this).data('dispute_id'))
    modal.modal('show');
    });
    </script>
    @endsection
