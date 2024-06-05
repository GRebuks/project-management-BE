<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return WorkspaceResource::collection($user->workspaces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkspaceRequest $request)
    {
        $ownerRoleId = 1;
        $workspace = Workspace::create($request->validated());
        $workspace->users()->attach(Auth::id(), ['role_id' => $ownerRoleId]);
        return new WorkspaceResource($workspace);
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
        return new WorkspaceResource($workspace);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkspaceRequest $request, Workspace $workspace)
    {
        $validated = $request->validated();
        $workspace->update($validated);
        return new WorkspaceResource($workspace);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
        $workspace->users()->detach();
        $workspace->delete();
        return response()->json(status:204);
    }

    public function addParticipant(Workspace $workspace, Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($workspace->users()->where('user_id', $validated['user_id'])->exists()) {
            return response()->json(['message' => 'User is already participating in this workspace'], 409);
        }

        $workspace->users()->attach($validated['user_id'], ['role_id' => 2]);
        return response()->json();
    }
    public function removeParticipant(Workspace $workspace, Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($workspace->users()->where('user_id', $validated['user_id'])->exists()) {
            $workspace->users()->detach($validated['user_id']);
            return response()->json();
        }

        return response()->json(['message' => 'User isn\'t participating in this workspace'], 409);

    }
}
