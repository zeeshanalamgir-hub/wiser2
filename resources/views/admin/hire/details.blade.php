@extends('admin.layouts.app')
<style>
    .chat-container {
        max-height: 400px;
        overflow-y: auto;
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
    }

    .chat-bubble {
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 10px;
        max-width: 75%;
        word-wrap: break-word;
    }

    .chat-buyer {
        background-color: #e2f0d9;
        align-self: flex-start;
    }

    .chat-seller {
        background-color: #d9eaf7;
        align-self: flex-end;
    }

    .chat-meta {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 2px;
    }

    .chat-wrapper {
        display: flex;
        flex-direction: column;
    }
</style>
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-10 text-muted">@lang('Job Details')</h5>
                    <h6 class="mb-20 text-muted"><a
                            href="{{ route('admin.job.details', $booking->job_id) }}">{{ __($booking->job->title) }}</a></h6>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date')
                            <span class="font-weight-bold">{{ showDateTime($booking->created_at) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Order Number')
                            <span class="font-weight-bold">{{ $booking->order_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Employees')
                            <span class="font-weight-bold">
                                <a
                                    href="{{ route('admin.users.detail', $booking->seller_id) }}">{{ @$booking->seller->username }}</a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Buyer')
                            <span class="font-weight-bold">
                                <a
                                    href="{{ route('admin.users.detail', $booking->buyer_id) }}">{{ @$booking->buyer->username }}</a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Delivery Data')
                            <span
                                class="font-weight-bold">{{ diffforhumans($booking->created_at->addDays($booking->job->delivery_time)) }}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="font-weight-bold">{{ getAmount($offerData->amount) }}
                                {{ __($general->cur_text) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Milestone Status')
                            @if ($offerData->milestone_status == 0)
                                <span class="badge badge--primary">@lang('Not Requested')</span>
                            @elseif($offerData->milestone_status == 1)
                                <span class="badge badge--success">@lang('Revision Requested')</span>
                            @elseif($offerData->milestone_status == 2)
                                <span class="badge badge--secondary">@lang('Requested')</span>
                            @elseif($offerData->milestone_status == 3)
                                <span class="badge badge--danger">@lang('Paid')</span>
                                {{-- @elseif($offerData->milestone_status == 4)
                                <span class="badge badge--dark">@lang('In Progress')</span>
                            @elseif($offerData->milestone_status == 5)
                                <span class="badge badge--danger">@lang('Expired')</span> --}}
                            @elseif($offerData->milestone_status == 6)
                                <span class="badge badge--warning">@lang('Disputed')</span>
                                <button class="btn-info btn-rounded text-white  badge disputeShow"
                                    data-dispute="{{ $booking->dispute_report }}"><i class="fa fa-info"></i></button>
                            @elseif($offerData->milestone_status == 7)
                                <span class="badge badge--warning">@lang('Refund')</span>
                            @elseif($offerData->milestone_status == 8)
                                <span class="badge badge--warning">@lang('Milestone Requested')</span>
                            @elseif($offerData->milestone_status == 9)
                                <span class="badge badge--warning">@lang('Milestone Request Rejected')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if ($booking->status == 1)
                                <span class="badge badge--primary">@lang('Running')</span>
                            @elseif($booking->status == 2)
                                <span class="badge badge--warning">@lang('Payable Both')</span>
                            @elseif($booking->status == 3)
                                <span class="badge badge--success">@lang('Paid')</span>
                            @elseif($booking->status == 4)
                                <span class="badge badge--success">@lang('Refund')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-xl-8 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 text-right">@lang('Other Information')</h5>
                    <div class="row mt-2 ">
                        <div class="col-md-12 mt-2">
                            @php
                                $revisions = $offerData->jobWorkDeliveryBid; // Collection of job_work_delivery_bid items
                            @endphp

                            @if ($revisions->isEmpty())
                                <p>No revisions found.</p>
                            @else
                                @foreach ($revisions as $index => $revision)
                                    <ul class="list-group mt-3">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center  font-weight-bold">
                                            Revision {{ $index + 1 }}
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            @lang('Submitted Work Cover Letter')
                                            <span class="font-weight-bold">{{ $revision->cover }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            @lang('Submitted Work Attachment')
                                            <a href="{{ route('admin.hire.work.file.download', $revision->id) }}"
                                                class="icon-btn btn--primary">
                                                <i class="las la-download"></i>
                                            </a>
                                        </li>
                                        @if ($revision->reason)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                @lang('Buyer Reason for Revision')
                                                <span class="font-weight-bold">{{ $revision->reason }}</span>
                                            </li>
                                        @endif
                                        @if ($revision->counter_file)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                @lang('Buyer Attachment')
                                                <a href="{{ route('admin.hire.work.file.download', $revision->id) }}"
                                                    class="icon-btn btn--primary">
                                                    <i class="las la-download"></i>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                @endforeach
                            @endif
                            @if ($dispute)
                                <h5>Dispute Details</h5>
                                <ul class="list-group mt-3">
                                    <li class="list-group-item">
                                        <strong>@lang('Reason'):</strong> {{ $dispute->reason }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>@lang('Dispute Amount'):</strong> {{ $dispute->dispute_amount }} USD
                                    </li>
                                    <li class="list-group-item">
                                        <strong>@lang('Dispute Sent To Admin By'):</strong> {{ $dispute->sent_to_admin_by }}
                                    </li>
                                    @if ($dispute->file)
                                        <li class="list-group-item">
                                            <strong>@lang('Dispute Attachment'):</strong>
                                            <a href="{{ route('admin.hire.dispute.file.download', $dispute->id) }}"
                                                class="icon-btn btn--primary">
                                                <i class="las la-download"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>

                                {{-- Show negotiation details for the dispute --}}
                                <h5 class="mt-4">Dispute Negotiations</h5>
                                @if ($dispute->disputeNegotiations->isEmpty())
                                    <p>No negotiations found.</p>
                                @else
                                    @foreach ($dispute->disputeNegotiations as $negotiation)
                                        <ul class="list-group mt-3">
                                            <li class="list-group-item">
                                                <strong>@lang('Sent By'):</strong>
                                                {{ $negotiation->send_by == 'buyer' ? $dispute->buyer->fullname : $dispute->seller->fullname }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>@lang('Seller Amount'):</strong> {{ $negotiation->seller_amount }} USD
                                            </li>
                                            <li class="list-group-item">
                                                <strong>@lang('Buyer Amount'):</strong> {{ $negotiation->buyer_amount }} USD
                                            </li>
                                            @if ($negotiation->seller_message)
                                                <li class="list-group-item">
                                                    <strong>@lang('Message'):</strong> {{ $negotiation->seller_message }}
                                                </li>
                                            @endif
                                            {{-- @if ($negotiation->seller_message)
                                                <li class="list-group-item">
                                                    <strong>@lang('Buyer Message'):</strong> {{ $negotiation->seller_message }}
                                                </li>
                                            @endif --}}
                                        </ul>
                                    @endforeach
                                @endif
                            @else
                                <p>No dispute found for this work.</p>
                            @endif



                            {{--  Dispute Chats End  --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden bx-shadow1 mt-5">
                <div class="card-body">
                    <h5 class="mt-4">Chats</h5>
                    @if ($messages->isEmpty())
                        <p class="text-center text-muted mb-0">No negotiations found.</p>
                    @else
                        <div class="chat-wrapper">
                            @foreach ($messages as $msg)
                                @php
                                    $isBuyer = $msg->from_id == $buyerId;
                                @endphp
                                <div class="d-flex {{ $isBuyer ? 'justify-content-start' : 'justify-content-end' }}">
                                    <div class="chat-bubble {{ $isBuyer ? 'chat-buyer' : 'chat-seller' }}">
                                        <strong>{{ $msg->sender_name }}</strong><br>
                                        {{ $msg->body }}
                                        <div class="chat-meta">
                                            {{ \Carbon\Carbon::parse($msg->created_at)->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>


    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Payment Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.employ.payment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="payment" value="seller">
                    <div class="modal-body">
                        <p>@lang('Are you sure to pay these employees?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="refundModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Refund Money')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.employ.payment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="payment" value="buyer">
                    <div class="modal-body">
                        <p>@lang('Are you sure to refund money to this buyer?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Dispute Report')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="dispute-detail">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if ($booking->status == 1)
        @if ($dispute?->status == 2)
            <button class="btn btn--success ml-1 approveBtn" data-toggle="tooltip" data-id="{{ $dispute->id }}"
                data-original-title="@lang('Employees Payment')"><i class="fas fa-ban"></i>
                @lang('Employees Payment')
            </button>

            <button class="btn btn--info ml-1 refundBtn" data-toggle="tooltip" data-id="{{ $dispute->id }}"
                data-original-title="@lang('Buyer Payment')"><i class="fas fa-ban"></i>
                @lang('Buyer Payment')
            </button>
        @endif
    @endif
@endpush

@push('script')
    <script>
        "use strict";
        $('.approveBtn').on('click', function() {
            var modal = $('#approveModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');
        });

        $('.refundBtn').on('click', function() {
            var modal = $('#refundModel');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');
        });

        $('.disputeShow').on('click', function() {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>
@endpush
