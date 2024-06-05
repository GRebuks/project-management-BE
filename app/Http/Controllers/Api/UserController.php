<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PreferenceResource;
use App\Http\Resources\UserSearchResource;
use App\Http\Resources\WorkspaceUserResource;
use App\Models\User;
use App\Models\Workspace;
use App\Notifications\NewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $users = User::where('username', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->get(['id', 'username', 'email']);

        return response()->json($users);
    }

    public function searchAllExceptLoggedIn(Request $request)
    {
        $query = $request->input('q');
        $loggedInUserId = Auth::id();

        if (!$query) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', $loggedInUserId)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('username', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->get();

        return UserSearchResource::collection($users);
    }

    // Search users participating in a specific workspace
    public function searchWorkspaceParticipants(Workspace $workspace, Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return WorkspaceUserResource::collection($workspace->users()->get(['id', 'username', 'email']));
        }

        $users = $workspace->users()->get(['id', 'username', 'email']);

        return WorkspaceUserResource::collection($users->map(function ($user) use ($workspace) {
            return new WorkspaceUserResource($user, $workspace->id);
        }));
    }

    // Search users participating in a specific workspace excluding the logged-in user
    public function searchWorkspaceParticipantsExcludingLoggedIn(Workspace $workspace, Request $request)
    {
        $query = $request->input('q');
        $loggedInUserId = Auth::id();

        if (!$query) {
            return response()->json([]);
        }

        $users = $workspace->users()
            ->where('users.id', '!=', $loggedInUserId)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('username', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->get(['id', 'username', 'email']);

        return WorkspaceUserResource::collection($users->map(function ($user) use ($workspace) {
            return new WorkspaceUserResource($user, $workspace->id);
        }));
    }

    // Search all except logged in and workspace participants
    public function searchAllExceptLoggedInAndWorkspaceParticipants(Workspace $workspace, Request $request)
    {
        $query = $request->input('q');
        $loggedInUserId = Auth::id();

        if (!$query) {
            return response()->json([]);
        }

        $workspaceParticipantIds = $workspace->users()->pluck('id')->toArray();

        $users = User::where('username', 'like', '%' . $query . '%')
            ->where('id', '!=', $loggedInUserId)
            ->whereNotIn('id', $workspaceParticipantIds)
            ->get();

        return UserSearchResource::collection($users);
    }

    public function getPreferences() {
        if (auth()->user()->preference) {
            return new PreferenceResource(auth()->user()->preference);
        }

        return new PreferenceResource([
            'primary' => '',
            'secondary' => '',
        ]);
    }

    public function setPreferences(Request $request)
    {
        $validated = $request->validate([
            'primary' => 'string|nullable',
            'secondary' => 'string|nullable',
        ]);
        auth()->user()->preference()->updateOrCreate(
            [],
            $validated,
        );
        return response()->noContent();
    }
}
