<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiceDataRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rice' => 'required|array',
         
            'rice.*.area_type' => 'required|string|max:255',
            'rice.*.seed_type' => 'nullable|string|max:255',
            'rice.*.area_harvested' => 'nullable|integer|min:0',
            'rice.*.production' => 'nullable|integer|min:0',
            'rice.*.ave_yield' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'rice.required' => 'Rice data is required.',
           
            'rice.*.farmer_id.exists' => 'The provided Farmer ID does not exist.',
            'rice.*.area_type.required' => 'Rice area type is required.',
        ];
    }
}
