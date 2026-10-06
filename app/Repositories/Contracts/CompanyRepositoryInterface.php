<?php

namespace App\Repositories\Contracts;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyRepositoryInterface
{
    public function getAll(): Collection;

    public function getById(string $publicId): ?Company;

    public function create(array $data): Company;

    public function update(Company $company, array $data): bool;

    public function delete(Company $company): bool;
}