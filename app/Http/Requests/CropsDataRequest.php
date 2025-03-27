<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CropsDataRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'crops' => 'required|array',
            'crops.*.crop_type' => 'required|string|max:255',
            'crops.*.variety_clone' => 'nullable|string|max:255',
            'crops.*.area_hectare' => 'nullable|numeric|min:0',
            'crops.*.production_type' => 'nullable|string|max:255',
            'crops.*.production_data' => 'nullable|json',
        ];
    }

    public function messages()
    {
        return [
            'crops.required' => 'Crops data is required.',
         
            'crops.*.farmer_id.exists' => 'The provided Farmer ID does not exist.',
            'crops.*.crop_type.required' => 'Crop type is required for each crop.',
        ];
    }
}
