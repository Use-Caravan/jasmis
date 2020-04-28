<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;

class DriverRegisterRequest  extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'email' => 'required|email|unique:deliveryboy,email',
            'mobile_number' => 'required|numeric|digits_between:8,15',
            'license' => 'required',
            'vehicle_number' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ];
    }
   
}

 

     