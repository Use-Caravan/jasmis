<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\City;
use App\Language;

class CityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('city');
        $formData = $this->request;
        $model = new City();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        return [
            'city_name.*' => 'required',                
            'country_id' => 'required',                
        ];
    }
}