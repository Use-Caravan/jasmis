<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\NewsletterSubscriber;
use App\Language;


class NewsletterSubscriberRequest extends FormRequest
{
    
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('newsletter-send-mail');
        $formData = $this->request;
        $model = new NewsletterSubscriber();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        
        return [
            'newsletter_subscriber_email' => 'required',                
            'newsletter_title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'newsletter_subscriber_email.required' => 'Please  Choose email',
            'newsletter_title.required' => 'Please Choose newsletter title',
        ];
    }
}