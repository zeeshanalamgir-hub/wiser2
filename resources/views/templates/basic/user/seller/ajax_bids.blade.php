@if ($data->isEmpty())
<div>
    <div class="row">
        <div class="col-12">
            <div class="card lg-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
                <p class="text-secondary">No offers found.</p>
            </div>
        </div>
    </div>
</div>
@else
    @foreach ($data as $job)
        <div class="row {{ $loop->first ? '' : 'mt-2' }}">
            <div class="col-12">
                <div class="card lg-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
                    <h6>{{ $job?->job?->title ?? 'N/A' }} </h6>
                    <div class="row fs-14">
                        <div class="col-lg-7 mb-3 mt-2 mb-md-0">
                            <ul class="nav">
                                <li class="nav-item">
                                    <span class="text-secondary">Job Type:</span>
                                    <span class="me-3 fw-bold">{{ ucfirst($job?->job?->job_type) ?? 'N/A' }}
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="text-secondary">Bid Amount:</span>
                                    <span class="me-3 fw-bold">${{ $job?->bid_price ?? 'N/A' }}</span>
                                </li>
                                <li class="nav-item">
                                    <span class="text-secondary">Submitted on:</span>
                                    <span class="me-3 fw-bold">{{ $job?->created_date ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="fw-bold
                                        @if($job?->buyerCounterOffer?->status == '0'|| is_null($job?->buyerCounterOffer?->status)) text-warning
                                        @elseif($job?->buyerCounterOffer?->status == '2') text-danger
                                        @elseif($job?->buyerCounterOffer?->status == '1') text-success
                                        @endif">
                                        <i class="fa-regular
                                            @if($job?->buyerCounterOffer?->status == '0' || is_null($job?->buyerCounterOffer?->status)) fa-clock bg-light
                                            @elseif($job?->buyerCounterOffer?->status == '2') fa-circle-xmark
                                            @elseif($job?->buyerCounterOffer?->status == '1') fa-circle-check
                                            @endif me-1">
                                        </i>
                                        @php
                                            if($job?->buyerCounterOffer?->status == 0){
                                                $job_status = 'Pending';
                                            }else if($job?->buyerCounterOffer?->status == 1){
                                                $job_status = 'Accepted';
                                            }else if($job?->buyerCounterOffer?->status == 2){
                                                $job_status = 'Offer Rejected';
                                            }else {
                                                $job_status = 'Unknown';
                                            }
                                        @endphp
                                        {{ $job_status }}
                                    </span>
                                </li>
                                {{-- <li class="nav-item">
                                    <span class="text-secondary">Status:</span>
                                    <span
                                        class="fw-bold
                                        @if ($job->seller_offer_responce == '0') text-warning
                                        @elseif($job->seller_offer_responce == '1' || $job->seller_offer_responce == '5') text-danger
                                        @elseif($job->seller_offer_responce == '2' ) text-success @endif">
                                        <i
                                            class="fa-regular
                                            @if ($job->seller_offer_responce == '0') fa-clock bg-light
                                            @elseif($job->seller_offer_responce == '1') fa-circle-xmark
                                            @elseif($job->seller_offer_responce == '2') fa-circle-check @endif me-1"></i>
                                        {{ ucfirst($job->seller_offer_responce_text) ?? 'N/A' }}
                                    </span>
                                </li> --}}
                            </ul>
                        </div>
                        <div class="col-lg-5 ps-lg-5 mt-md-3 mt-lg-0 text-end">
                            @if ($job->seller_offer_responce == 0)
                                <!-- Status 0: Show View Job, Bid Detail, and Edit Bid Pending-->
                                <button class="btn btn-sm bg-lsgreen border text-success"
                                    onclick="location.href='{{ url('/job/details/' . $job->encrypted_job_id) }}';"
                                    title="View Details">
                                    <i class="far fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm bg-lsgreen border text-primary mx-2"
                                    onclick="location.href='{{ url('/user/seller/job/list/ajax/bids/details', ['job_id' => $job->encrypted_job_id]) }}';"
                                    title="Bid Details">
                                    <i class="fa-regular fa-circle-check"></i> Bid Detail
                                </button>
                                <button class="btn btn-sm bg-lsgreen border text-danger"
                                    onclick="location.href='{{ $job->job->budgettype === 'fixed' ? route('job.bid.fixed', $job->encrypted_job_id) : route('job.bid.hourly', $job->encrypted_job_id) }}';"
                                    title="Edit Bid">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </button>
                            @elseif ($job->seller_offer_responce == 2)
                                <!-- Status 2: Show View Job, Bid Detail, and Offer Detail Accepted-->
                                <button class="btn btn-sm bg-lsgreen border text-success"
                                    onclick="location.href='{{ url('/job/details/' . $job->encrypted_job_id) }}';"
                                    title="View Details">
                                    <i class="far fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm bg-lsgreen border text-success"
                                    onclick="location.href='{{ route('job.offer.detail', ['id' => encrypt($job?->buyerCounterOffer?->id)]) }}';"
                                    title="View Details">
                                    <i class="far fa-eye"></i> Offer
                                </button>
                                @if ($job->buyerCounterOffer?->status != 2)
                                    <button class="btn btn-sm bg-lsgreen border text-success"
                                        onclick="location.href='{{ url('/user/seller/job/workroom/' . $job->encrypted_job_id) }}';"
                                        title="View Details">
                                        <i class="far fa-eye"></i> Contract
                                    </button>
                                @endif
                            @elseif ($job->seller_offer_responce == 3 || $job->seller_offer_responce == 1)
                                <!-- Offer received -->
                                <button class="btn btn-sm bg-lsgreen border text-success"
                                    onclick="location.href='{{ url('/job/details/' . $job->encrypted_job_id) }}';"
                                    title="View Details">
                                    <i class="far fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm bg-lsgreen border text-primary mx-2"
                                    onclick="location.href='{{ url('/user/seller/job/list/ajax/bids/details', ['job_id' => $job->encrypted_job_id]) }}';"
                                    title="Bid Details">
                                    <i class="fa-regular fa-circle-check"></i> Bid Detail
                                </button>
                                <button class="btn btn-sm bg-lsgreen border text-primary"
                                    onclick="location.href='{{ route('job.offer.detail', ['id' => encrypt($job?->buyerCounterOffer?->id)]) }}';"
                                    title="Offer Details">
                                    <i class="fa-regular fa-handshake"></i> Offer Detail
                                </button>
                            @else
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $data->links() }}
    </div>
@endif
