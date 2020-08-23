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
            'loyalty_point_per_bd' => 'required|integer|max:4294967295',
            'redeem_amount_per_point' => 'required|integer|max:4294967295',
            'minimum_amount_to_redeem' => 'required||integer|max:4294967295',            
        ];

        switch ($this->method()) {
            case 'POST':
                $rules["card_image"]  = 'required|mimes:jpeg,jpg,png|dimensions:width=550,height=356';
                $rules["popup_image"] = 'required|mimes:jpeg,jpg,png|dimensions:width=200,height=400';
            break;
            case 'PUT':
                $rules["card_image"]  = 'nullable|mimes:jpeg,jpg,png|dimensions:width=550,height=356';
                $rules["popup_image"] = 'nullable|mimes:jpeg,jpg,png|dimensions:width=200,height=400';
            case 'PATCH':            
        }
        return $rules;
    }
    
}
