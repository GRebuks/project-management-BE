<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewNotification;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function sendFriendRequest(User $user) {
        auth()->user()->friends()->attach($user, ['status' => 'pending']);
        $user->notify(new NewNotification("Friend request received", "{$user->username} has sent you a friend request."));
    }

    public function acceptFriendRequest(User $user) {
        auth()->user()->friends()->updateExistingPivot($user, ['status' => 'accepted']);
        $user->notify(new NewNotification("Friend request accepted", "{$user->username} has accepted your friend request."));
    }

    public function rejectFriendRequest(User $user) {
        auth()->user()->friends()->detach($user);
        $user->notify(new NewNotification("Friend request rejected", "{$user->username} has rejected your friend request."));
    }

    public function breakFriendRequest(User $user) {
        auth()->user()->friends()->detach($user);
        $user->notify(new NewNotification("Friend terminated", "{$user->username} is no longer your friend."));
    }

    public function markNotificationsAsRead()
    {
        auth()->user()->unreadNotifications()->markAsRead();
    }
}
