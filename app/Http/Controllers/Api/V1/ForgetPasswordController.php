<?php 

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Controller;

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
        return Password::broker('users');
    }
    
    
    public function sendEmail(Request $request)
    {           
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,email',
        ]);        
        if ($validator->fails()) {
            return $this->validateError($validator->errors());
        }
        try {
            $this->sendResetLinkEmail($request);
        } catch (\Exception $ex) {
            $errors = $ex->getMessage(); 
            return $this->commonError( __('apimsg.Mail configuration is incorrect') );
        }
        $this->setMessage( __('apimsg.We have sent password reset email') );
        return $this->asJson();
    }
}