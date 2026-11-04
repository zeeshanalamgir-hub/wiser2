@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="row mt-2">
        <div class="col-12">
            <h5 class="text-dgreen2">@lang('Ticket Details')</h5>
            <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                <h4 class="card-title text-lowercase mb-0">
                    @if ($my_ticket->status == 0)
                        <span class="badge bg-success text-capitalize">@lang('Open')</span>
                    @elseif($my_ticket->status == 1)
                        <span class="badge bg-primary text-capitalize">@lang('Answered')</span>
                    @elseif($my_ticket->status == 2)
                        <span class="badge bg-warning text-capitalize">@lang('Replied')</span>
                    @elseif($my_ticket->status == 3)
                        <span class="badge bg-danger text-capitalize">@lang('Closed')</span>
                    @endif [@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}
                </h4>

                @if ($my_ticket->status != 3)
                    <form action="{{ route('ticket.reply', $my_ticket->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="replayTicket" value="1">
                        <div class="mb-3">
                            <label for="message" class="form-label">@lang('Your Reply')</label>
                            <textarea class="form-control" name="message" rows="5" placeholder="@lang('Enter your reply')" required></textarea>
                        </div>


                        <div class="mb-3">
                            <label for="inputAttachments" class="form-label">@lang('Attachments')</label>
                            <input class="form-control" type="file" name="attachments[]" id="inputAttachments" multiple
                                aria-describedby="fileHelp">
                            <p class="form-text">@lang('Allowed File Extensions'): .jpg, .jpeg, .png, .doc, .docx</p>
                        </div>

                        <div id="fileUploadsContainer"></div>
                        <button type="button" class="btn btn-outline-secondary addFile">@lang('Add Another File')</button>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">@lang('Submit Reply')</button>
                        </div>
                    </form>
                @endif


                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="text-dgreen2"> <span class="fa fa-history"></span> @lang('Conversation History')</h5>

                        @forelse($messages as $msg)
                            <div class="card shadow-sm mb-3 p-3" style="border:1px solid #e2e2e2">

                                {{-- Message Body --}}
                                <div class="mb-2">
                                    {{ $msg->message }}
                                </div>

                                {{-- Attachments --}}
                                @if (isset($attachments[$msg->id]))
                                    <div class="mt-2">
                                        <strong>@lang('Attachments'):</strong>
                                        <div class="d-flex flex-wrap mt-2">
                                            @foreach ($attachments[$msg->id] as $file)
                                                @php
                                                    $ext = strtolower(pathinfo($file->attachment, PATHINFO_EXTENSION));
                                                @endphp

                                                <div class="me-3 mb-2 text-center">

                                                    {{-- Image Preview --}}
                                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                                        <a href="{{ asset($file->attachment) }}" target="_blank">
                                                            <img src="{{ asset($file->attachment) }}" width="100"
                                                                class="img-thumbnail">
                                                        </a>
                                                    @else
                                                        {{-- File Link --}}
                                                        <a href="{{ asset($file->attachment) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary">
                                                            {{ basename($file->attachment) }}
                                                        </a>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <p class="text-muted">@lang('No messages found')</p>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Close Ticket')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('ticket.reply', $my_ticket->id) }}">
                    @csrf
                    <input type="hidden" name="replayTicket" value="2">
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to close this support ticket?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-danger">@lang('Close Ticket')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on("change", ".custom-file-input", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $('.addFile').on('click', function() {
            $("#fileUploadsContainer").append(`
            <div class="input-group my-2">
                <input type="file" class="form-control" name="attachments[]">
                <button type="button" class="btn btn-danger remove-btn">@lang('Remove')</button>
            </div>
        `);
        });

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.input-group').remove();
        });
    </script>
@endsection
