@extends('admin.layouts.app')
@section('panel')
    <div class="notify__area">
    	@foreach($notifications as $notification)
        @php
            $notificationData = json_decode($notification->data);
        @endphp
        <a class="notify__item @if($notification['read_at'] == null) unread--notification @endif" href="{{ route('admin.notification.read',$notification['id']) }}">
            <div class="notify__thumb bg--primary">
                <img src="https://cdn-icons-png.freepik.com/512/7235/7235981.png">
            </div>
            <div class="notify__content">
                <h6 class="title">{{ __($notificationData->message) }}</h6>
                <span class="date"><i class="las la-clock"></i> {{ \Carbon\Carbon::parse($notificationData->created_at)->diffForHumans() }}</span>
            </div>
        </a>
        @endforeach
        {{ paginateLinks($notifications) }}
    </div>
@endsection
@push('breadcrumb-plugins')
<a href="{{ route('admin.notifications.readAll') }}" class="btn btn--primary">@lang('Mark all as read')</a>
@endpush