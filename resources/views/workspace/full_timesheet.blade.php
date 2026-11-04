@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'scripts' => ['assets/templates/basic/scripts/js/user/buyer/workroom.js'],
])
@section('content')
    <p class=" mb-0 text-bc"><a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
            <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i>
        <a href="/user/buyer/hire/offered-jobs" style="text-decoration:none; color:#9c9c9c">Offered Jobs </a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i> <span id="current-breadcrumb">Workspace Timesheet</span>
    </p>
    <div class="card mt-4 bg-green3">
        <div class="card-body p-4">
            @include('templates.basic.user.buyer.partial_job_details')
        </div>
    </div>
    @include('templates.basic.user.buyer.partial_navigation', ['activeList' => 'milestone'])

    <div id="ajax-content" class="row">
        @include('workspace.timesheet')
    </div>
@endsection
