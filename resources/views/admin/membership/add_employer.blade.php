@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                </div>
                <div class="card-footer py-4">
                    <form method="post" action="{{ url('/admin/membership/storeemp') }}">
                        @csrf
                        <div class="row">

                           <!--  @if($check_default == 0)
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Default <span class="text-danger">*</span></label>
                                    <select class="form-control" name="default" required>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                        <select>
                                </div>
                            </div>
                            @endif -->
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
                                    <label for="name" class="font-weight-bold">Service Platform Fee <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="service_platform_fee" name="service_platform_fee" placeholder="Service Platform Fee" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Job Platform Fee <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="fee" name="fee" placeholder="Job Platform Fee" required>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Regular job posting <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="normal_jobs" name="normal_jobs" placeholder="Enter Normal Serives" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Featured job posting  <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="featured_jobs" name="featured_jobs" placeholder="Enter Featured Serives" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Invite your selected sellers to your posted jobs<span class="text-danger">*</span></label>
                                <input value="0" type="number" class="form-control" id="invites" name="invites" placeholder="Enter Invites" required>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Mechanism for matching the seller's expertise to the job requirements <span class="text-danger">*</span></label>
                                <select id="expertise_matching_mechanism" name="expertise_matching_mechanism" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Requirements for approval and verification of payment methods <span class="text-danger">*</span></label>
                                <select id="verified" name="verified" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Requirement to deposit money that matches the cost of the job <span class="text-danger">*</span></label>
                                <select id="money_deposit_for_job" name="money_deposit_for_job" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Chat, share files, and place audio and video calls. <span class="text-danger">*</span></label>
                                <select id="communication_tools" name="communication_tools" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Timesheets, time tracker <span class="text-danger">*</span></label>
                                <select id="time_tracking_features" name="time_tracking_features" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Job posting  assistance <span class="text-danger">*</span></label>
                                <select id="r_assistance" name="r_assistance" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Easy, safe payments <span class="text-danger">*</span></label>
                                <select id="safe_payment" name="safe_payment" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Featured Job tag <span class="text-danger">*</span></label>
                                <select id="jobtag" name="jobtag" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Customer Support <span class="text-danger">*</span></label>
                                <select id="support" name="support" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Basic</option>
                                    <option value="2">Standard</option>
                                    <!-- <option value="1">Premium</option> -->
                                </select>
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-lg mt-2">Add</button>
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
