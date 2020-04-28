<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Role;
use App\Language;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('role');
        $formData = $this->request;
        $model = new Role();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        $rules = [
            'role_name' => 'required',                
        ];
        switch ($this->method()){

            case 'POST';
            $rules['role_name'] =  'required|unique:role,role_name';
            break;
            case 'PUT';
            $rules['role_name'] = 'required|unique:role,role_name';
            break;

        }
        return  $rules;
    }
}