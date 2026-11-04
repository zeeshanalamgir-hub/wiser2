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
                                                    <div class="widget-btn mt-20">
                                                        @auth
                                                            @if($conversion)
                                                                <a href="{{route('user.conversation.inbox')}}" class="btn--base w-100">@lang('Inbox')</a>
                                                            @else
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#depoModal" class="btn--base w-100">@lang('Contact Now')</a>
                                                            @endif
                                                        @endauth
                                                        @guest
                                                            <a href="{{route('user.login')}}" class="btn--base w-100">@lang('Contact Now')</a>
                                                        @endguest
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget custom-widget mb-30">
                                            <h3 class="widget-title">@lang('About ME')</h3>
                                            <p>{{__(@$user->about_me)}}</p>
                                        </div>
                                        <div class="widget custom-widget">
                                            <div class="follow-tab">
                                                <nav>
                                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                        <button class="nav-link active" id="followers-tab" data-bs-toggle="tab" data-bs-target="#followers"
                                                            type="button" role="tab" aria-controls="followers" aria-selected="true">@lang('Followers') ({{$user->followers()->count()}})</button>
                                                        <button class="nav-link" id="following-tab" data-bs-toggle="tab" data-bs-target="#following" type="button"
                                                            role="tab" aria-controls="following" aria-selected="false">@lang('Following') ({{$user->following()->count()}})</button>
                                                    </div>
                                                </nav>
                                                <div class="tab-content" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="followers" role="tabpanel" aria-labelledby="followers-tab">
                                                        @forelse($user->followers as $value)
                                                            <div class="follow-widget-area">
                                                                <div class="follow-widget-author">
                                                                    <div class="thumb">
                                                                        <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $value->image,'profile_image') }}" alt="@lang('client')">
                                                                    </div>
                                                                    <div class="content">
                                                                        <h5 class="name"><a href="{{route('profile', $value->username)}}">{{$value->username}}</a></h5>
                                                                        <span class="level">{{@$value->rank->level}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="follow-btn">
                                                                    <a href="{{route('user.follow', $user->id)}}" class="btn--base active"><i class="fas fa-user-plus"></i> @lang('Unfollow')</a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="tab-pane fade" id="following" role="tabpanel" aria-labelledby="following-tab">
                                                    @forelse($user->following as $value)
                                                        <div class="follow-widget-area">
                                                            <div class="follow-widget-author">
                                                                <div class="thumb">
                                                                    <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $value->image,'profile_image') }}" alt="@lang('client')">
                                                                </div>
                                                                <div class="content">
                                                                    <h5 class="name"><a href="{{route('profile', $value->username)}}">{{$value->username}}</a></h5>
                                                                    <span class="level">{{@$value->rank->level}}</span>
                                                                </div>
                                                            </div>
                                                            <div class="follow-btn">
                                                                <a href="{{route('user.follow', $user->id)}}" class="btn--base active"><i class="fas fa-user-plus"></i> @lang('Follow')</a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-9 col-lg-9 mb-30">
                                    <div class="item-details-area">
                                        <div class="product-tab">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <button class="nav-link active" id="service-tab" data-bs-toggle="tab" data-bs-target="#service" type="button"
                                                        role="tab" aria-controls="service" aria-selected="true">@lang('Services')</button>
                                                    <button class="nav-link" id="software-tab" data-bs-toggle="tab" data-bs-target="#software" type="button"
                                                        role="tab" aria-controls="software" aria-selected="false">@lang('Software')</button>
                                                    <button class="nav-link" id="job-tab" data-bs-toggle="tab" data-bs-target="#job" type="button"
                                                        role="tab" aria-controls="job" aria-selected="false">@lang('Job')</button>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="service" role="tabpanel" aria-labelledby="service-tab">
                                                    <div class="item-card-wrapper border-0 p-0 grid-view mt-30">
                                                    @foreach($userServices as $service)
                                                        <div class="item-card">
                                                            <div class="item-card-thumb">
                                                                <img src="{{getImage('assets/images/service/'.$service->image, imagePath()['service']['size']) }}" alt="@lang('Service Image')">
                                                                @if($service->featured == 1)
                                                                    <div class="item-level">@lang('Featured')</div>
                                                                @endif
                                                            </div>
                                                            <div class="item-card-content">
                                                                <div class="item-card-content-top">
                                                                    <div class="left">
                                                                        <div class="author-thumb">
                                                                            <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $service->user->image,'profile_image') }}" alt="{{__($service->user->username)}}">
                                                                        </div>
                                                                        <div class="author-content">
                                                                            <h5 class="name"><a href="{{route('profile', $service->user->username)}}">{{__($service->user->username)}}</a> <span class="level-text"> {{__(@$service->user->rank->level)}}</span></h5>
                                                                            <div class="ratings">
                                                                                <i class="fas fa-star"></i>
                                                                                <span class="rating me-2">{{__(getAmount($service->rating, 2))}}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="right">
                                                                        <div class="item-amount">{{__($general->cur_sym)}}{{__(showAmount($service->price))}}</div>
                                                                    </div>
                                                                </div>
                                                                <h3 class="item-card-title"><a href="{{route('service.details', [slug($service->title), encrypt($service->id)])}}">{{__($service->title)}}</a></h3>
                                                            </div>
                                                            <div class="item-card-footer">
                                                                <div class="left">

                                                                    <a href="javascript:void(0)" class="item-love me-2 loveHeartAction"  data-serviceid="{{$service->id}}"><i class="fas fa-heart"></i> <span class="give-love-amount">({{__($service->favorite)}})</span></a>
                                                                    
                                                                    <a href="javascript:void(0)" class="item-like"><i class="las la-thumbs-up"></i> ({{$service->likes}})</a>
                                                                </div>
                                                                <div class="right">
                                                                    <div class="order-btn">
                                                                        <a href="{{route('user.service.booking', [slug($service->title), encrypt($service->id)])}}" class="btn--base"><i class="las la-shopping-cart"></i> @lang('Order Now')</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="widget-btn text-center mt-4">
                                                    @if($userServices->total() > 6)
                                                        <a href="{{route('profile.service', $user->username)}}" class="btn--base showMoreService"  data-page="2" data-link="{{route('profile', $user->username)}}?page=">@lang('Show More')</a>
                                                    @endif
                                                </div>
                                            </div>
                                                <div class="tab-pane fade" id="software" role="tabpanel" aria-labelledby="software-tab">
                                                    <div class="item-card-wrapper border-0 p-0 grid-view mt-30">
                                                    @foreach($userSoftwares as $software)
                                                        <div class="item-card">
                                                            <div class="item-card-thumb">
                                                                <img src="{{getImage('assets/images/software/'.$software->image,'590x300') }}" alt="@lang('item-software')">
                                                            </div>
                                                            <div class="item-card-content">
                                                                <div class="item-card-content-top">
                                                                    <div class="left">
                                                                        <div class="author-thumb">
                                                                            <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $software->user->image,'profile_image') }}" alt="{{__($software->user->username)}}">
                                                                        </div>
                                                                        <div class="author-content">
                                                                            <h5 class="name"><a href="{{route('profile', $software->user->username)}}">{{__($software->user->username)}}</a> <span class="level-text">{{__(@$software->user->rank->level)}}</span></h5>
                                                                            <div class="ratings">
                                                                                <i class="fas fa-star"></i>
                                                                                <span class="rating me-2">{{showAmount($software->rating)}}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="right">
                                                                        <div class="item-amount">{{$general->cur_sym}}{{showAmount($software->amount)}}</div>
                                                                    </div>
                                                                </div>
                                                                <h3 class="item-card-title"><a href="{{route('software.details', [slug($software->title), encrypt($software->id)])}}">{{__($software->title)}}</a></h3>
                                                            </div>
                                                            <div class="item-card-footer">
                                                                <div class="left">
                                                                    <a href="javascript:void(0)" class="item-love me-2 loveHeartActionSoftware" data-softwareid="{{$software->id}}"><i class="fas fa-heart"></i> <span class="give-love-amount">({{__($software->favorite)}})</span></a>
                                                                    <a href="javascript:void(0)" class="item-like"><i class="las la-thumbs-up"></i> ({{__($software->likes)}})</a>
                                                                    <a href="{{route('user.software.buy',[slug($software->title), encrypt($software->id)])}}" class="btn--base active buy-btn"><i class="las la-shopping-cart"></i> @lang('Buy Now')</a>
                                                                </div>
                                                                <div class="right">
                                                                    <div class="order-btn">
                                                                        <a href="{{$software->demo_url}}" target="__blank" class="btn--base"><i class="las la-desktop"></i> @lang('Preview')</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    </div>

                                                <div class="widget-btn text-center mt-4">
                                                    @if($userSoftwares->total() > 6)
                                                        <a href="{{route('profile.software', $user->username)}}" class="btn--base">@lang('Show More')</a>
                                                    @endif
                                                </div>

                                                </div>
                                                <div class="tab-pane fade" id="job" role="tabpanel" aria-labelledby="job-tab">
                                                    <div class="item-card-wrapper border-0 p-0 grid-view mt-30" id="viewMoreJob">
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

                                                    <div class="widget-btn text-center mt-4">
                                                        @if($userJobs->total() > 1)
                                                            <a href="{{route('profile.job', $user->username)}}" class="btn--base">@lang('Show More')</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-reviews-content mt-40">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="section-header">
                                                        <h2 class="section-title">@lang('Seller Reviews')</h2>
                                                    </div>
                                                    <ul class="comment-list" id="reviewShow">
                                                    @foreach($userReviews as $review)
                                                        <li class="comment-container d-flex flex-wrap">
                                                            <div class="comment-avatar">
                                                                <img src="{{ userDefaultImage(imagePath()['profile']['user']['path'].'/'. $review->user->image,'profile_image') }}" alt="@lang('client')">
                                                            </div>
                                                            <div class="comment-box">
                                                                <div class="ratings-container">
                                                                    <div class="product-ratings">
                                                                        @if(intval($review->rating) == 1)
                                                                            <i class="las la-star"></i>
                                                                        @elseif(intval($review->rating) == 2)
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                        @elseif(intval($review->rating) == 3)
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                        @elseif(intval($review->rating) == 4)
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                        @elseif(intval($review->rating) == 5)
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                            <i class="las la-star"></i>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="comment-info mb-1">
                                                                    <h4 class="avatar-name">{{$review->user->fullname}}</h4> - <span class="comment-date">{{showDateTime($review->created_at, 'd M Y')}}</span>
                                                                </div>
                                                                <div class="comment-text">
                                                                    <p>{{__($review->review)}}</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                    </ul>
                                                    @if($userReviews->total() > 6)
                                                        <div class="view-more-btn text-center mt-4">
                                                            <a href="javascript:void(0)" class="btn--base reviewMore" data-page="2" data-link="{{route('profile', $user->username)}}?page="> @lang('View More')</a>
                                                        </div>
                                                    @endif
                                                </div>
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
<div class="modal fade" id="depoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">@lang('Start new conversation')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('user.conversation.store')}}">
                    @csrf
                    <input type="hidden" name="recevier_id" value="{{$user->id}}">

                        <div class="form-group">
                            <label for="subject" class="font-weight-bold">@lang('Subject')</label>
                            <input type="text" class="form-control" name="subject" placeholder="@lang('Enter Subject')" maxlength="255" required>
                        </div>
                         
                        <div class="form-group">
                            <label for="message" class="font-weight-bold">@lang('Message')</label>
                            <textarea rows="8" class="form-control" name="message" maxlength="500" placeholder="@lang('Enter Message')" required></textarea>
                        </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--base" style="width:100%;">@lang('Submit')</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
<script>
    'use strict';
    $('.reviewMore').on('click',function(){
        var link = $(this).data('link');
        var page = $(this).data('page');
        var href = link + page;
        var reviewCount = {{$reviewCount}};
        $.get(href, function(response){
            var html = $(response).find("#reviewShow").html();
            $("#reviewShow").append(html);
            var loadMoreCount = 6 * page;
            if(loadMoreCount > reviewCount){
                $('.reviewMore').hide()
            }
        });
        $(this).data('page', (parseInt(page) +1));
    });
</script>
@endpush