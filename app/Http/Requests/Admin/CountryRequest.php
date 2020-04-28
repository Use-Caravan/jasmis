<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Country;
use App\Language;

class CountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('country');
        $formData = $this->request;
        $model = new Country();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();        
        //$particulars = $this->request->get('attr')                        
        $rules = [
            'country_name.*' => 'required',            
        ];
        switch($method) {
            case 'POST':
                $rules['country_code'] = "required|unique:$tableName,country_code";
            break;
            case 'PUT':
                $rules['country_code'] = "required|unique:$tableName,country_code,$uniqueKey,country_key";
            break;
        }
        return $rules;
    }
}