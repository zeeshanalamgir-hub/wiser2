@extends('templates.basic.layouts.wiserlance.dashboard-layout-job', [
    'scripts' => ['assets/templates/basic/scripts/js/data-ajax.js', 'assets/templates/basic/scripts/js/user/buyer/hire_employ_bid_send_offer.js'],
    'styles' => ['assets/templates/basic/styles/css/racket-tables.css', 'assets/templates/basic/styles/css/user/buyer/job_bids_list.css', 'assets/templates/basic/styles/css/user/buyer/hire_employ_bid_send_offer.css', 'assets/templates/basic/styles/css/racket-forms.css'],
])
@section('content')

    <div class="racket-dashboard-content-5356 py-3">
        <div class="container-header-table-100095">
            <div class="header-top-buttons-10065">
                {{-- data-href="{{ route('user.job.vacancy') }}" --}}
                <div id="btn-bids-5432534" class="btn rounded sd-button-header-10067 ffs-24 poppins-semibold "
                    onclick="window.location.href = '/user/buyer/hire/employees/bids/{{ $item->encrypted_job_id }}'">
                    <i class="fas fa-chevron-left bid-arrow-back-789678"></i> Bids List
                </div>
                <div id="btn-offers-5432534" class="btn rounded sd-button-header-10068 ffs-24 poppins-semibold">
                    Send Offer
                </div>
            </div>
        </div>
        <div class="tab-content py-3" id="racket-job-vacancy-detail-view-tabs">
            <div class="tab-pane fade show active" id="bids" role="tabpanel" aria-labelledby="bids-tab">
                <div class="racket-table-view-2648652">
                    <div class="racket-table-top-header-with-heading">
                        <div class="racket-table-heading-green afacad-medium ffs-14" id="table-heading-67895">
                            {{ $item->job->title }}
                        </div>
                    </div>
                    <table class="racket-table">
                        <thead class="racket-table-line-header">
                            <tr>
                                <th class="poppins-semibold ffs-12">Job Type</th>
                                <th class="poppins-semibold ffs-12">Seller</th>
                                <th class="poppins-semibold ffs-12">Submitted on</th>
                                <th class="poppins-semibold ffs-12">Bid Amount</th>
                                <th class="poppins-semibold ffs-12">Status</th>
                            </tr>
                        </thead>
                        <tbody class="racket-table-body" id="job-list-bids-data">
                            <tr class="br-line-set-8765785">
                                <td class="afacad-regular ffs-12">{{ $item->job->budgettype }}</td>
                                <td class="afacad-regular ffs-12">{{ $item->user->username }}</td>
                                <td class="afacad-regular ffs-12">{{ $item->created_date }}</td>
                                <td class="afacad-regular ffs-12">{{ $item->collective_price_text }}</td>
                                <td class="afacad-regular ffs-12" id="bid-status">{{ $item->bid_status }}</td>
                            </tr>
                            <tr class="buyer-bit-detail-view-section-6789565">
                                <td colspan="9">
                                    <div class="cover-letter-details-outer-76755">
                                        <div class="letter-section-7895">
                                            <div class="poppins-semibold ffs-12">Cover Letter</div>
                                            <textarea rows="7" readonly id="job-letter-proposal-78965" class="afacad-regular ffs-12 p-2">{{ $item->proposal }}</textarea>
                                        </div>
                                        <div class="line-item-section-7895">
                                            <div class="poppins-semibold ffs-12">Bid Amount</div>
                                            <div class="afacad-regular ffs-12" id="job-bid-amount-67896">
                                                {{ $item->collective_price_text }}</div>
                                        </div>
                                        <div class="line-item-section-7895">
                                            <div class="poppins-semibold ffs-12">Submit Date</div>
                                            <div class="afacad-regular ffs-12" id="job-bid-submit-date-67896">
                                                {{ $item->created_date }}</div>
                                        </div>
                                        <div class="line-item-section-7895">
                                            <div class="poppins-semibold ffs-12">Time</div>
                                            @php
                                                $expectedTime = (string) $item->seller_proposal_days;
                                                $time = $expectedTime . ' days - (8hr/d)';
                                            @endphp
                                            <div class="afacad-regular ffs-12" id="job-bid-delivery-time-67896">
                                                {{ $time }}</div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr class="buyer-bit-detail-view-footer-section-6789565">
                                <td colspan="9">
                                    <div class="cover-letter-details-footer-76755">
                                        <div class="info-labels-789565 afacad-regular ffs-12">
                                            Last Updated <span id="last-updated-id-87685"> {{ $item->updated_at }}</span>
                                        </div>
                                        <div class="action-buttons-789565">
                                            <div class="poppins-semibold ffs-12 view-job-btn"
                                                onclick="window.location.href = '/job/details/{{ $item->encrypted_job_id }}'">
                                                View Job</div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="racket-forms-outer">
            <form action="{{ route('user.buyer.submit.bid.offer') }}" method="POST" enctype="multipart/form-data"
                id="submit-offer-form">
                @csrf

                <input type="hidden" value="{{ $offer && $offer->id ? $offer->id : '' }}" name="counter_offer_id">
                <input type="hidden" value="{{ $biddetail->job_type }}" name="job_type">
                <input type="hidden" value="{{ $biddetail->id }}" name="bid_id">
                <input type="hidden" value="{{ $biddetail->job->id }}" name="job_id">
                <input type="hidden" value="{{ $biddetail->job->user_id }}" name="sender">
                <input type="hidden" value="{{ $biddetail->user_id }}" name="reciever">
                <input type="hidden" value="{{ $biddetail->job->amount }}" name="job_amount">


                <div class="racket-forms-input-section">
                    <div class="racket-forms-header-outer rounded">
                        <div class="racket-forms-header">
                            <div class="racket-form-heading poppins-semibold ffs-12">Send Offer</div>
                        </div>
                    </div>

                    <div class="racket-forms-body py-2">
                        <div class="racket-form-body-section">



                            <div class="row">

                                @if ($biddetail->job_type == 'per_hour')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                                            Enter the amount you shall offer per hour.
                                        </div>
                                        <div class="form-group">
                                            <input type="number" placeholder="Amount" name="hourly_amount"
                                                value="{{ $offer && $offer->offerdata ? $offer->offerdata->amount : '' }}"
                                                class="form-control afacad-regular ffs-12">
                                            <div id="project_amount_error" class="text-danger" style="display: none">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                                            Days. (8hr/d)
                                        </div>
                                        <div class="form-group">
                                            <input type="number" placeholder="Days" id="project_days"
                                                name="project_days"
                                                value="{{ $offer && $offer->offerdata ? $offer->offerdata->days : '' }}"
                                                class="form-control afacad-regular ffs-12">
                                            <div id="days_error" class="text-danger" style="display:none;"></div>
                                        </div>
                                    </div>
                                @elseif($biddetail->job_type == 'byproject')
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                                            Enter the amount you shall offer.
                                        </div>
                                        <div class="form-group">
                                            <input type="number" placeholder="Amount" name="project_amount"
                                                value="{{ $offer && $offer->offerdata ? $offer->offerdata->amount : '' }}"
                                                class="form-control afacad-regular ffs-12">
                                            <div id="project_amount_error" class="text-danger" style="display: none">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                                            Days.
                                        </div>
                                        <div class="form-group">
                                            <input type="number" placeholder="Days" id="project_days"
                                                name="project_days"
                                                value="{{ $offer && $offer->offerdata ? $offer->offerdata->days : '' }}"
                                                class="form-control afacad-regular ffs-12">
                                            <div id="days_error" class="text-danger" style="display:none;"></div>
                                        </div>
                                    </div>
                                @elseif($biddetail->job_type == 'bymilestone')
                                    @php
                                        $milestones = DB::table('user_milestone')
                                            ->where('bid_id', $biddetail->id)
                                            ->get();
                                    @endphp
                                    <div class="col-12 ">

                                        @foreach ($milestones as $md)
                                            <div class="row dynamic-field">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="racket-form-body-section-heading poppins-light ffs-12">Milestone
                                                        </div>
                                                        <input type="text" name="milestone_title[]" id="field" maxlength="255" min="1"
                                                            class="form-control afacad-regular ffs-12" placeholder="@lang('Title')" value="{{ $md->title }}">
                                                        <div id="job_milestone_title_error" class="text-danger" style="display: none"></div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="racket-form-body-section-heading poppins-light ffs-12">Amount
                                                        </div>
                                                        <input type="number" name="milestone_amount[]" id="milestone_amount"  maxlength="255" min="1"
                                                            class="form-control afacad-regular ffs-12" placeholder="@lang('Amount')" value="{{ $md->amount }}">
                                                        <div id="job_milestone_amount_error" class="text-danger" style="display: none"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">

                                                    <div class="form-group">
                                                        <div class="racket-form-body-section-heading poppins-light ffs-12">Days
                                                        </div>
                                                        <input type="number" name="milestone_due_date[]" id="milestone_days" maxlength="255" min="1"
                                                            class="form-control afacad-regular ffs-12" placeholder="@lang('Days')" value="{{ $md->due_date }}">
                                                        <div id="job_milestone_due_date_error" class="text-danger" style="display: none"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                        <div class="col-12 mt-3">
                                            <div id="project_amount_error" class="text-danger" style="display: none"></div>
                                        </div>

                                @endif
                                {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                    <label class="text-dark ft-medium">@lang('Close this job after Seller accept')*</label>
                                    <input type="checkbox" id="close_job_checkbox" name="is_close">

                                </div> --}}

                                <div class="col-xl-12 col-lg-12 mt-3">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12 ">
                                        Cover Letter.
                                    </div>
                                    <div class="form-group">
                                        <textarea rows="7" class="form-control racket-forms-text-area nicEdit" name="coverletter">{{ $offer && $offer->description ? $offer->description : '' }} </textarea>
                                        <div id="cover_letter_error" class="text-danger" style="display:none;"></div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-3">
                                    <div class="racket-form-body-section-heading poppins-light ffs-12">
                                        Attachment.
                                    </div>
                                    <div class="racket-attachment-box-outer">
                                        <div class="racket-attachment-file-name-outer"><span
                                                id="racket-attachment-file-name" class="poppins-light ffs-12">Choose file
                                                (png, jpg, pdf, doc)</span></div>
                                        <div class="racket-attachment-file-icon">
                                            <img src="{{ asset('assets/wiserlance/assets/img/icons/upload-icon.png') }}"
                                                id="upload-icon">
                                        </div>
                                        <input type="file" name="file[]" class="" id="attachment-files"
                                            style="display: none" multiple>
                                    </div>
                                    <div id="selected-files-list" class="poppins-light ffs-12"></div>
                                    <div class="racket-attachment-hint-below poppins-light ffs-12">Note: Maximum 5 files
                                        can be uploaded. Maximum file size should be 250M</div>
                                </div>


                            </div>

                        </div>

                    </div>

                    <!-- Add Skills -->


                </div>


            </form>
        </div>
        <div class="row m-0 mt-3">
            <div class="col-lg-12 col-md-12 p-0">
                <button type="submit" id="send-offer-submit-btn-678956"
                    class="btn btn-md  rounded "style="width:100%; background-color:#00C535; padding:15px;color: white ">@lang('SEND OFFER')</button>
            </div>
        </div>
    </div>

@endsection
