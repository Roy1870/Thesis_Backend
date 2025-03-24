<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FarmerDataRequest extends FormRequest
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
        $rules = [
            'name'                      => 'nullable|string|max:255',
            'contact_number'            => 'nullable|string|max:20',
            'facebook_email'            => 'nullable|email|max:255',
            'home_address'              => 'nullable|string|max:255',
            'farm_address'              => 'nullable|string|max:255',
            'farm_location_longitude'   => 'nullable|numeric|between:-180,180',
            'farm_location_latitude'    => 'nullable|numeric|between:-90,90',
            'market_outlet_location'    => 'nullable|string|max:255',
            'buyer_name'                => 'nullable|string|max:255',
            'association_organization'  => 'nullable|string|max:255',
            'barangay'  => 'nullable|string|max:255',
        ];

        if ($this->isMethod('post')) { // For storing a new farmer
            $rules['name']           = 'required|string|max:255';
            $rules['contact_number'] = 'required|string|max:20';
        }

        return $rules;
    }
}
