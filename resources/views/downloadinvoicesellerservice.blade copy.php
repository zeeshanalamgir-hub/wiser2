<!DOCTYPE html>
<html>
    <head>
        <title>Wiserlance Invoice</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    </head>
    
    <body>
        <div class="row p-3 mt-3"> 
            <div class="col-lg-12 mt-3">
                <h5>INVOICE</h5>
            </div>
            <div class="col-lg-12 mt-3">
                <table class="table">
                    <tbody>
                        <tr style="width: 100%; border-color:white;">
                            <td style="width: 50%; border: 1px solid white !important;">
                                <span class="font-weight-bold">Invoice Number</span><br>
                                <span>{{ $booking->order_number }}</span><br>
                            </td>
                            <td></td>
                            <td style="width: 50%; border: 1px solid white !important;">
                                <span class="font-weight-bold">Date Created</span><br>
                                <span>{{ showDateTime($booking->created_at, 'd M Y') }}</span><br>
                            </td>
                        </tr>

                        <tr style="width: 100%; border-color:white;">
                            <td style="width: 50%; border: 1px solid white !important;">
                                <span class="font-weight-bold">Buyer's Detail</span><br>
                                <span>Name: {{ __($booking?->service?->user?->firstname) }} {{ __($booking?->service?->user?->lastname) }}</span><br>
                                <span>Address: </span><br>
                            </td>
                            <td></td>
                            @php
                                $service = DB::table('services')->where('id', $booking->service_id)->first();
                                $seller = DB::table('users')->where('id', $service->user_id)->first();
                            @endphp
                            <td style="width: 50%; border: 1px solid white !important;">
                                <span class="font-weight-bold">Seller's Detail</span><br>
                                <span>Name: {{ __($seller->firstname) }} {{ __($seller->lastname) }}</span><br>
                                <span>Address: {{ $seller->country }}</span><br>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <style type="text/css">
                .table tr th, .table tr td {
                    padding: 5px 8px !important;
                    vertical-align: top;
                }
            </style>
            
            <div class="col-lg-12 mt-4">
                <table class="table">
                    
                    <tbody>
                       
                        
                        <!-- Summary Section -->
                        <tr>
                            <td colspan="4" style="padding-top: 30px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Invoice Summary:</h6>
                                        <table class="table table-sm border-0">
                                            @php
                                                $incomes = number_format($fees['packagePrice'] * $booking->qty, 2) + number_format($fees['addOnsPrice'], 2) + number_format($fees['extraServicesTotal'], 2);
                                                $deductions = number_format($fees['calculatedSellerServiceFee'], 2) + number_format($fees['paymentProcessingFee'] ?? 0, 2) + number_format($fees['calculatedBuyerServiceFee'] ?? 0, 2);
                                            @endphp
                                            <tr>
                                                <td>Service Price:</td>
                                                <td class="text-right">$ {{ number_format($fees['packagePrice'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>× Quantity:</td>
                                                <td class="text-right">{{ $booking->qty }}</td>
                                            </tr>
                                            <tr>
                                                <td>Service Subtotal:</td>
                                                <td class="text-right">$ {{ number_format($fees['packagePrice'] * $booking->qty, 2) }}</td>
                                            </tr>
                                            @if(($fees['extraServicesTotal'] ?? 0) > 0)
                                            <tr>
                                                <td>Additional Services:</td>
                                                <td class="text-right">$ {{ number_format($fees['extraServicesTotal'], 2) }}</td>
                                            </tr>
                                            @endif
                                            @if(($fees['addOnsPrice'] ?? 0) > 0)
                                            <tr>
                                                <td>Add-ons:</td>
                                                <td class="text-right">$ {{ number_format($fees['addOnsPrice'], 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr style="border-top: 1px solid #dee2e6;">
                                                <td><strong>Subtotal:</strong></td>
                                                <td class="text-right"><strong>$ {{ $incomes ?? 0 }}</strong></td>
                                            </tr>
                                            
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Deductions:</h6>
                                        <table class="table table-sm border-0">
                                            <tr>
                                                <td>Platform Fee ({{ number_format(($fees['calculatedSellerServiceFee'] / $fees['totalBeforeFee'] * 100), 2) }}%):</td>
                                                <td class="text-right">-$ {{ number_format($fees['calculatedSellerServiceFee'], 2) }}</td>
                                            </tr>
                                            @if(($fees['paymentProcessingFee'] ?? 0) > 0)
                                            <tr>
                                                <td>Payment Processing:</td>
                                                <td class="text-right">-$ {{ number_format($fees['paymentProcessingFee'], 2) }}</td>
                                            </tr>
                                            @endif
                                            @if(($fees['calculatedBuyerServiceFee'] ?? 0) > 0)
                                            <tr>
                                                <td>Buyer Service Fee:</td>
                                                <td class="text-right">-$ {{ number_format($fees['calculatedBuyerServiceFee'], 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr style="border-top: 1px solid #dee2e6; background-color: #e9ecef;">
                                                <td><strong>Net Amount:</strong></td>
                                                <td class="text-right"><strong>$ {{ $incomes - $deductions }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="col-lg-12 mt-5">
                <div class="alert alert-light border" role="alert">
                    <p class="font-weight-bold mb-1">This is a computer-generated invoice.</p>
                    <small class="text-muted">Invoice ID: {{ $booking->order_number }} | Created: {{ showDateTime($booking->created_at, 'd M Y H:i') }}</small>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0JzD6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    </body>
</html>