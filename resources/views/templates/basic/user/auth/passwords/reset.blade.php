@extends($activeTemplate . 'layouts.wiserlance.layout')

@section('content')
<div class="col-12 p-md-3">
    <!--Breadcrumbs-->
    <p class="text-bc fs-14">
        <a class="text-3gray" href="{{ route('home') }}">
            <i class="fa-solid fa-house fs-14"></i>
        </a>
        <i class="fa-solid fa-chevron-right fs-12"></i> 
        <a class="text-3gray" href="{{ route('user.login') }}">@lang('Login')</a>
        <i class="fa-solid fa-chevron-right fs-12"></i> 
        @lang('Reset Password')
    </p> <!--Breadcrumbs End-->
    
    <center>
        @if (Session::has('feedback'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    &times;
                </button>
                {!! session('feedback') !!}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    &times;
                </button>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card bg-white shadow p-4 px-md-5" style="max-width:600px;">
            <p class="px-3 fs-12 text-end">
                @lang('Remember your password?') 
                <a class="links-c ms-1" href="{{ route('user.login') }}">@lang('Login Here')</a>
            </p>
            
            <h3 class="mt-4 fw-bold text-dgreen">@lang('Reset Password')</h3>
            <p class="text-muted fs-14">@lang('Enter your new password below')</p>
            
            <form class="account-form text-start mt-3" method="POST" action="{{ route('user.password.update') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-3 hover-input-popup">
                    <label for="password" class="form-label">@lang('New Password')*</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="@lang('Enter new password')">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                    @if($general->secure_password)
                        <div class="input-popup">
                            <p class="error lower">@lang('1 small letter minimum')</p>
                            <p class="error capital">@lang('1 capital letter minimum')</p>
                            <p class="error number">@lang('1 number minimum')</p>
                            <p class="error special">@lang('1 special character minimum')</p>
                            <p class="error minimum">@lang('6 character password')</p>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">@lang('Confirm Password')*</label>
                    <input type="password" 
                           class="form-control" 
                           name="password_confirmation" 
                           id="password-confirm"
                           required 
                           placeholder="@lang('Confirm your password')">
                </div>
                
                <button type="submit" class="btn btn-success bg-green border-0 rounded-1 w-100">
                    <i class="fa-solid fa-key me-2"></i>@lang('Reset Password')
                </button>
            </form>
            
            <div class="mt-3 text-center">
                <p class="fs-14 mb-0">
                    <i class="fa-solid fa-shield-alt text-success me-1"></i>
                    @lang('Your password must be at least 6 characters long and contain at least one number, one uppercase, one lowercase and one special character.')
                </p>
            </div>
        </div>
    </center>
</div>
@endsection

@push('style')
<style>
    .hover-input-popup {
        position: relative;
    }
    
    .hover-input-popup:hover .input-popup {
        opacity: 1;
        visibility: visible;
    }
    
    .input-popup {
        position: absolute;
        bottom: 100%;
        left: 50%;
        width: 280px;
        background-color: #1e2746;
        color: #fff;
        padding: 15px;
        border-radius: 8px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        -ms-border-radius: 8px;
        -o-border-radius: 8px;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
        opacity: 0;
        visibility: hidden;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
        z-index: 999;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        font-size: 13px;
        margin-bottom: 10px;
    }
    
    .input-popup::after {
        position: absolute;
        content: '';
        bottom: -10px;
        left: 50%;
        margin-left: -8px;
        border-width: 10px 10px 0 10px;
        border-style: solid;
        border-color: #1e2746 transparent transparent transparent;
    }
    
    .input-popup p {
        padding-left: 25px;
        position: relative;
        margin-bottom: 5px;
    }
    
    .input-popup p:last-child {
        margin-bottom: 0;
    }
    
    .input-popup p::before {
        position: absolute;
        content: '';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        left: 0;
        top: 2px;
        line-height: 1;
        font-size: 14px;
    }
    
    .input-popup p.error {
        text-decoration: line-through;
        opacity: 0.7;
    }
    
    .input-popup p.error::before {
        content: "\f057";
        color: #ea5455;
    }
    
    .input-popup p.success::before {
        content: "\f058";
        color: #28c76f;
    }
    
    .input-popup p.success {
        text-decoration: none;
        opacity: 1;
    }
    
    /* Dark overlay for background image if needed */
    .bg-overlay-white {
        position: relative;
    }
    
    .bg-overlay-white::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
    }
</style>
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        @if($general->secure_password)
            $('input[name=password]').on('input', function() {
                secure_password($(this));
            });
        @endif
        
        // Optional: Add password visibility toggle
        $('.toggle-password').on('click', function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        
    })(jQuery);
</script>
@endpush