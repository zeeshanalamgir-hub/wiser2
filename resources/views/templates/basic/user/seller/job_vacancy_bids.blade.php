@extends('templates.basic.layouts.wiserlance.dashboard-layout',  [
    'scripts' => [
        'assets/templates/basic/scripts/js/data-ajax.js',
        'assets/templates/basic/scripts/js/user/seller/job_vacancy_bids.js',
    ],
    'styles' => [
        'assets/templates/basic/styles/css/racket-tables.css',
        'assets/templates/basic/styles/css/user/seller/job_vacancy_bids.css'
    ]

])
@section('content')

<div class="racket-dashboard-content-5356 py-3">
    <div class="container-header-table-100095">
        <div class="header-top-buttons-10065">
            {{-- data-href="{{ route('user.job.vacancy') }}" --}}
            <div id="btn-bids-5432534" class="btn rounded sd-button-header-10067 ffs-24 poppins-semibold " onclick="window.location.href = '/user/seller/job/list'">
                <i class="fas fa-chevron-left bid-arrow-back-789678"></i> Bids
            </div>
            <div id="btn-offers-5432534" class="btn rounded sd-button-header-10068 ffs-24 poppins-semibold">
                Bid Detail
            </div>
        </div>
    </div>
    <div class="tab-content py-3" id="racket-job-vacancy-detail-view-tabs">
        <div class="tab-pane fade show active" id="bids" role="tabpanel" aria-labelledby="bids-tab">
            @include('templates.basic.user.seller.components.job-vacancy.bids-detail-view')
        </div>
    </div>
</div>
@endsection

