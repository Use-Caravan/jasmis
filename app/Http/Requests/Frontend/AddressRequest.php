<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_type_id' => 'required',
            'address_line_one' => 'required',
            'address_line_two' => 'required',
            'company' => 'required',
            'landmark' => 'required'
        ];
    }
}