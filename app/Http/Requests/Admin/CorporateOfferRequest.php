<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\CorporateOffer;
use App\Language;

class CorporateOfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('corporate-offer');
        $formData = $this->request;
        $model = new CorporateOffer();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        $language = Language::getList();
        $rules = [
            'offer_type' => 'required',
            'offer_value' => 'required|lte:100',
            'offer_level' => 'required|lte:100',
            'offer_name' => 'required',
            'offer_description' => 'required',
        ];
        switch ($this->method()) {
            case 'POST':
                foreach($language as $key => $value) {
                    $rules["offer_banner.$value->language_code"] = 'required|image|mimes:jpeg,jpg,png|max:300';
                }
                break;
            case 'PUT':
                foreach($language as $key => $value) {
                    $rules["offer_banner.$value->language_code"] = 'nullable|image|mimes:jpeg,jpg,png|max:300';
                }
                break;
        }
        return $rules;
    }
}