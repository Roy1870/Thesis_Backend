<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperatorRequest extends FormRequest
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

            // Operator Validation
            'operator' => 'required|array',
            'operator.farmer_id' => 'nullable|exists:farmers,farmer_id',
            'operator.fishpond_location' => 'required|string|max:255',
            'operator.geotagged_photo_url' => 'nullable|string|max:255',
            'operator.cultured_species' => 'required|string|max:255',
            'operator.productive_area_sqm' => 'nullable|numeric|min:0',
            'operator.stocking_density' => 'nullable|numeric|min:0',
            'operator.date_of_stocking' => 'nullable|date',
            'operator.production_kg' => 'nullable|numeric|min:0',
            'operator.date_of_harvest' => 'nullable|date',
            'operator.operational_status' => 'nullable|string|max:255',
            'operator.remarks' => 'nullable|string|max:255',
        ];
    }
}
