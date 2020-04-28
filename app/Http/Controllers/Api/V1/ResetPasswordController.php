<?php 

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Controller;

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
            ['token' => $token, 'email' => $request->email]
        );
    }

    //returns Password broker of web
    public function broker()
    {        
        return Password::broker('users');
    }

    //returns authentication guard of web
    protected function guard()
    {
        return Auth::guard(GUARD_USER);
    }
}