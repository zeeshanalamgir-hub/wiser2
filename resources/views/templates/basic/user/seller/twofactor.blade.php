@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.seller_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="card-area">
                        <div class="row justify-content-center">
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="card custom--card">
                                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                        <h4 class="card-title mb-0">
                                            @lang('Two Factor Authenticator')
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        @if(Auth::user()->ts)
                                            <div class="form-group mx-auto text-center">
                                                <a href="javascript:void(0)" class="btn btn--danger text-white" data-bs-toggle="modal" data-bs-target="#disableModal">@lang('Disable Two Factor Authenticator')</a>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="key" value="{{$secret}}" class="form-control" id="referralURL"
                                                        readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text copytext" id="copyBoard" onclick="myFunction()"> <i
                                                                class="fa fa-copy"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mx-auto text-center">
                                                <img class="mx-auto" src="{{$qrCodeUrl}}">
                                            </div>
                                            <div class="form-group mx-auto text-center">
                                                <a href="javascript:void(0)" class="btn--base" data-bs-toggle="modal" data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator')</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="card custom--card">
                                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                        <h4 class="card-title mb-0">
                                            @lang('Google Authenticator')
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="text-uppercase mb-2">@lang('Download Google Authenticator App')</h4>
                                        <p>@lang('Google Authenticator is a product based authenticator by Google that executes two-venture confirmation administrations for verifying clients of any programming applications').</p>
                                        <hr />
                                        <a class="btn--base"
                                            href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('DOWNLOAD APP')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="enableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">@lang('Verify Your Otp')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('user.twofactor.enable')}}">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="key" value="{{$secret}}">
                        <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--base" style="width:100%;">@lang('Verify')</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">@lang('Verify Your Otp')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('user.twofactor.disable')}}">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="key" value="{{$secret}}">
                        <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--base" style="width:100%;">@lang('Verify')</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-rounded text-white" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        (function($){
            "use strict";
            $('.copytext').on('click',function(){
                var copyText = document.getElementById("referralURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
            });
        })(jQuery);
    </script>
@endpush


