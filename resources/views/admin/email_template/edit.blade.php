@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <form action="{{ route('admin.email.template.update', $email_template->id) }}" method="POST">
            @csrf
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive table-responsive--sm">
                        <table class="table align-items-center table--light" id="shortcodesTable">
                            <thead>
                                <tr>
                                    <th>@lang('Short Code')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            @php
                                $shortcodes = is_string($email_template->shortcodes) ? json_decode($email_template->shortcodes, true) : $email_template->shortcodes;
                            @endphp
                            <tbody class="list">
                                @forelse($shortcodes as $shortcode => $key)
                                    <tr>
                                        <td data-label="@lang('Short Code')">
                                            <input type="text" name="shortcodes[{{ $loop->index }}][shortcode]" class="form-control shortcode-input" value="{{ $shortcode }}" placeholder="@lang('Enter shortcode')" >
                                        </td>
                                        <td data-label="@lang('Description')">
                                            <input type="text" name="shortcodes[{{ $loop->index }}][description]" class="form-control description-input" value="{{ $key }}" placeholder="@lang('Enter description')">
                                        </td>
                                        <td data-label="@lang('Actions')">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">@lang('Remove')</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-muted text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-primary mt-3" id="addRow">@lang('Add New Shortcode')</button>
                        {{-- <button type="submit" class="btn btn-sm btn-success mt-3">@lang('Save Changes')</button> --}}
                    </div>
                </div>
            </div><!-- card end -->
        </div>

        <div class="col-md-12">
            <div class="card mt-5">
                <div class="card-header bg--dark">
                    <h5 class="card-title text-white">{{ __($pageTitle) }}</h5>
                </div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label class="font-weight-bold">@lang('Act') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" placeholder="@lang('Act')" name="act" value="{{ $email_template->act }}"/>
                            </div>
                            <div class="form-group col-md-8">
                                <label class="font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" placeholder="@lang('Template Name')" name="name" value="{{ $email_template->name }}"/>
                            </div>
                            <div class="form-group col-md-8">
                                <label class="font-weight-bold">@lang('Subject') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" placeholder="@lang('Email subject')" name="subject" value="{{ $email_template->subj }}"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Status') <span class="text-danger">*</span></label>
                                <input type="checkbox" data-height="46px" data-width="100%" data-onstyle="-success"
                                       data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Send Email')"
                                       data-off="@lang("Don't Send")" name="email_status"
                                       @if($email_template->email_status) checked @endif>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Message') <span class="text-danger">*</span></label>
                                <textarea name="email_body" rows="10" class="form-control nicEdit" placeholder="@lang('Your message using shortcodes')">{{ $email_template->email_body }}</textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Notification Body') <span class="text-danger">*</span></label>
                                <textarea name="notification_body" rows="10" class="form-control nicEdit" placeholder="@lang('Your message using shortcodes')">{{ $email_template->notification_body }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-block btn--primary mr-2">@lang('Submit')</button>
                    </div>

            </div>
        </div>
    </form>
    </div>
@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.email.template.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="fa fa-fw fa-backward"></i> @lang('Go Back') </a>
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const table = document.getElementById('shortcodesTable').querySelector('tbody');
        let rowIndex = table.querySelectorAll('tr').length; // Track the current number of rows

        // Add new row
        document.getElementById('addRow').addEventListener('click', () => {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td data-label="@lang('Short Code')">
                    <input type="text" name="shortcodes[${rowIndex}][shortcode]" class="form-control shortcode-input" placeholder="@lang('Enter shortcode')" required>
                </td>
                <td data-label="@lang('Description')">
                    <input type="text" name="shortcodes[${rowIndex}][description]" class="form-control description-input" placeholder="@lang('Enter description')" required>
                </td>
                <td data-label="@lang('Actions')">
                    <button type="button" class="btn btn-sm btn-danger remove-row">@lang('Remove')</button>
                </td>
            `;
            table.appendChild(newRow);
            rowIndex++; // Increment the row index for the next addition
        });

        // Remove row
        table.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-row')) {
                const row = event.target.closest('tr');
                row.remove();
            }
        });
    });
</script>
@endpush
