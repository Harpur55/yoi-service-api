<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InternalAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return string
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('internalAccess') == null || $request->header('internalAccess') != env('INTERNAL_ACCESS')) {
            return response()->json([
                'status'    => false,
                'message'   => 'unauthorized',
                'data'      => null
            ], 401);
        } else {
            return $next($request);
        }
    }
}
