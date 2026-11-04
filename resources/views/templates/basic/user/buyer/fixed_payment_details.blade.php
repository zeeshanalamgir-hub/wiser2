<div class="card shadow-sm mt-3">
    <div class="card-body p-4 pb-2">
        <a class="links-c2 collapsed" data-bs-toggle="collapse" href="#collapsePayment" role="button" aria-expanded="false"
            aria-controls="collapsePayment">
            <i class="fa-solid fa-circle-chevron-down float-end text-dgreen2 fs-4"></i>
            <h5 class="mb-3 text-dgreen">Payment Details</h5>
        </a>

        <div class="collapse" id="collapsePayment" style="">
            <table class="table table-bordered fs-14">
                <thead>
                    <tr class="bg-dgreen2 text-white">
                        <th scope="col">Description</th>
                        {{-- <th scope="col">Due Date</th> --}}
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $platform_fee_amount = ($payment_details['receive_payment'] * $platform_fee) / 100;
                    @endphp
                    <tr>
                        <td>Project Payment</td>
                        {{-- <td>Upon Completion</td> --}}
                        <td>${{ number_format($totalBudget, 2) }}</td>
                    </tr>

                    <tr class="bg-lgreen3">
                        <td>Total Amount</td>
                        <td>${{ number_format($totalBudget, 2) }}</td>
                    </tr>
                    
                    <tr class="bg-lgreen3">
                        <td>Job Platform Fee ( {{$platform_fee}}% )</td>
                        <td>${{ number_format($platform_fee_amount, 2) }}</td>
                    </tr>
                    
                    <tr class="bg-lgreen3">
                        <td>You'll Pay</td>
                        <td>${{ number_format($totalBudget +  $platform_fee_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
