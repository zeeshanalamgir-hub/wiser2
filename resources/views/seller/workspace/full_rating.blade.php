@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'scripts' => ['assets/templates/basic/scripts/js/user/seller/workroom.js'],
])
@section('content')
    <p class=" mb-0 text-bc"><a class="text-3gray" href="{{ url('/user/seller/job/list/ajax/bids') }}">
            <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i>
        <a href="/user/seller/job/list/ajax/offers" style="text-decoration:none; color:#9c9c9c">Offered Jobs </a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i> <span id="breadcrumb-title">Workspace Rating</span>
    </p>
    {{--  <div class="card shadow mt-4">
        <div class="card-body p-4">
            @include('seller.workspace.partial_job_details')
            @include('seller.workspace.partial_navigation', ['activeList' => 'rating'])

            <div id="ajax-content" class="row">
                @include('seller.workspace.rating')
            </div>
        </div>
    </div>  --}}

    <div class="card mt-4 bg-green3">
        <div class="card-body p-4">
            @include('seller.workspace.partial_job_details')
        </div>
    </div>
    @include('seller.workspace.partial_navigation', ['activeList' => 'rating'])
    <div class="card">
        <div class="card-body p-4">
            <div id="ajax-content" class="row">
               @include('seller.workspace.rating')
            </div>
        </div>
    </div>
@endsection
