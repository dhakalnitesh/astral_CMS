<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Service info
            'customer_id' => 'required'|'exists:customers,id',
            'start_date' => 'required'|'date',
            'end_date' => 'nullable'|'date'|'after_or_equal:start_date',

        
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required.',
            // 'projects.required' => 'At least one project is required.',
            // 'categories.required' => 'Category is required.',
        ];
    }
}