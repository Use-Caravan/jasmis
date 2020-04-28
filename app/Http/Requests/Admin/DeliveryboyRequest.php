<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use App\Deliveryboy;

class DeliveryBoyRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $method = $this->method();
        $uniqueKey = $this->route('deliveryboy');
        $formData = $this->request;
        $model = new Deliveryboy();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')                

        $rules = [                    
                'name' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/|digits_between:8,15',
                'address' => 'required',
                'country' => 'required',                
                'city' => 'required',                
            ];
        switch($method) {
            case 'POST':
                $rules['password'] = "required|min:6";
                $rules['confirm_password'] = 'required|same:password';
            break;
            case 'PUT':
                $rules['password'] = "nullable|min:6";
                $rules['confirm_password'] = 'nullable|same:password';
            break;
        }
        return $rules;
    }
}