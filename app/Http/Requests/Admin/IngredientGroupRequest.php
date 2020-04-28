<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\IngredientGroup;
use App\Language;

class IngredientGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('ingredient-group');
        $formData = $this->request;
        $model = new IngredientGroup();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        $rules = [
            'ingredient_group_name.*' => 'required',
            'ingredient_type' => 'required',
            'sort_no' => 'nullable|numeric',
            'minimum' => 'required|numeric|lte:maximum',
            'maximum' => 'required|numeric|gte:minimum',
            'price.*' => 'required|numeric'
        ];
        /* switch ($this->method()) {
                    case 'POST':
                            $rules['maximum'] = "lte:". ($this->request->get('minimum')==null) 0;
                    break;
                    case 'PUT':
                    case 'PATCH':            
                       
            }    */
      
        return $rules;
    }
    
}