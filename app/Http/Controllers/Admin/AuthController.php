<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LoginRequest;
use App\AdminUser;
use App\Branch;
use App\BranchUser;
use App\Vendor;
use App\Role;

use Session;
use Common;
use Input;
use Auth;
use DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Login
     *
     * @return view
     */
    public function index(Request $request)
    {   
        if($request->method() == 'GET') {
            return view('admin.auth.index');
        }      
        if($request->method() == 'POST') {
                
            switch(APP_GUARD) {
                case GUARD_ADMIN:
                    $user = AdminUser::leftjoin(Role::tableName(),AdminUser::tableName().'.role_id',Role::tableName().'.role_id')
                    ->where('username',$request->username)->orWhere('email',$request->username)->first();
                    if($user === null) {
                        return redirect()->route('admin-login')->withInput()->with('error', 'Username or email does not match');
                    }
                    if($user->status === ITEM_INACTIVE) {                        
                        return redirect()->route('admin-login')->withInput()->with('error', 'Sub admin is inactived by admin');
                    }                    
                    if (
                        Auth::guard(APP_GUARD)->attempt(['username' => $request->username, 'password' => $request->password, 'status' => ITEM_ACTIVE]) ||
                        Auth::guard(APP_GUARD)->attempt(['email' => $request->username, 'password' => $request->password, 'status' => ITEM_ACTIVE])
                    ) {                                    
                        return redirect()->route('admin-dashboard');
                    }
                    else {
                        return redirect()->route('admin-login')->withInput()->with('error', 'Invalid username or password');
                    }
                    break;
                case GUARD_VENDOR:
                    $user = Vendor::where('username',$request->username)->orWhere('email',$request->username)->first();
                    if($user === null) {
                        return redirect()->route('admin-login')->withInput()->with('error', 'Username or email does not match');
                    }
                    if($user->status === ITEM_INACTIVE) {                        
                        return redirect()->route('admin-login')->withInput()->with('error', 'Sub admin is inactived by admin');
                    }                    
                    if (
                        Auth::guard(APP_GUARD)->attempt(['username' => $request->username, 'password' => $request->password, 'status' => ITEM_ACTIVE]) ||
                        Auth::guard(APP_GUARD)->attempt(['email' => $request->username, 'password' => $request->password, 'status' => ITEM_ACTIVE])
                    ) {                                    
                        return redirect()->route('admin-dashboard');
                    }
                    else {
                        return redirect()->route('admin-login')->withInput()->with('error', 'Invalid username or password');
                    }
                    break;
                case GUARD_OUTLET:
                      
                    $user = BranchUser::where('username',$request->username)->orWhere('email',$request->username)->first();
                    if($user === null) {
                        return redirect()->route('admin-login')->withInput()->with('error', 'Username or email does not match');
                    }
                    $branch = Branch::find($user->branch_id);
                    if($branch->status === ITEM_INACTIVE) {
                        return redirect()->route('admin-login')->withInput()->with('error', 'Sub admin is inactived by admin');
                    }                    
                    if (                        
                        Auth::guard(APP_GUARD)->attempt(['username' => $request->username, 'password' => $request->password]) ||
                        Auth::guard(APP_GUARD)->attempt(['email' => $request->username, 'password' => $request->password])
                    ) {  
                        return redirect()->route('admin-dashboard');
                    }
                    else {
                        return redirect()->route('admin-login')->withInput()->with('error', 'Invalid username or password');
                    }
                    break;
            }            
        }
    }
    
    public function register(Request $request)
    {
        if($request->method() == 'GET') {
            return view('admin.auth.register');
        } else {
            $admin = new AdminUser();
            $admin = $admin->fill($request->all());
            $admin->password = Hash::make($request->password);
            $admin->save();        
            return redirect()->route('admin-login');
        }        
    }

    /**
     * Logout
     * @return view
    */    
    public function logout(Request $request)
    {           
        AdminUser::authLogout();        
        return redirect()->route('admin-login');
    }
}
