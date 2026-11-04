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
                    <form method="post" action="{{ url('/admin/membership/update') }}">
                        @csrf
                        <input type="hidden" name="id" id="id" />
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Default <span class="text-danger">*</span></label>
                                    <select class="form-control" name="default" required>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" maxlength="40"
                                        name="title" placeholder="Enter Title" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Price /Month<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        placeholder="Enter Price" required>
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
                                    <label for="name" class="font-weight-bold">Monthly Number of Regular Services<span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="normal_services" name="normal_services" placeholder="Enter Normal Services" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Monthly Number of Featured Services <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="featured_services" name="featured_services" placeholder="Enter Featured Services" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Annual Services Rollover <span class="text-danger">*</span></label>
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
                                    <label for="name" class="font-weight-bold">Job Cost <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="fee" name="fee"
                                        placeholder="Enter Price" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Monthly Number of Bids <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bids" name="bids" placeholder="Enter number of Bids" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Featured Jobs Bids <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="featuredjobquotes" name="featuredjobquotes" placeholder="Enter Featured Bids" required>
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
                                <label for="name" class="font-weight-bold">Priority Support <span class="text-danger">*</span></label>
                                <select id="support" name="support" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
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
    <!-- <div class="row py-3" style="box-shadow: 0px 0 30px rgb(1 41 112 / 8%);">
                 @foreach ($membership_details as $m)
    <div class="col-lg-4  col-md-4 col-sm-12 col-12 mb-3"  >
                    <div style="border-top: 8px solid #344392;">
                        <div class="text-center">
                            <h4 class="mt-3 font-weight-bold">{{ $m->title }}</h4>
                            <sup class="sup">$</sup>&nbsp<span class="sppr1 font-weight-bold" style="font-size:20px;">{{ $m->price }}</span>&nbsp<sub class="sppr2" style="font-size:12px;">/ Month</sub><br>

                            <button  data-id="{{ $m->id }}" data-title="{{ $m->title }}" data-price={{ $m->price }} data-fee="{{ $m->fee }}" data-bids="{{ $m->bids }}" data-skills="{{ $m->skills }}" data-p_size="{{ $m->portfolio_size }}" data-r_assistance="{{ $m->r_assistance }}" data-normal_services="{{ $m->normal_services }}" data-priority_services="{{ $m->priority_services }}" data-featured_services="{{ $m->featured_services }}" data-profile_display="{{ $m->profile_display }}" data-safe_payment="{{ $m->safe_payment }}" data-support="{{ $m->support }}" class="icon-btn mt-2 btn--warning ml-1 closed" data-toggle="modal" data-target="#MembershipModel" title="Edit" data-original-title="Edit" data-featuredjobquotes="{{ $m->featuredjobquotes }}" data-verifiedaccount="{{ $m->verifiedaccount }}" data-jobbidcap="{{ $m->jobbidcap }}" data-id="1">Edit</button>
                        </div>
                        <hr>
                        <p class="">Job Cost <span class="sup font-weight-bold float-right">{{ $m->fee }} %</span></p>
                        <p class="">Monthly Free Bids <span class="sup font-weight-bold float-right"> {{ $m->bids }}</span></p>
                        <p class="">Featured Job quotes <span class="sup float-right">
                             @if ($m->title == 'Try it free')
    {{ $m->featuredjobquotes }}
@else
    Cost {{ $m->featuredjobquotes }} bids
    @endif
                        </span></p>
                         <p class=""> Job Bidding Cap  <span class="sup float-right">Job value maximum: ${{ $m->jobbidcap }} </span></p>
                         <p class=""> Portfolio Slot Cap   <span class="sup float-right">{{ $m->portfolio_size }}</span></p>
                         <p class=""> Service Posting Cap <span class="sup float-right">{{ $m->normal_services }} </span></p>
                         <p class=""> Featured Service Posting Cap <span class="sup float-right">{{ $m->featured_services }} </span></p>
                         <p class="">Find Jobs<span class="sup float-right">Available </span></p>
                         <p class="">Quick Withdrawals Service<span class="sup float-right">{{ $m->safe_payment == 0 ? 'No' : 'Yes' }}</span></p>
                         <p class="">Verified Account <span class="sup float-right">{{ $m->verifiedaccount == 0 ? 'No' : 'Yes' }}</span></p>

                         <p class="">Priorty Support<span class="sup float-right"> @if ($m->support === 0)
    No
@elseif ($m->support === 1)
    Yes
    @endif
        </span>
        </p>


                    </div>
                </div>
    @endforeach
            </div> -->

  <div class="row mb-30-none">
    <!-- Left Column - Headers -->
    <div class="col-md-2 mt-3">
        <div class="row">
            <div class="col-md-12">
                <!-- Empty div for spacing -->
            </div>
            <div class="col-md-12" style="padding-top:5px;padding-bottom:5px;">
                <h4 style="color: transparent; height: 24px;">p</h4> <!-- Fixed height for spacing -->
            </div>
            
            <!-- Header rows with fixed height and vertical alignment -->
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Price/Month</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Service Cost</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Monthly Number of Regular Services</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Monthly Number of Featured Services</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Annual Services Rollover</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Purchase additional Service</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Job Cost</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Monthly Number of Bids</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Featured Job Bids</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Annual Bids Rollover</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Purchase Additional Bids</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Portfolio Slots Limit</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Verified Account</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Priority Support</h4>
            </div>
            <div class="col-md-12 border border1 pl-1 d-flex align-items-center" style="height: 60px; padding-top:8px;padding-bottom:2px;">
                <h4 class="text-dark mb-0">Easy Safe Withdrawals</h4>
            </div>
            
            <!-- Add empty div for button alignment -->
            <div class="col-md-12 border border1" style="height: 60px;"></div>
        </div>
    </div>

    <!-- Membership Details Columns -->
    @foreach ($membership_details as $key => $m)
        <div class="col-md-2 mt-3">
            <div style="height: 60px;width: 60px;border-radius: 50px;background: transparent;text-align: center;color:#ff9f43 !important;border: 1px solid #ff9f43 !important;margin: auto;margin-top: -26px;"
                class="pt-1">
                <i class="fa fa-building"></i>
            </div>
            <div class="mb-2 py-2"
                style="width:100%;margin: auto;background: #344392 !important;text-align: center;margin-top: -35px;color: white;">
                {{ $m->title }}
            </div>
            <div class="row text-center">
                <!-- Value cells with matching heights and vertical alignment -->
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">${{ $m->price }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->service_cost }}%</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->normal_services }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->featured_services }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->annual_services_rollover }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->purchase_service == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->fee }}%</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->bids }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">
                        @if ($m->title == 'Try it free')
                            {{ $m->featuredjobquotes }}
                        @else
                            {{ $m->featuredjobquotes }}
                        @endif
                    </h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->annual_bids_rollover }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->purchase_additional_bids == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->portfolio_size }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->verifiedaccount == 0 ? 'No' : 'Yes' }}</h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">
                        @if ($m->support === 0)
                            No
                        @elseif ($m->support === 1)
                            Yes
                        @endif
                    </h4>
                </div>
                <div class="col-md-12 border pt-2 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <h4 class="mb-0">{{ $m->safe_payment == 0 ? 'No' : 'Yes' }}</h4>
                </div>

                <!-- Button cell with matching height -->
                <div class="col-md-12 border pt-1 d-flex align-items-center justify-content-center" style="height: 60px;">
                    <button data-id="{{ $m->id }}"
                            data-title="{{ $m->title }}"
                            data-price="{{ $m->price }}"
                            data-fee="{{ $m->fee }}"
                            data-bids="{{ $m->bids }}"
                            data-p_size="{{ $m->portfolio_size }}"
                            data-service_cost="{{ $m->service_cost }}"
                            data-normal_services="{{ $m->normal_services }}"
                            data-featured_services="{{ $m->featured_services }}"
                            data-annual_services_rollover="{{ $m->annual_services_rollover }}"
                            data-purchase_service="{{ $m->purchase_service }}"
                            data-annual_bids_rollover="{{ $m->annual_bids_rollover }}"
                            data-purchase_additional_bids="{{ $m->purchase_additional_bids }}"
                            data-safe_payment="{{ $m->safe_payment }}"
                            data-support="{{ $m->support }}"
                            data-verifiedaccount="{{ $m->verifiedaccount }}"
                            data-featuredjobquotes="{{ $m->featuredjobquotes }}"
                            class="icon-btn mt-2 btn--warning my-1 mb-2 btn-block closed"
                            data-toggle="modal" data-target="#MembershipModel"
                            title="Edit"
                            data-original-title="Edit">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#MembershipModel').on('show.bs.modal', function(e) {
                let id = $(e.relatedTarget).data('id');
                let title = $(e.relatedTarget).data('title');
                let price = $(e.relatedTarget).data('price');
                let fee = $(e.relatedTarget).data('fee');
                let bids = $(e.relatedTarget).data('bids');

                let p_size = $(e.relatedTarget).data('p_size');

                let service_cost = $(e.relatedTarget).data('service_cost');
                let normal_services = $(e.relatedTarget).data('normal_services');
                let featured_services = $(e.relatedTarget).data('featured_services');
                let annual_services_rollover = $(e.relatedTarget).data('annual_services_rollover');
                let purchase_service = $(e.relatedTarget).data('purchase_service');

                let annual_bids_rollover = $(e.relatedTarget).data('annual_bids_rollover');
                let purchase_additional_bids = $(e.relatedTarget).data('purchase_additional_bids');

                let profile_display = $(e.relatedTarget).data('profile_display');
              ;
                let safe_payment = $(e.relatedTarget).data('safe_payment');
                let support = $(e.relatedTarget).data('support');
                let featuredjobquotes = $(e.relatedTarget).data('featuredjobquotes');

                let verifiedaccount = $(e.relatedTarget).data('verifiedaccount');


                // Populate the modal input fields
                $('#id').val(id);
                $('#price').val(price);
                $('#title').val(title);
                $('#fee').val(fee);
                $('#bids').val(bids);
                $('#p_size').val(p_size);
                $('#service_cost').val(service_cost);
                $('#normal_services').val(normal_services);
                $('#featured_services').val(featured_services);
                $('#annual_services_rollover').val(annual_services_rollover);
                $('#purchase_service').val(purchase_service);

                $('#annual_bids_rollover').val(annual_bids_rollover);
                $('#purchase_additional_bids').val(purchase_additional_bids);

                $('#profile_display').val(profile_display);
                $('#featuredjobquotes').val(featuredjobquotes);
                $('#safe_payment').val(safe_payment);
                $('#support').val(support);

                $('#verifiedaccount').val(verifiedaccount);
            });
        })
    </script>
@endpush
