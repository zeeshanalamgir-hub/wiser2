 @extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
<style>
    .rating-review-container {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        color: #333;
        line-height: 1.5;
    }

    .section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.4rem;
        border-bottom: 2px solid #f1f3f5;
        padding-bottom: 0.75rem;
    }

    .review-label {
        color: #495057;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .review-user {
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
    }

    .review-user:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .user-avatar img {
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #dee2e6;
        transition: border-color 0.2s ease;
    }

    .review-user:hover .user-avatar img {
        border-color: #4dabf7;
    }

    .username {
        color: #2c3e50;
        font-size: 1rem;
        margin-bottom: 0.1rem;
    }

    .fullname {
        font-size: 0.85rem;
    }

    .rating-label {
        color: #495057;
        font-weight: 500;
        font-size: 0.95rem;
        display: block;
    }

    .rating-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f5;
    }

    .rating-item:last-child {
        border-bottom: none;
    }

    .star-rating {
        display: inline-flex;
        gap: 0.15rem;
    }

    .star-rating .fa-star {
        font-size: 1.2rem;
    }

    .star-rating .filled {
        color: #ffc107;
    }

    .star-rating .empty {
        color: #e9ecef;
    }

    .rating-value {
        font-weight: 600;
        color: #495057;
        font-size: 1.1rem;
        min-width: 2.5rem;
    }

    .comment-box, .reply-box {
        border-left: 3px solid #4dabf7;
        background-color: #f8f9fa !important;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .reply-box {
        border-left-color: #20c997;
    }

</style>

@section('content')
    <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  " role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white py-2 px-4">
                    <h1 class="modal-title fs-5" id="ModalLabel">@lang('Service Review')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $review = DB::table('review_ratings')
                            ->where('service_id', @$booking->service_id)
                            ->where('type', 'service')
                            ->first();
                        $review = DB::table('review_ratings')
                            ->where('service_id', @$booking->service_id)
                            ->where('type', 'service')
                            ->first();
                        $reviewer = DB::table('users')
                            ->where('id', @$review->user_id)
                            ->first();
                        $service = $booking?->service;
                    @endphp
                    <form method="POST" action="{{ route('user.rating.reply.service') }}">
                        @csrf
                        <input type="hidden" name="review_id" value="{{ @$review->id }}">
                       <div class="modal-body rating-review-container">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h5 class="mb-0 section-title">Rating & Review</h5>
                            </div>

                            <!-- Review For Section -->
                            <div class="review-for-section mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <p class="review-label font-weight-bold mr-3">Review For:</p>
                                    <a style="display:flex !important;gap: 10px;" href="{{ route('profile', [slug($service->user->username), encrypt($service->user->id)]) }}"
                                    class="review-user d-flex align-items-center text-decoration-none p-2 rounded bg-light">

                                        <!-- User Avatar -->
                                       <div class="user-avatar mr-3">
                                            @if (!is_null($booking->buyer->image))
                                                <img src="{{ Storage::url($booking->buyer->image) }}"
                                                    alt="{{ __($booking->buyer->username ?? 'User Avatar') }}"
                                                    class="img-fluid"
                                                    width="50"
                                                    height="50"
                                                    style="object-fit: cover; border-radius:50%">
                                            @else
                                                <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                    style="height:50px;width:50px;font-size:20px">
                                                    {{ strtoupper(substr($booking->buyer->firstname ?? 'A', 0, 1)) }}
                                                    {{ strtoupper(substr($booking->buyer->lastname ?? 'A', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- User Info -->
                                        <div class="user-info">
                                            <div class="username font-weight-semibold">
                                                {{ $service->user->username }}
                                            </div>
                                            <div class="fullname text-muted small">
                                                {{ $service->user->firstname }} {{ $service->user->lastname }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Ratings Section -->
                            <div class="ratings-section">

                                <!-- Overall Rating -->
                                <div class="rating-item">
                                    <label class="rating-label mb-2">Overall Rating</label>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating mr-3">
                                            @php $rating = @$review->rating ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $rating ? 'filled' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $rating }}.0</span>
                                    </div>
                                </div>

                                <!-- Skill -->
                                <div class="rating-item">
                                    <label class="rating-label mb-2">Skill</label>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating mr-3">
                                            @php $skill = @$review->skill ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $skill ? 'filled' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $skill }}.0</span>
                                    </div>
                                </div>

                                <!-- Seller Communication -->
                                <div class="rating-item">
                                    <label class="rating-label mb-2">Seller Communication</label>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating mr-3">
                                            @php $communication = @$review->communicationrating ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $communication ? 'filled' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $communication }}.0</span>
                                    </div>
                                </div>

                                <!-- Recommend to a Friend -->
                                <div class="rating-item">
                                    <label class="rating-label mb-2">Recommend to a Friend</label>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating mr-3">
                                            @php $recommend = @$review->recommendrating ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $recommend ? 'filled' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $recommend }}.0</span>
                                    </div>
                                </div>

                                <!-- Service as Described -->
                                <div class="rating-item">
                                    <label class="rating-label mb-2">Service as Described</label>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating mr-3">
                                            @php $described = @$review->describedrating ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $described ? 'filled' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $described }}.0</span>
                                    </div>
                                </div>

                            </div>

                            <!-- Comment Section -->
                            <div class="comment-section">
                                <label class="rating-label mb-2">Comment</label>
                                <div class="comment-box p-3 bg-light rounded">
                                    <p class="mb-0" style="word-wrap: break-word">{{ @$review->review ?: 'No comment provided.' }}</p>
                                </div>
                            </div>

                            <!-- Seller Reply Section -->
                            <div class="seller-reply-section">
                                <label class="rating-label mb-2">Seller Reply</label>
                                @if (@$review->reply == '')
                                    <div class="reply-box p-3 bg-light rounded">
                                        <p class="mb-0 text-muted"><i>Not replied yet</i></p>
                                    </div>
                                @else
                                    <div class="reply-box p-3 bg-light rounded">
                                        <p class="mb-0" style="word-wrap: break-word">{{ @$review->reply }}</p>
                                    </div>
                                @endif
                            </div>

                        </div>



                    </form>

                </div>
            </div>
        </div>
    </div>
    @include('modals.services_rating_modal')

    <div class="row pb-lg-4 mt-4">
        <div class="col-lg-4 pb-4 pb-lg-0 "> <!-- this drop down for small screens -->

            <x-buyer-service-mob-nav :booking="$booking" />

            <!--Breadcrumbs-->
            <p class="mb-0 text-bc mt-1 fs-14">
                <a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
                    <i class="fa-solid fa-house fs-14"></i>
                </a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a class="text-3gray" href="{{ route('user.service-booked') }}">Service Booking</a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                In Progress
            </p>
            <!--Breadcrumbs End-->

        </div>
        <x-buyer-service-nav :booking="$booking" activeTab="progress" />
    </div>

    <!-- card start-->
    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100">
            <p class="float-end">
                @if ($booking->is_endcontract == 1 || !$booking->service->reviews->isEmpty())
                    <a class="btn btn-sm btn-secondary ms-1 border-0" href="javascript:void(0);"
                        data-bs-toggle="modal" data-bs-target="#replyModal" title="Service Reviewed">
                        @lang('Service Reviewed')
                    </a>
                @else
                    @if ($booking->service->reviews->isEmpty() && $allworks->isNotEmpty())
                        <a class="btn btn-sm btn-warning text-white ms-1  border-0 contractBtn" href="javascript:void(0);"
                            data-buyer_id="{{ Auth::id() }}" data-seller_id="{{ $service->user->id }}"
                            data-bs-toggle="modal" data-bs-target="#contractModal" title="Write Review">
                            @lang('Write Review')
                        </a>
                    @endif
                @endif

                @if ($booking->is_extension_requested == 1)
                    <a class="btn btn-sm btn-secondary ms-1 border-0"
                        href="javascript:void(0);"
                        data-bs-toggle="modal"
                        data-bs-target="#extensionModal"
                        title="Extension">
                        @lang('View Extension')
                    </a>
                    @if ($booking->is_extension_approved === 1)
                        <a class="btn btn-sm btn-success ms-1 border-0"
                        href="javascript:void(0);"
                        title="Approved Extension">
                            @lang('Extension Approved')
                        </a>
                    @elseif ($booking->is_extension_approved === 2)
                        <a class="btn btn-sm btn-danger ms-1 border-0"
                        href="javascript:void(0);"
                        title="Rejected Extension">
                            @lang('Extension Rejected')
                        </a>
                    @endif
                @endif
               @if ($dispute_data_count > 0)
                    <a class="btn btn-sm btn-danger ms-1 border-0" href="{{ route('user.buyer.dispute') }}">
                        @lang('View Dispute')
                    </a>
                @elseif ($booking->is_endcontract != 1 && $allworks->isNotEmpty() && $booking->can_dispute)
                    <a class="btn btn-sm btn-danger ms-1 border-0 disputeBtn" href="javascript:void(0);"
                        data-service_id="{{ $booking->service_id }}"
                        data-order_number="{{ $booking->order_number }}"
                        data-bs-toggle="modal" data-bs-target="#disputeModal">
                        <i class="fa-solid fa-circle-exclamation text-white"></i>
                        @lang('Dispute')
                    </a>
                @else
                    <a class="btn btn-sm btn-danger ms-1 border-0 text-white disabled" style="cursor: not-allowed;"
                        href="javascript:void(0);" aria-disabled="true" tabindex="-1">
                        @lang('Dispute')
                    </a>
                @endif

                <a class="btn btn-sm ms-1" href="{{ route('user', ['id' => $booking->seller_id]) }}">
                    <i class="fa-regular fa-comment-dots fs-4 text-green"></i>
                </a>
            </p>
            <h5 class="text-dgreen2">In Progress</h5>
        </div>

        {{-- <div class="w-100 p-3 mb-3 position-relative">
            <!-- Top Row: Service Title + Status -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="fw-bold text-dgreen2">{{ $service->title ?? 'Service Name' }}</h5>
                <span class="badge bg-info">
                    @if ($booking->working_status == 1)
                        In Progress
                    @elseif ($booking->working_status == 2)
                        Completed
                    @else
                        Pending
                    @endif
                </span>
            </div>



            <!-- Bottom Row: Action Buttons -->
            <div class="d-flex flex-wrap justify-content-end gap-2">
                @if ($booking->is_endcontract == 1)
                    <a class="btn btn-sm btn-secondary" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#replyModal" title="Service Reviewed">@lang('Service Reviewed')</a>
                @else
                    @if ($allworks->isNotEmpty() && $allworks->first()->status == 6)
                        <a class="btn btn-sm btn-warning text-white contractBtn" href="javascript:void(0);"
                        data-buyer_id="{{ Auth::id() }}" data-seller_id="{{ $service->user->id }}"
                        data-bs-toggle="modal" data-bs-target="#contractModal" title="Write Review">@lang('Write Review')</a>
                    @endif
                @endif

                @if ($booking->is_extension_requested == 1)
                    <a class="btn btn-sm btn-secondary" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#extensionModal" title="Extension">@lang('View Extension')</a>
                    @if ($booking->is_extension_approved === 1)
                        <a class="btn btn-sm btn-success" href="javascript:void(0);" title="Approved Extension">@lang('Extension Approved')</a>
                    @elseif ($booking->is_extension_approved === 2)
                        <a class="btn btn-sm btn-danger" href="javascript:void(0);" title="Rejected Extension">@lang('Extension Rejected')</a>
                    @endif
                @endif

                @if ($dispute_data_count > 0)
                    <a class="btn btn-sm btn-danger" href="{{ route('user.buyer.dispute') }}">@lang('View Dispute')</a>
                @else
                    @if (in_array($booking->working_status, [1]))
                        <a class="btn btn-sm btn-danger text-white" style="cursor: not-allowed;" href="javascript:void(0);">@lang('Dispute')</a>
                    @else
                        @if ($allworks->isNotEmpty() && $allworks->first()->status != 0)
                            <a class="btn btn-sm btn-danger disputeBtn" href="javascript:void(0);" data-service_id="{{ $booking->service_id }}" data-order_number="{{ $booking->order_number }}" data-bs-toggle="modal" data-bs-target="#disputeModal">
                                <i class="fa-solid fa-circle-exclamation text-white"></i> @lang('Dispute')
                            </a>
                        @endif
                    @endif
                @endif

                <a class="btn btn-sm btn-outline-secondary" href="{{ route('user', ['id' => $booking->seller_id]) }}">
                    <i class="fa-regular fa-comment-dots mt-1"></i>
                </a>
            </div>
        </div> --}}

        <div class="w-100 bg-lgreen3 p-2 px-3 text-dgreen fw-bold rounded d-flex items-center justify-content-between">
            <div style="display: flex; align-items: center; gap: 5px;">
                DELIVERY WORK
            </div>
            <div>
                <div class="float-end d-flex border rounded-2 bg-white" style="max-height:40px">
                    <div class="text-green p-2 text-center" style="display: flex;align-items:center"><i class="fa-regular fa-clock fa-lg"></i> </div>
                    <div class="border-start px-2 text-center"  > <span id="days"></span> <p class="fs-10 fw-normal text-dgreen2" style="margin-top:-5px">Days</p><p></p></div>
                    <div class="border-start px-1 text-center"><span id="hours"></span> <p class="fs-10  fw-normal text-dgreen2" style="margin-top:-5px">Hours</p><p></p></div>
                    <div class="border-start px-1 text-center" ><span id="minutes"></span> <p class="fs-10  fw-normal text-dgreen2" style="margin-top:-5px">Minutes</p><p></p></div>
                    <div class="border-start px-1 text-center" ><span id="seconds"></span>  <p class="fs-10  fw-normal text-dgreen2" style="margin-top:-5px"> Seconds</p><p></p></div>
                </div>
            </div>
        </div>

        @if ($allworks->isEmpty())
            <!-- Check if there are no works -->
            <div class="w-100 px-3 mt-3">
                <p>No work in progress</p>
            </div>
        @else
            @foreach ($allworks as $index => $work)
                @php
                    $deliveryId = 'collapseDelivery' . ($index + 1);
                    $status = $work->status;
                    $submissionDate = showDateTime($work->created_at, 'd/m/Y');
                    $statusLabel = '';
                    $statusColor = '';

                    switch ($status) {
                        case 0:
                            $statusLabel = 'Pending';
                            $statusColor = 'btn-secondary';
                            break;
                        case 1:
                            $statusLabel = 'Work Accepted';
                            $statusColor = 'btn-success bg-green';
                            break;
                        case 2:
                            $statusLabel = 'Delivered';
                            $statusColor = 'btn-success bg-green';
                            break;
                        case 3:
                            $statusLabel = 'Declined';
                            $statusColor = 'btn-danger';
                            break;
                        case 4:
                            $statusLabel = 'In Progress';
                            $statusColor = 'btn-info';
                            break;
                        case 5:
                            $statusLabel = 'Canceled';
                            $statusColor = 'btn-danger';
                            break;
                        case 6:
                            $statusLabel = 'Paid';
                            $statusColor = 'btn-success bg-green';
                            break;
                    }
                @endphp

                <!-- Header -->
                <div class="w-100 px-3 mt-3">
                    <a class="links-c2" data-bs-toggle="collapse" href="#{{ $deliveryId }}" role="button"
                        aria-expanded="false" aria-controls="{{ $deliveryId }}">
                        <span class="float-end"><i class="far fa-eye text-green mt-1"></i> View</span>
                        <b class="fw-semibold">Delivery {{ $index + 1 }}</b>

                        @if ($statusLabel)
                            <button
                                class="py-0 btn btn-sm {{ $statusColor }} border-0 ms-3">{{ $statusLabel }}</button>
                        @endif

                        @if (in_array($booking->id, $disputedWorkIds))
                            <span class="py-0 btn btn-sm btn-danger border-0 ms-1">Disputed</span>
                        @endif
                        @if ($status == 1 && $booking->working_status == 0)
                            <span class="py-0 btn btn-sm btn-warning border-0 ms-1">Awaiting Payment</span>
                        @endif
                        <p class="text-secondary my-1 fs-14">
                            <span class="fw-semibold">Submission Date:</span> {{ $submissionDate }}
                        </p>
                    </a>
                </div>

                <!-- Collapsible Body -->
                <div class="collapse px-3 mt-2 fs-14" id="{{ $deliveryId }}">
                    <div class="card card-body">
                        <p class="fw-bold ms-3">Title: <span class="fw-normal">{{ $work->title }}</span></p>
                        <p class="fw-bold ms-3">Date: <span
                                class="fw-normal">{{ showDateTime($work->created_at, 'd M Y') }}</span>
                        </p>
                        <p class="fw-bold ms-3">Reason: <span class="fw-normal">{{ $work->details }}</span>
                            @if ($work->work_file)
                                <a href="{{ route('user.work-response-service-download', encrypt($work->id)) }}"
                                    title="Download"><i class="fa fa-paperclip"></i></a>
                            @endif
                        </p>

                        @if ($work->status == 2)
                            <p class="ms-3">
                                <a href="javascript:void(0);" data-order_number="{{ $booking->order_number }}"
                                    data-work="{{ $work->id }}" data-bs-toggle="modal"
                                    data-bs-target="#approvedModal"
                                    class="approvedBtn btn btn-sm btn-success border-0">Accept</a>

                                <a href="javascript:void(0);" data-order_number="{{ $booking->order_number }}"
                                    data-work="{{ $work->id }}" data-bs-toggle="modal" data-bs-target="#cancelModal"
                                    class="cancelBtn btn btn-sm btn-danger ms-2">Decline</a>
                            </p>
                        @endif



                        @if ($work->status == 3)
                            <div class="card card-body border-0 bg-light mt-3">
                                <p class="fw-bold">Decline Date: <span
                                        class="fw-normal">{{ showDateTime($work->updated_at, 'd M Y') }}</span></p>
                                <p class="fw-bold">Response: <span class="fw-normal">{{ $work->responce }}</span>
                                    @if ($work->responce_file)
                                        <a href="{{ route('user.work-response-service-rejected-download', encrypt($work->id)) }}"
                                            title="Download"><i class="fa fa-paperclip"></i></a>
                                    @endif
                                </p>
                            </div>
                        @endif

                        @if ($work->status == 1)
                            <p class="ms-3">
                                <a href="javascript:void(0);" data-order_number="{{ $booking->order_number }}"
                                    data-work="{{ $work->id }}" data-bs-toggle="modal" data-bs-target="#payModal"
                                    class="payBtn btn btn-sm btn-primary">Pay Now</a>
                            </p>
                        @endif
                    </div>
                </div>

                <hr class="mt-3">
            @endforeach
        @endif
    </div>
@endsection
@section('script')
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width:400px">
                <div class="modal-header bg-danger text-white py-2 px-4">
                    <h5 class="modal-title fs-5">@lang('Decline Delivery')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('user.work-delivery-rejected') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="work_id">
                    <input type="hidden" name="work_type" value="service">

                    <div class="modal-body">
                        <p class="mb-1">@lang('Upload your files here:')</p>
                        <div class="input-group mb-1">
                            <input type="file" class="form-control" name="file" aria-label="Upload">
                        </div>
                        <p class="fs-12 text-secondary"><i>@lang('Note: File Type (zip), Max size: 25 MB')</i></p>

                        <div class="form-floating">
                            <textarea class="form-control" name="details" placeholder="@lang('Reason for declining delivery')" style="height: 100px" required></textarea>
                            <label for="floatingDetails" class="p-2">@lang('Reason of decline the delivery')</label>
                        </div>
                    </div>
                    <div class="modal-footer clear-both">
                        <button type="submit" class="btn btn-sm btn-danger">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @php
        $add_ser = DB::table('additional_services')->where('booking_id', $booking->id)->get();
    @endphp
    @php
        $sub = $booking->qty * $booking->service_price;
        $total = $sub + $add_ser->sum('amount');
    @endphp

    <div class="modal fade" id="approvedModal" tabindex="-1" aria-labelledby="approvedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width:300px">
                <div class="modal-header bg-success text-white py-2 px-4">
                    <h5 class="modal-title fs-5">@lang('Accept Delivery')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('user.work-delivery-approved') }}">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="work_id">
                    <input type="hidden" name="totalss" value="{{ $total }}">
                    <input type="hidden" name="work_type" value="service">

                    <div class="modal-body">
                        <p class="fs-6">@lang('Are you sure to accept the delivery?')</p>
                    </div>
                    <div class="modal-footer clear-both">
                        <button type="button" class="btn btn-sm btn-danger bg-danger"
                            data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit"
                            class="btn btn-sm btn-success bg-success border-0">@lang('Yes')</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pay Modal -->
    <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white py-2 px-4">
                    <h5 class="modal-title" id="payModalLabel">@lang('Payment Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    <form method="POST" action="{{ route('user.work-delivery-approved-payed') }}">
                        @csrf
                        <input type="hidden" name="order_number" value="{{ $booking->order_number }}">
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="totalBookingAmount" value="{{ $booking->agreed_amount }}">

                        <div class="text-center mb-4">
                            <h2 class="m-0 ft-regular">@lang('Payment Confirmation')</h2>
                        </div>

                        <p class="text-center mb-4">@lang('Are you sure to release payment ?')</p>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success text-light fs-md ft-medium">
                                @lang('Confirm Payment')
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                @lang('Cancel')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $extension = $booking->extensionRequest;
    @endphp

    @if($extension)
        {{-- Accept Revision Modal --}}
        <div class="modal fade" id="extensionModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white py-2 px-4">
                        <h5 class="modal-title" id="payModalLabel">@lang('Revision Confirmation')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5">
                        <form method="POST" action="{{ route('user.change-extension-service') }}">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <input type="hidden" name="buyer_id" value="{{ $booking->buyer_id }}">
                            <input type="hidden" name="seller_id" value="{{ $booking->seller_id }}">

                            <div class="text-center mb-4">
                                <h5 class="fw-bold mb-2">@lang('Requested Extension Period')</h5>

                                <p class="mb-1">
                                    <strong>@lang('From'):</strong>
                                    {{ \Carbon\Carbon::parse($extension?->from_extension_date)->format('d M Y') }}
                                </p>

                                <p class="mb-0">
                                    <strong>@lang('To'):</strong>
                                    {{ \Carbon\Carbon::parse($extension?->upto_extension_date)->format('d M Y') }}
                                </p>

                                <p class="mb-0">
                                    <strong>@lang('Details'):</strong>
                                    {{ $extension?->details }}
                                </p>
                            </div>

                            <p class="text-center mb-4">@lang('Are you sure to accept this revision ?')</p>

                            <div class="d-grid gap-2">
                                <button type="submit" name="status" value="accept" class="btn btn-success">
                                    @lang('Accept Revision')
                                </button>

                                <button type="submit" name="status" value="reject" class="btn btn-danger">
                                    @lang('Reject Revision')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="disputeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  " role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white py-2 px-4">
                    <h4 class="modal-title" id="ModalLabel">@lang('Dispute')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST"
                    action="{{ route('user.service-dispute-create', ['orderNumber' => $booking->order_number]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @php
                            $add_ser = DB::table('additional_services')->where('booking_id', $booking->id)->get();
                            $sub = $booking->qty * $booking->service_price;
                            $total = $sub + $add_ser->sum('amount');
                        @endphp

                        <div class="row ">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Describe the reason</label>
                                <textarea rows="5" class="form-control" name="reason" placeholder="Describe the reason" required></textarea>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label>@lang('Upload Work File')</label>
                                <input class="form-control form-control" name="file" id="formFileLg"
                                    type="file">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Dispute offer')</label><br>
                                <label>Amount to dispute(in USD)</label>

                                <input name="dispute_amount" readonly class="form-control" value="<?php echo e(number_format($booking->amount, 2)); ?>">

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Employer amount (in USD)</label>
                                <input type="number" name="buyer_amount" class="form-control" step="0.01" min="0" required>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Freelancer amount (in USD)</label>
                                <input type="number" name="seller_amount" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Message to freelancer</label>
                                <textarea rows="5" class="form-control" name="message" placeholder="Message to freelancer" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-green btn-success py-1 px-3 apply-btn rounded">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Dispute Report')</h2>
                    </div>

                    <div class="dispute-detail">

                    </div>

                    <div class="form-group">

                    </div>
                    <div class="form-group text-center mb-0">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- End Modal -->

    <script src="{{asset('assets/wiserlance/assets/js/countMe.min.js')}}"></script>

    <script type="text/javascript">
        'use strict';

         window.onload = () => {
            const endTime = {{ $endTimestamp }};

            function updateCountdown() {
                const now = new Date().getTime();
                let diff = endTime - now;

                if (diff < 0) diff = 0;

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = days;
                document.getElementById('hours').textContent = hours;
                document.getElementById('minutes').textContent = minutes;
                document.getElementById('seconds').textContent = seconds;
            }

            updateCountdown();

            setInterval(updateCountdown, 1000);
        }


        $('.approvedBtn').on('click', function() {
            var modal = $('#approvedModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.find('input[name=work_id]').val($(this).data('work'))
            modal.modal('show');
        });
        $('.payBtn').on('click', function() {
            var modal = $('#payModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.find('input[name=work_id]').val($(this).data('work'))
            modal.modal('show');
        });
        $('.cancelBtn').on('click', function() {
            var modal = $('#cancelModal');
            modal.find('input[name=work_id]').val($(this).data('work'))
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.disputeBtn').on('click', function() {
            var modal = $('#disputeModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'));
            modal.find('input[name=service_id]').val($(this).data('service_id'));
            modal.modal('show');
        });

        $('.disputeShow').on('click', function() {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
        //         $('.toggle').click(function() {
        // $('#target').toggle();
        // });
        //     $('.toggle2').click(function() {
        // $('#target2').toggle();
        // });
        $('.toggle').click(function() {
            var id = $(this).data('work_id');
            $('#target' + id).toggle();
        });
        $('.contractBtn').on('click', function() {
            var modal = $('#contractModal');
            modal.find('input[name=buyer_id]').val($(this).data('buyer_id'))
            modal.find('input[name=seller_id]').val($(this).data('seller_id'))
            modal.modal('show');
        });

        function setLikelyRating(value, element) {
            // Set the hidden input value
            document.getElementById('likely_rating').value = value;

            // Reset the background color of all buttons
            document.querySelectorAll('.rating-btn').forEach(function(btn) {
                btn.style.backgroundColor = ''; // Reset background
                btn.style.color = ''; // Reset text color
            });

            // Highlight the selected button
            element.style.backgroundColor = 'black';
            element.style.color = 'white'; // Set text color to white for contrast
        }
    </script>
@endsection
