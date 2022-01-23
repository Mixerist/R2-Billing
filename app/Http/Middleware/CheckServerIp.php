<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckServerIp
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->ip() !== config('app.ip')) {
            return response('', 403);
        }

        return $next($request);
    }
}
