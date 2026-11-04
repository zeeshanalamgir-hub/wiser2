@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')

@section('content')
    @include('templates.basic.user.buyer.buyer_jobs_menu')
    <div id="racket-job-vacancy-view-tabs">
        <div class="tab-pane fade show active" id="jobs" role="tabpanel" aria-labelledby="open-job-tab">
            @include('templates.basic.user.buyer.components.hire-employee.partials.completed-jobs', ['data' => $data])
        </div>
    </div>
@endsection

