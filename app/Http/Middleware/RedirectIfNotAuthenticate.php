<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AccessRules;

class RedirectIfNotAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {        
        if (!Auth::guard($guard)->check()) {
            switch($guard) {
                case GUARD_ADMIN:                    
                case GUARD_VENDOR:                    
                case GUARD_OUTLET:
                    return redirect()->route('admin-login');
                    break;
                case GUARD_USER:
                    return redirect()->route('frontend.index');
                    break;
            }
        }        
        return $next($request);
    }
}
