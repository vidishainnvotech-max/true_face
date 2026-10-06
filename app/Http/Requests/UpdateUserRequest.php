<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make the request.
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
            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */
            'tenant_id' => [
                'sometimes',
                'integer',
                'exists:tenants,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | User Details
            |--------------------------------------------------------------------------
            */
            'username' => [
                'sometimes',
                'string',
                'max:100',
            ],

            'name' => [
                'sometimes',
                'string',
                'max:160',
            ],

            'email' => [
                'sometimes',
                'email',
                'max:190',
            ],

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */
            'password' => [
                'sometimes',
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            'status' => [
                'sometimes',
                'nullable',
                'string',
                'max:24',
            ],

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */
            'role_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:roles,id',
            ],
        ];
    }
}