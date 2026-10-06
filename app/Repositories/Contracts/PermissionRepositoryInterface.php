<?php

namespace App\Repositories\Contracts;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

interface PermissionRepositoryInterface
{
    /**
     * Get all permissions.
     */
    public function getAll(): Collection;

    /**
     * Get permission by public ID.
     */
    public function getById(string $publicId): ?Permission;

    /**
     * Create a permission.
     */
    public function create(array $data): Permission;

    /**
     * Update a permission.
     */
    public function update(
        Permission $permission,
        array $data
    ): bool;

    /**
     * Delete a permission.
     */
    public function delete(Permission $permission): bool;
}