<div class="modal fade" id="modalRequestMilestone" tabindex="-1" aria-labelledby="modalRequestMilestoneLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('New milestone request')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
            </div>
            <form class="row g-3" id="milestoneRequestForm" method="POST" action="{{ route('user.buyer.request.work.milestone.bid') }}">
                @csrf
                <input type="hidden" name="job_id" value="{{ $encJobId }}">
                <input type="hidden" name="bid_id" value="{{ $encBidId }}">
                {{--  here is my code  --}}


                <div class="modal-body">
                    <div id="payment_milestone_wrapper">
                        <span id="milestone_wrapper">
                            <div class="row row-10 milestone" id="dynamic-field-1">
                                <div class="col-sm-6 col-12">
                                    <div class="form-field">
                                        <label for="title">@lang('Milestone title')</label>
                                        <input type="text" name="milestone[0][title]" class="form-control required titled" placeholder="Milestone Title">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="form-field">
                                        <label for="amount">@lang('Amount')</label>
                                        <input type="text" name="milestone[0][amount]" class="form-control required amount" placeholder="$0" onkeypress="return isNumberKey(event)" onblur="checkAmountByMilestone()">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="form-field">
                                        <label for="due_date">@lang('Due date')</label>
                                        <input type="text" name="milestone[0][due_date]" class="form-control required due_date datepicker" placeholder="YYYY-MM-DD" onchange="calculateDaysFromToday(0)">
                                        <input type="hidden" id="days_from_today_0" name="milestone[0][days_from_today]" value="">
                                    </div>
                                </div>
                            </div>
                        </span>

                        <!-- Add More Button -->
                        <div class="mt-3">
                            <a href="javascript:void(0);" id="add_more_milestone" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> @lang(' Add More')</a>
                        </div>

                    </div>
                    <div id="walletError"></div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success bg-green border-0">@lang('Send request')</button>
                </div>
            </form>
        </div>
    </div>
</div>
