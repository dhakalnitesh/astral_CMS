<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation.
     */
 

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [

            'type' => [
                'required',
                'in:individual,business'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:customers,email'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20'
            ],

            'company_name' => [
                'required_if:type,business',
                'nullable',
                'string',
                'max:255'
            ],

            'address' => [
                'nullable',
                'string',
                'max:500'
            ],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Customer name is required.',

            'email.email' => 'Please enter a valid email address.',

            'company_name.required_if' =>
            'Company name is required when customer type is Business.',

            'type.required' => 'Please select customer type.',

            'status.required' => 'Customer status is required.',
        ];
    }

    /**
     * Friendly attribute names
     */

}
