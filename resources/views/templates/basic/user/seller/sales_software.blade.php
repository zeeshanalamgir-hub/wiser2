@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.seller_sidebar')
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
                                                <th>@lang('Buyer')</th>
                                                <th>@lang('Amount')</th>
                                                <th>@lang('Discount')</th>
                                                <th>@lang('Status')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($salesSoftwares as $booking)
                                                <tr>
                                                    <td data-label="@lang('Software')" class="text-start">
                                                        <div class="author-info">
                                                            <div class="thumb">
                                                                <img src="{{getImage('assets/images/software/'.$booking->software->image,'590x300') }}" alt="@lang('Software Image')">
                                                            </div>
                                                            <div class="content"><a href="{{route('software.details', [slug($booking->software->title), $booking->software->id])}}">{{__(str_limit($booking->software->title, 20))}}</a></div>
                                                        </div>
                                                    </td>
                                                    <td data-label="@lang('Order Number')">{{__($booking->order_number)}}</td>
                                                    <td data-label="@lang('Buyer')">
                                                    	 <span class="font-weight-bold">{{__($booking->user->fullname)}}</span>
					                                    <br>
					                                    <span class="text--info">
					                                    <a href="{{route('profile',@$booking->user->username)}}"><span>@</span>{{$booking->user->username}}</a>
					                                    </span>
                                                    </td>
                                                    <td data-label="@lang('Amount')">{{showAmount($booking->amount)}} {{__($general->cur_text)}}</td>
                                                    <td data-label="@lang('Discount')">
                                                        @if($booking->discount == 0)
                                                            <span>@lang('N/A')</span>
                                                        @else
                                                            {{showAmount($booking->discount)}} {{__($general->cur_text)}}
                                                        @endif
                                                    </td>

                                                    <td data-label="@lang('Status')">
                                                        @if($booking->status == 3)
                                                            <span class="badge badge--success">@lang('Paid')</span>
                                                             <br>
                                                            <span>{{diffforhumans($booking->updated_at)}}</span>
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
                                    {{$salesSoftwares->links()}}
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


