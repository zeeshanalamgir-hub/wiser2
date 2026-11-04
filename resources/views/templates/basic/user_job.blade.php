@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="all-sections pt-60">
    <div class="container-fluid p-max-sm-0">
        <div class="sections-wrapper d-flex flex-wrap justify-content-center">
            <article class="main-section">
                <div class="section-inner">
                    <div class="item-section item-overview-section">
                        <div class="container">
                            <div class="row justify-content-center mb-30-none">
                                <div class="col-xl-3 col-lg-3 mb-30">
                                    <div class="sidebar">
                                        <div class="widget mb-30">
                                            <div class="profile-widget">
                                                <div class="profile-widget-header">
                                                    <div class="profile-widget-thumb">
                                                        <img src="{{ userDefaultImage(imagePath()['profile']['user_bg']['path'].'/'. $user->bg_image,'background_image') }}" alt="item-banner">
                                                        @if($user->isOnline())
                                                            <span class="badge-offline bg--success">@lang('Online')</span>
                                                        @else
                                                            <span class="badge-offline bg--warning">@lang('Offline')</span>
                                                        @endif
                                                        @if(auth()->check())
                                                            @if(auth()->user()->following->find($user->id))
                                                                <a href="{{route('user.follow', $user->id)}}">
                                                                    <span class="badge-follow bg--success">
                                                                        @lang('Following')
                                                                    </span>
                                                                </a>
                                                            @else
                                                                <a href="{{route('user.follow', $user->id)}}">
                                                                    <span class="badge-follow bg--info">
                                                                        @lang('Follow')
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="{{route('user.follow', $user->id)}}">
                                                                <span class="badge-follow bg--info">
                                                                    @lang('Follow')
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="profile-widget-author">
                                                        <div class="thumb">
                                                            <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $user->image,'profile_image') }}" alt="{{__($user->username)}}">
                                                        </div>
                                                        <div class="content">
                                                            <h4 class="name">
                                                                <a href="{{route('profile', $user->username)}}">{{__($user->username)}}</a>
                                                            </h4>
                                                            <span class="designation">{{@$user->designation}}</span>
                                                            <div class="ratings">
                                                                <span><i class="las la-star text--warning"></i> {{getAmount($reviewAvg, 2)}} ({{$reviewCount}} @lang('reviews'))</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="profile-list-area">
                                                    <ul class="details-list">
                                                        <li><span>@lang('Total Service')</span> <span>{{$user->services->count()}}</span></li>
                                                        <li><span>@lang('Work Complete')</span> <span>{{$workCompleteCount}}</span></li>
                                                        <li><span>@lang('Total Software')</span> <span>{{$user->softwares->count()}}</span></li>
                                                        <li><span>@lang('Work Pending')</span> <span>{{$workPendingCount}}</span></li>
                                                        <li><span>@lang('Total Job')</span> <span>{{$user->jobs->count()}}</span></li>
                                                        <li><span>@lang('Country')</span> <span>{{@$user->address->country}}</span></li>
                                                        <li><span>@lang('Member since')</span> <span>{{showDateTime($user->created_at, 'd M Y')}}</span></li>
                                                        <li><span>@lang('Verified User')</span> 
                                                            @if($user->status == 1)
                                                                <span class="text--success">@lang('Yes')</span>
                                                            @else
                                                                <span class="text--success">@lang('No')</span>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-9 col-lg-9 mb-30">
                                    <div class="item-details-area">
                                        <div class="product-tab">
                                                <div class="tab-pane fade show active" id="service" role="tabpanel" aria-labelledby="service-tab">
                                                    <div class="item-card-wrapper border-0 p-0 grid-view">
                                                     @foreach($userJobs as $job)
                                                        <div class="item-card">
                                                            <div class="item-card-thumb">
                                                                <img src="{{getImage('assets/images/job/'.$job->image,'590x300') }}" alt="@lang('Job Image')">
                                                            </div>
                                                            <div class="item-card-content">
                                                                <div class="item-card-content-top">
                                                                    <div class="left">
                                                                        <div class="author-thumb">
                                                                            <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $job->user->image,'profile_image') }}" alt="@lang('author')">
                                                                        </div>
                                                                        <div class="author-content">
                                                                            <h5 class="name"><a href="{{route('profile', $job->user->username)}}">{{$job->user->username}}</a> <span class="level-text">{{__(@$job->user->rank->level)}}</span></h5>
                                                                        </div>
                                                                    </div>
                                                                    <div class="right">
                                                                        <div class="item-amount">{{$general->cur_sym}}{{showAmount($job->amount)}}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-tags order-3">
                                                                    @foreach($job->skill as $skill)
                                                                        <a href="javascript:void(0)">{{__($skill)}}</a>
                                                                    @endforeach
                                                                </div>
                                                                <h3 class="item-card-title"><a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}">{{__($job->title)}}</a></h3>
                                                            </div>
                                                            <div class="item-card-footer">
                                                                <div class="left">
                                                                    <a href="javascript:void(0)" class="btn--base active date-btn">{{__($job->delivery_time)}} @lang('Days')</a>
                                                                    <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" class="btn--base bid-btn">@lang('Total Bids')({{$job->jobBiding->count()}})</a>
                                                                </div>
                                                                <div class="right">
                                                                    <div class="order-btn">
                                                                        <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" class="btn--base"><i class="las la-shopping-cart"></i> @lang('Bid Now')</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                {{$userJobs->links()}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>
@include($activeTemplate.'partials.end_ad')
@endsection

