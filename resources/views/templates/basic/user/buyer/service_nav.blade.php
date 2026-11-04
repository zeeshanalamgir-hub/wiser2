<div class="row">
    <div class="col-md-4">
    </div>
    <div class="col-md-8 mt-1 mt-md-0 ps-0">
        <button type="button"
            class="btn btn-sm ms-2 float-end {{ request()->routeIs('user.booking-service-cancelled') ? 'btn-success bg-green' : 'btn-outline-secondary' }}"
            onclick="location.href='{{ route('user.booking-service-cancelled') }}'">
            Cancelled
        </button>
        <button type="button"
            class="btn btn-sm ms-2 float-end {{ request()->routeIs('user.booking-service-completed') ? 'btn-success bg-green' : 'btn-outline-secondary' }}"
            onclick="location.href='{{ route('user.booking-service-completed') }}'">
            Completed
        </button>
        <button type="button"
            class="btn btn-sm ms-2 float-end {{ request()->routeIs('user.booking-service-active') ? 'btn-success bg-green' : 'btn-outline-secondary' }}"
            onclick="location.href='{{ route('user.booking-service-active') }}'">
            Active
        </button>
        <button type="button"
            class="btn btn-sm ms-2 float-end {{ request()->routeIs('user.service-booked') ? 'btn-success bg-green' : 'btn-outline-secondary' }}"
            onclick="location.href='{{ route('user.service-booked') }}'">
            All Services
        </button>
    </div>
    <div class="float-end mt-3">
        <form class="d-flex float-end" role="search" style="width:345px;">
            <input class="form-control bg-white float-end bg-lgreen" style="height:30px; font-size: 14px;"
                type="search" placeholder="Search" name="search" value="{{ request('search') }}" aria-label="Search">
            <button class="btn btn-sm ms-1 btn-success bg-3gray border-0 " type="submit" style="height: 30px;">
                <img src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}" width="20">
            </button>
        </form>
    </div>
</div>
