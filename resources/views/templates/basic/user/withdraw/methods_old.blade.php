@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.seller_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="deposit-area">
                        <div class="row justify-content-center mb-30-none">
                            @foreach($withdrawMethod as $data)
                                <div class="col-lg-4 col-md-4 mb-4">
                                    <div class="deposit-item">
                                        <div class="deposit-item-header section--bg text-white text-center">
                                            <span class="title"> {{__($data->name)}}</span>
                                        </div>

                                        <div class="card-body card-body-withdraw">
                                            <img src="{{getImage(imagePath()['withdraw']['method']['path'].'/'. $data->image,imagePath()['withdraw']['method']['size'])}}" class="card-img-top" alt="{{__($data->name)}}" class="w-100">
                                            <ul class="list-group text-center">
                                                <li class="list-group-item">@lang('Limit')
                                                    : {{getAmount($data->min_limit)}}
                                                    - {{getAmount($data->max_limit)}} {{__($general->cur_text)}}</li>

                                                <li class="list-group-item"> @lang('Charge')
                                                    - {{getAmount($data->fixed_charge)}} {{__($general->cur_text)}}
                                                    + {{getAmount($data->percent_charge)}}%
                                                </li>
                                                <li class="list-group-item">@lang('Processing Time')
                                                    - {{$data->delay}}</li>
                                            </ul>
                                        </div>
                                        <div class="card-footer">
                                            <div class="deposit-item-footer bg--base">
                                                <div class="deposit-btn text-center">
                                                    <a href="javascript:void(0)"  data-id="{{$data->id}}"
                                                       data-resource="{{$data}}"
                                                       data-min_amount="{{getAmount($data->min_limit)}}"
                                                       data-max_amount="{{getAmount($data->max_limit)}}"
                                                       data-fix_charge="{{getAmount($data->fixed_charge)}}"
                                                       data-percent_charge="{{getAmount($data->percent_charge)}}"
                                                       data-base_symbol="{{__($general->cur_text)}}"
                                                       class="btn btn--base text-white btn-block btn-icon icon-left withdraw" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                                                        @lang('Withdraw Now')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title method-name" id="ModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" action="{{route('user.withdraw.money')}}">
                @csrf
                <div class="modal-body">
                    <p class="text-danger withdrawLimit"></p>
                    <p class="text-danger withdrawCharge"></p>

                    <input type="hidden" name="currency"  class="edit-currency form-control">
                    <input type="hidden" name="method_code" class="edit-method-code  form-control">

                    <div class="form-group">
                        <h5>@lang('Enter Amount')</h5>
                        <div class="input-group-append">
                            <input type="text" name="amount" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"  value="{{old('amount')}}" class="form-control" placeholder="@lang("Enter Amount")" required="">
                            <span class="input-group-text">{{__($general->cur_text)}}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--base btn-rounded text-white">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.withdraw').on('click', function () {
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount'); 
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var withdrawLimit = `@lang('Withdraw Limit'): ${minAmount} - ${maxAmount}  {{__($general->cur_text)}}`;
                $('.withdrawLimit').text(withdrawLimit);
                var withdrawCharge = `@lang('Charge'): ${fixCharge} {{__($general->cur_text)}} ${(0 < percentCharge) ? ' + ' + percentCharge + ' %' : ''}`
                $('.withdrawCharge').text(withdrawCharge);
                $('.method-name').text(`@lang('Withdraw Via') ${result.name}`);
                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.id);
            });
        })(jQuery);
    </script>

@endpush

