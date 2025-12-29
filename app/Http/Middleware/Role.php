<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    use ResponseTrait;
    /**
     * Handle en incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$type)
    {
        $user = $request->user();
        if (!$user)
            $this->returnError('Unauthorized',401);
        if (!in_array($user->role->name,$type))
        {
            $this->returnAbort('Forbidden',403);
        }
        return $next($request);

    }
}
