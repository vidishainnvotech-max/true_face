<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Get all permissions.
     */
    public function getAll(): Collection
    {
        return Permission::query()
            ->latest()
            ->get();
    }

    /**
     * Get permission by public ID.
     */
    public function getById(string $publicId): ?Permission
    {
        return Permission::where(
            'public_id',
            $publicId
        )->first();
    }

    /**
     * Create permission.
     */
    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    /**
     * Update permission.
     */
    public function update(
        Permission $permission,
        array $data
    ): bool {
        return $permission->update($data);
    }

    /**
     * Delete permission.
     */
    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}