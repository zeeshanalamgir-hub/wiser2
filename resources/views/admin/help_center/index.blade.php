@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Details Count')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Last Update')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($helpCenters as $item)
                            <tr>
                                <td>
                                    <span class="font-weight-bold">{{ $item->title }}</span>
                                </td>

                                <td>
                                    <span class="badge badge--info">
                                        {{ $item->details->count() }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge--primary">{{ $item->type }}</span>
                                </td>

                                <td>
                                    {{ showDateTime($item->updated_at) }} <br>
                                    {{ diffForHumans($item->updated_at) }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.help-center.edit', [
                                            'type'=>$type,
                                            'id'=>$item->id
                                        ]) }}"
                                       class="icon-btn btn--primary">
                                        <i class="las la-edit"></i>
                                    </a>

                                    <button class="icon-btn btn--danger deleteBtn"
                                            data-id="{{ $item->id }}"
                                            data-title="{{ $item->title }}">
                                        <i class="las la-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center text-muted">
                                    Help Center Not Found!
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer py-4">
                {{ paginateLinks($helpCenters) }}
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Create Button --}}
@if(
    in_array($type, ['seller', 'buyer']) 
    || (in_array($type, ['terms', 'policy']) && $helpCenters->count() == 0)
)
    @push('breadcrumb-plugins')
        <a href="{{ route('admin.help-center.create',['type'=>$type]) }}" class="btn btn--primary">
            <i class="las la-plus"></i> @lang('Add '.$createTitle)
        </a>
    @endpush
@endif

@push('script')
<script>
    "use strict";

    // Delete confirmation
    $('.deleteBtn').on('click', function () {
    let id = $(this).data('id');
    let title = $(this).data('title');
    let type = @json($type);

    let urlTemplate = "{{ route('admin.help-center.destroy', ['type' => ':type', 'id' => ':id']) }}";
    let url = urlTemplate
    .replace(':type', type)
    .replace(':id', id);


    Swal.fire({
        title: 'Delete Help Center',
        text: `Are you sure you want to delete "${title}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );

                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'Something went wrong. Please try again.',
                        'error'
                    );
                }
            });

        }
    });
});
</script>
@endpush