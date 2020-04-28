<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\AdminUser;
use App\Language;


class AdminUserRequest extends FormRequest
{
    
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('admin_user');        
        $formData = $this->request;
        $model = new AdminUser();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();                
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
    
        $rules =  [];
        
        switch ($this->method()) {
            case 'POST':
                $rules['password'] = 'required|min:6';
                $rules['confirm_password'] = 'required|same:password';
                $rules['email'] = "required|unique:$tableName,email";
                $rules['username'] = "required|unique:$tableName,username";
                $rules['phone_number'] = "required|numeric|digits_between:8,15|unique:$tableName,phone_number";
            break;
            case 'PUT':
            case 'PATCH':            
                $rules['email'] = "required|unique:$tableName,email,$uniqueKey,$tableKey";
                $rules['username'] = "required|unique:$tableName,username,$uniqueKey,$tableKey";
                $rules['phone_number'] = "required|numeric|digits_between:8,15|unique:$tableName,phone_number,$uniqueKey,$tableKey";
        }

    return $rules;
    }
}