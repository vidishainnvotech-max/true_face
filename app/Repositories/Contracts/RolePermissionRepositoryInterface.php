<?php

namespace App\Repositories\Contracts;

use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Collection;

interface RolePermissionRepositoryInterface
{
    /**
     * Assign permission to role.
     */
    public function create(array $data): RolePermission;

    /**
     * Get all permissions assigned to a role.
     */
    public function getByRole(int $roleId): Collection;

    /**
     * Remove permission from role.
     */
    public function delete(int $roleId, int $permissionId): bool;
}