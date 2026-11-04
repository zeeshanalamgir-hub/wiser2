@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
             
                </div>
                <div class="card-footer py-4">
                    <form method="post" action="{{ url('/admin/package/addjobpakage') }}">
                        @csrf
                        <div class="row"> 

                           <!--  <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                <select id="type" name="type" required class="form-control">
                                    <option value="0">Normal</option>
                                    <option value="1">Priority</option>
                                    <option value="2">Featured</option>
                                </select>
                            </div> -->
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" maxlength="40" name="title" placeholder="Enter Title" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" required>
                                </div>
                            </div>
                          
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold"><span id="bid-title">Regular Jobs</span> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="jobs" name="jobs" placeholder="Enter number of Jobs" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold"><span id="bid-title">Featured Jobs</span> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="f_jobs" name="f_jobs" placeholder="Enter number of Jobs" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold"><span id="bid-title">Invites</span> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="invites" name="invites" placeholder="Enter number of Invites" required>
                                    </div>
                                </div>
                                 
                                <!-- <div class="col-lg-4 col-md-4 col-sm-12">
                                    <label for="name" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                    <select id="status" name="status" required class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div> -->
                            
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary btn-lg mt-2">Save</button>
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