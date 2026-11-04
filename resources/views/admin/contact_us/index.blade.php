@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S/L')</th>
                                    <th>@lang('Full Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Company')</th>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Message')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contactUs as $contact)
                                    <tr>
                                        <td data-label="@lang('#')">{{ $loop->iteration }}</td>
                                        <td data-label="@lang('Full Name')">
                                            <span class="font-weight-bold">{{ $contact->fullname }}</span>
                                        </td>
                                        <td data-label="@lang('Email')">
                                            <a href="mailto:{{ $contact->email }}" class="text--primary">
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td data-label="@lang('Company')">
                                            {{ $contact->company ?? 'N/A' }}
                                        </td>
                                        <td data-label="@lang('Subject')">
                                            <span class="text--primary">{{ Str::limit($contact->subject, 30) }}</span>
                                        </td>
                                        <td data-label="@lang('Message')">
                                            <span class="text-muted">{{ Str::limit($contact->message, 50) }}</span>
                                            @if(strlen($contact->message) > 50)
                                                <button class="btn-link text--primary border-0 bg-transparent view-message" 
                                                        data-id="{{ $contact->id }}"
                                                        data-fullname="{{ $contact->fullname }}"
                                                        data-email="{{ $contact->email }}"
                                                        data-company="{{ $contact->company }}"
                                                        data-subject="{{ $contact->subject }}"
                                                        data-message="{{ $contact->message }}"
                                                        data-date="{{ showDateTime($contact->created_at) }}">
                                                    @lang('Read More')
                                                </button>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Date')">
                                            <span class="font-weight-bold">{{ showDateTime($contact->created_at) }}</span>
                                            <br>
                                            {{ diffForHumans($contact->created_at) }}
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <button class="icon-btn btn--primary view-message"
                                                    data-id="{{ $contact->id }}"
                                                    data-fullname="{{ $contact->fullname }}"
                                                    data-email="{{ $contact->email }}"
                                                    data-company="{{ $contact->company }}"
                                                    data-subject="{{ $contact->subject }}"
                                                    data-message="{{ $contact->message }}"
                                                    data-date="{{ showDateTime($contact->created_at) }}"
                                                    title="@lang('View Details')">
                                                <i class="las la-eye"></i>
                                            </button>
                                            
                                            <button class="icon-btn btn--danger delete-btn"
                                                    data-id="{{ $contact->id }}"
                                                    data-fullname="{{ $contact->fullname }}"
                                                    title="@lang('Delete')">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-muted text-center">{{ $emptyMessage }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($contactUs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($contactUs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    
    <!-- View Message Modal -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Message Details')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">@lang('Full Name'):</label>
                                <p class="fullname-display"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">@lang('Email'):</label>
                                <p class="email-display"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">@lang('Company'):</label>
                                <p class="company-display"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">@lang('Date'):</label>
                                <p class="date-display"></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">@lang('Subject'):</label>
                                <p class="subject-display"></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold text-black">@lang('Message'):</label>
                                <div class="message-display p-3 bg-light rounded text-black"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">
                        @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Delete Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure you want to delete this message from') <span class="font-weight-bold delete-fullname"></span>?</p>
                </div>
                <div class="modal-footer">
                    <form action="" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn--secondary" data-dismiss="modal">
                            @lang('Cancel')
                        </button>
                        <button type="submit" class="btn btn--danger">
                            <i class="las la-trash"></i> @lang('Delete')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{ route('admin.contact.us.index') }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Search by name, email, subject')" 
                   value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    
@endpush

@push('style')
    <style>
        .bulk-action-bar {
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .bulk-action-bar.show {
            display: block !important;
        }
        .message-display {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 300px;
            overflow-y: auto;
            color: #5b6e88;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            
            $('.view-message').on('click', function() {
                var modal = $('#viewMessageModal');
                
                modal.find('.fullname-display').text($(this).data('fullname'));
                modal.find('.email-display').text($(this).data('email'));
                modal.find('.company-display').text($(this).data('company') || 'N/A');
                modal.find('.subject-display').text($(this).data('subject'));
                modal.find('.message-display').html($(this).data('message').replace(/\n/g, '<br>'));
                modal.find('.date-display').text($(this).data('date'));
                
                modal.modal('show');
            });
            
            $('.delete-btn').on('click', function() {
                var modal = $('#deleteModal');
                var id = $(this).data('id');
                var fullname = $(this).data('fullname');
                
                modal.find('.delete-fullname').text(fullname);
                modal.find('#deleteForm').attr('action', '{{ route("admin.contact.us.destroy", "") }}/' + id);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush