<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaConfigRequest extends FormRequest
{    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'social_facebook'          => 'required|weburl',
            'social_instagram'         => 'required|weburl',
            'social_twitter'           => 'required|weburl',    
        ];
    }
}
