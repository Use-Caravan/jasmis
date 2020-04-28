<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use App\Cuisine;

class CuisineRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $method = $this->method();
        $uniqueKey = $this->route('cuisine');
        $formData = $this->request;
        //$particulars = $this->request->get('value');
        $model = new Cuisine();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')

        /*
        return [
            'cuisine_name.*' => 'required',
            'sort_no' => "numeric|nullable|unique:$tableName,sort_no,$uniqueKey,$tableKey"
        ];
        */
        return [
            'cuisine_name.*' => 'required',
            'sort_no' => "numeric|nullable|max:1000"
        ];

    }
}