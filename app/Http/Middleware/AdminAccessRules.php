<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AccessRules;
use App\Role;
use Session;

class AdminAccessRules
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if(!Session::has(SES_ROLE_JSON)) {
            $condition = [];            
            switch(APP_GUARD) {                
                case GUARD_ADMIN:                    
                    $condition = [ 'role_id' => auth()->guard(GUARD_ADMIN)->user()->role_id];
                    break;
                case GUARD_VENDOR:
                    $condition = [ 'user_type' => ROLE_USER_VENDOR];
                    break;
                case GUARD_OUTLET:
                    $condition = [ 'user_type' => ROLE_USER_OUTLET];
                    break;
            }
            $model = Role::where($condition)->first();            
            $roleJson = '[]';
            if ($model !== null && $model->permission) {
                $roleJson = $model->permission;
            }            
            Session::put(SES_ROLE_JSON, $roleJson);
            Session::save();
        }                
        if (!AccessRules::check(\Route::current()->getActionName())) {
            return response()->view('admin.errors.'.FORBIDDEN_UNAUTHORISED,[],FORBIDDEN_UNAUTHORISED);
        }                
        return $next($request);
    }
}
