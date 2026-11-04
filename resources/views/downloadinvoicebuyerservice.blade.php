<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiserlance Invoice - {{ $booking->order_number ?? 'INVOICE' }}</title>
    {{-- Same design as the Job Module invoice (workspace/print.blade.php) --}}
    <style>
        @page {
            size: A4;
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid black;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .from-to-table {
            width: 80%;
            border-collapse: collapse;
        }
        .from-to-table th, .from-to-table td {
            border: none;
            padding: 8px;
            text-align: left;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table .breakdown td {
            font-size: 12px;
        }
        .details-table th {
            width: 35%;
        }
        .text-danger {
            color: #e74c3c;
        }
        .check {
            font-family: 'DejaVu Sans', sans-serif;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }
        .footer small {
            font-size: 11px;
        }
        @media screen and (max-width: 600px) {
            body { padding: 10px; }
            .from-to-table { width: 100%; }
            .table-wrap { overflow-x: auto; }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/wiserlance/assets/img/logos/logo-wiserlance-color.svg') }}" style="width: 200px;" alt="Wiserlance">
        <p>Invoice Number #: {{ $booking->order_number ?? 'N/A' }}</p>
        <p>Created Date: {{ isset($booking->created_at) ? date('d M Y', strtotime($booking->created_at)) : date('d M Y') }}</p>
    </div>

    <div class="invoice-details">
        <table class="from-to-table">
            <tr>
                <th>FROM</th>
            </tr>
            <tr>
                <td>Name: {{ ($buyer->firstname ?? '') . ' ' . ($buyer->lastname ?? '') }}</td>
            </tr>
            <tr>
                <td>Address: {{ $buyer?->address?->address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Email: {{ $buyer->email ?? 'N/A' }}</td>
            </tr>
        </table>
        <table class="from-to-table">
            <tr>
                <th>TO</th>
            </tr>
            <tr>
                <td>Name: {{ ($seller->firstname ?? '') . ' ' . ($seller->lastname ?? '') }}</td>
            </tr>
            <tr>
                <td>Address: {{ $seller?->address?->address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Email: {{ $seller->email ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @php

        $servicePrice = $booking->service_price ?? 0;
        $qty = $booking->qty ?? 1;
        $discount = $booking->discount ?? 0;
        $addOnsPrice = $addOnsPrice ?? 0;

        $platformFeeAmount = $platformFee ?? 0;
        $platformFeePercent = $platformFeePercentage ?? 0;

        $subTotalRaw = ($servicePrice * $qty) + $addOnsPrice;

        $finalAmount = ($subTotalRaw - $discount) + $platformFeeAmount;

        $transactions = $transactions ?? $finalAmount;

    @endphp

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Extra Services</th>
                    <th>Platform Fee</th>
                    <th>Subtotal</th>
                    <th>Total Amount</th>
                    <th>Total Paid</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $service->title ?? 'Service Package' }}</td>
                    <td>${{ number_format($servicePrice, 2) }}</td>
                    <td>{{ $qty }}</td>
                    <td>${{ number_format($addOnsPrice, 2) }}</td>
                    <td>${{ number_format($platformFeeAmount, 2) }} ({{ $platformFeePercent }}%)</td>
                    <td>
                        ${{ number_format($subTotalRaw, 2) }}
                        @if($discount > 0)
                            <br>
                            <small class="text-danger">(Includes -${{ number_format($discount, 2) }} discount)</small>
                        @endif
                    </td>
                    <td>${{ number_format($finalAmount, 2) }}</td>
                    <td>${{ number_format($transactions, 2) }}</td>
                </tr>
                <tr class="breakdown">
                    <td colspan="8">
                        <strong>Fee Breakdown:</strong><br>
                        - Service Platform Fee: {{ $platformFeePercent }}% (${{ number_format($platformFeeAmount, 2) }})<br>
                        @if($discount > 0)
                            - Discount Applied: -${{ number_format($discount, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="7">Total Amount</td>
                    <td>${{ number_format($finalAmount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="7">Total Paid</td>
                    <td>${{ number_format($transactions, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="table details-table">
        <tr>
            <th>Order / Booking ID</th>
            <td>{{ $booking->order_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Platform Fee Percentage</th>
            <td>{{ $platformFeePercent }}%</td>
        </tr>
        <tr>
            <th>Invoice Generation Timestamp</th>
            <td>{{ isset($booking->created_at) ? date('d M Y H:i:s', strtotime($booking->created_at)) : date('d M Y H:i:s') }} UTC</td>
        </tr>
        @if(isset($service_paid_amount) && $service_paid_amount)
            <tr>
                <th>Settlement Status</th>
                <td><span class="check">✓</span> Full amount settled</td>
            </tr>
        @endif
    </table>

    <div class="footer">
        <p>This is a computer generated invoice</p>
        <small>
            Invoice ID: {{ $booking->order_number ?? 'N/A' }} | Created: {{ isset($booking->created_at) ? date('d M Y H:i', strtotime($booking->created_at)) : date('d M Y H:i') }}
            <br>
            Digitally verified · No signature required · Wiserlance secure payment
        </small>
    </div>
</body>
</html>
