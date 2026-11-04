@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="row  mt-2">
    <div class="col-12">
        <h5 class="text-dgreen2"> My Tickets</h5>

        <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
            @forelse($supports as $key => $support)
                <ul class="nav border-bottom pb-3 mb-3">
                    <li class="nav-item me-3 "><span class="text-dgreen2 fw-bold">@lang('Subject'):</span> <a
                        href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold">
                        [@lang('Ticket')#{{ $support->ticket }}]
                        {{ __($support->subject) }} </a> </li>
                    <li class="nav-item me-3"><span class="text-dgreen2 fw-bold"> Status:</span>
                        @if ($support->status == 0)
                            <span class="text-danger">@lang('Open')</span>
                        @elseif($support->status == 1)
                            <span class="text-green">@lang('Answered')</span>
                        @elseif($support->status == 2)
                            <span class="text-warning">@lang('Customer Reply')</span>
                        @elseif($support->status == 3)
                            <span class="text-green">@lang('Closed')</span>
                        @endif

                    </li>
                    <li class="nav-item me-3 "><span class="text-dgreen2 fw-bold"> Priority:</span>
                        @if ($support->priority == 1)
                            <span class="text-warning">@lang('Low')</span>
                        @elseif($support->priority == 2)
                            <span class="text-green">@lang('Medium')</span>
                        @elseif($support->priority == 3)
                            <span class="text-danger">@lang('High')</span>
                        @else
                            <span>@lang('N/A')</span>
                        @endif
                    </li>
                    <li class="nav-item me-3 me-md-0"><span class="text-dgreen2 fw-bold"> Last Reply: </span>{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }}  </li>
                    <li class="nav-item ms-lg-3 ms-xl-5 pt-md-2 pt-lg-0  ">
                        <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn-sm btn-success bg-green border-0"> <i class="fa-regular fa-eye"></i> View </a>
                </ul>
            @empty
                <ul class="nav pb-3 mb-3">
                    <li class="nav-item me-3 me-md-0">@lang('No data found')</li>
                </ul>
            @endforelse
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
