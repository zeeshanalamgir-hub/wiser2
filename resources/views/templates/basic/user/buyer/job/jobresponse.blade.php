@extends($activeTemplate.'layouts.master')
@section('content')
<section class="all-sections ptb-60">
    <div class="container-fluid">
        <div class="section-wrapper">
            <div class="row justify-content-center mb-30-none">
                @include($activeTemplate . 'partials.buyer_sidebar')
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
                                                <th>Free Lancer</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($bidingresponse as $job)
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
                                                    <td>{{__($job->firstname." ".$job->lastname)}}</td>
                                                    <td>
                                                        <a title="View Detail" href="{{url('/user/buyer/job/responsedetail/'.$job->bid_id)}}" class="btn btn--warning text-white"><i class="las la-eye"></i></a>
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


<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalLabel">@lang('Job Closed Confirmation')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
                <form method="POST" action="{{route('user.job.cancel')}}">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to close this job')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-rounded text-white" data-bs-dismiss="modal">@lang('Close')</button>
                         <button type="submit" class="btn btn--success btn-rounded text-white">@lang('Confirm')</button>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection



@push('script')
<script>
    'use strict';
    $('.cancelBtn').on('click', function () {
        var modal = $('#cancelModal');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
</script>
@endpush