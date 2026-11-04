@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
          
            <div class="row justify-content-center mb-30-none">
               
                
                <div class="col-xl-9 col-lg-12 mb-30">
               
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="dashboard-section">
                        <div class="row justify-content-center mb-30-none">
                            <div class="col-md-12 text-center">
                            <h2>Payment gateway integration</h2>
                            <form method="post" action="{{ url('/user/buyer/payment') }}">
                                @csrf
                                <input name="current_id" value="{{$current_id}}" type="hidden"/>
                                <input name="new_id" value="{{$new_id}}" type="hidden"/>
                                @foreach ($membership as $m)
                                <input type="hidden" name="type" value="{{$m->type}}"/>
                                <div class="offset-lg-3 col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-30">
                                    <div class="dashboard-item">
                                        
                                        <div class="row justify-content-center mb-30-none">
                                            <div class="col-md-12 text-center">
                                               
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12 border">
                                                        <h4 class="text-info">Title</h4>
                                                        
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
                                                        <h4 class="text-info">Priority Jobs</h4>
                                                    </div>
                                                    <div class="col-md-12 border">
                                                        <h4 class="text-info">Featured Jobs</h4>
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
                                            
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12 border">
                                                        <h4 class="text-primary">{{$m->title}}</h4>
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
                                                    
                                                </div>
                                            </div>
    
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                 <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#serviceModal" class="btn btn-success"><i class="las la-sign-in-alt"></i> @lang('Checkout')</a>
                                           
                            <!--<button class="btn btn-success">Pay</button>-->
                            </form>
                            </div>
                            
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</section>

   <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Payment You Order')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" action="{{ url('/user/buyer/payment') }}">
                @csrf
                <div class="modal-body">
                 <input name="current_id" value="{{$current_id}}" type="hidden"/>
                   <input name="new_id" value="{{$new_id}}" type="hidden"/>
                    <div class="form-group">
                        <h5>@lang('How you want to pay')</h5>
                        <select class="form-control" name="payment">
                            <option value="wallet">{{__($general->sitename)}} @lang('wallet')</option>
                            <!--<option value="checkout">@lang('Checkout')</option>-->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--base btn-rounded text-white">@lang('Confirm')</button>
                </div>
            </form>
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