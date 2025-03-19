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
    return [
        'farmer_id'  => 'required|integer|exists:farmers,farmer_id', // Ensures it's an integer
        'location'   => 'required|string|max:255',
        'updated_by' => 'required|string|max:255',
        'remarks'    => 'nullable|string|max:255',
    ];
}

}
