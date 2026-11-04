@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.seller_sidebar')
                <div class="col-xl-9 col-lg-12 mb-30">
                    <div class="dashboard-sidebar-open"><i class="las la-bars"></i> @lang('Menu')</div>
                    <div class="table-section">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="table-area">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Title')</th>
                                                <th>@lang('Category')</th>
                                                <th>@lang('Budget')</th>
                                               
                                                <th>@lang('Delivery Time')</th>
                                               <th>Bid Price</th>
                                               <th>Cover letter</th>
                                               <th>status</th>
                                                <th>@lang('Last Update')</th>
                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($jobsapplieds as $job)
                                                <tr>
                                                    <td data-label="@lang('Title')" class="text-start">
                                                        <div class="author-info">
                                                            <div class="thumb">
                                                                <img src="{{getImage('assets/images/job/'.$job->image,'590x300') }}" alt="@lang('Job Image')">
                                                            </div>
                                                            <div class="content">
                                                                <a href="{{route('job.details', [slug($job->title), encrypt($job->id)])}}" title="">{{__(str_limit($job->title, 20))}}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td data-label="@lang('Category')">{{__($job->category->name)}}</td>
                                                    <td data-label="@lang('Budget')">{{showAmount($job->amount)}} {{$general->cur_text}}</td>
                                                   
                                                    <td data-label="@lang('Delivery Time')">{{$job->delivery_time}} @lang('Days')</td>
                                                    <td>{{$job->bid_price}}</td>
                                                    <td>{{$job->proposal}}</td>
                                                    <td>
                                                        @if ($job->job_status == 2)
                                                            Job Completed
                                                            @elseif ($job->job_status == 1)
                                                            Job Assigned
                                                        @else
                                                           Pending 
                                                        @endif
                                                    </td>
                                                    <td data-label="@lang('Last Update')">
                                                        {{showDateTime($job->updated_at)}}
                                                        <br>
                                                        {{diffforhumans($job->updated_at)}}
                                                    </td>
                                               
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                   
                                  
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="respondmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Respond to freelancer')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
                <form method="POST" action="{{url('/user/conversation')}}">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                     
                        <div class="row">
                            <input type="hidden" value="@if(isset($job)){{$job->freelancer_id}}@endif" name="recevier_id"/>
                            <input type="hidden" value="Respond" name="subject"/>
                            <div class="col-md-12">
                                <label>Message</label>
                                <textarea class="form-control" name="message" required></textarea>
                            </div>
                            <div class="col-md-12">
                               <button class="btn btn-success mt-2">Send</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                        
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection



@push('script')
<script>
    'use strict';
    $('#res-btn').on('click', function () {
        var modal = $('#respondmodel');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
</script>
@endpush