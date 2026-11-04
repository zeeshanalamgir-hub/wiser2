@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Value')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td data-label="@lang('Name')"><span class="font-weight-bold">{{__($coupon->name)}}</span></td>
                                    <td data-label="@lang('Code')">
                                        <span class="font-weight-bold">{{$coupon->code}}</span>
                                    </td>
                                    <td data-label="@lang('Type')">
                                        @if($coupon->type == 1)
                                            <span class="badge badge--primary">@lang('Fixed')</span>
                                        @else
                                            <span class="badge badge--success">@lang('Percent')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Value')">
                                        <span class="font-weight-bold">
                                            @if($coupon->type == 1)
                                                {{$general->cur_sym}}{{getAmount($coupon->value)}}
                                            @else
                                                {{getAmount($coupon->value)}} %
                                            @endif
                                        </span>
                                        </td>
                                 
                                    <td data-label="@lang('Status')">
                                        @if($coupon->status == 1)
                                            <span class="badge badge--success">@lang('Enable')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                        @endif
                                    </td>
                                  
                                     <td data-label="@lang('Last Update')">
                                        {{ showDateTime($coupon->updated_at) }} <br> {{ diffForHumans($coupon->updated_at) }}
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" class="icon-btn btn--primary ml-1 updateCoupon"
                                            data-id="{{$coupon->id}}" 
                                            data-name="{{$coupon->name}}" 
                                            data-code="{{$coupon->code}}" 
                                            data-Type="{{$coupon->type}}" 
                                            data-value="{{getAmount($coupon->value)}}" 
                                            data-status="{{$coupon->status}}">
                                            <i class="las la-edit"></i>
                                        </a>
                                         <a href="javascript:void(0)" class="icon-btn btn--danger ml-1 deleteCoupon"
                                            data-id="{{$coupon->id}}" >
                                             <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($coupons) }}
                </div>
            </div>
        </div>
    </div>


    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Coupon')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.coupon.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" name="name" placeholder="@lang("Enter Name")"  maxlength="40" required="">
                        </div>

                        <div class="form-group">
                            <label for="code" class="form-control-label font-weight-bold">@lang('Code')</label>
                            <input type="text" class="form-control form-control-lg" name="code" placeholder="@lang("Enter Code")"  maxlength="40" required="">
                        </div>


                        <div class="form-group">
                            <label for="type" class="form-control-label font-weight-bold">@lang('Coupon Type')</label>
                            <select name="type" id="type" class="form-control form-control-lg" required="">
                                <option>@lang('Select Type')</option>
                                <option value="1">@lang('Fixed')</option>
                                <option value="2">@lang('Percent')</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="value" class="form-control-label font-weight-bold">@lang('Value')</label>
                            <input type="text" class="form-control form-control-lg" name="value" placeholder="@lang("Enter Value")"  maxlength="40" required="">
                        </div>


                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="status">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="updateCouponModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Coupon Update')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.coupon.update')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" name="name" placeholder="@lang("Enter Name")"  maxlength="40" required="">
                        </div>

                        <div class="form-group">
                            <label for="code" class="form-control-label font-weight-bold">@lang('Code')</label>
                            <input type="text" class="form-control form-control-lg" name="code" placeholder="@lang("Enter Code")"  maxlength="40" required="">
                        </div>


                        <div class="form-group">
                            <label for="type" class="form-control-label font-weight-bold">@lang('Coupon Type')</label>
                            <select name="type" id="type" class="form-control form-control-lg" required="">
                                <option>@lang('Select Type')</option>
                                <option value="1">@lang('Fixed')</option>
                                <option value="2">@lang('Percent')</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="value" class="form-control-label font-weight-bold">@lang('Value')</label>
                            <input type="text" class="form-control form-control-lg" name="value" placeholder="@lang("Enter Value")"  maxlength="40" required="">
                        </div>


                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="status">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="deleteCoupon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Delete Coupon Confirmation')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                
                <form action="{{route('admin.coupon.delete')}}" method="POST">
                    @csrf
                    @method("POST")
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete this coupon ?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addPlan" ><i class="las la-plus"></i>@lang('Add Coupon')</a>
@endpush

@push('script')
    <script>
        "use strict";
        $('.addPlan').on('click', function() {
            $('#addModal').modal('show');
        });

        $('.updateCoupon').on('click', function () {
            var modal = $('#updateCouponModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('input[name=name]').val($(this).data('name'));
            modal.find('input[name=code]').val($(this).data('code'));
            modal.find('select[name=type]').val($(this).data('type'));
            modal.find('input[name=value]').val($(this).data('value'));
            var data = $(this).data('status');
            if(data == 1){
                modal.find('input[name=status]').bootstrapToggle('on');
            }else{
                modal.find('input[name=status]').bootstrapToggle('off');
            }
            modal.modal('show');
        });

        $('.deleteCoupon').on('click', function () {
            var modal = $('#deleteCoupon');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });
    </script>
@endpush
