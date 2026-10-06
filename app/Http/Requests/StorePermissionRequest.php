<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_id' => [
                'nullable',
                'integer',
                'exists:tenants,id',
            ],

            'code' => [
                'required',
                'string',
                'max:120',
            ],

            'name' => [
                'required',
                'string',
                'max:160',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'module' => [
                'required',
                'string',
                'max:80',
            ],

            'action' => [
                'required',
                'string',
                'max:40',
            ],

            'status' => [
                'nullable',
                'string',
                'max:24',
                Rule::in(['active', 'archived']),
            ],
        ];
    }
}