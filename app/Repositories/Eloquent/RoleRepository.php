<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAll(): Collection
    {
        return Role::query()
            ->latest()
            ->get();
    }

    public function getById(string $publicId): ?Role
    {
        return Role::where('public_id', $publicId)->first();
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}