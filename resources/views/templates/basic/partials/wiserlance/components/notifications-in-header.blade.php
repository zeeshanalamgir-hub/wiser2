
@php
    $notis = DB::table('notifications')
        ->where('reciever_id', auth()->user()->id)
        ->orderBy('id', 'desc')
        ->limit(3)
        ->get();
@endphp

<div class="dropdown js-dropdown" >
    <a class="popup-title ntn-notification-link-67896" href="javascript:void(0)" data-toggle="dropdown" title="notifications" aria-label="notifications dropdown">

    </a>
    <ul class="dropdown-menu popup-content link dynamic-notification-list-67895675 p-5px " style="height: auto; width: 275px; background-color: aliceblue; margin-top: 10px;box-sizing: border-box; border-radius: 10px; ">

        @if(isset($notis))
            @foreach ($notis as $noti)
                @php
                    // Assuming $noti->message contains the notification message
                    $message = $noti->message;
                @endphp
                <li class="current single-dm-notification-67895674" >
                    <a href="{{ $noti->click_url }}" class="dropdown-item medium text-medium">
                        <span style="text-wrap: wrap;">{{ $message }}</span>
                    </a>
                </li>
            @endforeach
        @endif

        <hr>
        <li class="current"><a href="{{ url('/all-notifications') }}"
                class="dropdown-item medium text-medium"><span>@lang('View All')</span></a>
        </li>
    </ul>
</div>
