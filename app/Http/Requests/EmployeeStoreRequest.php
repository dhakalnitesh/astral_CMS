<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Authorize request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [

            'full_name' => 'required|string|max:255',

            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',

            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',

            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',

            'joining_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:joining_date',

            'salary' => 'nullable|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0',

            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'municipal_id' => 'nullable|exists:municipals,id',
            'tole'=>'nullable',

            'address_line' => 'nullable|string|max:255',
            'notes' => 'nullable|string',

        ];
    }

    public function messages(): array
{
    return [
        'full_name.required' => 'First name is required',
        'email.unique' => 'This email already exists',
        'phone.required'=>'Contact number is required',
        'end_date.after_or_equal' => 'End date must be after joining date',
    ];
}
}