@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')

@section('content')
<h5 class="text-dgreen2">Notifications</h5>

<div id="contents" class="col-12">
    <div class="rounded shadow-sm border p-3 bg-white mt-3">
        <div class="row">
            <div class="col-md-6">
                <p class="fs-14 fw-semibold">
                    <a href="{{ route('all-notifications', ['type' => 'all', 'search' => request('search')]) }}"
                    class="btn btn-sm btn-outline-secondary py-0 {{ request('type') == 'all' || request('type') == null ? 'active' : '' }}">
                        All
                    </a>

                    <a href="{{ route('all-notifications', ['type' => 'latest', 'search' => request('search')]) }}"
                    class="btn btn-sm btn-outline-secondary py-0 ms-2 {{ request('type') == 'latest' ? 'active' : '' }}">
                        Latest
                    </a>

                    <a href="{{ route('all-notifications', ['type' => 'prior', 'search' => request('search')]) }}"
                    class="btn btn-sm btn-outline-secondary py-0 ms-2 {{ request('type') == 'prior' ? 'active' : '' }}">
                        Prior
                    </a>
                </p>
            </div>
            <div class="col-md-6">
                <form class="d-flex" role="search">
                    <input class="form-control bg-white float-end bg-lgreen" style="height:30px; font-size: 14px;"
                        type="search" placeholder="Search" name="search" value="{{ request('search') }}" aria-label="Search">
                    <button class="btn btn-sm btn-secondary bg-3gray ms-1 border-0" type="submit"
                        style="height: 30px;"><img src="{{ asset('assets/wiserlance/assets/img/icons/icons8-search.svg') }}" width="20"></button>
                </form>
            </div>
        </div>

        <!-- Notifications -->
        @forelse ($notifications as $notification)
            <a href="{{ $notification->data->click_url ?? '#' }}" class="text-decoration-none">
                <div class="{{ $loop->even ? 'bg-light' : 'bg-lgreen3' }} rounded mt-3 p-3">
                    <div class="row">
                        <div class="col-sm-7 col-md-8 col-lg-9 d-flex fs-14">
                            <i class="far fa-bell {{ $loop->even ? 'text-secondary' : 'text-dgreen' }} fs-5 mt-3"></i>
                            <p class="mb-0 ms-3 {{ $loop->even ? 'text-secondary' : 'text-dgreen' }}">
                                <span class="fs-12 text-secondary">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y') }}
                                </span><br>
                                <span class="fw-bold">{{ $notification->data->message ?? 'No Title' }}</span>
                            </p>
                        </div>
                        <div class="col-sm-5 col-md-4 col-lg-3 text-end pt-2">
                            {{-- @if ($notification->data->click_url)
                                <span class="btn btn-sm btn-outline-secondary fs-12">View</span>
                            @endif --}}
                            <i class="far fa-times-circle fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-center mt-4">No notifications found.</p>
        @endforelse
        <!-- Notifications End -->
    </div>
</div>
@endsection

@section('script')
@endsection
