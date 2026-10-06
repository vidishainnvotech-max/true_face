<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:tenants,id',
            ],

            'code' => [
                'sometimes',
                'string',
                'max:120',
            ],

            'name' => [
                'sometimes',
                'string',
                'max:160',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
            ],

            'module' => [
                'sometimes',
                'string',
                'max:80',
            ],

            'action' => [
                'sometimes',
                'string',
                'max:40',
            ],

            'status' => [
                'sometimes',
                'string',
                'max:24',
                Rule::in(['active', 'archived']),
            ],
        ];
    }
}