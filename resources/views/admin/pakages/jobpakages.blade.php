@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two table-bordered">
                            <thead>
                            <tr>
                               <th>Action</th>
                               <th>Title</th>
                               <th>Price</th>
                               <th>Regular Jobs</th>
                               <th>Featured Jobs</th>
                               <th>Invites</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                        @foreach ($pakage_details as $m)
                            <tr>
                                <td><button data-id="{{$m->id}}" data-type="{{$m->type}}" data-title="{{$m->title}}" data-price={{$m->price}}  data-jobs="{{$m->jobs}}" data-f_jobs="{{$m->f_jobs}}" data-invites="{{$m->invites}}" data-status="{{$m->status}}" class="icon-btn btn--warning ml-1 closed" data-toggle="modal" data-target="#MembershipModel" title="Edit" data-original-title="Edit" data-id="1">Edit</button></td>
                               
                                <td>{{$m->title}}</td>
                                <td>{{$m->price}}</td>
                                <td>{{$m->jobs}}</td>
                                <td>{{$m->f_jobs}}</td>
                                <td>{{$m->invites}}</td>
                              
                            </tr>
                        @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    
                        {{ paginateLinks($pakage_details) }}
                
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
            <form method="post" action="{{ url('/admin/package/updatejobpakage') }}">
                @csrf
                <input type="hidden" name="id" id="id"/>
                
                {{-- <!-- <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="name" class="font-weight-bold">Type <span class="text-danger">*</span></label>
                        <select id="type" name="type" required class="form-control">
                            <option value="0">Normal</option>
                            <option value="1">Priority</option>
                            <option value="2">Featured</option>
                        </select>
                    </div>
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
                                <input type="number" class="form-control" id="jobs" name="jobs" placeholder="Enter number of Bids" required>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="name" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" required class="form-control">
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                    
                        <div class="col-md-12 text-center">
                            <button class="btn btn-primary btn-lg mt-2">Update</button>
                        </div>
                </div> --> --}}
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
                                        {{-- <select id="invites" name="invites" required class="form-control">
                                        <option value="30">30 invites</option>
                                        <option value="unlimited">Unlimited invites</option> --}}
                                    </select>
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
        let type = $(e.relatedTarget).data('type');
        let title = $(e.relatedTarget).data('title');
        let price = $(e.relatedTarget).data('price');
        let status = $(e.relatedTarget).data('status');
        let jobs = $(e.relatedTarget).data('jobs');
        let f_jobs = $(e.relatedTarget).data('f_jobs');
        let invites = $(e.relatedTarget).data('invites');
        
        $('#id').val(id);
        $('#type').val(type);
        $('#price').val(price);
        $('#title').val(title);
        $('#jobs').val(jobs);
        $('#f_jobs').val(f_jobs);
        $('#invites').val(invites);
        $('#status').val(status);
       
    });
})
</script>
@endpush