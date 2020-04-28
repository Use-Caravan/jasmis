<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use App\Branch;

class BranchRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $method = $this->method();
        $uniqueKey = $this->route('branch');
        $formData = $this->request;
        $model = new Branch();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')                    
        $language = Language::getList();
        $rules = [
                    'branch_name.*' => 'required',
                    'branch_address.*' => 'required',
                    'contact_email' => 'required|email',
                    'contact_number' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/|digits_between:8,15',
                    'vendor_id' => 'required',
                    'country_id' => 'required',
                    'city_id' => 'required',
                    'area_id' => 'required',
                    'restaurant_type' => 'required',
                    'preparation_time' => 'required',
                    'delivery_time' => 'required',
                    'pickup_time' => 'required',
                    'order_type' => 'required',
                    'availability_status' => 'required',
                    'approved_status' => 'required',
                    'delivery_area_id' => 'required',
                    'cuisine_id' => 'required',
                    'category_id' => 'required',
                    
                ];                   
        switch ($method) {
            case 'POST':                
               
                $rules['contact_email'] = "required|unique:$tableName,contact_email";
                $rules['contact_number'] = "required|unique:$tableName,contact_number";
                $rules['password'] = "required|min:6";
                $rules['confirm_password'] = "required|same:password";
            break;
            case 'PUT':
            case 'PATCH':            
               
                $rules['contact_email'] = "required|unique:$tableName,contact_email,$uniqueKey,$tableKey";
                $rules['contact_number'] = "required|unique:$tableName,contact_number,$uniqueKey,$tableKey";
                $rules['password'] = 'nullable|min:6';
                $rules['confirm_password'] = 'nullable|same:password';
               
        }
        
        return $rules;
    }
}