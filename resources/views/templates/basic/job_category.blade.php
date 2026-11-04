@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="all-sections pt-60">
    <div class="container-fluid p-max-sm-0">
        <div class="sections-wrapper d-flex flex-wrap justify-content-center">
            <article class="main-section">
                <div class="section-inner">
                    <div class="item-section">
                        <div class="container">
                            <div class="item-category-area">
                                <div class="category-slider">
                                    <div class="swiper-wrapper">
                                        @foreach($subCategorys as $subCategory)
                                            <div class="swiper-slide">
                                                <div class="category-item-box">
                                                    <a href="{{route('job.sub.category', [slug($subCategory->name), $subCategory->id])}}">
                                                        <img src="{{getImage(imagePath()['subcategory']['path'].'/'. $subCategory->image,imagePath()['subcategory']['size'])}}" alt="@lang('sub category')">
                                                        <div class="category-item-content">
                                                            <h4 class="title text-white">{{__($subCategory->name)}}</h4>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="slider-prev">
                                        <i class="las la-angle-left"></i>
                                    </div>
                                    <div class="slider-next">
                                        <i class="las la-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="item-top-area d-flex flex-wrap justify-content-between align-items-center">
                                <div class="item-wrapper d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="item-wrapper-left d-flex flex-wrap align-items-center">
                                        <div class="item-value">
                                            <span>@lang('Showing page'):&nbsp <span class="result">{{$jobs->firstItem()}} @lang('of') {{$jobs->lastPage()}}</span></span>
                                        </div>
                                    </div>
                                    <ul class="view-btn-list">
                                        <li class="active"><button type="button" class="grid-view-btn list-btn"><i class="lab la-buromobelexperte"></i></button></li>
                                        <li><button type="button" class="list-view-btn list-btn"><i class="las la-list"></i></button></li>
                                    </ul>
                                </div>
                                <div class="item-wrapper-right">
                                    <form class="search-from" action="{{route('job.search')}}" method="GET">
                                        <input type="search" name="search" value="{{@$search}}" class="form-control" placeholder="@lang('Search here')...">
                                        <button type="submit"><i class="las la-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="item-bottom-area">
                                <div class="row justify-content-center mb-30-none">
                                    <div class="col-xl-9 col-lg-9 mb-30">
                                        <div class="item-card-wrapper grid-view">
                                        @forelse($jobs as $job)
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
                                                <div class="item-card-footer mb-10-none">
                                                    <div class="left mb-10">
                                                        <a href="javascript:void(0)" class="btn--base active date-btn">{{$job->delivery_time}} @lang('Days')</a>
                                                        <a href="javascript:void(0)" class="btn--base bid-btn">@lang('Total Bids')({{$job->jobBiding->count()}})</a>
                                                    </div>
                                                    <div class="right mb-10">
                                                        <div class="order-btn">
                                                            <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" class="btn--base"><i class="las la-shopping-cart"></i> @lang('Bid Now')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="empty-message-box bg--gray">
                                                <div class="icon"><i class="las la-frown"></i></div>
                                                <p class="caption">{{$emptyMessage}}</p>
                                            </div>
                                        @endforelse
                                        </div>
                                        <nav>
                                            {{$jobs->links()}}
                                        </nav>
                                    </div>
                                    @include($activeTemplate.'partials.job_category_filter')
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