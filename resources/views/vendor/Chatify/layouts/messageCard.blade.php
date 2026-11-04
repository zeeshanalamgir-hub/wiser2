<?php
$seenIcon = !!$seen ? 'check-double' : 'check';
$timeAndSeen =
    "<span data-time='$created_at' class='message-time'>
        " .
    ($isSender ? "<span class='fas fa-$seenIcon' seen'></span>" : '') .
    " <span class='time'>$timeAgo</span>
    </span>";
?>

<div class="d-flex p-2 message-card {{ $isSender ? 'mc-sender' : 'mc-receiver' }}" data-id="{{ $id }}">
    {{-- Delete Message Button --}}
    @if ($isSender)
    <div class="actions">
        <i class="fas fa-trash delete-btn" data-id="{{ $id }}"></i>
    </div>
@endif
    {{-- Card --}}

    <div class="message-card-content ms-1">
        @if (@$attachment->type != 'image' || $message)

            @if($message === 'blocked')
                <div class="alert alert-danger">
                    Contact info detected. Sharing email or phone numbers off-platform may lead to a TOS warning
                </div>
                <p class="float-end fs-12 text-3gray"> {!! $timeAndSeen !!}</p>
            @else
                <p class="fs-12 mb-1 ms-2 p-2 rounded {{ $isSender ? 'bg-light' : 'bg-lsgreen' }}">
                    
                    {!! $message == null && $attachment != null && @$attachment->type != 'file'
                        ? $attachment->title
                        : nl2br($message) !!}

                    {{-- If attachment is a file --}}
                    @if (@$attachment->type == 'file')
                        <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName' => $attachment->file]) }}"
                            class="file-download">
                            <span class="fas fa-file"></span> {{ $attachment->title }}
                        </a>
                    @endif

                </p>
                <p class="float-end fs-12 text-3gray"> {!! $timeAndSeen !!}</p>
            @endif

        @endif
        @if (@$attachment->type == 'image')
            <div class="image-wrapper" style="text-align: {{ $isSender ? 'end' : 'start' }}">
                <div class="image-file chat-image"
                    style="background-image: url('{{ Chatify::getAttachmentUrl($attachment->file) }}')">
                    <div>{{ $attachment->title }}</div>
                </div>
                <div style="margin-bottom:5px; display:flex; align-items:center; justify-content:{{ $isSender ? 'flex-end' : 'flex-start' }}; gap:8px;">
                    <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName' => $attachment->file]) }}"
                        title="Download {{ $attachment->title }}" style="font-size:12px; color:inherit;">
                        <span class="fas fa-download"></span>
                    </a>
                    {!! $timeAndSeen !!}
                </div>
            </div>
        @endif
    </div>

</div>
