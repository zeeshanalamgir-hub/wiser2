@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                </div>
                <div class="card-footer py-4">
                    <form method="post" action="{{ url('/admin/membership/store') }}">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="freelancer_payment_plan" value="{{ $type }}"/>
                            {{-- @if($check_default == 0) --}}
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Default <span class="text-danger">*</span></label>
                                        <select class="form-control" name="default" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                            <select>
                                    </div>
                                </div>
                            {{-- @endif --}}
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" maxlength="40" name="title" placeholder="Enter Title" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold"> @if($type == 1) Price /Month @else Price/ Annual @endif<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Service Cost <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="service_cost" name="service_cost" placeholder="Enter Service Price" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold"> @if($type == 1) Monthly @else Annual @endif Number of Regular Services<span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="normal_services" name="normal_services" placeholder="Enter Normal Serives" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">@if($type == 1) Monthly @else Annual @endif Number of Featured Services <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="featured_services" name="featured_services" placeholder="Enter Featured Serives" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">@if($type == 1) Monthly @else Annual @endif Services Rollover <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="annual_services_rollover" name="annual_services_rollover" placeholder="Annual Services Rollover" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Purchase additional Service <span class="text-danger">*</span></label>
                                    <select id="purchase_service" name="purchase_service" required class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Job Cost <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="fee" name="fee" placeholder="Enter Price" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">@if($type == 1) Monthly @else Annual @endif Number of Bids <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bids" name="bids" placeholder="Enter number of Bids" required>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Featured Jobs Bids <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="featuredjobquotes" name="featuredjobquotes" placeholder="Enter number of featured bids" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Annual Bids Rollover <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="annual_bids_rollover" name="annual_bids_rollover" placeholder="Annual Bids Rollover" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Purchase Additional Bids <span class="text-danger">*</span></label>
                                <select id="purchase_additional_bids" name="purchase_additional_bids" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Portfolio Slots Limit <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="p_size" name="p_size" placeholder="Enter Portfolio Size" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Verified Account <span class="text-danger">*</span></label>
                                <select id="verifiedaccount" name="verifiedaccount" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Priorty Support <span class="text-danger">*</span></label>
                                <select id="support" name="support" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                    <!-- <option value="2">Standard</option>
                                    <option value="1">Premium</option> -->
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Easy Safe Withdrawals<span class="text-danger">*</span></label>
                                <select id="safe_payment" name="safe_payment" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
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
