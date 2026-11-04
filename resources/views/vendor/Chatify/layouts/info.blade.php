@php
    $user = Auth::user();
    $tz = new DateTimeZone($user->timezone ?? 'UTC');
    $date = new DateTime("now", $tz);
    $now = $date->format("h:i:s A");
@endphp
<style>
    .fa-brands::before {
        color: #0CB48C;
    }
</style>
<div class="bg-light p-4 rounded-end border-start" style="height:700px;">
    {{-- nav actions --}}
    {{-- <nav>
        <p>User Details</p>
        <a href="#"><i class="fas fa-times"></i></a>
    </nav> --}}
    {{-- user info and avatar --}}
    <div class="bg-dgreen rounded text-center p-4">
        <center>
            @if(!is_null($user?->image))
                    <img class="rounded-circle img-fluid" alt="{{ $user['username'] ?? 'Seller' }}"
                    src="{{ $user['image'] ? Storage::url($user->image) : asset('assets/images/default-profile.png') }}"
                    style="width: 80px;height:80px;object-fit: cover;">
                @else
                    <div class="d-flex justify-content-center">
                        <div class="rounded-circle text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                            style="height:80px;width:80px;font-size:20px;border:1px solid #fff;">
                                {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                @endif
        </center>
        <p class="info-name pt-3 text-green ">{{ config('chatify.name') }}</p>
        {{--  <p class="fs-12 text-light mb-1"><i class="fa-regular fa-clock"></i> 7:05 PM PM GMT+1(4 H ahead)</p>  --}}
        <p class="fs-12 text-light mb-1"><i class="fa-regular fa-clock"></i> {{ $now }} {{ $user?->timezone }}</p>
        <p class="fs-12 text-light ms-2"><i class="fa-solid fa-location-dot text-dgreen2 fs-6"></i> {{ $user?->country ?? '--' }} </p>
    </div>

    <div class="rounded-1 bg-mgrey p-1 px-3 mt-3 fs-14 text-secondary">
        Job in progress
        <span class="float-end px-2 text-white bg-dgreen2 badge rounded-pill job-in-progress-count">0</span>
    </div>
    <div class="rounded-1 bg-mgrey p-1 px-3 mt-2 fs-14 text-secondary">
        Completed Jobs
        <span class="float-end px-2 text-white bg-dgreen2 badge rounded-pill completed-jobs-count">0</span>
    </div>
    <div class="rounded-1 bg-mgrey p-1 px-3 mt-2 fs-14 text-secondary">
        Services in progress
        <span class="float-end px-2 text-white bg-dgreen2 badge rounded-pill services-in-progress-count">0</span>
    </div>
    <div class="rounded-1 bg-mgrey p-1 px-3 mt-2 fs-14 text-secondary">
        Completed services
        <span class="float-end px-2 text-white bg-dgreen2 badge rounded-pill completed-services-count">0</span>
    </div>

    {{-- <div>
        <p class="text-dgreen fs-14 mt-3 fw-bold">Media & Links <span class="float-end text-secondary fw-normal">See All
                <a href="#" class="links-c"><i class="fa-solid fa-arrow-right"></i> </a></span></p>

        <div class="fs-4 text-lgreen">
            <a href="#" style="text-decoration: none;">
                <i class="fa-brands fa-twitter mx-3"></i>
            </a>
            <a href="#" style="text-decoration: none;">
                <i class="fa-brands fa-facebook me-3"></i>
            </a>
            <a href="#" style="text-decoration: none;">
                <i class="fa-brands fa-youtube me-3"></i>
            </a>
            <a href="#" style="text-decoration: none;">
                <i class="fa-brands  fa-linkedin me-4"></i>
            </a>
            <a href="#" style="text-decoration: none;">
                <i class="fa-brands fa-instagram"></i>
            </a>
        </div>
    </div> --}}

    {{--  <div class="rounded-1 bg-mgrey p-1 px-3 mt-3 fs-14 text-secondary"> <i class="fa-solid fa-link text-green"></i> https://www.figma.com/file/ </div>  --}}
    {{-- <div class="messenger-infoView-btns">
        <a href="#" class="danger delete-conversation">Delete Conversation</a>
    </div> --}}
    {{-- shared photos --}}
    {{-- <div class="messenger-infoView-shared">
        <p class="messenger-title"><span>Shared Photos</span></p>
        <div class="shared-photos-list"></div>
    </div> --}}
</div>
