@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'scripts' => ['assets/templates/basic/scripts/js/user/buyer/workroom.js'],
])
@section('content')
<p class=" mb-0 text-bc"><a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
            <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i>
        <a href="/user/buyer/hire/offered-jobs" style="text-decoration:none; color:#9c9c9c">Offered Jobs </a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i> <span id="breadcrumb-title">Workspace Overview</span>
    </p>

    <div class="card mt-4 bg-green3">
        <div class="card-body p-4">
            @include('templates.basic.user.buyer.partial_job_details')
        </div>
    </div>

    @include('templates.basic.user.buyer.partial_navigation', ['activeList' => 'overview'])

    <div class="card">
        <div class="card-body p-4">
            <div id="ajax-content" class="row">
                @include('workspace.overview')
            </div>
        </div>
    </div>
    @if ($job_type === 'byproject')
        @if (count($contract_details) > 0 && !is_null($req_type) && $payment_details != [])
            @include('templates.basic.user.buyer.fixed_contract_details', [
                'contract_details' => $contract_details,
            ])
            @include('templates.basic.user.buyer.milestone_schedule')
        @elseif(is_null($req_type) && is_null($req_type) && $payment_details != [])
            @include('templates.basic.user.buyer.fixed_contract_details', [
                'contract_details' => $contract_details,
            ])
            @include('templates.basic.user.buyer.fixed_payment_details')
        @endif
    @elseif($job_type === 'hourly')
        @include('templates.basic.user.buyer.hourly_contract_details')
        @include('templates.basic.user.buyer.hourly_payment_details')
    @elseif($job_type === 'bymilestone')
        @include('templates.basic.user.buyer.fixed_contract_details', [
            'contract_details' => $contract_details,
        ])
        @include('templates.basic.user.buyer.milestone_schedule')
    @endif
    @include('templates.basic.user.buyer.term_condition_details')
@endsection
