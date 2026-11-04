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
                                <th>@lang('Name')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Password')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($staffs as $staff)
                                <tr>
                                    <td data-label="@lang('Name')">
                                        <span class="font-weight-bold">{{$staff->name}}</span>
                                    </td>

                                     <td data-label="@lang('Username')">
                                        <span class="font-weight-bold">{{$staff->username}}</span>
                                    </td>

                                    <td data-label="@lang('Email')">
                                        <span class="font-weight-bold">{{$staff->email}}</span>
                                    </td>

                                    <td data-label="@lang('Password')">
                                        @if($staff->status == 0)
                                            <span class="font-weight-bold">{{decrypt($staff->show_password)}}</span>
                                        @else
                                             <span class="font-weight-bold">@lang('N/A')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Joined At')">
                                        {{ showDateTime($staff->created_at) }} <br> {{ diffForHumans($staff->created_at) }}
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="{{route('admin.staff.edit', $staff->id)}}" class="icon-btn mr-2" data-toggle="tooltip" title="" data-original-title="@lang('Edit')">
                                            <i class="las la-edit text--shadow"></i>
                                        </a>

                                        @if($staff->status==0)
                                             <a href="javascript:void(0)" data-id="{{$staff->id}}" class="icon-btn btn--danger delete" data-toggle="tooltip" title="" data-original-title="@lang('Delete')">
                                                <i class="las la-trash text--shadow"></i>
                                            </a>
                                        @endif
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
                    {{ paginateLinks($staffs) }}
                </div>
            </div>
        </div>
    </div>


<div class="modal fade" id="deleteStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Delete Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            
            <form action="{{ route('admin.staff.delete') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to delete this staff?')</p>
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


@push('script')
<script>
    'use strict';
    $('.delete').on('click', function () {
        var modal = $('#deleteStaff');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
</script>
@endpush

