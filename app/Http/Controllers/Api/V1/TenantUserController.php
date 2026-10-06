<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantUserController extends Controller
{
    /**
     * Create a user for a specific tenant.
     */
    public function store(
        StoreUserRequest $request,
        Tenant $tenant
    ): JsonResponse {
        $data = $request->validated();

        return DB::transaction(function () use ($data, $tenant) {

            /*
            |--------------------------------------------------------------------------
            | Find and Validate Role
            |--------------------------------------------------------------------------
            */

            $role = Role::query()
                ->where('id', $data['role_id'])
                ->where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->where('is_assignable', true)
                ->first();

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or non-assignable role for this tenant.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'tenant_id' => $tenant->id,
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => $data['status'] ?? 'active',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Assign Role
            |--------------------------------------------------------------------------
            */

            $user->roles()->attach($role->id, [
                'tenant_id' => $tenant->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Load Role
            |--------------------------------------------------------------------------
            */

            $user->load('roles');

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' => 'Tenant user created successfully.',
                'data' => [
                    'id' => $user->id,
                    'tenant_id' => $user->tenant_id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,

                    'roles' => $user->roles->map(function ($role) {
                        return [
                            'id' => $role->id,
                            'public_id' => $role->public_id,
                            'code' => $role->code,
                            'name' => $role->name,
                            'scope_level' => $role->scope_level,
                            'status' => $role->status,
                        ];
                    })->values(),
                ],
            ], 201);
        });
    }
}