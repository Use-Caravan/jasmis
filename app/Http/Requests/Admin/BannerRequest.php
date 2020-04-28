<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Banner;
use App\Language;

class BannerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $uniqueKey = $this->route('banner');
        $formData = $this->request;
        $model = new Banner();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();
        $language = Language::getList();
        $particulars = $this->request->get('is_home_banner');
    
        $rules = [
            'banner_name.*' => 'required',                        
            'redirect_url' => 'required|weburl',
        ];
        
        switch ($method) {
            case 'POST':
                foreach($language as $key => $value) {
                    $rules["banner_file.$value->language_code"] = 'required|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=1170,min_height=170|dimensions:max_width=1180,max_height=180';
                    if($particulars == 1) { 
                        $rules["banner_file.$value->language_code"] = 'required|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=420,min_height=400|dimensions:max_width=450,max_height=430';
                    }                    
                }
            break;
            case 'PUT':
                foreach($language as $key => $value) {
                    $rules["banner_file.$value->language_code"] = 'nullable|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=1170,min_height=170|dimensions:max_width=1180,max_height=180';
                    if($particulars == 1) { 
                        $rules["banner_file.$value->language_code"] = 'nullable|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=420,min_height=400|dimensions:max_width=450,max_height=430';
                    }                    
                }                
            break;
        }
       return $rules;
    }
}