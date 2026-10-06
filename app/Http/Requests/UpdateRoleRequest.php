<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:160',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'scope_level' => [
                'sometimes',
                Rule::in([
                    'system',
                    'tenant',
                    'company',
                    'location',
                    'department',
                ]),
            ],

            'is_assignable' => [
                'sometimes',
                'boolean',
            ],

            'priority' => [
                'sometimes',
                'integer',
                'min:0',
                'max:65535',
            ],

            'status' => [
                'sometimes',
                'string',
                'max:24',
                Rule::in([
                    'active',
                    'archived',
                ]),
            ],
        ];
    }
}