@extends($activeTemplate.'layouts.wiserlance.layout')
@section('content')
@php
    $content = getContent('breadcrumbs.content', true);
@endphp
<div class="row p-3 p-md-3">
    <div class="col-12">
        <!--Breadcrumbs-->
        <p class=" text-bc fs-14"><a class="text-3gray" href="{{ route('user.login') }}">
                <i class="fa-solid fa-house fs-14"></i></a>
            <i class="fa-solid fa-chevron-right fs-12"></i> Verification
        </p> <!--Breadcrumbs End-->
        <center>
            <div class="card bg-white shadow p-4 px-md-5" style="max-width:600px;">
                <h3 class="mt-4 fw-bold text-dgreen">Verification </h3>
                <p class="text-secondary fs-14 m-0">A verification code has been sent to <span class="text-green">{{auth()->user()->email}}</span></p>
                <p class="text-dgreen2">Please verify your email to get access!</p>

                <form class="text-start mt-3 account-form" method="POST" action="{{route('user.verify.email')}}">
                    @csrf
                    <div class="mb-3">
                        <label  class="form-label">@lang('Verification Code*')</label>
                        <input type="text" name="email_verified_code" class="form-control" maxlength="7" id="code" required>
                    </div>
                    <button type="submit" class="btn btn-success bg-green border-0 rounded-1 w-100">@lang('Submit')</button>
                </form>
                <p class="mt-4 fs-14">Please check including your junk/spam Folder, if not found, you can</br><a href="{{route('user.send.verify.code')}}?type=email" class="links-c ms-2">@lang('Resend code')</a>

                </P>
            </div>
        </center>
    </div>
</div>
@endsection
