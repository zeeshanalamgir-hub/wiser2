@extends($activeTemplate.'layouts.master')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @if(request()->routeIs('user.buyer.transactions'))
                    @include($activeTemplate . 'partials.buyer_sidebar')
                @else
                    @include($activeTemplate . 'partials.seller_sidebar')
                @endif


                <div class="col-xl-9 col-lg-12 mb-30">
                          <div class="col-xl-4 col-lg-4 form-group">
                                            <select id="getBidsReport" class="form-control bg--gray">
                                                    <option selected="" disabled="">@lang('Select Log')</option>

                                                    <option value="0">Transaction Log</option>
                                                    <option value="1">Bids Log</option>

                                            </select>
                                        </div>
                                        <br>
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="table-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="table-area" id="tra">
                                    <table class="custom-table">
                                        <thead>
                                             <tr>
                                                <th>@lang('Date')</th>
                                                <th>@lang('TRX')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Charge')</th>
                                                <th>@lang('Post Balance')</th>
                                                <th>@lang('Detail')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($transactions as $transaction)
                                                <tr>
                                                    <td data-label="@lang('Date')">
                                                        {{showDateTime($transaction->created_at)}}
                                                        <br>
                                                        {{diffforhumans($transaction->created_at)}}

                                                    </td>
                                                    <td data-label="@lang('TRX')">{{$transaction->trx}}</td>
                                                    <td data-label="@lang('Amount')">
                                                        <strong
                                                            @if($transaction->trx_type == '+') class="text--success" @else class="text--danger" @endif>
                                                            {{($transaction->trx_type == '+') ? '+':'-'}}
                                                            {{getAmount($transaction->amount)}} {{$general->cur_text}}
                                                        </strong>
                                                    </td>
                                                    <td data-label="@lang('Charge')">{{getAmount($transaction->charge)}} {{$general->cur_text}}</td>
                                                    <td data-label="@lang('Post Balance')">{{getAmount($transaction->post_balance)}} {{$general->cur_text}}</td>
                                                    <td data-label="@lang('Detail')">{{__($transaction->details)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$transactions->links()}}
                                </div>
                                 <div class="table-area" id="bids">
                                    <table class="custom-table">
                                        <thead>
                                             <tr>
                                                <th>@lang('Date')</th>
                                                <th>@lang('Bids')</th>
                                                <th>@lang('Post Balance')</th>
                                                <th>@lang('Detail')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($bidsLog as $bids)
                                                <tr>
                                                    <td data-label="@lang('Date')">
                                                        {{showDateTime($bids->created_at)}}
                                                        <br>
                                                        {{diffforhumans($bids->created_at)}}

                                                    </td>

                                                    <td data-label="@lang('Amount')">
                                                        <strong
                                                            @if($bids->tr_op == '+') class="text--success" @else class="text--danger" @endif>
                                                            {{($bids->tr_op == '+') ? '+':'-'}}
                                                            {{$bids->bids}}
                                                        </strong>
                                                    </td>
                                                    <td data-label="@lang('Post Balance')">{{$bids->post_balance}} </td>
                                                    <td data-label="@lang('Detail')">{{$bids->details}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$bidsLog->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function(){

 $('#tra').show();
  $('#bids').hide();
    $('#getBidsReport').change(function(){
        if($('#getBidsReport').val() == 0)
        {
            $('#tra').show();
             $('#bids').hide();
        }else
        {
            $('#bids').show();
            $('#tra').hide();
        }


    });
});
</script>
@endsection
