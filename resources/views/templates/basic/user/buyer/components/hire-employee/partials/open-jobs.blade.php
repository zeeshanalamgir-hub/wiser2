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
<!-- Iterate through each job and create a card -->
@if ($jobs->isNotEmpty())
@foreach ($jobs as $job)
    <div class="row {{ $loop->first ? '' : 'mt-2' }}">
        <div class="col-12">
            <div class="card lg-34 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
                <h6>{{ $job->title }}</h6>
                <div class="row fs-14">
                    <div class="col-lg-6 mb-3 mt-2 mb-md-0">
                        <ul class="nav">
                            <li class="nav-item"><span class="text-secondary">Job Type:</span> <span class="me-3 fw-bold">{{ $job->budgettype }}</span></li>
                            <li class="nav-item"><span class="text-secondary">Bids:</span> <span class="me-3 fw-bold">{{ $job->user_biding_count }}</span></li>
                            <li class="nav-item"><span class="text-secondary">Created on:</span> <span class="me-3 fw-bold">{{ $job->created_date }}</span></li>
                            {{-- <li class="nav-item"><span class="text-secondary">Status:</span> <span class="fw-bold">{{ $job->status_text }}</span></li> --}}
                        </ul>
                    </div>
                    <div class="col-lg-6 ps-lg-5 mt-md-3 mt-lg-0 text-end">
                        <button class="btn btn-sm bg-lsgreen border text-success" onclick="location.href='{{ url('/job/details/'.$job->encrypted_job_id) }}';" title="View Details"><i class="far fa-eye"></i> View</button>

                        @if ($job->user_biding_count == 0)
                            <button class="btn btn-sm bg-lsgreen border text-primary mx-2" onclick="location.href='{{ url('/user/buyer/job/edit/'.$job->encrypted_job_id) }}';" title="Edit"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
                        @else
                            <button class="btn btn-sm bg-lsgreen border text-secondary mx-2" onclick="location.href='{{ url('/user/buyer/hire/employees/bids/'.$job->encrypted_job_id) }}';" title="Proposal">
                                <i class="fa-regular fa-circle-check"></i> Bids
                            </button>
                        @endif
                        {{-- <button class="btn btn-sm bg-lsgreen border text-secondary mx-2" onclick="location.href='{{route('professioan.hire', [ encrypt($user->id)])}}';" title="Proposal">
                            <i class="fa-regular fa-circle-check"></i> Invite Freelancers
                        </button> --}}

                        <button class="btn btn-sm bg-lsgreen border text-danger"
                            title="{{ $job->status == 2 ? 'Closed' : 'Close Job' }}"
                            @unless($job->status == 2)
                                onclick="if(confirm('Are you sure you want to close this job?')) { window.location.href='{{ url('user/buyer/close/job/' . $job->encrypted_job_id) }}'; }"
                            @endunless
                        >
                             <i class="fa-regular fa-circle-xmark"></i> {{ $job->status == 2 ? 'Closed' : 'Close' }}
                        </button>
                        {{--  @if($job->status != 2)
                            <button class="btn btn-sm bg-lsgreen border text-danger"
                                onclick="if(confirm('Are you sure you want to close this job?')) { window.location.href='{{ url('user/buyer/close/job/' . $job->encrypted_job_id) }}'; }"
                                title="Close Job">
                                <i class="fa-regular fa-circle-xmark"></i> Close
                            </button>
                        @else
                            <button class="btn btn-sm bg-lsgreen border text-danger"
                                title="Close Job">
                                <i class="fa-regular fa-circle-xmark"></i> Closed
                            </button>
                        @endif  --}}
                        {{--  <button class="btn btn-sm bg-lsgreen border text-danger" onclick="confirm('Are you sure you want to close this job?') && location.href='{{ url('Jobs/closeJob/'.$job->id) }}';" title="Close Job"><i class="fa-regular fa-circle-xmark"></i> Close</button>  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
{{ $jobs->links('pagination::bootstrap-4') }}
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

