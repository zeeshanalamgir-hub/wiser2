@extends('admin.layouts.app')
@section('panel')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
    .cust-table {
    border: 1px solid #d3d3d3;
    width: 90%;
    margin: 0 auto;
    }
    .cust-table > tbody > tr {
    margin: 0;
    padding: 0;
    }
    .cust-head p {
    font-size: 1em;
    margin: 0;
    padding: 0;
    text-align: center;
    }
    .th-c2 {
    background-color: rgb(244, 255, 129) !important;
    }
    .th-c3 {
    background-color: rgb(132, 255, 255) !important;
    }
    .th-c4 {
    background-color: rgb(100, 255, 218) !important;
    }
    .b-focus {
    background-color: rgb(233, 30, 99);
    color: #fff;
    border-radius: 3px;
    font-size: 0.8em;
    width: fit-content;
    margin: 0 auto;
    }
    .vertical-col {
    display: inline-flex;
    flex-direction: column;
    }
    th {
    height: 60px;
    padding: 8px;
    align-items: center!important;
    }
    td {
    height: 60px;
    padding: 8px;
    }
    .p-bold {
    font-weight: bold;
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
                        <input type="hidden" name="id" id="id"/>
                        
                        <!-- <div class="row">
                            
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
                                    <label for="name" class="font-weight-bold">Fee <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="fee" name="fee" placeholder="Enter Price" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold"><span id="bid-title">Bids</span> <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bids" name="bids" placeholder="Enter number of Bids" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 type_check">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Skills <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="skills" name="skills" placeholder="Enter number of Skills" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 type_check">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Portfolio Size <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="p_size" name="p_size" placeholder="Enter Portfolio Size" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Normal Serives <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="normal_services" name="normal_services" placeholder="Enter Normal Serives" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Priority Serives <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="priority_services" name="priority_services" placeholder="Enter Priority Serives" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Featured Serives <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="featured_services" name="featured_services" placeholder="Enter Featured Serives" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Profile display <span class="text-danger">*</span></label>
                                <select id="profile_display" name="profile_display" required class="form-control">
                                    <option value="0">Normal</option>
                                    <option value="1">Priority</option>
                                    <option value="2">Featured</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Recruiting Assistance <span class="text-danger">*</span></label>
                                <select id="r_assistance" name="r_assistance" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Safe Payment <span class="text-danger">*</span></label>
                                <select id="safe_payment" name="safe_payment" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Support <span class="text-danger">*</span></label>
                                <select id="support" name="support" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Basic</option>
                                    <option value="2">Standard</option>
                                    <option value="1">Premium</option>
                                </select>
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-lg mt-2">Update</button>
                            </div>
                        </div> -->
                        <div class="row">
                            
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" maxlength="40" name="title" placeholder="Enter Title" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Price /Month<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" required>
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
                                    <label for="name" class="font-weight-bold">Monthly Free Bids <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bids" name="bids" placeholder="Enter number of Bids" required>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Skills <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="skills" name="skills" placeholder="Enter number of Skills" required>
                                </div>
                            </div> -->
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Featured Job quotes <span class="text-danger">*</span></label>
                                    <input  type="text" class="form-control" id="featuredjobquotes" name="featuredjobquotes" placeholder="Featured Job quotes" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Job Bidding Cap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jobbidcap" name="jobbidcap" placeholder="Job Bidding Cap" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Portfolio Slot Cap <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="p_size" name="p_size" placeholder="Enter Portfolio Size" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Service Posting Cap<span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="normal_services" name="normal_services" placeholder="Enter Normal Serives" required>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Priority Services <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="priority_services" name="priority_services" placeholder="Enter Priority Serives" required>
                                </div>
                            </div> -->
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Featured Service Posting Cap <span class="text-danger">*</span></label>
                                    <input value="0" type="number" class="form-control" id="featured_services" name="featured_services" placeholder="Enter Featured Serives" required>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Profile display <span class="text-danger">*</span></label>
                                <select id="profile_display" name="profile_display" required class="form-control">
                                    <option value="0">Normal</option>
                                    <option value="1">Priority</option>
                                    <option value="2">Featured</option>
                                </select>
                            </div> -->
                            <!--  <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Recruiting Assistance <span class="text-danger">*</span></label>
                                <select id="r_assistance" name="r_assistance" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div> -->
                            
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="name" class="font-weight-bold">Quick Withdrawals Service <span class="text-danger">*</span></label>
                                <select id="safe_payment" name="safe_payment" required class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two table cust-table">
                            <tbody>
                                <!-- Column 1 Header -->
                                <tr class="vertical-col col-lg-3">
                                    
                                    <th style="height: 90px;"></th>
                                    <th>Action</th>
                                    <!-- <th>Price</th> -->
                                    <th>Job Cost</th>
                                    <th>Monthly Free Bids</th>
                                    <!-- <th>Skils</th> -->
                                    <th>Featured Job quotes</th>
                                    <th>Job Bidding Cap</th>
                                    <th>Portfolio Slot Cap</th>
                                    <th>Service Posting Cap</th>
                                    <!-- <th>Priority Services</th> -->
                                    <th>Featured Service Posting Cap</th>
                                    <th>Find Jobs</th>
                                    <!-- <th>Profile Display</th> -->
                                    <!-- <th>Recruiting Assistance</th> -->
                                    <th>Quick Withdrawals Service</th>
                                    <th>Verified Account</th>
                                    <th style="text-align:center!important;">Priorty Support</th>
                                </tr>
                                
                                
                                @foreach ($membership_details as $m)
                                <tr class="vertical-col col-lg-3 col-md-3">
                                    
                                    @if($m->title=="Try it free")
                                    <td class="th-c2" style="height: 90px;">
                                        <div class="cust-head">
                                            <p>{{$m->title}}</p>
                                            <p class="p-bold">${{$m->price}} /month</p>
                                        </div>
                                    </td>
                                    @elseif($m->title=="Basic")
                                    <td class="th-c3" style="height: 90px;">
                                        <div class="cust-head">
                                            <p>{{$m->title}}</p>
                                             <p class="p-bold">${{$m->price}} /month</p>
                                        </div>
                                    </td>
                                    @elseif($m->title=="Standard")
                                    <td class="th-c4" style="height: 90px;">
                                        <div class="cust-head">
                                            <p>{{$m->title}}</p>
                                             <p class="p-bold">${{$m->price}} /month</p>
                                        </div>
                                    </td>
                                    @elseif($m->title=="Essestional")
                                    <td class="th-c4" style="height: 90px;">
                                        <div class="cust-head">
                                            <p>{{$m->title}}</p>
                                             <p class="p-bold">${{$m->price}} /month</p>
                                        </div>
                                    </td>
                                    @elseif($m->title=="Premium")
                                    <td class="th-c4" style="height: 90px;">
                                        <div class="cust-head">
                                            <p>{{$m->title}}</p>
                                             <p class="p-bold">${{$m->price}} /month</p>
                                        </div>
                                    </td>
                                    @else
                                    <td class="th-c2" style="height: 90px;">
                                        <div class="cust-head">
                                            <p>{{$m->title}}</p>
                                             <p class="p-bold">${{$m->price}} /month</p>
                                        </div>
                                    </td>
                                    @endif
                                    <td><button data-id="{{$m->id}}" data-title="{{$m->title}}" data-price={{$m->price}} data-fee="{{$m->fee}}" data-bids="{{$m->bids}}" data-skills="{{$m->skills}}" data-p_size="{{$m->portfolio_size}}" data-r_assistance="{{$m->r_assistance}}" data-normal_services="{{$m->normal_services}}" data-priority_services="{{$m->priority_services}}" data-featured_services="{{$m->featured_services}}" data-profile_display="{{$m->profile_display}}" data-safe_payment="{{$m->safe_payment}}" data-support="{{$m->support}}" class="icon-btn btn--warning ml-1 closed" data-toggle="modal" data-target="#MembershipModel" title="Edit" data-original-title="Edit" data-featuredjobquotes="{{$m->featuredjobquotes}}" data-verifiedaccount="{{$m->verifiedaccount}}" data-jobbidcap="{{$m->jobbidcap}}" data-id="1">Edit</button></td>
                                    
                                    <!-- <td>{{$m->price}}</td> -->
                                    <td>{{$m->fee}} %</td>
                                    <td>{{$m->bids}}</td>
                                    <!-- <td>{{$m->skills}}</td> -->
                                    @if($m->title=="Try it free")
                                    <td>{{$m->featuredjobquotes}}</td>
                                    @else
                                    <td>Cost {{$m->featuredjobquotes}} bids</td>
                                    @endif
                                    <td>Job value maximum: ${{$m->jobbidcap}}</td>
                                    <td>{{$m->portfolio_size}}</td>
                                    <td>{{$m->normal_services}}</td>
                                    <!-- <td>{{$m->priority_services}}</td> -->
                                    <td>{{$m->featured_services}}</td>
                                    <td>Available</td>
                                    <!-- <td>
                                        @if ($m->profile_display === 1)
                                        Featured
                                        @elseif ($m->profile_display === 2)
                                        Priority
                                        @else
                                        Normal
                                        @endif
                                    </td> -->
                                    <!-- <td>{{$m->r_assistance == 0 ? "No" : "Yes"}}</td> -->
                                    <td>{{$m->safe_payment == 0 ? "No" : "Yes"}}</td>
                                    <td>{{$m->verifiedaccount == 0 ? "No" : "Yes"}}</td>
                                    <td style="text-align:center!important;">
                                        @if ($m->support === 0)
                                        No
                                        @elseif ($m->support === 1)
                                        Yes
                                        
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    
                    {{ paginateLinks($membership_details) }}
                    
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
    let bids = $(e.relatedTarget).data('bids');
    let skills = $(e.relatedTarget).data('skills');
    let p_size = $(e.relatedTarget).data('p_size');
    let normal_services = $(e.relatedTarget).data('normal_services');
    let priority_services = $(e.relatedTarget).data('priority_services');
    let featured_services = $(e.relatedTarget).data('featured_services');
    let profile_display = $(e.relatedTarget).data('profile_display');
    let r_assistance = $(e.relatedTarget).data('r_assistance');
    let recruiting_assistance = $(e.relatedTarget).data('recruiting_assistance');
    let safe_payment = $(e.relatedTarget).data('safe_payment');
    let support = $(e.relatedTarget).data('support');
    let featuredjobquotes = $(e.relatedTarget).data('featuredjobquotes');
    let jobbidcap = $(e.relatedTarget).data('jobbidcap');
    let verifiedaccount = $(e.relatedTarget).data('verifiedaccount');
    
    
    
    $('#id').val(id);
    $('#price').val(price);
    $('#title').val(title);
    $('#fee').val(fee);
    $('#bids').val(bids);
    $('#skills').val(skills);
    $('#p_size').val(p_size);
    $('#normal_services').val(normal_services);
    $('#priority_services').val(priority_services);
    $('#featured_services').val(featured_services);
    $('#profile_display').val(profile_display);
    $('#r_assistance').val(r_assistance);
    $('#featuredjobquotes').val(featuredjobquotes);
    $('#safe_payment').val(safe_payment);
    $('#support').val(support);
    $('#jobbidcap').val(jobbidcap);
    $('#verifiedaccount').val(verifiedaccount);
    
    
    });
    })
    </script>
    @endpush