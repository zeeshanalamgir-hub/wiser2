@extends($activeTemplate . 'layouts.wiserlance.layout')
@section('content')

<div class="col-12 p-md-3">
    <!--Breadcrumbs-->
    <p class=" text-bc fs-14"><a class="text-3gray" href="{{ url('/') }}">
        <i class="fa-solid fa-house fs-14"></i></a>
    <i class="fa-solid fa-chevron-right fs-12"></i>  Forgot Password
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
        <div class="card bg-white shadow p-4 px-md-5" style="max-width:600px;">
            <p class="px-3 fs-12 text-end">You don't have an account? <a class="links-c ms-1" href="{{ route('user.signupform') }}">Proceed to sign up</a></p>
            <h3 class="mt-4 fw-bold text-dgreen">Welcome Back </h3>
            <form class="account-form text-start mt-3" method="POST" action="{{ route('user.password.email') }}" onsubmit="return submitUserForm();">
                @csrf
                <div class="mb-0">
                    <label for="userInput" class="form-label my_value">@lang('Select Username or Email')</label>
                    <select class="form-select" name="type">
                        <option value="email">@lang('Email')</option>
                        <option value="username">@lang('Username')</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="userInputValue" class="form-label"></label>
                    <input type="text" class="form-control  @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" required autofocus="off">
                    @error('value')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success bg-green border-0 rounded-1 w-100" >@lang('Send Password Code')</button>
            </form>
        </div>
    </center>
</div>
@endsection

@section('script')
<script>
(function($){
    "use strict";

    myVal();
    $('select[name=type]').on('change',function(){
        myVal();
    });
    function myVal(){
        $('.my_value').text($('select[name=type] :selected').text());
    }
})(jQuery)
</script>
@endsection
