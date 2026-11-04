@foreach ($adminNotifications as $notification)
    <a href="{{ route('admin.notification.read', $notification->id) }}" class="dropdown-menu__item">
        <div class="navbar-notifi">
            <div class="navbar-notifi__left bg--green b-radius--rounded">
                <img src="{{ $notification?->user?->image ? Storage::url($notification?->user?->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}" alt="@lang('Profile Image')">
            </div>
            <div class="navbar-notifi__right">
                <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </a>
@endforeach
