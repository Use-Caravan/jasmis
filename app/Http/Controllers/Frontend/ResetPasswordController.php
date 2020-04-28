<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Password;
use Auth;

class ResetPasswordController extends Controller
{
    //trait for handling reset Password
    use ResetsPasswords;

   
    protected $redirectTo = '/';

    //Show form to admin where they can reset password
    public function showResetForm(Request $request, $token = null)
    {   
        return view('frontend.auth.reset-password')->with(
            ['token' => $request->token, 'email' => $request->email]
        );
    }

    //returns Password broker of web
    public function broker()
    {        
        return Password::broker(PROVIDER_USER_GUARD);
    }

    //returns authentication guard of web
    protected function guard()
    {
        return Auth::guard(GUARD_USER);
    }
}
