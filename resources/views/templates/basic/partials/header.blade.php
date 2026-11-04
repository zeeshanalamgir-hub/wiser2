<header class="header-section">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container-fluid">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="{{__($general->sitename)}}"></a>
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <button type="button" class="short-menu-open-btn"><i class="fas fa-align-center"></i></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ms-auto me-auto">
                                <li><a href="{{route('home')}}" @if(request()->routeIs('home'))class="active" @endif>@lang('Home')</a></li>
                                <li><a href="{{route('service')}}" @if(request()->routeIs('service'))class="active" @endif>@lang('Service')</a></li>
                                <li><a href="{{route('software')}}" @if(request()->routeIs('software'))class="active" @endif>@lang('Software')</a></li>
                                <li><a href="{{route('job')}}" @if(request()->routeIs('job'))class="active" @endif>@lang('Job')</a></li>
                                <li><a href="{{route('blog')}}" @if(request()->routeIs('blog') || request()->routeIs('blog.details'))class="active" @endif>@lang('Blog')</a></li>
                                <li><a href="{{route('contact')}}" @if(request()->routeIs('contact'))class="active" @endif>@lang('Contact')</a></li>
                            </ul>
                            <div class="language-select-area">
                                <select class="language-select langSel">
                                    @foreach($language as $item)
                                    <option value="{{$item->code}}" @if(session('lang')==$item->code) selected @endif>{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="header-action">
                                @guest
                                <a href="{{route('user.login')}}" class="btn--base active">@lang('Sign In')</a>
                                <a href="{{route('user.register')}}" class="btn--base">@lang('Sign Up')</a>
                                @endguest

                                @auth
                                <a href="{{route('user.home')}}" class="btn--base">{{Auth::user()->firstname." ".Auth::user()->lastname }}</a>
                                @endauth
                            </div>

                        </div>
                    </nav>
                </div>
            </div>
        </div>
        {{-- <div class="header-short-menu">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="short-menu">
                            <li class="short-menu-close-btn-area"> <button type="button" class="short-menu-close-btn">@lang('Close')</button></li>
                            @foreach($categorys->take(8) as $category)
                                <li><a href="{{route('service.category', [slug($category->name),$category->id])}}">{{__($category->name)}}</a></li>
        @endforeach
        </ul>
    </div>
    </div>
    </div>
    </div> --}}
    </div>
</header>