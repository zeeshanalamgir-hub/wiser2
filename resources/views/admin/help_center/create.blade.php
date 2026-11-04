@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="{{ route('admin.help-center.store',['type'=>$type]) }}" method="POST">
                        @csrf
                        <input type="hidden" name='type' value="{{ $type }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Title') <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="@lang('Enter title')" required>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label>@lang('Details')</label>

                            <div id="details-wrapper" class="accordion">

                                <div class="card detail-item mb-2" data-detail-id="0">
                                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                                        <button type="button" class="btn btn-link p-0 accordion-toggle font-weight-bold"
                                            data-toggle="collapse" data-target="#detail-body-0">
                                            <i class="las la-chevron-down mr-1"></i> <span class="accordion-title">@lang('Detail') #1</span>
                                        </button>
                                        @if($type == 'buyer' || $type == 'seller' || $type == 'terms')
                                            <button type="button" class="remove-detail btn btn-sm btn-danger">
                                                <i class="las la-trash"></i> @lang('Remove')
                                            </button>
                                        @endif
                                    </div>
                                    <div id="detail-body-0" class="collapse show" data-parent="#details-wrapper">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>@lang('Sub Title')</label>
                                                <input type="text" name="details[0][sub_title]" class="form-control"
                                                    placeholder="@lang('Enter sub title')">
                                            </div>
                                            <div class="form-group mb-0">
                                                <label>@lang('Description')</label>
                                                <textarea name="details[0][desc]" id="nic_desc_0"
                                                    class="form-control nicEdit" rows="4"
                                                    placeholder="@lang('Enter description')"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            @if($type == 'buyer' || $type == 'seller' || $type == 'terms')
                                <button type="button" class="btn btn--success addMoreBtn mt-2">
                                    <i class="las la-plus-circle"></i> @lang('Add More Detail')
                                </button>
                            @endif
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn--primary">
                                <i class="las la-save"></i> @lang('Save Help Center')
                            </button>
                            <a href="{{ route('admin.help-center.index', ['type'=>$type]) }}" class="btn btn--secondary">
                                @lang('Cancel')
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.help-center.index', ['type'=>$type]) }}" class="btn btn-sm btn--info float-right">
        <i class="las la-arrow-left"></i> @lang('Back to List')
    </a>
@endpush

@push('style')
    <style>
        #details-wrapper .card {
            border: 1px solid #e5e5e5;
        }
        #details-wrapper .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e5e5e5;
        }
        #details-wrapper .accordion-toggle {
            color: #333;
            text-decoration: none;
            font-size: 0.95rem;
        }
        #details-wrapper .accordion-toggle:hover {
            text-decoration: none;
            color: #000;
        }
        #details-wrapper .collapse.show ~ .card-header .la-chevron-down,
        #details-wrapper .card-header .la-chevron-down {
            transition: transform 0.2s;
        }
        #details-wrapper .collapsed .la-chevron-down {
            transform: rotate(-90deg);
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        let detailIndex = 1;
        let editorInstances = {};

        function initNicEditor($textarea) {
            if (!$textarea || $textarea.length === 0 || $textarea.hasClass('nic-initialized')) return;
            let editorId = $textarea.attr('id');
            if (!editorId) return;

            $textarea.addClass('nic-initialized');
            let editor = new nicEditor({ fullPanel: true });
            editor.panelInstance(editorId);
            editorInstances[editorId] = editor;

            // Prevent scroll chaining when the user scrolls past multiple open editors
            setTimeout(function() {
                $textarea.closest('.card').find('iframe').each(function() {
                    try {
                        let doc = this.contentDocument || this.contentWindow.document;
                        if (doc && doc.body) doc.body.style.overscrollBehavior = 'contain';
                    } catch(e) {}
                });
            }, 300);
        }

        // Lazy-init nicEditor only when the user clicks a textarea to type
        $(document).on('click', '#details-wrapper .nicEdit:not(.nic-initialized)', function() {
            initNicEditor($(this));
        });

        // Add More — appends a collapsed accordion card (no nicEditor init, no scroll)
        $('.addMoreBtn').click(function() {
            let idx = detailIndex;
            let collapseId = 'detail-body-' + idx;
            let editorId   = 'nic_desc_' + idx;

            let html = `
                <div class="card detail-item mb-2" data-detail-id="${idx}">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <button type="button" class="btn btn-link p-0 accordion-toggle collapsed font-weight-bold"
                            data-toggle="collapse" data-target="#${collapseId}">
                            <i class="las la-chevron-down mr-1"></i> <span class="accordion-title">@lang('Detail') #${idx + 1}</span>
                        </button>
                        <button type="button" class="remove-detail btn btn-sm btn-danger">
                            <i class="las la-trash"></i> @lang('Remove')
                        </button>
                    </div>
                    <div id="${collapseId}" class="collapse" data-parent="#details-wrapper">
                        <div class="card-body">
                            <div class="form-group">
                                <label>@lang('Sub Title')</label>
                                <input type="text" name="details[${idx}][sub_title]" class="form-control"
                                    placeholder="@lang('Enter sub title')">
                            </div>
                            <div class="form-group mb-0">
                                <label>@lang('Description')</label>
                                <textarea name="details[${idx}][desc]" id="${editorId}"
                                    class="form-control nicEdit" rows="4"
                                    placeholder="@lang('Enter description')"></textarea>
                            </div>
                        </div>
                    </div>
                </div>`;

            $('#details-wrapper').append(html);
            detailIndex++;
        });

        // Remove with confirmation
        $(document).on('click', '.remove-detail', function(e) {
            e.preventDefault();
            let $item = $(this).closest('.detail-item');

            if ($('.detail-item').length === 1) {
                Swal.fire({
                    icon: 'warning',
                    title: '@lang("Cannot Remove")',
                    text: '@lang("You must have at least one detail item")',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            Swal.fire({
                title: '@lang("Remove Detail")',
                text: '@lang("Are you sure you want to remove this detail?")',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '@lang("Yes, remove it!")'
            }).then(function(result) {
                if (result.isConfirmed) {
                    let editorId = $item.find('.nicEdit').attr('id');
                    if (editorId && editorInstances[editorId]) {
                        delete editorInstances[editorId];
                    }
                    $item.remove();
                    Swal.fire('@lang("Removed!")', '@lang("Detail has been removed.")', 'success');
                }
            });
        });

        // Update accordion title in real-time as user types in Sub Title
        $(document).on('input', '#details-wrapper input[name*="[sub_title]"]', function() {
            let val = $(this).val().trim();
            let $card = $(this).closest('.detail-item');
            let idx   = $card.data('detail-id');
            let fallback = '@lang("Detail") #' + (parseInt(idx) + 1);
            $card.find('.accordion-title').text(val || fallback);
        });

        // Sync nicEditor content back to textareas before submit
        $('form').on('submit', function() {
            for (let editorId in editorInstances) {
                if (editorInstances.hasOwnProperty(editorId)) {
                    try {
                        $('#' + editorId).val(editorInstances[editorId].getContent());
                    } catch(e) {}
                }
            }
            return true;
        });
    </script>
@endpush
