@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            {{-- Filter Card --}}
            <div class="card b-radius--10 mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="card-title mb-0">
                        <i class="las la-filter"></i>
                        @lang('Filter Skills')
                    </h5>

                    @if(
                        request('search') ||
                        request('category_id') ||
                        request('sub_category_id') ||
                        request('skill_category_id')
                    )

                        <span class="badge badge--primary">
                            @lang('Filtered Results')
                        </span>

                    @endif

                </div>


                <div class="card-body">

                    <form method="GET" action="{{ url()->current() }}">

                        <div class="row align-items-end">

                            {{-- Skill Name --}}
                            <div class="col-md-3">

                                <div class="form-group mb-md-0">

                                    <label class="form-control-label font-weight-bold">
                                        @lang('Skill Name')
                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">
                                                <i class="las la-search"></i>
                                            </span>

                                        </div>

                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control"
                                            placeholder="@lang('Search skill...')"
                                            value="{{ request('search') }}"
                                            autocomplete="off"
                                        >

                                    </div>

                                </div>

                            </div>


                            {{-- Category --}}
                            <div class="col-md-2">

                                <div class="form-group mb-md-0">

                                    <label class="form-control-label font-weight-bold">
                                        @lang('Category')
                                    </label>

                                    <select
                                        name="category_id"
                                        id="filter_category_id"
                                        class="form-control"
                                    >

                                        <option value="">
                                            @lang('All Categories')
                                        </option>

                                        @foreach($categorys as $category)

                                            <option
                                                value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}
                                            >
                                                {{ __($category->name) }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- Sub Category --}}
                            <div class="col-md-2">

                                <div class="form-group mb-md-0">

                                    <label class="form-control-label font-weight-bold">
                                        @lang('Sub Category')
                                    </label>

                                    <select
                                        name="sub_category_id"
                                        id="filter_sub_category_id"
                                        class="form-control"
                                    >

                                        <option value="">
                                            @lang('All Sub Categories')
                                        </option>

                                    </select>

                                </div>

                            </div>


                            {{-- Skill Category --}}
                            <div class="col-md-2">

                                <div class="form-group mb-md-0">

                                    <label class="form-control-label font-weight-bold">
                                        @lang('Skill Category')
                                    </label>

                                    <select
                                        name="skill_category_id"
                                        id="filter_skill_category_id"
                                        class="form-control"
                                    >

                                        <option value="">
                                            @lang('All Skill Categories')
                                        </option>

                                    </select>

                                </div>

                            </div>


                            {{-- Buttons --}}
                            <div class="col-md-3">

                                <div class="d-flex">

                                    <button
                                        type="submit"
                                        class="btn btn--primary mr-2"
                                    >
                                        <i class="las la-search"></i>
                                        @lang('Search')
                                    </button>


                                    @if(
                                        request('search') ||
                                        request('category_id') ||
                                        request('sub_category_id') ||
                                        request('skill_category_id')
                                    )

                                        <a
                                            href="{{ url()->current() }}"
                                            class="btn btn--dark"
                                        >
                                            <i class="las la-redo-alt"></i>
                                            @lang('Reset')
                                        </a>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Sub Category')</th>
                                    <th>@lang('Skill Category')</th>
                                    <th>@lang('Skills')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($skills as $skill)
                                <tr>
                                    <td data-label="@lang('Category')">
                                        <span class="font-weight-bold">{{$skill->category->name}}</span>
                                    </td>
                                    <td data-label="@lang('Sub Category')">
                                        <span class="font-weight-bold">{{$skill->subCategory->name}}</span>
                                    </td>
                                   
                                    <td data-label="@lang('Skill Category')">
                                        <span class="font-weight-bold">{{$skill->skillCategory->name}}</span>
                                    </td>
                                     <td data-label="@lang('Gateway')">
                                        <div class="user">

                                            {{-- <div class="thumb"><img src="{{getImage(imagePath()['skillcategory']['path'].'/'. $skill->image,imagePath()['skillcategory']['size'])}}" alt="@lang('image')"></div> --}}
                                            <span class="name">{{__($skill->name)}}</span>
                                        </div>
                                    </td>
                                 
                                  
                                  
                                 
                                    <td data-label="@lang('Last Update')">
                                        {{ showDateTime($skill->updated_at) }} <br> {{ diffForHumans($skill->updated_at) }}
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" class="icon-btn btn--primary ml-1 updateCategory"
                                            data-id="{{$skill->id}}" 
                                            data-name="{{$skill->name}}" 
                                            data-category_id="{{$skill->category_id}}" 
                                            data-sub_category_id="{{$skill->sub_category_id}}" 
                                            data-skill_category_id="{{$skill->skill_category_id}}" 
                                            >
                                            <i class="las la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($skills) }}
                </div>
            </div>
        </div>
    </div>


    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Skill Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.skill.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" name="name" placeholder="@lang("Enter Name")"  maxlength="100" required="">
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="form-control-label font-weight-bold">@lang('Select Category')</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option>@lang('Select Category')</option>
                                @foreach($categorys as $category)
                                    <option value="{{$category->id}}">{{__($category->name)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sub_category_id" class="form-control-label font-weight-bold">@lang('Select SubCategory')</label>
                            <select name="sub_category_id" id="sub_category_id" class="form-control">
                                <option>@lang('Select Category First')</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="skill_category_id" class="form-control-label font-weight-bold">@lang('Select Skill Category')</label>
                            <select name="skill_category_id" id="skill_category_id" class="form-control">
                                <option>@lang('Select Sub Category First')</option>
                            </select>
                        </div>

                        {{-- <div class="form-group">
                            <label for="symbol" class="form-control-label font-weight-bold">@lang('Image')</label>
                            <div class="custom-file">
                              <input type="file" name="image" class="custom-file-input" id="customFileLangHTML">
                              <label class="custom-file-label" for="customFileLangHTML" data-browse="@lang('Choose Image')">@lang('Image')</label>
                            </div>
                        </div> --}}

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="updateCategoryModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update SubCategory')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.skill.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" name="name" placeholder="@lang("Enter Name")"  maxlength="100" required="">
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="form-control-label font-weight-bold">@lang('Select Category')</label>
                            <select name="category_id" id="category_id_update" class="form-control">
                                <option>@lang('Select Category')</option>
                                @foreach($categorys as $category)
                                    <option value="{{$category->id}}">{{__($category->name)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sub_category_id" class="form-control-label font-weight-bold">@lang('Select SubCategory')</label>
                            <select name="sub_category_id" id="sub_category_id_update" class="form-control">
                                <option>@lang('Select Category first')</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="skill_category_id" class="form-control-label font-weight-bold">@lang('Select SubCategory')</label>
                            <select name="skill_category_id" id="skill_category_id_update" class="form-control">
                                <option>@lang('Select Sub Category first')</option>
                            </select>
                        </div>

                        {{-- <div class="form-group">
                            <label for="symbol" class="form-control-label font-weight-bold">@lang('Image')</label>
                            <div class="custom-file">
                              <input type="file" name="image" class="custom-file-input" id="customFileLangHTML">
                              <label class="custom-file-label" for="customFileLangHTML" data-browse="@lang('Choose Image')">@lang('Image')</label>
                            </div>
                        </div> --}}

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addCategory" ><i class="las la-plus"></i>@lang('Add Skills')</a>
@endpush

@push('script')
    <script>
        "use strict";
        $('.addCategory').on('click', function() {
            $('#addModal').modal('show');
        });

        $('.updateCategory').on('click', function () {
            var modal = $('#updateCategoryModal');
            var categoryId = $(this).data('category_id');
            var subCategoryId = $(this).data('sub_category_id');
            var skillCategoryId = $(this).data('skill_category_id');

            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('input[name=name]').val($(this).data('name'));
            modal.find('select[name=category_id]').val(categoryId);
            loadSubCategories(categoryId, $('#sub_category_id_update'), subCategoryId, function (selectedSubCategoryId) {
                loadSkillCategories(selectedSubCategoryId, $('#skill_category_id_update'), skillCategoryId);
            });
            modal.modal('show');
        });

         $(document).on("change",".custom-file-input",function(){
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        function loadSubCategories(categoryId, select, selectedValue, callback) {
            $.ajax({
                url:"{{ route('admin.category.subcategory.data') }}",
                data:{
                    category_id:categoryId
                },
                success:function(data){
                    select.empty();
                    $.each(data,function(value, key)
                    {
                        select.append('<option value="' + key + '">' + value + '</option>');
                    });
                    if (selectedValue !== undefined && selectedValue !== null) {
                        select.val(selectedValue);
                    }
                    if (callback) {
                        callback(select.val());
                    }
                }
            })
        }

        function loadSkillCategories(subCategoryId, select, selectedValue) {
            $.ajax({
                url:"{{ route('admin.category.skillcategory.data') }}",
                data:{
                    sub_category_id:subCategoryId
                },
                success:function(data){
                    select.empty();
                    $.each(data,function(value, key)
                    {
                        select.append('<option value="' + key + '">' + value + '</option>');
                    });
                    if (selectedValue !== undefined && selectedValue !== null) {
                        select.val(selectedValue);
                    }
                }
            })
        }

        $('#category_id').on('change', function(){
            loadSubCategories($(this).val(), $('#sub_category_id'), null, function (subCategoryId) {
                loadSkillCategories(subCategoryId, $('#skill_category_id'), null);
            });
        });

        $('#category_id_update').on('change', function(){
            loadSubCategories($(this).val(), $('#sub_category_id_update'), null, function (subCategoryId) {
                loadSkillCategories(subCategoryId, $('#skill_category_id_update'), null);
            });
        });

        $('#sub_category_id').on('change', function(){
            loadSkillCategories($(this).val(), $('#skill_category_id'), null);
        });

        $('#sub_category_id_update').on('change', function(){
            loadSkillCategories($(this).val(), $('#skill_category_id_update'), null);
        });
        // Filter Code
        $('#filter_category_id').on('change', function () {

            let categoryId = $(this).val();

            let subCategorySelect = $('#filter_sub_category_id');
            let skillCategorySelect = $('#filter_skill_category_id');

            subCategorySelect.html(
                '<option value="">Loading...</option>'
            );

            skillCategorySelect.html(
                '<option value="">All Skill Categories</option>'
            );


            if (!categoryId) {

                subCategorySelect.html(
                    '<option value="">All Sub Categories</option>'
                );

                return;
            }


            $.ajax({

                url: "{{ route('admin.category.subcategory.data') }}",

                data: {
                    category_id: categoryId
                },

                success: function (data) {

                    subCategorySelect.empty();

                    subCategorySelect.append(
                        '<option value="">All Sub Categories</option>'
                    );


                    $.each(data, function (value, key) {

                        let selected =
                            "{{ request('sub_category_id') }}" == key
                                ? 'selected'
                                : '';

                        subCategorySelect.append(
                            '<option value="' + key + '" ' + selected + '>' +
                            value +
                            '</option>'
                        );

                    });


                    // Load Skill Categories if sub category exists
                    if ("{{ request('sub_category_id') }}") {

                        subCategorySelect.trigger('change');

                    }

                }

            });

        });


    // Sub Category → Skill Categories
    $('#filter_sub_category_id').on('change', function () {

        let subCategoryId = $(this).val();

        let skillCategorySelect = $('#filter_skill_category_id');

        skillCategorySelect.html(
            '<option value="">Loading...</option>'
        );


        if (!subCategoryId) {

            skillCategorySelect.html(
                '<option value="">All Skill Categories</option>'
            );

            return;
        }


        $.ajax({

            url: "{{ route('admin.category.skillcategory.data') }}",

            data: {
                sub_category_id: subCategoryId
            },

            success: function (data) {

                skillCategorySelect.empty();

                skillCategorySelect.append(
                    '<option value="">All Skill Categories</option>'
                );


                $.each(data, function (value, key) {

                    let selected =
                        "{{ request('skill_category_id') }}" == key
                            ? 'selected'
                            : '';

                    skillCategorySelect.append(
                        '<option value="' + key + '" ' + selected + '>' +
                        value +
                        '</option>'
                    );

                });

            }

        });

    });


    // Load filters when page loads
    @if(request('category_id'))

        $('#filter_category_id').trigger('change');

    @endif
    </script>
@endpush
