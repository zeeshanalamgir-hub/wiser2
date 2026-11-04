<div class="row px-4">
    @php
        $redirect_url = $type == 'job' 
        ? "/job/details/" . encrypt($booking->job->id)
        : "/seller/service/service-details/" . encrypt($booking->service->id);
    @endphp
    <div class="col-md-6 col-lg-7 col-xl-8">
        <a class="links-c" href="{{ $redirect_url }}">
            <h5>{{ $type === 'job' ? $booking->job->title : $booking->service->title }}</h5>
        </a>
        <p class="pt-4"><b>Description:</b>
            {!! $type === 'job' ? $booking->job->description : $booking->service->description !!}</p>

    </div>
    <div class="col-md-6 col-lg-5 col-xl-4 d-flex">
            @if(!is_null($booking->seller->image))
                <img src="{{ Storage::url($booking->seller->image) }}"
                    alt="@lang('Profile Image')" class="rounded-circle me-2" width="45" height="45" style="object-fit: cover">
            @else
                <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                    style="height:45px;width:45px;font-size:20px">
                    {{ strtoupper(substr($booking->seller->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($booking->seller->lastname ?? 'A', 0, 1)) }}
                </div>
            @endif
        <p class="fs-5" style="margin-left:10px">
            {{  $booking->seller->fullname }}
        </p>
    </div>
</div><!--row end-->
