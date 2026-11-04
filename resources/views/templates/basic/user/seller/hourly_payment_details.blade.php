<div class="card shadow-sm mt-3">
    <div class="card-body p-4 pb-2">
        <a class="links-c2" data-bs-toggle="collapse" href="#collapsePayment" role="button" aria-expanded="true"
            aria-controls="collapsePayment">
            <i class="fa-solid fa-circle-chevron-down float-end text-dgreen2 fs-4"></i>
            <h5 class="mb-3 text-dgreen">Payment Details</h5>
        </a>

        <div class="collapse" id="collapsePayment" style="">
            <table class="table table-bordered fs-14">
                <thead>
                    <tr class="bg-dgreen2 text-white">
                        <th scope="col">Description</th>
                        <th scope="col">Hours</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_hours = 0;
                        $total_amount = 0;
                        $recieved_amount = 0;
                        $platform_fee_amount = 0;
                    @endphp
                    @forelse($payment_details as $key => $payment_detail)
                        @if (is_array($payment_detail))
                            @php
                                $total_hours += $payment_detail['raw_hours'] + $payment_detail['raw_minutes'] / 60;
                                $total_amount += $payment_detail['Amount'];
                                $recieved_amount = $total_amount;
                                $platform_fee_amount = ($recieved_amount * $platform_fee) / 100;

                            @endphp
                            <tr>
                                <td>{{ $payment_detail['Week'] }}</td>
                                <td>{{ $payment_detail['Hours'] }}</td>
                                <td>${{ $payment_detail['Amount'] ?? 0 }}</td>
                            </tr>
                        @endif
                    @empty
                    @endforelse

                    <tr class="bg-lgreen3">
                        <td colspan="2">Total Hours</td>
                        <td>{{ number_format($total_hours,2)}}</td>
                    </tr>
                    <tr class="bg-lgreen3">
                        <td colspan="2">Total Amount</td>
                        <td>${{ number_format($total_amount, 2) }}</td>
                    </tr>
                    <tr class="bg-lgreen3">
                        <td colspan="2">Service Fee ( {{$platform_fee}}% )</td>
                        <td>${{ number_format($platform_fee_amount, 2) }}</td>
                    </tr>
                    <tr class="bg-lgreen3">
                        <td colspan="2">You'll Receive</td>
                        <td>${{ number_format($total_amount - $platform_fee_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
