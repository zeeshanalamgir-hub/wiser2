@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="row mt-2">
        <div class="col-12">
            <div class="dropdown mb-1"><!--Dropdown-->
                {{-- <button class="btn btn-sm btn-outline-secondary dropdown-toggle float-end" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    @lang('Select Log')
                </button>
                <ul class="dropdown-menu fs-14" id="getBidsReport">
                    <li data-value="0"><button class="dropdown-item" type="button">Transaction Log</button></li>
                    <li data-value="1"><button class="dropdown-item" type="button">Bids Log</button></li>
                </ul> --}}

                <button class="btn btn-secondary btn-sm dropdown-toggle float-end" type="button" id="selectedFilter" data-bs-toggle="dropdown">
                    Select Log
                </button>

                <ul class="dropdown-menu fs-14" id="getBidsReport">
                    <li data-value="0">
                        <button class="dropdown-item" type="button">
                            All Reports
                        </button>
                    </li>

                    <li data-value="1">
                        <button class="dropdown-item" type="button">
                            Transaction Log
                        </button>
                    </li>

                    <li data-value="2">
                        <button class="dropdown-item" type="button">
                            Bids Log
                        </button>
                    </li>
                </ul>

            </div><!--Dropdown End-->

            <h5 class="text-dgreen2">Reports Log</h5>

            <!-- Transaction Log Table -->
            <div class="card shadow-sm border mt-md-3 p-4" id="tra">
                <div class="table-responsive">
                    <table class="table table-hover w-100">
                        <thead>
                            <tr class="fs-14 text-dgreen2">
                                <th scope="col">Date</th>
                                <th scope="col" class="text-nowrap">TRX</th>
                                <th scope="col" class="text-nowrap">Amount</th>
                                <th scope="col" class="text-nowrap">Charge</th>
                                <th scope="col" class="text-nowrap" style="min-width: 140px;">Post Balance</th>
                                <th scope="col">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr class="fs-14">
                                    <td>
                                        <span class="text-secondary text-nowrap">
                                            <i class="fa-solid fa-calendar-days"></i>
                                            {{ showDateTime($transaction->created_at) }}
                                        </span>
                                    </td>

                                    <td class="text-nowrap">
                                        <span class="fw-bold">{{ $transaction->trx }}</span>
                                    </td>

                                    <td class="text-nowrap">
                                        <span
                                            class="@if ($transaction->trx_type == '+') text-success @else text-danger @endif fw-bold">
                                            {{ $transaction->trx_type == '+' ? '+' : '-' }}${{ getAmount($transaction->amount) }}
                                        </span>
                                    </td>

                                    <td class="text-nowrap">
                                        ${{ getAmount($transaction->charge) }}
                                    </td>
                                    
                                    @if($transaction?->booking?->booking_type == 'service'
                                        && $transaction->user_id == $transaction->booking->buyer_id
                                        && $transaction->trx_type == '-'
                                        && $transaction->work_id)
                                        @php
                                            $heldAmount = $transaction->booking->amount + $transaction->booking->buyer_service_fee;
                                            $releasedBefore = \App\Models\Transaction::where('booking_id', $transaction->booking_id)
                                                ->where('user_id', $transaction->user_id)
                                                ->where('details', 'like', 'Payment for %')
                                                ->whereNotNull('work_id')
                                                ->where('work_id', '!=', $transaction->work_id)
                                                ->where('id', '<', $transaction->id)
                                                ->sum('amount');
                                        @endphp
                                        <td class="text-nowrap" style="min-width: 140px;">
                                            ${{ getAmount($transaction->post_balance + $heldAmount - $releasedBefore - $transaction->amount) }}
                                        </td>
                                    @else
                                        <td class="text-nowrap" style="min-width: 140px;">
                                            ${{ getAmount($transaction->post_balance) }}
                                        </td>
                                    @endif

                                    <td>
                                        {{ __($transaction->details) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        {{ __($emptyMessage) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>

            <!-- Bids Log Table -->
            <div class="card shadow-sm border mt-md-3 p-4" id="bids">
                <div class="table-responsive">
                    <table class="table table-hover w-100">
                        <thead>
                            <tr class="fs-14 text-dgreen2">
                                <th scope="col">Date</th>
                                <th scope="col" class="text-nowrap">Bids</th>
                                <th scope="col" class="text-nowrap" style="min-width: 140px;">Post Balance</th>
                                <th scope="col">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bidsLog as $bids)
                                <tr class="fs-14">
                                    <td>
                                        <span class="text-secondary text-nowrap">
                                            <i class="fa-solid fa-calendar-days"></i>
                                            {{ showDateTime($bids->created_at) }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <span
                                            class="@if ($bids->tr_op == '+') text-success @else text-danger @endif fw-bold">
                                            {{ $bids->tr_op == '+' ? '+' : '-' }}{{ $bids->bids }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap" style="min-width: 140px;">
                                        ${{ $bids->post_balance }}
                                    </td>
                                    <td>
                                        {{ $bids->details }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        {{ __($emptyMessage) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $bidsLog->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // $(document).ready(function() {
        //     $('#tra').show();
        //     $('#bids').hide();
        //     $('.dropdown-menu li').click(function() {
        //         let value = $(this).data('value');
        //         if (value == 0) {
        //             $('#tra').show();
        //             $('#bids').hide();
        //         } else if (value == 1) {
        //             $('#bids').show();
        //             $('#tra').hide();
        //         }
        //     });
        // });

        $(document).ready(function() {

            // Default: All Reports
            $('#tra').show();
            $('#bids').show();

            $('#getBidsReport li').click(function() {

                let value = $(this).data('value');
                let text = $(this).find('.dropdown-item').text().trim();

                // Selected filter button mein show karein
                $('#selectedFilter').text(text);

                if (value == 0) {

                    // All Reports
                    $('#tra').show();
                    $('#bids').show();

                } else if (value == 1) {

                    // Transaction Log
                    $('#tra').show();
                    $('#bids').hide();

                } else if (value == 2) {

                    // Bids Log
                    $('#tra').hide();
                    $('#bids').show();

                }

            });

        });
    </script>
@endsection
