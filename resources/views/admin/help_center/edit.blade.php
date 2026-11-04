@extends('admin.layouts.app')

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

        #details-wrapper .card-header .la-chevron-down {
            transition: transform 0.2s;
        }

        #details-wrapper .collapsed .la-chevron-down {
            transform: rotate(-90deg);
        }
    </style>
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body">

                    <form
                        action="{{ route('admin.help-center.update', [
                            'type' => $type,
                            'id' => $helpCenter->id,
                        ]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="type" value="{{ $type }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Title') <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ old('title', $helpCenter->title) }}" placeholder="@lang('Enter title')"
                                        required>
                                </div>
                            </div>
                        </div>

                        {{-- DETAILS --}}
                        <div class="form-group">
                            <label>@lang('Details')</label>

                            <div id="details-wrapper" class="accordion">

                                @php $detailIndex = 0; @endphp

                                @foreach ($helpCenter->details as $detail)
                                    <div class="card detail-item mb-2" data-detail-id="{{ $detailIndex }}">
                                        <div class="card-header d-flex justify-content-between align-items-center py-2">

                                            <button type="button"
                                                class="btn btn-link p-0 accordion-toggle font-weight-bold"
                                                data-toggle="collapse" data-target="#detail-body-{{ $detailIndex }}">

                                                <i class="las la-chevron-down mr-1"></i>
                                                <span class="accordion-title">
                                                    {{ $detail->sub_title ?? __('Detail') . ' #' . ($detailIndex + 1) }}
                                                </span>
                                            </button>
                                            @if ($type == 'buyer' || $type == 'seller' || $type == 'terms')
                                                <button type="button" class="remove-detail btn btn-sm btn-danger">
                                                    <i class="las la-trash"></i> @lang('Remove')
                                                </button>
                                            @endif
                                        </div>

                                        <div id="detail-body-{{ $detailIndex }}"
                                            class="collapse {{ $detailIndex == 0 ? 'show' : '' }}"
                                            data-parent="#details-wrapper">

                                            <div class="card-body">

                                                <input type="hidden" name="details[{{ $detailIndex }}][id]"
                                                    value="{{ $detail->id }}">

                                                <div class="form-group">
                                                    <label>@lang('Sub Title')</label>
                                                    <input type="text" name="details[{{ $detailIndex }}][sub_title]"
                                                        value="{{ $detail->sub_title }}" class="form-control"
                                                        placeholder="@lang('Enter sub title')">
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label>@lang('Description')</label>
                                                    <textarea name="details[{{ $detailIndex }}][desc]" id="nic_desc_{{ $detailIndex }}" class="form-control nic-lazy"
                                                        rows="4">{{ $detail->desc }}</textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    @php $detailIndex++; @endphp
                                @endforeach

                            </div>
                            @if ($type == 'buyer' || $type == 'seller' || $type == 'terms')
                                <button type="button" class="btn btn--success addMoreBtn mt-2">
                                    <i class="las la-plus-circle"></i> @lang('Add More Detail')
                                </button>
                            @endif
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn--primary">
                                <i class="las la-save"></i> @lang('Update Help Center')
                            </button>

                            <a href="{{ route('admin.help-center.index', ['type' => $type]) }}" class="btn btn--secondary">
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
    <a href="{{ route('admin.help-center.index', ['type' => $type]) }}" class="btn btn-sm btn--info float-right">
        <i class="las la-arrow-left"></i> @lang('Back to List')
    </a>
@endpush


@push('script')
    <script>
        "use strict";

        let detailIndex = {{ count($helpCenter->details) }};

        // Track which textarea IDs have been initialized
        let initializedEditors = {};

        function initNicEditor(id) {
            if (initializedEditors[id]) return;
            initializedEditors[id] = true;
            new nicEditor({
                fullPanel: true
            }).panelInstance(id);
        }

        function fixEditorWidth($collapsePanel) {
            setTimeout(function() {
                let $cardBody = $collapsePanel.find('.card-body');
                let w = $cardBody.width();
                if (!w) return;

                $collapsePanel.find('.nicEdit-panelContain').css('width', w + 'px');
                $collapsePanel.find('.nicEdit-main').css('width', w + 'px');
                $collapsePanel.find('.nicEdit-main iframe').css('width', '100%');
            }, 50);
        }

        // On load: init only the open panel's editor (avoids hidden-container width bug)
        bkLib.onDomLoaded(function() {
            $('.collapse.show').each(function() {
                let $panel = $(this);
                $panel.find('.nic-lazy').each(function() {
                    initNicEditor($(this).attr('id'));
                });
                fixEditorWidth($panel);
            });
        });

        // When a panel opens: init its editor if needed, then fix width
        $(document).on('shown.bs.collapse', '#details-wrapper .collapse', function() {
            let $panel = $(this);
            $panel.find('.nic-lazy').each(function() {
                initNicEditor($(this).attr('id'));
            });
            fixEditorWidth($panel);
        });

        // Accordion title live update
        $(document).on('input keyup', '#details-wrapper .detail-item input[name*="[sub_title]"]', function() {
            let val = $(this).val().trim();
            let $card = $(this).closest('.detail-item');
            let idx = $card.data('detail-id');
            $card.find('.accordion-title').text(val || 'Detail #' + (parseInt(idx) + 1));
        });

        // Add more detail — appends a collapsed panel; editor init deferred to shown.bs.collapse
        $('.addMoreBtn').click(function() {
            let idx = detailIndex;
            let collapseId = 'detail-body-' + idx;
            let editorId = 'nic_desc_' + idx;

            let html = `
    <div class="card detail-item mb-2" data-detail-id="${idx}">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <button type="button"
                class="btn btn-link p-0 accordion-toggle collapsed font-weight-bold"
                data-toggle="collapse"
                data-target="#${collapseId}">
                <i class="las la-chevron-down mr-1"></i>
                <span class="accordion-title">Detail #${idx + 1}</span>
            </button>
            <button type="button" class="remove-detail btn btn-sm btn-danger">
                <i class="las la-trash"></i> Remove
            </button>
        </div>
        <div id="${collapseId}" class="collapse" data-parent="#details-wrapper">
            <div class="card-body">
                <input type="hidden" name="details[${idx}][id]" value="">
                <div class="form-group">
                    <label>Sub Title</label>
                    <input type="text" name="details[${idx}][sub_title]" class="form-control">
                </div>
                <div class="form-group mb-0">
                    <label>Description</label>
                    <textarea name="details[${idx}][desc]" id="${editorId}"
                        class="form-control nic-lazy"></textarea>
                </div>
            </div>
        </div>
    </div>`;

            $('#details-wrapper').append(html);
            // Open the new panel — shown.bs.collapse will init the editor
            $('#' + collapseId).collapse('show');
            detailIndex++;
        });

        // Remove detail
        $(document).on('click', '.remove-detail', function() {
            let $item = $(this).closest('.detail-item');
            if ($('.detail-item').length === 1) {
                alert('At least one detail required');
                return;
            }
            $item.remove();
        });

        // Sync all nicEditors to their textareas before submit
        $('form').on('submit', function() {
            $('.nic-lazy').each(function() {
                let id = $(this).attr('id');
                try {
                    let inst = nicEditors.findEditor(id);
                    if (inst) $(this).val(inst.getContent());
                } catch (e) {}
            });
            return true;
        });
    </script>
@endpush
