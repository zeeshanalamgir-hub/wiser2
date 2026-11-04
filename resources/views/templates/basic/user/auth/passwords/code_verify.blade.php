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
        @lang('Code Verify')
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
                @lang('You don\'t have an account?') 
                <a class="links-c ms-1" href="{{ route('user.signupform') }}">@lang('Proceed to sign up')</a>
            </p>
            
            <h3 class="mt-4 fw-bold text-dgreen">@lang('Verify Code')</h3>
            
            <form class="account-form text-start mt-3" method="POST" action="{{ route('user.password.verify.code') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="mb-3">
                    <label for="code" class="form-label">@lang('Verification Code')</label>
                    <input type="text" 
                           class="form-control @error('code') is-invalid @enderror" 
                           name="code" 
                           id="code"
                           value="{{ old('code') }}" 
                           required 
                           autofocus="off"
                           placeholder="@lang('Enter verification code')">
                    @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3 text-end">
                    <a href="{{ route('user.login') }}" class="links-c">
                        <i class="fa-solid fa-arrow-left"></i> @lang('Back to Login')
                    </a>
                </div>
                
                <button type="submit" class="btn btn-success bg-green border-0 rounded-1 w-100">
                    @lang('Verify Code')
                </button>
            </form>
            
            <div class="mt-3 text-center">
                <p class="fs-14 mb-0">
                    @lang('Please check including your Junk/Spam Folder.')<br>
                    @lang('If not found, you can') 
                    <a href="{{ route('user.password.request') }}" class="links-c">
                        @lang('Resend code')
                    </a>
                </p>
            </div>
        </div>
    </center>
</div>
@endsection

@push('script')
<script>
(function($){
    "use strict";
    
    $('#code').on('input change', function () {
        var xx = document.getElementById('code').value;
        $(this).val(function (index, value) {
            value = value.substr(0,7);
            return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
        });
    });
    
    // Optional: Add countdown timer for resend code if you want
    // You can implement this based on your needs
    
})(jQuery)
</script>
@endpush