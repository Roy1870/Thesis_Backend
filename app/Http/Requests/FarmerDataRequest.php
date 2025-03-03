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
        if (request()->routeIs('farmer.store')) {
            return [
                'fname'         => 'required|string|max:255',
                'lname'         => 'required|string|max:255',
                'email'         => 'required|string|max:255',
                'home_address'  => 'required|string|max:255',
                'farm_address'  => 'required|string|max:255',
            ];
        } elseif (request()->routeIs('farmer.edit')) {
            return [
                'fname'         => 'nullable|string|max:255',
                'lname'         => 'nullable|string|max:255',
                'email'         => 'nullable|string|max:255',
                'home_address'  => 'nullable|string|max:255',
                'farm_address'  => 'nullable|string|max:255',
            ];
        }
    
        return [
            'fname'         => 'nullable|string|max:255',
            'lname'         => 'nullable|string|max:255',
            'email'         => 'nullable|string|max:255',
            'home_address'  => 'nullable|string|max:255',
            'farm_address'  => 'nullable|string|max:255',
        ];
    }
    
}

