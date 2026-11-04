@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
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
                Details
            </p>
            <!--Breadcrumbs End-->

        </div>
        <x-seller-service-nav :booking="$booking" activeTab="details" />
    </div>

    @php
        $serviceDetails = $booking->serviceDetail;
        $totalAmount = (number_format(($booking->agreed_amount - $booking->discount), 2)) - ( $fees['platformFee'] + $fees['paymentProcessingFee'] );
    @endphp
    <!-- Card start-->
    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2;">
        <div class="w-100">
            <p class="float-end mb-0">
                <a class="links-c" href="{{ route('user', ['id' => $booking->buyer_id]) }}"> <i class="fa-regular fa-comment-dots fs-5"></i></a>
                Chat with <span class="text-green">{{ $booking->buyer->fullname }}</span>
            </p>
            <h5 class="text-dgreen2 mb-2">Service Details</h5>
        </div>

        <!-- Service Title -->
        <div class="w-100 bg-lgreen3 px-3 py-2 text-dgreen fw-bold rounded mb-2">
            {{ $booking->service->title }}
            <br>
            <span class="fw-normal fs-14 text-secondary">{!! $booking->service->description !!}</span>
        </div>
        
        <!-- Section: Service Info (design style) -->
        <div class="w-100 p-2 border-bottom fs-14">Service Plan
            <span class="float-end fw-bold">{{ $booking?->serviceDetails?->title ?? '-' }}</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Service Cost
            <span class="float-end">${{ number_format($booking->service_price, 2) }}</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Quantity
            <span class="float-end">{{ $booking->qty }}</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">SubTotal
            <span class="float-end">${{ number_format($booking->service_price * $booking->qty, 2) }}</span>
        </div>
        
        @forelse($addOns as $addon)
            <div class="w-100 p-2 border-bottom fs-14">{{ $addon->title }}
                <span class="float-end">${{ number_format($addon->amount, 2) }}</span>
            </div>
        @empty
        @endforelse
        
        @if($fees['addOnPrice'] ?? 0 + $fees['extraServicesTotal'] ?? 0 > 0)
        <div class="w-100 p-2 border-bottom fs-14">Total Extra Services Cost
            <span class="float-end">${{ number_format($fees['addOnPrice'] ?? 0 + $fees['extraServicesTotal'] ?? 0, 2) }}</span>
        </div>
        @endif
        
        @if($booking?->discount > 0)
        <div class="w-100 p-2 border-bottom fs-14 text-danger">Discount
            <span class="float-end teext-danger">-${{ number_format($booking?->discount,2) ?? 0 }}</span>
        </div>
        @endif
        
        <div class="w-100 p-2 border-bottom fs-14 text-danger">Platform Fee ({{ $platform_fee ?? 8 }}%)
            <span class="float-end text-danger">${{ number_format($plarform_fee_amount,2) ?? 0 }}</span>
        </div>
        
        <div class="w-100 p-2 border-bottom fs-14">You'll receive
            <span class="float-end">${{ number_format($fees['totalServiceCost'],2) }}</span>
        </div>

        <div class="w-100 p-4 border-bottom fs-14"></div>

        <!-- Section: Order Meta Info (just like sample design) -->
        <div class="w-100 p-2 border-bottom fs-14">Order Number
            <span class="float-end">{{ $booking->order_number }}</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Buyer
            <span class="float-end">
                <a href="{{ route('buyerprofile', [slug($booking->buyer->username), encrypt($booking->buyer->id)]) }}">
                    <span>@</span>{{ __($booking->buyer->fullname) }}
                </a>
            </span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Delivery Days
            <span class="float-end">{{ $booking->service_days }} Day(s)</span>
        </div>
        <div class="w-100 p-2 border-bottom fs-14">Revisions
            <span class="float-end">{{ $booking->service_revisions }}</span>
        </div>
        
        <div class="w-100 p-4 border-bottom fs-14"></div>
        <!-- Section: Statuses (as buttons, optional - just for sample) -->
        <div class="w-100 p-2 border-bottom fs-14">Order Status
            <span class="float-end">
                @if(statusText($booking->status) == 'waiting acceptance')
                    <button class="py-0 btn btn-sm btn-secondary border-0 fs-12">Waiting Acceptance</button>
                @elseif(statusText($booking->status) == 'accepted')
                    <button class="py-0 btn btn-sm btn-success bg-green border-0 fs-12">Accepted</button>
                @elseif(statusText($booking->status) == 'cancel')
                    <button class="py-0 btn btn-sm btn-danger border-0 fs-12">Cancel</button>
                @else
                    <button
                        class="py-0 btn btn-sm btn-light border-0 fs-12">{{ ucwords(statusText($booking->status)) }}</button>
                @endif
            </span>
        </div>
        
        <div class="w-100 p-2 border-bottom fs-14">Working Status
            <span class="float-end">
                @if(workingServiceStatusText($booking->working_status) == 'waiting start')
                    <button class="py-0 btn btn-sm btn-secondary border-0 fs-12">Waiting Start</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'in progress')
                    <button class="py-0 btn btn-sm btn-success bg-dgreen2 fs-12 border-0 ms-2">In Progress</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'delivered')
                    <button class="py-0 btn btn-sm btn-success bg-green fs-12 border-0 ms-2">Delivered</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'disputed')
                    <button class="py-0 btn btn-sm btn-danger border-0 fs-12 ms-2">Disputed</button>
                @elseif(workingServiceStatusText($booking->working_status) == 'cancel')
                    <button class="py-0 btn btn-sm btn-danger border-0 fs-12 ms-2">Cancel</button>
                @else
                    <button
                        class="py-0 btn btn-sm btn-light border-0 fs-12">{{ ucwords(workingServiceStatusText($booking->working_status)) }}</button>
                @endif
            </span>
        </div>
    </div>
    <!-- Card end-->

    @if($booking->status == 9)
        <div class="float-end mt-3"> 
            <button type="button" id="approvedBtn" class="btn btn-sm btn-success ms-2">Accept</button> 
            <button type="button" id="declineService" class="btn btn-sm btn-danger ms-2">Decline</button> 
        </div>
    @endif
@endsection


@section('script')
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title text-white" id="ModalLabel">@lang('Approve Booking Confirmation')</h4>
                    <button onclick="closeModal('approvedModal')" type="button" class="btn-close" data-dismiss="approvedModal" aria-label="Close">
                    </button>
                </div>
                <form method="POST" action="{{route('user.approve-service',['id' => encrypt($booking->id)])}}">
                    @csrf
                    <div class="modal-body">
                        <h5>@lang('Are you sure to approved this booking ?')</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm btn-rounded text-white"
                            onclick="closeModal('approvedModal')">@lang('No')</button>
                        <button type="submit" class="btn btn-success btn-sm btn-rounded text-white">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Cancel Booking Confirmation')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('user.booking.confirm')}}">
                    @csrf
                    <input type="hidden" name="order_number">
                    <input type="hidden" name="confirm" value="cancel">
                    <div class="modal-body">
                        <p>@lang('Are you sure to cancel this booking')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-white"
                            data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success btn-rounded text-white">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="declinedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h4 class="modal-title" id="declinedModal">@lang('Decline Service Confirmation')</h4>
                    <button type="button" onclick="closeModal('declinedModal')" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="POST" action="{{route('user.decline-service',['id' => encrypt($booking->id)])}}">
                    @csrf
                    <div class="modal-body">
                        <label for="">Decline Reason</label>
                        <div class="form-group">
                            <textarea rows="4" class="form-control" name="decline_reason" placeholder="Decline Reason"
                                required></textarea>
                            <small>@lang('Minimum 100 Characters Required')</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger btn-rounded text-white"
                        onclick="closeModal('declinedModal')">@lang('No')</button>
                        <button type="submit" class="btn btn-success btn-rounded btn-sm text-white">@lang('Yes')</button>
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
                    <h4 class="modal-title" id="ModalLabel">@lang('Booking Work Delivery')</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('user.work.upload')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="work_type" value="service">
                        <div class="form-group">
                            <div>
                                <label for="formFileLg"
                                    class="form-label font-weight-bold">@lang('Upload Work File')</label>
                                <input class="form-control form-control-lg" name="file" id="formFileLg" type="file"
                                    required="">
                                <small>@lang('Supported files:zip and max size:25 Mb')</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <textarea rows="8" class="form-control" name="details"
                                placeholder="Describe Your Delivery Details ...." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-rounded text-white"
                            data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-base btn-rounded text-white">@lang('Submit')</button>
                    </div>
                </form>
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
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
                                <button type="submit" class="btn btn-base" style="width:100%;">@lang('Submit')</button>
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
    <script>
        'use strict';
        $('#approvedBtn').on('click', function () {
            var modal = $('#approvedModal');
            modal.modal('show');
        });

        $('#declineService').on('click', function () {
            var modal = $('#declinedModal');
            modal.modal('show');
        });

        function closeModal(modalName) {
            var modal = $('#' + modalName);
            modal.modal('hide');
        }

        $('.cancelBtn').on('click', function () {
            var modal = $('#cancelModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.workBtn').on('click', function () {
            var modal = $('#workModal');
            modal.find('input[name=order_number]').val($(this).data('order_number'))
            modal.modal('show');
        });

        $('.disputeShow').on('click', function () {
            var modal = $('#disputeModalShow');
            var feedback = $(this).data('dispute');
            modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>
@endsection