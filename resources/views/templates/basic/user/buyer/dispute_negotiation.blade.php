<div class="col-xl-8 order-2 order-xl-1 mt-4 mt-xl-0">
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Negotiation </div>
        <div class="card-body">
            @foreach ($negotiations as $negotiation)
                <div class="row">
                    <div class="col-sm-7 col-lg-8">
                        <div class="d-flex">
                            @if ($negotiation->send_by === 'buyer')
                                @if(!is_null($booking->buyer->image))
                                    <img src="{{ Storage::url($booking->buyer->image) }}"
                                        alt="@lang('Profile Image')" class="rounded-circle me-2" width="45" height="45" style="object-fit:cover">
                                @else
                                    <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                        style="height:50px;width:50px;font-size:20px">
                                        {{ strtoupper(substr($booking->buyer->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($booking->buyer->lastname ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                            @elseif ($negotiation->send_by === 'seller')
                                @if(!is_null($booking->seller->image))
                                    <img src="{{ Storage::url($booking->seller->image) }}"
                                        alt="@lang('Profile Image')" class="rounded-circle me-2" width="45" height="45" style="object-fit:cover">
                                @else
                                    <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                        style="height:50px;width:50px;font-size:20px">
                                        {{ strtoupper(substr($booking->seller->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($booking->seller->lastname ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                            @endif
                            <div style="padding-left:10px">
                                <p class="fs-14 text-3gray d-sm-none mb-0">
                                    <i class="far fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($negotiation->created_at)->format('d M, Y') }}
                                </p>
                                <h6 class="mb-0 fw-bold">
                                    @if ($negotiation->send_by === 'buyer')
                                        {{ $disputedetail->seller->fullname }}
                                    @elseif ($negotiation->send_by === 'seller')
                                        {{ $disputedetail->buyer->fullname }}
                                    @endif

                                </h6>
                                <p class="fs-14">
                                    {{ $negotiation->seller_message }}
                                </p>
                                <span>Seller:<b>${{ $negotiation->seller_amount }}</b> </span>
                                <span class="ms-xl-4 ms-lg-3 ms-2">Buyer:<b>${{ $negotiation->buyer_amount }}</b></span>
                                <p class="mt-1 d-sm-none"><span
                                        class="fs-6 bg-danger text-white rounded px-1">Rejected</span></p>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 col-lg-4 pe-lg-0 d-none d-sm-block">
                        <p class="fs-14 text-3gray"><i
                                class="far fa-calendar"></i>{{ \Carbon\Carbon::parse($negotiation->created_at)->format('d M, Y') }}
                        </p>

                        <p>
                            @if ($negotiation->send_by == 'seller')
                                @if ($negotiation->status == 0)
                                    <a data-target="#reqmsacceptModal" data-work_id="{{ $negotiation->id }}"
                                        href="javascript:void(0);"
                                        class="reqmsacceptBtn btn rounded bg-success text-light fs-sm ft-medium mr-2">@lang('Accept')</a>
                                    <a data-target="#reqmsrejectModal" data-work_id="{{ $negotiation->id }}"
                                        href="javascript:void(0);"
                                        class="reqmsrejectBtn btn rounded bg-danger text-light fs-sm ft-medium mr-2">@lang('Deny')</a>
                                @elseif($negotiation->status == 1)
                                    <span class="bg-success text-white py-1 px-2 fs-14 rounded">@lang('Accepted')</span>
                                @elseif($negotiation->status == 3)
                                    <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('Rejected')</span>
                                @endif
                            @elseif($negotiation->send_by == 'buyer')
                                {{-- @if ($disputedetail->status == 0 || $disputedetail->status == 1) --}}
                                @if ($negotiation->status == 0)
                                    <span class="bg-warning text-white py-1 px-2 fs-14 rounded">@lang('Pending')</span>
                                @elseif ($negotiation->status == 1)
                                    <span class="bg-green text-white py-1 px-2 fs-14 rounded">@lang('Accepted')</span>
                                @elseif($negotiation->status == 2)
                                    <span
                                        class="bg-danger text-white py-1 px-2 fs-10 rounded">@lang('Dispute submitted to admin')</span>
                                @elseif($negotiation->status == 3)
                                    <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('Rejected')</span>
                                @else
                                    <a data-target="#reqmsacceptModal" data-work_id="{{ $negotiation->id }}"
                                        href="javascript:void(0);"
                                        class="reqmsacceptBtn btn rounded bg-success text-light fs-sm ft-medium mr-2">@lang('Accept')</a>
                                    <a data-target="#reqmsrejectModal" data-work_id="{{ $negotiation->id }}"
                                        href="javascript:void(0);"
                                        class="reqmsrejectBtn btn rounded bg-danger text-light fs-sm ft-medium mr-2">@lang('Deny')</a>
                                @endif
                                {{-- @endif --}}
                            @endif

                        </p>
                    </div>
                </div>
                <hr>
            @endforeach
            @if ($disputedetail->status == 0 && $disputedetail->disputeNegotiations->last()?->status > 2)
                <form id="disputeFormSendOffer" class="row g-2 mt-5 rounded bg-lsgreen p-2"
                    action="{{ route('user.buyer.dispute.offer.work.bid') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dispute_id" value="{{ $disputedetail->id }}">
                    <!-- Make sure to set the dispute ID -->

                    <!-- Message Field -->
                    <div class="col-8 col-sm-9 col-md-10 col-xl-9 col-xxl-10">
                        <textarea type="text" name="message" class="form-control" rows="1" placeholder="Message Here"
                            required></textarea>
                    </div>

                    <!-- Attachment (optional) -->
                    <div class="col-4 col-sm-3 col-md-2 col-xl-3 col-xxl-2 pt-1 pt-sm-0">
                        <input class="d-none" type="file" id="actual-btn" name="attachment">
                        <label for="actual-btn"><i class="fas fa-paperclip text-secondary px-3 fs-sm-5"
                                style="cursor: -webkit-grab; cursor: grab;"></i></label>
                        <button type="submit" class="btn btn-outline-secondary py-sm-1 px-sm-2 py-0 p-1">
                            <i class="fas fa-paper-plane fs-sm-5 fs-14"></i>
                        </button>
                    </div>

                    <!-- Seller and Buyer Amounts -->
                    <div class="row mt-3">
                        <label for="inputSellerAmount" class="col-8 col-md-3 col-xl-4 col-xxl-3 col-form-label pe-0">Seller
                            Amount $</label>
                        <div class="col-4 col-md-2 px-0 pe-xxl-4">
                            <input type="text" name="seller_amount" class="form-control" id="inputSellerAmount" required>
                        </div>

                        <label for="inputBuyerAmount"
                            class="col-8 col-md-3 col-xl-4 ms-md-3 ms-xl-0 ms-xxl-4 col-xxl-3 col-form-label pe-0">Buyer
                            Amount $</label>
                        <div class="col-4 col-md-2 px-0 mt-1 mt-md-0 pe-xxl-4 pe-xl-3">
                            <input type="text" name="buyer_amount" class="form-control" id="inputBuyerAmount" required>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@include("templates.basic.user.buyer.dispute_results")
{{-- <div class="col-md-5 col-xl-4 order-1 order-md-2">
    <div class="card shadow-sm">
        <div class="card-header bg-lsgreen fw-bold"> Results </div>
        <div class="card-body p-4">
            <p class="mb-2">Dispute amount:<span class="fs-6 fw-bold"> ${{ $disputedetail->dispute_amount }}</span></p>
            @if (isset($settledDispute))
            <p class="mb-0">Seller:<span class="fs-6 fw-bold"> ${{ $settledDispute->seller_amount }}</span></p>
            <p class="mb-2">Buyer:<span class="fs-6 fw-bold"> ${{ $settledDispute->buyer_amount }}</span></p>
            @endif
            @if ($disputedetail->status == 0)
            <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('Not Settled')</span>
            @elseif($disputedetail->status == 1)
            <span class="bg-green text-white py-1 px-2 fs-14 rounded">@lang('Dispute Settled')</span>
            @elseif($disputedetail->status == 2)
            <span class="bg-danger text-white py-1 px-2 fs-14 rounded">@lang('dispute submitted to admin')</span>
            @endif
        </div>
    </div>
</div> --}}

<div class="modal fade" id="reqmsrejectModal" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true"
    role="dialog">
    <div class="modal-dialog" style="max-width:350px">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="modalRejectLabel">@lang('Decline Offer')</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6">@lang('Are you sure to decline this offer?')</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('user.buyer.dispute.offer.work.bid.decline') }}">
                    @csrf
                    <input type="hidden" name="work_id">
                    <input type="hidden" name="confirm" value="approved">
                    <button type="submit" class="btn btn-sm btn-danger">@lang('Yes')</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">@lang('No')</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reqmsacceptModal" tabindex="-1" aria-labelledby="modalAcceptLabel" aria-hidden="true"
    role="dialog">
    <div class="modal-dialog" style="max-width:350px">
        <div class="modal-content">
            <div class="modal-header bg-green text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="modalAcceptLabel">@lang('Accept Offer')</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6">@lang('Are you sure to accept this offer?')</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('user.buyer.dispute.offer.work.bid.accept') }}">
                    @csrf
                    <input type="hidden" name="work_id">
                    <input type="hidden" name="confirm" value="approved">
                    <button type="submit" class="btn btn-sm btn-success">@lang('Yes')</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">@lang('No')</button>
                </form>
            </div>
        </div>
    </div>
</div>
