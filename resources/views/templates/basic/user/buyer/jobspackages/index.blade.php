@extends($activeTemplate.'layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="dashboard-content">

    <p class=" mb-0 mt-3 text-bc"><a class="text-3gray" href="{{ route('user.buyer.dashboard') }}">
        <i class="fa-solid fa-house fs-14"></i></a>
        <i class="fa-solid fa-chevron-right fs-12 px-1"></i> <span id="current-breadcrumb">Job Packages</span>
    </p>
     <style type="text/css">
        .border1{
        background-color: #0cb48c !important;
        }
        .border1 h4{
        color: white !important;
        font-size: 12px !important;
        font-weight: 700;
        }
        .border h4{
        font-size: 14px;
        }
        </style>
    <div class="dashboard-widg-bar d-block">
        <div class="row mb-30-none">
            <div class="col-md-12 text-center mb-4">
                <h3>Employer Bids Packages</h3>
            </div>
           <!--  <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mt-1"></h4>
                        <br>
                    </div>

                    <div class="col-md-12 border">
                        <h4 class="text-dark">Price</h4>
                    </div>
                    <div class="col-md-12 border">
                        <h4 class="text-dark">Regular Jobs</h4>
                    </div>
                    <div class="col-md-12 border">
                        <h4 class="text-dark">Featured Jobs</h4>
                    </div>
                    <div class="col-md-12 border">
                        <h4 class="text-dark">Invites</h4>
                    </div>
                </div>
            </div>
            @foreach ($jobspakages as $key=>$m)
            <div class="col-md-2">
                <div class="row">
                    <div class="col-md-12 border">
                        <h4 class="text-danger">{{$m->title}}</h4>
                    </div>
                    <div class="col-md-12 border">
                        <h4>${{$m->price}}</h4>
                    </div>
                    <div class="col-md-12 border">
                        <h4>{{$m->jobs}}</h4>
                    </div>
                    <div class="col-md-12 border">
                        <h4>{{$m->f_jobs}}</h4>
                    </div>
                    <div class="col-md-12 border">
                        <h4>{{$m->invites}}</h4>
                    </div>

                    <a href="{{url('/user/buyer/jobspayment?id='.$m->id)}}"><button class="btn btn-success mt-1 mb-1">Get</button></a>


                </div>

            </div>

            @endforeach -->

             <div class="col-md-2 mt-3">
                <div class="row">
                    <div class="col-md-12">

                    </div>
                    <div class="col-md-12 pt-2 ">
                        <h4 style="color: transparent;">p</h4>
                    </div>
                    <div class="col-md-12 border pt-2 border1 pl-1">
                        <h4 class="text-dark">Price</h4>
                    </div>
                    <div class="col-md-12 border pt-2 border1 pl-1">
                        <h4 class="text-dark">Regular Jobs</h4>
                    </div>
                    <div class="col-md-12 border pt-2 border1 pl-1">
                        <h4 class="text-dark">Featured Jobs</h4>
                    </div>
                    <div class="col-md-12 border pt-2 border1 pl-1">
                        <h4 class="text-dark">Invites</h4>
                    </div>


                    <!-- <div class="col-md-12 border pt-2">
                        <h4 class="text-dark">Support</h4>
                    </div> -->
                </div>
            </div>
            @foreach ($jobspakages as $key=>$m)
            <div class="col-md-2 mt-3">
                <div style="height: 60px;width: 60px;border-radius: 50px;background: transparent;text-align: center;color:#fa7610 !important;border: 1px solid #fa7610 !important;margin: auto;margin-top: -26px;" class="pt-1">
                    <i class="fa fa-building"></i>
                </div>
                <div class="mb-2 py-2" style="width:100%;margin: auto;background: #0cb48c !important;text-align: center;margin-top: -35px;color: white;border-radius: 5px;">
                    {{$m->title}}
                </div>
                <div class="row text-center">

                    <div class="col-md-12 border pt-2 " style="border-top:transparent !important;">
                        <h4 class="font-weight-bold">${{$m->price}}</h4>
                    </div>
                    <div class="col-md-12 border pt-2">
                        <h4>{{$m->jobs}}</h4>
                    </div>
                    <div class="col-md-12 border pt-2">
                        <h4>{{$m->f_jobs}}</h4>
                    </div>
                    <div class="col-md-12 border pt-2">
                        <h4>{{$m->invites}}</h4>
                    </div>

                    <div class="col-md-12 border pt-1 text-left">






                        <a href="{{url('/user/buyer/jobspayment?id='.$m->id)}}"><button class="btn btn-block  mt-1 mb-1" style="background:#0cb48c !important;color:white !important;">Buy </button></a>
                    </div>

                </div>
            </div>
            @endforeach

        </div>
    </div>
    </div>
    @endsection
    @section('script')
    <script>
    (function($){
    "use strict";
    $('.approveBtn').on('click', function() {
    var modal = $('#detailModal');
    var feedback = $(this).data('admin_feedback');
    modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
    modal.modal('show');
    });
    })(jQuery);
    </script>
    @endsection
