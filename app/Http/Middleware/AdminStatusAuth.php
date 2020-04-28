<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AccessRules;
use App\AdminUser;
use App\Role;


class AdminStatusAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = APP_GUARD)
    {
        if(APP_GUARD !== GUARD_ADMIN) {
            return $next($request);
        }
        if(Auth::guard($guard)->user()->user_type === SUB_ADMIN) {

            if (Auth::guard($guard)->user()->status === ITEM_INACTIVE) {
                AdminUser::authLogout();
                return redirect()->route('admin-login')->with('error',__('admincrud.Your account has been deactivated'));
            }

            if(Auth::guard($guard)->user()->role_id === null) {
                AdminUser::authLogout();
                return redirect()->route('admin-login')->with('error',__('admincrud.You don\'t have role'));
            }

            $roleAccess = Role::find(Auth::guard($guard)->user()->role_id);
            if($roleAccess === null || $roleAccess->status === ITEM_INACTIVE) {
                AdminUser::authLogout();
                return redirect()->route('admin-login')->with('error',__('admincrud.Your role has beeen deactivated'));
            }
        }
        return $next($request);
    }
}
