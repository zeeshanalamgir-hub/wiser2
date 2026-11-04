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
                                                <th>@lang('Title')</th>
                                                <th>@lang('Price')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($favoriteSoftwares as $favoriteSoftware)
                                                <tr>
                                                    <td data-label="@lang('Title')" class="text-start">
                                                        <div class="author-info">
                                                            <div class="thumb">
                                                                <img src="{{getImage('assets/images/software/'.$favoriteSoftware->software->image,'590x300') }}" alt="@lang('software Image')">
                                                            </div>
                                                            <div class="content">{{__(str_limit($favoriteSoftware->software->title, 40))}}</div>
                                                        </div>
                                                    </td>
                                                    <td data-label="@lang('Price')">{{showAmount($favoriteSoftware->software->amount)}} {{$general->cur_text}}</td>
                                                    <td data-label="Action">
                                                        <a href="{{route('software.details', [slug($favoriteSoftware->software->title), $favoriteSoftware->software_id])}}" class="btn btn--primary text-white"><i class="las la-desktop"></i></a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{$favoriteSoftwares->links()}}
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
