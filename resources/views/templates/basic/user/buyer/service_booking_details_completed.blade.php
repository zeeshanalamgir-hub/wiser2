@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="row pb-lg-4 mt-4">
        <div class="col-lg-4 pb-4 pb-lg-0">
            <x-buyer-service-mob-nav :booking="$booking" />

            <!--Breadcrumbs-->
            <p class="mb-0 text-bc mt-1 fs-14">
                <a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
                    <i class="fa-solid fa-house fs-14"></i>
                </a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a class="text-3gray" href="{{ route('user.service-booked') }}">Service Booking</a>
                <i class="fa-solid fa-chevron-right fs-12"></i>
                Invoice
            </p>
        </div>
        <x-buyer-service-nav :booking="$booking" activeTab="invoice" />
    </div>

    <div class="card lg-3 px-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
        <!-- Header Section -->
        <div class="w-100">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <h5 class="text-dgreen2 mb-3 mb-sm-0">Invoice Details</h5>
                <p class="mb-0">
                    <a class="links-c" href="{{ route('user', ['id' => $booking->seller_id]) }}">
                        <i class="fa-regular fa-comment-dots fs-5 me-1"></i>
                    </a>
                    Chat with <span class="text-green fw-semibold">{{ $booking?->seller?->firstname }} {{ $booking?->seller?->lastname }}</span>
                </p>
            </div>
        </div>

        <!-- Invoice Header Info -->
        <div class="bg-lgreen3 px-4 py-3 text-dgreen rounded-3 mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="fw-semibold me-2">INVOICE NO:</span>
                <span class="fs-14 text-secondary">{{ $booking->order_number }}</span>
            </div>
            <div>
                <span class="fw-semibold me-2">Date Created:</span>
                <span class="fs-14 text-secondary">{{ showDateTime($booking->created_at, 'd M Y') }}</span>
            </div>
        </div>

        <!-- Bill From & Bill To -->
        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <div class=" p-3 rounded-3 h-100">
                    <span class="fs-5 text-green fw-semibold d-block mb-2">Bill From</span>
                     <div class="">
                        <span class="fw-semibold d-block mb-1">{{ $booking->buyer->firstname }} {{ $booking?->buyer?->lastname }}</span>
                        <span class="fs-14 text-muted d-block">Address: {{ $booking?->buyer?->address?->address ?? 'N/A' }}</span>
                        <span class="fs-14 text-muted d-block">Phone: {{ $booking?->buyer?->mobile ?? 'N/A' }}</span>
                        <span class="fs-14 text-muted">Email: {{ $booking?->buyer?->email }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class=" p-3 rounded-3 h-100">
                    <span class="fs-5 fw-semibold text-dgreen2 d-block mb-2">Bill To</span>
                   
                    <div class="">
                        <span class="fw-semibold d-block mb-1">{{ $booking->seller->firstname }} {{ $booking->seller->lastname }}</span>
                        <span class="fs-14 text-muted d-block">Address: {{ $booking->seller->address->address ?? 'N/A' }}</span>
                        <span class="fs-14 text-muted d-block">Phone: {{ $booking->seller->mobile ?? 'N/A' }}</span>
                        <span class="fs-14 text-muted">Email: {{ $booking->seller->email }}</span>
                    </div>
                </div>
            </div>
        </div>

        @php
            $incomes = number_format($subTotal, 2) + number_format($addOnPrice, 2);
            $deductions = number_format($platformFee, 2);
        @endphp

        <!-- Invoice Items Table -->
        <div class="mt-4">
            <div class="bg-lgreen3 p-2 px-3 rounded-3 mb-3">
                <span class="fw-semibold text-dgreen">Invoice Items</span>
            </div>

            <!-- Items List -->
            <div class="invoice-items">
                <!-- Service Item -->
                <div class="invoice-row d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="invoice-label text-muted" style="flex: 2;">Service</div>
                    <div class="invoice-value fw-semibold text-end" style="flex: 1;">{{ $booking->service->title }}</div>
                </div>

                <div class="invoice-row d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="invoice-label text-muted" style="flex: 2;">Price</div>
                    <div class="invoice-value fw-semibold text-end" style="flex: 1;">${{ number_format($booking->service_price, 2) }}</div>
                </div>

                <div class="invoice-row d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="invoice-label text-muted" style="flex: 2;">Quantity</div>
                    <div class="invoice-value fw-semibold text-end" style="flex: 1;">{{ $booking->qty }}</div>
                </div>

                @if($addOnPrice > 0)
                    <div class="invoice-row d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div class="invoice-label text-muted" style="flex: 2;">Additional Services</div>
                        <div class="invoice-value fw-semibold text-end" style="flex: 1;">${{ number_format($addOnPrice, 2) }}</div>
                    </div>
                @endif

                <div class="invoice-row d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="invoice-label text-danger fw-semibold" style="flex: 2;">Platform Fee ({{ $buyer_service_platform_fee }}%)</div>
                    <div class="invoice-value text-danger fw-semibold text-end" style="flex: 1;">${{ number_format($platformFee, 2) }}</div>
                </div>

                @if($booking->discount > 0)
                    <div class="invoice-row d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div class="invoice-label text-success fw-semibold" style="flex: 2;">Discount</div>
                        <div class="invoice-value text-success fw-semibold text-end" style="flex: 1;">-${{ number_format($booking->discount, 2) }}</div>
                    </div>
                @endif
                
                @php
                    $totalPaidAmount = 0;
                @endphp
                @foreach($allworks as $index => $work)
                    @php
                        $deliveryId = 'collapseDelivery' . ($index + 1);
                        
                        // Determine status label and color
                        switch ($work->status) {
                            case 0:
                                $statusLabel = 'Pending';
                                $statusColor = 'secondary';
                                $statusIcon = 'fa-regular fa-clock';
                                break;
                            case 1:
                                $statusLabel = 'Completed';
                                $statusColor = 'success';
                                $statusIcon = 'fa-regular fa-circle-check';
                                break;
                            case 2:
                                $statusLabel = 'Delivered';
                                $statusColor = 'info';
                                $statusIcon = 'fa-solid fa-truck';
                                break;
                            case 3:
                                $statusLabel = 'Declined';
                                $statusColor = 'danger';
                                $statusIcon = 'fa-regular fa-circle-xmark';
                                break;
                            case 4:
                                $statusLabel = 'In Progress';
                                $statusColor = 'primary';
                                $statusIcon = 'fa-solid fa-spinner fa-spin';
                                break;
                            case 5:
                                $statusLabel = 'Canceled';
                                $statusColor = 'danger';
                                $statusIcon = 'fa-regular fa-ban';
                                break;
                            case 6:
                                $statusLabel = 'Paid';
                                $statusColor = 'success';
                                $statusIcon = 'fa-regular fa-credit-card';
                                break;
                            default:
                                $statusLabel = 'Unknown';
                                $statusColor = 'secondary';
                                $statusIcon = 'fa-regular fa-question-circle';
                        }
                        
                        // Get transaction amount only if status is Paid (6)
                        $transaction = $transactions[$work->id] ?? null;
                        $amount = ($work->status == 6) ? ($transaction ? ($transaction->amount) : ($work->payed_amount ?? 0)) : 0;
                        $totalPaidAmount += $amount;
                    @endphp
                    
                    <div class="invoice-row d-flex justify-content-between align-items-center py-3 border-bottom revision-item">
                        <div class="d-flex align-items-center gap-3" style="flex: 2;">
                            <span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                                Revision {{ $index + 1 }}
                            </span>
                            <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-2 rounded-pill">
                                <i class="{{ $statusIcon }} me-1"></i>{{ $statusLabel }}
                            </span>
                        </div>
                        <div class="invoice-value fw-bold text-{{ $statusColor }} text-end" style="flex: 1;">
                            @if($work->status == 6)
                                ${{ number_format($amount, 2) }}
                            @else
                                0
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class=" d-flex justify-content-between align-items-center py-3 rounded-3 border-bottom">
                    <div class="invoice-label fw-bold text-success" style="flex: 2;">You'll Pay</div>
                    <div class="invoice-value text-success fw-bold fs-5 text-end" style="flex: 1;">
                        ${{ number_format($booking->agreed_amount + $platformFee, 2) }}
                    </div>
                </div>

                <!-- Total Amount -->
                <div class=" d-flex justify-content-between align-items-center py-3 rounded-3 border-bottom">
                    <div class="invoice-label fw-bold text-success" style="flex: 2;">Total Paid</div>
                    <div class="invoice-value text-success fw-bold fs-5 text-end" style="flex: 1;">
                        ${{ number_format($totalPaidAmount, 2) }}
                    </div>
                </div>


                <!-- Status -->
                <div class="invoice-row d-flex justify-content-between align-items-center py-3 mt-3">
                    <div class="invoice-label fw-semibold" style="flex: 2;">Payment Status</div>
                    <div class="invoice-value text-end" style="flex: 1;">
                        @if($booking->working_status == 1)
                            <span class="badge bg-success px-4 py-2 rounded-pill">
                                <i class="fa-regular fa-circle-check me-1"></i>Paid
                            </span>
                        @else
                            <span class="badge bg-danger px-4 py-2 rounded-pill">
                                <i class="fa-regular fa-circle-xmark me-1"></i>Unpaid
                            </span>
                        @endif
                    </div>
                </div>

                
            </div>
        </div>

        <!-- Invoice Footer Note -->
        <div class="mt-5 pt-3 border-top">
            <div class="alert alert-info bg-info bg-opacity-10 border-0 rounded-3">
                <i class="fa-solid fa-info-circle me-2"></i>
                <span class="small">This is a computer-generated invoice. No signature is required.</span>
            </div>
        </div>
    </div>

    <!-- Download Button -->
    <div class="mt-4 d-flex justify-content-end">
        <a href="{{ route('buyer.service.invoice.pdf', ['id' => $booking->id]) }}"
           class="btn btn-success bg-green border-0 px-4 py-2 shadow-sm">
            <i class="fa-solid fa-download me-2"></i>Download
        </a>
    </div>
@endsection

@section('script')
    <div class="modal fade" id="disputeModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalLabel">@lang('Dispute Report')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="dispute-detail"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

   
@endsection

<style>
    /* Additional Custom Styles */
    .bg-success-soft {
        background-color: rgba(40, 167, 69, 0.1);
    }
    .bg-warning-soft {
        background-color: rgba(255, 193, 7, 0.1);
    }
    .invoice-row {
        transition: all 0.2s ease;
    }
    .invoice-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .invoice-row {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 8px;
        }
        .invoice-row > div {
            width: 100%;
            text-align: left !important;
        }
        .invoice-label, .invoice-value {
            flex: auto !important;
        }
        .invoice-value.text-end {
            text-align: left !important;
        }
    }
</style>