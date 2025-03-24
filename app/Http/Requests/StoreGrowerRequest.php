<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrowerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'farmer' => 'nullable|array',
        'farmer.name' => 'required|string|max:255',
        'farmer.contact_number' => 'nullable|string|max:20',
        'farmer.facebook_email' => 'nullable|email|max:255',
        'farmer.home_address' => 'nullable|string|max:255',
        'farmer.barangay' => 'nullable|string|max:255',
        'farmer.farm_address' => 'nullable|string|max:255',
        'farmer.farm_location_latitude' => 'nullable|numeric',
        'farmer.farm_location_longitude' => 'nullable|numeric',
        'farmer.market_outlet_location' => 'nullable|string|max:255',
        'farmer.buyer_name' => 'nullable|string|max:255',
        'farmer.association_organization' => 'nullable|string|max:255',

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
