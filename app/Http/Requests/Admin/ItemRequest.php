<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;
use App\Item;

class ItemRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $method = $this->method();
        $uniqueKey = $this->route('item');
        $formData = $this->request;
        $model = new Item();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        //$particulars = $this->request->get('attr')             
        $language = Language::getList();   

        $rules = [
                    'item_name.*' => 'required',
                    'item_description.*' => 'required',
                    'allergic_ingredient.*' => 'required',
                    'branch_id' => 'required',
                    'category_id' => 'required',
                    //'cuisine_id' => 'required',
                    'item_price' => 'required|numeric',
                    //'ingredient_group_id' => 'required',
                    'sort_no' => 'nullable|numeric',
                    'approved_status' => 'required',
                    
                ];    
        switch ($this->method()) {
            case 'POST':
                foreach($language as $key => $value) {
                    $rules["item_image.$value->language_code"] = 'image|mimes:jpeg,jpg,png|max:300|dimensions:width=350,height=350';
                }
                if(auth()->guard(GUARD_ADMIN)->check()) {
                    $rules['vendor_id'] = 'required';
                }
                
            break;
            case 'PUT':
            foreach($language as $key => $value) {
                    $rules["item_image.$value->language_code"] = 'nullable|image|mimes:jpeg,jpg,png|max:300|dimensions:width=350,height=350';
                }
                if(auth()->guard(GUARD_ADMIN)->check()) {
                    $rules['vendor_id'] = 'required';
                }
                
            case 'PATCH':            
        }
        return $rules;
    }
}