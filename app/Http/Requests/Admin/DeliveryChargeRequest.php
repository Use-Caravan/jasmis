<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\DeliveryCharge;
use App\Language;

class DeliveryChargeRequest extends FormRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('deliverycharge');
        $formData = $this->request;
        $model = new DeliveryCharge();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        return [
            'from_km' => 'required|numeric',                
            'to_km' => 'required|numeric',
            'price' => 'required|numeric',
            
        ];
    }
    
}
