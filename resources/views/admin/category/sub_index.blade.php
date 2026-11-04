@extends('admin.layouts.app')

@section('panel')

<div class="row">
    <div class="col-lg-12">

        {{-- Filter Card --}}
        <div class="card b-radius--10 mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">
                    <i class="las la-filter"></i>
                    @lang('Filter Sub Categories')
                </h5>

                @if(request('search') || request('category_id'))
                    <span class="badge badge--primary">
                        @lang('Filtered Results')
                    </span>
                @endif

            </div>


            <div class="card-body">

                <form method="GET" action="{{ url()->current() }}">

                    <div class="row align-items-end">

                        {{-- Search by Name --}}
                        <div class="col-md-5">

                            <div class="form-group mb-md-0">

                                <label class="form-control-label font-weight-bold">
                                    @lang('Sub Category Name')
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
                                        placeholder="@lang('Search sub category by name...')"
                                        value="{{ request('search') }}"
                                        autocomplete="off"
                                    >

                                </div>

                            </div>

                        </div>


                        {{-- Category Filter --}}
                        <div class="col-md-4">

                            <div class="form-group mb-md-0">

                                <label class="form-control-label font-weight-bold">
                                    @lang('Category')
                                </label>

                                <select
                                    name="category_id"
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


                                @if(request('search') || request('category_id'))

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


        {{-- Sub Category Table --}}
        <div class="card b-radius--10">

            <div class="card-body p-0">

                <div class="table-responsive--sm table-responsive">

                    <table class="table table--light style--two">

                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Last Update')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>


                        <tbody>

                            @forelse($subCategorys as $subCategory)

                                <tr>

                                    {{-- Name --}}
                                    <td data-label="@lang('Name')">

                                        <div class="user">

                                            <div class="thumb">

                                                <img
                                                    src="{{ getImage('assets/images/subcategory/'.$subCategory->image) }}"
                                                    alt="@lang('image')"
                                                >

                                            </div>

                                            <span class="name">
                                                {{ __($subCategory->name) }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Category --}}
                                    <td data-label="@lang('Category')">

                                        @if($subCategory->category)

                                            <span class="font-weight-bold">
                                                {{ __($subCategory->category->name) }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                @lang('N/A')
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Last Update --}}
                                    <td data-label="@lang('Last Update')">

                                        {{ showDateTime($subCategory->updated_at) }}

                                        <br>

                                        <small class="text-muted">
                                            {{ diffForHumans($subCategory->updated_at) }}
                                        </small>

                                    </td>


                                    {{-- Action --}}
                                    <td data-label="@lang('Action')">

                                        <a
                                            href="javascript:void(0)"
                                            class="icon-btn btn--primary ml-1 updateCategory"
                                            data-id="{{ $subCategory->id }}"
                                            data-name="{{ $subCategory->name }}"
                                            data-category_id="{{ $subCategory->category_id }}"
                                            title="@lang('Edit')"
                                        >
                                            <i class="las la-edit"></i>
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        class="text-muted text-center py-5"
                                        colspan="100%"
                                    >

                                        <i
                                            class="las la-folder-open"
                                            style="font-size:40px; display:block; margin-bottom:10px;"
                                        ></i>

                                        {{ __($emptyMessage) }}

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Pagination --}}
            @if($subCategorys->hasPages())

                <div class="card-footer py-4">
                    {{ paginateLinks($subCategorys) }}
                </div>

            @endif

        </div>

    </div>
</div>


{{-- Add Sub Category Modal --}}
<div id="addModal" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    @lang('Add Sub Category')
                </h5>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>


            <form
                action="{{ route('admin.category.subcategory.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="modal-body">

                    {{-- Name --}}
                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Name')
                        </label>

                        <input
                            type="text"
                            class="form-control form-control-lg"
                            name="name"
                            placeholder="@lang('Enter Name')"
                            maxlength="100"
                            required
                        >

                    </div>


                    {{-- Category --}}
                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Select Category')
                        </label>

                        <select
                            name="category_id"
                            class="form-control"
                            required
                        >

                            <option value="">
                                @lang('Select Category')
                            </option>

                            @foreach($categorys as $category)

                                <option value="{{ $category->id }}">
                                    {{ __($category->name) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Image --}}
                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Image')
                        </label>

                        <div class="custom-file">

                            <input
                                type="file"
                                name="image"
                                class="custom-file-input"
                                id="addSubCategoryImage"
                            >

                            <label
                                class="custom-file-label"
                                for="addSubCategoryImage"
                                data-browse="@lang('Choose Image')"
                            >
                                @lang('Image')
                            </label>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn--dark"
                        data-dismiss="modal"
                    >
                        @lang('Close')
                    </button>

                    <button
                        type="submit"
                        class="btn btn--primary"
                    >
                        <i class="fa fa-fw fa-paper-plane"></i>
                        @lang('Submit')
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- Update Sub Category Modal --}}
<div id="updateCategoryModal" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    @lang('Update Sub Category')
                </h5>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>


            <form
                action="{{ route('admin.category.subcategory.update') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <input type="hidden" name="id">


                <div class="modal-body">

                    {{-- Name --}}
                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Name')
                        </label>

                        <input
                            type="text"
                            class="form-control form-control-lg"
                            name="name"
                            placeholder="@lang('Enter Name')"
                            maxlength="100"
                            required
                        >

                    </div>


                    {{-- Category --}}
                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Select Category')
                        </label>

                        <select
                            name="category_id"
                            class="form-control"
                            required
                        >

                            <option value="">
                                @lang('Select Category')
                            </option>

                            @foreach($categorys as $category)

                                <option value="{{ $category->id }}">
                                    {{ __($category->name) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Image --}}
                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Image')
                        </label>

                        <div class="custom-file">

                            <input
                                type="file"
                                name="image"
                                class="custom-file-input"
                                id="updateSubCategoryImage"
                            >

                            <label
                                class="custom-file-label"
                                for="updateSubCategoryImage"
                                data-browse="@lang('Choose Image')"
                            >
                                @lang('Image')
                            </label>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn--dark"
                        data-dismiss="modal"
                    >
                        @lang('Close')
                    </button>

                    <button
                        type="submit"
                        class="btn btn--primary"
                    >
                        <i class="fa fa-fw fa-paper-plane"></i>
                        @lang('Update')
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


{{-- Add Button --}}
@push('breadcrumb-plugins')

<a
    href="javascript:void(0)"
    class="btn btn-sm btn--primary box--shadow1 text--small addCategory"
>
    <i class="las la-plus"></i>
    @lang('Add Sub Category')
</a>

@endpush


@push('script')

<script>

"use strict";


/*
|--------------------------------------------------------------------------
| Add Sub Category
|--------------------------------------------------------------------------
*/

$('.addCategory').on('click', function () {

    $('#addModal').modal('show');

});


/*
|--------------------------------------------------------------------------
| Update Sub Category
|--------------------------------------------------------------------------
*/

$('.updateCategory').on('click', function () {

    var modal = $('#updateCategoryModal');

    modal.find('input[name=id]')
        .val($(this).data('id'));

    modal.find('input[name=name]')
        .val($(this).data('name'));

    modal.find('select[name=category_id]')
        .val($(this).data('category_id'));

    modal.modal('show');

});


/*
|--------------------------------------------------------------------------
| File Input
|--------------------------------------------------------------------------
*/

$(document).on('change', '.custom-file-input', function () {

    var fileName = $(this)
        .val()
        .split("\\")
        .pop();

    $(this)
        .siblings('.custom-file-label')
        .addClass('selected')
        .html(fileName);

});

</script>

@endpush
