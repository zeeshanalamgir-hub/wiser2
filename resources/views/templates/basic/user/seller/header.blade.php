<div class="row px-4">
    <div class="col-md-6 col-lg-7 col-xl-8 pt-3">
        @php
            $redirect_url = $type == 'job' 
            ? "/job/details/" . encrypt($booking->job->id)
            : "/seller/service/service-details/" . encrypt($booking->service->id);
        @endphp
        <a class="links-c" href="{{ $redirect_url }}">
            <h5>
                {{ $type === 'job' ? $booking->job->title : $booking->service->title }}
            </h5>
        </a>
        <p>
        <p class="pt-1"><b>Description:</b>
            {!! $type === 'job' ? $booking->job->description : $booking->service->description !!}</p>
        </p>
    </div>
    <div class="col-md-6 col-lg-5 col-xl-4 d-flex">
        @if(!is_null($booking->buyer->image))
         <img src="{{ Storage::url($booking->buyer->image) }}"
            alt="@lang('Profile Image')" class="rounded-circle me-2" width="45" height="45" style="object-fit: cover">
        @else
            <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                style="height:45px;width:45px;font-size:20px">
                {{ strtoupper(substr($booking->buyer->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($booking->buyer->lastname ?? 'A', 0, 1)) }}
            </div>
        @endif
        <p class="fs-5" style="margin-left:10px">
            {{  $booking->buyer->username }}
        </p>
    </div>
</div>
