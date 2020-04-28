<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\LoyaltyPoint;
use App\Language;

class LoyaltyPointRequest extends FormRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('loyaltypoint');
        $formData = $this->request;
        $model = new LoyaltyPoint();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        return [
            'from_amount' => 'required|numeric',                
            'to_amount' => 'required|numeric',
            'point' => 'required|numeric',
            
        ];
    }
    
}
