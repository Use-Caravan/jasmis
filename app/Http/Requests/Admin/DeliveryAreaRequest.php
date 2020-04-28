<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\DeliveryArea;
use App\Language;


class DeliveryAreaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {     
        
        $method = $this->method();
        $uniqueKey = $this->route('delivery_area');
        $formData = $this->request;
        //$particulars = $this->request->get('value');        
        $model = new DeliveryArea();
        $tableName = $model->getTable();
        $tableKey = $model::uniqueKey();
        $languageCount = Language::getActiveCount();

        return [
            'delivery_area_name.*' => 'required',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'zone_type' => 'required|numeric',
            'circle_latitude' => 'nullable|required_if:zone_type,'.DELIVERY_AREA_ZONE_CIRCLE,
            'circle_longitude' => 'nullable|required_if:zone_type,'.DELIVERY_AREA_ZONE_CIRCLE,
            'zone_radius' => 'nullable|required_if:zone_type,'.DELIVERY_AREA_ZONE_CIRCLE,
            'zone_latlng' => 'nullable|required_if:zone_type,'.DELIVERY_AREA_ZONE_POLYGON,
        ];
    }
}