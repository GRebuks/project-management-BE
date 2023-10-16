<?php

namespace App\Http\Middleware;

use App\Models\Board;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectBoardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If project_id is not found in the route, the user can access the resource
        if(!$request->route('board')) {
            return $next($request);
        }

        $project = $request->route('project');
        $board = $request->route('board');

        if(!$board || $board->project_id !== $project->id) {
            return response()->json(['message' => 'Board not found'], 404);
        }
        return $next($request);
    }
}
