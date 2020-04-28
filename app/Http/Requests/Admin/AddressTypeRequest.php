<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\AddressType;
use App\Language;
use Common;

class AddressTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('addresstype');
        $formData = $this->request;
        $model = new AddressType();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $language = Common::getLanguages();                
        $rules = [];
        foreach($language as $key => $value) {
            $rules["address_type_name.$key"] = "required";
        }
        return $rules;
    }
}