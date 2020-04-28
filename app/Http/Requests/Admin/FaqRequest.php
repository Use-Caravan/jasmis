<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Faq;
use App\Language;


class FaqRequest extends FormRequest
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
        $model = new Faq();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        return [
            'question.*' => 'required',                
            'answer.*' => 'required',
            'sort_no' => "numeric|nullable|max:1000"
        ];
    }
}