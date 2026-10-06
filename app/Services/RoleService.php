<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {}

    /**
     * Get all roles
     */
    public function getAll(): Collection
    {
        return $this->roleRepository->getAll();
    }

    /**
     * Get role by public ID
     */
    public function getById(string $publicId): ?Role
    {
        return $this->roleRepository->getById($publicId);
    }

    /**
     * Create role
     */
    public function create(array $data): Role
    {
        return $this->roleRepository->create($data);
    }

    /**
     * Update role
     */
    public function update(Role $role, array $data): bool
    {
        return $this->roleRepository->update($role, $data);
    }

    /**
     * Delete role
     */
    public function delete(Role $role): bool
    {
        return $this->roleRepository->delete($role);
    }
}