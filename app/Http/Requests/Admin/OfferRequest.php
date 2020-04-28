<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Offer;
use App\Language;

class OfferRequest extends FormRequest
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
        $model = new Offer();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')
        $language = Language::getList();
        $rules = [
            'start_datetime' => 'required',                
            'end_datetime' => 'required',
            'vendor_id' => 'required',
            'branch_id' => 'required',
            'item_id' => 'required',
            'offer_type' => 'required',
            'offer_value' => 'required',
        ];
        switch ($this->method()) {
            case 'POST':
                if ($this->request->get('offer_type') == VOUCHER_DISCOUNT_TYPE_PERCENTAGE) {
                    $rules['offer_value'] = 'lte:100'; 
                }
                break;
            case 'PUT':
                if ($this->request->get('offer_type') == VOUCHER_DISCOUNT_TYPE_PERCENTAGE) {
                    $rules['offer_value'] = 'lte:100'; 
                }
                break;
        }
        return $rules;
    }
}