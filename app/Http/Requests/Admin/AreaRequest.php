<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Area;
use App\Language;

class AreaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('area');
        $formData = $this->request;
        $model = new Area();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        return [
            'area_name.*' => 'required',    
            'country_id' => 'required', 
            'city_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',                
        ];
    }
}