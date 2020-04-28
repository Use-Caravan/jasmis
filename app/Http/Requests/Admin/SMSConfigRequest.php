<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SMSConfigRequest extends FormRequest
{    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sms_gateway_username' => 'required',
            'sms_gateway_password' => 'required',
            'sms_sender_id'        => 'required',
        ];
    }
}
