@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
             
                </div>
                <div class="card-footer py-4">
                     <form method="post" action="{{ route('admin.frontend.tier.update', $edittier->id) }}">
                        @csrf
                         
                                               <div class="row">
                            <div class=" col-lg-6 col-md-6 col-sm-12">
                                <label for="name" class="font-weight-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class=" col-lg-6 col-md-6 col-sm-12">
                                <label for="type" class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option  value="">Select Type</option>
                                    <option value="select">Select</option>
                                    <option value="input">Input</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>
                            <div class=" col-lg-6 col-md-6 col-sm-12">
                                <label for="cat" class="font-weight-bold">Category <span class="text-danger">*</span></label>
                                <select name="cat_id" class="form-control" required>
                                    <option  value="">Select Category</option>
                                    @foreach($cat as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" col-lg-6 col-md-6 col-sm-12">
                                <label for="cat" class="font-weight-bold">Sub Category  <span class="text-danger">*</span></label>
                                <select name="subcat_id" class="form-control" required>
                                    <option  value="">Select Sub Category</option>
                                    @foreach($subcat as $subcategory)
                                    <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" col-lg-6 col-md-6 col-sm-12">
                                <label for="cat" class="font-weight-bold">Child Category  <span class="text-danger">*</span></label>
                                <select name="childcat_id" class="form-control" required>
                                    <option  value="">Select Child Category</option>
                                    @foreach($childcat as $childcategory)
                                    <option value="{{$childcategory->id}}">{{$childcategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                                <div class="col-md-12 text-right mt-10">
                                    <button type="submit" class="btn btn-primary btn-lg mt-2">Add</button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection

