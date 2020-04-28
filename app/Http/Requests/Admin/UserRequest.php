<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use App\User;

class UserRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $method = $this->method();
        $uniqueKey = $this->route('user');
        $formData = $this->request;
        $model = new User();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')                    

        /* 'email' => "required|email|unique:user,email,$uniqueKey,user_key",  have to implement */

        $rules = [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'username' => 'required',
                    'email' => "required|email|unique:$tableName,email,$uniqueKey,$tableKey",
                    'phone_number' => 'required|numeric',
                    // 'password' => 'required|min:6',
                    // 'confirm_password' => 'required|min:6|same:password',
];                   
        // switch ($method) {
        //     case 'POST':                
        //         $rules['password'] = 'required|min:6';
        //         $rules['confirm_password'] = 'required|same:password';
        //         $rules['email'] = "required|unique:$tableName,email";
        //         $rules['username'] = "required|unique:$tableName,username";
        //         $rules['phone_number'] = "required|unique:$tableName,phone_number";
        //     break;
        //     case 'PUT':
        //     case 'PATCH':            
                
        //         $rules['email'] = "required|unique:$tableName,email,$uniqueKey,$tableKey";
        //         $rules['username'] = "required|unique:$tableName,username,$uniqueKey,$tableKey";
        //         $rules['phone_number'] = "required|unique:$tableName,phone_number,$uniqueKey,$tableKey";
        //         $rules['password'] = 'nullable|min:6';
        //         $rules['confirm_password'] = 'nullable|same:password';
        // }
        
        return $rules;
    }
}