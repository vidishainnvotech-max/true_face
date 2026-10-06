<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    /**
     * Get all permissions.
     */
    public function getAll(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    /**
     * Get permission by public ID.
     */
    public function getById(string $publicId): ?Permission
    {
        return $this->permissionRepository->getById($publicId);
    }

    /**
     * Create permission.
     */
    public function create(array $data): Permission
    {
        return $this->permissionRepository->create($data);
    }

    /**
     * Update permission.
     */
    public function update(
        Permission $permission,
        array $data
    ): bool {
        return $this->permissionRepository->update(
            $permission,
            $data
        );
    }

    /**
     * Delete permission.
     */
    public function delete(Permission $permission): bool
    {
        return $this->permissionRepository->delete($permission);
    }
}