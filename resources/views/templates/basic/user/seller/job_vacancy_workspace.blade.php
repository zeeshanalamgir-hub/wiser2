@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout')
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
                        @$review = DB::table('review_ratings')
                            ->where('data_id', $accepteddata->id)
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
                            <span class="font-weight-bold">Review by:
                                <a href="{{ route('profile', [slug(@$reviewer->username), encrypt(@$reviewer->id)]) }}">
                                    @if (@$reviewer->image == '')
                                        <img src="https://ionicframework.com/docs/demos/api/avatar/avatar.svg"
                                            class="img-fluid circle mr-2" width="40" alt="">
                                    @else
                                        <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'] . '/' . @$reviewer->image, 'profile_image') }}"
                                            alt="{{ __(@$reviewer->username) }}" class="img-fluid circle mr-2 "
                                            style="border-radius: 50%;height: 40px;width: 40px;border: 2px solid #e5e5e5;">
                                    @endif
                            </span><span>{{ @$reviewer->firstname }} {{ @$reviewer->lastname }}</span></a><br>

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
                            <br>
                            <label class="mt-3">Skill</label><br>
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
                            <br>
                            <label style="margin-top: 10px;">Seller communication level</label><br>
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
                                <label> Your Reply</label>
                                <textarea rows="5" class="form-control" name="reply" placeholder="Type your reply here..." required></textarea>
                            @else
                                <label>Your Reply</label>
                                <p>{{ @$review->reply }}</p>
                            @endif

                        </div>
                        @if (@$review->reply == '')
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
                    <form method="POST" action="{{ route('user.job.accept.extra.work.milestone.bid') }}">
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
                    <form method="POST" action="{{ route('user.job.reject.extra.work.milestone.bid') }}">
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
                <form method="POST" action="{{ route('user.job.request.work.milestone.bid') }}">
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

                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
                                <label>Platform Fee (%)</label>
                                <input id="feemulti" name="platformfeemulti" readonly
                                    value="{{ showAmount($usermembership[0]->fee) }}" class="form-control">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
                                <label>You Will Get Paid</label>
                                <input id="totalmulti" readonly class="form-control">
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
    <div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Work Delivery')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('user.job.work.send.bid') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="accepted_id">
                        <input type="hidden" name="dataa_id">
                        <input type="hidden" name="work_type" value="jobfile">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg" class="form-label font-weight-bold">@lang('Upload Work File')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg"
                                    type="file">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details" placeholder="Describe Your Delivery Details ...."
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success"
                                style="width:100%;">@lang('Submit')</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="dashboard-content">
        <div class="dashboard-tlbar d-block mb-5">
            <div class="row">
                <!-- <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                            <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard-manage-service.html" class="theme-cl">Manage Services</a></li>
                        </ol>
                    </nav>
                </div> -->
            </div>
        </div>
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
                    @if (@$accepted1->seller_offer_responce == '2')
                        <a href="{{ route('user.job.vacancy.bids', encrypt($singlejob->id)) }}"
                            class="btn rounded theme-bg-light theme-cl fs-sm ft-medium "><i
                                class="fa fa-angle-double-left"></i>Back</a>
                    @elseif(@$accepted2->buyer_offer_responce == '2')
                        <a href="{{ route('user.job.vacancy.offers.detail', encrypt($singlejob->id)) }}"
                            class="btn rounded theme-bg-light theme-cl fs-sm ft-medium "><i
                                class="fa fa-angle-double-left"></i>Back</a>
                    @endif
                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                        @if (@$accepted1->seller_offer_responce == '2')
                            <a href="{{ route('user.job.vacancy.accepted_bid', encrypt($singlejob->id)) }}"
                                class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Accepted Bid</a>
                        @elseif(@$accepted2->buyer_offer_responce == '2')
                            <a href="{{ route('user.job.vacancy.accepted_bid', encrypt($singlejob->id)) }}"
                                class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Accepted Offer</a>
                        @endif
                    @endif
                    @if (@$accepted1->seller_offer_responce == '2' || @$accepted2->buyer_offer_responce == '2')
                        @if (@$accepted1->job_type == 'hourly' || @$accepted2->job_type == 'hourly')
                            <a href="{{ route('user.job.vacancy.hourlytimesheet', encrypt($singlejob->id)) }}"
                                class="btn rounded theme-bg text-light fs-sm ft-medium ml-2">Hourly Timesheet</a>
                        @else
                            <a href="{{ route('user.job.vacancy.workspace', encrypt($singlejob->id)) }}"
                                class="btn rounded theme-bg text-light fs-sm ft-medium ml-2">Workspace</a>
                        @endif

                        @php
                            $userName = DB::table('users')
                                ->where('id', $accepted1->user_id)
                                ->first();
                            if ($userName->id == Auth()->user()->id) {
                                $userName = DB::table('jobs')
                                    ->where('id', $accepted1->job_id)
                                    ->first();
                                $userName = DB::table('users')
                                    ->where('id', $userName->user_id)
                                    ->first();
                            }
                        @endphp
                        <a href="{{ url('chatify') }}"
                            class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Chat with
                            {{ $userName->firstname }}</a>
                        <a href="{{ route('user.job.vacancy.invoicetab', encrypt($singlejob->id)) }}"
                            class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Invoice</a>

                        <a href="{{ route('user.job.vacancy.ratingtab', encrypt($singlejob->id)) }}"
                            class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Rating & review</a>
                    @endif
                </div>

            </div>
            <!-- <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center">@lang('Title')</th>
                                        <th scope="col" class="text-center">@lang('Type')</th>
                                        <th scope="col" class="text-center">@lang('Buyer')</th>
                                        <th scope="col" class="text-center">@lang('Start Date')</th>
                                        <th scope="col" class="text-center">@lang('Amount')</th>
                                        <th scope="col" class="text-center">@lang('Status')</th>
                                        <th scope="col" class="text-center">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td>Business Consultatnt</td>

                                        <td>Fixed</td>
                                        <td>Free14 <br>
                                            <a href="#">@Free14</a></td>
                                        <td >
                                            15-jan-23
                                        </td>
                                        <td>$100</td>
                                        <td><span style="font-size: 10px;" class="badge badge-info">Running</span></td>
                                        <td>
                                            <div class="dash-action-98563">
                                                <a href="javascript:void(0);" data-order_number="" data-order_number="" data-toggle="modal" data-target="#milestoneModal" class="milestoneBtn p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="fa fa-comments"></i></a>
                                                <a href="javascript:void(0);" class=" p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="fa fa-handshake"></i></a>


                                            </div>
                                        </td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> -->
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
            <div class="workboxmain">
                <div class="row">
                    @if ($counteroffer->type == 'bymilestone' || $accepteddata->is_endcontract == 1)
                        <div class="col-lg-12 topboxwork">
                            @if ($accepteddata->is_endcontract == 1)
                                <a data-toggle="modal" data-target="#replyModal"
                                    style="border: 1px solid #ea2b33 !important;" href="javascript:void(0);"
                                    class=" btn rounded bg-white  text-danger fs-sm ft-medium mr-2">@lang('Contract Ended by ')
                                    {{ @$reviewer->firstname }} {{ @$reviewer->lastname }} </a>
                            @else
                            @endif
                            @if ($counteroffer->type == 'bymilestone')
                                <a data-toggle="modal" data-target="#reqmsModal" href="javascript:void(0);"
                                    data-job_id="{{ $accepteddata->job_id }}" data-bid_id="{{ $accepteddata->bid_id }}"
                                    class="reqmsBtn btn rounded bg-white border fs-sm ft-medium ">@lang('Request Milestone')</a>
                            @elseif($counteroffer->type == 'byproject')
                                <!-- <a  data-toggle="modal" data-target="#" href="javascript:void(0);" data-job_id="{{ $accepteddata->job_id }}" data-bid_id="{{ $accepteddata->bid_id }}" class=" btn rounded bg-white border fs-sm ft-medium " >@lang('Dispute')</a>  -->
                            @endif
                        </div>
                    @endif
                    {{--  <!-- work one -->
                <div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <h5>Milestone 1</h5>
                            <p><span class="font-weight-normal"> $ 80.00</span>  &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-success">@lang('payed')</span> </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right" style="padding-top: 12px;">

                            <!-- <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a> -->
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->


                        </div>
                    </div>
                    <div class="row showresponce " style="display:none;" id="target">



                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <p><span class="font-weight-bold"> Title:</span>Accepting Delivery Two</p>
                            <p><span class="font-weight-bold"> Date:</span>20 January 2022</p>
                            <p><span class="font-weight-bold">Reason:</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl, vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt tristique risus ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est. Quisque mattis venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac nec magna. Nulla nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse consequat, dui non congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit amet mauris. Aenean consectetur sem eu lectus egestas tincidunt. Proin augue tellus, venenatis ac tempus nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida sit amet, condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales pellentesque risus. Nullam sit amet nisi eu neque feugiat finibus.</p>
                            <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a>

                        </div>





                    </div>
                </div>
                <!-- work two -->
                <!-- <div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <h5>Delivery Two <span style="font-size: 10px;" class="badge badge-success">@lang('Approved')</span></h5>
                            <p><span class="font-weight-bold">Submission Date:</span>15 January 2022</p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right pt-1">

                            <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a>
                        </div>
                    </div>
                </div> -->
                <!-- work two -->
                <!-- work two -->
                <div class="col-lg-12 innerbox">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <h5>Milestone 2</h5>
                            <p><span class="font-weight-normal"> $ 80.00</span>  &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-warning">@lang('requested')</span> </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right" style="padding-top: 12px;">

                            <a href="#" class="toggle2 btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a>
                            <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->


                        </div>
                    </div>
                    <div class="row showresponce " style="display:none;" id="target2">



                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <p><span class="font-weight-bold"> Title:</span>Accepting Delivery Two &nbsp <span style="font-size: 10px;" class="badge badge-info">@lang('in progress')</span></p>
                            <p><span class="font-weight-bold"> Date:</span>20 January 2022</p>
                            <p><span class="font-weight-bold">Reason:</span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus porttitor viverra nibh, a aliquet augue. Vestibulum dui nisl, vestibulum in lectus a, lobortis elementum velit. Vestibulum tincidunt tristique risus ac ornare. Nam vitae dui ut orci ornare vehicula at sit amet est. Quisque mattis venenatis eros ac auctor. Vivamus vel lacus in est sagittis maximus ac nec magna. Nulla nec arcu bibendum, laoreet mauris ut, pretium tellus. Suspendisse consequat, dui non congue egestas, ipsum nisl cursus dolor, eu mattis sem lectus sit amet mauris. Aenean consectetur sem eu lectus egestas tincidunt. Proin augue tellus, venenatis ac tempus nec, pretium sit amet ex. Nullam dolor turpis, tristique id gravida sit amet, condimentum ac dolor. Proin in sem ut elit tempor commodo. Mauris sodales pellentesque risus. Nullam sit amet nisi eu neque feugiat finibus.</p>
                            <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium float-right"  ><i class="fa fa-file"></i> &nbsp Attachment</a>

                        </div>
                    </div>

                </div> --}}
                    <!-- work  -->
                    @foreach ($counterofferdata as $cd)
                        @php
                            $works = DB::table('job_work_delivery_bid')
                                ->where('data_id', @$cd->id)
                                ->where('job_id', $accepteddata->job_id)
                                ->where('bid_id', $accepteddata->bid_id)
                                ->get();
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
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <h5>

                                        @if ($counteroffer->type == 'bymilestone')
                                            {{ $cd->title }}
                                        @elseif($counteroffer->type == 'byproject')
                                            {{ $Jobname->title }}
                                        @endif

                                        @if ($cd->status == '6')
                                            @if ($dispute->status == 1)
                                                <span style="font-size: 10px;" class="badge badge-success">awaiting
                                                    Payment</span> <span style="font-size: 10px;"
                                                    class="badge badge-success">awaiting payment</span>
                                            @elseif($dispute->status == 2)
                                                <span style="font-size: 10px;" class="badge badge-primary">dispute send to
                                                    admin</span>
                                            @else
                                                <span style="font-size: 10px;" class="badge badge-danger">in
                                                    dispute</span>
                                            @endif
                                        @else
                                            @if ($cd->status == '1')
                                                <!-- <span style="font-size: 10px;" class="badge badge-success">completed</span> -->
                                                <span style="font-size: 10px;" class="badge badge-success">awaiting
                                                    payment</span>
                                            @elseif($cd->status == '2')
                                                <span style="font-size: 10px;"
                                                    class="badge badge-primary">delivered</span>
                                            @elseif($cd->status == '0')
                                                <span style="font-size: 10px;" class="badge badge-warning">pending</span>
                                            @elseif($cd->status == 5)
                                                <span style="font-size: 10px;"
                                                    class="badge badge-success">@lang('payed')</span>
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
                                            {{ showDateTime($cd->created_at, 'd M Y') }}</span> &nbsp &nbsp

                                    </p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right" style="padding-top: 12px;">
                                    @if ($cd->milestone_status == '1')
                                        @if ($works->count() > 0)
                                            <a href="javascript:void(0);" title="View Detail"
                                                data-work_id="{{ @$cd->id }}"
                                                class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2"><i
                                                    class="fa fa-eye"></i> </a>
                                        @endif
                                        @if ($cd->status == '5')
                                            <a href="javascript:void(0);"
                                                class="btn rounded theme-bg text-light  fs-sm ft-medium mr-2">Payed </a>
                                        @endif
                                        @if ($cd->status == '0' || $cd->status == '3')
                                            <a data-target="#workModal" data-dataa_id="{{ @$cd->id }}"
                                                data-accepted_id="{{ @$accepteddata->id }}" href="javascript:void(0);"
                                                class="workBtn btn rounded theme-bg text-light fs-sm ft-medium mr-2">Request
                                                Payment </a>
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
                                                    @endphp
                                                    <a href="{{ route('user.view.bid.work.dispute', encrypt(@$dis_id->id)) }}"
                                                        class=" btn rounded bg-white border fs-sm ft-medium ">@lang('View Dispute')</a>
                                                @endif
                                                <!-- <a  data-toggle="modal" data-target="#disputeModal" href="javascript:void(0);" data-accepted_id="{{ $accepteddata->id }}" data-bid_type="bid" class="disputeBtn btn rounded bg-white border fs-sm ft-medium " >@lang('Dispute')</a>  -->
                                            @endif
                                        @endif
                                    @elseif($cd->milestone_status == '0')
                                        @if ($cd->req_type == 'buyer')
                                            <a title="Accept Milestone" data-target="#reqmsacceptModal"
                                                data-work_id="{{ @$cd->id }}" href="javascript:void(0);"
                                                class="reqmsacceptBtn btn rounded theme-bg text-light  fs-sm ft-medium mr-2"><i
                                                    class="fa fa-check"></i> </a>
                                            <a title="Reject Milestone" data-target="#reqmsrejectModal"
                                                data-work_id="{{ @$cd->id }}" href="javascript:void(0);"
                                                class="reqmsrejectBtn btn rounded bg-danger text-light theme-cl fs-sm ft-medium mr-2"><i
                                                    class="fa fa-times"></i> </a>
                                        @elseif($cd->req_type == 'seller')
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
                                    <!-- <a href="#"  class="btn btn-sm rounded theme-bg text-light ft-medium "  ><i class="fa fa-file"></i> &nbsp Download File</a> -->


                                </div>
                            </div>
                            <div class="row showresponce " style="display:none;" id="target{{ @$cd->id }}">




                                @foreach ($works as $work)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                        @if ($work->status == 0)
                                            <span style="font-size: 10px;"
                                                class="badge badge-warning">@lang('pending')</span>
                                        @elseif($work->status == 1)
                                            <span style="font-size: 10px;"
                                                class="badge badge-success">@lang('accepted')</span>
                                        @elseif($work->status == 2)
                                            <span style="font-size: 10px;"
                                                class="badge badge-primary">@lang('delivered')</span>
                                        @elseif($work->status == 3)
                                            <span style="font-size: 10px;"
                                                class="badge badge-danger">@lang('rejected')</span>
                                        @elseif($work->status == 4)
                                            <span style="font-size: 10px;"
                                                class="badge badge-warning">@lang('in progress')</span>
                                        @elseif($work->status == 5)
                                            <span style="font-size: 10px;"
                                                class="badge badge-success">@lang('payed')</span>
                                        @elseif($work->status == 6)
                                            <span style="font-size: 10px;"
                                                class="badge badge-danger">@lang('dispute')</span>
                                        @endif
                                        <p><span class="font-weight-bold">
                                                Date:</span>{{ showDateTime($work->created_at, 'd M Y') }}</p>
                                        <p><span class="font-weight-bold">Cover Letter:</span>{{ $work->cover }}
                                            @if (@$work->file == '')
                                            @else
                                                <a title="Download attachment"
                                                    href="{{ route('user.work.send.file.download', encrypt(@$work->id)) }}"><i
                                                        class="fa fa-paperclip"></i></a>
                                            @endif
                                        </p>


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
                                                        href="{{ route('user.work.send.counter.file.download', encrypt(@$work->id)) }}"><i
                                                            class="fa fa-paperclip"></i></a>
                                                @endif
                                            </p>
                                            <!--   <a data-order_number="" data-toggle="modal" data-target="#approvedModal"  class="approvedBtn btn btn-sm rounded theme-bg-light theme-cl ft-medium ml-2 float-left"  >Accept </a>
                                <a data-order_number="" data-toggle="modal" data-target="#cancelModal"  class="cancelBtn btn btn-sm rounded bg-danger text-white ft-medium ml-2 float-left"  >Decline </a> -->
                                        </div>
                                        <div class="col-lg-12 mt-2">
                                            <hr>
                                        </div>
                                    @endif
                                @endforeach


                            </div>
                        </div>
                    @endforeach
                    <!-- work -->

                </div>
            </div>




        </div>
    @endsection
    @section('script')
        <script>
            "use strict";
            $('.workBtn').on('click', function() {
                var modal = $('#workModal');
                modal.find('input[name=accepted_id]').val($(this).data('accepted_id'))
                modal.find('input[name=dataa_id]').val($(this).data('dataa_id'))
                modal.modal('show');
            });
            $('.toggle').click(function() {
                var id = $(this).data('work_id');
                $('#target' + id).toggle();
            });
            // $('.toggle2').click(function() {
            // $('#target2').toggle();
            // });
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
        <script type="text/javascript">
            $(document).on('keyup', '.bidamountmulti', function() {
                var abcd = 0;
                var jobprice = $('.bidamountmulti').val();
                var percent = $('#feemulti').val();
                console.log(percent);
                $('.bidamountmulti').each(function(key, value) {
                    // console.log(key);
                    // console.log(value);
                    var $this = $(this);
                    var thisval = $this.val();
                    console.log(thisval);
                    abcd += parseFloat(thisval);
                });
                var points = (parseFloat(percent) / 100);
                console.log(points);
                var totalpercent = ((points) * parseFloat(abcd));
                var leftprice = jobprice - totalpercent
                // console.log(totalpercent);
                $('#totalmulti').val(leftprice);
            });
        </script>
    @endsection
