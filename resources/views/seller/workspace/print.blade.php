<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
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
        .logo {
            color: #4169E1;
            font-size: 30px;
            font-weight: bold;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
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
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }
        @media print {
            .invoice-details {
                display: flex !important;
                justify-content: space-between !important;
            }
            .from-to-table {
                width: 45% !important;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/wiserlance/assets/img/logos/logo-wiserlance.svg') }}"
                        style="width: 200px;">
        <p>Invoice Number #: {{ $invoice['invoiceNumber'] }}</p>
        <p>Created Date: {{ $invoice['created_at']->format('Y-d-m') }}</p>
    </div>
    @php
        $isPlatform = Str::contains($invoice['details'], 'Platform');
    @endphp

    @if($isPlatform)
        <div class="invoice-details">
            <table class="from-to-table">
                <tr>
                    <th>FROM</th>
                </tr>
                <tr>
                    <td>Name: {{ $invoice['seller'] }}</td>
                </tr>
                <tr>
                    <td>Address: {{ $invoice['seller_address'] }}</td>
                </tr>
            </table>
            <table class="from-to-table">
                <tr>
                    <th>To</th>
                </tr>
                <tr>
                    <td>Name: Wiserlance Team</td>
                </tr>
            </table>
        </div>
    @else
        <div class="invoice-details">
            <table class="from-to-table">
                <tr>
                    <th>FROM</th>
                </tr>
                <tr>
                    <td>Name: {{ $invoice['employeer'] }}</td>
                </tr>
                <tr>
                    <td>Address: {{ $invoice['employeer_address'] }}</td>
                </tr>
            </table>
            <table class="from-to-table">
                <tr>
                    <th>To</th>
                </tr>
                <tr>
                    <td>Name: {{  $invoice['seller'] }}</td>
                </tr>
                <tr>
                    <td>Address: {{ $invoice['seller_address'] }}</td>
                </tr>
            </table>
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Particulars</th>
                <th>Details</th>
                <th>Rate</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Invoice number: # {{ $invoice['invoiceNumber'] }}</td>
                <td>{{ $invoice['details'] ?? '--' }}</td>
                <td>${{ number_format($invoice['amount'],2) }}</td>
                <td>{{ $invoice['qty'] }}</td>
                <td>{{ $invoice['budgettype'] }}</td>
                <td>${{ number_format($invoice['amount'],2) }}</td>
            </tr>
            <tr>
                <td colspan="5">Total Price</td>
                <td>${{ number_format($invoice['amount'],2) }}</td>
            </tr>
        </tbody>
    </table>
    <div class="footer">
        <p>This is a computer generated invoice</p>
    </div>
</body>
</html>
