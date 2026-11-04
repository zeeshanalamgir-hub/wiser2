@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                </div>
                <div class="card-footer py-4">
                    <form id="membershipForm" method="post" action="{{ url('/admin/membership/freelancer/package') }}">
                        @csrf
                        <div class="row">
                            <div class="offset-lg-4 col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" id="membershipType">
                                    <option value="1">Monthly</option>
                                    <option value="2">Annually</option>
                                </select>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-primary btn-lg mt-2" id="submitBtn">Select</button>
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
    document.getElementById('submitBtn').addEventListener('click', function () {
        var selectedType = document.getElementById('membershipType').value;
        var actionUrl = '{{ url('/admin/membership/freelancer/package') }}?type=' + selectedType;

        // Update form action URL
        document.getElementById('membershipForm').action = actionUrl;

        // Submit the form
        document.getElementById('membershipForm').submit();
    });
</script>
@endpush
