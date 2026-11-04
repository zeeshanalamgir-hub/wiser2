<!DOCTYPE html>
<html>
<head>
    <title>Wiserlance Invoice - {{ $booking->order_number ?? 'INVOICE' }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            background: white;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        .invoice-card {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e0e0e0;
        }
        .invoice-header {
            background: #1a2a3a;
            padding: 20px 25px;
            color: white;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            border-left: 3px solid #ffb347;
            padding-left: 15px;
        }
        .badge-status {
            background: #2ecc71;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }
        .info-section {
            padding: 25px;
        }
        /* Vertical table styling */
        .vertical-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .vertical-table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .vertical-table td:first-child {
            background: #f5f7fa;
            font-weight: 600;
            width: 30%;
        }
        /* Horizontal items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #eef2f5;
            padding: 10px 8px;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }
        .items-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }
        .items-table td:first-child {
            text-align: left;
        }
        .summary-table {
            width: 100%;
            margin-top: 15px;
        }
        .summary-table td {
            padding: 6px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-danger {
            color: #e74c3c;
        }
        .text-success {
            color: #27ae60;
        }
        .fw-bold {
            font-weight: bold;
        }
        .footer-note {
            background: #f9f9f9;
            border-top: 1px solid #ddd;
            padding: 12px 20px;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
        .badge {
            background: #27ae60;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 10px;
            display: inline-block;
        }
        .mt-3 {
            margin-top: 15px;
        }
        .mt-4 {
            margin-top: 20px;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
        .ml-auto {
            margin-left: auto;
        }
        .border-0 {
            border: none;
        }
        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #eee;
        }
        .text-muted {
            color: #6c757d;
        }
        small {
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="invoice-card">
        <!-- Header -->
        <div class="invoice-header">
            <table style="width: 100%; border: none; background: transparent;">
                <tr>
                    <td style="border: none; background: transparent;">
                        <div class="invoice-title">INVOICE</div>
                    </td>
                    
                </tr>
            </table>
        </div>

        <div class="info-section">
            <!-- ==================== INVOICE INFO & PARTIES ==================== -->
            <table class="vertical-table">
                <tr>
                    <td>Invoice Number</td>
                    <td><strong>{{ $booking->order_number ?? 'N/A' }}</strong></td>
                </tr>

                <tr>
                    <td>Date Created</td>
                    <td>{{ isset($booking->created_at) ? date('d M Y', strtotime($booking->created_at)) : date('d M Y') }}</td>
                </tr>

                <tr>
                    <td>Bill From</td>
                    <td>
                        <strong>Name:</strong>
                        {{ ($seller->firstname ?? '') . ' ' . ($seller->lastname ?? '') }}<br>

                        <strong>Address:</strong>
                        {{ $seller?->address?->address ?? 'N/A' }}<br>

                        <strong>Email:</strong>
                        {{ $seller->email ?? 'N/A' }}
                    </td>
                </tr>

                {{-- BILL TO = BUYER --}}
                <tr>
                    <td>Bill To</td>
                    <td>
                        <strong>Name:</strong>
                        {{ ($buyer->firstname ?? '') . ' ' . ($buyer->lastname ?? '') }}<br>

                        <strong>Address:</strong>
                        {{ $buyer?->address?->address ?? 'N/A' }}<br>

                        <strong>Email:</strong>
                        {{ $buyer->email ?? 'N/A' }}
                    </td>
                </tr>
            </table>

        <!-- ==================== ORDER SUMMARY TABLE ==================== -->
        @php
            $serviceTitle = $service->title ?? 'Service Package';
            $servicePrice = $booking->service_price ?? 0;
            $qty = $booking->qty ?? 1;
            $addOnsPrice = $addOnsPrice ?? 0;
            $discount = $booking->discount ?? 0;
            $platformFeePercent = $platform_fee ?? 0;
            $platformFeeAmount = $plarform_fee_amount ?? 0;
            $paymentProcessingFee = $payment_processing_fee ?? 0;
            
            $subTotalRaw = ($servicePrice * $qty) + $addOnsPrice;
            $finalAmount = $subTotalRaw - ($discount + $platformFeeAmount + $paymentProcessingFee);
            $transactions = $transactions ?? $finalAmount;
        @endphp

        <h5 style="font-weight: bold; margin-bottom: 15px; border-left: 3px solid #ffb347; padding-left: 12px;">Order Summary</h5>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Extra Services</th>
                    <th>Platform Fee</th>
                    <th>Subtotal</th>
                    <th>You'll Received</th>
                    <th>Total Received</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $serviceTitle }}</strong></td>
                    <td>${{ number_format($servicePrice, 2) }}</td>
                    <td>{{ $qty }}</td>
                    <td>${{ number_format($addOnsPrice, 2) }}</td>
                    <td>${{ number_format($platformFeeAmount, 2) }} ({{ $platformFeePercent }}%)</td>
                    <td>
                        ${{ number_format($subTotalRaw, 2) }}
                        @if($discount > 0)
                            <br><small style="color: #e74c3c;">(Includes -${{ number_format($discount, 2) }} discount)</small>
                        @endif
                    </td>
                    <td>${{ number_format($finalAmount, 2) }}</td>
                    <td>${{ number_format($transactions, 2) }}</td>
                </tr>
                <tr style="background: #f5f7fa;">
                    <td colspan="8" style="text-align: left; font-size: 10px; color: #666;">
                        <strong>Fee Breakdown:</strong><br>
                        - Service Platform Fee: {{ $platformFeePercent }}% (${{ number_format($platformFeeAmount, 2) }})<br>
                        @if($paymentProcessingFee > 0)
                        - Payment Processing Fee: ${{ number_format($paymentProcessingFee, 2) }}<br>
                        @endif
                        @if($discount > 0)
                        - Discount Applied: -${{ number_format($discount, 2) }}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- ==================== SUMMARY SECTION ==================== -->
        
        <!-- ==================== ADDITIONAL DETAILS ==================== -->
        <table class="vertical-table" style="margin-top: 20px;">
            <tr>
                <td>Order / Booking ID</td>
                <td><strong>{{ $booking->order_number ?? 'N/A' }}</strong></td>
            </tr>
            <tr>
                <td>Invoice Generation Timestamp</td>
                <td>{{ isset($booking->created_at) ? date('d M Y H:i:s', strtotime($booking->created_at)) : date('d M Y H:i:s') }} UTC</td>
            </tr>
        </table>

        <!-- Footer Note -->
        <div class="footer-note">
            <p style="font-weight: bold; margin-bottom: 5px;">This is a computer-generated invoice.</p>
            <small>Invoice ID: {{ $booking->order_number ?? 'N/A' }} | Created: {{ isset($booking->created_at) ? date('d M Y H:i', strtotime($booking->created_at)) : date('d M Y H:i') }}</small>
            <div style="margin-top: 6px;">
                <small>Digitally verified · No signature required · Wiserlance secure payment</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>