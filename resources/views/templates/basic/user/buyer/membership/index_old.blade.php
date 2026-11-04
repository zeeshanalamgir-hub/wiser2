@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60"> 
    <div class="container-fluid">
        <div class="section-wrapper">
          
            <div class="row justify-content-center mb-30-none">
                
                 @include($activeTemplate . 'partials.buyer_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
               
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="dashboard-section">
                        <div class="row justify-content-center mb-30-none">
                            <div class="col-md-12 text-center">
                                <h3>Employer Membership Packages</h3>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="mt-1"></h4>
                                        <br>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Price</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Platform Fee</h4>
                                    </div>
                                  
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Normal Jobs</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Priority jobs</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Featured jobs</h4>
                                    </div>
                                 
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Recruiting assistance</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Safe Payment</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4 class="text-info">Support</h4>
                                    </div>
                                </div>
                            </div>
                            @foreach ($membership as $key=>$m)
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12 border">
                                        <h4 class="text-{{$arr[$key]}}">{{$m->title}}</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4>${{$m->price}}</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4>{{$m->fee}}%</h4>
                                    </div>
                                  
                                    <div class="col-md-12 border">
                                        <h4>{{$m->normal_jobs}}</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4>{{$m->priority_jobs}}</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4>{{$m->featured_jobs}}</h4>
                                    </div>
                                  
                                    <div class="col-md-12 border">
                                        <h4>{{$m->r_assistance == 0 ? "No" : "Yes"}}</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4>{{$m->safe_payment == 0 ? "No" : "Yes"}}</h4>
                                    </div>
                                    <div class="col-md-12 border">
                                        <h4>
                                            @if ($m->support == 1)
                                            Basic
                                            @elseif($m->support == 2)
                                            Standard
                                            @elseif($m->support == 3)
                                            Premium
                                            @else
                                            No
                                            @endif
                                    </h4>
                                    </div>
                                    <div class="col-md-12 border">
                                    @if($m->default == 0)
                                        @if($currentmembership_id == $m->id)
                                            <p class="text-danger">Current Membership</p>
                                        @else
                                        <a href="{{url('/user/buyer/payment?c_id='.$currentmembership_id.'&id='.$m->id)}}"><button class="btn btn-success mt-1 mb-1">Get Started</button></a>
                                        @endif
                                    @endif
                                    </div>
                                  
                                </div>
                            </div>
                            @endforeach
                         

             
                       
                            
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</section>

   
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Details')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">

                <div class="withdraw-detail"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection



@push('script')
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
@endpush