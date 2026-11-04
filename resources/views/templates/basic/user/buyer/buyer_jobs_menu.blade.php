<div class="row pt-lg-2 pb-lg-3 py-2">
    {{-- responsive design for jobs  --}}
    <div class="col-lg-4">
        <div class="dropdown float-end pe-md-5 d-block d-lg-none fs-14">
            <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                All Jobs </button>
            <ul class="dropdown-menu fs-14">
                <!-- <li><a class="dropdown-item" href="#">Open Jobs</a></li>-->
                <li><a class="dropdown-item" href="#">Closed Jobs</a></li>
                <li><a class="dropdown-item" href="#">Offer Jobs</a></li>
                <li><a class="dropdown-item" href="#">Active Jobs</a></li>
                <li><a class="dropdown-item" href="#">Completed Jobs</a></li>
            </ul>
        </div>
        {{--  <h5 id="page-title" class="d-none"> {{ $pageTitle }}</h5>  --}}
        <p class="mb-0 text-bc mt-1 fs-14">
            <a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
                <i class="fa-solid fa-house fs-14"></i>
            </a>
            {{--  <i class="fa-solid fa-chevron-right fs-12"></i>
            <span>
                Posted Jobs & Bids
            </span>  --}}
            <i class="fa-solid fa-chevron-right fs-12"></i>
            <span id="breadcrumb-title">
                Open Jobs
            </span>
        </p>

    </div>
    {{--  <div class="col-md-3">
        <p class="mb-0 text-bc mt-1 fs-14">
            <a class="text-3gray" href="http://wiser2.test/user/buyer/dashboard">
                <i class="fa-solid fa-house fs-14"></i> <!-- Breadcrumb with link -->
            </a>
            <i class="fa-solid fa-chevron-right fs-12"></i>
            <span id="breadcrumb-title">
                Manage Jobs <!-- Last breadcrumb without link -->
            </span>
        </p>
    </div>  --}}
    <div class="col-md-7 col-lg-8 ps-md-5 pe-0 d-none d-lg-block">
        <div class="float-end pe-3">
            <button id="btn-open-job" type="button"
                class="btn btn-sm {{ $pageTitle == 'All Jobs' ? 'btn-success bg-green' : 'btn-outline-secondary' }}">All
                Jobs</button>
            <button id="btn-offers" type="button"
                class="btn btn-sm {{ $pageTitle == 'Offered Jobs' ? 'btn-success bg-green' : 'btn-outline-secondary' }} ms-2">Offered
                Jobs</button>
            <button id="btn-active-jobs" type="button"
                class="btn btn-sm {{ $pageTitle == 'Active Jobs' ? 'btn-success bg-green' : 'btn-outline-secondary' }} ms-2">Active
                Jobs</button>
            <button id="btn-completed-jobs" type="button"
                class="btn btn-sm {{ $pageTitle == 'Completed Jobs' ? 'btn-success bg-green' : 'btn-outline-secondary' }} ms-2">Completed
                Jobs</button>
            <button id="btn-closed-jobs" type="button"
                class="btn btn-sm {{ $pageTitle == 'Closed Jobs' ? 'btn-success bg-green' : 'btn-outline-secondary' }} ms-2">Closed
                Jobs</button>
        </div>
    </div>
</div>
