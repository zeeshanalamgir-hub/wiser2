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
                                    <th>@lang('Job Title')</th>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Category / SubCategory')</th>
                                    <th>@lang('Budget')</th>
                                    <th>@lang('Delivery Time')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobs as $job)
                                    <tr @if ($loop->odd) class="table-light" @endif>
                                        <td data-label="@lang('Title')">
                                            <div class="user">
                                                {{-- <div class="thumb"><img
                                                        src="{{ getImage('assets/images/job/' . $job->image, '590x300') }}"
                                                        alt="@lang('image')"></div> --}}
                                                <span class="name">{{ __(str_limit($job->title, 20)) }}</span>
                                            </div>
                                        </td>

                                        <td data-label="@lang('Buyer')">
                                            {{-- <span class="font-weight-bold">{{ @$job->user->fullname }}</span> --}}
                                            <br>
                                            <span class="small">
                                                @if (@$job->user->id == '')
                                                @else
                                                    <a
                                                        href="{{ route('admin.users.detail', $job->user_id) }}"><span>@</span>{{ $job->user->username }}</a>
                                            </span>
                                @endif
                                </td>

                                <td data-label="@lang('Category / SubCategory')">
                                    <span class="font-weight-bold">{{ __(optional($job->category)->name) }}</span>
                                    <br>
                                    @if ($job->sub_category_id)
                                        <span>{{ __($job->subCategory->name) }}</span>
                                    @else
                                        <span>@lang('N/A')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Budget')">
                                    <span class="font-weight-bold">
                                        {{ $general->cur_sym }}
                                        @if ($job->budgettype == 'hourly')
                                            {{ getAmount($job->amount * $job->weekly_hours)}}
                                        @elseif ($job->budgettype == 'fixed')
                                            {{ getAmount($job->amount) }}
                                        @else
                                            0
                                        @endif
                                    </span>
                                </td>


                                <td data-label="@lang('Delivery Time')">
                                    <span class="font-weight-bold">{{ diffForHumans($job->delivery_date) }}</span>
                                </td>

                                <td data-label="@lang('Status')">
                                    @if ($job->status == 1)
                                        <span class="font-weight-normal badge--success">@lang('Approved')</span>
                                        <br>
                                        {{ diffforhumans($job->created_at) }}
                                    @elseif($job->status == 2)
                                        <span class="font-weight-normal badge--warning">@lang('Closed')</span>
                                        <br>
                                        {{ diffforhumans($job->created_at) }}
                                    @elseif($job->status == 3)
                                        <span class="font-weight-normal badge--danger">@lang('Cancel')</span>
                                        <br>
                                        {{ diffforhumans($job->created_at) }}
                                    @elseif($job->status == 0)
                                        <span class="font-weight-normal badge--primary">@lang('Pending')</span>
                                        <br>
                                        {{ diffforhumans($job->created_at) }}
                                    @endif
                                </td>

                                <td data-label="@lang('Last Update')">
                                    <span>{{ showDateTime($job->created_at) }}</span>
                                    <br>
                                    {{ diffforhumans($job->created_at) }}
                                </td>

                                <td data-label="@lang('Action')">
                                    @if ($job->status == 0)
                                        <button class="icon-btn btn--success ml-1 approved" data-toggle="tooltip"
                                            data-id="{{ $job->id }}" data-original-title="@lang('Approved')">
                                            <i class="las la-check"></i>
                                        </button>

                                        <button class="icon-btn btn--danger ml-1 cancel" data-toggle="tooltip"
                                            title="" data-original-title="@lang('Cancel')"
                                            data-id="{{ $job->id }}">
                                            <i class="las la-times"></i>
                                        </button>
                                    @endif

                                    @if ($job->status == 4)
                                        <button class="icon-btn btn--warning ml-1 closed" data-toggle="tooltip"
                                            title="" data-original-title="@lang('Closed')"
                                            data-id="{{ $job->id }}">@lang('Closed')</button>
                                    @endif

                                    @if ($job->status != 0)
                                        <a href="{{ route('admin.job.biding.list', $job->id) }}"
                                            class="icon-btn btn--info ml-1" data-toggle="tooltip"
                                            data-original-title="@lang('Biding list')">@lang('Biding List')</a>
                                    @endif

                                    <a href="{{ route('admin.job.details', $job->id) }}" class="icon-btn ml-1"
                                        data-toggle="tooltip"
                                        data-original-title="@lang('Details')">@lang('Details')</a>

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
                    {{ paginateLinks($jobs) }}
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="approvedby" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Approval Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.job.approvedBy') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to approved this job post?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="cancelBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Cancel Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.job.cancelBy') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to cancel this job post?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary"
                            data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="closedBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Closed Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.job.closedBy') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to closed this job post?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary"
                            data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('breadcrumb-plugins')
    <form action="{{ route('admin.job.search', $scope ?? str_replace('admin.job.', '', request()->route()->getName())) }}"
        method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Job Title')"
                value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>


    <form action="{{ route('admin.job.category') }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <select class="form-control" name="category">
                <option value="" required>----@lang('Select Category')----</option>
                @foreach ($categorys as $category)
                    @if (@$searchCategory == $category->id)
                        <option value="{{ $category->id }}" selected="">{{ __($category->name) }}</option>
                    @else
                        <option value="{{ $category->id }}">{{ __($category->name) }}</option>
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
        $('.approved').on('click', function() {
            var modal = $('#approvedby');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });

        $('.cancel').on('click', function() {
            var modal = $('#cancelBy');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });

        $('.closed').on('click', function() {
            var modal = $('#closedBy');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });
    </script>
@endpush
