<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRolePermissionRequest;
use App\Models\RolePermission;
use App\Services\RolePermissionService;
use Illuminate\Http\JsonResponse;

class RolePermissionController extends Controller
{
    public function __construct(
        protected RolePermissionService $rolePermissionService
    ) {}

    /**
     * Assign permission to a role.
     */
    public function store(
        AssignRolePermissionRequest $request
    ): JsonResponse {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate role-permission assignment
        |--------------------------------------------------------------------------
        */

        $exists = RolePermission::query()
            ->where('tenant_id', $data['tenant_id'])
            ->where('role_id', $data['role_id'])
            ->where('permission_id', $data['permission_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Permission is already assigned to this role.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Role Permission
        |--------------------------------------------------------------------------
        */

        $rolePermission = $this->rolePermissionService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Permission assigned to role successfully.',
            'data' => $rolePermission,
        ], 201);
    }

    /**
     * Get all permissions assigned to a role.
     */
    public function index(int $role): JsonResponse
    {
        $rolePermissions = $this->rolePermissionService
            ->getByRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Role permissions fetched successfully.',
            'data' => $rolePermissions,
        ]);
    }

    /**
     * Remove permission from a role.
     */
    public function destroy(
        int $role,
        int $permission
    ): JsonResponse {
        $deleted = $this->rolePermissionService->delete(
            $role,
            $permission
        );

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Role permission assignment not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission removed from role successfully.',
        ]);
    }
}