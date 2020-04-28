<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            //'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'comments' => 'required'
        ];
    }
}