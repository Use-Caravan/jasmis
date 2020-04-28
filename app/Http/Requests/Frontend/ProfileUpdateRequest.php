<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use Auth;

class ProfileUpdateRequest extends FormRequest
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
            'gender' => 'required|digits_between:1,2',
            'dob' => 'required',
            'current_password' => 'nullable|min:6',
            'new_password' => 'nullable|min:6',
            'confirm_password' => 'same:new_password'
        ];
    }
    
}