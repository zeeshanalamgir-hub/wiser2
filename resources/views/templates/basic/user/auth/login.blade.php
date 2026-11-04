@extends($activeTemplate . 'layouts.wiserlance.layout')
@section('content')
<div class="col-12 p-md-3">
    <!--Breadcrumbs-->
    <p class=" text-bc fs-14"><a class="text-3gray" href="{{ url('/') }}">
            <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12"></i> Log In
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
            <p class="px-3 fs-12 text-end">You don't have an account? <a class="links-c ms-1" href="signup">Proceed to sign up</a></p>
            <h3 class="mt-4 fw-bold text-dgreen">Welcome Back </h3>
            <form class="account-form text-start mt-3" method="POST" action="{{ route('user.login') }}">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username or Email </label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required="">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required="">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="dd">
                    <a href="{{ route('user.password.request') }}" class="float-end links-c forget-pass" >@lang('Forgot Password')?</a>

                    <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-success bg-green border-0 rounded-1 w-100" >@lang('Login Now')</button>
            </form>


            {{-- <p class="mt-4 fs-12">Or continue with <i class="fa-solid fa-arrow-right me-3"></i>
                <a href="#" class="links"><img src="img/google.png" width="25"> </a>
                <a href="#" class="links"><img src="img/apple.png" width="28" class="me-2"> </a>
                <a href="#" class="links"><img src="img/facebook.png" width="23"></a>
            </P> --}}

        </div>
    </center>
</div>
@endsection
@section('script')
@endsection
