<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    /**
     * Display all permissions.
     */
    public function index(): JsonResponse
    {
        $permissions = $this->permissionService->getAll();

        return response()->json([
            'success' => true,
            'message' => 'Permissions fetched successfully.',
            'data' => PermissionResource::collection($permissions),
        ]);
    }

    /**
     * Store a new permission.
     */
    public function store(
        StorePermissionRequest $request
    ): JsonResponse {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Generate Public ID
        |--------------------------------------------------------------------------
        */

        $data['public_id'] = (string) Str::ulid();

        /*
        |--------------------------------------------------------------------------
        | Default Status
        |--------------------------------------------------------------------------
        */

        $data['status'] = $data['status'] ?? 'active';

        /*
        |--------------------------------------------------------------------------
        | Created By User
        |--------------------------------------------------------------------------
        */

        $data['created_by_user_id'] = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Create Permission
        |--------------------------------------------------------------------------
        */

        $permission = $this->permissionService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully.',
            'data' => new PermissionResource($permission),
        ], 201);
    }

    /**
     * Display a single permission.
     */
    public function show(string $permission): JsonResponse
    {
        $permissionModel = $this->permissionService->getById($permission);

        if (!$permissionModel) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission fetched successfully.',
            'data' => new PermissionResource($permissionModel),
        ]);
    }

    /**
     * Update a permission.
     */
    public function update(
        UpdatePermissionRequest $request,
        string $permission
    ): JsonResponse {
        $permissionModel = $this->permissionService->getById($permission);

        if (!$permissionModel) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.',
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
        | Update Permission
        |--------------------------------------------------------------------------
        */

        $this->permissionService->update(
            $permissionModel,
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully.',
            'data' => new PermissionResource(
                $permissionModel->fresh()
            ),
        ]);
    }

    /**
     * Delete a permission.
     */
    public function destroy(string $permission): JsonResponse
    {
        $permissionModel = $this->permissionService->getById($permission);

        if (!$permissionModel) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Permission
        |--------------------------------------------------------------------------
        */

        $this->permissionService->delete($permissionModel);

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully.',
        ]);
    }
}