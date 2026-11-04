<div class="row">
    <div class="col-12">
        <div class="card lg-3 p-4 shadow-sm" style="border: solid 1px #d2d2d2">
            <div class="row fs-14">
                <div class="col-lg-8 mb-3 mt-2 mb-md-0">
                    <h5> Activity </h5>
                </div>
                <div class="col-lg-4 mb-3 mt-2 mb-md-0" style="display: flex; justify-content: flex-end; align-items: center">
                    {{-- <img src="{{ asset('assets/wiserlance/assets/img/icons/search-icon.png') }}">
                    <img src="{{ asset('assets/wiserlance/assets/img/icons/filter-icon.png') }}"> --}}
                    <a href="{{route('user.seller.export.transaction')}}" class="export-button text-end btn btn-sm border-secondary fs-12 ms-2 bg-white" >Export CSV</a>
                    <a href="{{url('/user/seller/transactions')}}" class="text-end btn btn-sm border-secondary fs-12 ms-2 bg-white" >View all</a>
                </div>
            </div>
            @forelse($transactions as $transaction)
                <div class="row fs-14">
                    <div class="col-lg-12 mb-3 mt-2 mb-md-0">
                        <ul class="nav">
                            <li class="nav-item"><span class="text-secondary">@lang('Date'):</span> <span class="me-3 fw-bold"> {{ showDateTime($transaction->created_at) }} </span></li>
                            <li class="nav-item"><span class="text-secondary">@lang('TRX'):</span> <span class="me-3 fw-bold"> {{ $transaction->trx ?? 'N/A' }} </span></li>
                            <li class="nav-item">
                                <span class="text-secondary">@lang('Amount'):</span>
                                <span class="me-3 fw-bold">
                                    <strong class="{{ $transaction->trx_type == '+' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->trx_type == '+' ? '+$' : '-$' }}{{ getAmount($transaction->amount) }}
                                    </strong>
                                </span>
                            </li>
                            <li class="nav-item"><span class="text-secondary">@lang('Charge'):</span> <span class="me-3 fw-bold"> ${{ getAmount($transaction->charge) }} </span></li>
                            <li class="nav-item"><span class="text-secondary">@lang('Post Balance'):</span> <span class="me-3 fw-bold"> ${{ getAmount($transaction->post_balance) }} </span></li>
                            <li class="nav-item"><span class="text-secondary">@lang('Detail'):</span> <span class="me-3 fw-bold"> {{ __($transaction->details) }} </span></li>
                        </ul>
                    </div>
                </div>
                <hr>
            @empty
                <ul class="nav">
                    <li class="nav-item"><span class="text-secondary">{{ __($emptyMessage) }}</span></li>
                </ul>
            @endforelse
        </div>
    </div>
</div>
