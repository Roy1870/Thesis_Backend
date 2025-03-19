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
            'farmer_id'  => 'required|integer|exists:farmers,farmer_id', 
            'created_at'   => 'nullable|date',
            'updated_at'   => 'nullable|date',
        ];
    }
}
