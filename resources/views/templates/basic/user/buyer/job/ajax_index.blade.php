<div class="row">
    <div class="col-12">
        <h5 class="text-dgreen2 mt-2">Manage Jobs</h5>
        {{-- <h5 class="text-dgreen2 ms-2">Manage Jobs</h5> --}}
        <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
            @forelse($jobs as $job)
                <h6 class="mb-1 mt-2"><a href="#" class="links-c2">{{ $job->title }}</a></h6>
                <ul class="nav border-bottom pb-3 fs-14 d-flex flex-wrap justify-content-around row">
                    <li class="nav-item col-12 col-md-2 col-lg-2"><span class="text-dgreen2 fw-bold">Job Type:</span>
                        {{ $job->job_type }} </li>
                    <li class="nav-item col-12 col-md-2 col-lg-2"><span class="text-secondary"> <i
                                class="fa-solid fa-calendar-days"></i> {{ $job->created_date }}</li>
                    <li class="nav-item col-12 col-md-1 col-lg-1"><span class="text-dgreen2 fw-bold"> Bids:</span>
                        {{ $job->bid_count }}</li>
                    <li class="nav-item col-12 col-md-3 col-lg-3"><span class="text-dgreen2 fw-bold"> Status:</span>
                        {{ $job->status_text }} </li>
                    <li class="nav-item col-12 col-md-3 col-lg-3 text-end">
                        <a href="{{ route('job.details', $job->encrypted_job_id) }}"
                            class="links-c2 border p-1 rounded"> <i class="fa-regular fa-eye text-green"></i> View </a>
                        @if ($job->status == 0)
                            <a href="{{ route('user.job.edit', ['encryptedJobId' => $job->encrypted_job_id]) }}"
                                class="mx-2 links-c2 border p-1 rounded"><i
                                    class="fa-solid fa-pen-to-square text-dgreen2"></i> Edit </a>
                        @endif
                        {{-- <a href="#" class="links-c2 border p-1 rounded"> <i class="fa-solid fa-arrow-up-right-from-square text-primary"></i> Publish </a> --}}
                    </li>
                </ul>

            @empty
                <h7 class="text-secondary">No Entries to display</h7>
            @endforelse
        </div>
    </div>
    <!-- Pagination -->
    {{ $jobs->links() }}
</div>
