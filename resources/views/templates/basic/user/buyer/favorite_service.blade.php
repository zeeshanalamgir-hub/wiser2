@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="dashboard-content">
        <div class="container-header-table-100095">
            <div class="table-container-985632">
                <div class="table-heading-10002">
                    <div class="table-heding-top-78563">Dispute</div>

                </div>
                <table class="table row-border">
                    <thead class="table-head-10004">
                        <tr>
                            <th scope="col" class="table-head-col-10005">
                                @lang('Title')</th>
                            <th scope="col" class="table-head-col-10005">
                                @lang('Price')</th>
                            <th scope="col" class="table-head-col-10005">
                                @lang('Delivery Time')</th>
                            <th scope="col" class="table-head-col-10005">
                                @lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($favoriteServices as $favoriteService)
                            <tr>
                                <td><img class="img-fluid " width="60"
                                        src="{{ getImage('assets/images/service/' . $favoriteService->service->image, '590x300') }}"
                                        alt="@lang('Service Image')"></td>
                                <td>
                                    <div class="dash-title">
                                        <h4 class="mb-0 ft-medium fs-sm">
                                            {{ __(str_limit($favoriteService->service->title, 40)) }}</h4>
                                    </div>
                                </td>
                                <td>{{ showAmount($favoriteService->service->price) }} {{ $general->cur_text }}</td>
                                <td>{{ $favoriteService->service->delivery_time }} @lang('Days')</td>
                                <td>
                                    <div class="dash-action-98563">
                                        <a href="{{ route('service.details', [slug($favoriteService->service->title), $favoriteService->service_id]) }}"
                                            class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                class="lni lni-eye"></i></a>
                                        <!-- <a href="javascript:void(0);" class="p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i class="lni lni-pencil"></i></a>
                                                                                    <a href="javascript:void(0);" class="p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="lni lni-trash-can"></i></a> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection
