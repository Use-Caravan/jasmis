<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;

class DeliveryboySettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_accept_time_limit'          => 'required|numeric',
            'request_radius'          => 'required|numeric',
            'order_assign_type'       => 'required',
            'deliveryboy_url'   => 'required|url',
            'company_id' => 'required',
            'auth_token'  => 'required',            
        ];
    }
}