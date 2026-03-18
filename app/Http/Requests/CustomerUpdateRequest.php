<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:individual,business',
            'name' => 'required_if:type,individual|string|max:255',
            // 'email' => 'required|email|unique:customers,email,' . $this->route('customer'), // Excluding the current customer's email from being unique
            'email'=> 'required',
            'phone' => 'nullable|string|max:255',
            'alternate_phone' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'company_name' => 'nullable|string|max:255',
            'company_registration_number' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'municipal_id' => 'nullable|exists:municipals,id',
            'street' => 'nullable|string|max:255',
            'tole' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required_if' => 'Full name is required for individual customers.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'category_id.exists' => 'The selected category is invalid.',
            'product_id.exists' => 'The selected product is invalid.',
            'province_id.exists' => 'The selected province is invalid.',
            'district_id.exists' => 'The selected district is invalid.',
            'municipal_id.exists' => 'The selected municipal is invalid.',
        ];
    }
}