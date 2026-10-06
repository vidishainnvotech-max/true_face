<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Repositories\Contracts\TenantRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($data) {

            // Admin data ko tenant data se alag karo
            $adminData = $data['admin'];

            unset($data['admin']);

            // 1. Create Tenant
            $tenant = $this->tenantRepository->create($data);

            // 2. Create Initial Tenant Admin
            User::create([
                'tenant_id' => $tenant->id,
                'username' => $adminData['username'],
                'name' => $adminData['name'],
                'email' => $adminData['email'],
                'password' => $adminData['password'],
                'status' => 'active',
            ]);

            return $tenant;
        });
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