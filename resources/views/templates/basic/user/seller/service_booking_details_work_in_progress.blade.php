@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
<style>
    .booking-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.countdown-wrapper {
    gap: 10px;
}

.countdown-box {
    padding: 10px 0;
    font-family: 'Arial', sans-serif;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.countdown-box h1 {
    font-size: 1.8rem;
    margin-bottom: 0.2rem;
}

.countdown-box small {
    font-size: 0.8rem;
    display: block;
}

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .review-user {
            flex-direction: column;
            align-items: flex-start !important;
            text-align: left;
        }

        .user-avatar {
            margin-bottom: 0.5rem;
        }

        .user-info {
            text-align: left;
        }

        .section-title {
            font-size: 1.2rem;
        }
    }
</style>
@section('content')
<style>
    .rating-review-container {
        padding: 1rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }

    .review-label {
        color: #495057;
        min-width: 120px;
    }

    .rating-label {
        font-weight: 500;
        color: #495057;
        display: block;
    }

    .star-rating {
        font-size: 1.1rem;
    }

    .star-rating .fa-star {
        color: #ddd;
        margin-right: 2px;
    }

    .star-rating .fa-star.filled {
        color: #FFD700;
    }

    .star-rating .fa-star.empty {
        color: #e9ecef;
    }

    .rating-value {
        font-weight: 600;
        color: #333;
        font-size: 1rem;
    }

    .comment-box, .reply-box {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .review-user {
        transition: all 0.2s ease;
    }

    .review-user:hover {
        background-color: #e9ecef;
        transform: translateY(-1px);
    }

    .user-info .username {
        color: #333;
        font-size: 0.95rem;
    }

    .user-info .fullname {
        font-size: 0.85rem;
    }

    .modal-header .btn-close {
        opacity: 1;
        padding: 0.5rem;
    }

    .modal-header .btn-close i {
        font-size: 1rem;
    }

    .apply-btn {
        padding: 0.5rem 2rem;
        font-weight: 500;
    }

    .theme-bg {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .theme-bg:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .reply-input textarea.form-control {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.75rem;
        font-size: 0.95rem;
    }

    .reply-input textarea.form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>

    <div class="modal fade" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white py-2 px-4">
                    <h1 class="modal-title fs-5" id="ModalLabel">@lang('Service Review')</h1>
                    <button type="button" class="btn-close text-white" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $reviewer = DB::table('users')->where('id', @$review->user_id)->first();
                    @endphp
                    <form method="POST" action="{{ route('user.service.rating.reply') }}">
                        @csrf
                        <input type="hidden" name="review_id" value="{{ @$review->id }}">
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <div class="rating-review-container">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h5 class="mb-0 section-title">Rating & Review</h5>
                            </div>

                            <!-- Reviewer Information -->
                            <div class="reviewer-section">
                                <div class="d-flex align-items-center mb-3">
                                    <p class="review-label font-weight-bold mr-3">Review by:</p>
                                    <a style="display:flex !important;gap: 10px;" href="{{ route('profile', [slug(@$reviewer->username), encrypt(@$reviewer->id)]) }}"
                                    class="review-user d-flex align-items-center text-decoration-none p-2 rounded bg-light">

                                        <!-- User Avatar -->
                                       <div class="user-avatar mr-3">
                                            @if (!is_null(@$reviewer->image))
                                                <img src="{{ Storage::url(@$reviewer->image) }}"
                                                    alt="{{ __(@$reviewer->username ?? 'User Avatar') }}"
                                                    class="img-fluid rounded-circle"
                                                    width="48"
                                                    height="48"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                                                    style="height:48px;width:48px;font-size:18px">
                                                    {{ strtoupper(substr(@$reviewer->firstname ?? 'A', 0, 1)) }}
                                                    {{ strtoupper(substr(@$reviewer->lastname ?? 'A', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- User Info -->
                                        <div class="user-info">
                                            <div class="username font-weight-semibold">
                                                {{ @$reviewer->username }}
                                            </div>
                                            <div class="fullname text-muted small">
                                                {{ @$reviewer->firstname }} {{ @$reviewer->lastname }}
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

                            <!-- Reply Section -->
                            <div class="reply-section">
                                <label class="rating-label mb-2">Your Reply</label>
                                @if(@$review->reply == '')
                                    <div class="reply-input">
                                        <textarea name="reply" class="form-control" rows="4" placeholder="Type your reply here..." required></textarea>
                                        <div class="modal-footer mt-3 px-0">
                                            <button type="submit" class="btn theme-bg text-light apply-btn rounded">
                                                @lang('Submit Reply')
                                            </button>
                                        </div>
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

    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Dispute Report')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="ModalLabel">@lang('Are you sure to dispute this booking')</h4>

                    </div>
                    <div class="modal-body">
                        <form method="POST"
                            action="{{route('user.work.dispute', ['encbuyerCounterOfferDataId' => encrypt(123)])}}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="order_number">
                            <input type="hidden" name="work_type" value="service">
                            <div class="form-group">
                                <textarea rows="8" class="form-control" name="dispute" placeholder="Why dispute ...."
                                    required></textarea>
                                <small>@lang('Minimum 50 Characters Required')</small>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" style="width:100%;">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-rounded text-white"
                        data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row pb-lg-4 mt-4">
        <div class="col-lg-4 pb-4 pb-lg-0 "> <!-- this drop down for small screens -->

            <x-seller-service-mob-nav :booking="$booking" />
            <!--Breadcrumbs-->
            <p class="mb-0 text-bc mt-1 fs-14">
                <a class="text-3gray" href="{{ route('user.home') }}">
                    <i class="fa-solid fa-house fs-14"></i>
                </a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a class="text-3gray" href="{{ route('user.seller-booking-service') }}">Service Booking</a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                In progress
            </p>
            <!--Breadcrumbs End-->
        </div>

        <x-seller-service-nav :booking="$booking" activeTab="progress" />
    </div>

    <!-- card start-->
    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100">
            <p class="float-end">

                @if($booking->is_endcontract == 1)
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#contractModal"
                        class="btn btn-sm btn-secondary text-white border fs-sm ft-medium mr-2"
                        style="border: 1px solid gray !important;">
                        @lang('Service Reviewed')
                    </a>
                @endif

                 @if($dispute_data_count > 0)
                    <a href="{{ route('user.seller.dispute') }}"
                        class="btn btn-sm  text-white bg-danger border fs-sm ft-medium mr-2">
                        @lang('View Dispute')
                    </a>
                {{-- @else
                    <a href="javascript:void(0);" class="btn btn-sm btn-danger ms-1 border-0 disputeShow"
                        data-dispute="No dispute details found">
                        <i class="fa-solid fa-circle-exclamation text-white"></i> @lang('Dispute')
                    </a>  --}}
                @endif

                @php
                    $remainingRevisions = ($booking?->service_revisions + $booking?->extra_revisions) - $workscount;
                @endphp

                @if($workscount >= $booking->service_revisions + $booking?->extra_revisions)
                    <a href="javascript:void(0);" class="btn btn-sm btn-secondary bg-dgreen2 border-0">
                        @lang('Revisions Expired')
                    </a>
                @else
                    @if($booking->is_endcontract == 0 && !in_array($booking->status, [6,9,10]))
                        <a href="javascript:void(0);" class="btn btn-sm btn-secondary bg-dgreen2 border-0">
                            {{ $remainingRevisions }} @lang('Revision'){{ $remainingRevisions > 1 ? 's' : '' }} @lang('Left')
                        </a>

                        <a href="javascript:void(0);" class="btn btn-sm btn-secondary bg-dgreen2 ms-1  border-0 workBtn"
                            data-order_number="{{ $booking->order_number }}">
                            @lang('Upload Work Delivery')
                        </a>
                    @endif
                @endif

                @if($booking->is_endcontract == 0 && !in_array($booking->status, [6,9,10]))
                    <a href="javascript:void(0);" class="btn btn-sm btn-secondary ms-1  border-0"
                        data-bs-toggle="modal" data-bs-target="#ExtentionModal">
                        @lang('Extension') ({{$booking?->extensionRequest ? $booking?->extensionRequest->count() : 0}})
                    </a>
                @endif

                <!-- <a href="javascript:void(0);" class="btn btn-sm btn-warning text-white ms-1 border-0" data-bs-toggle="modal"
                        data-bs-target="#ReviewModal">
                        @lang('Write Review')
                    </a> -->

                <!-- <a href="#" class="btn btn-sm">
                        <i class="fa-regular fa-comment-dots fs-4 text-green"></i>
                    </a> -->

                    <a class="links-c" href="{{ route('user', ['id' => $booking->buyer_id]) }}">
                        <i class="fa-regular fa-comment-dots fs-4"></i>
                    </a>
                    {{-- Chat with <span class="text-green"><a class="links-c" href="{{ route('user', ['id' => $booking->buyer_id]) }}">{{ $booking->buyer->fullname ?? 'Seller' }}</a></span> --}}

            </p>
            <h5 class="text-dgreen2">@lang('In Progress')</h5>
        </div>

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
            @if($allworks->isEmpty()) <!-- Check if there are no works -->
                <div class="w-100 px-3 mt-3">
                    <p>No work in progress</p>
                </div>
            @else
                @foreach($allworks as $index => $work)
                    @php
                        $deliveryId = 'collapseDelivery' . ($index + 1);
                        $statusLabel = '';
                        $statusColor = '';

                        switch ($work->status) {
                            case 0:
                                $statusLabel = 'Pending';
                                $statusColor = 'btn-secondary';
                                break;
                            case 1:
                                $statusLabel = 'Completed';
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

                    <div class="w-100 px-3 mt-3">
                        <a class="links-c2" data-bs-toggle="collapse" href="#{{ $deliveryId }}" role="button" aria-expanded="false"
                            aria-controls="{{ $deliveryId }}">
                            <span class="float-end"><i class="far fa-eye text-green mt-1"></i> View</span>
                            <b class="fw-semibold">Delivery {{ $index + 1 }}</b>

                            @if($statusLabel)
                                <button class="py-0 btn btn-sm {{ $statusColor }} border-0 ms-3">{{ $statusLabel }}</button>
                            @endif

                            <p class="text-secondary my-1 fs-14">
                                <span class="fw-semibold">Submission Date:</span>
                                <span class="text-secondary">{{ showDateTime($work->created_at, 'd/m/Y') }}</span>
                            </p>
                        </a>
                    </div>

                    <div class="collapse px-3 mt-2 fs-14" id="{{ $deliveryId }}">
                        <div class="card card-body">
                            <p class="fw-bold ms-3">Title: <span class="fw-normal">{{ $work->title }}</span></p>
                            <p class="fw-bold ms-3">Date: <span class="fw-normal">{{ showDateTime($work->created_at, 'd M Y') }}</span>
                            </p>
                            <p class="fw-bold ms-3">Reason:
                                <span class="fw-normal">{{ $work->details }}</span>
                                @if(!empty($work->work_file))
                                    <a title="Download attachment"
                                        href="{{ route('user.work-response-service-download', encrypt($work->id)) }}">
                                        <i class="fa fa-paperclip"></i>
                                    </a>
                                @endif
                            </p>

                            @if($work->status == 3 && !empty($work->responce))
                                <div class="card card-body border-0 bg-light">
                                    <p class="fw-bold">Date: <span class="fw-normal">{{ showDateTime($work->updated_at, 'd M Y') }}</span>
                                    </p>
                                    <p class="fw-bold">Reason:
                                        <span class="fw-normal">{{ $work->responce }}</span>
                                        @if(!empty($work->responce_file))
                                            <a title="Download attachment"
                                                href="{{ route('user.work-response-service-rejected-download', encrypt($work->id)) }}">
                                                <i class="fa fa-paperclip"></i>
                                            </a>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="mt-3">
                @endforeach
            @endif
        </div>
@endsection
@section('script')

<div class="modal fade" id="ExtentionModal" tabindex="-1" aria-labelledby="ExtentionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width:400px">
                <div class="modal-header bg-dgreen2 text-white py-2 px-4">
                    <h5 class="modal-title" id="ExtentionModalLabel">@lang('Request For Extension')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('user.service.extension.request') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                    <input type="hidden" name="buyer_id" value="{{ $booking->buyer_id }}">
                    <input type="hidden" name="seller_id" value="{{ $booking->seller_id }}">
                    <input type="hidden" name="from_extension_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">

                    <div class="modal-body">
                        <div class="form-floating">
                            <textarea name="details" class="form-control" id="floatingDetails" placeholder="Description"
                                style="height:100px" required></textarea>
                            <label for="floatingDetails" class="p-2">@lang('Rason for extension')</label>
                        </div>

                        <div class="my-3">
                            <label for="text" class="p-2">Select your date*</label>
                            <input class="form-control" name="upto_extension_date" type="date" name="date" required="">
                        </div>

                    </div>

                    <div class="modal-footer clear-both">
                        <button type="submit"
                            class="btn btn-sm btn-success bg-green text-white border-0">@lang('Submit')</button>
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="workModal" tabindex="-1" aria-labelledby="workModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width:400px">
                <div class="modal-header bg-dgreen2 text-white py-2 px-4">
                    <h5 class="modal-title" id="workModalLabel">@lang('Upload Work Delivery')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('user.work-upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="work_type" value="service">

                    <div class="modal-body">
                        <p class="mb-1">@lang('Upload your delivery files here:')</p>
                        <div class="input-group mb-1">
                            <input type="file" class="form-control" name="file" id="inputGroupFile04"
                                aria-describedby="inputGroupFileAddon04" aria-label="Upload" required>
                        </div>
                        <p class="fs-12 text-secondary"><i>@lang('Note: File Type (zip only). Max size: 25 MB')</i></p>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="title" id="floatingTitle" placeholder="Title"
                                style="height:40px">
                            <label for="floatingTitle" class="p-2">@lang('Title*')</label>
                        </div>

                        <div class="form-floating">
                            <textarea name="details" class="form-control" id="floatingDetails" placeholder="Description"
                                style="height:100px" required></textarea>
                            <label for="floatingDetails" class="p-2">@lang('Description*')</label>
                        </div>
                    </div>

                    <div class="modal-footer clear-both">
                        <button type="submit"
                            class="btn btn-sm btn-success bg-green text-white border-0">@lang('Submit')</button>
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/wiserlance/assets/js/countMe.min.js')}}"></script>

    <script>
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


        $('.workBtn').on('click', function () {
            var modal = $('#workModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.toggle').click(function () {
            var id = $(this).data('work_id');
            $('#target' + id).toggle();
        });

        $(document).on('click', '.disputeShow', function () {
            $('#disputeModalShow').modal('show');
        });
    </script>
@endsection
