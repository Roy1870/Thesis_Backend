<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CropsDataRequest extends FormRequest
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
            'grower_id'        => 'required|exists:growers,grower_id', // Ensures grower_id exists
            'crop_type'        => 'required|string|max:255',
            'variety_clone'    => 'nullable|string|max:255',
            'area_hectare'     => 'required|numeric|min:0', // Ensures valid numeric input
            'production_type'  => 'required|string|max:255',
            'production_data'  => 'required|json', // Ensures JSON format
        ];
    }
}
