<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // API Resources
    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection(Project::where('is_public', true)->get());
    }

    public function store(ProjectRequest $request): ProjectResource
    {
        $validated = $request->validated();
        $userId = Auth::id();

        $validated['user_id'] = $userId;

        $project = Project::create($validated);
        return new ProjectResource($project);
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, Project $project): ProjectResource
    {
        $validated = $request->validated();

        $project->update($validated);
        return new ProjectResource($project);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(status: 204);
    }

    // Other methods
    public function getUserProjects($user_id): AnonymousResourceCollection
    {
        $auth_user_id = auth()->id();

        // If retrieving logged-in users projects, show all
        if ($user_id == $auth_user_id) {
            $projects = Project::where('user_id', $user_id)->get();
        } // If retrieving other users projects, show only public
        else {
            $projects = Project::where('user_id', $user_id)
                ->where('is_public', true)
                ->get();
        }

        return ProjectResource::collection($projects);
    }
}
