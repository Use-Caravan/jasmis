<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use App\Vendor;

class VendorRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $method = $this->method();
        $uniqueKey = $this->route('vendor');
        $formData = $this->request;
        $model = new Vendor();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')                    
        $language = Language::getList();
        $rules = [
                    'vendor_name.*' => 'required',
                    'vendor_description.*' => 'required',
                    'vendor_address.*' => 'required',
                    'username' => 'required',
                    'email' => 'required|email',
                    'mobile_number' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/|digits_between:8,15',

                    // 'password' => 'required|min:6|max:10',
                    // 'confirm_password' => 'required|same:password',

                    'contact_number' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/|digits_between:8,15',
                    'country_id' => 'required',
                    'city_id' => 'required',
                    'area_id' => 'required',
                    'commission' => 'required',
                    'commission_type' => 'required',
                    'tax' => 'required',
                    'service_tax' => 'required',
                    'payment_option' => 'required',
                    'min_order_value' => 'required',
                    'color_code' => 'required',
                    'sort_no' => 'nullable|numeric',
                    'approved_status' => 'required',
                    /* 'preparation_time' => 'required',
                    'restaurant_type' => 'required',
                    'delivery_time' => 'required',
                    'pickup_time' => 'required',    
                    'order_type' => 'required',
                    'availability_status' => 'required',
                    'delivery_area_id' => 'required',
                    'cuisine_id' => 'required',
                    'category_id' => 'required', */
                    
                ];                   
        switch ($method) {
            case 'POST':                
                foreach($language as $key => $value) {
                    $rules["vendor_logo.$value->language_code"] = 'required|image|mimes:jpeg,jpg,png|max:300|dimensions:width=120,height=120';
                }
                $rules['password'] = 'required|min:6';
                $rules['confirm_password'] = 'required|same:password';
                $rules['email'] = "required|unique:$tableName,email";
                $rules['username'] = "required|unique:$tableName,username";
                $rules['mobile_number'] = "required|unique:$tableName,mobile_number";
            break;
            case 'PUT':
            case 'PATCH':            
                foreach($language as $key => $value) {
                    $rules["vendor_logo.$value->language_code"] = 'nullable|image|mimes:jpeg,jpg,png|max:300|dimensions:width=120,height=120';
                }
                $rules['email'] = "required|unique:$tableName,email,$uniqueKey,$tableKey";
                $rules['username'] = "required|unique:$tableName,username,$uniqueKey,$tableKey";
                $rules['mobile_number'] = "required|unique:$tableName,mobile_number,$uniqueKey,$tableKey";
                $rules['password'] = 'nullable|min:6';
                $rules['confirm_password'] = 'nullable|same:password';
        }
        if ($formData->get('commission_type') == VENDOR_COMMISSION_TYPE_PERCENTAGE) {
              
            $rules['commission'] = 'required|integer|lt:100';
        }

        return $rules;
    }
}