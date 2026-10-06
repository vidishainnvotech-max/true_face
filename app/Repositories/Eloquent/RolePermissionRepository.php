<?php

namespace App\Repositories\Eloquent;

use App\Models\RolePermission;
use App\Repositories\Contracts\RolePermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    /**
     * Assign permission to role.
     */
    public function create(array $data): RolePermission
    {
        return RolePermission::create($data);
    }

    /**
     * Get all permissions assigned to a role.
     */
    public function getByRole(int $roleId): Collection
    {
        return RolePermission::query()
            ->where('role_id', $roleId)
            ->latest()
            ->get();
    }

    /**
     * Remove permission from role.
     */
    public function delete(int $roleId, int $permissionId): bool
    {
        return RolePermission::query()
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->delete() > 0;
    }
}