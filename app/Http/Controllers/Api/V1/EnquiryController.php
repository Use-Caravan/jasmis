<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\{
    Controllers\Api\V1\Controller,
    Resources\Api\V1\EnquiryResource,
    Resources\Api\V1\ContactResource 
};
use App\Api\Enquiry;
use App\Api\Configuration;
use App\Helpers\OneSignal;
use Validator;
use DB;

class EnquiryController extends Controller
{    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {           
        
        $validator = Validator::make(request()->all(), [
            'first_name' => 'required',
            'email' => "required|email",
            'phone_number' => 'required|numeric|digits_between:8,15',
            'comments' => 'required'    
        ]);
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }
        DB::beginTransaction();
        try {        
            $model = new Enquiry();
            $model->fill(request()->all());   
            $model->save();
            DB::commit();     
            $this->setMessage( __('apimsg.Enquiry has been sent.') );
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        $data = new EnquiryResource($model);        
        return $this->asJson($data);
    }    

    public function contactDetails()
    {        
        $data = [
            'app_address' => config('webconfig.app_address'),
            'app_email' => config('webconfig.app_email'),
            'app_contact_number' => config('webconfig.app_contact_number'),
            'app_latitude' => config('webconfig.app_latitude'),
            'app_longitude' => config('webconfig.app_longitude'),
        ];
        $this->setMessage( __("apimsg.Contact Details are") );
        return $this->asJson($data);
    }


    public function sendNotification()
    {
        $oneSignal  = OneSignal::getInstance();
        $oneSignal->setAppType(ONE_SIGNAL_USER_APP);            
        $oneSignal->push(['en' => 'Order Delivery Status'], ['en' => 'Delivery Successfull'], ['f9cf4e42-03c1-4e3f-b34f-a70bad5eb4b2'], []);
        return response()->json($oneSignal);
    }
}
