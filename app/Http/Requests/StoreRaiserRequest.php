<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRaiserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Farmer Validation
            'farmer' => 'nullable|array',
            'farmer.name' => 'required|string|max:255', 
            'farmer.contact_number' => 'nullable|string|max:20',
            'farmer.facebook_email' => 'nullable|email|max:255',
            'farmer.home_address' => 'nullable|string|max:255',
            'farmer.farm_address' => 'nullable|string|max:255',
            'farmer.farm_location_latitude' => 'nullable|numeric',
            'farmer.farm_location_longitude' => 'nullable|numeric',
            'farmer.market_addict_location' => 'nullable|string|max:255',
            'farmer.buyer_name' => 'nullable|string|max:255',
            'farmer.association_organization' => 'nullable|string|max:255',

            // Raiser Validation
            'raisers' => 'nullable|array',
            'raisers.*.farmer_id' => 'nullable|exists:farmers,farmer_id',
            'raisers.location' => 'required|string|max:255',
            'raisers.updated_by' => 'required|string|max:255',
            'raisers.remarks' => 'nullable|string|max:255',

            // Livestock Records Validation
            'livestock' => 'nullable|array',
            'livestock.*.raiser_id' => 'nullable|exists:raisers,raiser_id',
            'livestock.*.animal_type' => 'required_with:livestock|string|max:255',
            'livestock.*.subcategory' => 'nullable|string|max:255',
            'livestock.*.quantity' => 'required_with:livestock|integer|min:1',
        ];
    }
}
