<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\LoyaltyLevel;
use App\Language;

class LoyaltyLevelRequest extends FormRequest
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
        $model = new LoyaltyLevel();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        $rules =  [
            'loyalty_level_name.*' => 'required',
            'from_point' => 'required|numeric',
            'to_point' => 'required|numeric',            
        ];

        switch ($this->method()) {
            case 'POST':
                $rules["card_image"] = 'required|mimes:jpeg,jpg,png|dimensions:width=550,height=356';
            break;
            case 'PUT':
                $rules["card_image"] = 'nullable|mimes:jpeg,jpg,png|dimensions:width=550,height=356';
            case 'PATCH':            
        }
        return $rules;
    }
    
}
