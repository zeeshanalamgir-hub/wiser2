@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'scripts' => ['assets/templates/basic/scripts/js/user/seller/workroom.js'],
])
@section('content')
    {{-- notification --}}
    <div class="alert" id="memo_update" role="alert" style="display: none;"></div>

    <p class=" mb-0 text-bc"><a class="text-3gray" href="{{ url('/user/seller/job/list/ajax/bids') }}">
            <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i>
        <a href="/user/seller/job/list/ajax/offers" style="text-decoration:none; color:#9c9c9c">Offered Jobs </a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i> <span id="breadcrumb-title">Workspace Timesheet</span>
    </p>

    <div class="card mt-4 bg-green3">
        <div class="card-body p-4">
            @include('seller.workspace.partial_job_details')
        </div>
    </div>
    @include('seller.workspace.partial_navigation', ['activeList' => 'milestone'])
    <div class="card">
        <div class="card-body p-4">
            <div id="ajax-content" class="row">
               @include('seller.workspace.timesheet')
            </div>
        </div>
    </div>
@endsection
