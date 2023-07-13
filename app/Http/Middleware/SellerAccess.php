<?php

namespace App\Http\Middleware;

use App\Models\Seller;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerAccess
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
        $seller = Seller::where('user_id', Auth::id())->first();
        if (!$seller) {
            return response()->json([
                'status'    => false,
                'message'   => 'unregistered as seller',
                'data'      => null
            ], 400);
        }

        return $next($request);
    }
}
