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
                               <th>Services</th>
                               <th>Featured Services</th>
                               <th>Bids</th>
                               <th>Status</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                        @foreach ($pakage_details as $m)
                            <tr>
                                <td><button data-id="{{$m->id}}" data-title="{{$m->title}}" data-price={{$m->price}}  data-bids="{{$m->bids}}" data-status="{{$m->status}}" data-services="{{$m->services}}"data-f_services="{{$m->f_services}}" class="icon-btn btn--warning ml-1 closed" data-toggle="modal" data-target="#MembershipModel" title="Edit" data-original-title="Edit" data-id="1">Edit</button></td>
                                <td>{{$m->title}}</td>
                                <td>{{$m->price}}</td>
                                <td>{{$m->services}}</td>
                                <td>{{$m->f_services}}</td>
                                <td>{{$m->bids}}</td>
                                <td>{{$m->status == 0 ? "Inactive" : "Active"}}</td>
                            
                              
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
            <form method="post" action="{{ url('/admin/package/updatebidpakage') }}">
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
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
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
        let status = $(e.relatedTarget).data('status');
        let bids = $(e.relatedTarget).data('bids');
        let services = $(e.relatedTarget).data('services');
        let f_services = $(e.relatedTarget).data('f_services');
      
        
        $('#id').val(id);
        $('#price').val(price);
        $('#title').val(title);
        $('#bids').val(bids);
        $('#status').val(status);
        $('#services').val(services);
        $('#f_services').val(f_services);
       
    });
})
</script>
@endpush