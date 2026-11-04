@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="dashboard-content">
        <div class="container-header-table-100095">
            @include('templates.basic.user.buyer.components.buyer-hire-employees-top-buttons', [
                'activeTab' => 'closedJob',
            ])
            <div class="table-container-985632">
                <div class="table-heading-10002">
                    <h3>Closed Jobs</h3>
                </div>
                <table class="table row-border">
                    <thead class="table-head-10004">
                        <tr>
                            <th scope="col">@lang('Job Title')</th>
                            <th scope="col">@lang('Job Type')</th>
                            <th scope="col">@lang('Closed Date')</th>
                            <th class="text-center">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hireEmploys as $job)
                            <tr>
                                <td><a style="color:blue;text-decoration: underline;"
                                        href="{{ route('job.details', [slug($job->title), encrypt($job->id)]) }}">{{ __(str_limit($job->title, 20)) }}
                                    </a></td>
                                @if ($job->budgettype == 'fixed')
                                    <td>Fixed</td>
                                @elseif($job->budgettype == 'per_hour')
                                    <td>Per Hour</td>
                                @endif

                                <td>
                                    {{ showDateTime($job->closed_at, 'd M Y') }}
                                </td>
                                <td class="text-center">

                                    <div class="dash-action-98563">
                                        N/A


                                    </div>
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
        "use strict";
        $('.approvedBtn').on('click', function() {
            var modal = $('#approvedModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.cancelBtn').on('click', function() {
            var modal = $('#cancelModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection
