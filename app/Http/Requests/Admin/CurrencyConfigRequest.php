<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyConfigRequest extends FormRequest
{    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency_code'    => 'required',
            'currency_symbol'  => 'required',
            'currency_position'=> 'required',    
        ];
    }
}
