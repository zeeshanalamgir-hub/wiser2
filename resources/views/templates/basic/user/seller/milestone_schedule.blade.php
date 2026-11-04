<div class="card shadow-sm mt-3">
    <div class="card-body p-4 pb-2">
        <a class="links-c2" data-bs-toggle="collapse" href="#collapsePayment" role="button" aria-expanded="true"
            aria-controls="collapsePayment">
            <i class="fa-solid fa-circle-chevron-down float-end text-dgreen2 fs-4"></i>
            <h5 class="mb-3 text-dgreen">Milestone Schedule</h5>
        </a>

        <div class="collapse" id="collapsePayment" style="">
            <table class="table table-bordered fs-14">
                <thead>
                    <tr class="bg-dgreen2 text-white">
                        <th scope="col">Milestone Title</th>
                        {{-- <th scope="col">Due Date</th> --}}
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_amount = 0;
                        $platform_fee_amount = ($payment_details['total_budget'] * $platform_fee) / 100;
                    @endphp
                    @forelse($payment_details['milestones'] as $key => $milestone)
                    @php
                        $total_amount+=$milestone['amount'];
                    @endphp
                        <tr>
                            <td>{{ $milestone['title'] ?? 'Project Payment' }}</td>
                            {{-- <td>{{ $milestone['due_date'] }}</td> --}}
                            <td>${{ number_format($milestone['amount'],2) }}</td>
                        </tr>
                    @empty
                    @endforelse

                    <tr class="bg-lgreen3">
                        <td>Total Amount</td>
                        <td>${{ number_format($payment_details['total_budget'],2) }}</td>
                    </tr>
                    <tr class="bg-lgreen3">
                        <td>Service Fee ( {{$platform_fee}}% )</td>
                        <td>${{ $platform_fee_amount }}</td>
                    </tr>
                    <tr class="bg-lgreen3">
                        <td>You’ll Receive</td>
                        <td>${{ $payment_details['receive_payment']-$platform_fee_amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
