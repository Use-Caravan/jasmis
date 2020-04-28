<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;

class CorporateOfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {               
        return [
            'corporate_name' => 'required',
            'contact_name' => 'required',
            'office_email' => 'required|email',
            'mobile_number' => 'required|numeric|digits_between:8,15',
            'contact_address' => 'required',
            'company_logo' => 'required',
            'valid_upto' => 'required'
        ];
    }
}