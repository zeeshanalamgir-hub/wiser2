 @extends('admin.layouts.app')
@section('panel')

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($langs as $lang)
                            <tr>
                                <td data-label="@lang('Name')">
                                    <span class="font-weight-bold">{{$lang->name}}</span>
                                   
                                </td>


                               

                                 <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.frontend.language.edit', $lang->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                        <i class="las la-edit text--shadow"></i>
                                    </a>
                                </td>
                                
                                
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($langs) }}
                </div>
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
<a href="{{route('admin.frontend.addlanguages')}}" class="icon-btn" data-toggle="tooltip" title="" >
                                        <i class="las la-plus text--shadow"></i> Add New Language
                                    </a>
@endpush
