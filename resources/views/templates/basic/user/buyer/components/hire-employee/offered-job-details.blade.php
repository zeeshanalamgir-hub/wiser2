@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')

@section('content')
@if($buyerCounterOffer->status == 2)
    <div class="col-12 shadow mt-3 alert alert-danger" role="alert">
        <h5>The seller has cancelled offer</h5>
        <p><b>Reason: </b>{!! $buyerCounterOffer->rejected_reason ?? 'The reason for the Job rejection will come here!' !!}</p>
    </div>
{{-- @elseif($buyerCounterOffer->userBid->seller_offer_responce == 1)
    <div class="col-12 shadow mt-3 alert alert-danger" role="alert">
        <h5>{{ $buyerCounterOffer->userBid->username }} rejected offer</h5>
        <p><b>Reason: </b>{{ $buyerCounterOffer->userBid->seller_offer_responce_text ?? 'The reason for the Job rejection will come here!' }}</p>
    </div> --}}
@endif

<div class="card shadow mt-2">
    <div class="card-header pt-3">
        <p class="float-end me-3">
            <span class="text-secondary">Status:</span>
            <span class="fw-bold text-{{ $status == 1 ? 'green' : ($status == 2 ? 'danger' : 'warning') }}">
                @if($status == 1)
                    <i class="fa-regular fa-circle-check"></i> Accepted
                @elseif($status == 2)
                    <i class="fa-regular fa-circle-xmark"></i> Rejected
                @else
                    <i class="fa-regular fa-clock me-1 bg-light"></i> Pending
                @endif
            </span>
        </p>
        <h5>Offered Job Details</h5>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-8">
                <p><a class="links" href="{{route('job.details', ['id' => encrypt($buyerCounterOffer->job_id)])}}" target="_blank"><h5>{{ $buyerCounterOffer->job->title }}</h5></a></p>
                <p><b>Job Type:</b> {{ ucfirst($buyerCounterOffer->type) }}</p> <!-- Show Job Type -->
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-header bg-lsgreen">Amount</div>
                    <div class="card-body">
                        <h4 class="text-green">
                            ${{ number_format($totalAmount, 2) }}{{ $buyerCounterOffer->job->budgettype === 'hourly' ? ' /hr' : '' }}
                        </h4>
                    </div>
                </div>
            </div>
        </div><!--nested row end-->
        <div class="row">
            <div class="col-12">
                <p><b>Description:</b> {!! $buyerCounterOffer->description !!}</p>
            </div>
        </div>
        @if($buyerCounterOffer->job->budgettype !== 'hourly' && in_array($buyerCounterOffer->type, ['bymilestone', 'byproject']))
            <div class="row">
                <div class="col">
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th scope="col" colspan="4">Milestone Title</th>
                                <th scope="col" colspan="4">Due Date</th>
                                <th scope="col" colspan="4" class="text-end">Amount</th>

                                {{-- <th scope="col">Due Days</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($milestones as $milestone)
                                <tr>
                                    <td colspan="4">{{ $milestone->title }}</td>
                                    <td colspan="4">
                                    @if(is_numeric($milestone->due_date))
                                        {{ $milestone->due_date }}
                                    @else
                                        {{ \Carbon\Carbon::parse($milestone->due_date)->format('d-M-Y') }}
                                    @endif
                                </td>
                                    {{-- <td colspan="4">  {{ \Carbon\Carbon::parse($milestone->due_date)->format('d-M-Y') }}</td> --}}
                                    <td colspan="4" class="text-end">${{ number_format($milestone->amount, 2) }}</td>

                                    {{-- <td>{{ $milestone->due_days }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
        <div class="row mt-4">
            <div class="col-12">
                @if ($status == 1)
                    <!-- Workroom Button -->
                    <a href='{{ url('/user/buyer/workspace/overview/'.encrypt($buyerCounterOffer->job_id)) }}' class="btn btn-primary me-2" title="Go to Workroom">
                        <i class="fas fa-briefcase"></i> Workroom
                    </a>
                @endif
                <!-- Offer List Button -->
                <a href="{{ url('user/buyer/hire/offered-jobs') }}" class="btn btn-secondary me-2" title="View Offer List">
                    <i class="fas fa-list"></i> Offer List
                </a>
            </div>
        </div>
    </div> <!--card Body End-->
</div> <!--card End-->
@endsection
