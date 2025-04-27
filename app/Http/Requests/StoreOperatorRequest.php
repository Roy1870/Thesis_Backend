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
            'farmer_id' => 'nullable|exists:farmers,farmer_id', // Nullable for new farmers
            'name' => 'required_without:farmer_id|string|max:255', // Required if no farmer_id is provided
            'contact_number' => 'nullable|string|max:20',
            'rsbsa_id' => 'nullable|string|max:255',
            'facebook_email' => 'nullable|string|max:255',
            'home_address' => 'nullable|string|max:500',
            'barangay' => 'nullable|string|max:255',

            // Operator Validation
            'operators' => 'required|array|min:1',
            'operators.*.fishpond_location' => 'required|string|max:255',
            'operators.*.geotagged_photo_url' => 'nullable|string|max:255',
            'operators.*.cultured_species' => 'required|string|max:255',
            'operators.*.productive_area_sqm' => 'nullable|numeric|min:0',
            'operators.*.stocking_density' => 'nullable|numeric|min:0',
            'operators.*.date_of_stocking' => 'nullable|date',
            'operators.*.production_kg' => 'nullable|numeric|min:0',
            'operators.*.date_of_harvest' => 'nullable|date',
            'operators.*.operational_status' => 'nullable|string|max:255',
            'operators.*.remarks' => 'nullable|string|max:255',
        ];
    }
}
