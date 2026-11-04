@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.buyer_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="table-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="table-area">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Software')</th>
                                                <th>@lang('Order Number')</th>
                                                <th>@lang('Seller')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Discount')</th>
                                                <th>@lang('Software')</th>
                                                <th>@lang('Document')</th>
                                                <th>@lang('Status')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($softwarePurchases as $softwarePurchase)
                                                <tr>
                                                    <td data-label="@lang('Service')" class="text-start">
                                                        <div class="author-info">
                                                            <div class="thumb">
                                                                <img src="{{getImage('assets/images/software/'.$softwarePurchase->software->image,'590x300') }}" alt="@lang('Service Image')">
                                                            </div>
                                                            <div class="content"><a href="{{route('software.details', [slug($softwarePurchase->software->title), $softwarePurchase->software->id])}}">{{__(str_limit($softwarePurchase->software->title, 10))}}</a></div>
                                                        </div>
                                                    </td>
                                                    <td data-label="@lang('Order Number')">{{__($softwarePurchase->order_number)}}</td>
                                                    <td data-label="@lang('Seller')">
                                                    	 <span class="font-weight-bold">{{__(@$softwarePurchase->software->user->fullname)}}</span>
					                                    <br>
					                                    <span class="text--info">
					                                    <a href="{{route('profile',@$softwarePurchase->software->user->username)}}"><span>@</span>{{ $softwarePurchase->software->user->username }}</a>
					                                    </span>
                                                    </td>
                                                    <td data-label="@lang('Amount')">{{showAmount($softwarePurchase->amount)}} {{__($general->cur_text)}}</td>

                                                     <td data-label="@lang('Discount')">
                                                        @if($softwarePurchase->discount == 0)
                                                            <span>@lang('N/A')</span>
                                                        @else
                                                            {{showAmount($softwarePurchase->discount)}} {{__($general->cur_text)}}
                                                        @endif
                                                        </td>

                                                     <td data-label="@lang('Software')">
                                                         <a href="{{route('user.buyer.software.file.download', encrypt($softwarePurchase->software->id))}}" class="btn btn--primary text-white"><i class="las la-arrow-down"></i></a>
                                                     </td>

                                                     <td data-label="@lang('Document')">
                                                         <a href="{{route('user.buyer.software.document.download', encrypt($softwarePurchase->software->id))}}" class="btn btn--primary text-white"><i class="las la-arrow-down"></i></a>
                                                     </td>
                                                    
                                                     <td data-label="@lang('Status')">
			                                            @if($softwarePurchase->status == 3)
			                                                <span class="badge badge--success">@lang('Paid')</span>
                                                            <br>
                                                            <span>{{diffforhumans($softwarePurchase->updated_at)}}</span>
			                                            @else
			                                                <span class="badge badge--danger">@lang('N/A')</span>
			                                            @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$softwarePurchases->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
