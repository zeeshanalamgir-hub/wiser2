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
        <div>
            <div class="row mb-2">
                <div class="col-12">
                    <div class="card lg-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
                        <h6>{{ $job->job->title ?? 'N/A' }} </h6>
                        <div class="row fs-14">
                            <div class="col-lg-8 mb-3 mt-2 mb-md-0">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <span class="text-secondary">Job Type:</span>
                                        <span class="me-3 fw-bold">{{ ucfirst($job->job->budgettype ?? 'N/A') }}</span>
                                    </li>
                                    <li class="nav-item">
                                        <span class="text-secondary">Offer amount:</span>
                                        <span class="me-3 fw-bold">
                                            ${{ number_format((float)$job->offer_amount, 2) }}
                                        </span> <!-- Convert offer_amount to float before formatting -->
                                    </li>
                                    <li class="nav-item">
                                        <span class="text-secondary">Submitted on:</span>
                                        <span class="me-3 fw-bold">{{ \Carbon\Carbon::parse($job->created_date)->format('d-M-Y') }}</span>
                                    </li>
                                    <li class="nav-item"><span class="text-secondary">Status:</span>
                                        <span class="fw-bold
                                            @if($job->status == '0') text-warning
                                            @elseif($job->status == '2') text-danger
                                            @elseif($job->status == '1') text-success
                                            @endif">
                                            <i class="fa-regular
                                                @if($job->status == '0') fa-clock bg-light
                                                @elseif($job->status == '2') fa-circle-xmark
                                                @elseif($job->status == '1') fa-circle-check
                                                @endif me-1"></i>
                                            {{ $job->status_label }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 ps-lg-5 mt-md-3 mt-lg-0 text-end">
                                <button class="btn btn-sm bg-lsgreen border text-primary mx-2"
                                    onclick="location.href='{{ route('user.seller.workroom', ['encryptedId' => encrypt($job->job_id)]) }}';"
                                    title="Workroom">
                                    <i class="fa-solid fa-building"></i> Workroom
                                </button>
                            </div>
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
