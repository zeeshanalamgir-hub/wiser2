@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
             
                </div>
                <div class="card-footer py-4">
                    <form method="post" action="{{ url('/admin/membership/addredirect') }}">
                        @csrf
                        <div class="row">
                            <div class="offset-lg-4 col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control">
                                    <option value="0">Freelancer</option>
                                    <option value="1">Employer</option>
                                </select>
                            </div>

                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary btn-lg mt-2">Select</button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection





@push('script')
<script>
    'use strict';
    $('.disputeShow').on('click', function () {
        var modal = $('#disputeModalShow');
        var feedback = $(this).data('dispute');
        modal.find('.dispute-detail').html(`<p> ${feedback} </p>`);
        modal.modal('show');
    });
</script>
@endpush