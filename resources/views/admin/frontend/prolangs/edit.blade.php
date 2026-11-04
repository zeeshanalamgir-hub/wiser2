 @extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
             
                </div>
                <div class="card-footer py-4">
                     <form method="post" action="{{ route('admin.frontend.language.update', $editlang->id) }}">
                        @csrf
                         
                                               <div class="row">
                            <div class=" col-lg-6 col-md-6 col-sm-12">
                                <label for="name" class="font-weight-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{$editlang->name}}" name="name" required>
                            </div>
                           

                                <div class="col-md-12 text-right mt-10">
                                    <button type="submit" class="btn btn-primary btn-lg mt-2">Update</button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection

