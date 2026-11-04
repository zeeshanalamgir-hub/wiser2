@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.seller_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="table-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="table-area">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Title')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Software File')</th>
                                                <th>@lang('Demo Url')</th>
                                                <th>@lang('Documents')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Last Update')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($softwares as $software)
                                                <tr>
                                                    <td data-label="@lang('Title')" class="text-start">
                                                        <div class="author-info">
                                                            <div class="thumb">
                                                                <img src="{{getImage('assets/images/software/'.$software->image,'590x300') }}" alt="@lang('Service Image')">
                                                            </div>
                                                            <div class="content">{{__(str_limit($software->title, 10))}}</div>
                                                        </div>
                                                    </td>
                                                    <td data-label="@lang('Amount')">{{$general->cur_sym}}{{showAmount($software->amount)}}</td>
                                                    <td data-label="@lang('Software File')">
                                                        <a href="{{route('user.software.file.download',encrypt($software->id))}}" class="btn btn--sm btn--info text-white"><i class="las la-arrow-down"></i></a>
                                                    </td>

                                                     <td data-label="@lang('Demo Url')">
                                                        <a href="{{$software->demo_url}}" target="__blank">{{$software->demo_url}}</a>
                                                    </td>

                                                    <td data-label="@lang('Documents')">
                                                        <a href="{{route('user.software.document.download',encrypt($software->id))}}" class="btn btn--sm btn--info text-white"><i class="las la-arrow-down"></i></a>
                                                    </td>

                                                    <td data-label="@lang('Status')">
                                                        @if($software->status == 1)
                                                            <span class="badge badge--success">@lang('Approved')</span>
                                                            <br>
                                                            {{diffforhumans($software->created_at)}}
                                                        @elseif($software->status == 2)
                                                            <span class="badge badge--danger">@lang('Cancel')</span>
                                                             <br>
                                                            {{diffforhumans($software->created_at)}}
                                                        @else
                                                            <span class="badge badge--primary">@lang('Pending')</span>
                                                             <br>
                                                            {{diffforhumans($software->created_at)}}
                                                        @endif
                                                        </td>
                                                    <td data-label="@lang('Last Update')">
                                                        {{showDateTime($software->updated_at)}}
                                                        <br>
                                                        {{diffforhumans($software->updated_at)}}
                                                    </td>
                                                    <td data-label="Action">
                                                        <a href="{{route('user.software.edit', [slug($software->title), $software->id])}}" class="btn btn--primary text-white"><i class="fa fa-pencil-alt"></i></a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$softwares->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
