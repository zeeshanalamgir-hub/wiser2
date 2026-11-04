@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')

    <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Service Review')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $review = DB::table('review_ratings')
                            ->where('data_id', @$booking->id)
                            ->where('type', 'service')
                            ->first();

                        $reviewer = DB::table('users')
                            ->where('id', @$review->user_id)
                            ->first();
                    @endphp
                    <form method="POST" action="{{ route('user.rating.reply.service') }}">
                        @csrf
                        <input type="hidden" name="review_id" value="{{ @$review->id }}">
                        <div class="modal-body">

                            <h5>Rating & review</h5>

                            <span class="font-weight-bold">Review For:
                                <a
                                    href="{{ route('profile', [slug($booking?->service?->serviceDetail?->user?->username), encrypt($booking?->service?->serviceDetail?->user?->id)]) }}">
                                    @if ($booking?->service?->serviceDetail?->user?->image == '')
                                        <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                            class="img-fluid circle mr-2" width="40" alt="">
                                    @else
                                        <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $booking?->service?->serviceDetail?->user?->image, 'profile_image') }}"
                                            alt="{{ __($booking?->service?->serviceDetail?->user?->username) }}"
                                            class="img-fluid circle mr-2 "
                                            style="border-radius: 50%;height: 40px;width: 40px;border: 2px solid #e5e5e5;">
                                    @endif
                            </span><span>{{ $booking?->service?->serviceDetail?->user?->firstname }}
                                {{ $booking?->service?->serviceDetail?->user?->lastname }}</span></a><br>

                            <label class="mt-3">Rating</label><br>
                            @if (@$review->rating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <span style="color:gold;">5</span></span>
                            @elseif(@$review->rating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">4</span></span>
                            @elseif(@$review->rating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">3</span></span>
                            @elseif(@$review->rating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <span style="color:gold;">2</span></span>
                            @elseif(@$review->rating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">1</span></span>
                            @endif

                            <br> <label style="margin-top: 10px;">Skill</label><br>
                            @if (@$review->skill == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <span style="color:gold;">5</span></span>
                            @elseif(@$review->skill == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">4</span></span>
                            @elseif(@$review->skill == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">3</span></span>
                            @elseif(@$review->skill == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <span style="color:gold;">2</span></span>
                            @elseif(@$review->skill == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                    <span style="color:gold;">1</span></span>
                            @endif


                            <br> <label style="margin-top: 10px;">Seller communication level</label><br>
                            @if (@$review->communicationrating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <span style="color:gold;">5</span></span>
                            @elseif(@$review->communicationrating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">4</span></span>
                            @elseif(@$review->communicationrating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">3</span></span>
                            @elseif(@$review->communicationrating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <span style="color:gold;">2</span></span>
                            @elseif(@$review->communicationrating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                    <span style="color:gold;">1</span></span>
                            @endif
                            <br><label style="margin-top: 10px;">Recommend to a friend</label><br>
                            @if (@$review->recommendrating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <span style="color:gold;">5</span></span>
                            @elseif(@$review->recommendrating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">4</span></span>
                            @elseif(@$review->recommendrating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">3</span></span>
                            @elseif(@$review->recommendrating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <span style="color:gold;">2</span></span>
                            @elseif(@$review->recommendrating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                    <span style="color:gold;">1</span></span>
                            @endif
                            <br><label style="margin-top: 10px;">Service as described</label><br>
                            @if (@$review->describedrating == 5)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <span style="color:gold;">5</span></span>
                            @elseif(@$review->describedrating == 4)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">4</span></span>
                            @elseif(@$review->describedrating == 3)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star" style="color:gold;"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">3</span></span>
                            @elseif(@$review->describedrating == 2)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"
                                        style="color:gold;"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <span style="color:gold;">2</span></span>
                            @elseif(@$review->describedrating == 1)
                                <span><i class="fa fa-star" style="color:gold;"></i> <i class="fa fa-star"></i> <i
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                    <span style="color:gold;">1</span></span>
                            @endif
                            <br> <label style="margin-top: 10px;">Comment</label>
                            <p>{{ @$review->review }}</p>
                            @if (@$review->reply == '')
                                <label> Seller Reply</label>
                                <p>Not replyed yet</p>
                            @else
                                <label>Seller Reply</label>
                                <p>{{ @$review->reply }}</p>
                            @endif

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Review & Rating')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="POST"
                        action="{{ route('user.buyer.rating.service', ['service' => $booking?->service]) }}">
                        @csrf
                        <input type="hidden" name="seller_id">
                        <input type="hidden" name="buyer_id">
                        <input type="hidden" name="data_id" value="{{ $booking->id }}">
                        <input type="hidden" name="service_id" value="{{ $booking?->service_id }}">
                        <div class="modal-body">

                            <h5>Rating & review</h5>
                            <span class="font-weight-bold">How was your work experience with:
                                <a
                                    href="{{ route('profile', [slug($booking?->service?->serviceDetail?->user?->username), encrypt($booking?->service?->serviceDetail?->user?->id)]) }}">
                                    @if ($booking?->service?->serviceDetail?->user?->image == '')
                                        <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                            class="img-fluid circle mr-2" width="40" alt="">
                                    @else
                                        <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $booking?->service?->serviceDetail?->user?->image, 'profile_image') }}"
                                            alt="{{ __($booking?->service?->serviceDetail?->user?->username) }}"
                                            class="img-fluid circle mr-2 "
                                            style="border-radius: 50%;height: 40px;width: 40px;border: 2px solid #e5e5e5;">
                                    @endif
                            </span><span>{{ $booking?->service?->serviceDetail?->user?->firstname }}
                                {{ $booking?->service?->serviceDetail?->user?->lastname }}</span></a><br>

                            <label class="mt-3">Skill</label>
                            <div class="star-rating">
                                <input type="radio" class="stars" id="5-stars" name="skill" value="5" />
                                <label for="5-stars" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="4-stars" name="skill" value="4" />
                                <label for="4-stars" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="3-stars" name="skill" value="3" />
                                <label for="3-stars" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="2-stars" name="skill" value="2" />
                                <label for="2-stars" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="1-star" name="skill" value="1" />
                                <label for="1-star" class="star">&#9733;</label>
                            </div>
                            <label>Seller communication level</label>
                            <div class="star-rating">
                                <input type="radio" class="stars" id="5-stars1" name="rating1" value="5" />
                                <label for="5-stars1" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="4-stars1" name="rating1" value="4" />
                                <label for="4-stars1" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="3-stars1" name="rating1" value="3" />
                                <label for="3-stars1" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="2-stars1" name="rating1" value="2" />
                                <label for="2-stars1" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="1-stars1" name="rating1" value="1" />
                                <label for="1-stars1" class="star">&#9733;</label>
                            </div>
                            <label>Recommend to a friend</label>
                            <div class="star-rating">
                                <input type="radio" class="stars" id="5-stars2" name="rating2" value="5" />
                                <label for="5-stars2" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="4-stars2" name="rating2" value="4" />
                                <label for="4-stars2" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="3-stars2" name="rating2" value="3" />
                                <label for="3-stars2" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="2-stars2" name="rating2" value="2" />
                                <label for="2-stars2" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="1-stars2" name="rating2" value="1" />
                                <label for="1-stars2" class="star">&#9733;</label>
                            </div>
                            <label>Service as described</label>
                            <div class="star-rating">
                                <input type="radio" class="stars" id="5-stars3" name="rating3" value="5" />
                                <label for="5-stars3" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="4-stars3" name="rating3" value="4" />
                                <label for="4-stars3" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="3-stars3" name="rating3" value="3" />
                                <label for="3-stars3" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="2-stars3" name="rating3" value="2" />
                                <label for="2-stars3" class="star">&#9733;</label>
                                <input type="radio" class="stars" id="1-stars3" name="rating3" value="1" />
                                <label for="1-stars3" class="star">&#9733;</label>
                            </div>
                            <label>Comment</label>
                            <textarea rows="5" class="form-control" name="comment" placeholder="Type your comment here..." required></textarea>


                        </div>
                        <div class="modal-footer">

                            <button type="submit"
                                class="btn theme-bg text-light apply-btn rounded ">@lang('Submit')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

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
                Completed
            </p>
            <!--Breadcrumbs End-->

        </div>
        <x-buyer-service-nav :booking="$booking" activeTab="completed" />
    </div>
    <!-- card start-->
    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100">
            <p class="float-end">
                                        {{-- <a class="btn btn-sm btn-secondary bg-dgreen2 border-0" href="#"> 1 Revision Left</a> --}}
                                        {{-- <a class="btn btn-sm btn-secondary bg-dgreen2 ms-1  border-0" href="#"
                                            data-bs-toggle="modal" data-bs-target="#UploadModal" title="Upload Work Delivery">
                                            Upload Work Delivery</a>
                                        <a class="btn btn-sm btn-warning text-white ms-1  border-0" href="#" data-bs-toggle="modal"
                                            data-bs-target="#UploadModal" title="Upload Work Delivery"> Write Review</a>
                                        <a class="btn btn-sm btn-danger ms-1  border-0" href="#"><i
                                                class="fa-solid fa-circle-exclamation text-white"></i> Dispute </a> --}}
                                         <a class="btn btn-sm ms-1" href="{{ route('user', ['id' => $booking->seller_id]) }}">
                                            <i class="fa-regular fa-comment-dots fs-4 text-green"></i>
                                        </a>
                                    </p>
            <h5 class="text-dgreen2">Completed</h5>
        </div>

        <div class="w-100 bg-lgreen3 p-2 px-3 mt-3 text-dgreen fw-bold rounded"> Completed Work </div>

        {{-- @if ($allworkscount > 0)
            @foreach ($allworks as $work)
                <div class="w-100 px-3 mt-3">
                    <a class="links-c2" data-bs-toggle="collapse" href="#collapseDelivery{{ $work->id }}"
                        role="button" aria-expanded="false" aria-controls="collapseDelivery{{ $work->id }}">

                        <span class="float-end">
                            <i class="far fa-eye text-green mt-1"></i> View
                        </span>

                        <b class="fw-semibold">{{ $work->title }}</b>

                        @switch($work->status)
                            @case(0)
                                <button class="py-0 btn btn-sm btn-secondary border-0 ms-3">Pending</button>
                            @break

                            @case(1)
                                <button class="py-0 btn btn-sm btn-success bg-green border-0 ms-3">Accepted</button>
                                <button class="py-0 btn btn-sm btn-info border-0 ms-1">Awaiting Payment</button>
                            @break

                            @case(2)
                                <button class="py-0 btn btn-sm btn-primary border-0 ms-3">Delivered</button>
                            @break

                            @case(3)
                            @case(5)
                                <button class="py-0 btn btn-sm btn-danger border-0 ms-3">Declined</button>
                            @break

                            @case(4)
                                <button class="py-0 btn btn-sm btn-info border-0 ms-3">In Progress</button>
                            @break

                            @case(6)
                                <button class="py-0 btn btn-sm btn-success bg-green border-0 ms-3">Paid</button>
                            @break

                            @default
                                <button class="py-0 btn btn-sm btn-secondary border-0 ms-3">Unknown Status</button>
                        @endswitch

                        @if ($booking->status == 8)
                            <button class="py-0 btn btn-sm btn-danger border-0 ms-1">Disputed</button>
                        @endif

                        <p class="text-secondary my-1 fs-14">
                            <span class="fw-semibold">Submission Date:</span>
                            <span class="text-secondary">{{ showDateTime($work->created_at, 'd/m/Y') }}</span>
                        </p>
                    </a>
                </div>

                <div class="collapse px-3 mt-2 fs-14" id="collapseDelivery{{ $work->id }}">
                    <div class="card card-body">
                        <p class="fw-bold ms-3">Title: <span class="fw-normal">{{ $work->title }}</span></p>
                        <p class="fw-bold ms-3">Date: <span
                                class="fw-normal">{{ showDateTime($work->created_at, 'd M Y') }}</span></p>
                        <p class="fw-bold ms-3">Reason: <span class="fw-normal">{{ $work->details }}</span>
                            @if (!empty($work->work_file))
                                <a title="Download attachment"
                                    href="{{ route('user.buyer.work.response.service.download', encrypt($work->id)) }}">
                                    <i class="fa fa-download"></i>
                                </a>
                            @endif
                        </p>

                        @if ($work->status == 2 && !in_array($booking->status, [0, 3, 8]))
                            <div class="ms-3 mt-3">
                                <a data-order_number="{{ $booking->order_number }}" data-bs-toggle="modal"
                                    data-bs-target="#approvedModal" data-work="{{ $work->id }}"
                                    class="approvedBtn btn btn-sm btn-success bg-green">Accept</a>

                                <a data-order_number="{{ $booking->order_number }}" data-bs-toggle="modal"
                                    data-bs-target="#cancelModal" data-work="{{ $work->id }}"
                                    class="cancelBtn btn btn-sm btn-danger ms-2">Decline</a>
                            </div>
                        @endif

                        @if ($work->status == 3)
                            <div class="card card-body border-0 bg-light mt-3">
                                <p class="fw-bold">Date: <span
                                        class="fw-normal">{{ showDateTime($work->updated_at, 'd M Y') }}</span></p>
                                <p class="fw-bold">Reason: <span class="fw-normal">{{ $work->responce }}</span>
                                    @if (!empty($work->responce_file))
                                        <a title="Download attachment"
                                            href="{{ route('user.buyer.work.response.service.download', encrypt($work->id)) }}">
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
        @else
            <div class="w-100 px-3 mt-3">
                <p>No work has been completed yet.</p>
            </div>
        @endif --}}

        @if($allworkscount > 0)
            @foreach($allworks as $work)

                <div class="row p-3 align-items-center">
                    
                    <!-- Date -->
                    <div class="col-sm-3 col-md-2">
                        <span class="text-secondary fs-14">
                            {{ showDateTime($work->created_at, 'd/m/Y') }}
                        </span>
                    </div>

                    <!-- Message / Details -->
                    <div class="col-sm-9 col-md-6">
                        {{ $work->details }}
                    </div>

                    <!-- Status + File -->
                    <div class="col-md-4 ps-0 text-sm-center">
                        <div class="float-lg-end">

                            {{-- Status Badge --}}
                            @switch($work->status)
                                @case(0)
                                    <i class="fa-regular fa-clock text-secondary"></i> Pending
                                    @break

                                @case(1)
                                    <i class="fa-solid fa-check text-success"></i> Accepted
                                    @break

                                @case(2)
                                    <i class="fa-solid fa-check text-green"></i> Delivered
                                    @break

                                @case(3)
                                @case(5)
                                    <i class="fa-solid fa-xmark text-danger"></i> Declined
                                    @break

                                @case(4)
                                    <i class="fa-solid fa-spinner text-info"></i> In Progress
                                    @break

                                @case(6)
                                    <i class="fa-solid fa-check text-success"></i> Paid
                                    @break

                                @default
                                    <i class="fa-regular fa-circle-question text-muted"></i> Unknown
                            @endswitch


                            {{-- Download File --}}
                            @if(!empty($work->work_file))
                                <i class="fa-regular fa-file text-secondary ms-3"></i>
                                <a class="links-c ms-1"
                                href="{{ route('user.work-send-service-download', encrypt($work->id)) }}">
                                    Download File
                                </a>
                            @endif

                        </div>
                    </div>

                </div>

                {{-- If work was declined and has response --}}
                @if($work->status == 3)
                    <div class="row px-3 pb-2">
                        <div class="col-md-2"></div>
                        <div class="col-md-10 bg-light p-2 rounded">
                            <strong>Response:</strong> {{ $work->responce }}

                            @if(!empty($work->responce_file))
                                <i class="fa fa-paperclip text-secondary ms-2"></i>
                                <a href="{{ route('user.work-response-service-download', encrypt($work->id)) }}">
                                    Download Attachment
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Accept / Decline Buttons --}}
                @if($work->status == 2 && !in_array($booking->status, [0, 3, 8]))
                    <div class="row px-3 pb-3">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <a data-order_number="{{$booking->order_number}}"
                            data-bs-toggle="modal"
                            data-bs-target="#approvedModal"
                            data-work="{{$work->id}}"
                            class="approvedBtn btn btn-sm btn-success bg-green">
                                Accept
                            </a>

                            <a data-order_number="{{$booking->order_number}}"
                            data-bs-toggle="modal"
                            data-bs-target="#cancelModal"
                            data-work="{{$work->id}}"
                            class="cancelBtn btn btn-sm btn-danger ms-2">
                                Decline
                            </a>
                        </div>
                    </div>
                @endif

                <hr class="mt-3">

            @endforeach
        @else
            <div class="row p-3">
                <div class="col-12">
                    <p>No work has been completed yet.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('script')
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Reject Work Delivery')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('user.work.delivery.rejected') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="work_type" value="service">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Upload File')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Rejection Details ...."
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-white"
                            data-dismiss="modal">@lang('Close')</button>
                        <button type="submit"
                            class="disabled btn btn-success btn-rounded text-white">@lang('Submit')</button>
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

    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Work Delivery Approval Confirmation')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.work.delivery.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="totalss" value="{{ $total }}">
                        <input type="hidden" name="work_type" value="service">
                        <p>@lang('Are you sure to approved this work delivery')</p>

                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Payment Confirmation')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.work.delivery.approved.payed') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="totalss" value="{{ $total }}">
                        <input type="hidden" name="work_type" value="service">
                        <p>@lang('Are you sure to release payment ?')</p>

                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <div class="modal fade" id="disputeModal_old" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Are you sure to dispute this booking')</h2>
                    </div>
                    <form method="POST"
                        action="{{ route('user.work.dispute', ['encbuyerCounterOfferDataId' => encrypt(123)]) }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="service_id">
                        <input type="hidden" name="work_type" value="service">
                        <p>@lang('Are you sure to cancel this Proposal')</p>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="dispute" placeholder="Why dispute ...." required></textarea>
                            <small>@lang('Minimum 50 Characters Required')</small>
                        </div>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="disputeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Dispute')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form method="POST"
                    action="{{ route('user.work.dispute', ['encbuyerCounterOfferDataId' => encrypt(123)]) }}">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="service_id">
                    <input type="hidden" name="buyer_id" value="{{ $booking->user_id }}">
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <div class="modal-body">
                        @php
                            $add_ser = DB::table('additional_services')->where('booking_id', $booking->id)->get();
                        @endphp
                        @php
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
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Dispute offer')</label><br>
                                <label>Amount to dispute(in USD)</label>
                                <input name="dispute_amount" readonly class="form-control"
                                    value="{{ number_format($booking->amount, 0) }}">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Employer amount(in USD)</label>
                                <input type="number" id="totalmulti" name="buyer_amount" class="form-control" required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Freelancer amount(in USD)</label>
                                <input type="number" id="totalmulti" name="seller_amount" class="form-control"
                                    required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Message to freelancer</label>
                                <textarea rows="5" class="form-control" name="message" placeholder="Message to freelancer" required></textarea>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">

                        <button type="submit"
                            class="btn theme-bg text-light apply-btn rounded ">@lang('Submit')</button>
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
    <script type="text/javascript">
        'use strict';
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
    </script>
    </script>
@endsection
