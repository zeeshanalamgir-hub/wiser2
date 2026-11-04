<div class="row pb-lg-3 mt-3">
    {{-- responsive design for jobs  --}}
    <div class="col-lg-4">
        <div class="dropdown float-end pe-md-5 d-block d-lg-none fs-14">
            <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Open Jobs </button>
            <ul class="dropdown-menu fs-14">
                <!-- <li><a class="dropdown-item" href="#">Open Jobs</a></li>-->
                <li><a class="dropdown-item" href="/user/seller/job/list/ajax/bids">Bids</a></li>
                <li><a class="dropdown-item" href="/user/seller/job/list/ajax/offers">Offers</a></li>
                <li><a class="dropdown-item" href="/user/seller/job/list/ajax/active">Active</a></li>
                <li><a class="dropdown-item" href="/user/seller/job/list/ajax/completed">Completed Jobs</a></li>
            </ul>
        </div>
        <h5 id="page-title" class="d-none"></h5>
        <p class="mb-0 text-bc mt-1 fs-14 mb-3">
            <a class="text-3gray" href="{{ route('user.home') }}">
                <i class="fa-solid fa-house fs-14"></i>
            </a>
            <i class="fa-solid fa-chevron-right fs-12"></i>
            <span>
                Projects
            </span>
            <i class="fa-solid fa-chevron-right fs-12"></i>
            <span id="breadcrumb-title">
                Bids
            </span>
        </p>
    </div>

    <div class="col-md-7 col-lg-8 ps-md-5 pe-0 d-none d-lg-block">
        <div class="float-end pe-3 job-tab-buttons">
            <button id="btn-bids" type="button"
                class="btn btn-sm {{ $pageTitle == 'Bids' ? 'btn-success seller' : 'btn-outline-secondary' }}"
                data-url="/user/seller/job/list/ajax/bids" data-target="jobs" data-page-title="Bids">Bids</button>
            <button id="btn-seller-offers" type="button"
                class="btn btn-sm {{ $pageTitle == 'Offers' ? 'btn-success seller' : 'btn-outline-secondary' }} ms-2"
                data-url="/user/seller/job/list/ajax/offers" data-target="jobs" data-page-title="Offers">Offers</button>
            <button id="btn-seller-active-jobs" type="button"
                class="btn btn-sm {{ $pageTitle == 'Active Jobs' ? 'btn-success seller' : 'btn-outline-secondary' }} ms-2"
                data-url="/user/seller/job/list/ajax/active" data-target="jobs" data-page-title="Active Jobs">Active
                Jobs</button>
            <button id="btn-seller-completed-jobs" type="button"
                class="btn btn-sm {{ $pageTitle == 'Completed Jobs' ? 'btn-success seller' : 'btn-outline-secondary' }} ms-2"
                data-url="/user/seller/job/list/ajax/completed" data-target="jobs"
                data-page-title="Completed Jobs">Completed Jobs</button>
        </div>
    </div>

</div>
<div class="row pb-2"> <!-- Search form -->
    <div class="col-12">
        <form class="d-flex float-end" role="search" style="width:340px;">
            <input class="form-control bg-white float-end bg-lgreen " style="height:30px; font-size: 14px;"
                type="search" id="search" placeholder="Search Jobs" name="search" value="{{ request()?->search ?? '' }}"
                aria-label="Search">
            <button class="btn btn-sm ms-1 btn-success bg-3gray border-0 " type="submit" style="height: 30px;"><img
                    src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}" width="20"></button>
        </form>
    </div>
</div>

<div id="racket-job-vacancy-view-tabs">
    <div class="tab-pane fade show active" id="jobs" role="tabpanel" aria-labelledby="open-job-tab">
    </div>
</div>
