<?php

namespace App\Http\Middleware;

use App\Models\Board;
use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BoardWorkspaceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
        if(!$request->route('board'))
            return $next($request);

        $board = $request->route('board');
        if (is_string($board)) {
            $boardId = (int)$board;
            $board = Board::find($boardId);
        }

        $workspace = $board->workspace;

        $routeWorkspace = $request->route('workspace');
        if (is_string($routeWorkspace)) {
            $workspaceId = (int)$routeWorkspace;
            $routeWorkspace = Workspace::find($workspaceId);
        }

        if($routeWorkspace->id !== $workspace->id
        || !$workspace->users->pluck('id')->contains(auth()->id())) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 404);
        }
        return $next($request);
    }
}
