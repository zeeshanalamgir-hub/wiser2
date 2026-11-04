@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-4 col-xl-6">
        <h5 class="text-dgreen2 ms-2 mt-3">Transactions History</h5>
    </div>

    <form class="col-sm-8 col-md-5 col-xl-4 pe-lg-0" style="display: flex;align-items:center;justify-content:space-between" role="search" method="GET">
        <input 
            class="form-control bg-white float-end bg-lgreen"
            name="search"
            value="{{ request()->search }}"
            style="height:30px; font-size: 14px;"
            type="search"
            placeholder="Search"
            aria-label="Search"
        >

        <button class="btn btn-sm btn-success ms-1 bg-green border-0" 
            type="submit" style="height: 30px;">
            <img src="{{ asset('assets/images/icons8-search.svg') }}" width="20">
        </button>

        <a href="{{ url()->current() }}" 
            class="btn btn-sm btn-danger ms-1"
            style="height:30px;">
            Reset
        </a>
    </form>

    <div class="col-sm-4 col-md-3 col-xl-2 pe-xl-0 mt-3 mt-sm-0" style="display: flex;align-items:center;justify-content:end" >
        <a href="{{ route('user.seller.export.transaction') }}" target="_blank" class="btn btn-sm border-secondary fs-12 ms-2 bg-white">Export CSV</a>
    </div>

    <div class="col-12">
        <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
            <div class="table-responsive">
    <table class="table table-hover align-middle w-100">
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
                    
                    <td class="text-nowrap">
                        <span class="text-secondary">
                            <i class="fa-solid fa-calendar-days"></i>
                            {{ showDateTime($transaction->created_at) }}
                        </span>
                    </td>

                    <td class="text-nowrap">
                        <span class="fw-bold">{{ $transaction->trx }}</span>
                    </td>

                    <td class="text-nowrap">
                        <span class="fw-bold @if($transaction->trx_type == '+') text-success @else text-danger @endif">
                            {{ $transaction->trx_type == '+' ? '+' : '-' }}${{ getAmount($transaction->amount) }} 
                        </span>
                    </td>

                    <td class="text-nowrap">
                        ${{ getAmount($transaction->charge) }}
                    </td>

                    <td class="text-nowrap" style="min-width: 140px;">
                        ${{ getAmount($transaction->post_balance) }}
                    </td>

                    <td>
                        <span>{{ __($transaction->details) }}</span>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <span class="text-secondary">
                            <i class="fa-solid fa-calendar-days"></i>
                            {{ __($emptyMessage) }}
                        </span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
            
            {{-- Pagination --}}
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#tra').show();
    $('#bids').hide();
    $('.dropdown-menu li').click(function() {
        let value = $(this).data('value');
        if (value == 0) {
            $('#tra').show();
            $('#bids').hide();
        } else if (value == 1) {
            $('#bids').show();
            $('#tra').hide();
        }
    });
});
</script>
@endsection