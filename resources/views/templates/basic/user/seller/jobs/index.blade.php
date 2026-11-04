@extends($activeTemplate.'layouts.wiserlance.dashboard-layout')
@section('content')

<!-- Bid Cancel Modal -->
<div class="modal fade" id="cancelbidModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
    <div class="modal-dialog modal-xl login-pop-form" role="document">
        <div class="modal-content" id="loginmodal">
            <div class="modal-headers">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="ti-close"></span>
                </button>
            </div>
            <div class="modal-body p-5">
                <div class="text-center mb-4">
                    <h4 class="m-0 ft-regular">@lang('Cancel Bid')</h4>
                </div>
                {{-- action="{{route('user.seller.job.cancel.bid')}}" --}}
                <form method="POST" action="{{ url('user/seller/job/bids/cancel-bid') }}" >
                    @csrf
                    <input type="hidden" name="cancelbid_id">
                    <input type="hidden" name="confirm" value="cancel">

                    <h6>@lang('Are you sure to cancel this bid ?')<h6>
                    <div class="form-group">
                        <input type="text" name="cancenreason" class="form-control" placeholder="Cancel Reason" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                    </div>
                    <div class="form-group text-center mb-0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Bid Cancel Modal -->

<div class="dashboard-content">
    <div class="dashboard-tlbar d-block mb-5">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-muted"><a href="{{url('')}}">Home</a></li>
                        <li class="breadcrumb-item text-muted"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);" class="theme-cl">Manage Services</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="dashboard-widg-bar d-block">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="mb-4 tbl-lg rounded overflow-hidden">
                    <div class="table-responsive bg-white">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    {{-- <th scope="col"></th> --}}
                                    <th scope="col">@lang('Title')</th>
                                    {{-- <th scope="col">@lang('Category')</th> --}}
                                    <th scope="col">@lang('Budget')</th>
                                    <th scope="col">@lang('Delivery Time')</th>
                                    <th scope="col">Bid Price</th>
                                    <th scope="col">Cover Letter</th>
                                    <th scope="col">Status</th>
                                    {{-- <th scope="col">@lang('Last Update')</th> --}}
                                    <th scope="col" style="width:20%;" class="text-center">@lang('Action')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobsapplieds as $job)
                                <tr>
                                    {{-- <td>
                                        <img src="{{getImage('assets/images/job/'.$job->image,'590x300') }}" alt="@lang('Job Image')" class="img-fluid circle" width="50" />
                                    </td> --}}
                                    <td>
                                        <div class="dash-title">
                                            <h4 class="mb-0 ft-medium fs-sm"><a href="{{route('job.details', [encrypt($job->id)])}}">{{__(str_limit($job->title, 20))}} {{ $job->user_biding_id}}</a></h4>
                                        </div>
                                    </td>
                                    {{-- <td><span class="ft-medium">{{__($job->category->name)}}</span></td> --}}
                                    <td>{{showAmount($job->amount)}} {{$general->cur_text}}</td>
                                    <td>{{$job->delivery_time}} @lang('Days')</td>
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
                                    <td>
                                        <a href="javascript:void(0);" data-cancelbid_id="{{ $job->user_biding_id }}" title="Cancel Bid" data-toggle="modal" data-target="#cancelbidModal" class="cancelbidBtn p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="lni lni-trash-can"></i></a>
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

    @endsection


    @section('script')
    <script>
        'use strict';
        $('#res-btn').on('click', function() {
            var modal = $('#respondmodel');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });

        $('.cancelbidBtn').on('click', function() {
            var modal = $('#cancelbidModal');
            modal.find('input[name=cancelbid_id]').val($(this).data('cancelbid_id'))
            modal.modal('show');
        });

    </script>
    @endsection
