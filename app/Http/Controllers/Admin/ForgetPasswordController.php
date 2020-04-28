<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller\Admin;

//Trait
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

//Password Broker Facade
use Illuminate\Support\Facades\Password;
use Validator;

class ForgetPasswordController extends Controller
{
    //Sends Password Reset emails
    use SendsPasswordResetEmails;   

    //Password Broker for Seller Model
    public function broker()
    {
        return Password::broker(PROVIDER_ADMIN_GUARD);
    }
    
    
    public function sendEmail(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:admin_user,email',            
        ]);
        if($request->ajax()) {
            if ($validator->fails()) {
                return response()->json(['status' => AJAX_FAIL, 'errors' => $validator->errors()]);
            }
            return response()->json(['status' => AJAX_SUCCESS]);    
        } else {
            try {
                $this->sendResetLinkEmail($request);
            } catch (\Exception $ex) {
                throw $ex;
                //return redirect()->route('admin-login')->with('error',__('admincrud.Something went wrong from mail configrutions') );
            }
            return redirect()->route('admin-login');
        }
    }
}