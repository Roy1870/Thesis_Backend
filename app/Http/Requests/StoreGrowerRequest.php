<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrowerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change if you need authorization logic
    }

    public function rules()
    {
        return [
            'farmer_id' => 'required|exists:farmers,farmer_id',

            'crops' => 'nullable|array',
            'crops.*.crop_type' => 'required_with:crops|string',
            'crops.*.variety_clone' => 'nullable|string',
            'crops.*.area_hectare' => 'required_with:crops|numeric',
            'crops.*.production_type' => 'required_with:crops|string',
            'crops.*.production_data' => 'nullable|json',

            'rice' => 'nullable|array',
            'rice.*.area_type' => 'required_with:rice|string',
            'rice.*.seed_type' => 'required_with:rice|string',
            'rice.*.area_harvested' => 'required_with:rice|integer',
            'rice.*.production' => 'required_with:rice|integer',
            'rice.*.ave_yield' => 'required_with:rice|integer'
        ];
    }
}
