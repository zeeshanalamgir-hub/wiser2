@extends('admin.layouts.app')

@section('panel')

<div class="row">

    <div class="col-lg-12">


        {{-- Filter --}}
        <div class="card b-radius--10 mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">
                    <i class="las la-filter"></i>
                    @lang('Filter Tiers')
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
                        <div class="col-md-5">

                            <div class="form-group mb-md-0">

                                <label class="form-control-label font-weight-bold">
                                    @lang('Name')
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
                                        placeholder="@lang('Search tier by name...')"
                                        value="{{ request('search') }}"
                                        autocomplete="off"
                                    >

                                </div>

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


        {{-- Tiers Table --}}
        <div class="card b-radius--10">

            <div class="card-body p-0">

                <div class="table-responsive--md table-responsive">

                    <table class="table table--light style--two">

                        <thead>

                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Sub-Category')</th>
                                <th>@lang('Child-Category')</th>
                                <th>@lang('Action')</th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse($tiers as $tier)

                                <tr>

                                    <td data-label="@lang('Name')">

                                        <span class="font-weight-bold">
                                            {{ $tier->name }}
                                        </span>

                                    </td>


                                    <td data-label="@lang('Type')">
                                        {{ $tier->type }}
                                    </td>


                                    <td data-label="@lang('Category')">
                                        {{ $tier->cat_id }}
                                    </td>


                                    <td data-label="@lang('Sub-Category')">
                                        {{ $tier->subcat_id }}
                                    </td>


                                    <td data-label="@lang('Child-Category')">
                                        {{ $tier->childcat_id }}
                                    </td>


                                    <td data-label="@lang('Action')">

                                        <a
                                            href="{{ route('admin.frontend.tier.edit', $tier->id) }}"
                                            class="icon-btn"
                                            data-toggle="tooltip"
                                            title="@lang('Details')"
                                        >
                                            <i class="las la-edit text--shadow"></i>
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
            @if($tiers->hasPages())

                <div class="card-footer py-4">

                    {{ paginateLinks($tiers) }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


@push('breadcrumb-plugins')

<a
    href="{{ route('admin.frontend.addtiers') }}"
    class="btn btn-sm btn--primary box--shadow1 text--small"
>
    <i class="las la-plus"></i>
    @lang('Add New Tier')
</a>

@endpush
