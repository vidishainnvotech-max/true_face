<?php

namespace App\Services;

use App\Models\RolePermission;
use App\Repositories\Contracts\RolePermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionService
{
    public function __construct(
        protected RolePermissionRepositoryInterface $rolePermissionRepository
    ) {}

    /**
     * Assign permission to role.
     */
    public function create(array $data): RolePermission
    {
        return $this->rolePermissionRepository->create($data);
    }

    /**
     * Get all permissions assigned to a role.
     */
    public function getByRole(int $roleId): Collection
    {
        return $this->rolePermissionRepository->getByRole($roleId);
    }

    /**
     * Remove permission from role.
     */
    public function delete(
        int $roleId,
        int $permissionId
    ): bool {
        return $this->rolePermissionRepository->delete(
            $roleId,
            $permissionId
        );
    }
}