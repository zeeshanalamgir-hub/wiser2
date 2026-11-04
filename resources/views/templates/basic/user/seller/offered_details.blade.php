@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')

@section('content')
<p class="mb-0 mt-3 text-bc">
    <i class="fa-solid fa-house fs-14"></i>
    <i class="fa-solid fa-chevron-right fs-12 px-1"></i>
    <a href="/user/seller/job/list/ajax/bids" style="text-decoration:none; color:#9c9c9c">Bids</a>
    <i class="fa-solid fa-chevron-right fs-12 px-1"></i> Bid Details
</p>
{{-- @if($status == 1)
    <div class="col-12 shadow mt-3 alert alert-danger" role="alert">
        <h5>Offer rejected by {{ $jobPosterName }}</h5>
        <p><b>Reason: </b> {{ $data->seller_offer_responce_reason }}</p>
    </div>
@endif --}}

<div class="card shadow mt-4">
    <div class="card-header pt-3">
        <p class="float-end me-3">
            <span class="text-secondary">Status:</span>
            <span class="fw-bold text-{{ $status == 2 ? 'success' : ($status == 1 ? 'danger' : '') }}">
                @if($status == 2)
                    <i class="fa-regular fa-circle-check"></i> {{ $data->seller_offer_responce_text }}
                @elseif($status == 1)
                    <i class="fa-regular fa-circle-xmark"></i> {{ $data->seller_offer_responce_text }}
                @elseif($status == 3)
                    <i class="fa-regular fa-circle-check"></i> {{ $data->seller_offer_responce_text }}
                @else
                    <i class="fa-regular fa-clock me-1 bg-light"></i>{{ $data->seller_offer_responce_text }}
                @endif
            </span>
        </p>
        <h5>Bid Details</h5>
    </div>

    <div class="card-body p-4">
        <div class="row">
            <div class="col-8">
                <a class="links" href="#"><h5>{{ $data->job->title }}</h5></a>
                <p><b>Description:</b> {!! $data->job->description ?? 'No description available' !!}</p>
                <p><b>Job Type:</b> {{ ucfirst($data->job->job_type) }}</p>
            </div>
            {{-- <div class="col-4">
                <div class="card text-center">
                    <div class="card-header bg-lsgreen">Total Amount</div>
                    <div class="card-body">
                        <h4 class="text-green">
                            ${{ number_format($expected_total_price, 2) }}{{ $data->job->job_type === 'hourly' ? ' /hr' : '' }}
                        </h4>
                    </div>
                </div>
            </div> --}}
        </div>

        @if($data->job_type == 'bymilestone' && !empty($milestones))
            <div class="row">
                <div class="col">
                    <table class="table mt-1">
                        <thead>
                            <tr>
                                <th scope="col" colspan="6">Milestone Title</th>
                                <th scope="col">Due Date</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($milestones as $milestone)
                                <tr>
                                    <td colspan="6">{{ $milestone->title }}</td>
                                    <td>
                                        @if (is_numeric($milestone->due_date))
                                            {{ $milestone->due_date }}
                                        @elseif (\Carbon\Carbon::hasFormat($milestone->due_date, 'Y-m-d') || strtotime($milestone->due_date))
                                            {{ \Carbon\Carbon::parse($milestone->due_date)->format('d-M-Y') }}
                                        @else
                                            No Due Date
                                        @endif
                                    </td>
                                    <td>${{ number_format($milestone->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($data->job_type == 'byproject')
            <div class="row">
                <div class="col">
                    <table class="table mt-2">
                        <thead>
                            <tr>
                                <th scope="col" colspan="6">Milestone Title</th>
                                <th scope="col" class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6">Project Payment</td>
                                <td class="text-end">${{ number_format($expected_total_price, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="row mt-1">
            <div class="col-12">
                <table class="table mt-1">
                    <tbody>
                        <tr>
                            <td><b>Total</b></td>
                            <td class="text-end">${{ number_format($expected_total_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td>{{ $platformFeePercentage }}% Seller Service Fee</td>
                            <td class="text-end">(${{ number_format($platform_fee, 2) }})</td>
                        </tr>
                        {{-- <tr>
                            <td>You’ll Receive -After service fees, your estimated payment</td>
                            <td class="text-end"><b>${{ number_format($total_after_fee, 2) }}</b></td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
