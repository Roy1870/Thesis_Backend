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
        if (request()->routeIs('operator.store')) {
            return [
                'farmer_id'         => 'required|integer',
                'fishpond_location' => 'required|string|max:255',
                'cultured_species' => 'required|string|min:1',
                'productive_area' => 'required|integer|min:1',
                'stocking_density' => 'required|integer|max:255',
                'production' => 'required|integer|max:255',
                'harvest_date' => 'required|string|max:255',
                'month' => 'required|string|max:255',
                'year' => 'required|integer|max:255',
            ];
        } elseif (request()->routeIs('operator.edit')) {
            return [
                'farmer_id'         => 'nullable|integer',
                'fishpond_location' => 'required|string|max:255',
                'cultured_species' => 'required|string|min:1',
                'productive_area' => 'required|integer|min:1',
                'stocking_density' => 'required|integer|max:255',
                'production' => 'required|integer|max:255',
                'harvest_date' => 'required|string|max:255',
                'month' => 'required|string|max:255',
                'year' => 'required|integer|max:255',
            ];
        }
    
        return [
            'farmer_id'         => 'nullable|integer',
            'fishpond_location' => 'nullable|string|max:255',
            'cultured_species' => 'nullable|string|min:1',
            'productive_area' => 'nullable|integer|min:1',
            'stocking_density' => 'nullable|integer|max:255',
            'production' => 'nullable|integer|max:255',
            'harvest_date' => 'nullable|string|max:255',
            'month' => 'nullable|string|max:255',
            'year' => 'nullable|integer|max:255',
        ];
    }
}
