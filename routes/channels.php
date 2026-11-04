<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('Authorizing private channel', [
        'auth_user_id' => $user->id,
        'requested_channel_id' => $id,
    ]);

    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.Admin.{id}', function ($user, $id) {
    Log::info('Admin channel authorization check', [
        'auth_user' => $user ? get_class($user) : null,
        'auth_user_id' => $user->id ?? null,
        'requested_admin_channel_id' => $id,
    ]);

    return true;
},['guards' => ['admin']]);

Broadcast::channel('notification-seven.{userId}', function ($user, $userId) {
    // Check if the user is authenticated with the 'admin' guard
    $isAuthenticated = auth()->guard('admin')->check();
    $authUserId = auth()->guard('admin')->id();

    // Log for debugging
    Log::info('Admin channel authorization check', [
        'auth_user' => $user ? get_class($user) : null,
        'auth_user_id' => $authUserId,
        'requested_user_id' => $userId,
        'is_authenticated' => $isAuthenticated,
    ]);

    // Authorize only if the user is authenticated and their ID matches the requested channel ID
    return $isAuthenticated && (int) $authUserId === (int) $userId;
});

Broadcast::channel('admin.notifications', function () {
    return auth()->guard('admin')->check();
});
