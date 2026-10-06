<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display all users.
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAll();

        return response()->json([
            'success' => true,
            'message' => 'Users fetched successfully.',
            'data' => UserResource::collection($users),
        ]);
    }

    /**
     * Store a new user and assign role.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $roleId = $data['role_id'] ?? null;

        unset($data['role_id']);

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        $data['password'] = Hash::make($data['password']);

        /*
        |--------------------------------------------------------------------------
        | Create User + Assign Role
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(function () use ($data, $roleId) {

            $user = $this->userService->create($data);

            if ($roleId !== null) {

                $role = Role::query()
                    ->where('id', $roleId)
                    ->where('tenant_id', $user->tenant_id)
                    ->where('status', 'active')
                    ->where('is_assignable', true)
                    ->first();

                if (!$role) {
                    abort(422, 'Invalid or non-assignable role for this tenant.');
                }

                $user->roles()->syncWithoutDetaching([
                    $role->id => [
                        'tenant_id' => $user->tenant_id,
                    ],
                ]);
            }

            return $user;
        });

        $user->load('roles');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => new UserResource($user),
        ], 201);
    }

    /**
     * Display a single user.
     */
    public function show(int $user): JsonResponse
    {
        $userModel = $this->userService->getById($user);

        if (!$userModel) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User fetched successfully.',
            'data' => new UserResource($userModel),
        ]);
    }

    /**
     * Update a user.
     */
    public function update(
        UpdateUserRequest $request,
        int $user
    ): JsonResponse {
        $userModel = $this->userService->getById($user);

        if (!$userModel) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        */

        $roleId = $data['role_id'] ?? null;

        unset($data['role_id']);

        DB::transaction(function () use ($userModel, $data, $roleId) {

            $this->userService->update($userModel, $data);

            if ($roleId !== null) {

                $role = Role::query()
                    ->where('id', $roleId)
                    ->where('tenant_id', $userModel->tenant_id)
                    ->where('status', 'active')
                    ->where('is_assignable', true)
                    ->first();

                if (!$role) {
                    abort(422, 'Invalid or non-assignable role for this tenant.');
                }

                $userModel->roles()->sync([
                    $role->id => [
                        'tenant_id' => $userModel->tenant_id,
                    ],
                ]);
            }
        });

        $userModel->load('roles');

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => new UserResource($userModel->fresh('roles')),
        ]);
    }

    /**
     * Delete a user.
     */
    public function destroy(int $user): JsonResponse
    {
        $userModel = $this->userService->getById($user);

        if (!$userModel) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        DB::transaction(function () use ($userModel) {

            // Remove role assignments
            $userModel->roles()->detach();

            // Delete user
            $this->userService->delete($userModel);
        });

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}