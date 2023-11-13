<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkspaceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->route('workspace'))
            return $next($request);

        $workspace = $request->route('workspace');
        if(!$workspace->users->pluck('id')->contains(auth()->id())) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 404);
        }
        return $next($request);
    }
}
