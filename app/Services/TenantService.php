<?php

namespace App\Services;

use App\Models\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;

class TenantService
{
    public function __construct(
        protected TenantRepositoryInterface $tenantRepository
    ) {}

    public function getAll()
    {
        return $this->tenantRepository->getAll();
    }

    public function getById(string $publicId): ?Tenant
    {
        return $this->tenantRepository->getById($publicId);
    }

    public function create(array $data): Tenant
    {
        return $this->tenantRepository->create($data);
    }

    public function update(Tenant $tenant, array $data): bool
    {
        return $this->tenantRepository->update($tenant, $data);
    }

    public function delete(Tenant $tenant): bool
    {
        return $this->tenantRepository->delete($tenant);
    }
}