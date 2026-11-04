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
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Delivery At')</th>
                                    <th>@lang('Created At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($jobBidings as $jobBiding)
                                <tr @if($loop->odd) class="table-light" @endif>
                                    <td data-label="@lang('Title')">
                                        <span class="name">{{__(str_limit($jobBiding->job->title, 20))}}</span>
                                    </td>
                                    <td data-label="@lang('Seller')">
                                        <span class="font-weight-bold">{{$jobBiding->seller->username}}</span>
                                        <br>
                                        <span class="small">
                                        <a href="{{ route('admin.users.detail', $jobBiding->sender_id) }}"><span>@</span>{{ $jobBiding->seller->username }}</a>
                                        </span>
                                    </td>

                                    <td data-label="@lang('Amount')">
                                       <span class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount($jobBiding->offerdata->amount) }}</span>
                                    </td>

                                    <td data-label="@lang('Status')">
                                        <span class="font-weight-bold">{{ $jobBiding->offerdata->getStatusLabel() }}</span>
                                     </td>
                                     <td data-label="@lang('Delivery At')">
                                        <span>{{showDateTime($jobBiding->offerdata->due_date)}}</span>
                                        <br>
                                        {{diffforhumans($jobBiding->offerdata->due_date)}}
                                    </td>
                                     <td data-label="@lang('Created At')">
                                        <span>{{showDateTime($jobBiding->updated_at)}}</span>
                                        <br>
                                        {{diffforhumans($jobBiding->updated_at)}}
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="{{route('admin.job.biding.details', $jobBiding->id)}}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
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
                    {{ paginateLinks($jobBidings) }}
                </div>
            </div>
        </div>
    </div>
@endsection



