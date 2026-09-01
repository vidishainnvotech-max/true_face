<?php

namespace App\Repositories\Eloquent;

use App\Models\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;

class TenantRepository implements TenantRepositoryInterface
{
    public function getAll()
    {
        return Tenant::latest()->paginate(20);
    }

    public function getById(string $publicId): ?Tenant
    {
        return Tenant::where('public_id', $publicId)->first();
    }

    public function create(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function update(Tenant $tenant, array $data): bool
    {
        return $tenant->update($data);
    }

    public function delete(Tenant $tenant): bool
    {
        return $tenant->delete();
    }
}