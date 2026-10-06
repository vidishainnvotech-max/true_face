<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    /**
     * Display all roles.
     */
    public function index(): JsonResponse
    {
        $roles = $this->roleService->getAll();

        return response()->json([
            'success' => true,
            'message' => 'Roles fetched successfully.',
            'data' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Store a new role.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Generate Public ID
        |--------------------------------------------------------------------------
        */

        $data['public_id'] = (string) Str::ulid();

        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $data['is_assignable'] = $data['is_assignable'] ?? true;
        $data['priority'] = $data['priority'] ?? 100;
        $data['status'] = $data['status'] ?? 'active';

        /*
        |--------------------------------------------------------------------------
        | Tenant Scope
        |--------------------------------------------------------------------------
        |
        | For tenant roles, tenant_scope_id will be the tenant_id.
        |
        */

        $data['tenant_scope_id'] = $data['tenant_id'];

        /*
        |--------------------------------------------------------------------------
        | Created By User
        |--------------------------------------------------------------------------
        */

        $data['created_by_user_id'] = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Create Role
        |--------------------------------------------------------------------------
        */

        $role = $this->roleService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
            'data' => new RoleResource($role),
        ], 201);
    }

    /**
     * Display a single role.
     */
    public function show(string $role): JsonResponse
    {
        $roleModel = $this->roleService->getById($role);

        if (!$roleModel) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role fetched successfully.',
            'data' => new RoleResource($roleModel),
        ]);
    }

    /**
     * Update a role.
     */
    public function update(
        UpdateRoleRequest $request,
        string $role
    ): JsonResponse {
        $roleModel = $this->roleService->getById($role);

        if (!$roleModel) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.',
            ], 404);
        }

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Updated By User
        |--------------------------------------------------------------------------
        */

        $data['updated_by_user_id'] = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Update Tenant Scope
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('tenant_id', $data)) {
            $data['tenant_scope_id'] = $data['tenant_id'];
        }

        /*
        |--------------------------------------------------------------------------
        | Update Role
        |--------------------------------------------------------------------------
        */

        $this->roleService->update($roleModel, $data);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'data' => new RoleResource($roleModel->fresh()),
        ]);
    }

    /**
     * Delete a role.
     */
    public function destroy(string $role): JsonResponse
    {
        $roleModel = $this->roleService->getById($role);

        if (!$roleModel) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Role
        |--------------------------------------------------------------------------
        */

        $this->roleService->delete($roleModel);

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.',
        ]);
    }
}