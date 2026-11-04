{{-- -------------------- Saved Messages -------------------- --}}
@if ($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="saved-messages avatar av-m">
                    <span class="far fa-bookmark"></span>
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Saved Messages <span>You</span></p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Contact list -------------------- --}}
@if ($get == 'users' && !!$lastMessage)
    <?php
    $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
    $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8') . '..' : $lastMessageBody;
    ?>
    @if ($is_first)
        {{-- <a class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button"
            role="tab" aria-controls="contact-tab-pane" aria-selected="false" href="{{ route('user', $user->id) }}"> --}}
            <div id="chatBoxLive" class="bg-white p-2 messenger-list-item" data-contact="{{ $user->id }}">
                <span class="float-end fs-10 pe-3 contact-item-time" data-time="{{$lastMessage->created_at}}">{{ $lastMessage->timeAgo }}</span>
                <div class="d-flex p-2" style="width:180px;">
                    @if($user->active_status)
                        <span class="activeStatus"></span>
                    @endif
                    @if(!is_null($user?->image))
                        <img src="{{ Storage::url($user->image) }}" alt="@lang('Profile Image')" class="rounded-circle me-2" width="35"
                        height="35">
                    @else
                        <div class="rounded-circle border border-secondary text-white bg-dgreen d-flex justify-content-center align-items-center fw-bold"
                            style="height:35px;width:38px;font-size:12px">
                                {{ strtoupper(substr($user->firstname ?? 'A', 0, 1)) }} {{ strtoupper(substr($user->lastname ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                    <div class="ps-2">
                        <p data-id="{{ $user->id }}" data-type="user" data-messenger = "{{ $user->name }}" class="messengers mb-0 fw-bold fs-14 text-green">{{ $user->name }}</p>
                        <p class="fs-10 text-secondary mb-0"> {{-- Last Message user indicator --}}
                            {!!
                                $lastMessage->from_id == Auth::user()->id
                                ? '<span class="lastMessageIndicator">You :</span>'
                                : ''
                            !!}
                            {{-- Last message body --}}
                            @if($lastMessage->attachment == null)
                            {!!
                                $lastMessageBody
                            !!}
                            @else
                            <span class="fas fa-file"></span> Attachment
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        {{-- </a> --}}
    @else
        <div id="chatBoxLive" class="p-2 messenger-list-item" data-contact="{{ $user->id }}">
            <p class="float-end fs-10 contact-item-time" data-time="{{$lastMessage->created_at}}">{{ $lastMessage->timeAgo }}</p>
            <div class="d-flex p-2">
                <img src="{{ $user->avatar }}" alt="@lang('Profile Image')" class="rounded-circle me-2" width="35"
                    height="35">
                <div class="ps-2">
                    <p data-id="{{ $user->id }}" data-type="user" class="mb-0 fw-bold fs-14">{{ $user->username }}</p>
                    <p class="fs-10 text-secondary mb-0">{{-- Last Message user indicator --}}
                        {!!
                            $lastMessage->from_id == Auth::user()->id
                            ? '<span class="lastMessageIndicator">You :</span>'
                            : ''
                        !!}
                        {{-- Last message body --}}
                        @if($lastMessage->attachment == null)
                        {!!
                            $lastMessageBody
                        !!}
                        @else
                        <span class="fas fa-file"></span> Attachment
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif
@endif

{{-- -------------------- Search Item -------------------- --}}
@if ($get == 'search_item')
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="avatar av-m" style="background-image: url('{{ $user->avatar }}');">
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ $user->id }}" data-type="user">
                    {{ strlen($user->name) > 12 ? trim(substr($user->name, 0, 12)) . '..' : $user->name }}
            </td>

        </tr>
    </table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if ($get == 'sharedPhoto')
    <div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif
