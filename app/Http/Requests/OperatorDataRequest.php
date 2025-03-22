<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperatorDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'farmer_id'  => 'required|integer|exists:farmers,farmer_id', 
            'fishpond_location'    => 'required|string|max:255',
            'geotagged_photo_url'  => 'nullable|string|max:255',
            'cultured_species'     => 'required|string|max:255',
            'productive_area_sqm'  => 'nullable|numeric|min:0',
            'stocking_density'     => 'nullable|numeric|min:0',
            'date_of_stocking'     => 'nullable|date',
            'production_kg'        => 'nullable|numeric|min:0',
            'date_of_harvest'      => 'nullable|date',
            'operational_status'   => 'required|string|max:255',
            'remarks'              => 'nullable|string|max:500',
        ];
    }
}
