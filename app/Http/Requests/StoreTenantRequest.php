<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'code' => 'required|string|max:32|unique:tenants,code',
        'name' => 'required|string|max:160',
        'slug' => 'nullable|string|max:160|unique:tenants,slug',
        'status' => 'nullable|in:active,inactive,suspended',
        'isolation_mode' => 'nullable|in:shared,dedicated',
        'default_timezone' => 'nullable|string|max:64',
        'default_locale' => 'nullable|string|max:12',
        'country_code' => 'nullable|string|size:2',
        'data_residency_region' => 'nullable|string|max:64',
        'settings' => 'nullable|array',
    ];
}
}
