<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RaiserDataRequest extends FormRequest
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
        if (request()->routeIs('raiser.store')) {
            return [
                'farmer_id'         => 'required|integer',
                'species' => 'required|string|max:255',
                'remarks' => 'required|string|max:255',
            ];
        } elseif (request()->routeIs('raiser.edit')) {
            return [
                'farmer_id'         => 'required|integer',
                'species' => 'required|string|max:255',
                'remarks' => 'required|string|max:255',
            ];
        }
    
        return [
            'farmer_id'         => 'nullable|integer',
            'species' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ];
    }
}
