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
                        <h6>{{ $job->job->title ?? 'N/A' }} </h6>
                        <div class="row fs-14">
                            <div class="col-lg-7 mb-3 mt-2 mb-md-0">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <span class="text-secondary">Job Type:</span>
                                        <span class="me-3 fw-bold">{{ ucfirst($job->job->budgettype ?? 'N/A') }}</span>
                                    </li>
                                    <li class="nav-item">
                                        <span class="text-secondary">Submitted on:</span>
                                        <span class="me-3 fw-bold">{{ \Carbon\Carbon::parse($job->created_date)->format('d-M-Y') }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-5 ps-lg-5 mt-md-3 mt-lg-0 text-end">
                                <button class="btn btn-sm bg-lsgreen border text-primary mx-2"
                                    onclick="location.href='{{ route('user.seller.workroom', ['encryptedId' => encrypt($job?->job?->id)]) }}'" style="font-size: 12px;"
                                    title="Workroom">
                                    <i class="fa-solid fa-building"></i> Workroom
                                </button>
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
