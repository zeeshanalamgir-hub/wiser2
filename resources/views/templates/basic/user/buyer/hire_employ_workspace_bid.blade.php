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

        }

        .header-top-button {
            border: 2px solid #003F45;
            padding: 10px 30px 10px 30px;
            font-weight: 700;
            color: #003F45;
        }

        .header-top-button-active {
            background-color: #00C535;
            padding: 10px 30px 10px 30px;
            font-weight: 700;
            color: white;
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

        $accepted1 = DB::table('user_biding')
            ->where('job_id', $singlejob->id)
            ->where('seller_offer_responce', '2')
            ->first();
        $accepted2 = DB::table('user_offers')
            ->where('job_id', $singlejob->id)
            ->where('buyer_offer_responce', '2')
            ->first();
        $seller = DB::table('users')
            ->where('id', $accepteddata->seller_id)
            ->first();
        $counteroffer = DB::table('buyer_counter_offers')
            ->where('bid_id', $accepteddata->bid_id)
            ->first();
        $counterofferdata = DB::table('buyer_counter_offers_data')
            ->where('counter_offer_id', $counteroffer->id)
            ->where('bid_id', $accepteddata->bid_id)
            ->get();
        $payable = DB::table('buyer_counter_offers_data')
            ->where('counter_offer_id', $counteroffer->id)
            ->where('bid_id', $accepteddata->bid_id)
            ->where('is_payable', 1)
            ->count();
    @endphp
    <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Contract Ended')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $review = DB::table('review_ratings')
                            ->where('data_id', @$accepteddata->id)
                            ->where('type', 'bid')
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
                        <form method="POST" action="{{ route('user.buyer.rating.bid') }}">
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
                <form method="POST" action="{{ route('user.buyer.dispute.work.bid') }}">
                    @csrf
                    <input type="hidden" name="accepted_id">
                    <input type="hidden" name="bid_type">
                    <input type="hidden" name="work_id">

                    <div class="modal-body">


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
                                <label>Amount to dispute</label>
                                <input name="dispute_amount" readonly class="form-control">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Employer amount</label>
                                <input id="totalmulti" name="buyer_amount" class="form-control" required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Freelancer amount</label>
                                <input id="totalmulti" name="seller_amount" class="form-control" required>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label>Message to freelancer</label>
                                <textarea rows="5" class="form-control" name="seller_message" placeholder="Message to freelancer" required></textarea>
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
    <div class="modal fade" id="reqmsacceptModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Accept Milestone')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.accept.extra.work.milestone.bid') }}">
                        @csrf
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to accept this milestone?')
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
    <div class="modal fade" id="reqmsrejectModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Decline Milestone')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.reject.extra.work.milestone.bid') }}">
                        @csrf
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to decline this milestone?')
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
    <div class="modal fade" id="reqmsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Request Milestone')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('user.buyer.request.work.milestone.bid') }}">
                    @csrf
                    <input type="hidden" name="job_id">
                    <input type="hidden" name="bid_id">
                    <div class="modal-body">
                        <div class="row ">
                            <div class="col-md-10 dynamic-field " id="dynamic-field-1">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="field" class="hidden-md">Milestone Title</label>
                                        <input type="text" id="field" placeholder="Milestone Title"
                                            class="form-control" name="milestone_title[]" required />

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="field" class="hidden-md">Bid Amount</label>
                                            <input type="number" class="form-control bidamountmulti" placeholder="$0"
                                                name="milestone_amount[]" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Days</label>
                                            <input type="number" class="form-control"
                                                name="milestone_due_date[] required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 append-buttons" style="margin-top:2rem !important">
                                <div class="clearfix">
                                    <button type="button" id="add-button"
                                        class="btn rounded theme-bg text-light fs-sm ft-mediume float-left shadow-sm"><i
                                            class="fa fa-plus fa-fw"></i>
                                    </button>
                                    <button type="button" id="remove-button"
                                        class="btn rounded bg-danger text-light fs-sm ft-mediume float-left shadow-sm ml-1"
                                        style="width:10p" disabled="disabled"><i class="fa fa-minus fa-fw"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit"
                            class="btn theme-bg text-light apply-btn rounded ">@lang('Request')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Payment You Order')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('user.buyer.pay.bid') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="work_id" id="work_id"> <!-- Updated here -->
                        <input type="hidden" name="amount">
                        <input type="hidden" name="value">
                        <input type="hidden" value="{{ $acceptedbid->id }}" name="acceptedbid">
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
                        <h2 class="m-0 ft-regular">@lang('Accept Work')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.accept.work.bid') }}">
                        @csrf
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to approved this work delivery?')
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
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
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
                        <h2 class="m-0 ft-regular">@lang('Decline Work')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.buyer.reject.work.bid') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="work_id">
                        <input type="hidden" name="confirm" value="cancel">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Attachment')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Details ...." required></textarea>
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
    <div class="dashboard-content">
        <div class="container-header-table-100095">

            @include('templates.basic.user.buyer.components.buyer-hire-employees-timesheet-top-button')

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table text-center">
                                <thead class="thead-dark">
                                </thead>
                                @php
                                    $seller = DB::table('users')
                                        ->where('id', $accepteddata->seller_id)
                                        ->first();
                                    $counteroffer = DB::table('buyer_counter_offers')
                                        ->where('bid_id', $accepteddata->bid_id)
                                        ->first();
                                    $counterofferdata = DB::table('buyer_counter_offers_data')
                                        ->where('counter_offer_id', $counteroffer->id)
                                        ->where('bid_id', $accepteddata->bid_id)
                                        ->get();
                                    $Jobname = DB::table('jobs')
                                        ->where('id', $accepteddata->job_id)
                                        ->first();
                                @endphp
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="workboxmain" style="padding-top: 10px">
                <div class="form-heading">
                    Bid Details
                </div>
                <div class="row">

                    @foreach ($counterofferdata as $cd)
                        @php
                            $works = DB::table('job_work_delivery_bid')
                                ->where('data_id', @$cd->id)
                                ->where('job_id', $accepteddata->job_id)
                                ->where('bid_id', $accepteddata->bid_id)
                                ->get();
                        @endphp
                        @php
                            $dispute = DB::table('job_dispute_bid')
                                ->where('work_id', @$cd->id)
                                ->where('job_id', $accepteddata->job_id)
                                ->where('bid_id', $accepteddata->bid_id)
                                ->first();
                            $getdatadispute = DB::table('job_dispute_bid_data')
                                ->where('dispute_id', @$dispute->id)
                                ->where('status', 1)
                                ->first();

                        @endphp
                        <div class="col-lg-12 innerbox">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                                    <h5>
                                        @if ($counteroffer->type == 'bymilestone')
                                            {{ $cd->title }}
                                        @elseif($counteroffer->type == 'byproject')
                                            {{ $Jobname->title }}
                                        @endif
                                        @if ($cd->status == '6')
                                            @if ($dispute->status == 1)
                                                <span style="font-size: 10px;padding: 10px 25px 10px 25px"
                                                    class="badge badge-success">Dispute Offer
                                                    Accepted</span>
                                            @elseif($dispute->status == 2)
                                                <span style="font-size: 10px;padding: 10px 25px 10px 25px"
                                                    class="badge badge-primary">dispute send to
                                                    admin</span>
                                            @else
                                                <span style="font-size: 10px;padding: 10px 25px 10px 25px"
                                                    class="badge badge-danger">in
                                                    dispute</span>
                                            @endif
                                        @else
                                            @if ($cd->status == '1')
                                                <span style="font-size: 10px;padding: 10px 25px 10px 25px"
                                                    class="badge badge-success">accepted</span>
                                            @elseif($cd->status == '2')
                                                <span style="font-size: 10px;padding: 10px 25px 10px 25px"
                                                    class="badge badge-primary">delivered</span>
                                            @elseif($cd->status == '0')
                                                <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                    class="badge badge-warning">pending</span>
                                            @elseif($cd->status == 5)
                                                <span style="font-size: 10px;padding: 10px 25px 10px 25px"
                                                    class="badge badge-success">@lang('completed')</span>
                                            @else
                                                <span style="font-size: 10px;" class="badge badge-info">Revision
                                                    Requested</span>
                                            @endif
                                        @endif
                                    </h5>
                                    <p>
                                        @if ($cd->status == '6')
                                            @if ($dispute->status == 1)
                                                <span class="font-weight-normal"><del> $ {{ $cd->amount }} </del></span>
                                                &nbsp <span class="font-weight-normal"> $
                                                    {{ $getdatadispute->seller_amount }}</span>
                                            @elseif($dispute->status == 2)
                                                <span class="font-weight-normal"> $ {{ $cd->amount }}</span>
                                            @else
                                                <span class="font-weight-normal"> $ {{ $cd->amount }}</span>
                                            @endif
                                        @else
                                            @if ($cd->disputed_amount != 0)
                                                <span class="font-weight-normal"><del> $ {{ $cd->amount }} </del></span>
                                                &nbsp <span class="font-weight-normal"> $
                                                    {{ $cd->disputed_amount }}</span>
                                            @else
                                                <span class="font-weight-normal"> $ {{ $cd->amount }}</span>
                                            @endif
                                        @endif
                                        @php
                                            $backrev = $cd->revisions;
                                            $nowrev = $backrev - 1;
                                        @endphp
                                        &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i>
                                            Revisions:
                                            @if ($nowrev <= 0)
                                                0
                                            @else
                                                {{ $nowrev }}
                                            @endif
                                        </span> &nbsp &nbsp <span class="font-weight-normal"><i
                                                class="lni lni-calendar"></i>
                                            {{ showDateTime($cd->created_at, 'd M Y') }}</span>
                                        &nbsp &nbsp
                                    </p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-right" style="padding-top: 12px;">
                                    @if ($cd->milestone_status == '1')
                                        @if ($works->count() > 0)
                                            <a href="javascript:void(0);" title="View Detail"
                                                data-work_id="{{ @$cd->id }}"
                                                class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2"><i
                                                    class="fa fa-eye"></i> </a>
                                        @else
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-warning">@lang('work pending')</span>
                                        @endif
                                        @if ($cd->status == '5')
                                            <a href="javascript:void(0);"
                                                class="btn rounded theme-bg text-light  fs-sm ft-medium mr-2">Payed </a>
                                        @endif
                                        @if ($cd->status == '1')
                                            @if ($cd->status == '5')
                                            @else
                                                <a href="javascript:void(0);" data-work_id="{{ @$work->id }}"
                                                    data-toggle="modal" data-target="#payModal"
                                                    class="approvedBtn btn btn-sm rounded theme-bg-light theme-cl ft-medium ml-2 float-left"
                                                    style="padding:7px;">Accept</a>
                                            @endif
                                        @endif
                                        @if ($counteroffer->type == 'bymilestone' || $counteroffer->type == 'byproject')
                                            @if ($works->count() > 0)
                                                @if ($cd->status == '6')
                                                    @php
                                                        $dis_id = DB::table('job_dispute_bid')
                                                            ->where('work_id', @$cd->id)
                                                            ->where('job_id', $accepteddata->job_id)
                                                            ->where('bid_id', $accepteddata->bid_id)
                                                            ->first();
                                                        $getdatadispute = DB::table('job_dispute_bid_data')
                                                            ->where('dispute_id', @$dis_id->id)
                                                            ->where('status', 1)
                                                            ->first();
                                                    @endphp
                                                    @if ($dis_id->status == 1)
                                                        <a href="javascript:void(0);" data-work_id="{{ @$work->bid_id }}"
                                                            data-toggle="modal" data-target="#payModal"
                                                            class="approvedBtn btn btn-sm rounded theme-bg-light theme-cl ft-medium ml-2 float-left"
                                                            style="padding:7px;">Accept</a>
                                                    @endif
                                                    <a href="{{ route('user.buyer.view.bid.work.dispute', encrypt(@$dis_id->id)) }}"
                                                        class=" btn rounded bg-white border fs-sm ft-medium ">@lang('View Dispute')</a>
                                                @else
                                                    @if ($cd->status == 5)
                                                        <a href="javascript:void(0);"
                                                            class=" btn rounded bg-white border fs-sm ft-medium ">@lang('Dispute')</a>
                                                    @else
                                                        <a data-toggle="modal" data-target="#disputeModal"
                                                            href="javascript:void(0);" data-work_id="{{ $cd->id }}"
                                                            data-accepted_id="{{ $accepteddata->id }}"
                                                            data-dispute_amount="{{ $cd->amount }}"
                                                            data-bid_type="{{ $counteroffer->type }}"
                                                            class="disputeBtn btn rounded bg-white border fs-sm ft-medium ">@lang('Dispute')</a>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @elseif($cd->milestone_status == '0')
                                        @if ($cd->req_type == 'seller')
                                            <a title="Accept Milestone" data-target="#reqmsacceptModal"
                                                data-work_id="{{ @$cd->id }}" href="javascript:void(0);"
                                                class="reqmsacceptBtn btn rounded theme-bg text-light  fs-sm ft-medium mr-2"><i
                                                    class="fa fa-check"></i> </a>
                                            <a title="Reject Milestone" data-target="#reqmsrejectModal"
                                                data-work_id="{{ @$cd->id }}" href="javascript:void(0);"
                                                class="reqmsrejectBtn btn rounded bg-danger text-light theme-cl fs-sm ft-medium mr-2"><i
                                                    class="fa fa-times"></i> </a>
                                        @elseif($cd->req_type == 'buyer')
                                            @if ($cd->req_responce_status == '')
                                                <span style="font-size: 10px;"
                                                    class="badge badge-warning">@lang('request pending')</span>
                                            @endif
                                        @endif
                                    @endif
                                    @if ($cd->req_responce_status == 'reject')
                                        <span style="font-size: 10px;"
                                            class="badge badge-danger">@lang('request rejected')</span>
                                    @endif


                                </div>
                            </div>
                            <div class="row showresponce " style="display:none;" id="target{{ @$cd->id }}">



                                @foreach ($works as $work)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                        @if ($work->status == 0)
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-warning">@lang('pending')</span>
                                        @elseif($work->status == 1)
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-success">@lang('accepted')</span>
                                        @elseif($work->status == 2)
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-primary">@lang('delivered')</span>
                                        @elseif($work->status == 3)
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-danger">@lang('rejected')</span>
                                        @elseif($work->status == 4)
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-warning">@lang('in progress')</span>
                                        @elseif($work->status == 5)
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-success">@lang('completed')</span>
                                        @elseif($work->status == 6)
                                            <span style="font-size: 10px; padding: 10px 25px 10px 25px"
                                                class="badge badge-danger">@lang('dispute')</span>
                                        @endif
                                        <p class="mt-2"><span class="font-weight-bold">
                                                Date:</span>{{ showDateTime($work->created_at, 'd M Y') }}</p>
                                        <p><span class="font-weight-bold">Cover Letter:</span>{{ $work->cover }}
                                            @if (@$work->file == '')
                                            @else
                                                <a title="Download attachment"
                                                    href="{{ route('user.buyer.work.recieve.file.download', encrypt(@$work->id)) }}"><i
                                                        class="fa fa-paperclip"></i></a>
                                            @endif
                                        </p>

                                        @if ($cd->status == '6')
                                        @else
                                            @if ($work->status == 0)
                                                <a href="javascript:void(0);" data-work_id="{{ @$work->id }}"
                                                    data-toggle="modal" data-target="#payModal"
                                                    class="approvedBtn btn btn-sm rounded theme-bg-light theme-cl ft-medium ml-2 float-left"
                                                    style="padding:7px;">Accept</a>

                                                <a href="javascript:void(0);" data-work_id="{{ @$work->id }}"
                                                    data-toggle="modal" data-target="#cancelModal"
                                                    class="cancelBtn btn btn-sm rounded bg-danger text-white ft-medium ml-2 float-left"
                                                    style="padding:6px;">Decline </a>
                                            @endif
                                        @endif
                                    </div>
                                    @if ($work->status == 3)
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2"
                                            style="background:#F6F8F7;padding: 25px;border-radius: 10px !important;">
                                            <!--  <p><span class="font-weight-bold"> Title:</span>Accepting Delivery Two</p> -->
                                            <p><span class="font-weight-bold">
                                                    Date:</span>{{ showDateTime($work->updated_at, 'd M Y') }}</p>
                                            <p><span class="font-weight-bold">Reason:</span>{{ $work->reason }}
                                                @if (@$work->reason_file == '')
                                                @else
                                                    <a title="Download attachment"
                                                        href="{{ route('user.buyer.hire.work.recieve.counter.file.download', encrypt(@$work->id)) }}"><i
                                                            class="fa fa-paperclip"></i></a>
                                                @endif
                                            </p>

                                        </div>
                                        <div class="col-lg-12 mt-2">
                                            <hr>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-12 topboxwork">
                            @if ($accepteddata->is_endcontract == 1)
                                <a data-toggle="modal" data-target="#replyModal"
                                    style="border: 1px solid #ea2b33 !important; padding: 10px 25px 10px 25px"
                                    href="javascript:void(0);"
                                    class=" btn rounded bg-white  text-danger fs-sm ft-medium ">
                                    @lang('Contract Ended')
                                </a>
                            @else
                                <a data-toggle="modal" data-target="#contractModal" href="javascript:void(0);"
                                    data-buyer_id="{{ $accepteddata->buyer_id }}"
                                    data-seller_id="{{ $accepteddata->seller_id }}" style="padding: 10px 25px 10px 25px"
                                    class="contractBtn btn rounded bg-danger text-white border fs-sm ft-medium">
                                    @lang('End Contract')
                                </a>
                            @endif
                            @if ($counteroffer->type == 'bymilestone')
                                <a data-toggle="modal" data-target="#reqmsModal" href="javascript:void(0);"
                                    data-job_id="{{ $accepteddata->job_id }}" data-bid_id="{{ $accepteddata->bid_id }}"
                                    style="padding: 10px 25px 10px 25px"
                                    class="reqmsBtn btn rounded bg-white border fs-sm ft-medium ">
                                    @lang('Request Milestone')
                                </a>
                            @elseif($counteroffer->type == 'byproject')
                            @endif
                        </div>
                    @endforeach
                    <!-- work -->
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script type="text/javascript">
            "use strict";

            // JavaScript to set the work_id value when the modal is opened
            $('#payModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var work_id = button.data('work_id');
                var modal = $(this);
                modal.find('.modal-body #work_id').val(work_id);
            });

            $('.approvedBtn').on('click', function() {
                var modal = $('#approvedModal');
                // modal.find('input[name=work_id]').val($(this).data('work_id'))
                // modal.modal('show');
            });
            $('.cancelBtn').on('click', function() {
                var modal = $('#cancelModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.modal('show');
            });
            $('.toggle').click(function() {
                var id = $(this).data('work_id');
                $('#target' + id).toggle();
            });
            // $('.toggle2').click(function() {
            // $('#target2').toggle();
            // });
            $('.payBtn').on('click', function() {
                var modal = $('#payModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.find('input[name=amount]').val($(this).data('amount'))
                modal.find('input[name=value]').val($(this).data('value'))
                modal.modal('show');
            });
            $('.contractBtn').on('click', function() {
                var modal = $('#contractModal');
                modal.find('input[name=buyer_id]').val($(this).data('buyer_id'))
                modal.find('input[name=seller_id]').val($(this).data('seller_id'))
                modal.modal('show');
            });
            $('.reqmsBtn').on('click', function() {
                var modal = $('#reqmsModal');
                modal.find('input[name=job_id]').val($(this).data('job_id'))
                modal.find('input[name=bid_id]').val($(this).data('bid_id'))
                modal.modal('show');
            });
            $('.reqmsacceptBtn').on('click', function() {
                var modal = $('#reqmsacceptModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.modal('show');
            });
            $('.reqmsrejectBtn').on('click', function() {
                var modal = $('#reqmsrejectModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.modal('show');
            });
            $('.disputeBtn').on('click', function() {
                var modal = $('#disputeModal');
                modal.find('input[name=accepted_id]').val($(this).data('accepted_id'))
                modal.find('input[name=bid_type]').val($(this).data('bid_type'))
                modal.find('input[name=work_id]').val($(this).data('work_id'))
                modal.find('input[name=dispute_amount]').val($(this).data('dispute_amount'))
                modal.modal('show');
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                var buttonAdd = $("#add-button");
                var buttonRemove = $("#remove-button");
                var className = ".dynamic-field";
                var count = 0;
                var field = "";
                var maxFields = 50;

                function totalFields() {
                    return $(className).length;
                }

                function addNewField() {
                    count = totalFields() + 1;
                    field = $("#dynamic-field-1").clone();
                    field.attr("id", "dynamic-field-" + count);
                    field.children("label").text("Field " + count);
                    field.find("input").val("");
                    $(className + ":last").after($(field));
                }

                function removeLastField() {
                    if (totalFields() > 1) {
                        $(className + ":last").remove();
                    }
                }

                function enableButtonRemove() {
                    if (totalFields() === 2) {
                        buttonRemove.removeAttr("disabled");
                        buttonRemove.addClass("shadow-sm");
                    }
                }

                function disableButtonRemove() {
                    if (totalFields() === 1) {
                        buttonRemove.attr("disabled", "disabled");
                        buttonRemove.removeClass("shadow-sm");
                    }
                }

                function disableButtonAdd() {
                    if (totalFields() === maxFields) {
                        buttonAdd.attr("disabled", "disabled");
                        buttonAdd.removeClass("shadow-sm");
                    }
                }

                function enableButtonAdd() {
                    if (totalFields() === (maxFields - 1)) {
                        buttonAdd.removeAttr("disabled");
                        buttonAdd.addClass("shadow-sm");
                    }
                }
                buttonAdd.click(function() {
                    addNewField();
                    enableButtonRemove();
                    disableButtonAdd();
                });
                buttonRemove.click(function() {
                    removeLastField();
                    disableButtonRemove();
                    enableButtonAdd();
                });
            });
        </script>
    @endsection
