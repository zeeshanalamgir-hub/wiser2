@extends('admin.layouts.app')
@section('panel')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .p-bold {
            font-weight: bold;
        }

        .border1 {
            background-color: #344392 !important;
        }

        .border1 h4 {
            color: white !important;
            font-size: 12px !important;
            font-weight: 700;
        }

        .border h4 {
            font-size: 14px;
        }
    </style>
  <div class="row">
    <!-- Left Column - Headers with auto height -->
    <div class="col-md-4 mt-3">
        <div class="row">
            <div class="col-md-12"></div>
            <div class="col-md-12" style="padding-top:5px;padding-bottom:5px;">
                <h4 style="color: transparent; height: 24px;">p</h4>
            </div>

            <!-- Header cells with min-height and auto height if text wraps -->
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Price/Month</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Service Platform fees</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Job Platform fees</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Regular job posting</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Features job posting</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Invite your selected sellers to your posted jobs</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Mechanism for matching the seller's expertise to the job requirements</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Requirements for approval and verification of payment methods</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Requirement to deposit money that matches the cost of the job</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Chat, share files, and place audio and video calls.</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Timesheets, time tracker</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Job Posting Assistance</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Easy, safe payments</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Featured Job tag</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="min-height: 50px; height: auto; padding-top:8px;padding-bottom:8px;">
                <h4 class="text-dark mb-0">Customer Support</h4>
            </div>

            <!-- Empty div for spacing with same min-height -->
            <div class="col-md-12 border border1" style="min-height: 50px; height: auto;"></div>
        </div>
    </div>

    <!-- Membership Details Columns with min-height -->
    @foreach ($membership_details as $key => $m)
        <div class="col-md-2 mt-3">
            <div style="height: 60px;width: 60px;border-radius: 50px;background: transparent;text-align: center;color:#ff9f43 !important;border: 1px solid #ff9f43 !important;margin: auto;margin-top: -26px;" class="pt-1">
                <i class="fa fa-building"></i>
            </div>
            <div class="mb-2 py-2" style="width:100%;margin: auto;background: #344392 !important;text-align: center;margin-top: -35px;color: white;">
                {{ $m->title }}
            </div>
            <div class="row text-center">

                <!-- Value cells with min-height to match headers -->
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">${{ $m->price }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->service_platform_fee }}%</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->fee }}%</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->normal_jobs > 100000 ? 'Unlimited' : $m->normal_jobs }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->featured_jobs }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">
                        @if ($m->invites === 'unlimited')
                            {{ $m->invites }}
                        @else
                            {{ $m->invites }}
                        @endif
                    </h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->expertise_matching_mechanism == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->verified == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->money_deposit_for_job == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->communication_tools == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->time_tracking_features == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->r_assistance == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->safe_payment == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">{{ $m->jobtag == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <h4 class="mb-0">
                        @if ($m->support === 1)
                            Basic
                        @elseif ($m->support === 2)
                            Standard
                        @elseif ($m->support === 3)
                            Premium
                        @else
                            No
                        @endif
                    </h4>
                </div>

                <!-- Button cell with same min-height -->
                <div class="col-md-12 border d-flex align-items-center justify-content-center" style="min-height: 50px; height: auto; padding: 8px;">
                    <button data-id="{{ $m->id }}" data-title="{{ $m->title }}" data-price="{{ $m->price }}" data-fee="{{ $m->fee }}" data-r_assistance="{{ $m->r_assistance }}"
                        data-normal_jobs="{{ $m->normal_jobs }}" data-priority_jobs="{{ $m->priority_jobs }}" data-featured_jobs="{{ $m->featured_jobs }}"
                        data-no_projects="{{ $m->no_projects }}" data-safe_payment="{{ $m->safe_payment }}" data-support="{{ $m->support }}"
                        data-verified="{{ $m->verified }}" data-service_platform_fee="{{ $m->service_platform_fee }}" data-expertise_matching_mechanism="{{ $m->expertise_matching_mechanism }}"
                        data-money_deposit_for_job="{{ $m->money_deposit_for_job }}" data-communication_tools="{{ $m->communication_tools }}" data-time_tracking_features="{{ $m->time_tracking_features }}"
                        class="icon-btn btn--warning my-1 mb-2 btn-block closed"
                        data-toggle="modal" data-jobtag="{{ $m->jobtag }}" data-invites="{{ $m->invites }}" data-target="#MembershipModel" title="Edit"
                        data-original-title="Edit">Edit</button>
                </div>
            </div>
        </div>
    @endforeach
</div>

    <!-- The Modal for edit memberships -->
    <div class="modal" id="MembershipModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Update Membership</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form method="post" action="{{ url('/admin/membership/updateemp') }}">
                        @csrf
                        <input type="hidden" name="id" id="id"/>

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
                                    <input type="text" class="form-control" id="normal_jobs" name="normal_jobs" placeholder="Entre Regular Jobs" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Featured job posting  <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="featured_jobs" name="featured_jobs" placeholder="Enter Featured Jobs" required>
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
                                <button class="btn btn-primary btn-lg mt-2">Update</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
     @endsection
    @push('script')

    <script>
    $(document).ready(function(){
    $('#MembershipModel').on('show.bs.modal', function(e){
    let id = $(e.relatedTarget).data('id');
    let title = $(e.relatedTarget).data('title');
    let price = $(e.relatedTarget).data('price');
    let fee = $(e.relatedTarget).data('fee');

    let normal_jobs = $(e.relatedTarget).data('normal_jobs');
    let priority_jobs = $(e.relatedTarget).data('priority_jobs');
    let featured_jobs = $(e.relatedTarget).data('featured_jobs');

    let r_assistance = $(e.relatedTarget).data('r_assistance');
    let jobtag = $(e.relatedTarget).data('jobtag');
    let invites = $(e.relatedTarget).data('invites');
    let safe_payment = $(e.relatedTarget).data('safe_payment');
    let support = $(e.relatedTarget).data('support');
    let verified = $(e.relatedTarget).data('verified');

    let service_platform_fee = $(e.relatedTarget).data('service_platform_fee');
    let expertise_matching_mechanism = $(e.relatedTarget).data('expertise_matching_mechanism');
    let money_deposit_for_job = $(e.relatedTarget).data('money_deposit_for_job');
    let communication_tools = $(e.relatedTarget).data('communication_tools');
    let time_tracking_features = $(e.relatedTarget).data('time_tracking_features');

    $('#id').val(id);
    $('#price').val(price);
    $('#title').val(title);
    $('#fee').val(fee);
    
    if(normal_jobs >= 100000) {
        $('#normal_jobs').val('Unlimited');
    }else {
        $('#normal_jobs').val(normal_jobs);
    }
    $('#priority_jobs').val(priority_jobs);
    $('#featured_jobs').val(featured_jobs);
    $('#r_assistance').val(r_assistance);
    $('#jobtag').val(jobtag);
    $('#invites').val(invites);
    $('#safe_payment').val(safe_payment);
    $('#support').val(support);
    $('#verified').val(verified);

    $('#service_platform_fee').val(service_platform_fee);
    $('#expertise_matching_mechanism').val(expertise_matching_mechanism);
    $('#money_deposit_for_job').val(money_deposit_for_job);
    $('#communication_tools').val(communication_tools);
    $('#time_tracking_features').val(time_tracking_features);

    });
    })
    </script>
    @endpush
