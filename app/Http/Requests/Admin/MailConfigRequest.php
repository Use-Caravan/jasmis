<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MailConfigRequest extends FormRequest
{    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'smtp_host'         => 'required',
            'smtp_username'     => 'required|email',
            'smtp_password'     => 'required',
            'encryption'        => 'required',
            'port'              => 'required|numeric|min:3',
            'is_smtp_enabled'   => 'required',
        ];
    }
}
