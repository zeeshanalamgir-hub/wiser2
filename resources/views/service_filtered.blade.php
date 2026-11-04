@if ($processedServices->count())
    @foreach ($processedServices as $row)
        @php
            $service = $row->service;
        @endphp
        <!-- Service Card -->
        <div class="col-md-6 col-xl-4 my-2 mt-lg-0">
            <div class="card shadow-sm border">
                <!-- Service Image -->
                <img src="{{ getImage('assets/images/service/' . $service->image, imagePath()['service']['size']) }}"
                    class="card-img-top p-2 pb-0" alt="{{ $service->title }}"
                    style="height:165px;object-fit: cover;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Seller Info -->
                        <div class="col-6 col-lg-8 mb-2">
                            <div class="d-flex flex-row gap-2 align-items-center fs-10">
                                <div>
                                    <a href="{{ route('profile', [$service->user->username, encrypt($service->user->id)]) }}">
                                        @if (!is_null($service->user->image))
                                            <div class="d-flex justify-content-center">
                                                <img class="rounded-circle border border-secondary img-fluid"
                                                    alt="{{ $service->user->username ?? 'Seller' }}"
                                                    src="{{ $service->user->image ? Storage::url($service->user->image) : asset('assets/images/default-profile.png') }}"
                                                    style="width: 40px;height:40px;object-fit: cover;">
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center">
                                                <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                    style="height:40px;width:40px;font-size:20px">
                                                    {{ strtoupper(substr($service->user->firstname ?? 'A', 0, 1)) }}
                                                    {{ strtoupper(substr($service->user->lastname ?? 'A', 0, 1)) }}
                                                </div>
                                            </div>
                                        @endif
                                        {{-- <img class="rounded-circle border border-secondary img-fluid"
                                            style="border-radius:50% !important;width:40px !important;height:40px !important"
                                            alt="{{ $service->user->username ?? 'Seller' }}"
                                            src="{{ asset('storage/' . $service->user->image) }}"
                                            width="40" height="40"> --}}
                                    </a>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <p class="mb-0 fs-12 fw-semibold">
                                        <a href="{{ route('profile', [$service->user->username, encrypt($service->user->id)]) }}"
                                            class="text-dark text-decoration-none">
                                            {{ $service->user->username }}
                                        </a>
                                    </p>
                                    <p class="mb-0 text-secondary fs-10">
                                        {{ $service->category->name ?? 'Freelancer' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Rating Info -->
                        <div class="col-6 col-lg-4 mt-lg-0 text-end">
                            <div class="d-flex flex-column align-items-end justify-content-center">
                                @php
                                    $progress = ($row->avg_rating / 5) * 100;
                                @endphp

                                <div class="w-100 mb-1">
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-success"
                                            role="progressbar"
                                            style="width: {{ $progress }}%;">
                                        </div>
                                    </div>
                                </div>

                                <p class="mb-0 fs-10 text-center fw-semibold">
                                    <i class="fa fa-star text-warning"></i>
                                    {{ number_format($row->avg_rating, 1) }}
                                    ({{ $row->total_reviews }})
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Service Title -->
                    <h6 class="fs-12 fw-normal">
                        <a href="{{ route('service.details', [encrypt($service->id)]) }}"
                            class="text-dark text-decoration-none">
                            {{ Str::limit($service->title, 60) }}
                        </a>
                    </h6>
                    <!-- Pricing and Delivery -->
                    <div class="w-100">
                        <p class="mb-0 fs-10 fw-semibold float-end">
                            Starting From:
                            <span class="text-green">{{ $currencySymbol }}{{ number_format($row->price, 2) }}</span>
                        </p>
                        <p class="mb-0 fs-10 fw-semibold">
                            Delivery:
                            <span class="text-green">{{ $row->delivery_time }} Days</span>
                        </p>
                    </div>
                    <!-- Order/View Button -->
                    <p class="mt-3 mb-0">
                        <a href="{{ route('service.details', [encrypt($service->id)]) }}"
                            class="btn cursor-pointer btn-sm btn-success bg-dgreen border-0 float-end" style="font-size:12px">View Details</a>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-12 text-center w-100 my-5">
        <i class="fa fa-frown fs-2 mb-2"></i>
        <div class="fs-5 fw-semibold">No services found with your current filters.</div>
        <div class="fs-6 mt-2 text-muted">Try adjusting your filter criteria to see more results.</div>
    </div>
@endif
