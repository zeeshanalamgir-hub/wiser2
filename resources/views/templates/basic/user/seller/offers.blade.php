@if ($jobs->isEmpty())
    <p>No jobs found.</p>
@else
    @foreach ($jobs as $job)
        <div class="mt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card lg-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
                        <h6>{{ $job->job->title ?? 'N/A' }} </h6>
                        <div class="row fs-14">
                            <div class="col-lg-7 mb-3 mt-2 mb-md-0">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <span class="text-secondary">Job Type:</span>
                                        <span class="me-3 fw-bold">{{ ucfirst($job->job->jobtype ?? 'N/A') }}</span>
                                    </li>
                                    <li class="nav-item">
                                        <span class="text-secondary">Bid Amount:</span>
                                        <span class="me-3 fw-bold">${{ $job->totalOfferAmount ?? 'N/A' }}</span>
                                    </li>
                                    <li class="nav-item">
                                        <span class="text-secondary">Submitted on:</span>
                                        <span
                                            class="me-3 fw-bold">{{ \Carbon\Carbon::parse($job->created_date)->format('d-M-Y') }}</span>
                                    </li>

                                    @if ($job->seller_offer_responce == '1' || $job->seller_offer_responce == '2' || $job->seller_offer_responce == '3')
                                        <li class="nav-item">
                                            <span
                                                class="fw-bold
                                                @if ($job->seller_offer_responce == '1') text-danger
                                                @elseif($job->seller_offer_responce == '2' || $job->seller_offer_responce == '3') text-success @endif">
                                                <i
                                                    class="fa-regular
                                                    @if ($job->seller_offer_responce == '1') fa-circle-xmark
                                                    @elseif($job->seller_offer_responce == '2') fa-circle-check @endif me-1"></i>
                                                {{ ucfirst($job->seller_offer_responce_text) ?? 'N/A' }}
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            @if ($job->seller_offer_responce == '3')
                                <div class="col-lg-5 ps-lg-5 mt-md-3 mt-lg-0">
                                    <button class="btn btn-sm bg-lsgreen border text-primary"
                                        onclick="location.href='{{ route('job.offer.detail', ['id' => $job->encUserBidId]) }}';"
                                        title="View Offer">
                                        <i class="far fa-eye"></i> View Offer
                                    </button>
                                    <button class="btn btn-sm bg-lsgreen border text-success"
                                        onclick="location.href='{{ route('user.offers.accept', ['encUserBidId' => $job->encUserBidId]) }}';"
                                        title="Accept Offer">
                                        <i class=""></i> Accept Offer
                                    </button>
                                    <button class="btn btn-sm bg-lsgreen border text-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalSendBid"
                                        onclick="location.href='{{ route('user.offers.accept', ['encUserBidId' => $job->encUserBidId]) }}';"
                                        title="Reject Offer">
                                        <i class=""></i> Reject Offer
                                    </button>
                                </div>
                            @endif
                            @if ($job->seller_offer_responce == '2' || $job->seller_offer_responce == '1')
                                <div class="col-lg-5 ps-lg-5 mt-md-3 mt-lg-0">
                                    <button class="btn btn-sm bg-lsgreen border text-primary"
                                        onclick="location.href='{{ route('job.offer.detail', ['id' => $job->encUserBidId]) }}';"
                                        title="Offer Details">
                                        <i class="fa-regular fa-handshake"></i> Offer Detail
                                    </button>
                                    @if ($job->seller_offer_responce == '2')
                                        <button class="btn btn-sm bg-lsgreen border text-primary mx-2"
                                            onclick="location.href='{{ route('user.seller.workroom', ['encryptedId' => $job->encUserBidId]) }}';"
                                            title="Workroom">
                                            <i class="fa-solid fa-building"></i> Workroom
                                        </button>
                                     @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
@endif
<div class="modal fade" id="modalSendBid" tabindex="-1" aria-labelledby="modalSendBidLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSendBidLabel">Decline Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.offers.decline', ['encUserBidId' => $job->encUserBidId]) }}"
                    method="POST">
                    @csrf
                    <div class="cover-letter-details-outer-76755">
                        <div class="letter-section-7895">
                            <div class="poppins-semibold ffs-12">Decline Reason</div>
                            <textarea rows="7" id="declineReason" name="decline_reason" class="afacad-regular ffs-12 p-2" required
                                placeholder="Enter your decline reason here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Decline Offer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
