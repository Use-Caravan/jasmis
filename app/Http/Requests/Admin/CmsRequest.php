<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Cms;
use App\Language;


class CmsRequest extends FormRequest
{
    
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('cms');
        $formData = $this->request;
        $model = new Cms();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        $rules =  [
            'title.*' => 'required',                
            'keywords.*' => 'required',
            'description.*' => 'required',
            'cms_content.*' => 'required',
            'sort_no' => 'nullable|numeric|max:1000',
            'section' => 'required',
        ];

        switch ($this->method()) {
            case 'POST':
                $rules["ldpi_image_path"] = 'required|mimes:jpeg,jpg,png';
                $rules["mdpi_image_path"] = 'required|mimes:jpeg,jpg,png';
                $rules["hdpi_image_path"] = 'required|mimes:jpeg,jpg,png';
                $rules["xhdpi_image_path"] = 'required|mimes:jpeg,jpg,png';
                $rules["xxhdpi_image_path"] = 'required|mimes:jpeg,jpg,png';
                $rules["xxxhdpi_image_path"] = 'required|mimes:jpeg,jpg,png';
            break;
            case 'PUT':
                $rules["ldpi_image_path"] = 'nullable|mimes:jpeg,jpg,png';
                $rules["mdpi_image_path"] = 'nullable|mimes:jpeg,jpg,png';
                $rules["hdpi_image_path"] = 'nullable|mimes:jpeg,jpg,png';
                $rules["xhdpi_image_path"] = 'nullable|mimes:jpeg,jpg,png';
                $rules["xxhdpi_image_path"] = 'nullable|mimes:jpeg,jpg,png';
                $rules["xxxhdpi_image_path"] = 'nullable|mimes:jpeg,jpg,png';
            case 'PATCH':            
        }

        return $rules;
    }    
}