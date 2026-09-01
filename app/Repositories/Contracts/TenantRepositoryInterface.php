<?php

namespace App\Repositories\Contracts;

use App\Models\Tenant;

interface TenantRepositoryInterface
{
    public function getAll();

    public function getById(string $publicId): ?Tenant;

    public function create(array $data): Tenant;

    public function update(Tenant $tenant, array $data): bool;

    public function delete(Tenant $tenant): bool;
}