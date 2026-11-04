@extends($activeTemplate.'layouts.wiserlance.layout')


@section('content')


<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Login</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="middle">
    <div class="container">
        <div class="row align-items-start justify-content-center">

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                @if (Session::has('feedback'))

                <div class="alert alert-danger alert-dismissible">

                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    {!! session('feedback') !!}

                </div>

                @endif
                <form class="account-form" method="POST" action="{{ route('user.login')}}" onsubmit="return submitUserForm();">
                    @csrf

                    <div class="form-group">
                        <label>@lang('Enter Code')*</label>
                        <input type="text" class="form-control " id="code" name="code" value="{{old('code')}}" placeholder="@lang('Enter code ')" required="">
                        <!-- <input type="text" class="form-control" placeholder="Username*"> -->
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Verify')</button>
                    </div>
                   
                   
                </form>
            </div>



        </div>
    </div>
</section>
@endsection
@section('script')

@endsection