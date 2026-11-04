@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
<style>
    .btn:hover {
        background-color: #069170 !important;
        color: #fff !important;
    }
    .input-popup {
    background: #f8f9fa;
    border: 1px solid #e6e6e6;
    border-radius: 6px;
    padding: 12px;
    margin-top: 10px;
}

.input-popup p {
    margin: 4px 0;
    font-size: 13px;
    color: #dc3545;
    display: flex;
    align-items: center;
    gap: 6px;
}

.input-popup p.valid {
    color: #28a745;
}

.input-popup p::before {
    content: "✖";
    font-size: 12px;
}

.input-popup p.valid::before {
    content: "✔";
}
</style>
@section('content')
    <div class="dashboard-content mt-2">
        <div class="dashboard-tlbar d-block mb-5 d-none">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <h1 class="ft-medium">Change Password</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="#">Home</a></li>
                            <li class="breadcrumb-item text-muted"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#" class="theme-cl">Change Password</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <form action="" method="post" role="form">
            @csrf
            <div class="dashboard-widg-bar d-block">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="_dashboard_content bg-white rounded mb-4">
                            <div class="_dashboard_content_header br-bottom py-3 px-3">
                                <div class="_dashboard__header_flex">
                                    <h4 class="mb-0 ft-medium fs-md" style="display: flex;gap:10px"><i class="fa fa-lock mr-1 theme-cl fs-sm"></i>Change
                                        Password</h4>
                                </div>
                            </div>

                            <div class="_dashboard_content_body py-3 px-3">
                                <div class="row">

                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <label class="text-dark ft-medium">@lang('Current Password')</label>
                                            <input type="password" name="current_password" class="form-control"
                                                placeholder="@lang('Current Password')" required="">
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12  mt-2 mb-2">
                                        <div class="form-group">
                                            <label class="text-dark ft-medium">@lang('New Password')</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="@lang('New Password')" required="">
                                            @if ($general->secure_password)
                                                <div class="input-popup">
                                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                                    <p class="error number">@lang('1 number minimum')</p>
                                                    <p class="error special">@lang('1 special character minimum')</p>
                                                    <p class="error minimum">@lang('6 character password')</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <label class="text-dark ft-medium">@lang('Confirm Password')</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                placeholder="@lang('Confirm Password')" required="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12" style="text-align: right">
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md rounded bg-green3 text-white">@lang('CHANGE PASSWORD')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endsection


    @section('script')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
        <script>
            (function($) {
                "use strict";
                @if ($general->secure_password)
                    $('input[name=password]').on('input', function() {
                        secure_password($(this));
                    });
                @endif
            })(jQuery);
            $('input[name=password]').on('input', function() {

            let password = $(this).val();

            let lower = /[a-z]/;
            let upper = /[A-Z]/;
            let number = /[0-9]/;
            let special = /[!@#$%^&*]/;
            let length = password.length >= 6;

            toggleClass('.lower', lower.test(password));
            toggleClass('.capital', upper.test(password));
            toggleClass('.number', number.test(password));
            toggleClass('.special', special.test(password));
            toggleClass('.minimum', length);

        });

        function toggleClass(selector, status){
            if(status){
                $(selector).addClass('valid');
            }else{
                $(selector).removeClass('valid');
            }
        }

        </script>
    @endsection
