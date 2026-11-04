@extends('admin.layouts.app')

@section('panel')

<div class="row">
    <div class="col-lg-12">

        {{-- Filter Card --}}
        <div class="card b-radius--10 mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="las la-filter"></i>
                    @lang('Filter Categories')
                </h5>

                @if(request('search'))
                    <span class="badge badge--primary">
                        @lang('Filtered Results')
                    </span>
                @endif
            </div>

            <div class="card-body">

                <form method="GET" action="{{ url()->current() }}">

                    <div class="row align-items-end">

                        {{-- Name Search --}}
                        <div class="col-md-6 col-lg-5">
                            <div class="form-group mb-md-0">
                                <label class="form-control-label font-weight-bold">
                                    @lang('Category Name')
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
                                        placeholder="@lang('Search category by name...')"
                                        value="{{ request('search') }}"
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-md-6 col-lg-7">
                            <div class="d-flex justify-content-md-end">

                                <button
                                    type="submit"
                                    class="btn btn--primary mr-2"
                                >
                                    <i class="las la-search"></i>
                                    @lang('Search')
                                </button>

                                @if(request('search'))
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


        {{-- Category Table --}}
        <div class="card b-radius--10">

            <div class="card-body p-0">

                <div class="table-responsive--sm table-responsive">

                    <table class="table table--light style--two">

                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Sub category')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Last Update')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($categorys as $category)

                                <tr>

                                    {{-- Name --}}
                                    <td data-label="@lang('Name')">

                                        <div class="user">

                                            <div class="thumb">
                                                <img
                                                    src="{{ getImage('assets/images/category/'.$category->image) }}"
                                                    alt="@lang('image')"
                                                >
                                            </div>

                                            <span class="name">
                                                {{ __($category->name) }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Sub Category --}}
                                    <td data-label="@lang('Sub category')">

                                        <span class="font-weight-bold">
                                            {{ $category->subCategory->count() }}
                                        </span>

                                    </td>


                                    {{-- Type --}}
                                    <td data-label="@lang('Type')">

                                        <span class="font-weight-bold">
                                            {{ __($category->type) }}
                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td data-label="@lang('Status')">

                                        @if($category->status == 1)

                                            <span class="badge badge--success">
                                                @lang('Enable')
                                            </span>

                                        @else

                                            <span class="badge badge--danger">
                                                @lang('Disabled')
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Last Update --}}
                                    <td data-label="@lang('Last Update')">

                                        {{ showDateTime($category->updated_at) }}

                                        <br>

                                        <small class="text-muted">
                                            {{ diffForHumans($category->updated_at) }}
                                        </small>

                                    </td>


                                    {{-- Action --}}
                                    <td data-label="@lang('Action')">

                                        <a
                                            href="javascript:void(0)"
                                            class="icon-btn btn--primary ml-1 updateCategory"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-type="{{ $category->type }}"
                                            data-status="{{ $category->status }}"
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
                                        <i class="las la-folder-open"
                                           style="font-size: 40px; display:block; margin-bottom:10px;">
                                        </i>

                                        {{ __($emptyMessage) }}
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Pagination --}}
            @if($categorys->hasPages())

                <div class="card-footer py-4">
                    {{ paginateLinks($categorys) }}
                </div>

            @endif

        </div>

    </div>
</div>


{{-- Add Category Modal --}}
<div id="addModal" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    @lang('Add Category')
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
                action="{{ route('admin.category.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="modal-body">

                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Name')
                        </label>

                        <input
                            type="text"
                            class="form-control form-control-lg"
                            name="name"
                            placeholder="@lang('Enter Name')"
                            maxlength="50"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Type')
                        </label>

                        <select class="form-control" name="type">

                            <option value="">
                                Select Category Type
                            </option>

                            <option value="job">
                                Job
                            </option>

                            <option value="service">
                                Service
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Status')
                        </label>

                        <input
                            type="checkbox"
                            data-width="100%"
                            data-onstyle="-success"
                            data-offstyle="-danger"
                            data-toggle="toggle"
                            data-on="@lang('Enable')"
                            data-off="@lang('Disabled')"
                            name="status"
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Image')
                        </label>

                        <div class="custom-file">

                            <input
                                type="file"
                                name="image"
                                class="custom-file-input"
                                id="customFileLangHTML"
                            >

                            <label
                                class="custom-file-label"
                                for="customFileLangHTML"
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


{{-- Update Category Modal --}}
<div id="updateCategoryModal" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    @lang('Update Category')
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
                action="{{ route('admin.category.update') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <input type="hidden" name="id">


                <div class="modal-body">

                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Name')
                        </label>

                        <input
                            type="text"
                            class="form-control form-control-lg"
                            name="name"
                            placeholder="@lang('Enter Name')"
                            maxlength="50"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Type')
                        </label>

                        <select class="form-control" name="type">

                            <option value="">
                                Select Category Type
                            </option>

                            <option value="job">
                                Job
                            </option>

                            <option value="service">
                                Service
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Status')
                        </label>

                        <input
                            type="checkbox"
                            data-width="100%"
                            data-onstyle="-success"
                            data-offstyle="-danger"
                            data-toggle="toggle"
                            data-on="@lang('Enable')"
                            data-off="@lang('Disabled')"
                            name="status"
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-control-label font-weight-bold">
                            @lang('Image')
                        </label>

                        <div class="custom-file">

                            <input
                                type="file"
                                name="image"
                                class="custom-file-input"
                                id="customFileLangHTML"
                            >

                            <label
                                class="custom-file-label"
                                for="customFileLangHTML"
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


@push('breadcrumb-plugins')

    <a
        href="javascript:void(0)"
        class="btn btn-sm btn--primary box--shadow1 text--small addCategory"
    >
        <i class="las la-plus"></i>
        @lang('Add Category')
    </a>

@endpush


@push('script')

<script>
    "use strict";

    $('.addCategory').on('click', function () {
        $('#addModal').modal('show');
    });


    $('.updateCategory').on('click', function () {

        var modal = $('#updateCategoryModal');

        modal.find('input[name=id]').val($(this).data('id'));

        modal.find('input[name=name]').val($(this).data('name'));

        modal.find('select[name=type]').val($(this).data('type'));

        var data = $(this).data('status');

        if (data == 1) {
            modal.find('input[name=status]').bootstrapToggle('on');
        } else {
            modal.find('input[name=status]').bootstrapToggle('off');
        }

        modal.modal('show');
    });


    $(document).on('change', '.custom-file-input', function () {

        var fileName = $(this).val().split("\\").pop();

        $(this)
            .siblings('.custom-file-label')
            .addClass('selected')
            .html(fileName);

    });

</script>

@endpush