<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProjectOwnershipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If project_id is not found in the route, the user can access the resource
        if(!$request->route('project')) {
            return $next($request);
        }

        $project = $request->route('project');

        if (!$project || $project->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
