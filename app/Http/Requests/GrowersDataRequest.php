<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrowersDataRequest extends FormRequest
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
            'farmer_id'         => 'required|integer',
            'crop_name' => 'required|string|max:255',
            'area_hectares' => 'required|integer|min:1',
            'yield' => 'required|integer|min:1',
            'season' => 'required|string|max:255',
            'market_outlet' => 'required|string|max:255',
        ];
    }
}
