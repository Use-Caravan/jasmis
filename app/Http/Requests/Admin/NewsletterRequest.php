<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Newsletter;
use App\Language;


class NewsletterRequest extends FormRequest
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
        $model = new Newsletter();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        return [
            'newsletter_title' => 'required',                
            'newsletter_content' => 'required',
        ];
    }
}