@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <style type="text/css">
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            font-size: 1.5em;
            justify-content: left;
            text-align: center;
            width: 5em;
            margin-top: -10px;
        }

        .star-rating .stars {
            display: none !important;
            c
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }

        .star-rating :checked~label {
            color: #f90;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #fc0;
        }
    </style>
    @php
        $seller = DB::table('users')
            ->where('id', $accepteddata->seller_id)
            ->first();
        $counteroffer = DB::table('seller_counter_offers')
            ->where('offer_id', $accepteddata->offer_id)
            ->first();
        $counterofferdata = DB::table('seller_counter_offers_data')
            ->where('counter_offer_id', $counteroffer->id)
            ->where('offer_id', $accepteddata->offer_id)
            ->first();
        $payable = DB::table('job_work_delivery_houly_offer')
            ->where('data_id', @$counterofferdata->id)
            ->where('job_id', $accepteddata->job_id)
            ->where('offer_id', $accepteddata->offer_id)
            ->where('is_payable', 1)
            ->count();
    @endphp
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
                            ->where('data_id', @$accepteddata->id)
                            ->where('type', 'offer')
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
                                <a href="{{ route('profile', [slug($seller->username), encrypt($seller->id)]) }}">
                                    @if ($seller->image == '')
                                        <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                            class="img-fluid circle mr-2" width="40" alt="">
                                    @else
                                        <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $seller->image, 'profile_image') }}"
                                            alt="{{ __($seller->username) }}" class="img-fluid circle mr-2 "
                                            style="border-radius: 50%;height: 40px;width: 40px;border: 2px solid #e5e5e5;">
                                    @endif
                            </span><span>{{ $seller->firstname }} {{ $seller->lastname }}</span></a><br>

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
                    <h4 class="modal-title" id="ModalLabel">@lang('End Contract')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($payable > 0)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-danger" role="alert">
                                    Error: Please process all your pending payment to end this contract.
                                </div>
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('user.buyer.rating') }}">
                            @csrf
                            <input type="hidden" name="seller_id">
                            <input type="hidden" name="buyer_id">
                            <input type="hidden" name="data_id" value="{{ $accepteddata->id }}">
                            <div class="modal-body">

                                <h5>Rating & review</h5>
                                <span class="font-weight-bold">How was your work experience with:
                                    <a href="{{ route('profile', [slug($seller->username), encrypt($seller->id)]) }}">
                                        @if ($seller->image == '')
                                            <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                                class="img-fluid circle mr-2" width="40" alt="">
                                        @else
                                            <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . $seller->image, 'profile_image') }}"
                                                alt="{{ __($seller->username) }}" class="img-fluid circle mr-2 "
                                                style="border-radius: 50%;height: 40px;width: 40px;border: 2px solid #e5e5e5;">
                                        @endif
                                </span><span>{{ $seller->firstname }} {{ $seller->lastname }}</span></a><br>
                                <label>Reason for ending contract</label>
                                <select class="form-control" name="reason">
                                    <option value="Job completed successfully">Job completed successfully</option>
                                    <option value="Not satisfy with work">Not satisfy with work</option>
                                    <option value="Very lazy freelancer">Very lazy freelancer</option>
                                </select>
                                <label class="mt-3">Skill</label>
                                <div class="star-rating">
                                    <input type="radio" class="stars" id="5-stars" name="skill"
                                        value="5" />
                                    <label for="5-stars" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="4-stars" name="skill"
                                        value="4" />
                                    <label for="4-stars" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="3-stars" name="skill"
                                        value="3" />
                                    <label for="3-stars" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="2-stars" name="skill"
                                        value="2" />
                                    <label for="2-stars" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="1-star" name="skill"
                                        value="1" />
                                    <label for="1-star" class="star">&#9733;</label>
                                </div>
                                <label>Seller communication level</label>
                                <div class="star-rating">
                                    <input type="radio" class="stars" id="5-stars1" name="rating1"
                                        value="5" />
                                    <label for="5-stars1" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="4-stars1" name="rating1"
                                        value="4" />
                                    <label for="4-stars1" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="3-stars1" name="rating1"
                                        value="3" />
                                    <label for="3-stars1" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="2-stars1" name="rating1"
                                        value="2" />
                                    <label for="2-stars1" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="1-stars1" name="rating1"
                                        value="1" />
                                    <label for="1-stars1" class="star">&#9733;</label>
                                </div>
                                <label>Recommend to a friend</label>
                                <div class="star-rating">
                                    <input type="radio" class="stars" id="5-stars2" name="rating2"
                                        value="5" />
                                    <label for="5-stars2" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="4-stars2" name="rating2"
                                        value="4" />
                                    <label for="4-stars2" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="3-stars2" name="rating2"
                                        value="3" />
                                    <label for="3-stars2" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="2-stars2" name="rating2"
                                        value="2" />
                                    <label for="2-stars2" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="1-stars2" name="rating2"
                                        value="1" />
                                    <label for="1-stars2" class="star">&#9733;</label>
                                </div>
                                <label>Service as described</label>
                                <div class="star-rating">
                                    <input type="radio" class="stars" id="5-stars3" name="rating3"
                                        value="5" />
                                    <label for="5-stars3" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="4-stars3" name="rating3"
                                        value="4" />
                                    <label for="4-stars3" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="3-stars3" name="rating3"
                                        value="3" />
                                    <label for="3-stars3" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="2-stars3" name="rating3"
                                        value="2" />
                                    <label for="2-stars3" class="star">&#9733;</label>
                                    <input type="radio" class="stars" id="1-stars3" name="rating3"
                                        value="1" />
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Payment You Order')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="POST" action="{{ route('user.buyer.pay.offer.hourly') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="amount">
                        <input type="hidden" name="acceptedoffer" value="{{ $acceptedoffer->id }}">
                        <div class="form-group">
                            <h5>@lang('How you want to pay')</h5>
                            <select class="form-control" name="payment">
                                <option value="wallet">{{ __($general->sitename) }} @lang('wallet')</option>
                                <!-- <option value="checkout">@lang('Checkout')</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-white"
                            data-dismiss="modal">@lang('Close')</button>
                        <button type="submit"
                            class="btn theme-bg text-light apply-btn rounded full-width">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                        <h2 class="m-0 ft-regular">@lang('Accept Submission ')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.accept.timesheet.offer') }}">
                        @csrf
                        <input type="hidden" name="approved">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to approved this work timesheet')

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
    <div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Declined Submission ')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.rejected.timesheet.offer') }}">
                        @csrf
                        <input type="hidden" name="rejected">
                        <input type="hidden" name="confirm" value="cancel">
                        <p>@lang('Are you sure to decline this timesheet')</p>
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Attachment')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="reason" placeholder="Describe Your Reason ...." required></textarea>
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
    <div class="dashboard-content">
        <!-- <div class="dashboard-tlbar d-block mb-5">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                            <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard-manage-service.html" class="theme-cl">Manage Services</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div> -->
        <!-- <div class="dashboard-widg-bar d-block">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">@lang('Job')</th>
                                        <th scope="col">@lang('Employ')</th>
                                        <th scope="col">@lang('Amount')</th>
                                        <th scope="col">@lang('Working Status')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hireEmploys as $hireEmploy)
    <tr>
                                        <td>
                                            <img src="{{ getImage('assets/images/job/' . $hireEmploy->job->image, '590x300') }}" alt="@lang('Job Image')" class="img-fluid circle" width="50" />
                                        </td>
                                        <td>{{ __(str_limit($hireEmploy->job->title, 20)) }}</td>
                                        <td>{{ __(@$hireEmploy->user->fullname) }} <a href="{{ route('profile', [slug(@$hireEmploy->job->user->username), encrypt(@$hireEmploy->job->user->id)]) }}" >{{ $hireEmploy->job->user->username }}</a></td>
                                        <td>{{ showAmount($hireEmploy->bid_price) }} {{ __($general->cur_text) }}</td>
                                        <td>
                                            @if ($hireEmploy->working_status == 0)
    <span class="badge badge-primary">@lang('Pending')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 1)
    <span class="badge badge-success">@lang('Completed')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 2)
    <span class="badge badge-secondary">@lang('Delivered')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 3)
    <span class="badge badge-danger">@lang('Cancel')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 4)
    <span class="badge badge-info">@lang('In Progress')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 5)
    <span class="badge badge-danger">@lang('Delivery Expired')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 6)
    <span class="badge badge-warning">@lang('Dispute')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
    @endif
                                        </td>
                                        <td style="width:10%;">
                                            @if ($hireEmploy->status == 1)
    <span class="badge badge-success">@lang('Running')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 2)
    <span class="badge badge-warning">@lang('Payable Both')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 3)
    <span class="badge badge-success">@lang('Paid')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 4)
    <span class="badge badge-info">@lang('Refund')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
    @endif
                                        </td>
                                        <td>
                                            <div class="dash-action-98563">
                                                <a href="{{ route('user.buyer.hire.employ.details', encrypt($hireEmploy->id)) }}" class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="lni lni-eye"></i></a>
                                                @if ($hireEmploy->working_status == 0)
    <a href="javascript:void(0);" data-order_number="{{ $hireEmploy->id }}" data-order_number="{{ $hireEmploy->id }}" data-toggle="modal" data-target="#approvedModal" class="approvedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i class="lni lni-pencil"></i></a>
                                                <a data-toggle="modal" data-target="#cancelModal" href="javascript:void(0);" data-order_number="{{ $hireEmploy->id }}" class=" cancelBtn p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="lni lni-trash-can"></i></a>
    @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <style type="text/css">
            .btn {
                padding: 7px;
            }
        </style>
        <div class="dashboard-widg-bar d-block ">
            <div class="row">
                <div class="col-lg-12 pb-3">

                    @php

                        $accepted1 = DB::table('user_biding')
                            ->where('job_id', $singlejob->id)
                            ->where('seller_offer_responce', '2')
                            ->first();
                        $accepted2 = DB::table('user_offers')
                            ->where('job_id', $singlejob->id)
                            ->where('buyer_offer_responce', '2')
                            ->first();
                    @endphp
                    <!-- <a href="{{ route('user.buyer.hire.employ') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium " >Open jobs</a> -->
                    @if (@$accepted1->seller_offer_responce == '2')
                        <a href="{{ route('user.buyer.hire.employ.bids', encrypt($singlejob->id)) }}"
                            class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium "><i
                                class="fa fa-angle-double-left"></i>Back</a>
                    @elseif(@$accepted2->buyer_offer_responce == '2')
                        <a href="{{ route('user.buyer.hire.employ.offers', encrypt($singlejob->id)) }}"
                            class="btn rounded  theme-bg-light theme-cl fs-sm ft-medium ml-2"><i
                                class="fa fa-angle-double-left"></i>Back</a>
                    @endif
                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                        @if (@$accepted1->seller_offer_responce == '2')
                            <a href="{{ route('user.buyer.hire.employ.acceptedbidoffer', encrypt($singlejob->id)) }}"
                                class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Bid Overview</a>
                        @elseif(@$accepted2->buyer_offer_responce == '2')
                            <a href="{{ route('user.buyer.hire.employ.acceptedbidoffer', encrypt($singlejob->id)) }}"
                                class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Offer Overview</a>
                        @endif
                    @endif
                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                        @if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'per_hour')
                            <a href="{{ route('user.buyer.hire.employ.timesheet', encrypt($singlejob->id)) }}"
                                class="btn rounded  theme-bg text-light fs-sm ft-medium ml-2">Hourly Timesheet</a>
                        @else
                            <a href="{{ route('user.buyer.hire.employ.workspace', encrypt($singlejob->id)) }}"
                                class="btn rounded  theme-bg text-light fs-sm ft-medium ml-2">Workspace</a>
                        @endif
                        <!-- <a href="{{ route('user.buyer.hire.employ.hourlytimesheet') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Timesheet(Hourly)</a> -->
                        <!-- <a href="{{ route('user.buyer.hire.employ.completed') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Completed</a>
                    <a href="{{ route('user.buyer.hire.employ.closed') }}" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Closed</a> -->
                        <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Chat with
                            seller</a>
                        <a href="{{ route('user.buyer.hire.employ.invoicetab', encrypt($singlejob->id)) }}"
                            class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Invoice</a>

                        <a href="{{ route('user.buyer.hire.employ.rateandreview', encrypt($singlejob->id)) }}"
                            class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Rating & review</a>
                    @endif
                </div>

            </div>
            <div class="workboxmain mt-3">
                <div class="row">
                    <div class="col-lg-12 topboxwork">
                        @if ($accepteddata->is_endcontract == 1)
                            <a data-toggle="modal" data-target="#replyModal"
                                style="border: 1px solid #ea2b33 !important;" href="javascript:void(0);"
                                class=" btn rounded bg-white  text-danger fs-sm ft-medium mr-2">@lang('Contract Ended')</a>
                        @else
                            <a data-toggle="modal" data-target="#contractModal" href="javascript:void(0);"
                                data-buyer_id="{{ $accepteddata->buyer_id }}"
                                data-seller_id="{{ $accepteddata->seller_id }}"
                                class="contractBtn btn rounded bg-danger text-white border fs-sm ft-medium mr-2">@lang('End Contract')</a>
                        @endif
                    </div>
                    <!-- <div class="col-lg-12 topboxwork">
                        <a data-target="#workModal" href="javascript:void(0);" class="workBtn btn rounded theme-bg text-light fs-sm ft-medium mr-2" >Add Timesheet </a>
                    </div> -->
                    <!-- work one -->
                    {{-- <div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12 col-12">
                            <h5>Timsheet 1 &nbsp <span style="font-size: 10px;" class="badge badge-success">@lang('payed')</span></h5>
                            <p><span class="font-weight-normal"> $20.00/hour</span>  &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span class="font-weight-normal"><i class="fa fa-clock"></i> 4 hour 10 min</span> &nbsp &nbsp <span class="font-weight-normal"><i class="fa fa-clock"></i> Cost: $200</span> &nbsp &nbsp <a href="#"><span class="font-weight-normal"><i class="fa fa-paperclip"></i> </span></a>  </p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right" style="padding-top: 12px;">

                            <!-- <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a> -->
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->


                        </div>
                    </div>

                </div>
                <div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <h5>Timsheet 2 &nbsp <span style="font-size: 10px;" class="badge badge-danger">@lang('Declined')</span></h5>
                            <p><span class="font-weight-normal"> $20.00/hour</span>  &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span class="font-weight-normal"><i class="fa fa-clock"></i> 4 hour 10 min</span> &nbsp &nbsp <span class="font-weight-normal"> Cost: $200</span>&nbsp &nbsp &nbsp <a href="#"><span class="font-weight-normal"><i class="fa fa-paperclip"></i> </span></a>  </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right" style="padding-top: 12px;">

                            <!-- <a href="#" class=" btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-trash"></i> </a> -->
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->


                        </div>
                    </div>
                    <div class="row showresponce " >



                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">

                            <p><span class="font-weight-bold">Reason:</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl, vestibulum in lectus a, lobortis elementum velit.   <a href=""><i class="fa fa-paperclip"></i></a> </p>
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a> -->

                        </div>
                    </div>

                </div>
                --}}

                    @php
                        $seller = DB::table('users')
                            ->where('id', $accepteddata->seller_id)
                            ->first();
                        $counteroffer = DB::table('seller_counter_offers')
                            ->where('offer_id', $accepteddata->offer_id)
                            ->first();
                        $counterofferdata = DB::table('seller_counter_offers_data')
                            ->where('counter_offer_id', $counteroffer->id)
                            ->where('offer_id', $accepteddata->offer_id)
                            ->first();
                        $works = DB::table('job_work_delivery_houly_offer')
                            ->where('data_id', @$counterofferdata->id)
                            ->where('job_id', $accepteddata->job_id)
                            ->where('offer_id', $accepteddata->offer_id)
                            ->get();
                    @endphp
                    @if ($works->count() > 0)
                        @foreach ($works as $work)
                            <div class="col-lg-12 innerbox">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-12">
                                        <h5>{{ $work->title }} &nbsp
                                            @if ($work->status == '2')
                                                <span style="font-size: 10px;"
                                                    class="badge badge-warning">@lang('pending')</span>
                                            @elseif($work->status == '1')
                                                <span style="font-size: 10px;"
                                                    class="badge badge-success">@lang('accepted')</span>
                                            @elseif($work->status == '3')
                                                <span style="font-size: 10px;"
                                                    class="badge badge-danger">@lang('declined')</span>
                                            @elseif($work->status == '5')
                                                <span style="font-size: 10px;"
                                                    class="badge badge-success">@lang('completed')</span>
                                            @elseif($work->status == '6')
                                                <span style="font-size: 10px;"
                                                    class="badge badge-danger">@lang('dispute')</span>
                                            @endif
                                        </h5>
                                        <p><span class="font-weight-normal">
                                                <span>$</span>{{ $counterofferdata->amount }}/hour</span> &nbsp &nbsp <span
                                                class="font-weight-normal"><i class="lni lni-calendar"></i>
                                                {{ showDateTime($work->date_start, 'd M Y') }} -
                                                {{ showDateTime($work->date_end, 'd M Y') }}</span> &nbsp &nbsp <span
                                                class="font-weight-normal"><i class="fa fa-clock"></i>
                                                {{ $work->time }} hour {{ $work->minutes }} min</span> &nbsp &nbsp <span
                                                class="font-weight-normal"> Cost:
                                                <span>$</span>{{ $work->total_cost }}</span>

                                        </p>
                                    </div>
                                    <div class="ol-lg-3 col-md-3 col-sm-12 col-12 text-right" style="padding-top: 12px;">
                                        @if ($work->status == '2')
                                            <a data-target="#approvedModal" data-approved_id="{{ @$work->id }}"
                                                href="javascript:void(0);"
                                                class="approvedBtn btn rounded theme-bg text-light fs-sm ft-medium mr-2"><i
                                                    class="fa fa-check"></i> </a>
                                            <a data-target="#rejectedModal" data-rejected_id="{{ @$work->id }}"
                                                href="javascript:void(0);"
                                                class="rejectedBtn btn rounded bg-danger text-white fs-sm ft-medium mr-2"><i
                                                    class="fa fa-times"></i> </a>
                                        @endif
                                        @if ($work->status == '5')
                                            <a href="javascript:void(0);"
                                                class="btn rounded theme-bg text-light  fs-sm ft-medium mr-2">Payed </a>
                                        @endif
                                        @if ($work->status == '1')
                                            @if ($work->status == '5')
                                            @else
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#payModal"
                                                    data-amount="{{ @$work->total_cost }}"
                                                    data-work_id="{{ @$work->id }}"
                                                    class="payBtn btn rounded theme-bg text-light  fs-sm ft-medium mr-2">Pay
                                                </a>
                                            @endif
                                        @endif


                                    </div>
                                </div>
                                <div class="row showresponce ">



                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">

                                        <p><span class="font-weight-bold">Detail:</span>{{ $work->details }} @if (@$work->file == '')
                                            @else
                                                <a title="Download attachment"
                                                    href="{{ route('user.buyer.timesheet.bid.file.download', encrypt(@$work->id)) }}"><i
                                                        class="fa fa-paperclip"></i></a>
                                            @endif
                                        </p>

                                    </div>
                                    @if ($work->status == '3')
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2"
                                            style="background:#F6F8F7;padding: 10px;border-radius: 10px !important;">
                                            <p><span class="font-weight-bold">Reason:</span>{{ $work->reason }}
                                                @if (@$work->response_file == '')
                                                @else
                                                    <a title="Download attachment"
                                                        href="{{ route('user.buyer.timesheet.response.bid.file.download', encrypt(@$work->id)) }}"><i
                                                            class="fa fa-paperclip"></i></a>
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12 innerbox">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6>No Timesheet Found</h6>
                                </div>
                            </div>


                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script type="text/javascript">
            "use strict";
            $('.approvedBtn').on('click', function() {
                var modal = $('#approvedModal');
                modal.find('input[name=approved]').val($(this).data('approved_id'))
                modal.modal('show');
            });
            $('.rejectedBtn').on('click', function() {
                var modal = $('#rejectedModal');
                modal.find('input[name=rejected]').val($(this).data('rejected_id'))
                modal.modal('show');
            });
            $('.contractBtn').on('click', function() {
                var modal = $('#contractModal');
                modal.find('input[name=buyer_id]').val($(this).data('buyer_id'))
                modal.find('input[name=seller_id]').val($(this).data('seller_id'))
                modal.modal('show');
            });
            $('.payBtn').on('click', function() {
                var modal = $('#payModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.find('input[name=amount]').val($(this).data('amount'))
                modal.modal('show');
            });
        </script>
    @endsection
