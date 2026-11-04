@php
    $content = getContent('footer.content', true);
    $footer_menu = getContent('footer.element', false);
    $socialIcons = getContent('social_icon.element', false);
@endphp
<section class="footer-section section--bg pt-60">
    <div class="container-fluid">
        <div class="footer-wrapper">
            <div class="footer-toggle"><span class="right-icon"></span><span class="title">@lang('Quick Links') </span></div>
            <div class="footer-bottom-area">
                <div class="row mb-30-none">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                        <div class="footer-widget">
                            <h3 class="title">@lang('About Us')</h3>
                            <p>{{__(@$content->data_values->heading)}}</p>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 mb-30">
                        <div class="footer-widget">
                            <h3 class="title">@lang('Our Info')</h3>
                            <ul class="footer-links">
                                <li><a href="{{route('user.login')}}">@lang('Sign In')</a></li>
                                <li><a href="{{route('user.register')}}">@lang('Join With Us')</a></li>
                                <li><a href="{{route('contact')}}">@lang('Contact Us')</a></li>
                                <li><a href="{{route('service')}}">@lang('Service')</a></li>
                                <li><a href="{{route('software')}}">@lang('Software')</a></li>
                                <li><a href="{{route('job')}}">@lang('Job')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 mb-30">
                        <div class="footer-widget">
                            <h3 class="title">@lang('Service Category')</h3>
                            <ul class="footer-links">
                                @foreach($categorys->take(6) as $category)
                                    <li><a href="{{route('service.category', [slug($category->name),$category->id])}}">{{__($category->name)}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 mb-30">
                        <div class="footer-widget">
                            <h3 class="title">@lang('Short Links')</h3>
                            <ul class="footer-links">
                                <li><a href="{{route('blog')}}">@lang('Blog')</a></li>
                                @foreach($footer_menu as $value)
                                    <li>
                                        <a href="{{route('footer.menu', [slug($value->data_values->menu), $value->id])}}">{{__($value->data_values->menu)}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-30">
                        <div class="footer-widget">
                            <h3 class="title">@lang('Subscribe News')</h3>
                            <p>{{__(@$content->data_values->subscribe_title)}}</p>
                            <form class="subscribe-form">
                                <input type="email" name="email" id="emailSub" placeholder="@lang('Email Address')..">
                                <button type="button" class="subscribe-btn"><i class="fas fa-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-area d-flex flex-wrap justify-content-between align-items-center">
                <div class="copyright">
                    <p>@lang('Copyright') © {{Carbon\Carbon::now()->format('Y')}} @lang('All Rights reserved')</p>
                </div>
                <div class="social-area">
                    <ul class="footer-social">
                        @foreach($socialIcons as $element)
                            <li>
                                <a href="{{@$element->data_values->url}}" target="__blank">@php echo $element->data_values->social_icon @endphp</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    'use strict';
    $(document).on('click','.subscribe-btn' , function(){
        var email = $("#emailSub").val();
        if(email){
            $.ajax({
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}",},
                url:"{{ route('subscribe') }}",
                method:"POST",
                data:{email:email},
                success:function(response)
                {
                    if(response.success) {
                        notify('success', response.success);
                    }else{
                        $.each(response, function (i, val) {
                            notify('error', val);
                        });
                    }
                }
            });
        }
        else{
            notify('error', "Please Input Your Email");
        }
    });
</script>
@endpush

