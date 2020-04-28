<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyPointConfigRequest extends FormRequest
{    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'loyalty_amount'            => 'required|numeric',
            'loyalty_point_for_amount'  => 'required|numeric',
            'loyalty_points'            => 'required|numeric',
            'loyalty_amount_for_points' => 'required|numeric',
        ];
    }
}
