<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FarmerDataRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'rsbsa_id' => 'nullable|string|max:255',
            'facebook_email' => 'nullable|string|max:255',
            'home_address' => 'nullable|string|max:500',
            'farm_address' => 'nullable|string|max:500',
            'farm_location_longitude' => 'nullable|numeric',
            'farm_location_latitude' => 'nullable|numeric',
            'market_outlet_location' => 'nullable|string|max:500',
            'buyer_name' => 'nullable|string|max:255',
            'association_organization' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
    
            // Crops validation
            'crops' => 'nullable|array',
            'crops.*.farmer_id' => 'nullable|exists:farmers,farmer_id',
            'crops.*.crop_type' => 'nullable|string|max:255',
            'crops.*.variety_clone' => 'nullable|string|max:255',
            'crops.*.area_hectare' => 'nullable|numeric|min:0',
            'crops.*.production_type' => 'nullable|string|max:255',
            'crops.*.production_data' => 'nullable|json',
    
            // Rice validation
            'rice' => 'nullable|array',
            'rice.*.farmer_id' => 'nullable|exists:farmers,farmer_id',
            'rice.*.area_type' => 'nullable|string|max:255',
            'rice.*.seed_type' => 'nullable|string|max:255',
            'rice.*.area_harvested' => 'nullable|integer|min:0',
            'rice.*.production' => 'nullable|integer|min:0',
            'rice.*.ave_yield' => 'nullable|integer|min:0',
        ];
    }
    

    public function messages()
    {
        return [
            'name.required' => 'Farmer name is required.',
            'facebook_email.email' => 'Please enter a valid email address.',
            'facebook_email.unique' => 'This email is already taken.',
            'crops.*.crop_type.required' => 'Crop type is required for each crop.',
            'rice.*.area_type.required' => 'Rice area type is required.',
        ];
    }
}
