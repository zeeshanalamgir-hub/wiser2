@php
    $expiry = \Carbon\Carbon::parse($expiry_date);
    $today = today();

    $daysLeft = $today->diffInDays($expiry, false);

    $showExpireSoon = $daysLeft === 2;
    $showExpireTomorrow = $daysLeft === 1;
    $showExpireToday = $daysLeft === 0;
@endphp
<div class="row">
    <div class="col-md-5">
        <h4 class="text-dgreen2 mt-3"> Hi, {{ Auth::user()?->fullname }} </h4>
    </div>
    @include('templates.basic.user.buyer.dashboard-components.dashboard-titlebar-top')
</div>
@if($show_alert)

    @if($showExpireSoon)
        <div class="alert alert-info">
            Your membership will expire in 2 days. <a href="/user/buyer/employermembership">Click Here</a> to upgrade your membership and enjoy uninterrupted services.
        </div>
    @elseif($showExpireTomorrow)
        <div class="alert alert-warning">
            Your membership will expire tomorrow.  <a href="/user/buyer/employermembership">Click Here</a> to upgrade your membership and enjoy uninterrupted services.
        </div>
    @elseif($showExpireToday)
        <div class="alert alert-danger">
            Your membership expires today.  <a href="/user/buyer/employermembership">Click Here</a> to upgrade your membership and enjoy uninterrupted services.
        </div>
    @else
        <div class="alert alert-danger">
            Your membership expires.  <a href="/user/buyer/employermembership">Click Here</a> to upgrade your membership and enjoy uninterrupted services.
        </div>
    @endif

@endif

<div class="row my-3">
    @include('templates.basic.user.buyer.dashboard-components.dashboard-tboxes-top')
</div>
<div class="mt-4">
    @include('templates.basic.user.buyer.dashboard-components.dashboard-activity-table')
</div>
