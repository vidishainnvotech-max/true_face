<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
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
                'max:96',
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

            'scope_level' => [
                'required',
                Rule::in([
                    'system',
                    'tenant',
                    'company',
                    'location',
                    'department',
                ]),
            ],

            'is_system' => [
                'required',
                'boolean',
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

    /**
     * Additional business validation.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $isSystem = $this->boolean('is_system');
            $tenantId = $this->input('tenant_id');

            // System role => tenant_id must be NULL
            if ($isSystem && !is_null($tenantId)) {
                $validator->errors()->add(
                    'tenant_id',
                    'System role must not have a tenant_id.'
                );
            }

            // Tenant role => tenant_id is required
            if (!$isSystem && is_null($tenantId)) {
                $validator->errors()->add(
                    'tenant_id',
                    'Tenant role must have a tenant_id.'
                );
            }

            // System role should use system scope
            if ($isSystem && $this->input('scope_level') !== 'system') {
                $validator->errors()->add(
                    'scope_level',
                    'System role must have scope_level = system.'
                );
            }

            // Non-system role cannot use system scope
            if (!$isSystem && $this->input('scope_level') === 'system') {
                $validator->errors()->add(
                    'scope_level',
                    'Non-system role cannot have system scope.'
                );
            }
        });
    }
}