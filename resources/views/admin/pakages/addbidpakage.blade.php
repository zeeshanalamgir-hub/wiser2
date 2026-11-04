@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
             
                </div>
                <div class="card-footer py-4">
                    <form method="post" action="{{ url('/admin/package/addbidpakage') }}">
                        @csrf
                        <div class="row">
              
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
                                        <label for="name" class="font-weight-bold"><span id="bid-title">Bids</span> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="bids" name="bids" placeholder="Enter number of Bids" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold"><span id="bid-title">Services</span> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="services" name="services" placeholder="Enter number of Services" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold"><span id="bid-title">Featured Services</span> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="f_services" name="f_services" placeholder="Enter number of Featured Services" required>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <label for="name" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                    <select id="status" name="status" required class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            
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