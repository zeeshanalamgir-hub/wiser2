@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Title')</th>
                            <th>@lang('Seller')</th>
                            <th>@lang('Category / SubCategory')</th>
                            <th>@lang('Software')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Document')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Last Update')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($softwares as $software)
                            <tr @if($loop->odd) class="table-light" @endif>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                        <div class="thumb"><img src="{{ getImage('assets/images/software/'.$software->image,'590x300')}}" alt="@lang('image')"></div>
                                        <span class="name">{{__(str_limit($software->title, 10))}}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Seller')">
                                    <span class="font-weight-bold">{{$software->user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $software->user_id) }}"><span>@</span>{{ $software->user->username }}</a>
                                    </span>
                                </td>
                                <td data-label="@lang('Category / SubCategory')">
                                    <span class="font-weight-bold">{{__($software->category->name)}}</span>
                                    <br>
                                    @if($software->sub_category_id)
                                        <span>{{__($software->subCategory->name)}}</span>
                                    @else
                                        <span>@lang('N/A')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Software')">
                                    <a href="{{route('admin.software.download', encrypt($software->id))}}" class="icon-btn"><i class="las la-arrow-down"></i></a>
                                </td>

                                <td data-label="@lang('Amount')">
                                   <span class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount($software->amount) }}</span>
                                </td>

                                <td data-label="@lang('Document')">
                                    <a href="{{route('admin.document.download', encrypt($software->id))}}" class="icon-btn"><i class="las la-arrow-down"></i></a>
                                </td>

                                <td data-label="@lang('Status')">
                                    @if($software->status == 1)
                                        <span class="font-weight-normal badge--success">@lang('Approved')</span>
                                        <br>
                                        {{diffforhumans($software->created_at)}}
                                    @elseif($software->status == 2)
                                        <span class="font-weight-normal badge--danger">@lang('Cancel')</span>
                                        <br>
                                        {{diffforhumans($software->created_at)}}
                                    @elseif($software->status == 0)
                                        <span class="font-weight-normal badge--primary">@lang('Pending')</span>
                                        <br>
                                        {{diffforhumans($software->created_at)}}
                                    @endif
                                </td>

                                <td data-label="@lang('Last Update')">
                                    <span>{{showDateTime($software->updated_at)}}</span>
                                    <br>
                                     {{diffforhumans($software->updated_at)}}
                                </td>

                                <td data-label="@lang('Action')">
                                    @if($software->status == 0)
                                        <button class="icon-btn btn--success ml-2 approved" data-toggle="tooltip" data-id="{{$software->id}}" data-original-title="@lang('Approved')">
                                            <i class="las la-check"></i>
                                        </button>

                                        <button class="icon-btn btn--danger ml-2 cancel" data-toggle="tooltip" data-original-title="@lang('Cancel')" data-id="{{$software->id}}">
                                            <i class="las la-times"></i>
                                        </button>
                                    @endif

                                    <a href="{{route('admin.software.details', $software->id)}}" class="icon-btn ml-2" data-toggle="tooltip"  data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4">
                {{ paginateLinks($softwares) }}
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="approvedby" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Approval Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            
            <form action="{{route('admin.software.approvedBy')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to approved this software?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="cancelBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Cancel Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            
            <form action="{{ route('admin.software.cancelBy') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to cancel this software?')</p>
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
<form action="{{route('admin.software.search', $scope ?? str_replace('admin.software.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('Username or price')" value="{{ $search ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>

<form action="{{route('admin.software.category')}}" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append">
        <select class="form-control" name="category">
            <option>----@lang('Select Category')----</option> 
            @foreach($categorys as $category)
                @if(@$categoryId == $category->id)
                    <option value="{{$category->id}}" selected="">{{__($category->name)}}</option> 
                @else
                    <option value="{{$category->id}}">{{__($category->name)}}</option> 
                @endif
            @endforeach
       </select>
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>
@endpush


@push('script')
<script>
    'use strict';
    $('.approved').on('click', function () {
        var modal = $('#approvedby');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });

    $('.cancel').on('click', function () {
        var modal = $('#cancelBy');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
</script>
@endpush