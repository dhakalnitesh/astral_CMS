<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee'); // get ID from route for unique validation

        return [
            'full_name' => ['required', 'string', 'max:255'],

            'email' => ['required', 'email', Rule::unique('employees', 'email')->ignore($employeeId)],
            'phone' => ['required', 'string', 'max:20'],

            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string'],

            'designation' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],

            'joining_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:joining_date'],

            'salary' => ['nullable', 'numeric', 'min:0'],
            'experience_years' => ['nullable', 'integer', 'min:0'],

            'province_id' => ['nullable', 'exists:provinces,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'municipal_id' => ['nullable', 'exists:municipals,id'],

            'address_line' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full Name is required.',
            'full_name.string' => 'Full Name must be a string.',
            'full_name.max' => 'Full Name cannot exceed 255 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already taken.',

            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',

            'designation.required' => 'Designation is required.',
            'joining_date.required' => 'Joining date is required.',
            'salary.required' => 'Salary is required.',
            'experience_years.required' => 'Experience is required.',
        ];
    }
}