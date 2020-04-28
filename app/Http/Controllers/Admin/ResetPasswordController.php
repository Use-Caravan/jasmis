<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Password;
use Auth;

class ResetPasswordController extends Controller
{
    //trait for handling reset Password
    use ResetsPasswords;
   
    protected $redirectTo = 'admin/login';

    //Show form to admin where they can reset password
    public function showResetForm(Request $request, $token = null)
    {        
        return view('admin.auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    //returns Password broker of admin
    public function broker()
    {
        return Password::broker('admin');
    }

    //returns authentication guard of admin
    protected function guard()
    {
        return Auth::guard(APP_GUARD);
    }
}