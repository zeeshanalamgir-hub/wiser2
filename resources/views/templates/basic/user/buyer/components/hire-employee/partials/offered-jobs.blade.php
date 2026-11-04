<div class="row pb-2" style="display:flex;justify-content:flex-end">
    <div class="col-5">
        <form class="d-flex float-end" role="search" style="width:100%">
            <input class="form-control bg-white float-end bg-lgreen" name="search" value="{{ request()?->search }}" style="height:30px; font-size: 14px;" type="search" placeholder="Search Jobs" aria-label="Search">
            <button class="btn btn-sm ms-1 btn-success bg-3gray border-0 " type="submit" style="height: 30px;">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>
</div>
@if ($offers->isNotEmpty())
    @foreach ($offers as $offer)
        <div class="row {{ $loop->first ? '' : 'mt-2' }}">
            <div class="col-12">
                <div class="card lg-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
                    <h6>{{ $offer->job_title }}</h6> <!-- Dynamically displaying the job title -->
                    <div class="row fs-14">
                        <div class="col-md-8 col-lg-8 mb-3 mt-2 mb-md-0">
                            <ul class="nav">
                                <li class="nav-item">
                                    <span class="text-secondary">Job Type:</span>
                                    <span class="me-3 fw-bold"> {{ $offer->job_type  }}
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="text-secondary">Offer amount:</span>
                                    <span class="me-3 fw-bold">
                                        ${{ number_format((float)$offer->offer_amount, 2) }}
                                    </span> <!-- Convert offer_amount to float before formatting -->
                                </li>
                                <li class="nav-item">
                                    <span class="text-secondary">Created on:</span>
                                    <span class="me-3 fw-bold">{{ $offer->offer_date ?? 'N/A' }}</span> <!-- Safeguard against null created_date -->
                                </li>
                                <li class="nav-item"><span class="text-secondary"></span>
                                    <span class="fw-bold
                                        @if($offer->status_label == 'Pending') text-warning
                                        @elseif($offer->status_label == 'Rejected') text-danger
                                        @elseif($offer->status_label == 'Accepted') text-success
                                        @endif">
                                        <i class="fa-regular
                                            @if($offer->status_label == 'Pending') fa-clock bg-light
                                            @elseif($offer->status_label == 'Rejected') fa-circle-xmark
                                            @elseif($offer->status_label == 'Accepted') fa-circle-check
                                            @endif me-1"></i>
                                       {{ $offer->status_label }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        {{-- Action buttons --}}
                        <div class="col-md-4 col-lg-4 ps-md-5 mt-sm-3 mt-md-0 text-end">
                            <button class="btn btn-sm bg-lsgreen border text-success" onclick="location.href='{{ url('user/buyer/hire/offered-jobs/details/'.$offer->buyer_id) }}';" title="View Details"><i class="far fa-eye"></i> View</button>

                            @if($offer->status_label == 'Accepted')
                                <button class="btn btn-sm bg-lsgreen border text-primary mx-2" onclick="location.href='{{ url('/user/buyer/workspace/overview/'.encrypt($offer->job_id)) }}';" title="Workroom"><i class="fa-solid fa-building"></i> Workroom</button>
                            @elseif($offer->status_label == 'Pending')
                                <button class="cancelbidBtn btn btn-sm bg-lsgreen border text-danger mx-2" data-bs-toggle="modal" data-bs-target="#cancelbidModal" data-cancelbid_id="{{$offer->id}}"   title="Cancel"><i class="fa-regular fa-circle-check"></i> Cancel</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Pagination links -->
    <div class="mt-4">
        {{ $offers->links('pagination::bootstrap-4') }}
    </div>
@else
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card lg-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
                    <p class="text-secondary">No offers found.</p>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- Bid Cancel Modal -->
<div class="modal fade" id="cancelbidModal" tabindex="-1" aria-labelledby="cancelBidModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h1 class="modal-title fs-5">@lang('Cancel Bid')</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="mb-3">
                    <p class="fs-sm text-muted">@lang('Are you sure you want to cancel this bid?')</p>
                </div>
                <form method="POST" action="{{ route('user.buyer.cancel.bid') }}">
                    @csrf
                    <input type="hidden" name="cancelbid_id">
                    <input type="hidden" name="confirm" value="cancel">

                    <!-- Cancel Reason Textarea -->
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">@lang('Cancel Reason')</label>
                        <textarea name="cancenreason" id="cancelReason" class="form-control" rows="3" placeholder="@lang('Enter your reason')" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger text-white ft-medium">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Bid Cancel Modal -->
