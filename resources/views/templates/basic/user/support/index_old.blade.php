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
                                                <th>@lang('Subject')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Priority')</th>
                                                <th>@lang('Last Reply')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($supports as $key => $support)
                                                <tr>
                                                    <td data-label="@lang('Subject')"> <a href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                                    <td data-label="@lang('Status')">
                                                        @if($support->status == 0)
                                                            <span class="badge badge--success py-2 px-3">@lang('Open')</span>
                                                        @elseif($support->status == 1)
                                                            <span class="badge badge--primary py-2 px-3">@lang('Answered')</span>
                                                        @elseif($support->status == 2)
                                                            <span class="badge badge--warning py-2 px-3">@lang('Customer Reply')</span>
                                                        @elseif($support->status == 3)
                                                            <span class="badge badge--dark py-2 px-3">@lang('Closed')</span>
                                                        @endif
                                                    </td>
                                                    <td data-label="@lang('Priority')">
                                                        @if($support->priority == 1)
                                                            <span class="badge badge--dark py-2 px-3">@lang('Low')</span>
                                                        @elseif($support->priority == 2)
                                                            <span class="badge badge--secondary py-2 px-3">@lang('Medium')</span>
                                                        @elseif($support->priority == 3)
                                                            <span class="badge badge--primary py-2 px-3">@lang('High')</span>
                                                        @else
                                                            <span>@lang('N/A')</span>
                                                        @endif
                                                    </td>
                                                    <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                                    <td data-label="@lang('Action')">
                                                        <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--primary btn-sm text-white">
                                                            <i class="fa fa-desktop"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">@lang('No data found')</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$supports->links()}}
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

