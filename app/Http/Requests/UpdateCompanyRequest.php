<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id;

        return [
            'tenant_id' => 'required|exists:tenants,id|unique:companies,tenant_id,' . $companyId,

            'code' => 'required|string|max:32|unique:companies,code,' . $companyId,

            'name' => 'required|string|max:160',

            'legal_name' => 'nullable|string|max:200',

            'trade_name' => 'nullable|string|max:160',

            'slug' => 'nullable|string|max:160|unique:companies,slug,' . $companyId,

            'email' => 'nullable|email|max:190',

            'phone' => 'nullable|string|max:32',

            'website' => 'nullable|string|max:255',

            'timezone' => 'nullable|string|max:64',

            'default_currency' => 'nullable|string|size:3',

            'country_code' => 'nullable|string|size:2',

            'fiscal_year_start_month' => 'nullable|integer|min:1|max:12',

            'status' => 'nullable|in:active,inactive,suspended',

            'settings' => 'nullable|array',

            'effective_from' => 'nullable|date',

            'effective_to' => 'nullable|date|after_or_equal:effective_from',
        ];
    }
}