<?php

namespace App\Http\Middleware;

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
        if(!$request->route('board'))
            return $next($request);

        $board = $request->route('board');
        $workspace = $board->workspace;

        if($request->route('workspace')->id !== $workspace->id
        || !$workspace->users->pluck('id')->contains(auth()->id())) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 404);
        }
        return $next($request);
    }
}
