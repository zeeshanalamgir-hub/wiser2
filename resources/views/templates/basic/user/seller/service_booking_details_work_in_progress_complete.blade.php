@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="modal fade" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
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
                        $reviewer = DB::table('users')->where('id', @$review->user_id)->first();
                       @endphp
                    <form method="POST" action="{{route('user.rating.reply.service')}}">
                        @csrf
                        <input type="hidden" name="review_id" value="{{@$review->id}}">
                        <div class="modal-body">

                            <h5>Rating & review</h5>
                            <span class="font-weight-bold">Review by:
                                <a href="{{route('profile', [slug(@$reviewer->username), encrypt(@$reviewer->id)])}}">
                                    @if(@$reviewer->image == '')
                                        <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                            class="img-fluid circle mr-2" width="40" alt="">
                                    @else
                                        <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . @$reviewer->image, 'profile_image') }}"
                                            alt="{{__(@$reviewer->username)}}" class="img-fluid circle mr-2 "
                                            style="border-radius: 50%;height: 40px;width: 40px;border: 2px solid #e5e5e5;">
                                    @endif
                            </span><span>{{@$reviewer->firstname}} {{@$reviewer->lastname}}</span></a><br>

                            <label class="mt-3">Rating</label><br>
                            @if(@$review->rating == 5)
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
                            <br>

                            @if(@$review->skill == 5)
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
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">1</span></span>
                            @endif
                            <br>
                            <label style="margin-top: 10px;">Seller communication level</label><br>
                            @if(@$review->communicationrating == 5)
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
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">1</span></span>
                            @endif
                            <br><label style="margin-top: 10px;">Recommend to a friend</label><br>
                            @if(@$review->recommendrating == 5)
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
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">1</span></span>
                            @endif
                            <br><label style="margin-top: 10px;">Service as described</label><br>
                            @if(@$review->describedrating == 5)
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
                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <span
                                        style="color:gold;">1</span></span>
                            @endif
                            <br> <label style="margin-top: 10px;">Comment</label>
                            <p>{{@$review->review}}</p>
                            @if(@$review->reply == '')
                                <label> Your Reply</label>
                                <textarea rows="5" class="form-control" name="reply" placeholder="Type your reply here..."
                                    required></textarea>
                            @else
                                <label>Your Reply</label>
                                <p>{{@$review->reply}}</p>
                            @endif

                        </div>
                        @if(@@$review->reply == '')
                            <div class="modal-footer">

                                <button type="submit"
                                    class="btn theme-bg text-light apply-btn rounded ">@lang('Submit')</button>
                            </div>
                        @endif
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
                Completed
            </p>
            <!--Breadcrumbs End-->

        </div>
        <x-seller-service-nav :booking="$booking" activeTab="completed" />
    </div>

    <!-- card start-->
    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <div class="w-100">
             <p class="float-end">
                {{-- <a class="btn btn-sm btn-secondary bg-dgreen2 border-0" href="#"> 1 Revision Left</a>
                <a class="btn btn-sm btn-secondary bg-dgreen2 ms-1  border-0" href="#"
                    data-bs-toggle="modal" data-bs-target="#UploadModal" title="Upload Work Delivery">
                    Upload Work Delivery</a>
                <a class="btn btn-sm btn-warning text-white ms-1  border-0" href="#" data-bs-toggle="modal"
                    data-bs-target="#UploadModal" title="Upload Work Delivery"> Write Review</a>
                <a class="btn btn-sm btn-danger ms-1  border-0" href="#"><i
                        class="fa-solid fa-circle-exclamation text-white"></i> Dispute </a> --}}
                <a class="btn btn-sm " href="{{ route('user', ['id' => $booking->buyer_id]) }}"> <i
                        class="fa-regular fa-comment-dots fs-4 text-green"></i></a>
                    Chat with <span class="text-green">{{ $booking->buyer->firstname }} {{ $booking->buyer->lastname }}</span>

            </p>
            <h5 class="text-dgreen2">Completed</h5>
        </div>

        <div class="w-100 bg-lgreen3 p-2 px-3 mt-3 text-dgreen fw-bold rounded"> COMPLETED WORK </div>

        {{-- @if($allworkscount > 0)
            @foreach($allworks as $work)
                <div class="w-100 px-3 mt-3">
                    <a class="links-c2" data-bs-toggle="collapse" href="#collapseDelivery{{$work->id}}" role="button"
                        aria-expanded="false" aria-controls="collapseDelivery{{$work->id}}">

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

                        @if($booking->status == 8)
                            <button class="py-0 btn btn-sm btn-danger border-0 ms-1">Disputed</button>
                        @endif

                        <p class="text-secondary my-1 fs-14">
                            <span class="fw-semibold">Submission Date:</span>
                            <span class="text-secondary">{{ showDateTime($work->created_at, 'd/m/Y') }}</span>
                        </p>
                    </a>
                </div>

                <div class="collapse px-3 mt-2 fs-14" id="collapseDelivery{{$work->id}}">
                    <div class="card card-body">
                        <p class="fw-bold ms-3">Title: <span class="fw-normal">{{ $work->title }}</span></p>
                        <p class="fw-bold ms-3">Date: <span class="fw-normal">{{ showDateTime($work->created_at, 'd M Y') }}</span></p>
                        <p class="fw-bold ms-3">Reason: <span class="fw-normal">{{ $work->details }}</span>
                            @if(!empty($work->work_file))
                                <a title="Download attachment" href="{{ route('user.work-send-service-download', encrypt($work->id)) }}">
                                    <i class="fa fa-download"></i>
                                </a>
                            @endif
                        </p>

                        @if($work->status == 2 && !in_array($booking->status, [0, 3, 8]))
                            <div class="ms-3 mt-3">
                                <a data-order_number="{{$booking->order_number}}" data-bs-toggle="modal" data-bs-target="#approvedModal"
                                data-work="{{$work->id}}"
                                class="approvedBtn btn btn-sm btn-success bg-green">Accept</a>

                                <a data-order_number="{{$booking->order_number}}" data-bs-toggle="modal" data-bs-target="#cancelModal"
                                data-work="{{$work->id}}"
                                class="cancelBtn btn btn-sm btn-danger ms-2">Decline</a>
                            </div>
                        @endif

                        @if($work->status == 3)
                            <div class="card card-body border-0 bg-light mt-3">
                                <p class="fw-bold">Date: <span class="fw-normal">{{ showDateTime($work->updated_at, 'd M Y') }}</span></p>
                                <p class="fw-bold">Reason: <span class="fw-normal">{{ $work->responce }}</span>
                                    @if(!empty($work->responce_file))
                                        <a title="Download attachment" href="{{ route('user.work-response-service-download', encrypt($work->id)) }}">
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

                    <div class="col-sm-3 col-md-2">
                        <span class="text-secondary fs-14">
                            {{ showDateTime($work->created_at, 'd/m/Y') }}
                        </span>
                    </div>

                    <div class="col-sm-9 col-md-6">
                        {{ $work->details }}
                    </div>

                    <div class="col-md-4 ps-0 text-sm-center">
                        <div class="float-lg-end">

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
