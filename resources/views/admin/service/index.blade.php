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
                                <th>@lang('Category Level 1/ Level 2 / Level 3')</th>
                                <!-- <th>@lang('Amount')</th> -->
                                <!-- <th>@lang('Delivery Time')</th>
                                <th>@lang('Featured Item')</th> -->
                                <th>@lang('Status')</th>
                                <th>@lang('Last Update')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($services as $service)
                            <tr @if($loop->odd) class="table-light" @endif>
                                <td data-label="@lang('Title')">
                                    <div class="user">
                                        <div class="thumb"><a href="{{route('admin.service.details', $service->id)}}"><img src="{{ getImage('assets/images/service/'.$service->image,'590x300')}}" alt="@lang('image')"></a></div>
                                        <span class="name">{{__(str_limit($service->title, 10))}}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Seller')">
                                    <span class="font-weight-bold">{{optional($service->user)->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $service->user_id) }}"><span>@</span>{{ optional($service->user)->username }}</a>
                                    </span>
                                </td>

                                <td data-label="@lang('Category / SubCategory')">
                                    <span class="font-weight-bold">{{__(@$service->category->name)}}</span>
                                    <br>
                                    @if($service->sub_category_id)
                                        <span>{{__($service->subCategory->name)}}</span>
                                    @else
                                        <span>@lang('N/A')</span>
                                    @endif
                                    <br>
                                    @if($service->childcat_id)
                                        <span>{{__($service->childCategory->name)}}</span>
                                    @else
                                        <span>@lang('N/A')</span>
                                    @endif

                                </td>
                                <!-- <td data-label="@lang('Amount')">
                                   <span class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount($service->price) }}</span>
                                </td>

                                 <td data-label="@lang('Delivery Time')">
                                   <span class="font-weight-bold">{{($service->delivery_time)}} @lang('Days')</span>
                                </td>

                                <td data-label="@lang('Featured Item')">
                                     @if($service->featured == 1)
                                        <span class="badge badge-success badge-pill font-weight-bold">@lang('Included')</span>
                                        <a href="javascript:void(0)" class="icon-btn btn--info ml-2 notInclude" data-toggle="tooltip" title="" data-original-title="@lang('Not Include')" data-id="{{ $service->id }}">
                                            <i class="las la-arrow-alt-circle-left"></i>
                                        </a>
                                    @else
                                        <span class="badge badge-warning badge-pill font-weight-bold text-white">@lang('Not included')</span>
                                        <a href="javascript:void(0)" class="icon-btn btn--success ml-2 include text-white" data-toggle="tooltip" title="" data-original-title="@lang('Include')" data-id="{{ $service->id }}">
                                            <i class="las la-arrow-alt-circle-right"></i>
                                        </a>
                                    @endif
                                </td> -->

                                <td data-label="@lang('Status')">
                                    @if($service->status == 1)
                                        <span class="font-weight-normal badge--success">@lang('Approved')</span>
                                        <br>
                                        {{diffforhumans($service->created_at)}}
                                    @elseif($service->status == 2)
                                        <span class="font-weight-normal badge--danger">@lang('Cancel')</span>
                                        <br>
                                        {{diffforhumans($service->created_at)}}
                                    @elseif($service->status == 0)
                                        <span class="font-weight-normal badge--primary">@lang('Pending')</span>
                                        <br>
                                        {{diffforhumans($service->created_at)}}
                                    @endif
                                </td>

                                <td data-label="@lang('Last Update')">
                                    <span>{{showDateTime($service->updated_at)}}</span>
                                    <br>
                                     {{diffforhumans($service->updated_at)}}
                                </td>

                                <td data-label="@lang('Action')">

                                    @if($service->status == 0)
                                        <button class="icon-btn btn--success ml-2 approved" data-toggle="tooltip" data-id="{{$service->id}}" data-original-title="@lang('Approved')">
                                            <i class="las la-check"></i>
                                        </button>

                                        <button class="icon-btn btn--danger ml-2 cancel" data-toggle="tooltip" title="" data-original-title="@lang('Cancel')" data-id="{{$service->id}}">
                                            <i class="las la-times"></i>
                                        </button>
                                    @endif

                                    <a href="{{route('admin.service.details', $service->id)}}" class="icon-btn ml-2" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
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
                    {{ paginateLinks($services) }}
                </div>
            </div>
        </div>
    </div>


<div class="modal fade" id="includeFeatured" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Featured Item Include')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{ route('admin.service.featured.include') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure include this service featured list?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="NotincludeFeatured" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Featured Item Remove')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{ route('admin.service.featured.remove') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure remove this service featured list?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
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

            <form action="{{route('admin.service.approvedBy')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to approved this service?')</p>
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

            <form action="{{ route('admin.service.cancelBy') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to cancel this service?')</p>
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
    <form action="{{route('admin.service.search', $scope ?? str_replace('admin.service.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or price')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>


    <form action="{{route('admin.service.category')}}" method="GET" class="form-inline float-sm-right bg--white mr-2">
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

    $('.include').on('click', function () {
        var modal = $('#includeFeatured');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });

    $('.notInclude').on('click', function () {
        var modal = $('#NotincludeFeatured');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
</script>
@endpush
