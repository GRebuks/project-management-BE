<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function addParticipant(Task $task, Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // If user is already assigned to task
        if($task->users()->where('user_id', $validated['user_id'])->exists()) {
            return response()->json(['message' => 'User is already assigned to this task!'], 409);
        }

        $task->users()->attach($validated['user_id']);
        return response()->json();
    }

    public function removeParticipant(Task $task, Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if($task->users()->where('user_id', $validated['user_id'])->exists()) {
            $task->users()->detach($validated['user_id']);
            return response()->json();
        }

        return response()->json(['message' => 'User is not assigned to this task!'], 409);
    }
}
