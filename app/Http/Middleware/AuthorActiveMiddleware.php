<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role_id !== 3 || $user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح لك بنشر كتاب.'
            ], 403);
        }


        return $next($request);
    }
}
