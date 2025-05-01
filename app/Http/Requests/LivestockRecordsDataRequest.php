<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivestockRecordsDataRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Farmer validation
            'farmer_id' => 'nullable|exists:farmers,farmer_id', // Nullable for new farmers
            'name' => 'required_without:farmer_id|string|max:255', // Required if no farmer_id is provided
            'contact_number' => 'nullable|string|max:20',
            'facebook_email' => 'nullable|string|max:255',
            'home_address' => 'nullable|string|max:500',
            'farm_address' => 'nullable|string|max:500',
            'barangay' => 'nullable|string|max:255',

            // Livestock records validation
            'livestock_records' => 'required|array|min:1',
            'livestock_records.*.animal_type' => 'required|string|max:255',
            'livestock_records.*.subcategory' => 'nullable|string|max:255',
            'livestock_records.*.quantity' => 'required|integer|min:1',
            'livestock_records.*.updated_by' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'farmer_id.exists' => 'The provided Farmer ID does not exist.',
            'name.required_without' => 'Farmer name is required when creating a new farmer.',
            'livestock_records.required' => 'At least one livestock record is required.',
            'livestock_records.array' => 'Livestock records must be an array.',
            'livestock_records.*.animal_type.required' => 'Animal type is required.',
            'livestock_records.*.quantity.required' => 'Quantity is required.',
            'livestock_records.*.quantity.integer' => 'Quantity must be a whole number.',
            'livestock_records.*.quantity.min' => 'Quantity must be at least 1.',
            'livestock_records.*.updated_by.required' => 'Updated by field is required.',       
        ];
    }
}
