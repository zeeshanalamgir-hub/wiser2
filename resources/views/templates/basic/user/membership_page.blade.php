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
                            @foreach ($membership as $m)
                            <div class="col-xl-4 col-lg-3 col-md-3 col-sm-6 mb-30">
                                <div class="dashboard-item bg--primary">
                                    
                                 
                                    <div class="dashboard-content">
                                        <div class="num text-white"></div>
                                        <h3 class="text-white">{{$m->title}}</h3>
                                        <h3 class="title text-white">Price : {{$m->price}}</h3>
                                        <h3 class="title text-white"><b>Freelancer Fee</b> : {{$m->fee}}%</h3>
                                        <h3 class="title text-white"><b>Bids per Month</b> : {{$m->bids}}</h3>
                                        <h3 class="title text-white"><b>Number of Skills</b> : {{$m->skills}}</h3>
                                        <h3 class="title text-white"><b>Portfolio Size</b> : {{$m->portfolio_size}}</h3>
                                        <h3 class="title text-white"><b>Integrated collaboration features</b> : {{$m->i_c_fetaures == 0 ? "No" : "Yes"}}</h3>
                                        <h3 class="title text-white"><b>Recruiting assistance</b> : {{$m->recruiting_assistance == 0 ? "No" : "Yes"}}</h3>
                                        <h3 class="title text-white"><b>Easy, safe payments</b> : {{$m->safe_payment == 0 ? "No" : "Yes"}}</h3>
                                        <h3 class="title text-white"><b>Customer Support</b> :
                                            @if ($m->support === 1)
                                            Basic
                                        @elseif ($m->support === 2)
                                            Standard
                                        @elseif ($m->support === 3)
                                        Premium
                                        @else
                                            No
                                        @endif
                                        </h3>
                                        <br>
                                        @if($current_membership != $m->id)
                                        <a href="{{url('/user/payment?c_id='.$current_membership.'&id='.$m->id)}}"><button class="btn btn-primary">Upgrade</button></a>
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