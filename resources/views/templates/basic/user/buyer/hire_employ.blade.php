@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="dashboard-content">
        <div class="container-header-table-100095">
            @include('templates.basic.user.buyer.components.buyer-hire-employees-top-buttons', [
                'activeTab' => 'openJob',
            ])
            <div class="table-container-985632">
                <div class="table-heading-10002">
                    <div class="table-heding-top-78563">Open Jobs</div>
                </div>
                <table class="table row-border">
                    <thead class="table-head-10004">
                        <tr>
                            <th class="table-head-col-10005">
                                @lang('Job Title')</th>
                            <th class="table-head-col-10005">
                                @lang('Job Type')</th>
                            <th class="table-head-col-10005">
                                @lang('Bids')</th>
                            <th class="table-head-col-10005">
                                @lang('Date')</th>
                            <th class="table-head-col-10005">
                                @lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody class="table-body-10004">
                        @foreach ($hireEmploys as $hireEmploy)
                            <tr>
                                <td style="width: 300px">
                                    <a style="color:#565656;"
                                        href="{{ url('/job/details/' . encrypt($hireEmploy->job->id)) }}">
                                        {{ __(str_limit($hireEmploy->job->title, 20)) }}
                                    </a>
                                </td>
                                @if ($hireEmploy->job->budgettype == 'fixed')
                                    <td style="white-space: 150px">Fixed</td>
                                @elseif($hireEmploy->job->budgettype == 'per_hour')
                                    <td style="white-space: 150px">Per Hour</td>
                                @endif
                                @php
                                    $bids = DB::table('user_biding')
                                        ->where('job_id', $hireEmploy->job->id)
                                        ->count();
                                    $offers = DB::table('user_offers')
                                        ->where('job_id', $hireEmploy->job->id)
                                        ->count();
                                @endphp
                                <td style="white-space: 130px">
                                    <a style="color:#565656;"
                                        href="{{ route('user.buyer.hire.employ.bids', encrypt($hireEmploy->job_id)) }}">
                                        {{ $bids }}
                                    </a>
                                </td>

                                <td style="white-space: 150px">
                                    {{ showDateTime($hireEmploy->job->created_at, 'd M Y') }}
                                </td>
                                <td style="width: 100px">
                                    <div class="dash-action-98563">
                                        <a title="View Bidding Detail"
                                            href="{{ route('user.buyer.hire.employ.bids', encrypt($hireEmploy->job_id)) }}"
                                            class="circle text-info bg-light-info d-inline-flex align-items-center justify-content-center"
                                            style="gap: 16px"><i style="color:#00C535" class="lni lni-eye"></i> <span
                                                style="color: #565656">Bid details</span></a>

                                    </div>
                                    {{-- @if ($hireEmploy->job->show_completed == '1')
                                            <a title="Add to completed" href="javascript:void(0);"
                                                data-id="{{ $hireEmploy->job->id }}" data-toggle="modal"
                                                data-target="#completedModal"
                                                class="completedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i
                                                    class="fa fa-check"></i></a>
                                        @endif --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        'use strict';
        $('.completedBtn').on('click', function() {
            var modal = $('#completedModal');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });
    </script>
@endsection
