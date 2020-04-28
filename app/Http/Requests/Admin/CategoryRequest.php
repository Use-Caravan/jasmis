<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Category;
use App\Language;


class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('category');
        $formData = $this->request;
        $model = new Category();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        $rules =  [
            'category_name.*' => 'required',
            'is_main_category' => 'required',
            'main_category_id' => 'required_if:is_main_category,'.SUB_CATEGORY,
            'sort_no' => 'numeric|nullable|max:1000',
        ];

        switch ($this->method()) {
            case 'POST':
                $rules["category_image"] = 'required';
                
            break;
            case 'PUT':
                $rules["category_image"] = 'nullable';
                
            case 'PATCH':            
        }

        return $rules;
    }    
}