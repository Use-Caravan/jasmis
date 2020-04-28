<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;


//Trait
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

//Password Broker Facade
use Illuminate\Support\Facades\Password;
use Validator;

class ForgetPasswordController extends Controller
{
    //Sends Password Reset emails
    use SendsPasswordResetEmails;
    
    //Password Broker for User Model
    public function broker()
    {   
        return Password::broker(PROVIDER_USER_GUARD);
    }
        
    public function sendEmail(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,email',            
        ]);
        if($request->ajax()) {
            if ($validator->fails()) {
               return response()->json(['status' => AJAX_FAIL, 'errors' => $validator->errors(),'msg' => __('frontendmsg.Enter your email')]);
            }
           return response()->json(['status' => AJAX_SUCCESS,'msg' => __('frontendmsg.Sent reset link for your email')]);
        } else {
            try {
               $this->sendResetLinkEmail($request);               
            } catch (\Exception $ex) {
                return redirect()->route('frontend.index')->with('error',__('frontendmsg.Something went wrong from mail configrutions') );
            }
            return redirect()->route('frontend.index');
        }
    }
}
