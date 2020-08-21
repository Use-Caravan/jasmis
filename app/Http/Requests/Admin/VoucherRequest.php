<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use App\Voucher;

class VoucherRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $method = $this->method();
        $uniqueKey = $this->route('voucher');
        $formData = $this->request;
        $model = new Voucher();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        $particulars = $this->request->get('promo_for_shops');        
        $rules = [
                    'manual_voucher' => 'nullable',
                    //'max_redeem_amount' => 'required|numeric',
                    'max_redeem_amount' => 'numeric',
                    'value' => 'required|numeric',
                    'min_order_value' => 'required|numeric',
                    'apply_promo_for' => 'required',
                    'app_type' => 'required',
                    'expiry_date' => 'required',
                    'limit_of_use' => 'nullable|numeric',
                    'promo_for_shops' => 'required_if:apply_promo_for,'.PROMO_FOR_ALL_SHOPS.','.PROMO_FOR_BOTH,
                    'promo_for_user' => 'required_if:apply_promo_for,'.PROMO_FOR_ALL_USERS.','.PROMO_FOR_BOTH,
                    //'promo_for_shops' => 'required_if:apply_promo_for,'.PROMO_FOR_BOTH,
                    //'promo_for_user' => 'required_if:apply_promo_for,'.PROMO_FOR_BOTH,
                    'shopbeneficiary_id' => 'required_if:promo_for_shops,'.PROMO_SHOPS_PARTICULAR,
                    'userbeneficiary_id' => 'required_if:promo_for_user,'.PROMO_USER_PARTICULAR,
                ]; 
            switch ($this->method()) {
                case 'POST':
                    if ($this->request->get('discount_type') == VOUCHER_DISCOUNT_TYPE_PERCENTAGE) {
                        $rules['value'] = 'lte:100'; 
                    }
                    break;
                case 'PUT':
                    if ($this->request->get('discount_type') == VOUCHER_DISCOUNT_TYPE_PERCENTAGE) {
                        $rules['value'] = 'lte:100'; 
                    }
                    break;
            }
        return $rules;
    }
}