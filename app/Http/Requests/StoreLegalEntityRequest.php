<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLegalEntityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = $this->input('tenant_id');

        return [
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],

            'company_id' => [
                'required',
                'integer',
                Rule::exists('companies', 'id')
                    ->where(function ($query) use ($tenantId) {
                        $query->where('tenant_id', $tenantId);
                    }),
            ],

            'code' => [
                'required',
                'string',
                'max:32',
                'unique:legal_entities,code',
            ],

            'legal_name' => [
                'required',
                'string',
                'max:200',
            ],

            'registration_number' => [
                'nullable',
                'string',
                'max:80',
            ],

            'tax_identifier' => [
                'nullable',
                'string',
                'max:80',
            ],

            'email' => [
                'nullable',
                'email',
                'max:190',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:32',
            ],

            'address_line_1' => [
                'nullable',
                'string',
                'max:190',
            ],

            'address_line_2' => [
                'nullable',
                'string',
                'max:190',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state_code' => [
                'nullable',
                'string',
                'max:32',
            ],

            'country_code' => [
                'nullable',
                'string',
                'size:2',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'status' => [
                'nullable',
                'string',
                'max:24',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'effective_to' => [
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],

            'settings' => [
                'nullable',
                'array',
            ],
        ];
    }
}