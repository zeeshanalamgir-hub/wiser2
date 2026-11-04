

@php
    $hasErrors = false;
    if ($errors->any()) {
        $hasErrors = true;
    }
    if (session('notify') && session('notify')[0][0] == 'error') {
        $hasErrors = true;
    }
@endphp
@extends($activeTemplate . 'layouts.wiserlance.layout')
@section('content')
    <div class="row p-3 pb-5">
        <div class="col-12">
            <!--Breadcrumbs-->
            <p class=" text-bc fs-14"> <a class="text-3gray" href="{{ route('home') }}">
                <i class="fa-solid fa-house fs-14"></i></a>
                <i class="fa-solid fa-chevron-right fs-12"></i> Sign Up
            </p> <!--Breadcrumbs End-->
            <center>
                <div class="card bg-white shadow p-4 px-lg-5" style="max-width:600px;">
                    <form method="GET" action="{{ route('user.register') }}">
                        <div id="signUpForm">
                            <h3 class="mt-4 fw-bold text-dgreen"> Join us as a!</h3>
                            <p> Select category and in just a few steps to your own profile.</p>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="rounded-3 border border-2 p-4 selection-option" data-value="0">
                                        <img class="img-fluid" src="{{ asset('assets/wiserlance/assets/img/home/freelancer.webp') }}" width="150px">
                                        <h4 class="text-dgreen2" >I want to be a <br>freelancer</h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="rounded border border-2 p-4 mt-4 mt-md-auto selection-option" data-value="1">
                                        <img class="img-fluid" src="{{ asset('assets/wiserlance/assets/img/home/employer.png') }}" width="135px">
                                        <h4 class="mt-3 text-dgreen2">I want to be a <br>employer</h4>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="ftype" name="type" value="" />
                            <button type="submit" id="joinButton" class="btn btn-success bg-green border-0 rounded-1 mt-3 disabled w-100"> Join Us</button>
                        </div>
                    </form>
                    <p class="mt-3 text-end fs-12">You already have an account?<a class="links-c ps-2 " href="{{route('user.login')}}"> Log In Now</a></p>
                </div>
            </center>
        </div>
    </div>
@endsection
@section('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectionOptions = document.querySelectorAll(".selection-option");
    const joinButton = document.getElementById("joinButton");
    const ftypeInput = document.getElementById("ftype");

    selectionOptions.forEach(option => {
        option.addEventListener("click", function () {
            // Remove active class from all options
            selectionOptions.forEach(opt => opt.classList.remove("active", "border-success"));

            // Add active class to the clicked option
            this.classList.add("active", "border-success");

            // Set hidden input value
            ftypeInput.value = this.getAttribute("data-value");
            joinButton.removeAttribute("disabled");
            joinButton.classList.remove("disabled");
        });
    });
});

</script>
@endsection
