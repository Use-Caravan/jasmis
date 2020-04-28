<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Language;

class ConfigurationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'app_name'          => 'required',
            'app_logo'          => 'logo_validate|image|mimes:jpeg,jpg,png|max:1000|dimensions:width=143,height=37',
            'app_favicon'       => 'logo_validate|image|mimes:jpeg,jpg,png|max:100',
            'app_description'   => 'required',
            'app_meta_keywords' => 'required',
            'app_meta_description'  => 'required',
            'app_email'         => 'required',
            'app_contact_number'=> 'required',    
            'app_primary_color' => 'required',
            'play_store_link'   => 'required',
            'app_store_link'    => 'required',
            'map_key'           => 'required',
        ];
    }
}