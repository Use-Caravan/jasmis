<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;

class RegisterRequest extends FormRequest
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
            'last_name' => 'required',                
            'email' => 'required|email',
            'phone_number' => 'required|numeric|digits_between:8,15',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ];
    }
}