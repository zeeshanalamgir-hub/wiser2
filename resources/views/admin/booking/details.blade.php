@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-xl-4 col-md-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-10 text-muted">@lang('Service Details')</h5>
                <h6 class="mb-20 text-muted"><a href="{{route('admin.service.details', $booking->service_id)}}">{{__(@$booking->service->title)}}</a></h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Date')
                        <span class="font-weight-bold">{{ showDateTime($booking->created_at) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Order Number')
                        <span class="font-weight-bold">{{ $booking->order_number }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Seller')
                        <span class="font-weight-bold">
                            <a href="{{ route('admin.users.detail', $booking->seller_id) }}">{{ @$booking->seller->username }}</a>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Buyer')
                        <span class="font-weight-bold">
                            <a href="{{ route('admin.users.detail', ['id'=>$booking?->buyer?->id]) }}">{{ @$booking?->buyer?->username }}</a>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Delivery Date')
                        <span class="font-weight-bold">{{showDateTime($booking->created_at->addDays($booking->service->delivery_time), 'd M Y')}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Service Price')
                        <span class="font-weight-bold">{{getAmount($booking->service_price)}} {{$general->cur_text}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Quantity')
                        <span class="font-weight-bold">{{__($booking->qty)}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Extra Service')
                        <span class="font-weight-bold">{{showAmount(@$add_ser->sum('amount'))}} {{$general->cur_text}}</span>
                    </li>
                
                    @if($booking->discount != 0)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Discount')
                        <span class="font-weight-bold">{{showAmount($booking->discount)}} {{__($general->cur_text)}}</span>
                    </li>
                    @endif
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Agreed Dispute Amount')
                        @if($booking->agreed_amount > 0)
                        <span class="font-weight-bold">{{showAmount($booking->agreed_amount)}} {{__($general->cur_text)}}</span>
                        @else
                        <span class="font-weight-bold">0 {{__($general->cur_text)}}</span>
                        @endif
                    </li>
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Amount')
                        <span class="font-weight-bold">{{(@$booking->service_price*@$booking->qty)+@$add_ser->sum('amount')-@$booking->agreed_amount}} {{__($general->cur_text)}}</span>
                    </li>
                    <!--<li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Working Status')
                        @if($booking->working_status == 0)
                        <span class="badge badge--primary">@lang('Pending')</span>
                        @elseif($booking->working_status == 1)
                        <span class="badge badge--success">@lang('Completed')</span>
                        @elseif($booking->working_status == 2)
                        <span class="badge badge--secondary">@lang('Delivered')</span>
                        @elseif($booking->working_status == 3)
                        <span class="badge badge--danger">@lang('Cancel')</span>
                        @elseif($booking->working_status == 4)
                        <span class="badge badge--dark">@lang('In Progress')</span>
                        @elseif($booking->working_status == 5)
                        <span class="badge badge--danger">@lang('Expired')</span>
                        @elseif($booking->working_status == 6)
                        <div>
                            <span class="badge badge--warning">@lang('Dispute')</span>
                            <button class="btn-info btn-rounded text-white  badge disputeShow" data-dispute="{{$booking->dispute_report}}"><i class="fa fa-info"></i></button>
                        </div>
                        @endif
                    </li>-->
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        <!-- @if($booking->status == 1)
                        <span class="badge badge--primary">@lang('Running')</span>
                        @elseif($booking->status == 2)
                        <span class="badge badge--warning">@lang('Payable Both')</span>
                        @elseif($booking->status == 3)
                        <span class="badge badge--success">@lang('Paid')</span>
                        @elseif($booking->status == 4)
                        <span class="badge badge--success">@lang('Refund')</span>
                        @endif -->
                        @if($booking->status == 1)
                        <span class="badge badge--success">@lang('In Pregress')</span>
                        
                        @elseif($booking->status == 2)
                        <span class="badge badge--warning">@lang('Payable Both')</span>
                        
                        @elseif($booking->status == 3)
                        <span class="badge badge--success">@lang('Paid & Delivered')</span>
                        
                        @elseif($booking->status == 4)
                        <span class="badge badge--success">@lang('Refund')</span>
                        
                        @elseif($booking->status == 5)
                        <span class="badge badge--primary">@lang('pending')</span>
                        
                        @elseif($booking->status == 6)
                        <span class="badge badge--success">@lang('completed')</span>
                        
                        @elseif($booking->status == 0)
                        <span class="badge badge--warning">@lang('unpaid')</span>
                        
                        @elseif($booking->status == 8)
                        <span class="badge badge--danger">@lang('in Dispute')</span>
                        
                        @elseif($booking->status == 7)
                        <span class="badge badge--danger">@lang('dispute send to admin')</span>
                        
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-md-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 text-right">@lang('Other Information')</h5>
                <div class="row mt-2 text-center">
                    @if($add_ser_count > 0)
                    <div class="col-md-12">
                        <h5 class="card-title">@lang('Extra Service')</h5>
                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                    <tr>
                                        <th scope="col">@lang('Title')</th>
                                        <th scope="col">@lang('Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    @foreach($add_ser as $a)
                                    <tr>
                                        <td data-label="@lang('Title')">{{$a->title}}</td>
                                        <td data-label="@lang('Price')"><span class="font-weight-bold">{{showAmount(@$a->amount)}} {{$general->cur_text}}</span></td>
                                    </tr>
                                    @endforeach
                                    <thead>
                                        <tr>
                                            <th scope="col">@lang('total')</th>
                                            <th scope="col">{{showAmount(@$add_ser->sum('amount'))}} {{$general->cur_text}}</th>
                                        </tr>
                                    </thead>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12 mt-3">
                        <h5 class="card-title">@lang('Delivery Details')</h5>
                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                    <tr>
                                        <th scope="col">@lang('Title')</th>
                                        <th scope="col">@lang('Details')</th>
                                        <th scope="col">@lang('Download work file')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach($allworks as $value)
                                    <tr>
                                        <td data-label="@lang('Title')"><span class="font-weight-bold">{{$value->title}}</span></td>
                                        <td data-label="@lang('Price')">{{$value->details}}</td>
                                        <td>
                                            <a href="{{route('admin.work.delivery.download', encrypt($value->id))}}" class="icon-btn btn--primary"><i class="las la-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <thead>
                                        
                                    </thead>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    

                    {{-- <div class="col-md-12 mt-2">
                        @foreach($allworks as $value)
                        <div class="card-title text-center font-weight-bold">
                            <a href="{{route('admin.work.delivery.download', encrypt($value->id))}}" class="icon-btn btn--primary"><i class="las la-download"></i>
                            </a>
                        </div>
                        <div  class="card-title text-center">details</div>
                        <p class="card-text">{{__($value->details)}}</p>
                        @endforeach
                    </div>--}}
                </div>
            </div>
        </div>
        @php
          @$dispute_data = DB::table('service_dispute_data')->where('service_id',$booking->service_id)->where('order_number',$booking->order_number)->first();
        
         $dispute_data_count = DB::table('service_dispute_data')->where('service_id',$booking->service_id)->where('order_number',$booking->order_number)->count();
         $dispute_offers = DB::table('service_dispute_offer')->where('dispute_id',@$dispute_data->id)->get();
         
        @endphp
                                    @if($booking->status == '8' && @$dispute_data_count > 0)
         <div class="card b-radius--10 overflow-hidden box--shadow1 mt-30">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 text-right">@lang('Dispute Information')</h5>
                <div class="row mt-2 text-center">
                    
                    <div class="col-md-12 mt-3"> 
                        <div class="row">
                            <div class="col-lg-6 text-left">
                                <h5 class="card-title">@lang('Dispute offers') </h5>
                            </div>
                            <div class="col-lg-6 text-right">
                                <a href="javascript:void(0)" data-dispute="{{@$dispute_data->id}}" class="btn btn-sm btn--primary box--shadow1 text--small addCategory">Admin Decision</a>
                            </div> 
                        </div>
                        
                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                    <tr>
                                        <th scope="col">@lang('Buyer Amount')</th>
                                        <th scope="col">@lang('Seller Amount')</th>
                                        <th scope="col">@lang('Sent By')</th>
                                        <th scope="col">@lang('Message')</th>
                                         <th style="text-align: center;" scope="col">@lang('Status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                     @foreach($dispute_offers as $data)
                                    <tr>
                                        <td >$ {{@$data->buyer_amount}}</td>
                                        <td >$ {{@$data->seller_amount}}</td>
                                        <td>
                                             @if(@$data->send_by == 'buyer')
                                             Buyer
                                             @elseif(@$data->send_by == 'seller')
                                             Seller
                                             @endif
                                        </td> 
                                        <td>
                                            @if(@$data->message == '')
                                            empty Message
                                            @else
                                            {{$data->message}}
                                            @endif

                                        </td>
                                         <td >
                                              @if(@$data->send_by == 'buyer')
                                            
                                             @if($data->status == 0)
                            <span style="font-size: 10px;" class="badge badge-warning">@lang('offer sent by buyer')</span>
                            @elseif($data->status == 1)
                            <span style="font-size: 10px;" class="badge badge-success">@lang('offer accepted by seller')</span>
                            @elseif($data->status == 3)
                            <span style="font-size: 10px;" class="badge badge-danger">@lang('offer declined by seller')</span>
                            
                            @endif
                                             @elseif(@$data->send_by == 'seller')
                                            
                                              @if($data->status == 0)
                            <span style="font-size: 10px;" class="badge badge-warning">@lang('offer sent by seller')</span>
                            @elseif($data->status == 1)
                            <span style="font-size: 10px;" class="badge badge-success">@lang('offer accepted by buyer')</span>
                            @elseif($data->status == 3)
                            <span style="font-size: 10px;" class="badge badge-danger">@lang('offer declined by buyer')</span>
                            
                            @endif
                                             @endif
                                         </td>

                                    </tr>
                                    @endforeach
                                    <thead>
                                        
                                    </thead>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    

                    {{-- <div class="col-md-12 mt-2">
                        @foreach($allworks as $value)
                        <div class="card-title text-center font-weight-bold">
                            <a href="{{route('admin.work.delivery.download', encrypt($value->id))}}" class="icon-btn btn--primary"><i class="las la-download"></i>
                            </a>
                        </div>
                        <div  class="card-title text-center">details</div>
                        <p class="card-text">{{__($value->details)}}</p>
                        @endforeach
                    </div>--}}
                </div>
            </div>
        </div>
        @endif
    </div>


</div>

<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Seller Payment')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.booking.service.payment')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="payment" value="seller">
                <div class="modal-body">
                    <p>@lang('Are you sure to pay this service seller?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
 <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Admin Decision')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.service.dispute.admindecision') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h6>Disputed Amount: $ {{number_format(@$dispute_data->disputed_amount,2)}}</h6>
                        <input type="hidden" name="dispute_id">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Seller Amount')</label>
                            <input type="text" class="form-control form-control-lg" name="seller_amount" placeholder="@lang("Seller Amount")"  maxlength="50" required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Buyer Amount')</label>
                            <input type="text" class="form-control form-control-lg" name="buyer_amount" placeholder="@lang("Buyer Amount")"  maxlength="50" required="">
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Reason')</label>
                            <textarea class="form-control form-control-lg" name="reason" placeholder="@lang("Reason")"  required=""></textarea>
                        </div>
                         
                        <!-- <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="status">
                        </div> -->

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<div id="refundModel" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Refund Money')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.booking.service.payment')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="payment" value="buyer">
                <div class="modal-body">
                    <p>@lang('Are you sure to refund money to this buyer?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Dispute Report')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dispute-detail">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
@if($booking->status == 2)
<button class="btn btn--success ml-1 approveBtn"
data-toggle="tooltip" data-id="{{$booking->id}}" data-original-title="@lang('Seller Payment')"><i class="fas fa-ban"></i>
@lang('Seller Payment')
</button>
<button class="btn btn--info ml-1 refundBtn"
data-toggle="tooltip" data-id="{{$booking->id}}" data-original-title="@lang('Buyer Payment')"><i class="fas fa-ban"></i>
@lang('Buyer Payment')
</button>
@endif
@endpush
@push('script')
<script>
"use strict";
$('.approveBtn').on('click', function () {
var modal = $('#approveModal');
modal.find('input[name=id]').val($(this).data('id'));
modal.modal('show');
});
$('.refundBtn').on('click', function () {
var modal = $('#refundModel');
modal.find('input[name=id]').val($(this).data('id'));
modal.modal('show');
});
$('.disputeShow').on('click', function () {
var modal = $('#disputeModalShow');
var feedback = $(this).data('dispute');
modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
modal.modal('show');
});
$('.addCategory').on('click', function() {
var modal = $('#addModal');
modal.find('input[name=dispute_id]').val($(this).data('dispute'));
$('#addModal').modal('show');
});
</script>
@endpush