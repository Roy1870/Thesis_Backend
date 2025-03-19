<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivestockRecordsDataRequest extends FormRequest
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
            'raiser_id'    => 'required|exists:raisers,raiser_id', // Ensures raiser_id exists
            'animal_type'  => 'required|string|max:255',
            'subcategory'  => 'required|string|max:255',
            'quantity'     => 'required|integer|min:1', // Ensures quantity is a positive integer
        ];
    }
}
