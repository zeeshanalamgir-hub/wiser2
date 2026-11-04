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

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <!--<table class="table table--light style--two table-bordered">-->
                        <!--    <thead>-->
                        <!--    <tr>-->
                        <!--       <th>Action</th>-->
                        <!--       <th>Title</th>-->
                        <!--       <th>Price</th>-->
                        <!--       <th>Fee</th>-->
                               
                        <!--       <th>Normal Job</th>-->
                        <!--       <th>Priority Job</th>-->
                        <!--       <th>Featured Job</th>-->
                        <!--       <th>Recruiting Assistance</th>-->
                        <!--       <th>Safe Payment</th>-->
                        <!--       <th>Support</th>-->
                        <!--       <th>verified</th>-->
                        <!--    </tr>-->
                        <!--    </thead>-->
                        <!--    <tbody>-->
                        <!--@foreach ($membership_details as $m)-->
                        <!--    <tr>-->
                        <!--        <td><button data-id="{{$m->id}}" data-title="{{$m->title}}" data-price={{$m->price}} data-fee="{{$m->fee}}" data-r_assistance="{{$m->r_assistance}}" data-normal_jobs="{{$m->normal_jobs}}" data-priority_jobs="{{$m->priority_jobs}}" data-featured_jobs="{{$m->featured_jobs}}" data-no_projects="{{$m->no_projects}}" data-safe_payment="{{$m->safe_payment}}" data-support="{{$m->support}}" data-verified="{{$m->verified}}" class="icon-btn btn--warning ml-1 closed" data-toggle="modal" data-target="#MembershipModel" title="Edit" data-original-title="Edit" data-id="1">Edit</button></td>-->
                        <!--        <td>{{$m->title}}</td>-->
                        <!--        <td>{{$m->price}}</td>-->
                        <!--        <td>{{$m->fee}} %</td>-->
               
                        <!--        <td>{{$m->normal_jobs}}</td>-->
                        <!--        <td>{{$m->priority_jobs}}</td>-->
                        <!--        <td>{{$m->featured_jobs}}</td>-->
                      
                        <!--        <td>{{$m->r_assistance == 0 ? "No" : "Yes"}}</td>-->
                        <!--        <td>{{$m->safe_payment == 0 ? "No" : "Yes"}}</td>-->
                        <!--        <td>-->
                        <!--            @if ($m->support === 1)-->
                        <!--                Basic-->
                        <!--            @elseif ($m->support === 2)-->
                        <!--                Standard-->
                        <!--            @elseif ($m->support === 3)-->
                        <!--            Premium-->
                        <!--            @else-->
                        <!--                No-->
                        <!--            @endif-->
                        <!--        </td>-->
                        <!--        <td>{{$m->verified == 0 ? "No" : "Yes"}}</td>-->
                        <!--    </tr>-->
                        <!--@endforeach-->

                        <!--    </tbody>-->
                        <!--</table>-->
                        
                         <table class="table table--light style--two table cust-table">
               <tbody>
            <!-- Column 1 Header -->
            <tr class="vertical-col col-lg-3">
   <!--<th>Title</th>-->
                             <th style="height: 90px;"></th>
                           <th>Action</th>
                               
                               <th>Service Fee</th>
                               
                               <th>Regular job posting </th>
                               <th>Features job posting </th>
                               <th>Total Job Invites Per Month</th>
                               <th>Featured Job tag </th>
                               <th>Job posting assistance</th>
                               <th>Easy, safe payments </th>
                               <th>Customer Support </th>
                               <th style="text-align:center!important;">Approval and verification of payment methods</th>
            </tr>

            <!-- Column 2 -->
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
                    @else
                                      <td class="th-c2" style="height: 90px;">
                    <div class="cust-head" >
                        <p>{{$m->title}}</p>
                         <p class="p-bold">${{$m->price}} /month</p>
                    </div>
                    </td>
                    @endif
                <td><button data-id="{{$m->id}}" data-title="{{$m->title}}" data-price={{$m->price}} data-fee="{{$m->fee}}" data-r_assistance="{{$m->r_assistance}}" data-normal_jobs="{{$m->normal_jobs}}" data-priority_jobs="{{$m->priority_jobs}}" data-featured_jobs="{{$m->featured_jobs}}" data-no_projects="{{$m->no_projects}}" data-safe_payment="{{$m->safe_payment}}" data-support="{{$m->support}}" data-verified="{{$m->verified}}" class="icon-btn btn--warning ml-1 closed" data-toggle="modal" data-jobtag="{{$m->jobtag}}" data-invites="{{$m->invites}}" data-target="#MembershipModel" title="Edit" data-original-title="Edit" data-id="1">Edit</button></td>
                                <!--<td>{{$m->price}}</td>-->
                                <td>{{$m->fee}} %</td>
               
                                <td>{{$m->normal_jobs}}</td>
                                <!-- <td>{{$m->priority_jobs}}</td> -->
                                <td>{{$m->featured_jobs}}</td>
                                <td>{{$m->invites}}</td>
                                <td>{{$m->jobtag == 0 ? "No" : "Yes"}}</td>
                                <td>{{$m->r_assistance == 0 ? "No" : "Yes"}}</td>
                                <td>{{$m->safe_payment == 0 ? "No" : "Yes"}}</td>
                                <td>
                                    @if ($m->support === 1)
                                        Basic
                                    @elseif ($m->support === 2)
                                        Standard
                                    @elseif ($m->support === 3)
                                    Premium
                                    @else
                                        No
                                    @endif
                                </td>
                                <td style="text-align:center!important;">{{$m->verified == 0 ? "No" : "Yes"}}</td>
                

            </tr>
            @endforeach

            <!-- Column 3 -->
       

            <!-- Column 4 -->
     


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
              
               {{-- <!--  <div class="row">
              
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
                                <label for="name" class="font-weight-bold">Normal Jobs <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="normal_jobs" name="normal_jobs" placeholder="Enter Normal Serives" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Priority Jobs <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="priority_jobs" name="priority_jobs" placeholder="Enter Priority Serives" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Featured Jobs <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="featured_jobs" name="featured_jobs" placeholder="Enter Featured Serives" required>
                            </div>
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
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="name" class="font-weight-bold">Verified <span class="text-danger">*</span></label>
                            <select id="verified" name="verified" required class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-12 text-center">
                            <button class="btn btn-primary btn-lg mt-2">Update</button>
                        </div>
                </div> --> --}}
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
                                    <label for="name" class="font-weight-bold">Service Fee <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="fee" name="fee" placeholder="Service Fee" required>
                                </div>
                            </div>
                            
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Regular job posting <span class="text-danger">*</span></label>
                                        <input value="0" type="number" class="form-control" id="normal_jobs" name="normal_jobs" placeholder="Enter Normal Serives" required>
                                    </div>
                                </div>
                               <!--  <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Priority Jobs <span class="text-danger">*</span></label>
                                        <input value="0" type="number" class="form-control" id="priority_jobs" name="priority_jobs" placeholder="Enter Priority Serives" required>
                                    </div>
                                </div> -->
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Features job posting  <span class="text-danger">*</span></label>
                                        <input value="0" type="number" class="form-control" id="featured_jobs" name="featured_jobs" placeholder="Enter Featured Serives" required>
                                    </div>
                                </div>
                               <div class="col-lg-4 col-md-4 col-sm-12">
                                    <label for="name" class="font-weight-bold">Total Job Invites Per Month<span class="text-danger">*</span></label>
                                    <select id="invites" name="invites" required class="form-control">
                                        <option value="30">30 invites</option>
                                        <option value="unlimited">Unlimited invites</option>
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
                                    <label for="name" class="font-weight-bold">Customer Support <span class="text-danger">*</span></label>
                                    <select id="support" name="support" required class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Basic</option>
                                        <option value="2">Standard</option>
                                        <!-- <option value="1">Premium</option> --> 
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <label for="name" class="font-weight-bold">Requirements for approval and verification of payment methods <span class="text-danger">*</span></label>
                                    <select id="verified" name="verified" required class="form-control">
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
        $('#id').val(id);
        $('#price').val(price);
        $('#title').val(title);
        $('#fee').val(fee);
     
        $('#normal_jobs').val(normal_jobs);
        $('#priority_jobs').val(priority_jobs);
        $('#featured_jobs').val(featured_jobs);
       $('#r_assistance').val(r_assistance);
        $('#jobtag').val(jobtag);
       $('#invites').val(invites);
        $('#safe_payment').val(safe_payment);
        $('#support').val(support);
        $('#verified').val(verified);
    });
})
</script>
@endpush