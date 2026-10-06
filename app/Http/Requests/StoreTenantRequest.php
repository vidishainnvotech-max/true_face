<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Tenant Details
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

            // Initial Tenant Admin Details
            'admin.username' => 'required|string|max:80',
            'admin.name' => 'required|string|max:255',
            'admin.email' => 'required|email|max:255|unique:users,email',
            'admin.password' => 'required|string|min:8|confirmed',
        ];
    }
}